@extends('layouts.admin')
@section('title', 'Dashboard')

@push('styles')
<style>
/* ── Hero Stat Cards ── */
.kpi-card {
    border-radius: 18px;
    padding: 22px 24px;
    position: relative; overflow: hidden;
    color: #fff; min-height: 130px;
    display: flex; flex-direction: column; justify-content: space-between;
}
.kpi-card::after {
    content: '';
    position: absolute; top: -30px; right: -30px;
    width: 120px; height: 120px;
    background: rgba(255,255,255,.08);
    border-radius: 50%;
}
.kpi-card .kpi-icon {
    width: 44px; height: 44px;
    background: rgba(255,255,255,.18);
    border-radius: 12px;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.2rem; margin-bottom: 14px;
    backdrop-filter: blur(6px);
}
.kpi-card .kpi-val  { font-size: 2.1rem; font-weight: 900; line-height: 1; letter-spacing: -1px; }
.kpi-card .kpi-lbl  { font-size: .75rem; font-weight: 600; opacity: .75; text-transform: uppercase; letter-spacing: .5px; margin-top: 4px; }
.kpi-card .kpi-foot {
    display: flex; align-items: center; gap: 6px;
    font-size: .74rem; opacity: .7; margin-top: 10px;
}
.kc-indigo { background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%); box-shadow: 0 8px 28px rgba(99,102,241,.4); }
.kc-green  { background: linear-gradient(135deg, #059669 0%, #10b981 100%); box-shadow: 0 8px 28px rgba(16,185,129,.35); }
.kc-blue   { background: linear-gradient(135deg, #2563eb 0%, #3b82f6 100%); box-shadow: 0 8px 28px rgba(59,130,246,.35); }
.kc-amber  { background: linear-gradient(135deg, #d97706 0%, #f59e0b 100%); box-shadow: 0 8px 28px rgba(245,158,11,.35); }

/* ── Mini Stat Cards ── */
.mini-stat {
    background: #fff;
    border-radius: 14px;
    border: 1px solid #e8ecf4;
    padding: 16px 20px;
    display: flex; align-items: center; gap: 14px;
    box-shadow: 0 1px 4px rgba(0,0,0,.04);
    transition: transform .2s, box-shadow .2s;
}
.mini-stat:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(0,0,0,.08); }
.ms-icon {
    width: 42px; height: 42px; flex-shrink: 0;
    border-radius: 11px;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.1rem;
}
.ms-val { font-size: 1.55rem; font-weight: 800; line-height: 1; }
.ms-lbl { font-size: .72rem; font-weight: 600; text-transform: uppercase; letter-spacing: .4px; color: #94a3b8; margin-top: 2px; }

/* ── Chart Card ── */
.chart-wrap { position: relative; height: 200px; }

/* ── Booking Status Donut ── */
.donut-wrap { position: relative; }
.donut-center {
    position: absolute; top: 50%; left: 50%;
    transform: translate(-50%, -50%);
    text-align: center; pointer-events: none;
}
.donut-center .dc-val { font-size: 1.6rem; font-weight: 900; color: #0f172a; line-height: 1; }
.donut-center .dc-lbl { font-size: .7rem; color: #94a3b8; font-weight: 600; }
.legend-item { display: flex; align-items: center; gap: 8px; margin-bottom: 10px; }
.legend-dot { width: 10px; height: 10px; border-radius: 50%; flex-shrink: 0; }
.legend-lbl { font-size: .82rem; color: #475569; flex: 1; }
.legend-val { font-weight: 700; color: #1e293b; font-size: .88rem; }

/* ── Activity Feed ── */
.activity-item {
    display: flex; align-items: flex-start; gap: 12px;
    padding: 14px 0;
    border-bottom: 1px solid #f1f5f9;
}
.activity-item:last-child { border-bottom: none; padding-bottom: 0; }
.act-avatar {
    width: 36px; height: 36px; flex-shrink: 0;
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-size: .78rem; font-weight: 800; color: #fff;
}
.act-info .act-name  { font-weight: 700; font-size: .88rem; color: #1e293b; }
.act-info .act-event { font-size: .78rem; color: #64748b; margin-top: 1px; }
.act-info .act-meta  { font-size: .73rem; color: #94a3b8; margin-top: 2px; }
.act-right { margin-left: auto; text-align: right; flex-shrink: 0; }
.act-amount { font-weight: 800; font-size: .9rem; color: #6366f1; }

/* ── Quick Action Cards ── */
.qa-card {
    background: #fff;
    border-radius: 14px;
    border: 1px solid #e8ecf4;
    padding: 18px;
    text-decoration: none;
    display: flex; align-items: center; gap: 14px;
    transition: all .18s;
    box-shadow: 0 1px 4px rgba(0,0,0,.04);
}
.qa-card:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(99,102,241,.12); border-color: #c7d2fe; }
.qa-icon {
    width: 46px; height: 46px; flex-shrink: 0;
    border-radius: 12px;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.2rem;
}
.qa-title { font-weight: 700; font-size: .9rem; color: #1e293b; }
.qa-sub   { font-size: .75rem; color: #94a3b8; margin-top: 2px; }
</style>
@endpush

@section('content')

@php
    $totalBookings = $stats['total_bookings'];
    $confirmedPct  = $totalBookings > 0 ? round(($stats['confirmed'] / $totalBookings) * 100) : 0;
    $pendingPct    = $totalBookings > 0 ? round(($stats['pending']   / $totalBookings) * 100) : 0;
    $cancelledPct  = $totalBookings > 0 ? round(($stats['cancelled'] / $totalBookings) * 100) : 0;

    $avatarColors = ['#6366f1','#10b981','#f59e0b','#ef4444','#3b82f6','#8b5cf6','#ec4899'];
@endphp

{{-- Welcome Strip --}}
<div class="d-flex align-items-center justify-content-between mb-5">
    <div>
        <h2 style="font-size:1.55rem;font-weight:900;color:#0f172a;margin:0">
            Good {{ now()->hour < 12 ? 'morning' : (now()->hour < 17 ? 'afternoon' : 'evening') }}, {{ explode(' ', auth()->user()->name)[0] }} 👋
        </h2>
        <p style="color:#94a3b8;margin:4px 0 0;font-size:.88rem">Here's what's happening with EventSY today.</p>
    </div>
    <a href="{{ route('admin.events.create') }}" class="a-btn a-btn-primary" style="padding:10px 20px;font-size:.9rem">
        <i class="bi bi-plus-lg"></i>Create Event
    </a>
</div>

{{-- ── KPI Cards ── --}}
<div class="row g-3 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="kpi-card kc-indigo">
            <div>
                <div class="kpi-icon"><i class="bi bi-calendar3"></i></div>
                <div class="kpi-val">{{ $stats['events']['total'] }}</div>
                <div class="kpi-lbl">Total Events</div>
            </div>
            <div class="kpi-foot">
                <i class="bi bi-check-circle"></i>{{ $stats['events']['active'] }} active now
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="kpi-card kc-green">
            <div>
                <div class="kpi-icon"><i class="bi bi-ticket-perforated"></i></div>
                <div class="kpi-val">{{ $stats['confirmed'] }}</div>
                <div class="kpi-lbl">Confirmed Bookings</div>
            </div>
            <div class="kpi-foot">
                <i class="bi bi-arrow-up-right"></i>{{ $stats['total_bookings'] }} total bookings
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="kpi-card kc-blue">
            <div>
                <div class="kpi-icon"><i class="bi bi-people-fill"></i></div>
                <div class="kpi-val">{{ $stats['total_users'] }}</div>
                <div class="kpi-lbl">Registered Users</div>
            </div>
            <div class="kpi-foot">
                <i class="bi bi-globe2"></i>Syria-wide attendees
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="kpi-card kc-amber">
            <div>
                <div class="kpi-icon"><i class="bi bi-cash-stack"></i></div>
                <div class="kpi-val">${{ number_format($stats['total_revenue'], 0) }}</div>
                <div class="kpi-lbl">Total Revenue</div>
            </div>
            <div class="kpi-foot">
                <i class="bi bi-credit-card"></i>From paid bookings
            </div>
        </div>
    </div>
</div>

{{-- ── Mini Stats Row ── --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3">
        <div class="mini-stat">
            <div class="ms-icon" style="background:#d1fae5"><i class="bi bi-calendar-check" style="color:#059669"></i></div>
            <div>
                <div class="ms-val" style="color:#059669">{{ $stats['events']['active'] }}</div>
                <div class="ms-lbl">Active Events</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="mini-stat">
            <div class="ms-icon" style="background:#ede9fe"><i class="bi bi-ticket" style="color:#6366f1"></i></div>
            <div>
                <div class="ms-val" style="color:#1e293b">{{ $stats['total_bookings'] }}</div>
                <div class="ms-lbl">All Bookings</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="mini-stat">
            <div class="ms-icon" style="background:#fef3c7"><i class="bi bi-hourglass-split" style="color:#d97706"></i></div>
            <div>
                <div class="ms-val" style="color:#d97706">{{ $stats['pending'] }}</div>
                <div class="ms-lbl">Awaiting Payment</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="mini-stat">
            <div class="ms-icon" style="background:#fee2e2"><i class="bi bi-x-circle" style="color:#dc2626"></i></div>
            <div>
                <div class="ms-val" style="color:#dc2626">{{ $stats['cancelled'] }}</div>
                <div class="ms-lbl">Cancelled</div>
            </div>
        </div>
    </div>
</div>

{{-- ── Charts + Activity Row ── --}}
<div class="row g-4 mb-4">

    {{-- Booking Status Chart --}}
    <div class="col-lg-4">
        <div class="a-card h-100">
            <div class="a-card-header pb-0 p-4">
                <div>
                    <div class="a-card-title">Booking Overview</div>
                    <div class="a-card-sub">Status breakdown</div>
                </div>
            </div>
            <div class="p-4">
                <div class="donut-wrap mb-4" style="max-width:180px;margin:0 auto;position:relative">
                    <canvas id="donutChart" height="180"></canvas>
                    <div class="donut-center">
                        <div class="dc-val">{{ $totalBookings }}</div>
                        <div class="dc-lbl">Total</div>
                    </div>
                </div>
                <div>
                    <div class="legend-item">
                        <div class="legend-dot" style="background:#6366f1"></div>
                        <div class="legend-lbl">Confirmed</div>
                        <div class="legend-val">{{ $stats['confirmed'] }}</div>
                        <div style="font-size:.72rem;color:#94a3b8;margin-left:4px">({{ $confirmedPct }}%)</div>
                    </div>
                    <div class="legend-item">
                        <div class="legend-dot" style="background:#f59e0b"></div>
                        <div class="legend-lbl">Pending</div>
                        <div class="legend-val">{{ $stats['pending'] }}</div>
                        <div style="font-size:.72rem;color:#94a3b8;margin-left:4px">({{ $pendingPct }}%)</div>
                    </div>
                    <div class="legend-item">
                        <div class="legend-dot" style="background:#94a3b8"></div>
                        <div class="legend-lbl">Cancelled</div>
                        <div class="legend-val">{{ $stats['cancelled'] }}</div>
                        <div style="font-size:.72rem;color:#94a3b8;margin-left:4px">({{ $cancelledPct }}%)</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Revenue by Event Chart --}}
    <div class="col-lg-8">
        <div class="a-card h-100">
            <div class="a-card-header p-4 pb-0">
                <div>
                    <div class="a-card-title">Revenue by Event</div>
                    <div class="a-card-sub">Top events by booking value</div>
                </div>
            </div>
            <div class="p-4">
                <div class="chart-wrap">
                    <canvas id="revenueChart"></canvas>
                </div>
            </div>
        </div>
    </div>

</div>

{{-- ── Recent Activity + Quick Actions ── --}}
<div class="row g-4">

    {{-- Recent Bookings Feed --}}
    <div class="col-lg-8">
        <div class="a-card">
            <div class="a-card-header p-4" style="border-bottom:1px solid #f1f5f9">
                <div>
                    <div class="a-card-title">Recent Bookings</div>
                    <div class="a-card-sub">Latest activity</div>
                </div>
                <a href="{{ route('admin.bookings') }}" class="a-btn a-btn-ghost" style="font-size:.78rem;padding:7px 14px">
                    View All <i class="bi bi-arrow-right"></i>
                </a>
            </div>
            <div class="p-4">
                @forelse($stats['recent_bookings'] as $b)
                @php $color = $avatarColors[($loop->index) % count($avatarColors)]; @endphp
                <div class="activity-item">
                    <div class="act-avatar" style="background:{{ $color }}">
                        {{ strtoupper(substr($b->user->name ?? '?', 0, 1)) }}
                    </div>
                    <div class="act-info">
                        <div class="act-name">{{ $b->user->name ?? 'Unknown' }}</div>
                        <div class="act-event">
                            <i class="bi bi-calendar3 me-1" style="color:#6366f1"></i>
                            {{ Str::limit($b->event->title ?? '—', 40) }}
                        </div>
                        <div class="act-meta">
                            <code style="font-size:.7rem">{{ $b->booking_ref }}</code>
                            &nbsp;·&nbsp;
                            {{ $b->seats_booked }} seat{{ $b->seats_booked > 1 ? 's' : '' }}
                            &nbsp;·&nbsp;
                            {{ $b->created_at->diffForHumans() }}
                        </div>
                    </div>
                    <div class="act-right">
                        <div class="act-amount">
                            {{ $b->total_amount > 0 ? '$'.number_format($b->total_amount,2) : 'FREE' }}
                        </div>
                        @if($b->status === 'confirmed')
                            <span class="a-badge ab-confirmed mt-1"><i class="bi bi-check-circle"></i>Confirmed</span>
                        @elseif($b->status === 'pending')
                            <span class="a-badge ab-pending mt-1"><i class="bi bi-clock"></i>Pending</span>
                        @else
                            <span class="a-badge ab-cancelled mt-1">Cancelled</span>
                        @endif
                    </div>
                </div>
                @empty
                <div class="text-center py-4" style="color:#94a3b8">
                    <i class="bi bi-ticket-perforated" style="font-size:2rem;display:block;margin-bottom:8px;opacity:.4"></i>
                    No bookings yet
                </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Quick Actions + Event Health --}}
    <div class="col-lg-4">

        {{-- Quick Actions --}}
        <div class="a-card mb-4">
            <div class="a-card-header p-4" style="border-bottom:1px solid #f1f5f9">
                <div class="a-card-title">Quick Actions</div>
            </div>
            <div class="p-4 d-flex flex-column gap-3">
                <a href="{{ route('admin.events.create') }}" class="qa-card">
                    <div class="qa-icon" style="background:#ede9fe"><i class="bi bi-plus-circle-fill" style="color:#6366f1"></i></div>
                    <div>
                        <div class="qa-title">Create Event</div>
                        <div class="qa-sub">Add a new event</div>
                    </div>
                    <i class="bi bi-chevron-right ms-auto" style="color:#cbd5e1;font-size:.8rem"></i>
                </a>
                <a href="{{ route('admin.events.index') }}" class="qa-card">
                    <div class="qa-icon" style="background:#dbeafe"><i class="bi bi-calendar3" style="color:#2563eb"></i></div>
                    <div>
                        <div class="qa-title">Manage Events</div>
                        <div class="qa-sub">{{ $stats['events']['total'] }} events total</div>
                    </div>
                    <i class="bi bi-chevron-right ms-auto" style="color:#cbd5e1;font-size:.8rem"></i>
                </a>
                <a href="{{ route('admin.bookings') }}" class="qa-card">
                    <div class="qa-icon" style="background:#d1fae5"><i class="bi bi-ticket-perforated" style="color:#059669"></i></div>
                    <div>
                        <div class="qa-title">All Bookings</div>
                        <div class="qa-sub">{{ $stats['total_bookings'] }} total</div>
                    </div>
                    <i class="bi bi-chevron-right ms-auto" style="color:#cbd5e1;font-size:.8rem"></i>
                </a>
                <a href="{{ route('events.index') }}" target="_blank" class="qa-card">
                    <div class="qa-icon" style="background:#fef3c7"><i class="bi bi-globe2" style="color:#d97706"></i></div>
                    <div>
                        <div class="qa-title">View Public Site</div>
                        <div class="qa-sub">Opens in new tab</div>
                    </div>
                    <i class="bi bi-box-arrow-up-right ms-auto" style="color:#cbd5e1;font-size:.8rem"></i>
                </a>
            </div>
        </div>

        {{-- Event Health --}}
        <div class="a-card">
            <div class="a-card-header p-4" style="border-bottom:1px solid #f1f5f9">
                <div class="a-card-title">Event Health</div>
            </div>
            <div class="p-4 d-flex flex-column gap-3">
                <div>
                    <div class="d-flex justify-content-between mb-1">
                        <span style="font-size:.8rem;font-weight:600;color:#475569">Active</span>
                        <span style="font-size:.8rem;font-weight:700;color:#059669">{{ $stats['events']['active'] }}</span>
                    </div>
                    <div class="progress" style="height:7px">
                        @php $ap = $stats['events']['total'] > 0 ? ($stats['events']['active']/$stats['events']['total'])*100 : 0 @endphp
                        <div class="progress-bar bg-success" style="width:{{ $ap }}%"></div>
                    </div>
                </div>
                <div>
                    <div class="d-flex justify-content-between mb-1">
                        <span style="font-size:.8rem;font-weight:600;color:#475569">Booking Rate</span>
                        <span style="font-size:.8rem;font-weight:700;color:#6366f1">{{ $confirmedPct }}%</span>
                    </div>
                    <div class="progress" style="height:7px">
                        <div class="progress-bar" style="width:{{ $confirmedPct }}%;background:#6366f1"></div>
                    </div>
                </div>
                <div>
                    <div class="d-flex justify-content-between mb-1">
                        <span style="font-size:.8rem;font-weight:600;color:#475569">Pending Collection</span>
                        <span style="font-size:.8rem;font-weight:700;color:#d97706">{{ $pendingPct }}%</span>
                    </div>
                    <div class="progress" style="height:7px">
                        <div class="progress-bar bg-warning" style="width:{{ $pendingPct }}%"></div>
                    </div>
                </div>
                <div>
                    <div class="d-flex justify-content-between mb-1">
                        <span style="font-size:.8rem;font-weight:600;color:#475569">Cancellation Rate</span>
                        <span style="font-size:.8rem;font-weight:700;color:#dc2626">{{ $cancelledPct }}%</span>
                    </div>
                    <div class="progress" style="height:7px">
                        <div class="progress-bar bg-danger" style="width:{{ $cancelledPct }}%"></div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.2/dist/chart.umd.min.js"></script>
<script>
Chart.defaults.font.family = "'Inter', sans-serif";

// ── Donut Chart ──
new Chart(document.getElementById('donutChart'), {
    type: 'doughnut',
    data: {
        labels: ['Confirmed', 'Pending', 'Cancelled'],
        datasets: [{
            data: [{{ $stats['confirmed'] }}, {{ $stats['pending'] }}, {{ $stats['cancelled'] }}],
            backgroundColor: ['#6366f1', '#f59e0b', '#94a3b8'],
            borderWidth: 0,
            hoverOffset: 6
        }]
    },
    options: {
        cutout: '72%',
        plugins: { legend: { display: false }, tooltip: { callbacks: {
            label: ctx => ` ${ctx.label}: ${ctx.raw}`
        }}},
        animation: { animateScale: true, duration: 900 }
    }
});

// ── Revenue Bar Chart ──
@php
    $chartData = [];
    foreach($stats['recent_bookings'] as $b) {
        $title = Str::limit($b->event->title ?? 'Unknown', 20);
        if (!isset($chartData[$title])) $chartData[$title] = 0;
        $chartData[$title] += $b->total_amount;
    }
    arsort($chartData);
    $chartData = array_slice($chartData, 0, 6, true);
@endphp

new Chart(document.getElementById('revenueChart'), {
    type: 'bar',
    data: {
        labels: {!! json_encode(array_keys($chartData)) !!},
        datasets: [{
            label: 'Revenue ($)',
            data: {!! json_encode(array_values($chartData)) !!},
            backgroundColor: [
                'rgba(99,102,241,.85)', 'rgba(139,92,246,.85)',
                'rgba(16,185,129,.85)', 'rgba(59,130,246,.85)',
                'rgba(245,158,11,.85)', 'rgba(236,72,153,.85)'
            ],
            borderRadius: 8,
            borderSkipped: false,
        }]
    },
    options: {
        responsive: true, maintainAspectRatio: false,
        plugins: { legend: { display: false } },
        scales: {
            x: { grid: { display: false }, ticks: { font: { size: 11 }, color: '#94a3b8' } },
            y: { grid: { color: '#f1f5f9' }, ticks: {
                font: { size: 11 }, color: '#94a3b8',
                callback: v => '$' + v
            }, border: { display: false } }
        },
        animation: { duration: 900 }
    }
});
</script>
@endpush
