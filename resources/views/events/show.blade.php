@extends('layouts.app')
@section('title', $event->title . ' — EventSY')

@push('styles')
<style>
.event-hero {
    background: linear-gradient(145deg, #070b18 0%, #0f172a 35%, #1e1b4b 68%, #312e81 100%);
    padding: 64px 0 72px;
    position: relative;
    overflow: hidden;
}
.event-hero::before {
    content: '';
    position: absolute; inset: 0;
    background: radial-gradient(ellipse at 75% 40%, rgba(99,102,241,.32) 0%, transparent 55%),
                radial-gradient(ellipse at 5% 90%, rgba(245,158,11,.18) 0%, transparent 50%),
                radial-gradient(ellipse at 50% 0%, rgba(124,58,237,.2) 0%, transparent 60%);
}
.event-hero-blob {
    position: absolute; border-radius: 50%; filter: blur(80px); opacity: .25;
}
.event-hero-blob-1 { width: 460px; height: 460px; background: #4f46e5; top: -140px; right: -60px; }
.event-hero-blob-2 { width: 200px; height: 200px; background: #f59e0b; bottom: -40px; left: 8%; }
.event-hero-content { position: relative; z-index: 2; }
.event-hero h1 {
    font-size: clamp(1.7rem, 3.5vw, 2.6rem);
    font-weight: 800; color: #fff; line-height: 1.2;
    letter-spacing: -.4px;
}
.event-hero .breadcrumb-item a { color: rgba(255,255,255,.5); text-decoration: none; font-size: .82rem; }
.event-hero .breadcrumb-item.active { color: rgba(255,255,255,.75); font-size: .82rem; }
.breadcrumb-item + .breadcrumb-item::before { color: rgba(255,255,255,.3); }

.info-chip {
    display: inline-flex; align-items: center; gap: 8px;
    background: rgba(255,255,255,.09);
    border: 1px solid rgba(255,255,255,.14);
    border-radius: 10px; padding: 9px 16px;
    color: rgba(255,255,255,.85); font-size: .88rem;
    backdrop-filter: blur(8px);
}
.info-chip i { color: #a5b4fc; }

.section-header {
    font-size: .7rem; font-weight: 800; letter-spacing: 1.2px;
    text-transform: uppercase; color: #4f46e5;
    display: flex; align-items: center; gap: 8px;
    margin-bottom: 16px;
}
.section-header::after {
    content: ''; flex: 1; height: 1px; background: #e2e8f0;
}

/* Detail card */
.detail-card {
    background: #fff;
    border-radius: 18px;
    border: 1px solid #e2e8f0;
    box-shadow: 0 4px 24px rgba(0,0,0,.07);
}
.meta-block {
    display: flex; align-items: center; gap: 14px;
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 12px; padding: 16px;
}
.meta-block .icon-wrap {
    width: 46px; height: 46px; flex-shrink: 0;
    border-radius: 12px;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.2rem;
}
.meta-block .label { font-size: .75rem; color: #94a3b8; font-weight: 600; text-transform: uppercase; letter-spacing: .4px; }
.meta-block .value { font-weight: 700; font-size: .98rem; color: #1e293b; }
.meta-block .sub   { font-size: .8rem; color: #64748b; }

/* Booking sidebar */
.booking-sidebar {
    background: #fff;
    border-radius: 18px;
    border: 1px solid #e2e8f0;
    box-shadow: 0 8px 32px rgba(79,70,229,.1);
    position: sticky; top: 90px;
}
.booking-sidebar .price-display { font-size: 2rem; font-weight: 800; color: #4f46e5; line-height: 1; }
.booking-sidebar .price-display.free { color: #10b981; }
.btn-book-big {
    background: linear-gradient(135deg, #4f46e5, #7c3aed);
    color: #fff; border: none; border-radius: 12px;
    font-weight: 700; font-size: 1rem; padding: 14px;
    width: 100%; transition: all .2s;
    display: block; text-align: center; text-decoration: none;
}
.btn-book-big:hover { transform: translateY(-2px); box-shadow: 0 8px 24px rgba(79,70,229,.4); color: #fff; }
.btn-login-book {
    background: #f1f5f9; color: #475569; border: none; border-radius: 12px;
    font-weight: 600; font-size: .95rem; padding: 12px;
    width: 100%; text-align: center; text-decoration: none; display: block;
    transition: background .2s;
}
.btn-login-book:hover { background: #e2e8f0; color: #1e293b; }
.sold-out-box {
    background: linear-gradient(135deg, #fee2e2, #fecaca);
    border: 1px solid #fca5a5; border-radius: 12px;
    padding: 18px; text-align: center;
}
.divider-label { font-size: .72rem; font-weight: 700; letter-spacing: .6px; text-transform: uppercase; color: #94a3b8; }
</style>
@endpush

@section('content')

@php
    $isFree = $event->price == 0;
    $isSold = $event->available_seats == 0;
    $isLow  = !$isSold && $event->available_seats <= 10;
    $pct    = $event->total_seats > 0 ? (($event->total_seats - $event->available_seats) / $event->total_seats) * 100 : 0;
@endphp

{{-- Hero --}}
<div class="event-hero">
    <div class="event-hero-blob event-hero-blob-1"></div>
    <div class="event-hero-blob event-hero-blob-2"></div>
    <div class="container event-hero-content">
        <nav aria-label="breadcrumb" class="mb-3">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('events.index') }}"><i class="bi bi-house me-1"></i>Events</a></li>
                <li class="breadcrumb-item active">{{ Str::limit($event->title, 45) }}</li>
            </ol>
        </nav>

        <div class="d-flex flex-wrap align-items-center gap-2 mb-3">
            @if($isSold)
                <span class="event-badge badge-sold">Sold Out</span>
            @elseif($event->status === 'cancelled')
                <span class="event-badge" style="background:linear-gradient(135deg,#64748b,#475569);color:#fff">Cancelled</span>
            @elseif($isLow)
                <span class="event-badge badge-low">{{ $event->available_seats }} Seats Left</span>
            @else
                <span class="event-badge badge-avail">Available</span>
            @endif
            @if($isFree)
                <span class="event-badge badge-free">Free Event</span>
            @endif
        </div>

        <h1>{{ $event->title }}</h1>

        <div class="d-flex flex-wrap gap-2 mt-4">
            <div class="info-chip">
                <i class="bi bi-calendar3"></i>
                {{ \Carbon\Carbon::parse($event->event_date)->format('D, M j, Y') }}
                &nbsp;·&nbsp;
                {{ \Carbon\Carbon::parse($event->event_date)->format('g:i A') }}
            </div>
            <div class="info-chip">
                <i class="bi bi-geo-alt"></i>
                {{ $event->location }}
            </div>
            <div class="info-chip">
                <i class="bi bi-people"></i>
                {{ $event->available_seats }} / {{ $event->total_seats }} seats available
            </div>
        </div>
    </div>
</div>

{{-- Body --}}
<div class="container mt-5">
    <div class="row g-4">

        {{-- Left: Details --}}
        <div class="col-lg-8">
            <div class="detail-card p-4 mb-4">
                <div class="section-header"><i class="bi bi-info-circle"></i>About This Event</div>
                <p class="text-muted" style="font-size:.95rem;line-height:1.7">
                    {{ $event->description ?? 'No description provided.' }}
                </p>
            </div>

            <div class="detail-card p-4">
                <div class="section-header"><i class="bi bi-calendar-check"></i>Event Details</div>
                <div class="row g-3">
                    <div class="col-sm-6">
                        <div class="meta-block">
                            <div class="icon-wrap" style="background:#ede9fe"><i class="bi bi-calendar-event" style="color:#7c3aed"></i></div>
                            <div>
                                <div class="label">Date</div>
                                <div class="value">{{ \Carbon\Carbon::parse($event->event_date)->format('D, M j, Y') }}</div>
                                <div class="sub">{{ \Carbon\Carbon::parse($event->event_date)->format('g:i A') }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="meta-block">
                            <div class="icon-wrap" style="background:#fee2e2"><i class="bi bi-geo-alt-fill" style="color:#ef4444"></i></div>
                            <div>
                                <div class="label">Location</div>
                                <div class="value">{{ $event->location }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="meta-block">
                            <div class="icon-wrap" style="background:#d1fae5"><i class="bi bi-people-fill" style="color:#10b981"></i></div>
                            <div>
                                <div class="label">Capacity</div>
                                <div class="value">{{ $event->available_seats }} seats left</div>
                                <div class="sub">{{ $event->total_seats }} total capacity</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="meta-block">
                            <div class="icon-wrap" style="background:#fef3c7"><i class="bi bi-tag-fill" style="color:#f59e0b"></i></div>
                            <div>
                                <div class="label">Price Per Seat</div>
                                <div class="value" style="color:{{ $isFree ? '#10b981' : '#4f46e5' }}">
                                    {{ $isFree ? 'FREE' : '$' . number_format($event->price, 2) }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Seats Progress --}}
                <div class="mt-4">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="divider-label">Seats Filled</span>
                        <span style="font-size:.8rem;font-weight:700;color:{{ $isSold ? '#ef4444' : ($isLow ? '#f59e0b' : '#10b981') }}">
                            {{ round($pct) }}% booked
                        </span>
                    </div>
                    <div class="progress" style="height:8px">
                        <div class="progress-bar {{ $pct > 85 ? 'bg-danger' : ($pct > 55 ? 'bg-warning' : 'bg-success') }}"
                             style="width:{{ $pct }}%"></div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Right: Booking Sidebar --}}
        <div class="col-lg-4">
            <div class="booking-sidebar p-4">
                <div class="text-center mb-4">
                    <div class="divider-label mb-2">Price Per Seat</div>
                    <div class="price-display {{ $isFree ? 'free' : '' }}">
                        {{ $isFree ? 'FREE' : '$' . number_format($event->price, 0) }}
                    </div>
                    @if(!$isFree)
                        <div class="text-muted" style="font-size:.8rem">per seat</div>
                    @endif
                </div>

                <hr style="border-color:#e2e8f0">

                @if($event->status !== 'active')
                    <div class="alert alert-warning text-center" style="border-radius:12px;font-size:.88rem">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        This event is not open for booking.
                    </div>
                @elseif($isSold)
                    <div class="sold-out-box">
                        <i class="bi bi-x-circle-fill text-danger" style="font-size:2rem"></i>
                        <div class="fw-700 text-danger mt-2" style="font-weight:700">Sold Out</div>
                        <div class="text-muted small mt-1">All seats have been taken</div>
                    </div>
                @elseif(auth()->check())
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-1">
                            <span style="font-size:.8rem;color:#64748b">Availability</span>
                            <span style="font-size:.8rem;font-weight:700;color:{{ $isLow ? '#f59e0b' : '#10b981' }}">
                                {{ $event->available_seats }} left
                            </span>
                        </div>
                        <div class="progress mb-3" style="height:6px">
                            <div class="progress-bar {{ $isLow ? 'bg-warning' : 'bg-success' }}" style="width:{{ 100 - $pct }}%"></div>
                        </div>
                    </div>
                    <a href="{{ route('bookings.create', $event->id) }}" class="btn-book-big">
                        <i class="bi bi-ticket me-2"></i>Book Now
                    </a>
                    <div class="text-center mt-3" style="font-size:.76rem;color:#94a3b8">
                        <i class="bi bi-shield-check me-1" style="color:#10b981"></i>Secure booking · Instant confirmation
                    </div>
                @else
                    <a href="{{ route('login') }}" class="btn-book-big mb-2">
                        <i class="bi bi-box-arrow-in-right me-2"></i>Sign In to Book
                    </a>
                    <a href="{{ route('register') }}" class="btn-login-book mt-2">
                        <i class="bi bi-person-plus me-2"></i>Create Account
                    </a>
                    <div class="text-center mt-3" style="font-size:.76rem;color:#94a3b8">
                        Free to register · No credit card required
                    </div>
                @endif

                <hr style="border-color:#e2e8f0;margin-top:20px">
                <div class="text-center">
                    <a href="{{ route('events.index') }}" style="font-size:.82rem;color:#4f46e5;text-decoration:none;font-weight:600">
                        <i class="bi bi-arrow-left me-1"></i>Back to Events
                    </a>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
