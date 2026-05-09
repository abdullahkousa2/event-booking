<?php

namespace App\Data\DTOs;

use App\Models\Booking;

final class BookingDTO
{
    public function __construct(
        public readonly ?int    $id,
        public readonly int     $userId,
        public readonly int     $eventId,
        public readonly int     $seatsRequested,
        public readonly float   $totalAmount,
        public readonly string  $status,
        public readonly ?string $bookingRef,
    ) {}

    public static function fromRequest(array $validated, int $userId, float $price): self
    {
        $seats = (int) $validated['seats_requested'];

        return new self(
            id:             null,
            userId:         $userId,
            eventId:        (int) $validated['event_id'],
            seatsRequested: $seats,
            totalAmount:    round($price * $seats, 2),
            status:         'pending',
            bookingRef:     null,
        );
    }

    public static function fromModel(Booking $booking): self
    {
        return new self(
            id:             $booking->id,
            userId:         $booking->user_id,
            eventId:        $booking->event_id,
            seatsRequested: $booking->seats_booked,
            totalAmount:    (float) $booking->total_amount,
            status:         $booking->status,
            bookingRef:     $booking->booking_ref,
        );
    }

    public function toArray(): array
    {
        return [
            'id'          => $this->id,
            'user_id'     => $this->userId,
            'event_id'    => $this->eventId,
            'seats_booked'=> $this->seatsRequested,
            'total_amount'=> $this->totalAmount,
            'status'      => $this->status,
            'booking_ref' => $this->bookingRef,
        ];
    }
}
