@extends('layouts.app')
@section('title', 'Discover Events — EventSY')

@push('styles')
<style>
.hero {
    background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 50%, #0f172a 100%);
    padding: 72px 0 56px;
    position: relative;
    overflow: hidden;
}
.hero::before {
    content: '';
    position: absolute; inset: 0;
    background: radial-gradient(ellipse at 60% 0%, rgba(99,102,241,.25) 0%, transparent 70%),
                radial-gradient(ellipse at 10% 100%, rgba(245,158,11,.12) 0%, transparent 60%);
}
.hero-content { position: relative; z-index: 2; }
.hero h1 { font-size: 2.6rem; font-weight: 800; color: #fff; letter-spacing: -.5px; line-height: 1.2; }
.hero h1 span { background: linear-gradient(90deg,#818cf8,#a78bfa); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; }
.hero p { color: rgba(255,255,255,.65); font-size: 1.05rem; }
.search-bar {
    background: rgba(255,255,255,.06);
    border: 1.5px solid rgba(255,255,255,.12);
    border-radius: 14px;
    padding: 18px 20px;
    backdrop-filter: blur(10px);
}
.search-bar .form-control, .search-bar .form-select {
    background: rgba(255,255,255,.08);
    border: 1.5px solid rgba(255,255,255,.15);
    color: #fff;
    border-radius: 10px;
}
.search-bar .form-control::placeholder { color: rgba(255,255,255,.45); }
.search-bar .form-control:focus, .search-bar .form-select:focus {
    background: rgba(255,255,255,.12);
    border-color: rgba(99,102,241,.7);
    color: #fff;
    box-shadow: 0 0 0 3px rgba(99,102,241,.2);
}
.search-bar .form-select option { background: #1e1b4b; color: #fff; }
.search-bar .form-label { color: rgba(255,255,255,.7); font-size: .8rem; font-weight: 600; letter-spacing: .5px; text-transform: uppercase; }
.btn-search {
    background: linear-gradient(135deg,#4f46e5,#7c3aed);
    color: #fff;
    border: none;
    border-radius: 10px;
    font-weight: 700;
    padding: 11px 24px;
    transition: all .2s;
    white-space: nowrap;
}
.btn-search:hover { transform: translateY(-1px); box-shadow: 0 6px 20px rgba(79,70,229,.45); color:#fff; }
.stat-chip {
    display: inline-flex; align-items: center; gap: 6px;
    background: rgba(255,255,255,.07);
    border: 1px solid rgba(255,255,255,.1);
    border-radius: 20px; padding: 5px 14px;
    color: rgba(255,255,255,.7); font-size: .82rem; font-weight: 500;
}
.stat-chip strong { color: #fff; }

/* Event Card */
.event-card {
    border-radius: 18px !important;
    overflow: hidden;
    transition: transform .25s ease, box-shadow .25s ease, border-color .25s ease;
    border: 1px solid #e2e8f0 !important;
}
.event-card:hover {
    transform: translateY(-7px);
    box-shadow: 0 20px 56px rgba(79,70,229,.2) !important;
    border-color: #c4b5fd !important;
}
.event-card .card-top {
    height: 5px;
    background: linear-gradient(90deg, #4f46e5, #818cf8);
}
.event-card .card-top.free { background: linear-gradient(90deg,#10b981,#34d399); }
.event-card .card-top.low  { background: linear-gradient(90deg,#f59e0b,#fbbf24); }
.event-card .card-top.sold { background: linear-gradient(90deg,#ef4444,#f87171); }
.event-card .price-tag {
    font-size: 1.25rem; font-weight: 800; color: #4f46e5;
    background: #ede9fe; padding: 4px 10px; border-radius: 8px;
}
.event-card .price-tag.free { color: #10b981; background: #d1fae5; }
.event-card .meta-row { display: flex; align-items: center; gap: 6px; color: #64748b; font-size: .82rem; }
.event-card .meta-row i { color: #4f46e5; font-size: .85rem; }
.seats-text { font-size: .78rem; font-weight: 600; }
.seats-text.ok   { color: #10b981; }
.seats-text.low  { color: #f59e0b; }
.seats-text.sold { color: #ef4444; }
.btn-book {
    background: linear-gradient(135deg,#4f46e5,#7c3aed);
    color: #fff; border: none; border-radius: 10px;
    font-size: .85rem; font-weight: 700; padding: 10px 18px;
    transition: all .2s; letter-spacing: .1px;
}
.btn-book:hover { transform: translateY(-1px); box-shadow: 0 6px 18px rgba(79,70,229,.45); color:#fff; }
.btn-details {
    background: linear-gradient(135deg,#4f46e5,#7c3aed);
    color: #fff; border: none; border-radius: 10px;
    font-size: .85rem; font-weight: 700; padding: 10px 18px;
    transition: all .2s; letter-spacing: .1px; text-decoration: none;
    display: inline-flex; align-items: center; gap: 5px;
}
.btn-details:hover { transform: translateY(-1px); box-shadow: 0 6px 18px rgba(79,70,229,.45); color: #fff; }
.btn-details-icon {
    background: #f1f5f9; color: #4f46e5; border: 1.5px solid #e2e8f0;
    border-radius: 10px; font-size: .85rem; font-weight: 700; padding: 9px 13px;
    transition: all .2s; text-decoration: none;
    display: inline-flex; align-items: center;
}
.btn-details-icon:hover { background: #ede9fe; border-color: #c4b5fd; color: #4f46e5; }

/* Results bar */
.results-bar { padding: 14px 0; border-bottom: 1px solid #e2e8f0; margin-bottom: 28px; }
.results-bar h5 { font-weight: 700; font-size: 1rem; margin: 0; }

/* Empty state */
.empty-state { text-align: center; padding: 80px 20px; }
.empty-icon {
    width: 90px; height: 90px;
    background: linear-gradient(135deg,#ede9fe,#ddd6fe);
    border-radius: 50%; display: inline-flex; align-items: center; justify-content: center;
    font-size: 2.2rem; color: #7c3aed; margin-bottom: 20px;
}
</style>
@endpush

@section('content')

{{-- Hero --}}
<div class="hero">
    <div class="container hero-content">
        <div class="row align-items-center g-4">
            <div class="col-lg-5">
                <div class="section-eyebrow mb-2" style="color:#818cf8">Syria's #1 Event Platform</div>
                <h1>Discover &amp;<br>Book <span>Amazing Events</span></h1>
                <p class="mt-3 mb-4">Workshops, conferences, and training sessions across Syria. Find your next experience.</p>
                <div class="d-flex flex-wrap gap-2">
                    <div class="stat-chip"><i class="bi bi-calendar-event" style="color:#818cf8"></i><strong>{{ $events->total() }}</strong> Live Events</div>
                    <div class="stat-chip"><i class="bi bi-geo-alt" style="color:#f59e0b"></i>Syria-wide</div>
                    <div class="stat-chip"><i class="bi bi-shield-check" style="color:#10b981"></i>Secure Booking</div>
                </div>
            </div>
            <div class="col-lg-7">
                <div class="search-bar">
                    <form method="GET" action="{{ route('events.index') }}">
                        <div class="row g-3 align-items-end">
                            <div class="col-sm-4">
                                <label class="form-label mb-1"><i class="bi bi-geo-alt me-1"></i>Location</label>
                                <input type="text" name="location" class="form-control" placeholder="Damascus, Aleppo…" value="{{ $filters['location'] ?? '' }}">
                            </div>
                            <div class="col-sm-4">
                                <label class="form-label mb-1"><i class="bi bi-calendar3 me-1"></i>Date</label>
                                <input type="date" name="date" class="form-control" value="{{ $filters['date'] ?? '' }}">
                            </div>
                            <div class="col-sm-4">
                                <label class="form-label mb-1"><i class="bi bi-funnel me-1"></i>Show</label>
                                <select name="available" class="form-select">
                                    <option value="">All Events</option>
                                    <option value="1" {{ isset($filters['available']) ? 'selected' : '' }}>Available Only</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn-search flex-grow-1">
                                        <i class="bi bi-search me-2"></i>Search Events
                                    </button>
                                    @if(array_filter($filters ?? []))
                                    <a href="{{ route('events.index') }}" class="btn btn-sm" style="background:rgba(255,255,255,.1);color:rgba(255,255,255,.7);border-radius:10px;font-size:.82rem;padding:0 14px">
                                        <i class="bi bi-x-circle me-1"></i>Clear
                                    </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Results --}}
<div class="container mt-5">
    <div class="results-bar d-flex align-items-center justify-content-between flex-wrap gap-2">
        <h5>
            @if(array_filter($filters ?? []))
                <i class="bi bi-funnel-fill text-primary me-2"></i>Filtered Results
                <span class="ms-2 px-2 py-1 rounded-pill" style="background:#ede9fe;color:#4f46e5;font-size:.75rem">{{ $events->total() }} found</span>
            @else
                <i class="bi bi-grid me-2" style="color:#4f46e5"></i>All Events
                <span class="ms-2 px-2 py-1 rounded-pill" style="background:#ede9fe;color:#4f46e5;font-size:.75rem">{{ $events->total() }} events</span>
            @endif
        </h5>
    </div>

    @if($events->isEmpty())
    <div class="empty-state">
        <div class="empty-icon"><i class="bi bi-calendar-x"></i></div>
        <h4 style="font-weight:700">No events found</h4>
        <p class="text-muted">Try adjusting your filters or check back later.</p>
        <a href="{{ route('events.index') }}" class="btn btn-primary mt-2 px-4">Clear Filters</a>
    </div>
    @else
    <div class="row g-4">
        @foreach($events as $event)
        @php
            $pct = $event->total_seats > 0 ? (($event->total_seats - $event->available_seats) / $event->total_seats) * 100 : 0;
            $isFree = $event->price == 0;
            $isSold = $event->available_seats == 0;
            $isLow  = !$isSold && $event->available_seats <= 10;
            $topClass = $isFree ? 'free' : ($isSold ? 'sold' : ($isLow ? 'low' : ''));
        @endphp
        <div class="col-md-6 col-lg-4">
            <div class="card event-card h-100">
                <div class="card-top {{ $topClass }}"></div>
                <div class="card-body p-4 d-flex flex-column">

                    {{-- Top row: category + price --}}
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            @if($isSold)
                                <span class="event-badge badge-sold">Sold Out</span>
                            @elseif($isLow)
                                <span class="event-badge badge-low">{{ $event->available_seats }} Left!</span>
                            @else
                                <span class="event-badge badge-avail">Available</span>
                            @endif
                        </div>
                        <div class="price-tag {{ $isFree ? 'free' : '' }}">
                            {{ $isFree ? 'FREE' : '$' . number_format($event->price, 0) }}
                        </div>
                    </div>

                    {{-- Title --}}
                    <h5 class="fw-700 mb-2" style="font-size:1.05rem;font-weight:700;line-height:1.35">{{ $event->title }}</h5>
                    <p class="text-muted mb-3" style="font-size:.84rem;line-height:1.55">{{ Str::limit($event->description, 90) }}</p>

                    {{-- Meta --}}
                    <div class="d-flex flex-column gap-2 mb-3 mt-auto">
                        <div class="meta-row">
                            <i class="bi bi-calendar3"></i>
                            <span>{{ \Carbon\Carbon::parse($event->event_date)->format('D, M j, Y') }}</span>
                            <span class="ms-auto text-muted">{{ \Carbon\Carbon::parse($event->event_date)->format('g:i A') }}</span>
                        </div>
                        <div class="meta-row">
                            <i class="bi bi-geo-alt"></i>
                            <span>{{ Str::limit($event->location, 38) }}</span>
                        </div>
                    </div>

                    {{-- Seats progress --}}
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-1">
                            <span class="seats-text {{ $isSold ? 'sold' : ($isLow ? 'low' : 'ok') }}">
                                <i class="bi bi-people me-1"></i>
                                {{ $isSold ? 'Sold out' : $event->available_seats . ' seats left' }}
                            </span>
                            <span class="seats-text" style="color:#94a3b8">{{ $event->total_seats }} total</span>
                        </div>
                        <div class="progress" style="height:5px">
                            <div class="progress-bar {{ $pct > 85 ? 'bg-danger' : ($pct > 55 ? 'bg-warning' : 'bg-success') }}"
                                 style="width:{{ $pct }}%"></div>
                        </div>
                    </div>

                    {{-- Actions --}}
                    <div class="d-flex gap-2">
                        @if(!$isSold && auth()->check())
                            <a href="{{ route('bookings.create', $event->id) }}" class="btn-book flex-grow-1 text-center text-decoration-none">
                                <i class="bi bi-ticket me-1"></i>Book Now
                            </a>
                            <a href="{{ route('events.show', $event->id) }}" class="btn-details-icon" title="View Details">
                                <i class="bi bi-eye"></i>
                            </a>
                        @else
                            <a href="{{ route('events.show', $event->id) }}" class="btn-details flex-grow-1 justify-content-center">
                                <i class="bi bi-eye me-1"></i>View Details
                            </a>
                        @endif
                    </div>

                </div>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Pagination --}}
    @if($events->hasPages())
    <div class="d-flex justify-content-center mt-5">
        {{ $events->appends($filters ?? [])->links() }}
    </div>
    @endif
    @endif
</div>
@endsection
