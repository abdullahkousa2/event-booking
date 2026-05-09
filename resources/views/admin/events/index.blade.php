@extends('layouts.admin')
@section('title', 'Events')

@push('styles')
<style>
.events-toolbar {
    display: flex; align-items: center; justify-content: space-between;
    gap: 16px; flex-wrap: wrap; margin-bottom: 20px;
}
.ev-count-badge {
    background: #ede9fe; color: #6366f1;
    border-radius: 20px; padding: 4px 12px;
    font-size: .78rem; font-weight: 700;
}
.event-row-title { font-weight: 700; font-size: .9rem; color: #1e293b; }
.event-row-id    { font-size: .72rem; color: #94a3b8; margin-top: 1px; }
.seat-bar { height: 5px; border-radius: 20px; background: #e8ecf4; width: 80px; overflow: hidden; margin-top: 4px; }
.seat-bar-fill { height: 100%; border-radius: 20px; }
.price-tag { font-weight: 800; color: #6366f1; }
.price-tag.free { color: #059669; }
.action-group { display: flex; gap: 5px; }
</style>
@endpush

@section('content')

<div class="events-toolbar">
    <div class="d-flex align-items-center gap-3">
        <span class="ev-count-badge">{{ $events->total() }} Events</span>
    </div>
    <a href="{{ route('admin.events.create') }}" class="a-btn a-btn-primary">
        <i class="bi bi-plus-lg"></i>New Event
    </a>
</div>

<div class="a-card">
    <div class="table-responsive">
        <table class="table a-table mb-0">
            <thead>
                <tr>
                    <th>Event</th>
                    <th>Date &amp; Time</th>
                    <th>Location</th>
                    <th>Price</th>
                    <th>Seats</th>
                    <th>Status</th>
                    <th style="text-align:right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($events as $event)
                @php
                    $pct  = $event->total_seats > 0 ? (($event->total_seats - $event->available_seats) / $event->total_seats) * 100 : 0;
                    $isFree = $event->price == 0;
                    $barColor = $pct > 85 ? '#ef4444' : ($pct > 55 ? '#f59e0b' : '#10b981');
                @endphp
                <tr>
                    <td>
                        <div class="event-row-title">{{ Str::limit($event->title, 42) }}</div>
                        <div class="event-row-id">ID #{{ $event->id }}</div>
                    </td>
                    <td>
                        <div style="font-weight:600;font-size:.875rem">
                            {{ \Carbon\Carbon::parse($event->event_date)->format('M j, Y') }}
                        </div>
                        <div style="font-size:.75rem;color:#94a3b8">
                            {{ \Carbon\Carbon::parse($event->event_date)->format('g:i A') }}
                        </div>
                    </td>
                    <td style="color:#475569;max-width:160px">
                        <div style="font-size:.875rem;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">
                            {{ $event->location }}
                        </div>
                    </td>
                    <td>
                        @if($isFree)
                            <span class="a-badge ab-free">FREE</span>
                        @else
                            <span class="price-tag">${{ number_format($event->price, 2) }}</span>
                        @endif
                    </td>
                    <td style="min-width:110px">
                        <div style="font-weight:700;font-size:.875rem">
                            {{ $event->available_seats }}
                            <span style="color:#94a3b8;font-weight:400">/{{ $event->total_seats }}</span>
                        </div>
                        <div class="seat-bar">
                            <div class="seat-bar-fill" style="width:{{ $pct }}%;background:{{ $barColor }}"></div>
                        </div>
                    </td>
                    <td>
                        @if($event->status === 'active')
                            <span class="a-badge ab-active"><i class="bi bi-circle-fill" style="font-size:.45rem"></i>Active</span>
                        @elseif($event->status === 'cancelled')
                            <span class="a-badge ab-sold">Cancelled</span>
                        @else
                            <span class="a-badge ab-cancelled">Completed</span>
                        @endif
                    </td>
                    <td>
                        <div class="action-group justify-content-end">
                            <a href="{{ route('admin.events.edit', $event->id) }}"
                               class="a-btn a-btn-ghost a-btn-icon" title="Edit">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form method="POST" action="{{ route('admin.events.destroy', $event->id) }}"
                                  class="d-inline" onsubmit="return confirm('Delete {{ addslashes($event->title) }}? This cannot be undone.')">
                                @csrf @method('DELETE')
                                <button type="submit" class="a-btn a-btn-danger a-btn-icon" title="Delete">
                                    <i class="bi bi-trash3"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7">
                        <div class="text-center py-5" style="color:#94a3b8">
                            <i class="bi bi-calendar-x" style="font-size:2.5rem;opacity:.4;display:block;margin-bottom:10px"></i>
                            <div style="font-weight:600">No events yet</div>
                            <a href="{{ route('admin.events.create') }}" class="a-btn a-btn-primary mt-3" style="display:inline-flex">
                                <i class="bi bi-plus-lg"></i>Create First Event
                            </a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($events->hasPages())
    <div class="p-4 d-flex justify-content-between align-items-center" style="border-top:1px solid #f1f5f9">
        <span style="font-size:.78rem;color:#94a3b8">
            Showing {{ $events->firstItem() }}–{{ $events->lastItem() }} of {{ $events->total() }}
        </span>
        {{ $events->links() }}
    </div>
    @endif
</div>

@endsection
