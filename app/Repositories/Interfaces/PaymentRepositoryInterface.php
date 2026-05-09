<?php

namespace App\Repositories\Interfaces;

use App\Data\DTOs\PaymentDTO;
use App\Models\Payment;

interface PaymentRepositoryInterface
{
    public function create(PaymentDTO $dto): Payment;
    public function findByBooking(int $bookingId): ?Payment;
    public function updateStatus(int $id, string $status, ?string $paidAt = null): bool;
}
