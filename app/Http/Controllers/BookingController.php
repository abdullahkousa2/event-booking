<?php

namespace App\Http\Controllers;

use App\Data\DTOs\BookingDTO;
use App\Http\Requests\StoreBookingRequest;
use App\Services\BookingService;
use App\Services\EventService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class BookingController extends Controller
{
    public function __construct(
        private readonly BookingService $bookingService,
        private readonly EventService   $eventService,
    ) {}

    public function index(): View
    {
        $bookings = $this->bookingService->getUserBookings(auth()->id());
        return view('bookings.index', compact('bookings'));
    }

    public function create(int $eventId): View
    {
        $event = $this->eventService->getEvent($eventId);
        abort_if(!$event || $event->status !== 'active', 404);
        return view('bookings.create', compact('event'));
    }

    public function store(StoreBookingRequest $request): RedirectResponse
    {
        $event = $this->eventService->getEvent($request->event_id);
        abort_if(!$event, 404);

        $dto = BookingDTO::fromRequest($request->validated(), auth()->id(), (float) $event->price);

        $booking = $this->bookingService->createBooking($dto);

        return redirect()->route('payments.create', $booking->id)
            ->with('success', 'Booking created! Please complete payment to confirm.');
    }

    public function show(int $id): View
    {
        $bookings = $this->bookingService->getUserBookings(auth()->id());
        $booking  = $bookings->firstWhere('id', $id);
        abort_if(!$booking, 404);
        return view('bookings.show', compact('booking'));
    }

    public function destroy(int $id): RedirectResponse
    {
        $cancelled = $this->bookingService->cancelBooking($id, auth()->id());

        return redirect()->route('bookings.index')
            ->with($cancelled ? 'success' : 'error',
                $cancelled ? 'Booking cancelled successfully.' : 'Unable to cancel this booking.');
    }
}
