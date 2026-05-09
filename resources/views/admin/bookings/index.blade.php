@extends('layouts.admin')
@section('title', 'Bookings')

@push('styles')
<style>
.bookings-toolbar {
    display: flex; align-items: center; justify-content: space-between;
    gap: 16px; flex-wrap: wrap; margin-bottom: 20px;
}
.bk-count-badge {
    background: #d1fae5; color: #059669;
    border-radius: 20px; padding: 4px 12px;
    font-size: .78rem; font-weight: 700;
}
.user-cell { display: flex; align-items: center; gap: 10px; }
.user-cell .ua {
    width: 30px; height: 30px; flex-shrink: 0;
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-size: .72rem; font-weight: 800; color: #fff;
}
.amount-cell { font-weight: 800; color: #6366f1; font-size: .9rem; }
.amount-cell.free { color: #059669; }
</style>
@endpush

@section('content')

{{-- Summary strip --}}
<div class="bookings-toolbar">
    <span class="bk-count-badge">{{ $bookings->count() }} Bookings</span>
</div>

<div class="a-card">
    <div class="table-responsive">
        <table class="table a-table mb-0">
            <thead>
                <tr>
                    <th>Reference</th>
                    <th>User</th>
                    <th>Event</th>
                    <th>Seats</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th>Payment</th>
                    <th>Booked</th>
                </tr>
            </thead>
            <tbody>
                @forelse($bookings as $b)
                @php
                    $colors = ['#6366f1','#10b981','#f59e0b','#3b82f6','#8b5cf6','#ec4899'];
                    $c = $colors[$loop->index % count($colors)];
                @endphp
                <tr>
                    <td>
                        <code>{{ $b->booking_ref }}</code>
                    </td>
                    <td>
                        <div class="user-cell">
                            <div class="ua" style="background:{{ $c }}">
                                {{ strtoupper(substr($b->user->name ?? '?', 0, 1)) }}
                            </div>
                            <div>
                                <div style="font-weight:600;font-size:.875rem">{{ $b->user->name ?? '—' }}</div>
                                <div style="font-size:.72rem;color:#94a3b8">{{ $b->user->email ?? '' }}</div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div style="font-weight:600;font-size:.875rem">{{ Str::limit($b->event->title ?? '—', 30) }}</div>
                        @if($b->event)
                        <div style="font-size:.73rem;color:#94a3b8">
                            {{ \Carbon\Carbon::parse($b->event->event_date)->format('M j, Y') }}
                        </div>
                        @endif
                    </td>
                    <td>
                        <div style="font-weight:700;text-align:center">{{ $b->seats_booked }}</div>
                    </td>
                    <td>
                        <span class="amount-cell {{ $b->total_amount == 0 ? 'free' : '' }}">
                            {{ $b->total_amount > 0 ? '$'.number_format($b->total_amount, 2) : 'FREE' }}
                        </span>
                    </td>
                    <td>
                        @if($b->status === 'confirmed')
                            <span class="a-badge ab-confirmed"><i class="bi bi-check-circle"></i>Confirmed</span>
                        @elseif($b->status === 'pending')
                            <span class="a-badge ab-pending"><i class="bi bi-clock"></i>Pending</span>
                        @else
                            <span class="a-badge ab-cancelled">Cancelled</span>
                        @endif
                    </td>
                    <td>
                        @if($b->payment)
                            @if($b->payment->status === 'completed')
                                <span class="a-badge ab-active"><i class="bi bi-check-circle"></i>Paid</span>
                            @elseif($b->payment->status === 'failed')
                                <span class="a-badge ab-sold">Failed</span>
                            @else
                                <span class="a-badge ab-pending">Pending</span>
                            @endif
                        @else
                            <span style="color:#cbd5e1;font-size:.8rem">—</span>
                        @endif
                    </td>
                    <td style="white-space:nowrap;color:#64748b;font-size:.82rem">
                        {{ $b->created_at->format('M j') }}
                        <div style="font-size:.7rem;color:#94a3b8">{{ $b->created_at->format('Y') }}</div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8">
                        <div class="text-center py-5" style="color:#94a3b8">
                            <i class="bi bi-ticket-perforated" style="font-size:2.5rem;opacity:.4;display:block;margin-bottom:10px"></i>
                            <div style="font-weight:600">No bookings yet</div>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
