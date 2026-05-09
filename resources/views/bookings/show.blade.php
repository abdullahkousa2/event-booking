@extends('layouts.app')
@section('title', 'Booking Details — EventSY')

@push('styles')
<style>
.detail-header {
    background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 100%);
    padding: 40px 0;
    position: relative; overflow: hidden;
}
.detail-header::before {
    content: '';
    position: absolute; inset: 0;
    background: radial-gradient(ellipse at 60% 50%, rgba(99,102,241,.2) 0%, transparent 65%);
}
.detail-header-content { position: relative; z-index: 1; }
.detail-header h1 { color: #fff; font-size: 1.6rem; font-weight: 800; margin: 0; }
.ref-badge {
    display: inline-block;
    background: rgba(255,255,255,.1);
    border: 1px solid rgba(255,255,255,.15);
    border-radius: 8px; padding: 5px 14px;
    font-family: 'Courier New', monospace;
    font-size: .85rem; font-weight: 700; color: #fff;
    margin-top: 8px;
}

.detail-card {
    background: #fff;
    border-radius: 18px;
    border: 1px solid #e2e8f0;
    box-shadow: 0 4px 20px rgba(0,0,0,.06);
}
.section-label {
    font-size: .72rem; font-weight: 700; letter-spacing: .6px;
    text-transform: uppercase; color: #94a3b8; margin-bottom: 16px;
}
.info-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 16px;
}
.info-item .label { font-size: .75rem; color: #94a3b8; font-weight: 600; margin-bottom: 3px; }
.info-item .value { font-weight: 700; font-size: .97rem; color: #1e293b; }

.event-block {
    background: linear-gradient(135deg, #f8fafc, #f1f5f9);
    border: 1px solid #e2e8f0;
    border-radius: 14px; padding: 18px 20px;
}
.event-block .ev-title { font-weight: 700; font-size: 1.05rem; color: #1e293b; }
.event-block .ev-meta { font-size: .82rem; color: #64748b; margin-top: 6px; }
.event-block .ev-meta i { color: #4f46e5; margin-right: 4px; }

.payment-block {
    background: linear-gradient(135deg, #f0fdf4, #dcfce7);
    border: 1px solid #bbf7d0;
    border-radius: 14px; padding: 18px 20px;
}
.payment-block.failed {
    background: linear-gradient(135deg, #fef2f2, #fee2e2);
    border-color: #fecaca;
}
.txn-code {
    font-family: 'Courier New', monospace;
    font-size: .8rem; color: #059669; background: rgba(16,185,129,.1);
    padding: 2px 8px; border-radius: 5px;
}
.txn-code.failed { color: #dc2626; background: rgba(239,68,68,.1); }

.btn-pay-big {
    background: linear-gradient(135deg, #10b981, #059669);
    color: #fff; border: none; border-radius: 12px;
    font-weight: 700; font-size: .95rem; padding: 12px 28px;
    text-decoration: none; display: inline-flex; align-items: center; gap: 8px;
    transition: all .2s;
}
.btn-pay-big:hover { transform: translateY(-1px); box-shadow: 0 6px 18px rgba(16,185,129,.35); color: #fff; }
.btn-back {
    background: #f1f5f9; color: #475569; border: none; border-radius: 12px;
    font-weight: 600; font-size: .93rem; padding: 12px 24px;
    text-decoration: none; display: inline-flex; align-items: center; gap: 8px;
    transition: background .2s;
}
.btn-back:hover { background: #e2e8f0; color: #1e293b; }
</style>
@endpush

@section('content')

{{-- Header --}}
<div class="detail-header">
    <div class="container detail-header-content">
        <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
            <div>
                <h1><i class="bi bi-ticket-perforated me-2"></i>Booking Details</h1>
                <div class="ref-badge">{{ $booking->booking_ref }}</div>
            </div>
            <div class="mt-1">
                @if($booking->status === 'confirmed')
                    <span class="event-badge badge-confirmed" style="font-size:.85rem;padding:7px 16px">
                        <i class="bi bi-check-circle me-1"></i>Confirmed
                    </span>
                @elseif($booking->status === 'pending')
                    <span class="event-badge badge-pending" style="font-size:.85rem;padding:7px 16px">
                        <i class="bi bi-clock me-1"></i>Pending Payment
                    </span>
                @else
                    <span class="event-badge badge-cancel" style="font-size:.85rem;padding:7px 16px">
                        <i class="bi bi-x-circle me-1"></i>Cancelled
                    </span>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">

            {{-- Booking Info --}}
            <div class="detail-card p-4 mb-4">
                <div class="section-label">Booking Information</div>
                <div class="info-grid mb-4">
                    <div class="info-item">
                        <div class="label">Booking Reference</div>
                        <div class="value" style="font-family:'Courier New',monospace;color:#4f46e5">{{ $booking->booking_ref }}</div>
                    </div>
                    <div class="info-item">
                        <div class="label">Booked On</div>
                        <div class="value">{{ $booking->created_at->format('M j, Y · g:i A') }}</div>
                    </div>
                    <div class="info-item">
                        <div class="label">Seats Booked</div>
                        <div class="value"><i class="bi bi-people me-1 text-primary"></i>{{ $booking->seats_booked }} seat{{ $booking->seats_booked > 1 ? 's' : '' }}</div>
                    </div>
                    <div class="info-item">
                        <div class="label">Total Amount</div>
                        <div class="value" style="font-size:1.15rem;color:#4f46e5">
                            {{ $booking->total_amount > 0 ? '$' . number_format($booking->total_amount, 2) : 'FREE' }}
                        </div>
                    </div>
                </div>

                {{-- Event Block --}}
                @if($booking->event)
                <div class="section-label">Event</div>
                <div class="event-block mb-4">
                    <div class="ev-title">{{ $booking->event->title }}</div>
                    <div class="ev-meta">
                        <i class="bi bi-calendar3"></i>{{ \Carbon\Carbon::parse($booking->event->event_date)->format('D, M j, Y · g:i A') }}
                        &nbsp;&bull;&nbsp;
                        <i class="bi bi-geo-alt"></i>{{ $booking->event->location }}
                    </div>
                </div>
                @endif

                {{-- Payment Block --}}
                @if($booking->payment)
                @php $pStatus = $booking->payment->status; @endphp
                <div class="section-label">Payment</div>
                <div class="payment-block {{ $pStatus === 'failed' ? 'failed' : '' }}">
                    <div class="row g-3">
                        <div class="col-sm-6">
                            <div class="label" style="font-size:.75rem;color:#64748b;font-weight:600;margin-bottom:3px">Transaction ID</div>
                            <code class="txn-code {{ $pStatus === 'failed' ? 'failed' : '' }}">{{ $booking->payment->transaction_id }}</code>
                        </div>
                        <div class="col-sm-6">
                            <div class="label" style="font-size:.75rem;color:#64748b;font-weight:600;margin-bottom:3px">Method</div>
                            <div style="font-weight:600">{{ ucfirst(str_replace('_', ' ', $booking->payment->payment_method)) }}</div>
                        </div>
                        <div class="col-sm-6">
                            <div class="label" style="font-size:.75rem;color:#64748b;font-weight:600;margin-bottom:3px">Status</div>
                            @if($pStatus === 'completed')
                                <span class="event-badge badge-active">Paid</span>
                            @elseif($pStatus === 'failed')
                                <span class="event-badge badge-sold">Failed</span>
                            @else
                                <span class="event-badge badge-pending">Pending</span>
                            @endif
                        </div>
                        @if($booking->payment->paid_at)
                        <div class="col-sm-6">
                            <div class="label" style="font-size:.75rem;color:#64748b;font-weight:600;margin-bottom:3px">Paid At</div>
                            <div style="font-weight:600">{{ \Carbon\Carbon::parse($booking->payment->paid_at)->format('M j, Y · g:i A') }}</div>
                        </div>
                        @endif
                    </div>
                </div>
                @endif
            </div>

            {{-- Actions --}}
            <div class="d-flex gap-3 flex-wrap">
                @if($booking->status === 'pending')
                    <a href="{{ route('payments.create', $booking->id) }}" class="btn-pay-big">
                        <i class="bi bi-credit-card"></i>Complete Payment
                    </a>
                @endif
                <a href="{{ route('bookings.index') }}" class="btn-back">
                    <i class="bi bi-arrow-left"></i>My Bookings
                </a>
            </div>

        </div>
    </div>
</div>
@endsection
