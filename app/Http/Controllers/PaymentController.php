<?php

namespace App\Http\Controllers;

use App\Data\DTOs\PaymentDTO;
use App\Http\Requests\ProcessPaymentRequest;
use App\Models\Booking;
use App\Services\PaymentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PaymentController extends Controller
{
    public function __construct(private readonly PaymentService $paymentService) {}

    public function create(int $bookingId): View
    {
        $booking = Booking::with('event')->where('user_id', auth()->id())->findOrFail($bookingId);
        return view('payments.create', compact('booking'));
    }

    public function store(ProcessPaymentRequest $request): RedirectResponse
    {
        $booking = Booking::where('user_id', auth()->id())->findOrFail($request->booking_id);

        $dto = PaymentDTO::fromRequest($request->validated(), $booking->id, (float) $booking->total_amount);

        $payment = $this->paymentService->processPayment($dto);

        return redirect()->route('payments.success', $payment->id)
            ->with('success', 'Payment successful! Your booking is confirmed.');
    }

    public function success(int $paymentId): View
    {
        $payment = \App\Models\Payment::with(['booking.event'])->findOrFail($paymentId);
        return view('payments.success', compact('payment'));
    }
}
