<?php

namespace App\Repositories\Interfaces;

use App\Data\DTOs\BookingDTO;
use App\Models\Booking;
use Illuminate\Database\Eloquent\Collection;

interface BookingRepositoryInterface
{
    public function create(BookingDTO $dto): Booking;
    public function find(int $id): ?Booking;
    public function findByUserAndEvent(int $userId, int $eventId): ?Booking;
    public function userBookings(int $userId): Collection;
    public function allBookings(): Collection;
    public function updateStatus(int $id, string $status): bool;
    public function cancel(int $id): bool;
}
