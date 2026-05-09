@extends('layouts.app')
@section('title', 'Complete Payment — EventSY')

@push('styles')
<style>
.payment-header {
    background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 100%);
    padding: 40px 0;
    position: relative; overflow: hidden;
}
.payment-header::before {
    content: '';
    position: absolute; inset: 0;
    background: radial-gradient(ellipse at 60% 50%, rgba(99,102,241,.2) 0%, transparent 65%);
}
.payment-header-content { position: relative; z-index: 1; }
.payment-header h1 { color: #fff; font-size: 1.6rem; font-weight: 800; margin: 0; }
.payment-header p { color: rgba(255,255,255,.6); margin: 6px 0 0; font-size: .88rem; }

.order-card {
    background: linear-gradient(135deg, #4f46e5, #7c3aed);
    border-radius: 18px;
    padding: 24px;
    color: #fff;
    position: relative; overflow: hidden;
    margin-bottom: 24px;
}
.order-card::before {
    content: '';
    position: absolute; inset: 0;
    background: radial-gradient(ellipse at 90% 10%, rgba(255,255,255,.15) 0%, transparent 55%);
}
.order-card-content { position: relative; z-index: 1; }
.order-card .label { font-size: .72rem; opacity: .7; font-weight: 600; text-transform: uppercase; letter-spacing: .4px; }
.order-card .amount { font-size: 2.2rem; font-weight: 800; line-height: 1; }
.order-card .ref-chip {
    display: inline-block;
    background: rgba(255,255,255,.15); border-radius: 8px;
    padding: 3px 10px; font-family: 'Courier New', monospace; font-size: .8rem;
}

.payment-form-card {
    background: #fff;
    border-radius: 18px;
    border: 1px solid #e2e8f0;
    box-shadow: 0 4px 24px rgba(0,0,0,.07);
}
.mock-notice {
    background: linear-gradient(135deg, #dbeafe, #ede9fe);
    border: 1px solid #bfdbfe;
    border-radius: 12px;
    padding: 14px 18px;
    font-size: .84rem;
    color: #1e40af;
    display: flex; align-items: center; gap: 10px;
}
.mock-notice i { font-size: 1.2rem; flex-shrink: 0; }

.method-grid {
    display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 10px; margin-bottom: 20px;
}
.method-option { display: none; }
.method-label {
    display: flex; flex-direction: column; align-items: center; justify-content: center;
    gap: 6px; border: 2px solid #e2e8f0; border-radius: 12px;
    padding: 14px 10px; cursor: pointer; transition: all .2s;
    font-size: .8rem; font-weight: 600; color: #475569;
}
.method-label i { font-size: 1.5rem; color: #94a3b8; transition: color .2s; }
.method-option:checked + .method-label {
    border-color: #4f46e5;
    background: #ede9fe;
    color: #4f46e5;
}
.method-option:checked + .method-label i { color: #4f46e5; }

.card-fields { transition: opacity .25s; }

.fake-card {
    background: linear-gradient(135deg, #0f172a, #1e1b4b);
    border-radius: 14px; padding: 20px; color: #fff; margin-bottom: 20px;
}
.fake-card .chip-img {
    width: 40px; height: 28px;
    background: linear-gradient(135deg, #fbbf24, #f59e0b);
    border-radius: 5px; margin-bottom: 16px;
}
.fake-card .card-num {
    font-family: 'Courier New', monospace;
    font-size: 1.1rem; letter-spacing: 3px; margin-bottom: 14px;
}
.fake-card .card-footer-row { display: flex; justify-content: space-between; font-size: .78rem; opacity: .7; }

.btn-pay-submit {
    background: linear-gradient(135deg, #10b981, #059669);
    color: #fff; border: none; border-radius: 12px;
    font-weight: 700; font-size: 1.05rem; padding: 15px;
    width: 100%; transition: all .2s;
}
.btn-pay-submit:hover { transform: translateY(-1px); box-shadow: 0 8px 24px rgba(16,185,129,.4); color: #fff; }
.security-notice {
    display: flex; align-items: center; justify-content: center; gap: 6px;
    font-size: .78rem; color: #94a3b8; margin-top: 12px;
}
</style>
@endpush

@section('content')

{{-- Header --}}
<div class="payment-header">
    <div class="container payment-header-content">
        <h1><i class="bi bi-credit-card me-2"></i>Complete Payment</h1>
        <p>Secure checkout for your event booking</p>
    </div>
</div>

<div style="background:#f8fafc;padding:40px 0">
    <div class="container">
        <div class="row g-4 justify-content-center">
            <div class="col-lg-6">

                {{-- Order Summary --}}
                <div class="order-card">
                    <div class="order-card-content">
                        <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
                            <div>
                                <div class="label mb-1">Order Summary</div>
                                <div style="font-weight:800;font-size:1.05rem">{{ $booking->event->title ?? 'Event' }}</div>
                                <div style="font-size:.82rem;opacity:.75;margin-top:4px">
                                    {{ $booking->seats_booked }} seat{{ $booking->seats_booked > 1 ? 's' : '' }}
                                    &nbsp;·&nbsp;
                                    <span class="ref-chip">{{ $booking->booking_ref }}</span>
                                </div>
                            </div>
                            <div style="text-align:right">
                                <div class="label mb-1">Total Due</div>
                                <div class="amount">
                                    {{ $booking->total_amount > 0 ? '$' . number_format($booking->total_amount, 2) : 'FREE' }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Payment Form --}}
                <div class="payment-form-card p-4">
                    <div class="mock-notice mb-4">
                        <i class="bi bi-shield-check"></i>
                        <span>This is a <strong>simulated payment</strong> for demonstration purposes. No real charge will be made.</span>
                    </div>

                    <form method="POST" action="{{ route('payments.store') }}" id="payForm">
                        @csrf
                        <input type="hidden" name="booking_id" value="{{ $booking->id }}">

                        <div class="mb-4">
                            <label class="form-label mb-3" style="font-weight:700">Payment Method</label>
                            <div class="method-grid">
                                <div>
                                    <input type="radio" name="payment_method" id="m_credit" value="credit_card" class="method-option" checked onchange="toggleCardFields(true)">
                                    <label for="m_credit" class="method-label">
                                        <i class="bi bi-credit-card-2-front"></i>Credit Card
                                    </label>
                                </div>
                                <div>
                                    <input type="radio" name="payment_method" id="m_debit" value="debit_card" class="method-option" onchange="toggleCardFields(true)">
                                    <label for="m_debit" class="method-label">
                                        <i class="bi bi-credit-card"></i>Debit Card
                                    </label>
                                </div>
                                <div>
                                    <input type="radio" name="payment_method" id="m_bank" value="bank_transfer" class="method-option" onchange="toggleCardFields(false)">
                                    <label for="m_bank" class="method-label">
                                        <i class="bi bi-bank"></i>Bank Transfer
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="card-fields" id="cardFields">
                            <div class="fake-card">
                                <div class="chip-img"></div>
                                <div class="card-num">4242 4242 4242 4242</div>
                                <div class="card-footer-row">
                                    <span>{{ strtoupper(auth()->user()->name) }}</span>
                                    <span>12/28</span>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Cardholder Name</label>
                                <input type="text" name="card_name" class="form-control"
                                       placeholder="As shown on card"
                                       value="{{ auth()->user()->name }}">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Card Number (mock)</label>
                                <input type="text" name="card_number" class="form-control"
                                       placeholder="4242 4242 4242 4242"
                                       value="4242 4242 4242 4242">
                            </div>
                            <div class="row mb-3">
                                <div class="col-6">
                                    <label class="form-label">Expiry</label>
                                    <input type="text" class="form-control" placeholder="MM/YY" value="12/28">
                                </div>
                                <div class="col-6">
                                    <label class="form-label">CVV</label>
                                    <input type="text" class="form-control" placeholder="123" value="123">
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn-pay-submit">
                            <i class="bi bi-lock-fill me-2"></i>
                            Pay {{ $booking->total_amount > 0 ? '$' . number_format($booking->total_amount, 2) : 'FREE' }}
                        </button>
                        <div class="security-notice">
                            <i class="bi bi-shield-lock-fill" style="color:#10b981"></i>
                            Secured · 256-bit TLS · Mock gateway
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function toggleCardFields(show) {
    const fields = document.getElementById('cardFields');
    fields.style.opacity = show ? '1' : '0.3';
    fields.style.pointerEvents = show ? 'auto' : 'none';
}
</script>
@endpush
@endsection
