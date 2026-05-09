@extends('layouts.app')
@section('title', 'Book Event — EventSY')

@push('styles')
<style>
.booking-page { background: #f8fafc; min-height: calc(100vh - 160px); padding: 48px 0; }
.page-header {
    background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 100%);
    padding: 36px 0;
    margin-bottom: 0;
    position: relative; overflow: hidden;
}
.page-header::before {
    content: '';
    position: absolute; inset: 0;
    background: radial-gradient(ellipse at 60% 50%, rgba(99,102,241,.2) 0%, transparent 65%);
}
.page-header-content { position: relative; z-index: 1; }
.page-header h1 { color: #fff; font-size: 1.6rem; font-weight: 800; margin: 0; }
.page-header .breadcrumb-item a { color: rgba(255,255,255,.55); text-decoration: none; font-size: .82rem; }
.page-header .breadcrumb-item.active { color: rgba(255,255,255,.75); font-size: .82rem; }
.breadcrumb-item + .breadcrumb-item::before { color: rgba(255,255,255,.3); }

.booking-card {
    background: #fff;
    border-radius: 18px;
    border: 1px solid #e2e8f0;
    box-shadow: 0 4px 24px rgba(0,0,0,.07);
}
.event-summary-card {
    background: linear-gradient(135deg, #4f46e5, #7c3aed);
    border-radius: 18px;
    padding: 24px;
    color: #fff;
    position: relative; overflow: hidden;
}
.event-summary-card::before {
    content: '';
    position: absolute; inset: 0;
    background: radial-gradient(ellipse at 80% 20%, rgba(255,255,255,.15) 0%, transparent 60%);
}
.event-summary-card .sc { position: relative; z-index: 1; }
.event-summary-card h5 { font-weight: 800; font-size: 1.1rem; }
.event-summary-card .chip {
    display: inline-flex; align-items: center; gap: 5px;
    background: rgba(255,255,255,.15); border-radius: 8px; padding: 4px 10px;
    font-size: .78rem;
}
.seats-picker {
    background: #f8fafc;
    border: 2px solid #e2e8f0;
    border-radius: 14px;
    padding: 20px;
}
/* Custom seat grid */
.seat-grid {
    display: grid;
    grid-template-columns: repeat(5, 1fr);
    gap: 8px;
}
.seat-btn {
    border: 2px solid #e2e8f0;
    border-radius: 10px;
    background: #fff;
    padding: 10px 6px;
    cursor: pointer;
    transition: all .18s;
    text-align: center;
    position: relative;
}
.seat-btn:hover {
    border-color: #4f46e5;
    background: #ede9fe;
    color: #4f46e5;
}
.seat-btn.selected {
    border-color: #4f46e5;
    background: linear-gradient(135deg, #4f46e5, #7c3aed);
    color: #fff;
    box-shadow: 0 4px 14px rgba(79,70,229,.3);
    transform: scale(1.04);
}
.seat-btn .sb-num {
    font-size: 1.15rem;
    font-weight: 800;
    line-height: 1;
}
.seat-btn .sb-lbl {
    font-size: .64rem;
    font-weight: 600;
    opacity: .65;
    margin-top: 2px;
    text-transform: uppercase;
    letter-spacing: .3px;
}
.seat-btn.selected .sb-lbl { opacity: .8; }
.btn-confirm {
    background: linear-gradient(135deg, #4f46e5, #7c3aed);
    color: #fff; border: none; border-radius: 12px;
    font-weight: 700; font-size: 1rem; padding: 14px 28px;
    transition: all .2s; width: 100%;
}
.btn-confirm:hover { transform: translateY(-1px); box-shadow: 0 8px 24px rgba(79,70,229,.4); color: #fff; }
.btn-cancel-outline {
    background: #f1f5f9; color: #475569; border: none;
    border-radius: 12px; font-weight: 600; font-size: .95rem;
    padding: 12px 24px; width: 100%; text-align: center;
    text-decoration: none; display: block; transition: background .2s;
}
.btn-cancel-outline:hover { background: #e2e8f0; color: #1e293b; }
.price-summary {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 14px;
    padding: 18px 20px;
}
.price-row { display: flex; justify-content: space-between; align-items: center; font-size: .9rem; }
.price-row .label { color: #64748b; }
.price-row .amount { font-weight: 700; color: #1e293b; }
.price-row.total .label { font-weight: 700; font-size: 1rem; color: #1e293b; }
.price-row.total .amount { font-size: 1.2rem; color: #4f46e5; }
</style>
@endpush

@section('content')

@php
    $isFree = $event->price == 0;
@endphp

{{-- Page Header --}}
<div class="page-header">
    <div class="container page-header-content">
        <nav aria-label="breadcrumb" class="mb-2">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('events.index') }}">Events</a></li>
                <li class="breadcrumb-item"><a href="{{ route('events.show', $event->id) }}">{{ Str::limit($event->title, 35) }}</a></li>
                <li class="breadcrumb-item active">Book</li>
            </ol>
        </nav>
        <h1><i class="bi bi-ticket-perforated me-2"></i>Book Your Seat</h1>
    </div>
</div>

<div style="background:#f8fafc;padding:40px 0">
    <div class="container">
        <div class="row g-4 justify-content-center">
            <div class="col-lg-8">

                {{-- Event Summary --}}
                <div class="event-summary-card mb-4">
                    <div class="sc">
                        <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
                            <div>
                                <div style="font-size:.75rem;opacity:.7;font-weight:600;text-transform:uppercase;letter-spacing:.5px;margin-bottom:6px">You're Booking</div>
                                <h5>{{ $event->title }}</h5>
                                <div class="d-flex flex-wrap gap-2 mt-2">
                                    <div class="chip"><i class="bi bi-calendar3"></i>{{ \Carbon\Carbon::parse($event->event_date)->format('M j, Y · g:i A') }}</div>
                                    <div class="chip"><i class="bi bi-geo-alt"></i>{{ Str::limit($event->location, 35) }}</div>
                                    <div class="chip"><i class="bi bi-people"></i>{{ $event->available_seats }} seats available</div>
                                </div>
                            </div>
                            <div style="text-align:right">
                                <div style="font-size:.75rem;opacity:.7;font-weight:600;margin-bottom:4px">Price / Seat</div>
                                <div style="font-size:1.8rem;font-weight:800">{{ $isFree ? 'FREE' : '$' . number_format($event->price, 0) }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Booking Form --}}
                <div class="booking-card p-4">
                    <h5 class="fw-bold mb-4"><i class="bi bi-ticket me-2 text-primary"></i>Select Your Seats</h5>

                    <form method="POST" action="{{ route('bookings.store') }}" id="bookingForm">
                        @csrf
                        <input type="hidden" name="event_id" value="{{ $event->id }}">

                        <div class="seats-picker mb-4">
                            <label class="form-label mb-3" style="font-weight:700">
                                Number of Seats
                                <span style="color:#94a3b8;font-weight:400;font-size:.82rem;margin-left:6px">
                                    — click to select
                                </span>
                            </label>

                            {{-- Hidden real input --}}
                            <input type="hidden" name="seats_requested" id="seatsInput"
                                   value="{{ old('seats_requested', 1) }}"
                                   class="@error('seats_requested') is-invalid @enderror">
                            @error('seats_requested')<div class="text-danger mb-2" style="font-size:.82rem">{{ $message }}</div>@enderror

                            <div class="seat-grid">
                                @for($i = 1; $i <= min(10, $event->available_seats); $i++)
                                <button type="button"
                                        class="seat-btn {{ (old('seats_requested', 1) == $i) ? 'selected' : '' }}"
                                        onclick="selectSeat({{ $i }}, this)">
                                    <div class="sb-num">{{ $i }}</div>
                                    <div class="sb-lbl">seat{{ $i > 1 ? 's' : '' }}</div>
                                </button>
                                @endfor
                            </div>

                            @if(!$isFree)
                            <div class="mt-3 d-flex align-items-center gap-2" style="font-size:.8rem;color:#64748b">
                                <i class="bi bi-info-circle" style="color:#4f46e5"></i>
                                ${{ number_format($event->price, 2) }} × <span id="seatsCountText">{{ old('seats_requested', 1) }}</span> seat(s) =
                                <strong style="color:#4f46e5" id="seatsSubtotal">${{ number_format($event->price * old('seats_requested', 1), 2) }}</strong>
                            </div>
                            @endif
                        </div>

                        {{-- Price Summary --}}
                        <div class="price-summary mb-4">
                            <div class="price-row mb-2">
                                <span class="label">Price per seat</span>
                                <span class="amount">{{ $isFree ? 'FREE' : '$' . number_format($event->price, 2) }}</span>
                            </div>
                            <div class="price-row mb-3">
                                <span class="label">Seats selected</span>
                                <span class="amount" id="seatsDisplay">1</span>
                            </div>
                            <hr style="border-color:#e2e8f0;margin:12px 0">
                            <div class="price-row total">
                                <span class="label">Total Amount</span>
                                <span class="amount" id="totalDisplay">{{ $isFree ? 'FREE' : '$' . number_format($event->price, 2) }}</span>
                            </div>
                        </div>

                        <div class="alert alert-info d-flex align-items-center gap-2" style="border-radius:12px;font-size:.86rem">
                            <i class="bi bi-info-circle-fill fs-5" style="flex-shrink:0"></i>
                            <span>Payment will be collected after booking to confirm your reservation.</span>
                        </div>

                        <div class="d-flex flex-column gap-3 mt-4">
                            <button type="submit" class="btn-confirm">
                                <i class="bi bi-check-circle me-2"></i>Confirm Booking
                            </button>
                            <a href="{{ route('events.show', $event->id) }}" class="btn-cancel-outline">
                                Cancel
                            </a>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
const pricePerSeat = {{ $event->price }};
const isFree = {{ $isFree ? 'true' : 'false' }};

function selectSeat(n, btn) {
    // Deselect all
    document.querySelectorAll('.seat-btn').forEach(b => b.classList.remove('selected'));
    // Select clicked
    btn.classList.add('selected');
    // Update hidden input
    document.getElementById('seatsInput').value = n;
    // Update summary
    updateSummary(n);
}

function updateSummary(seats) {
    const seatsDisplay = document.getElementById('seatsDisplay');
    const totalDisplay = document.getElementById('totalDisplay');
    const seatsCountText = document.getElementById('seatsCountText');
    const seatsSubtotal  = document.getElementById('seatsSubtotal');

    if (seatsDisplay) seatsDisplay.textContent = seats;
    if (seatsCountText) seatsCountText.textContent = seats;

    if (isFree) {
        if (totalDisplay) totalDisplay.textContent = 'FREE';
    } else {
        const total = (pricePerSeat * seats).toFixed(2);
        const fmt = '$' + parseFloat(total).toLocaleString('en-US', {minimumFractionDigits:2, maximumFractionDigits:2});
        if (totalDisplay)   totalDisplay.textContent = fmt;
        if (seatsSubtotal)  seatsSubtotal.textContent = fmt;
    }
}

document.addEventListener('DOMContentLoaded', function() {
    updateSummary(parseInt(document.getElementById('seatsInput').value) || 1);
});
</script>
@endpush
@endsection
