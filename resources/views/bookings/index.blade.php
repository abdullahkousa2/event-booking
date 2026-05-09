@extends('layouts.app')
@section('title', 'My Bookings — EventSY')

@push('styles')
<style>
.bookings-header {
    background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 100%);
    padding: 40px 0;
    position: relative; overflow: hidden;
}
.bookings-header::before {
    content: '';
    position: absolute; inset: 0;
    background: radial-gradient(ellipse at 60% 50%, rgba(99,102,241,.2) 0%, transparent 65%);
}
.bookings-header-content { position: relative; z-index: 1; }
.bookings-header h1 { color: #fff; font-size: 1.8rem; font-weight: 800; margin: 0; }
.bookings-header p  { color: rgba(255,255,255,.6); margin: 6px 0 0; font-size: .88rem; }

.booking-row {
    background: #fff;
    border-radius: 16px;
    border: 1px solid #e2e8f0;
    box-shadow: 0 2px 12px rgba(0,0,0,.05);
    overflow: hidden;
    transition: transform .2s, box-shadow .2s;
}
.booking-row:hover { transform: translateY(-2px); box-shadow: 0 8px 28px rgba(79,70,229,.1); }
.booking-row .stripe {
    height: 5px;
    background: linear-gradient(90deg, #4f46e5, #818cf8);
}
.booking-row .stripe.confirmed { background: linear-gradient(90deg, #10b981, #34d399); }
.booking-row .stripe.pending   { background: linear-gradient(90deg, #f59e0b, #fbbf24); }
.booking-row .stripe.cancelled { background: linear-gradient(90deg, #94a3b8, #64748b); }

.booking-ref {
    font-family: 'Courier New', monospace;
    font-size: .78rem; font-weight: 700;
    color: #4f46e5;
    background: #ede9fe;
    padding: 3px 10px; border-radius: 6px;
    display: inline-block;
}
.booking-meta { font-size: .82rem; color: #64748b; }
.booking-meta i { color: #4f46e5; margin-right: 4px; }

.btn-pay {
    background: linear-gradient(135deg, #10b981, #059669);
    color: #fff; border: none; border-radius: 8px;
    font-size: .8rem; font-weight: 700; padding: 7px 14px;
    text-decoration: none; display: inline-flex; align-items: center; gap: 4px;
    transition: all .2s;
}
.btn-pay:hover { transform: translateY(-1px); box-shadow: 0 4px 12px rgba(16,185,129,.35); color: #fff; }
.btn-view {
    background: #ede9fe; color: #4f46e5; border: none; border-radius: 8px;
    font-size: .8rem; font-weight: 600; padding: 7px 12px;
    text-decoration: none; display: inline-flex; align-items: center; gap: 4px;
    transition: background .2s;
}
.btn-view:hover { background: #ddd6fe; color: #4f46e5; }
.btn-cancel-sm {
    background: #fee2e2; color: #ef4444; border: none; border-radius: 8px;
    font-size: .8rem; font-weight: 600; padding: 7px 10px;
    display: inline-flex; align-items: center; transition: background .2s;
}
.btn-cancel-sm:hover { background: #fecaca; color: #dc2626; }

.empty-state { text-align: center; padding: 80px 20px; }
.empty-icon {
    width: 90px; height: 90px;
    background: linear-gradient(135deg, #ede9fe, #ddd6fe);
    border-radius: 50%; display: inline-flex; align-items: center; justify-content: center;
    font-size: 2.2rem; color: #7c3aed; margin-bottom: 20px;
}
.btn-browse {
    background: linear-gradient(135deg, #4f46e5, #7c3aed);
    color: #fff; border: none; border-radius: 10px;
    font-weight: 700; padding: 11px 28px; text-decoration: none;
    display: inline-block; transition: all .2s;
}
.btn-browse:hover { transform: translateY(-1px); box-shadow: 0 6px 20px rgba(79,70,229,.4); color: #fff; }
</style>
@endpush

@section('content')

{{-- Header --}}
<div class="bookings-header">
    <div class="container bookings-header-content">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h1><i class="bi bi-ticket-perforated me-2"></i>My Bookings</h1>
                <p>{{ $bookings->count() }} booking{{ $bookings->count() !== 1 ? 's' : '' }} total</p>
            </div>
            <a href="{{ route('events.index') }}" class="btn-browse">
                <i class="bi bi-plus-circle me-2"></i>Book New Event
            </a>
        </div>
    </div>
</div>

<div class="container mt-5">
    @if($bookings->isEmpty())
    <div class="empty-state">
        <div class="empty-icon"><i class="bi bi-ticket-perforated"></i></div>
        <h4 style="font-weight:700;margin-bottom:10px">No bookings yet</h4>
        <p class="text-muted">You haven't booked any events. Explore what's happening around Syria.</p>
        <a href="{{ route('events.index') }}" class="btn-browse mt-3">Browse Events</a>
    </div>
    @else
    <div class="d-flex flex-column gap-3">
        @foreach($bookings as $booking)
        @php
            $stripeClass = $booking->status === 'confirmed' ? 'confirmed' : ($booking->status === 'cancelled' ? 'cancelled' : 'pending');
        @endphp
        <div class="booking-row">
            <div class="stripe {{ $stripeClass }}"></div>
            <div class="p-4">
                <div class="row align-items-center g-3">

                    {{-- Event Info --}}
                    <div class="col-lg-4">
                        <div class="fw-bold" style="font-size:1rem;line-height:1.3">
                            {{ $booking->event->title ?? 'Event Deleted' }}
                        </div>
                        @if($booking->event)
                        <div class="booking-meta mt-1">
                            <i class="bi bi-calendar3"></i>{{ \Carbon\Carbon::parse($booking->event->event_date)->format('M j, Y') }}
                            &nbsp;·&nbsp;
                            <i class="bi bi-geo-alt"></i>{{ Str::limit($booking->event->location, 28) }}
                        </div>
                        @endif
                        <div class="mt-2">
                            <span class="booking-ref">{{ $booking->booking_ref }}</span>
                        </div>
                    </div>

                    {{-- Stats --}}
                    <div class="col-lg-4">
                        <div class="d-flex gap-4">
                            <div>
                                <div style="font-size:.72rem;font-weight:600;text-transform:uppercase;color:#94a3b8;letter-spacing:.4px">Seats</div>
                                <div class="fw-bold">{{ $booking->seats_booked }}</div>
                            </div>
                            <div>
                                <div style="font-size:.72rem;font-weight:600;text-transform:uppercase;color:#94a3b8;letter-spacing:.4px">Total</div>
                                <div class="fw-bold" style="color:#4f46e5">
                                    {{ $booking->total_amount > 0 ? '$' . number_format($booking->total_amount, 2) : 'FREE' }}
                                </div>
                            </div>
                            <div>
                                <div style="font-size:.72rem;font-weight:600;text-transform:uppercase;color:#94a3b8;letter-spacing:.4px">Status</div>
                                @if($booking->status === 'confirmed')
                                    <span class="event-badge badge-confirmed">Confirmed</span>
                                @elseif($booking->status === 'pending')
                                    <span class="event-badge badge-pending">Pending</span>
                                @else
                                    <span class="event-badge badge-cancel">Cancelled</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Actions --}}
                    <div class="col-lg-4">
                        <div class="d-flex align-items-center justify-content-lg-end gap-2 flex-wrap">
                            @if($booking->status === 'pending')
                                <a href="{{ route('payments.create', $booking->id) }}" class="btn-pay">
                                    <i class="bi bi-credit-card"></i>Pay Now
                                </a>
                            @endif
                            <a href="{{ route('bookings.show', $booking->id) }}" class="btn-view">
                                <i class="bi bi-eye"></i>Details
                            </a>
                            @if($booking->status !== 'cancelled')
                                <form method="POST" action="{{ route('bookings.cancel', $booking->id) }}" class="d-inline"
                                      onsubmit="return confirm('Cancel this booking? This action cannot be undone.')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn-cancel-sm">
                                        <i class="bi bi-x-lg"></i>
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>

                </div>
            </div>
        </div>
        @endforeach
    </div>
    @endif
</div>
@endsection
