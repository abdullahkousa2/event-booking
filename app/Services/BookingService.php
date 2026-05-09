<?php

namespace App\Services;

use App\Data\DTOs\BookingDTO;
use App\Exceptions\BookingConflictException;
use App\Exceptions\InsufficientSeatsException;
use App\Models\Booking;
use App\Repositories\Interfaces\BookingRepositoryInterface;
use App\Repositories\Interfaces\EventRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BookingService
{
    public function __construct(
        private readonly BookingRepositoryInterface $bookingRepository,
        private readonly EventRepositoryInterface   $eventRepository,
    ) {}

    /**
     * Core booking logic using Pessimistic Concurrency Control (PCC).
     *
     * Uses SELECT FOR UPDATE to acquire an exclusive row lock on the event,
     * ensuring only one transaction can check and modify available_seats at a time.
     * Scenario: 50 users → 10 seats → exactly 10 succeed.
     */
    public function createBooking(BookingDTO $dto): Booking
    {
        return DB::transaction(function () use ($dto) {
            // Step 1: Acquire exclusive row lock (SELECT ... FOR UPDATE)
            // All concurrent transactions trying the same event BLOCK here until we commit/rollback.
            $event = $this->eventRepository->findWithLock($dto->eventId);

            // Step 2: Check capacity AFTER acquiring lock — reads the up-to-date value
            if ($event->available_seats < $dto->seatsRequested) {
                Log::channel('bookings')->warning('Booking rejected — insufficient seats', [
                    'user_id'    => $dto->userId,
                    'event_id'   => $dto->eventId,
                    'requested'  => $dto->seatsRequested,
                    'available'  => $event->available_seats,
                    'trace_id'   => request()->header('X-Trace-ID'),
                ]);
                throw new InsufficientSeatsException(
                    "Only {$event->available_seats} seat(s) remaining for this event."
                );
            }

            // Step 3: Prevent duplicate booking for same user+event
            if ($this->bookingRepository->findByUserAndEvent($dto->userId, $dto->eventId)) {
                throw new BookingConflictException('You have already booked this event.');
            }

            // Step 4: Create booking record
            $booking = $this->bookingRepository->create($dto);

            // Step 5: Atomically decrement seats using arithmetic SQL update (not read-modify-write)
            // SQL: UPDATE events SET available_seats = available_seats - ? WHERE id = ?
            $this->eventRepository->decrementSeats($dto->eventId, $dto->seatsRequested);

            Log::channel('bookings')->info('Booking confirmed', [
                'booking_ref' => $booking->booking_ref,
                'user_id'     => $dto->userId,
                'event_id'    => $dto->eventId,
                'event_title' => $event->title,
                'seats'       => $dto->seatsRequested,
                'amount'      => $dto->totalAmount,
                'trace_id'    => request()->header('X-Trace-ID'),
            ]);

            return $booking->load(['event', 'payment']);
        });
        // Lock is released here on COMMIT — next waiting transaction can proceed
    }

    public function cancelBooking(int $bookingId, int $userId): bool
    {
        return DB::transaction(function () use ($bookingId, $userId) {
            $booking = $this->bookingRepository->find($bookingId);

            if (!$booking || $booking->user_id !== $userId) {
                return false;
            }

            if ($booking->status === 'cancelled') {
                return false;
            }

            $this->bookingRepository->cancel($bookingId);
            $this->eventRepository->incrementSeats($booking->event_id, $booking->seats_booked);

            Log::channel('bookings')->info('Booking cancelled', [
                'booking_ref' => $booking->booking_ref,
                'user_id'     => $userId,
                'event_id'    => $booking->event_id,
                'seats'       => $booking->seats_booked,
                'trace_id'    => request()->header('X-Trace-ID'),
            ]);

            return true;
        });
    }

    public function getUserBookings(int $userId): Collection
    {
        return $this->bookingRepository->userBookings($userId);
    }

    public function getAllBookings(): Collection
    {
        return $this->bookingRepository->allBookings();
    }
}
