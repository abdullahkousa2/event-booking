<?php

namespace App\Repositories\Eloquent;

use App\Data\DTOs\EventDTO;
use App\Models\Event;
use App\Repositories\Interfaces\EventRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class EventRepository implements EventRepositoryInterface
{
    public function all(array $filters = []): Collection
    {
        return Event::active()->with('creator')
            ->when(isset($filters['location']), fn($q) => $q->where('location', 'like', '%'.$filters['location'].'%'))
            ->when(isset($filters['date']), fn($q) => $q->whereDate('event_date', $filters['date']))
            ->when(isset($filters['available']) && $filters['available'], fn($q) => $q->available())
            ->orderBy('event_date')
            ->get();
    }

    public function paginated(int $perPage = 10, array $filters = []): LengthAwarePaginator
    {
        return Event::active()->with('creator')
            ->when(isset($filters['location']), fn($q) => $q->where('location', 'like', '%'.$filters['location'].'%'))
            ->when(isset($filters['date']), fn($q) => $q->whereDate('event_date', $filters['date']))
            ->when(isset($filters['available']) && $filters['available'], fn($q) => $q->available())
            ->orderBy('event_date')
            ->paginate($perPage);
    }

    public function find(int $id): ?Event
    {
        return Event::find($id);
    }

    // SELECT * FROM events WHERE id = ? FOR UPDATE  — acquires exclusive row lock
    public function findWithLock(int $id): Event
    {
        return Event::lockForUpdate()->findOrFail($id);
    }

    public function create(EventDTO $dto): Event
    {
        return Event::create([
            'title'           => $dto->title,
            'description'     => $dto->description,
            'location'        => $dto->location,
            'event_date'      => $dto->eventDate,
            'price'           => $dto->price,
            'total_seats'     => $dto->totalSeats,
            'available_seats' => $dto->totalSeats,
            'status'          => $dto->status,
            'created_by'      => $dto->createdBy,
        ]);
    }

    public function update(int $id, EventDTO $dto): Event
    {
        $event = Event::findOrFail($id);
        $event->update([
            'title'       => $dto->title,
            'description' => $dto->description,
            'location'    => $dto->location,
            'event_date'  => $dto->eventDate,
            'price'       => $dto->price,
            'status'      => $dto->status,
        ]);
        return $event->fresh();
    }

    public function delete(int $id): bool
    {
        return (bool) Event::destroy($id);
    }

    // Atomic arithmetic decrement — safe against race conditions
    public function decrementSeats(int $id, int $count): bool
    {
        return (bool) Event::where('id', $id)
            ->where('available_seats', '>=', $count)
            ->decrement('available_seats', $count);
    }

    public function incrementSeats(int $id, int $count): bool
    {
        return (bool) Event::where('id', $id)->increment('available_seats', $count);
    }

    public function getStats(): array
    {
        return [
            'total'     => Event::count(),
            'active'    => Event::active()->count(),
            'cancelled' => Event::where('status', 'cancelled')->count(),
            'completed' => Event::where('status', 'completed')->count(),
        ];
    }
}
