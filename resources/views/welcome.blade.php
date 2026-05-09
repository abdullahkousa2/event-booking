@extends('layouts.app')
@section('title', 'EventSY — Syria\'s Premier Event Booking Platform')

@push('styles')
<style>
/* ── Hero ── */
.landing-hero {
    background: linear-gradient(135deg, #070b18 0%, #0f172a 40%, #1e1b4b 70%, #0f172a 100%);
    min-height: 88vh;
    display: flex;
    align-items: center;
    position: relative;
    overflow: hidden;
    padding: 80px 0;
}
.landing-hero::before {
    content: '';
    position: absolute; inset: 0;
    background:
        radial-gradient(ellipse at 70% 30%, rgba(99,102,241,.28) 0%, transparent 55%),
        radial-gradient(ellipse at 10% 80%, rgba(245,158,11,.14) 0%, transparent 50%),
        radial-gradient(ellipse at 90% 90%, rgba(124,58,237,.18) 0%, transparent 45%);
}
.hero-blob {
    position: absolute;
    border-radius: 50%;
    filter: blur(80px);
    opacity: .35;
    animation: blobFloat 8s ease-in-out infinite;
}
.hero-blob-1 { width: 500px; height: 500px; background: #4f46e5; top: -120px; right: -80px; }
.hero-blob-2 { width: 320px; height: 320px; background: #7c3aed; bottom: -60px; left: 5%; animation-delay: -3s; }
.hero-blob-3 { width: 220px; height: 220px; background: #f59e0b; top: 30%; right: 15%; opacity: .18; animation-delay: -5s; }
@keyframes blobFloat {
    0%, 100% { transform: translateY(0) scale(1); }
    50% { transform: translateY(-24px) scale(1.04); }
}
.hero-content { position: relative; z-index: 2; }
.hero-eyebrow {
    display: inline-flex; align-items: center; gap: 8px;
    background: rgba(99,102,241,.18);
    border: 1px solid rgba(99,102,241,.35);
    border-radius: 100px;
    padding: 6px 16px;
    font-size: .75rem; font-weight: 700;
    color: #a5b4fc;
    letter-spacing: 1.2px;
    text-transform: uppercase;
    margin-bottom: 24px;
}
.hero-title {
    font-size: clamp(2.4rem, 5vw, 3.8rem);
    font-weight: 800;
    color: #fff;
    letter-spacing: -.6px;
    line-height: 1.12;
    margin-bottom: 24px;
}
.hero-title .gradient-text {
    background: linear-gradient(90deg, #818cf8, #a78bfa, #c4b5fd);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}
.hero-subtitle {
    color: rgba(255,255,255,.6);
    font-size: 1.1rem;
    line-height: 1.7;
    max-width: 520px;
    margin-bottom: 36px;
}
.hero-cta-primary {
    display: inline-flex; align-items: center; gap: 8px;
    background: linear-gradient(135deg, #4f46e5, #7c3aed);
    color: #fff; text-decoration: none;
    padding: 14px 28px; border-radius: 12px;
    font-weight: 700; font-size: 1rem;
    box-shadow: 0 8px 28px rgba(79,70,229,.45);
    transition: all .22s;
}
.hero-cta-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 14px 36px rgba(79,70,229,.55);
    color: #fff;
}
.hero-cta-secondary {
    display: inline-flex; align-items: center; gap: 8px;
    background: rgba(255,255,255,.08);
    border: 1.5px solid rgba(255,255,255,.18);
    color: rgba(255,255,255,.85); text-decoration: none;
    padding: 13px 24px; border-radius: 12px;
    font-weight: 600; font-size: 1rem;
    backdrop-filter: blur(8px);
    transition: all .22s;
}
.hero-cta-secondary:hover {
    background: rgba(255,255,255,.14);
    border-color: rgba(255,255,255,.3);
    color: #fff;
}

/* ── Hero stats ── */
.hero-stats {
    display: flex; gap: 32px;
    margin-top: 48px;
    padding-top: 40px;
    border-top: 1px solid rgba(255,255,255,.08);
}
.hero-stat-val { font-size: 1.9rem; font-weight: 800; color: #fff; line-height: 1; }
.hero-stat-lbl { font-size: .75rem; color: rgba(255,255,255,.45); font-weight: 500; margin-top: 4px; letter-spacing: .3px; }

/* ── Hero card preview ── */
.hero-preview-card {
    background: rgba(255,255,255,.06);
    backdrop-filter: blur(16px);
    border: 1px solid rgba(255,255,255,.1);
    border-radius: 20px;
    padding: 24px;
    box-shadow: 0 24px 64px rgba(0,0,0,.4), inset 0 1px 0 rgba(255,255,255,.08);
}
.preview-event-item {
    display: flex; align-items: center; gap: 14px;
    padding: 14px 16px;
    border-radius: 12px;
    background: rgba(255,255,255,.05);
    border: 1px solid rgba(255,255,255,.07);
    margin-bottom: 10px;
    transition: background .2s;
}
.preview-event-item:last-child { margin-bottom: 0; }
.preview-event-item:hover { background: rgba(255,255,255,.09); }
.preview-dot {
    width: 42px; height: 42px;
    border-radius: 12px;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
    font-size: 1.1rem;
}
.preview-event-name { font-size: .88rem; font-weight: 700; color: #fff; }
.preview-event-meta { font-size: .72rem; color: rgba(255,255,255,.45); margin-top: 2px; }
.preview-badge {
    margin-left: auto;
    padding: 3px 9px;
    border-radius: 100px;
    font-size: .68rem;
    font-weight: 700;
    letter-spacing: .3px;
    text-transform: uppercase;
    flex-shrink: 0;
}

/* ── Stats bar ── */
.stats-bar {
    background: #fff;
    padding: 48px 0;
    border-bottom: 1px solid #e2e8f0;
}
.stat-card {
    text-align: center;
    padding: 0 24px;
}
.stat-card-num {
    font-size: 2.4rem;
    font-weight: 800;
    background: linear-gradient(135deg, #4f46e5, #7c3aed);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    line-height: 1;
}
.stat-card-label { font-size: .85rem; color: #64748b; font-weight: 500; margin-top: 6px; }
.stat-divider { width: 1px; background: #e2e8f0; align-self: stretch; margin: 8px 0; }

/* ── How it works ── */
.hiw-section { background: #f8fafc; padding: 88px 0; }
.section-tag {
    display: inline-flex; align-items: center; gap: 6px;
    background: #ede9fe;
    color: #4f46e5;
    border-radius: 100px;
    padding: 5px 14px;
    font-size: .72rem; font-weight: 700;
    letter-spacing: 1px;
    text-transform: uppercase;
    margin-bottom: 16px;
}
.section-title { font-size: 2.1rem; font-weight: 800; color: #0f172a; letter-spacing: -.4px; }
.section-subtitle { color: #64748b; font-size: 1.05rem; max-width: 520px; line-height: 1.7; margin: 0 auto; }
.step-card {
    background: #fff;
    border: 1px solid #e2e8f0;
    border-radius: 20px;
    padding: 36px 28px;
    text-align: center;
    position: relative;
    transition: all .25s;
    height: 100%;
}
.step-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 16px 48px rgba(79,70,229,.14);
    border-color: #c4b5fd;
}
.step-num {
    position: absolute;
    top: -14px; left: 28px;
    background: linear-gradient(135deg, #4f46e5, #7c3aed);
    color: #fff;
    width: 28px; height: 28px;
    border-radius: 8px;
    font-size: .72rem; font-weight: 800;
    display: flex; align-items: center; justify-content: center;
}
.step-icon {
    width: 64px; height: 64px;
    border-radius: 18px;
    background: linear-gradient(135deg, #ede9fe, #ddd6fe);
    display: flex; align-items: center; justify-content: center;
    margin: 16px auto 20px;
    font-size: 1.6rem;
}
.step-title { font-size: 1.05rem; font-weight: 700; color: #1e293b; margin-bottom: 10px; }
.step-desc { font-size: .875rem; color: #64748b; line-height: 1.65; }

/* ── Featured events ── */
.events-section { padding: 88px 0; background: #fff; }
.event-card-landing {
    background: #fff;
    border: 1px solid #e2e8f0;
    border-radius: 18px;
    overflow: hidden;
    transition: all .25s;
    height: 100%;
    display: flex; flex-direction: column;
}
.event-card-landing:hover {
    transform: translateY(-6px);
    box-shadow: 0 20px 52px rgba(79,70,229,.16);
    border-color: #c4b5fd;
}
.event-card-top {
    height: 6px;
}
.event-card-body { padding: 22px; flex: 1; display: flex; flex-direction: column; }
.event-card-cat {
    font-size: .68rem; font-weight: 700;
    letter-spacing: .8px; text-transform: uppercase;
    color: #4f46e5; margin-bottom: 8px;
}
.event-card-title { font-size: 1.05rem; font-weight: 700; color: #0f172a; line-height: 1.35; margin-bottom: 14px; }
.event-card-meta {
    display: flex; align-items: center; gap: 6px;
    font-size: .78rem; color: #64748b;
    margin-bottom: 6px;
}
.event-card-footer {
    display: flex; justify-content: space-between; align-items: center;
    margin-top: auto; padding-top: 16px;
    border-top: 1px solid #f1f5f9;
}
.event-price-tag {
    font-size: 1.15rem; font-weight: 800; color: #4f46e5;
}
.event-price-tag.free { color: #10b981; }
.btn-book-now {
    display: inline-flex; align-items: center; gap: 5px;
    background: linear-gradient(135deg, #4f46e5, #7c3aed);
    color: #fff; text-decoration: none;
    padding: 8px 16px; border-radius: 9px;
    font-size: .8rem; font-weight: 700;
    transition: all .2s;
}
.btn-book-now:hover {
    transform: translateY(-1px);
    box-shadow: 0 6px 18px rgba(79,70,229,.4);
    color: #fff;
}

/* ── CTA banner ── */
.cta-section {
    background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 50%, #0f172a 100%);
    padding: 96px 0;
    position: relative; overflow: hidden;
}
.cta-section::before {
    content: '';
    position: absolute; inset: 0;
    background: radial-gradient(ellipse at 60% 50%, rgba(99,102,241,.3) 0%, transparent 65%);
}
.cta-content { position: relative; z-index: 1; text-align: center; }
.cta-title { font-size: 2.6rem; font-weight: 800; color: #fff; letter-spacing: -.5px; margin-bottom: 16px; }
.cta-subtitle { color: rgba(255,255,255,.6); font-size: 1.05rem; max-width: 480px; margin: 0 auto 36px; line-height: 1.65; }

/* ── Features grid ── */
.features-section { background: #f8fafc; padding: 88px 0; }
.feature-card {
    background: #fff;
    border: 1px solid #e2e8f0;
    border-radius: 16px;
    padding: 28px;
    display: flex; gap: 18px; align-items: flex-start;
    transition: all .22s;
}
.feature-card:hover { border-color: #c4b5fd; box-shadow: 0 8px 28px rgba(79,70,229,.1); }
.feature-icon {
    width: 48px; height: 48px; border-radius: 14px;
    background: linear-gradient(135deg, #ede9fe, #ddd6fe);
    display: flex; align-items: center; justify-content: center;
    font-size: 1.3rem; flex-shrink: 0;
}
.feature-title { font-size: .95rem; font-weight: 700; color: #1e293b; margin-bottom: 6px; }
.feature-desc { font-size: .83rem; color: #64748b; line-height: 1.6; }
</style>
@endpush

@section('content')

{{-- ══ HERO ══ --}}
<section class="landing-hero">
    <div class="hero-blob hero-blob-1"></div>
    <div class="hero-blob hero-blob-2"></div>
    <div class="hero-blob hero-blob-3"></div>
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-6 hero-content">
                <div class="hero-eyebrow">
                    <i class="bi bi-stars" style="color:#f59e0b"></i>
                    Syria's #1 Event Platform
                </div>
                <h1 class="hero-title">
                    Discover & Book<br>
                    <span class="gradient-text">Unforgettable Events</span>
                </h1>
                <p class="hero-subtitle">
                    From concerts and workshops to conferences and cultural gatherings — EventSY connects you to the best events across Syria. Reserve your seat in seconds.
                </p>
                <div class="d-flex flex-wrap gap-3">
                    <a href="{{ route('events.index') }}" class="hero-cta-primary">
                        <i class="bi bi-search"></i>
                        Browse Events
                    </a>
                    @guest
                    <a href="{{ route('register') }}" class="hero-cta-secondary">
                        <i class="bi bi-person-plus"></i>
                        Create Account
                    </a>
                    @endguest
                </div>
                <div class="hero-stats">
                    <div>
                        <div class="hero-stat-val">500+</div>
                        <div class="hero-stat-lbl">Events Hosted</div>
                    </div>
                    <div>
                        <div class="hero-stat-val">12K+</div>
                        <div class="hero-stat-lbl">Happy Attendees</div>
                    </div>
                    <div>
                        <div class="hero-stat-val">98%</div>
                        <div class="hero-stat-lbl">Satisfaction Rate</div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 hero-content d-none d-lg-block">
                <div class="hero-preview-card">
                    <div style="font-size:.7rem;font-weight:700;color:rgba(255,255,255,.4);letter-spacing:1px;text-transform:uppercase;margin-bottom:14px">
                        <i class="bi bi-lightning-fill" style="color:#f59e0b"></i> Upcoming Events
                    </div>
                    <div class="preview-event-item">
                        <div class="preview-dot" style="background:linear-gradient(135deg,#4f46e5,#7c3aed)">
                            <i class="bi bi-music-note-beamed text-white"></i>
                        </div>
                        <div>
                            <div class="preview-event-name">Damascus Jazz Night</div>
                            <div class="preview-event-meta"><i class="bi bi-calendar3 me-1"></i>May 15 · Al-Assad Opera House</div>
                        </div>
                        <span class="preview-badge" style="background:linear-gradient(135deg,#10b981,#059669);color:#fff">Available</span>
                    </div>
                    <div class="preview-event-item">
                        <div class="preview-dot" style="background:linear-gradient(135deg,#f59e0b,#d97706)">
                            <i class="bi bi-camera text-white"></i>
                        </div>
                        <div>
                            <div class="preview-event-name">Photography Workshop</div>
                            <div class="preview-event-meta"><i class="bi bi-calendar3 me-1"></i>May 18 · Aleppo Art Center</div>
                        </div>
                        <span class="preview-badge" style="background:linear-gradient(135deg,#f59e0b,#d97706);color:#fff">Few Left</span>
                    </div>
                    <div class="preview-event-item">
                        <div class="preview-dot" style="background:linear-gradient(135deg,#10b981,#059669)">
                            <i class="bi bi-cpu text-white"></i>
                        </div>
                        <div>
                            <div class="preview-event-name">Tech Innovation Summit</div>
                            <div class="preview-event-meta"><i class="bi bi-calendar3 me-1"></i>May 22 · Damascus Conf. Hall</div>
                        </div>
                        <span class="preview-badge" style="background:rgba(99,102,241,.25);border:1px solid rgba(99,102,241,.4);color:#a5b4fc">FREE</span>
                    </div>
                    <div style="margin-top:16px;padding-top:14px;border-top:1px solid rgba(255,255,255,.07);text-align:center">
                        <a href="{{ route('events.index') }}" style="font-size:.78rem;color:#818cf8;font-weight:600;text-decoration:none">
                            View all events <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ══ STATS BAR ══ --}}
<section class="stats-bar">
    <div class="container">
        <div class="d-flex justify-content-center align-items-center flex-wrap gap-0">
            <div class="stat-card">
                <div class="stat-card-num">500+</div>
                <div class="stat-card-label">Events Listed</div>
            </div>
            <div class="stat-divider d-none d-md-block"></div>
            <div class="stat-card">
                <div class="stat-card-num">12K+</div>
                <div class="stat-card-label">Tickets Booked</div>
            </div>
            <div class="stat-divider d-none d-md-block"></div>
            <div class="stat-card">
                <div class="stat-card-num">200+</div>
                <div class="stat-card-label">Event Organizers</div>
            </div>
            <div class="stat-divider d-none d-md-block"></div>
            <div class="stat-card">
                <div class="stat-card-num">14</div>
                <div class="stat-card-label">Syrian Cities</div>
            </div>
            <div class="stat-divider d-none d-md-block"></div>
            <div class="stat-card">
                <div class="stat-card-num">4.9★</div>
                <div class="stat-card-label">Average Rating</div>
            </div>
        </div>
    </div>
</section>

{{-- ══ HOW IT WORKS ══ --}}
<section class="hiw-section">
    <div class="container">
        <div class="text-center mb-5">
            <div class="section-tag"><i class="bi bi-lightning-fill"></i> How It Works</div>
            <h2 class="section-title mb-3">Book Your Seat in 3 Steps</h2>
            <p class="section-subtitle">Getting to your next event has never been easier. EventSY makes the entire process seamless.</p>
        </div>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="step-card">
                    <div class="step-num">01</div>
                    <div class="step-icon"><i class="bi bi-search text-primary"></i></div>
                    <h5 class="step-title">Discover Events</h5>
                    <p class="step-desc">Browse hundreds of events by category, date, location, or price. Find exactly what excites you.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="step-card">
                    <div class="step-num">02</div>
                    <div class="step-icon"><i class="bi bi-ticket-perforated text-primary"></i></div>
                    <h5 class="step-title">Reserve Your Seats</h5>
                    <p class="step-desc">Choose how many seats you need with our visual seat picker. Instant confirmation, no waiting.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="step-card">
                    <div class="step-num">03</div>
                    <div class="step-icon"><i class="bi bi-check-circle text-primary"></i></div>
                    <h5 class="step-title">Enjoy the Event</h5>
                    <p class="step-desc">Show your booking confirmation at the door. Easy check-in, unforgettable experiences await.</p>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ══ FEATURED EVENTS ══ --}}
@isset($featuredEvents)
@if($featuredEvents->count())
<section class="events-section">
    <div class="container">
        <div class="d-flex justify-content-between align-items-end mb-5 flex-wrap gap-3">
            <div>
                <div class="section-tag"><i class="bi bi-star-fill"></i> Featured</div>
                <h2 class="section-title mb-0">Events You'll Love</h2>
            </div>
            <a href="{{ route('events.index') }}" style="color:#4f46e5;font-weight:700;text-decoration:none;font-size:.9rem">
                View All Events <i class="bi bi-arrow-right"></i>
            </a>
        </div>
        <div class="row g-4">
            @foreach($featuredEvents->take(3) as $event)
            @php
                $avail = $event->available_seats;
                $total = $event->total_seats ?? $avail;
                $pct   = $total > 0 ? round(($avail / $total) * 100) : 0;
                $colors = ['linear-gradient(135deg,#4f46e5,#7c3aed)', 'linear-gradient(135deg,#f59e0b,#d97706)', 'linear-gradient(135deg,#10b981,#059669)', 'linear-gradient(135deg,#ef4444,#dc2626)'];
                $color  = $colors[$loop->index % count($colors)];
                $isFree = $event->price == 0;
            @endphp
            <div class="col-md-4">
                <div class="event-card-landing">
                    <div class="event-card-top" style="background: {{ $color }}"></div>
                    <div class="event-card-body">
                        <div class="event-card-cat"><i class="bi bi-tag me-1"></i>Event</div>
                        <div class="event-card-title">{{ Str::limit($event->title, 55) }}</div>
                        <div class="event-card-meta">
                            <i class="bi bi-calendar3 text-primary"></i>
                            {{ \Carbon\Carbon::parse($event->event_date)->format('M j, Y · g:i A') }}
                        </div>
                        <div class="event-card-meta">
                            <i class="bi bi-geo-alt text-primary"></i>
                            {{ Str::limit($event->location, 40) }}
                        </div>
                        <div class="mt-3">
                            <div class="d-flex justify-content-between" style="font-size:.72rem;color:#94a3b8;margin-bottom:4px">
                                <span>{{ $avail }} seats left</span>
                                <span>{{ $pct }}%</span>
                            </div>
                            <div class="progress" style="height:4px">
                                <div class="progress-bar" style="width:{{ $pct }}%;background:{{ $color }}"></div>
                            </div>
                        </div>
                        <div class="event-card-footer">
                            <div class="event-price-tag {{ $isFree ? 'free' : '' }}">
                                {{ $isFree ? 'FREE' : '$' . number_format($event->price, 0) }}
                            </div>
                            <a href="{{ route('events.show', $event->id) }}" class="btn-book-now">
                                Book Now <i class="bi bi-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif
@endisset

{{-- ══ FEATURES ══ --}}
<section class="features-section">
    <div class="container">
        <div class="text-center mb-5">
            <div class="section-tag"><i class="bi bi-shield-check-fill"></i> Why EventSY</div>
            <h2 class="section-title mb-3">Built for Event-Lovers</h2>
            <p class="section-subtitle">Everything you need for a smooth, enjoyable booking experience — all in one place.</p>
        </div>
        <div class="row g-4">
            <div class="col-md-6 col-lg-4">
                <div class="feature-card">
                    <div class="feature-icon"><i class="bi bi-lightning-charge-fill text-primary"></i></div>
                    <div>
                        <div class="feature-title">Instant Booking</div>
                        <div class="feature-desc">Reserve your seats in seconds. Real-time availability updates ensure you never miss out.</div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="feature-card">
                    <div class="feature-icon"><i class="bi bi-lock-fill text-primary"></i></div>
                    <div>
                        <div class="feature-title">Secure Payments</div>
                        <div class="feature-desc">Industry-standard payment processing. Your financial data is always encrypted and protected.</div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="feature-card">
                    <div class="feature-icon"><i class="bi bi-geo-alt-fill text-primary"></i></div>
                    <div>
                        <div class="feature-title">All Across Syria</div>
                        <div class="feature-desc">Events in Damascus, Aleppo, Homs, Latakia, and 10+ more cities throughout the country.</div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="feature-card">
                    <div class="feature-icon"><i class="bi bi-ticket-perforated-fill text-primary"></i></div>
                    <div>
                        <div class="feature-title">Manage Your Bookings</div>
                        <div class="feature-desc">View, track, and manage all your bookings from a single dashboard anytime, anywhere.</div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="feature-card">
                    <div class="feature-icon"><i class="bi bi-people-fill text-primary"></i></div>
                    <div>
                        <div class="feature-title">Group Reservations</div>
                        <div class="feature-desc">Book multiple seats at once for friends, family, or colleagues — all in a single transaction.</div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="feature-card">
                    <div class="feature-icon"><i class="bi bi-headset text-primary"></i></div>
                    <div>
                        <div class="feature-title">Dedicated Support</div>
                        <div class="feature-desc">Our support team is here to help — before, during, and after every event you book.</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ══ CTA BANNER ══ --}}
<section class="cta-section">
    <div class="container">
        <div class="cta-content">
            <h2 class="cta-title">Ready to Experience More?</h2>
            <p class="cta-subtitle">Join thousands of event-goers across Syria. Create your free account and start booking in minutes.</p>
            <div class="d-flex justify-content-center gap-3 flex-wrap">
                @guest
                <a href="{{ route('register') }}" class="hero-cta-primary">
                    <i class="bi bi-person-plus-fill"></i>
                    Get Started — It's Free
                </a>
                <a href="{{ route('login') }}" class="hero-cta-secondary">
                    <i class="bi bi-box-arrow-in-right"></i>
                    Sign In
                </a>
                @else
                <a href="{{ route('events.index') }}" class="hero-cta-primary">
                    <i class="bi bi-search"></i>
                    Discover Events Now
                </a>
                @endguest
            </div>
        </div>
    </div>
</section>

@endsection
