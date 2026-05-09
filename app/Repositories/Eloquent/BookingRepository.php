<?php

namespace App\Repositories\Eloquent;

use App\Data\DTOs\BookingDTO;
use App\Models\Booking;
use App\Repositories\Interfaces\BookingRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;

class BookingRepository implements BookingRepositoryInterface
{
    public function create(BookingDTO $dto): Booking
    {
        return Booking::create([
            'user_id'      => $dto->userId,
            'event_id'     => $dto->eventId,
            'seats_booked' => $dto->seatsRequested,
            'total_amount' => $dto->totalAmount,
            'status'       => $dto->status,
            'booking_ref'  => $dto->bookingRef ?? $this->generateRef(),
        ]);
    }

    public function find(int $id): ?Booking
    {
        return Booking::with(['event', 'user', 'payment'])->find($id);
    }

    public function findByUserAndEvent(int $userId, int $eventId): ?Booking
    {
        return Booking::where('user_id', $userId)->where('event_id', $eventId)->first();
    }

    public function userBookings(int $userId): Collection
    {
        return Booking::with(['event', 'payment'])
            ->where('user_id', $userId)
            ->orderByDesc('created_at')
            ->get();
    }

    public function allBookings(): Collection
    {
        return Booking::with(['event', 'user', 'payment'])
            ->orderByDesc('created_at')
            ->get();
    }

    public function updateStatus(int $id, string $status): bool
    {
        return (bool) Booking::where('id', $id)->update(['status' => $status]);
    }

    public function cancel(int $id): bool
    {
        return $this->updateStatus($id, 'cancelled');
    }

    private function generateRef(): string
    {
        return 'BK-' . now()->format('Ymd') . '-' . strtoupper(Str::random(6));
    }
}
