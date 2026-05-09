@extends('layouts.admin')
@section('title', 'Create Event')

@push('styles')
<style>
.form-page { max-width: 860px; }
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
.live-preview {
    background: linear-gradient(135deg, #6366f1, #8b5cf6);
    border-radius: 14px; padding: 20px; color: #fff; position: sticky; top: 80px;
    overflow: hidden;
}
.live-preview::before {
    content: '';
    position: absolute; inset: 0;
    background: radial-gradient(ellipse at 80% 20%, rgba(255,255,255,.15) 0%, transparent 60%);
}
.lp-content { position: relative; z-index: 1; }
.lp-label { font-size: .68rem; opacity: .6; font-weight: 700; text-transform: uppercase; letter-spacing: .5px; }
.lp-title { font-weight: 800; font-size: 1.05rem; line-height: 1.3; margin-top: 4px; min-height: 24px; }
.lp-chip {
    display: inline-flex; align-items: center; gap: 5px;
    background: rgba(255,255,255,.15); border-radius: 7px; padding: 4px 10px;
    font-size: .74rem; margin-top: 4px;
}
.lp-price { font-size: 1.8rem; font-weight: 900; line-height: 1; }
</style>
@endpush

@section('content')

<div class="d-flex align-items-center gap-3 mb-4">
    <a href="{{ route('admin.events.index') }}" class="a-btn a-btn-ghost a-btn-icon">
        <i class="bi bi-arrow-left"></i>
    </a>
    <div>
        <div style="font-size:.75rem;color:#94a3b8;font-weight:600">Events / Create New</div>
    </div>
</div>

<div class="row g-4">
    {{-- Form --}}
    <div class="col-lg-8">
        <form method="POST" action="{{ route('admin.events.store') }}" id="eventForm" class="form-page">
            @csrf

            {{-- Basic Info --}}
            <div class="form-section">
                <div class="form-section-header">
                    <div class="fsh-icon" style="background:#ede9fe"><i class="bi bi-type" style="color:#6366f1"></i></div>
                    <div>
                        <div class="fsh-title">Basic Information</div>
                        <div class="fsh-sub">Title and description of the event</div>
                    </div>
                </div>
                <div class="form-section-body">
                    <div class="mb-3">
                        <label class="form-label">Event Title *</label>
                        <input type="text" name="title" id="inp_title"
                               class="form-control @error('title') is-invalid @enderror"
                               value="{{ old('title') }}"
                               placeholder="e.g. Web Engineering Conference 2026"
                               oninput="updatePreview()" required>
                        @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div>
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="4"
                                  placeholder="Describe what attendees will experience, learn, or gain from this event...">{{ old('description') }}</textarea>
                    </div>
                </div>
            </div>

            {{-- Location & Date --}}
            <div class="form-section">
                <div class="form-section-header">
                    <div class="fsh-icon" style="background:#dbeafe"><i class="bi bi-geo-alt" style="color:#2563eb"></i></div>
                    <div>
                        <div class="fsh-title">Location &amp; Schedule</div>
                        <div class="fsh-sub">Where and when the event takes place</div>
                    </div>
                </div>
                <div class="form-section-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Location *</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-geo-alt"></i></span>
                                <input type="text" name="location"
                                       class="form-control @error('location') is-invalid @enderror"
                                       value="{{ old('location') }}"
                                       placeholder="e.g. Damascus Conference Center"
                                       oninput="updatePreview()" required
                                       style="border-left:none;border-radius:0 9px 9px 0">
                            </div>
                            @error('location')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Date &amp; Time *</label>
                            <input type="datetime-local" name="event_date"
                                   class="form-control @error('event_date') is-invalid @enderror"
                                   value="{{ old('event_date') }}"
                                   oninput="updatePreview()" required>
                            @error('event_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>
            </div>

            {{-- Pricing & Capacity --}}
            <div class="form-section">
                <div class="form-section-header">
                    <div class="fsh-icon" style="background:#d1fae5"><i class="bi bi-tag" style="color:#059669"></i></div>
                    <div>
                        <div class="fsh-title">Pricing &amp; Capacity</div>
                        <div class="fsh-sub">Set to 0 for a free event</div>
                    </div>
                </div>
                <div class="form-section-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Price per Seat ($)</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" name="price" id="inp_price"
                                       class="form-control"
                                       step="0.01" min="0" value="{{ old('price', 0) }}"
                                       oninput="updatePreview()" required>
                            </div>
                            <div class="form-text">0 = free event</div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Total Seats *</label>
                            <input type="number" name="total_seats"
                                   class="form-control @error('total_seats') is-invalid @enderror"
                                   min="1" value="{{ old('total_seats') }}"
                                   placeholder="e.g. 100" required>
                            @error('total_seats')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select">
                                <option value="active"    {{ old('status','active') === 'active'    ? 'selected' : '' }}>Active</option>
                                <option value="cancelled" {{ old('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Submit --}}
            <div class="d-flex gap-3">
                <button type="submit" class="a-btn a-btn-primary" style="padding:11px 28px;font-size:.9rem">
                    <i class="bi bi-check-lg"></i>Create Event
                </button>
                <a href="{{ route('admin.events.index') }}" class="a-btn a-btn-ghost" style="padding:11px 20px;font-size:.9rem">
                    Cancel
                </a>
            </div>
        </form>
    </div>

    {{-- Live Preview --}}
    <div class="col-lg-4">
        <div style="position:sticky;top:80px">
            <div style="font-size:.75rem;font-weight:700;text-transform:uppercase;letter-spacing:.5px;color:#94a3b8;margin-bottom:10px">
                Live Preview
            </div>
            <div class="live-preview">
                <div class="lp-content">
                    <div class="lp-label">Event Title</div>
                    <div class="lp-title" id="prev_title">Your event title…</div>

                    <div class="d-flex flex-wrap gap-1 mt-3">
                        <div class="lp-chip" id="prev_location">
                            <i class="bi bi-geo-alt"></i>Location
                        </div>
                        <div class="lp-chip" id="prev_date">
                            <i class="bi bi-calendar3"></i>Date &amp; time
                        </div>
                    </div>

                    <div style="margin-top:18px;border-top:1px solid rgba(255,255,255,.15);padding-top:14px">
                        <div class="lp-label">Price per Seat</div>
                        <div class="lp-price" id="prev_price">$0 — FREE</div>
                    </div>
                </div>
            </div>

            <div class="a-card mt-3 p-3" style="font-size:.8rem;color:#64748b">
                <i class="bi bi-lightbulb me-2" style="color:#f59e0b"></i>
                Fill in the form to see a live card preview of your event.
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function updatePreview() {
    const title = document.getElementById('inp_title').value || 'Your event title…';
    const price = parseFloat(document.getElementById('inp_price').value) || 0;
    const location = document.querySelector('[name=location]').value || 'Location';
    const dateEl = document.querySelector('[name=event_date]').value;

    document.getElementById('prev_title').textContent = title;
    document.getElementById('prev_location').innerHTML = '<i class="bi bi-geo-alt"></i> ' + (location || 'Location');
    document.getElementById('prev_price').textContent = price === 0 ? 'FREE' : '$' + price.toFixed(2);

    if (dateEl) {
        const d = new Date(dateEl);
        const opts = { weekday:'short', month:'short', day:'numeric', year:'numeric' };
        document.getElementById('prev_date').innerHTML = '<i class="bi bi-calendar3"></i> ' + d.toLocaleDateString('en-US', opts);
    }
}
</script>
@endpush
@endsection
