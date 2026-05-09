<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\User;
use App\Services\BookingService;
use App\Services\EventService;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __construct(
        private readonly EventService   $eventService,
        private readonly BookingService $bookingService,
    ) {}

    public function index(): View
    {
        $stats = [
            'events'          => $this->eventService->getStats(),
            'total_bookings'  => Booking::count(),
            'confirmed'       => Booking::where('status', 'confirmed')->count(),
            'pending'         => Booking::where('status', 'pending')->count(),
            'cancelled'       => Booking::where('status', 'cancelled')->count(),
            'total_users'     => User::where('role', 'user')->count(),
            'total_revenue'   => Payment::where('status', 'completed')->sum('amount'),
            'recent_bookings' => Booking::with(['user', 'event'])->latest()->take(10)->get(),
        ];

        return view('admin.dashboard', compact('stats'));
    }

    public function bookings(): View
    {
        $bookings = $this->bookingService->getAllBookings();
        return view('admin.bookings.index', compact('bookings'));
    }
}
