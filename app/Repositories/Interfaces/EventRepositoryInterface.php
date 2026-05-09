<?php

namespace App\Repositories\Interfaces;

use App\Data\DTOs\EventDTO;
use App\Models\Event;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface EventRepositoryInterface
{
    public function all(array $filters = []): Collection;
    public function paginated(int $perPage = 10, array $filters = []): LengthAwarePaginator;
    public function find(int $id): ?Event;
    public function findWithLock(int $id): Event;
    public function create(EventDTO $dto): Event;
    public function update(int $id, EventDTO $dto): Event;
    public function delete(int $id): bool;
    public function decrementSeats(int $id, int $count): bool;
    public function incrementSeats(int $id, int $count): bool;
    public function getStats(): array;
}
