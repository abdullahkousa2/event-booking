@extends('layouts.admin')
@section('title', 'Edit Event')

@push('styles')
<style>
.form-section {
    background: #fff;
    border-radius: 16px;
    border: 1px solid #e8ecf4;
    box-shadow: 0 1px 4px rgba(0,0,0,.04);
    margin-bottom: 20px;
    overflow: hidden;
}
.form-section-header {
    display: flex; align-items: center; gap: 12px;
    padding: 18px 22px;
    border-bottom: 1px solid #f1f5f9;
    background: #fafbfe;
}
.fsh-icon {
    width: 36px; height: 36px;
    border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    font-size: .95rem;
}
.fsh-title { font-weight: 800; font-size: .92rem; color: #0f172a; }
.fsh-sub   { font-size: .74rem; color: #94a3b8; margin-top: 1px; }
.form-section-body { padding: 22px; }

.ev-meta-pill {
    display: inline-flex; align-items: center; gap: 6px;
    background: #f1f5f9; border-radius: 8px;
    padding: 5px 12px; font-size: .78rem; color: #475569; font-weight: 500;
}
.danger-zone {
    background: #fff5f5;
    border: 1.5px solid #fecaca;
    border-radius: 14px;
    padding: 18px 22px;
}
</style>
@endpush

@section('content')

@php
    $pct = $event->total_seats > 0
        ? (($event->total_seats - $event->available_seats) / $event->total_seats) * 100
        : 0;
@endphp

<div class="d-flex align-items-center gap-3 mb-4">
    <a href="{{ route('admin.events.index') }}" class="a-btn a-btn-ghost a-btn-icon">
        <i class="bi bi-arrow-left"></i>
    </a>
    <div>
        <div style="font-size:.75rem;color:#94a3b8;font-weight:600">Events / Edit</div>
        <div style="font-weight:800;font-size:.95rem;color:#0f172a">{{ Str::limit($event->title, 50) }}</div>
    </div>
</div>

{{-- Event health strip --}}
<div class="a-card p-4 mb-4">
    <div class="d-flex flex-wrap gap-3 align-items-center">
        <div class="ev-meta-pill">
            <i class="bi bi-hash" style="color:#6366f1"></i>ID #{{ $event->id }}
        </div>
        <div class="ev-meta-pill">
            <i class="bi bi-people" style="color:#059669"></i>
            {{ $event->available_seats }} / {{ $event->total_seats }} seats available
        </div>
        <div class="ev-meta-pill">
            <i class="bi bi-calendar3" style="color:#2563eb"></i>
            {{ \Carbon\Carbon::parse($event->event_date)->format('M j, Y · g:i A') }}
        </div>
        <div class="ev-meta-pill">
            @if($event->status === 'active')
                <i class="bi bi-circle-fill" style="color:#10b981;font-size:.55rem"></i>Active
            @elseif($event->status === 'cancelled')
                <i class="bi bi-circle-fill" style="color:#ef4444;font-size:.55rem"></i>Cancelled
            @else
                <i class="bi bi-circle-fill" style="color:#94a3b8;font-size:.55rem"></i>Completed
            @endif
        </div>

        {{-- Seat bar --}}
        <div class="flex-grow-1" style="min-width:160px">
            <div class="d-flex justify-content-between mb-1">
                <span style="font-size:.72rem;color:#94a3b8;font-weight:600">Seat Occupancy</span>
                <span style="font-size:.72rem;font-weight:700;color:{{ $pct > 85 ? '#ef4444' : ($pct > 55 ? '#f59e0b' : '#10b981') }}">
                    {{ round($pct) }}%
                </span>
            </div>
            <div class="progress" style="height:6px">
                <div class="progress-bar {{ $pct > 85 ? 'bg-danger' : ($pct > 55 ? 'bg-warning' : 'bg-success') }}"
                     style="width:{{ $pct }}%"></div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <form method="POST" action="{{ route('admin.events.update', $event->id) }}">
            @csrf @method('PUT')

            {{-- Basic Info --}}
            <div class="form-section">
                <div class="form-section-header">
                    <div class="fsh-icon" style="background:#ede9fe"><i class="bi bi-type" style="color:#6366f1"></i></div>
                    <div>
                        <div class="fsh-title">Basic Information</div>
                        <div class="fsh-sub">Title and description</div>
                    </div>
                </div>
                <div class="form-section-body">
                    <div class="mb-3">
                        <label class="form-label">Event Title *</label>
                        <input type="text" name="title" class="form-control"
                               value="{{ old('title', $event->title) }}" required>
                    </div>
                    <div>
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="4">{{ old('description', $event->description) }}</textarea>
                    </div>
                </div>
            </div>

            {{-- Location & Date --}}
            <div class="form-section">
                <div class="form-section-header">
                    <div class="fsh-icon" style="background:#dbeafe"><i class="bi bi-geo-alt" style="color:#2563eb"></i></div>
                    <div>
                        <div class="fsh-title">Location &amp; Schedule</div>
                        <div class="fsh-sub">Where and when</div>
                    </div>
                </div>
                <div class="form-section-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Location *</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-geo-alt"></i></span>
                                <input type="text" name="location" class="form-control"
                                       value="{{ old('location', $event->location) }}" required
                                       style="border-left:none;border-radius:0 9px 9px 0">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Date &amp; Time *</label>
                            <input type="datetime-local" name="event_date" class="form-control"
                                   value="{{ old('event_date', \Carbon\Carbon::parse($event->event_date)->format('Y-m-d\TH:i')) }}"
                                   required>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Pricing & Status --}}
            <div class="form-section">
                <div class="form-section-header">
                    <div class="fsh-icon" style="background:#d1fae5"><i class="bi bi-tag" style="color:#059669"></i></div>
                    <div>
                        <div class="fsh-title">Pricing &amp; Status</div>
                        <div class="fsh-sub">Seat capacity cannot be changed after creation</div>
                    </div>
                </div>
                <div class="form-section-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Price per Seat ($)</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" name="price" class="form-control"
                                       step="0.01" min="0" value="{{ old('price', $event->price) }}" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Total Seats</label>
                            <input type="text" class="form-control"
                                   value="{{ $event->total_seats }}" disabled
                                   style="background:#f8fafc;color:#94a3b8">
                            <div class="form-text">{{ $event->available_seats }} available · {{ $event->total_seats - $event->available_seats }} booked</div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Status *</label>
                            <select name="status" class="form-select">
                                <option value="active"    {{ $event->status === 'active'    ? 'selected' : '' }}>Active</option>
                                <option value="cancelled" {{ $event->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                <option value="completed" {{ $event->status === 'completed' ? 'selected' : '' }}>Completed</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Submit --}}
            <div class="d-flex gap-3 mb-4">
                <button type="submit" class="a-btn a-btn-primary" style="padding:11px 28px;font-size:.9rem">
                    <i class="bi bi-check-lg"></i>Save Changes
                </button>
                <a href="{{ route('admin.events.index') }}" class="a-btn a-btn-ghost" style="padding:11px 20px;font-size:.9rem">
                    Cancel
                </a>
            </div>

        </form>

        {{-- Danger Zone --}}
        <div class="danger-zone">
            <div style="font-weight:800;color:#dc2626;margin-bottom:6px;font-size:.9rem">
                <i class="bi bi-exclamation-triangle me-2"></i>Danger Zone
            </div>
            <div style="font-size:.82rem;color:#64748b;margin-bottom:14px">
                Deleting this event is permanent and cannot be undone. All associated bookings will be affected.
            </div>
            <form method="POST" action="{{ route('admin.events.destroy', $event->id) }}"
                  onsubmit="return confirm('Permanently delete {{ addslashes($event->title) }}? This cannot be undone.')">
                @csrf @method('DELETE')
                <button type="submit" class="a-btn a-btn-danger" style="font-size:.82rem;padding:8px 16px">
                    <i class="bi bi-trash3"></i>Delete This Event
                </button>
            </form>
        </div>

    </div>

    {{-- Side info --}}
    <div class="col-lg-4">
        <div class="a-card p-4" style="position:sticky;top:80px">
            <div style="font-weight:800;font-size:.88rem;color:#0f172a;margin-bottom:16px">Event Summary</div>

            <div class="d-flex flex-column gap-3">
                <div>
                    <div style="font-size:.72rem;color:#94a3b8;font-weight:600;text-transform:uppercase;letter-spacing:.4px;margin-bottom:3px">Title</div>
                    <div style="font-weight:700;font-size:.88rem">{{ $event->title }}</div>
                </div>
                <div>
                    <div style="font-size:.72rem;color:#94a3b8;font-weight:600;text-transform:uppercase;letter-spacing:.4px;margin-bottom:3px">Created</div>
                    <div style="font-size:.85rem;color:#475569">{{ $event->created_at->format('M j, Y · g:i A') }}</div>
                </div>
                <div>
                    <div style="font-size:.72rem;color:#94a3b8;font-weight:600;text-transform:uppercase;letter-spacing:.4px;margin-bottom:3px">Last Updated</div>
                    <div style="font-size:.85rem;color:#475569">{{ $event->updated_at->diffForHumans() }}</div>
                </div>
                <div>
                    <div style="font-size:.72rem;color:#94a3b8;font-weight:600;text-transform:uppercase;letter-spacing:.4px;margin-bottom:6px">Booking Fill Rate</div>
                    <div class="progress" style="height:10px">
                        <div class="progress-bar {{ $pct > 85 ? 'bg-danger' : ($pct > 55 ? 'bg-warning' : 'bg-success') }}"
                             style="width:{{ $pct }}%"></div>
                    </div>
                    <div style="font-size:.75rem;color:#64748b;margin-top:5px">
                        {{ $event->total_seats - $event->available_seats }} booked · {{ $event->available_seats }} remaining
                    </div>
                </div>
            </div>

            <div style="margin-top:20px;padding-top:16px;border-top:1px solid #f1f5f9">
                <a href="{{ route('events.show', $event->id) }}" target="_blank"
                   class="a-btn a-btn-ghost" style="width:100%;justify-content:center;font-size:.82rem">
                    <i class="bi bi-eye"></i>View Public Page
                </a>
            </div>
        </div>
    </div>
</div>

@endsection
