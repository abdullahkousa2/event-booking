<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'EventSY — Syria Event Booking')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary:    #4f46e5;
            --primary-d:  #3730a3;
            --accent:     #f59e0b;
            --success:    #10b981;
            --danger:     #ef4444;
            --dark:       #0f172a;
            --text:       #1e293b;
            --muted:      #64748b;
            --bg:         #f8fafc;
            --card-bg:    #ffffff;
            --border:     #e2e8f0;
            --radius:     14px;
            --shadow-sm:  0 1px 3px rgba(0,0,0,.08), 0 1px 2px rgba(0,0,0,.06);
            --shadow-md:  0 4px 16px rgba(0,0,0,.10);
            --shadow-lg:  0 12px 40px rgba(79,70,229,.18);
        }
        * { box-sizing: border-box; }
        body {
            font-family: 'Inter', sans-serif;
            background: var(--bg);
            color: var(--text);
            font-size: 15px;
        }

        /* ── Navbar ── */
        .navbar {
            background: rgba(15,23,42,.92) !important;
            backdrop-filter: blur(14px);
            -webkit-backdrop-filter: blur(14px);
            border-bottom: 1px solid rgba(255,255,255,.06);
            padding: 14px 0;
        }
        .navbar-brand {
            font-weight: 800;
            font-size: 1.25rem;
            color: #fff !important;
            letter-spacing: -.3px;
        }
        .navbar-brand span { color: var(--accent); }
        .navbar .nav-link {
            color: rgba(255,255,255,.75) !important;
            font-weight: 500;
            padding: 6px 14px !important;
            border-radius: 8px;
            transition: all .2s;
        }
        .navbar .nav-link:hover, .navbar .nav-link.active {
            color: #fff !important;
            background: rgba(255,255,255,.1);
        }
        .btn-nav-cta {
            background: var(--primary);
            color: #fff !important;
            padding: 7px 18px !important;
            border-radius: 8px;
            font-weight: 600;
            transition: all .2s !important;
        }
        .btn-nav-cta:hover {
            background: var(--primary-d) !important;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(79,70,229,.4) !important;
        }
        .dropdown-menu {
            border: 1px solid var(--border);
            border-radius: 12px;
            box-shadow: var(--shadow-md);
            padding: 8px;
        }
        .dropdown-item {
            border-radius: 8px;
            padding: 8px 12px;
            font-size: .9rem;
            font-weight: 500;
        }

        /* ── Cards ── */
        .card {
            border: 1px solid var(--border) !important;
            border-radius: var(--radius) !important;
            box-shadow: var(--shadow-sm);
            background: var(--card-bg);
        }
        .card-hover {
            transition: transform .22s ease, box-shadow .22s ease;
        }
        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-lg) !important;
        }

        /* ── Buttons ── */
        .btn-primary {
            background: var(--primary);
            border-color: var(--primary);
            font-weight: 600;
            border-radius: 9px;
            transition: all .2s;
        }
        .btn-primary:hover {
            background: var(--primary-d);
            border-color: var(--primary-d);
            transform: translateY(-1px);
            box-shadow: 0 4px 14px rgba(79,70,229,.35);
        }
        .btn-outline-primary {
            color: var(--primary);
            border-color: var(--primary);
            font-weight: 600;
            border-radius: 9px;
        }
        .btn-outline-primary:hover {
            background: var(--primary);
            border-color: var(--primary);
            transform: translateY(-1px);
        }
        .btn-success {
            background: var(--success);
            border-color: var(--success);
            font-weight: 600;
            border-radius: 9px;
        }
        .btn-success:hover { background: #059669; border-color: #059669; transform: translateY(-1px); }

        /* ── Badges ── */
        .badge-free    { background: linear-gradient(135deg,#10b981,#059669); color:#fff; }
        .badge-paid    { background: linear-gradient(135deg,#4f46e5,#3730a3); color:#fff; }
        .badge-avail   { background: linear-gradient(135deg,#10b981,#34d399); color:#fff; }
        .badge-low     { background: linear-gradient(135deg,#f59e0b,#d97706); color:#fff; }
        .badge-sold    { background: linear-gradient(135deg,#ef4444,#dc2626); color:#fff; }
        .badge-active  { background: linear-gradient(135deg,#10b981,#059669); color:#fff; }
        .badge-pending { background: linear-gradient(135deg,#f59e0b,#d97706); color:#fff; }
        .badge-cancel  { background: linear-gradient(135deg,#94a3b8,#64748b); color:#fff; }
        .badge-confirmed { background: linear-gradient(135deg,#4f46e5,#3730a3); color:#fff; }
        .event-badge, .status-badge {
            display: inline-block;
            padding: 4px 11px;
            border-radius: 20px;
            font-size: .72rem;
            font-weight: 700;
            letter-spacing: .3px;
            text-transform: uppercase;
        }

        /* ── Section headings ── */
        .section-eyebrow {
            font-size: .75rem;
            font-weight: 700;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            color: var(--primary);
        }

        /* ── Alerts ── */
        .alert { border: none; border-radius: 12px; font-weight: 500; }
        .alert-success { background: #d1fae5; color: #065f46; }
        .alert-danger  { background: #fee2e2; color: #991b1b; }
        .alert-info    { background: #dbeafe; color: #1e40af; }
        .alert-warning { background: #fef3c7; color: #92400e; }

        /* ── Forms ── */
        .form-control, .form-select {
            border-radius: 9px;
            border: 1.5px solid var(--border);
            font-size: .95rem;
            padding: .55rem .85rem;
            transition: all .2s;
        }
        .form-control:focus, .form-select:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(79,70,229,.12);
        }
        .form-label { font-weight: 600; font-size: .875rem; margin-bottom: .4rem; color: var(--text); }

        /* ── Pagination ── */
        .pagination { gap: 4px; }
        .page-item .page-link {
            border-radius: 8px !important;
            border: 1.5px solid var(--border);
            color: var(--text);
            font-weight: 600;
            font-size: .875rem;
            padding: 7px 14px;
            transition: all .2s;
            min-width: 38px;
            text-align: center;
        }
        .page-item .page-link:hover { background: var(--primary); color: #fff; border-color: var(--primary); }
        .page-item.active .page-link { background: var(--primary); border-color: var(--primary); color:#fff; box-shadow: 0 2px 8px rgba(79,70,229,.3); }
        .page-item.disabled .page-link { opacity: .4; }

        /* ── Progress ── */
        .progress { border-radius: 20px; background: #e2e8f0; }
        .progress-bar { border-radius: 20px; }

        /* ── Tables ── */
        .table th { font-size: .78rem; font-weight: 700; letter-spacing: .5px; text-transform: uppercase; color: var(--muted); border-bottom: 2px solid var(--border); }
        .table td { vertical-align: middle; border-color: var(--border); }
        .table-hover tbody tr:hover { background: #f1f5f9; }

        /* ── Footer ── */
        footer {
            background: linear-gradient(180deg, #0a0f1e 0%, #0f172a 100%);
            border-top: 1px solid rgba(255,255,255,.07);
            color: rgba(255,255,255,.5);
            padding: 52px 0 0;
            margin-top: 80px;
        }
        footer a { color: rgba(255,255,255,.45); text-decoration: none; transition: color .18s; }
        footer a:hover { color: #fff; }
        .footer-brand-icon {
            width: 38px; height: 38px;
            background: linear-gradient(135deg, #4f46e5, #818cf8);
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1rem; color: #fff; flex-shrink: 0;
        }
        .footer-brand-name { font-size: 1.15rem; font-weight: 800; color: #fff; letter-spacing: -.2px; }
        .footer-brand-name span { color: #f59e0b; }
        .footer-desc { font-size: .82rem; color: rgba(255,255,255,.38); line-height: 1.6; max-width: 260px; }
        .footer-heading { font-size: .68rem; font-weight: 800; letter-spacing: 1.4px; text-transform: uppercase; color: rgba(255,255,255,.3); margin-bottom: 14px; }
        .footer-link-list { list-style: none; padding: 0; margin: 0; }
        .footer-link-list li { margin-bottom: 10px; }
        .footer-link-list a { font-size: .84rem; display: flex; align-items: center; gap: 7px; }
        .footer-link-list a i { font-size: .8rem; color: #4f46e5; }
        .footer-bar {
            margin-top: 40px;
            border-top: 1px solid rgba(255,255,255,.06);
            padding: 18px 0;
        }
        .footer-bar-text { font-size: .76rem; color: rgba(255,255,255,.25); }
        .footer-tech-badge {
            display: inline-flex; align-items: center; gap: 5px;
            background: rgba(255,255,255,.05);
            border: 1px solid rgba(255,255,255,.08);
            border-radius: 6px; padding: 3px 10px;
            font-size: .72rem; color: rgba(255,255,255,.35);
        }

        /* ── Misc ── */
        code { background: #f1f5f9; color: var(--primary); padding: 2px 7px; border-radius: 5px; font-size: .85em; }
        .text-primary { color: var(--primary) !important; }
        .bg-primary   { background: var(--primary) !important; }
    </style>
    @stack('styles')
</head>
<body>

{{-- Navbar --}}
<nav class="navbar navbar-expand-lg sticky-top">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center gap-2" href="{{ route('home') }}">
            <div style="width:34px;height:34px;background:linear-gradient(135deg,var(--primary),#818cf8);border-radius:9px;display:flex;align-items:center;justify-content:center;">
                <i class="bi bi-calendar-event-fill text-white" style="font-size:.95rem"></i>
            </div>
            Event<span>SY</span>
        </a>

        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu" style="color:rgba(255,255,255,.75)">
            <i class="bi bi-list fs-4"></i>
        </button>

        <div class="collapse navbar-collapse" id="navMenu">
            <ul class="navbar-nav mx-auto gap-1">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('events.*') || request()->routeIs('home') ? 'active' : '' }}" href="{{ route('events.index') }}">
                        <i class="bi bi-search me-1"></i>Events
                    </a>
                </li>
                @auth
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('bookings.*') ? 'active' : '' }}" href="{{ route('bookings.index') }}">
                        <i class="bi bi-ticket-perforated me-1"></i>My Bookings
                    </a>
                </li>
                @if(auth()->user()->isAdmin())
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('admin.dashboard') }}">
                        <i class="bi bi-speedometer2 me-1"></i>Admin
                    </a>
                </li>
                @endif
                @endauth
            </ul>

            <ul class="navbar-nav align-items-center gap-2">
                @guest
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('login') }}">Sign In</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link btn-nav-cta" href="{{ route('register') }}">Get Started</a>
                </li>
                @else
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle d-flex align-items-center gap-2" href="#" data-bs-toggle="dropdown">
                        <div style="width:30px;height:30px;background:linear-gradient(135deg,var(--primary),#818cf8);border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:.75rem;color:#fff;font-weight:700;">
                            {{ strtoupper(substr(auth()->user()->name,0,1)) }}
                        </div>
                        {{ explode(' ', auth()->user()->name)[0] }}
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><span class="dropdown-item-text text-muted small py-1">{{ auth()->user()->email }}</span></li>
                        <li><hr class="dropdown-divider my-1"></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger">
                                    <i class="bi bi-box-arrow-right me-2"></i>Logout
                                </button>
                            </form>
                        </li>
                    </ul>
                </li>
                @endguest
            </ul>
        </div>
    </div>
</nav>

{{-- Flash Messages --}}
@if(session('success') || session('error') || $errors->any())
<div class="container mt-3">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show d-flex align-items-center gap-2">
            <i class="bi bi-check-circle-fill fs-5"></i>
            <span>{{ session('success') }}</span>
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center gap-2">
            <i class="bi bi-exclamation-triangle-fill fs-5"></i>
            <span>{{ session('error') }}</span>
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show">
            <div class="d-flex align-items-start gap-2">
                <i class="bi bi-exclamation-triangle-fill fs-5 mt-1"></i>
                <div>@foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach</div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
</div>
@endif

<main class="pb-5">
    @yield('content')
</main>

<footer>
    <div class="container">
        <div class="row g-5">

            {{-- Brand column --}}
            <div class="col-lg-4 col-md-12">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <div class="footer-brand-icon"><i class="bi bi-calendar-event-fill"></i></div>
                    <div class="footer-brand-name">Event<span>SY</span></div>
                </div>
                <p class="footer-desc mb-4">
                    Syria's premier event booking platform. Discover workshops, conferences, and cultural events — all in one place.
                </p>
                <div class="d-flex gap-2">
                    <a href="{{ route('events.index') }}" class="footer-tech-badge"><i class="bi bi-calendar-event"></i> Browse Events</a>
                    @guest
                    <a href="{{ route('register') }}" class="footer-tech-badge"><i class="bi bi-person-plus"></i> Join Free</a>
                    @endguest
                </div>
            </div>

            {{-- Quick Links --}}
            <div class="col-lg-2 col-sm-4">
                <div class="footer-heading">Explore</div>
                <ul class="footer-link-list">
                    <li><a href="{{ route('events.index') }}"><i class="bi bi-search"></i>All Events</a></li>
                    @auth
                    <li><a href="{{ route('bookings.index') }}"><i class="bi bi-ticket-perforated"></i>My Bookings</a></li>
                    @if(auth()->user()->isAdmin())
                    <li><a href="{{ route('admin.dashboard') }}"><i class="bi bi-speedometer2"></i>Admin Panel</a></li>
                    @endif
                    @else
                    <li><a href="{{ route('login') }}"><i class="bi bi-box-arrow-in-right"></i>Sign In</a></li>
                    <li><a href="{{ route('register') }}"><i class="bi bi-person-plus"></i>Register</a></li>
                    @endauth
                </ul>
            </div>

            {{-- Platform --}}
            <div class="col-lg-3 col-sm-4">
                <div class="footer-heading">Platform</div>
                <ul class="footer-link-list">
                    <li><a href="#"><i class="bi bi-shield-check"></i>Secure Bookings</a></li>
                    <li><a href="#"><i class="bi bi-lightning-charge"></i>Real-time Seats</a></li>
                    <li><a href="#"><i class="bi bi-geo-alt"></i>Syria-wide Events</a></li>
                    <li><a href="#"><i class="bi bi-bell"></i>Instant Confirmation</a></li>
                </ul>
            </div>

            {{-- Tech Stack --}}
            <div class="col-lg-3 col-sm-4">
                <div class="footer-heading">Built With</div>
                <div class="d-flex flex-wrap gap-2">
                    <span class="footer-tech-badge"><i class="bi bi-code-slash" style="color:#f05340"></i> Laravel</span>
                    <span class="footer-tech-badge"><i class="bi bi-database" style="color:#4479a1"></i> MySQL</span>
                    <span class="footer-tech-badge"><i class="bi bi-bootstrap" style="color:#7952b3"></i> Bootstrap</span>
                    <span class="footer-tech-badge"><i class="bi bi-braces" style="color:#f59e0b"></i> Blade</span>
                </div>
                <p class="mt-3" style="font-size:.75rem;color:rgba(255,255,255,.22);line-height:1.5">
                    Web Engineering Final Project<br>Academic Year 2025–2026
                </p>
            </div>

        </div>

        {{-- Bottom bar --}}
        <div class="footer-bar">
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-2">
                <span class="footer-bar-text">&copy; {{ date('Y') }} EventSY. All rights reserved.</span>
                <div class="d-flex align-items-center gap-3">
                    <span class="footer-bar-text">Made with <i class="bi bi-heart-fill" style="color:#ef4444;font-size:.7rem"></i> in Syria</span>
                </div>
            </div>
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>
