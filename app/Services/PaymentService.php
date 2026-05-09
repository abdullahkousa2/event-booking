<?php

namespace App\Services;

use App\Data\DTOs\PaymentDTO;
use App\Exceptions\PaymentFailedException;
use App\Models\Booking;
use App\Models\Payment;
use App\Repositories\Interfaces\BookingRepositoryInterface;
use App\Repositories\Interfaces\EventRepositoryInterface;
use App\Repositories\Interfaces\PaymentRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PaymentService
{
    public function __construct(
        private readonly PaymentRepositoryInterface $paymentRepository,
        private readonly BookingRepositoryInterface $bookingRepository,
        private readonly EventRepositoryInterface   $eventRepository,
    ) {}

    /**
     * Mock payment processor — simulates a real payment gateway.
     * 95% success rate to demonstrate both happy path and failure path.
     */
    public function processPayment(PaymentDTO $dto): Payment
    {
        return DB::transaction(function () use ($dto) {
            $transactionId = 'TXN-' . now()->format('YmdHis') . '-' . rand(1000, 9999);

            $payment = $this->paymentRepository->create(new PaymentDTO(
                id:            null,
                bookingId:     $dto->bookingId,
                amount:        $dto->amount,
                paymentMethod: $dto->paymentMethod,
                status:        'pending',
                transactionId: $transactionId,
                paidAt:        null,
            ));

            // Simulate 95% success rate
            $success = rand(1, 100) <= 95;

            if ($success) {
                $this->paymentRepository->updateStatus($payment->id, 'completed', now()->toIso8601String());
                $this->bookingRepository->updateStatus($dto->bookingId, 'confirmed');

                Log::channel('bookings')->info('Payment completed', [
                    'transaction_id' => $transactionId,
                    'booking_id'     => $dto->bookingId,
                    'amount'         => $dto->amount,
                    'method'         => $dto->paymentMethod,
                    'trace_id'       => request()->header('X-Trace-ID'),
                ]);

                return $payment->fresh();
            }

            // Payment failed
            $this->paymentRepository->updateStatus($payment->id, 'failed');

            Log::channel('bookings')->warning('Payment failed', [
                'transaction_id' => $transactionId,
                'booking_id'     => $dto->bookingId,
                'trace_id'       => request()->header('X-Trace-ID'),
            ]);

            throw new PaymentFailedException('Payment was declined. Please try again.');
        });
    }

    public function refundPayment(int $bookingId): bool
    {
        $payment = $this->paymentRepository->findByBooking($bookingId);
        if (!$payment || $payment->status !== 'completed') {
            return false;
        }

        $this->paymentRepository->updateStatus($payment->id, 'refunded');

        Log::channel('bookings')->info('Payment refunded', [
            'transaction_id' => $payment->transaction_id,
            'booking_id'     => $bookingId,
            'trace_id'       => request()->header('X-Trace-ID'),
        ]);

        return true;
    }
}
