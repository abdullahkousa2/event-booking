<?php

namespace App\Data\DTOs;

use App\Models\Payment;

final class PaymentDTO
{
    public function __construct(
        public readonly ?int    $id,
        public readonly int     $bookingId,
        public readonly float   $amount,
        public readonly string  $paymentMethod,
        public readonly string  $status,
        public readonly ?string $transactionId,
        public readonly ?string $paidAt,
    ) {}

    public static function fromRequest(array $validated, int $bookingId, float $amount): self
    {
        return new self(
            id:            null,
            bookingId:     $bookingId,
            amount:        $amount,
            paymentMethod: $validated['payment_method'],
            status:        'pending',
            transactionId: null,
            paidAt:        null,
        );
    }

    public static function fromModel(Payment $payment): self
    {
        return new self(
            id:            $payment->id,
            bookingId:     $payment->booking_id,
            amount:        (float) $payment->amount,
            paymentMethod: $payment->payment_method,
            status:        $payment->status,
            transactionId: $payment->transaction_id,
            paidAt:        $payment->paid_at?->toIso8601String(),
        );
    }

    public function toArray(): array
    {
        return [
            'id'             => $this->id,
            'booking_id'     => $this->bookingId,
            'amount'         => $this->amount,
            'payment_method' => $this->paymentMethod,
            'status'         => $this->status,
            'transaction_id' => $this->transactionId,
            'paid_at'        => $this->paidAt,
        ];
    }
}
