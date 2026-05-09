<?php

namespace App\Data\DTOs;

use App\Models\Event;

final class EventDTO
{
    public function __construct(
        public readonly ?int    $id,
        public readonly string  $title,
        public readonly ?string $description,
        public readonly string  $location,
        public readonly string  $eventDate,
        public readonly float   $price,
        public readonly int     $totalSeats,
        public readonly ?int    $availableSeats,
        public readonly string  $status,
        public readonly ?int    $createdBy,
    ) {}

    public static function fromRequest(array $validated, ?int $userId = null): self
    {
        return new self(
            id:             null,
            title:          $validated['title'],
            description:    $validated['description'] ?? null,
            location:       $validated['location'],
            eventDate:      $validated['event_date'],
            price:          (float) $validated['price'],
            totalSeats:     (int) $validated['total_seats'],
            availableSeats: (int) $validated['total_seats'],
            status:         $validated['status'] ?? 'active',
            createdBy:      $userId,
        );
    }

    public static function fromModel(Event $event): self
    {
        return new self(
            id:             $event->id,
            title:          $event->title,
            description:    $event->description,
            location:       $event->location,
            eventDate:      $event->event_date->toIso8601String(),
            price:          (float) $event->price,
            totalSeats:     $event->total_seats,
            availableSeats: $event->available_seats,
            status:         $event->status,
            createdBy:      $event->created_by,
        );
    }

    public function toArray(): array
    {
        return [
            'id'              => $this->id,
            'title'           => $this->title,
            'description'     => $this->description,
            'location'        => $this->location,
            'event_date'      => $this->eventDate,
            'price'           => $this->price,
            'total_seats'     => $this->totalSeats,
            'available_seats' => $this->availableSeats,
            'status'          => $this->status,
            'created_by'      => $this->createdBy,
        ];
    }
}
