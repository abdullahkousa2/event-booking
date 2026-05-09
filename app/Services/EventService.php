<?php

namespace App\Services;

use App\Data\DTOs\EventDTO;
use App\Models\Event;
use App\Repositories\Interfaces\EventRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class EventService
{
    public function __construct(
        private readonly EventRepositoryInterface $eventRepository,
    ) {}

    public function listActiveEvents(array $filters = []): Collection
    {
        return $this->eventRepository->all($filters);
    }

    public function paginatedEvents(int $perPage = 9, array $filters = []): LengthAwarePaginator
    {
        return $this->eventRepository->paginated($perPage, $filters);
    }

    public function getEvent(int $id): ?Event
    {
        return $this->eventRepository->find($id);
    }

    public function createEvent(EventDTO $dto): Event
    {
        return $this->eventRepository->create($dto);
    }

    public function updateEvent(int $id, EventDTO $dto): Event
    {
        return $this->eventRepository->update($id, $dto);
    }

    public function deleteEvent(int $id): bool
    {
        return $this->eventRepository->delete($id);
    }

    public function getStats(): array
    {
        return $this->eventRepository->getStats();
    }
}
