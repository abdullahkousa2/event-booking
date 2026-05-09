<?php

namespace App\Repositories\Eloquent;

use App\Data\DTOs\PaymentDTO;
use App\Models\Payment;
use App\Repositories\Interfaces\PaymentRepositoryInterface;

class PaymentRepository implements PaymentRepositoryInterface
{
    public function create(PaymentDTO $dto): Payment
    {
        return Payment::create([
            'booking_id'     => $dto->bookingId,
            'amount'         => $dto->amount,
            'payment_method' => $dto->paymentMethod,
            'status'         => $dto->status,
            'transaction_id' => $dto->transactionId ?? $this->generateTransactionId(),
        ]);
    }

    public function findByBooking(int $bookingId): ?Payment
    {
        return Payment::where('booking_id', $bookingId)->first();
    }

    public function updateStatus(int $id, string $status, ?string $paidAt = null): bool
    {
        $data = ['status' => $status];
        if ($paidAt) {
            $data['paid_at'] = $paidAt;
        }
        return (bool) Payment::where('id', $id)->update($data);
    }

    private function generateTransactionId(): string
    {
        return 'TXN-' . time() . '-' . rand(1000, 9999);
    }
}
