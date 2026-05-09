@extends('layouts.app')
@section('title', 'Payment Successful — EventSY')

@push('styles')
<style>
.success-page {
    min-height: calc(100vh - 160px);
    display: flex; align-items: center;
    background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 30%, #f0fdf4 100%);
    padding: 48px 0;
}
.success-card {
    background: #fff;
    border-radius: 24px;
    border: 1px solid #bbf7d0;
    box-shadow: 0 20px 60px rgba(16,185,129,.12);
    overflow: hidden;
}
.success-top {
    background: linear-gradient(135deg, #059669 0%, #10b981 100%);
    padding: 40px 36px;
    text-align: center;
    position: relative; overflow: hidden;
}
.success-top::before {
    content: '';
    position: absolute; inset: 0;
    background: radial-gradient(ellipse at 50% 0%, rgba(255,255,255,.2) 0%, transparent 65%);
}
.success-top .check-ring {
    width: 80px; height: 80px;
    background: rgba(255,255,255,.2);
    border: 3px solid rgba(255,255,255,.4);
    border-radius: 50%;
    display: inline-flex; align-items: center; justify-content: center;
    font-size: 2.4rem; color: #fff;
    position: relative; z-index: 1;
    margin-bottom: 16px;
    animation: pop .4s cubic-bezier(.34,1.56,.64,1) both;
}
@keyframes pop {
    from { transform: scale(0); opacity: 0; }
    to   { transform: scale(1); opacity: 1; }
}
.success-top h2 { color: #fff; font-weight: 800; font-size: 1.6rem; margin: 0; position: relative; z-index: 1; }
.success-top p  { color: rgba(255,255,255,.75); margin: 6px 0 0; position: relative; z-index: 1; }

.success-body { padding: 32px 36px 36px; }

.receipt-grid {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 14px;
    padding: 20px;
    margin-bottom: 24px;
}
.receipt-row {
    display: flex; justify-content: space-between; align-items: center;
    padding: 8px 0;
    border-bottom: 1px solid #e2e8f0;
    font-size: .88rem;
}
.receipt-row:last-child { border-bottom: none; padding-bottom: 0; }
.receipt-row .r-label { color: #64748b; font-weight: 500; }
.receipt-row .r-value { font-weight: 700; color: #1e293b; }
.receipt-row .r-value code {
    font-family: 'Courier New', monospace;
    font-size: .8rem; color: #4f46e5; background: #ede9fe; padding: 2px 8px; border-radius: 5px;
}
.receipt-row.highlight .r-label { font-weight: 700; color: #1e293b; }
.receipt-row.highlight .r-value { font-size: 1.1rem; color: #059669; }

.confetti-bar {
    height: 5px;
    background: linear-gradient(90deg, #10b981, #4f46e5, #f59e0b, #ef4444, #10b981);
    background-size: 300% 100%;
    animation: slide 3s linear infinite;
}
@keyframes slide { from { background-position: 0 0; } to { background-position: 300% 0; } }

.btn-primary-green {
    background: linear-gradient(135deg, #10b981, #059669);
    color: #fff; border: none; border-radius: 12px;
    font-weight: 700; font-size: .97rem; padding: 13px;
    width: 100%; text-decoration: none; display: block; text-align: center;
    transition: all .2s; margin-bottom: 10px;
}
.btn-primary-green:hover { transform: translateY(-1px); box-shadow: 0 6px 20px rgba(16,185,129,.4); color: #fff; }
.btn-secondary-outline {
    background: #f1f5f9; color: #475569; border: none; border-radius: 12px;
    font-weight: 600; font-size: .93rem; padding: 12px;
    width: 100%; text-decoration: none; display: block; text-align: center;
    transition: background .2s;
}
.btn-secondary-outline:hover { background: #e2e8f0; color: #1e293b; }
</style>
@endpush

@section('content')
<div class="success-page">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="confetti-bar"></div>
                <div class="success-card">
                    <div class="success-top">
                        <div class="check-ring"><i class="bi bi-check-lg"></i></div>
                        <h2>Payment Successful!</h2>
                        <p>Your booking is confirmed. See you at the event!</p>
                    </div>
                    <div class="success-body">
                        <div class="receipt-grid">
                            <div class="receipt-row">
                                <span class="r-label">Booking Reference</span>
                                <span class="r-value"><code>{{ $payment->booking->booking_ref }}</code></span>
                            </div>
                            <div class="receipt-row">
                                <span class="r-label">Transaction ID</span>
                                <span class="r-value"><code>{{ $payment->transaction_id }}</code></span>
                            </div>
                            <div class="receipt-row">
                                <span class="r-label">Event</span>
                                <span class="r-value">{{ Str::limit($payment->booking->event->title ?? '—', 35) }}</span>
                            </div>
                            <div class="receipt-row">
                                <span class="r-label">Seats</span>
                                <span class="r-value">{{ $payment->booking->seats_booked }}</span>
                            </div>
                            <div class="receipt-row">
                                <span class="r-label">Payment Method</span>
                                <span class="r-value">{{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }}</span>
                            </div>
                            <div class="receipt-row highlight">
                                <span class="r-label">Amount Paid</span>
                                <span class="r-value">
                                    {{ $payment->amount > 0 ? '$' . number_format($payment->amount, 2) : 'FREE' }}
                                </span>
                            </div>
                        </div>

                        <a href="{{ route('bookings.index') }}" class="btn-primary-green">
                            <i class="bi bi-ticket-perforated me-2"></i>View My Bookings
                        </a>
                        <a href="{{ route('events.index') }}" class="btn-secondary-outline">
                            <i class="bi bi-search me-2"></i>Browse More Events
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
