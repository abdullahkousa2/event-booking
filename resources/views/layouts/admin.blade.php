<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') — EventSY Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        :root {
            --p:        #6366f1;
            --p-d:      #4f46e5;
            --p-dd:     #3730a3;
            --accent:   #f59e0b;
            --success:  #10b981;
            --danger:   #ef4444;
            --warning:  #f59e0b;
            --info:     #3b82f6;
            --sidebar-w: 260px;
            --topbar-h:  64px;
        }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Inter', sans-serif;
            background: #f0f2f8;
            color: #1e293b;
            font-size: 14px;
        }

        /* ══════════════════════════════
           SIDEBAR
        ══════════════════════════════ */
        .a-sidebar {
            width: var(--sidebar-w);
            height: 100vh;
            background: #0c0f1d;
            position: fixed; top: 0; left: 0; z-index: 300;
            display: flex; flex-direction: column;
            overflow: hidden;
        }
        /* glow blob at top */
        .a-sidebar::before {
            content: '';
            position: absolute; top: -80px; left: -40px;
            width: 280px; height: 280px;
            background: radial-gradient(circle, rgba(99,102,241,.35) 0%, transparent 70%);
            pointer-events: none;
        }
        .a-sidebar::after {
            content: '';
            position: absolute; bottom: 60px; right: -60px;
            width: 200px; height: 200px;
            background: radial-gradient(circle, rgba(168,85,247,.2) 0%, transparent 70%);
            pointer-events: none;
        }

        /* Brand */
        .sb-brand {
            padding: 22px 20px 18px;
            border-bottom: 1px solid rgba(255,255,255,.05);
            position: relative; z-index: 1;
            flex-shrink: 0;
        }
        .sb-brand a { display: flex; align-items: center; gap: 12px; text-decoration: none; }
        .sb-logo {
            width: 40px; height: 40px; flex-shrink: 0;
            background: linear-gradient(135deg, #6366f1, #a78bfa);
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.1rem; color: #fff;
            box-shadow: 0 4px 14px rgba(99,102,241,.5);
        }
        .sb-title { color: #fff; font-weight: 800; font-size: 1.1rem; letter-spacing: -.3px; }
        .sb-sub   { color: rgba(255,255,255,.3); font-size: .68rem; font-weight: 600; letter-spacing: .8px; text-transform: uppercase; margin-top: 1px; }

        /* Nav */
        .sb-nav { flex: 1; overflow-y: auto; padding: 12px 14px; position: relative; z-index: 1; }
        .sb-nav::-webkit-scrollbar { display: none; }
        .sb-section {
            font-size: .62rem; font-weight: 700; letter-spacing: 1.2px;
            text-transform: uppercase; color: rgba(255,255,255,.22);
            padding: 0 8px; margin: 18px 0 6px;
        }
        .sb-section:first-child { margin-top: 6px; }
        .sb-link {
            display: flex; align-items: center; gap: 10px;
            color: rgba(255,255,255,.5);
            text-decoration: none;
            padding: 9px 10px;
            border-radius: 10px;
            margin-bottom: 2px;
            font-weight: 500;
            font-size: .875rem;
            transition: all .18s;
            position: relative;
        }
        .sb-link .sb-icon {
            width: 32px; height: 32px; flex-shrink: 0;
            border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            background: rgba(255,255,255,.05);
            font-size: .9rem;
            transition: all .18s;
        }
        .sb-link:hover { color: rgba(255,255,255,.85); background: rgba(255,255,255,.06); }
        .sb-link:hover .sb-icon { background: rgba(255,255,255,.1); }
        .sb-link.active {
            color: #fff;
            background: linear-gradient(135deg, rgba(99,102,241,.3), rgba(168,85,247,.2));
            border: 1px solid rgba(99,102,241,.3);
        }
        .sb-link.active .sb-icon {
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
            box-shadow: 0 3px 10px rgba(99,102,241,.45);
            color: #fff;
        }
        .sb-badge {
            margin-left: auto;
            background: rgba(99,102,241,.3);
            color: #a5b4fc;
            font-size: .65rem; font-weight: 700;
            padding: 2px 7px; border-radius: 20px;
        }

        /* User footer */
        .sb-footer {
            padding: 14px; border-top: 1px solid rgba(255,255,255,.05);
            position: relative; z-index: 1; flex-shrink: 0;
        }
        .sb-user {
            display: flex; align-items: center; gap: 10px;
            padding: 10px 12px; border-radius: 12px;
            background: rgba(255,255,255,.04);
            margin-bottom: 8px;
        }
        .sb-avatar {
            width: 36px; height: 36px; flex-shrink: 0;
            background: linear-gradient(135deg, #6366f1, #a78bfa);
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: .82rem; font-weight: 800; color: #fff;
            box-shadow: 0 0 0 2px rgba(99,102,241,.4);
        }
        .sb-uname { color: #fff; font-weight: 700; font-size: .85rem; line-height: 1.2; }
        .sb-urole { color: rgba(255,255,255,.35); font-size: .7rem; }
        .sb-logout {
            display: flex; align-items: center; gap: 8px; width: 100%;
            background: rgba(239,68,68,.08);
            border: 1px solid rgba(239,68,68,.15);
            border-radius: 10px; padding: 9px 14px;
            color: rgba(239,68,68,.7); font-size: .82rem; font-weight: 600;
            cursor: pointer; transition: all .18s;
        }
        .sb-logout:hover { background: rgba(239,68,68,.15); color: #ef4444; border-color: rgba(239,68,68,.3); }

        /* ══════════════════════════════
           MAIN
        ══════════════════════════════ */
        .a-main { margin-left: var(--sidebar-w); min-height: 100vh; display: flex; flex-direction: column; }

        /* Topbar */
        .a-topbar {
            height: var(--topbar-h);
            background: #fff;
            border-bottom: 1px solid #e8ecf4;
            padding: 0 28px;
            display: flex; align-items: center; justify-content: space-between;
            position: sticky; top: 0; z-index: 200;
            box-shadow: 0 1px 0 #e8ecf4, 0 2px 8px rgba(0,0,0,.04);
        }
        .a-topbar .tb-left { display: flex; align-items: center; gap: 14px; }
        .tb-breadcrumb { display: flex; align-items: center; gap: 6px; font-size: .82rem; }
        .tb-breadcrumb .bc-sep { color: #cbd5e1; }
        .tb-breadcrumb .bc-curr { font-weight: 700; color: #1e293b; }
        .tb-breadcrumb .bc-prev { color: #94a3b8; }
        .tb-title { font-weight: 800; font-size: 1.15rem; color: #0f172a; }
        .a-topbar .tb-right { display: flex; align-items: center; gap: 14px; }
        .tb-date {
            display: flex; align-items: center; gap: 6px;
            background: #f8fafc; border: 1px solid #e8ecf4;
            border-radius: 8px; padding: 6px 12px;
            font-size: .78rem; font-weight: 600; color: #64748b;
        }
        .tb-date i { color: #6366f1; }
        .tb-new-event {
            display: flex; align-items: center; gap: 6px;
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
            color: #fff; border: none; border-radius: 9px;
            padding: 8px 16px; font-size: .82rem; font-weight: 700;
            text-decoration: none; transition: all .18s;
            box-shadow: 0 2px 8px rgba(99,102,241,.35);
        }
        .tb-new-event:hover { box-shadow: 0 4px 14px rgba(99,102,241,.5); transform: translateY(-1px); color: #fff; }

        /* Content */
        .a-content { padding: 28px; flex: 1; }

        /* ══════════════════════════════
           SHARED COMPONENTS
        ══════════════════════════════ */

        /* Cards */
        .a-card {
            background: #fff;
            border-radius: 16px;
            border: 1px solid #e8ecf4;
            box-shadow: 0 1px 4px rgba(0,0,0,.04), 0 4px 16px rgba(0,0,0,.03);
        }
        .a-card-header {
            padding: 20px 22px 0;
            display: flex; align-items: center; justify-content: space-between;
        }
        .a-card-title { font-weight: 800; font-size: .97rem; color: #0f172a; }
        .a-card-sub   { font-size: .76rem; color: #94a3b8; margin-top: 2px; }

        /* Tables */
        .a-table th {
            font-size: .7rem; font-weight: 700; letter-spacing: .6px;
            text-transform: uppercase; color: #94a3b8;
            border-bottom: 2px solid #f1f5f9;
            padding: 11px 16px; background: #fafbfe;
            white-space: nowrap;
        }
        .a-table td { vertical-align: middle; border-color: #f1f5f9; padding: 13px 16px; font-size: .875rem; }
        .a-table tbody tr { transition: background .12s; }
        .a-table tbody tr:hover td { background: #f8faff; }
        .a-table tbody tr:last-child td { border-bottom: none; }

        /* Badges */
        .a-badge {
            display: inline-flex; align-items: center; gap: 4px;
            padding: 3px 10px; border-radius: 20px;
            font-size: .68rem; font-weight: 700; letter-spacing: .3px; text-transform: uppercase;
        }
        .a-badge i { font-size: .65rem; }
        .ab-confirmed { background: #ede9fe; color: #4f46e5; }
        .ab-pending   { background: #fef3c7; color: #d97706; }
        .ab-cancelled { background: #f1f5f9; color: #64748b; }
        .ab-active    { background: #d1fae5; color: #059669; }
        .ab-sold      { background: #fee2e2; color: #dc2626; }
        .ab-free      { background: #d1fae5; color: #059669; }
        .ab-paid      { background: #ede9fe; color: #4f46e5; }
        .ab-info      { background: #dbeafe; color: #1d4ed8; }

        /* Buttons */
        .a-btn {
            display: inline-flex; align-items: center; gap: 6px;
            border-radius: 9px; font-weight: 600; font-size: .82rem;
            padding: 8px 16px; border: none; cursor: pointer;
            text-decoration: none; transition: all .18s;
        }
        .a-btn-primary {
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
            color: #fff; box-shadow: 0 2px 8px rgba(99,102,241,.3);
        }
        .a-btn-primary:hover { box-shadow: 0 4px 14px rgba(99,102,241,.45); transform: translateY(-1px); color: #fff; }
        .a-btn-ghost { background: #f1f5f9; color: #475569; }
        .a-btn-ghost:hover { background: #e2e8f0; color: #1e293b; }
        .a-btn-danger { background: #fee2e2; color: #dc2626; }
        .a-btn-danger:hover { background: #fecaca; color: #b91c1c; }
        .a-btn-icon { padding: 7px 10px; }

        /* Forms */
        .form-control, .form-select {
            border-radius: 9px; border: 1.5px solid #e2e8f0;
            font-size: .9rem; padding: .55rem .85rem; transition: all .2s;
            font-family: 'Inter', sans-serif;
        }
        .form-control:focus, .form-select:focus {
            border-color: #6366f1;
            box-shadow: 0 0 0 3px rgba(99,102,241,.12);
        }
        .form-label { font-weight: 600; font-size: .82rem; margin-bottom: .35rem; color: #475569; }
        .form-text  { font-size: .75rem; color: #94a3b8; margin-top: .25rem; }
        .input-group-text {
            border-radius: 9px 0 0 9px; border: 1.5px solid #e2e8f0;
            background: #f8fafc; font-weight: 700; color: #64748b;
        }
        .input-group .form-control { border-radius: 0 9px 9px 0; border-left: none; }
        .input-group .form-control:focus { border-left: 1.5px solid #6366f1; }

        /* Alerts */
        .alert { border: none; border-radius: 12px; font-size: .88rem; font-weight: 500; }
        .alert-success { background: #d1fae5; color: #065f46; }
        .alert-danger  { background: #fee2e2; color: #991b1b; }

        /* Pagination */
        .pagination { gap: 3px; }
        .page-item .page-link {
            border-radius: 8px !important; border: 1.5px solid #e2e8f0;
            color: #475569; font-weight: 600; font-size: .8rem;
            padding: 6px 12px; transition: all .18s;
        }
        .page-item .page-link:hover { background: #6366f1; color: #fff; border-color: #6366f1; }
        .page-item.active .page-link { background: #6366f1; border-color: #6366f1; color: #fff; box-shadow: 0 2px 8px rgba(99,102,241,.35); }
        .page-item.disabled .page-link { opacity: .35; }

        /* Code */
        code {
            font-family: 'Courier New', monospace;
            background: #ede9fe; color: #6366f1;
            padding: 2px 8px; border-radius: 5px; font-size: .8em;
        }

        /* Progress */
        .progress { border-radius: 20px; background: #e8ecf4; }
        .progress-bar { border-radius: 20px; }

        /* Avatar circle */
        .mini-avatar {
            width: 32px; height: 32px; flex-shrink: 0;
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: .75rem; font-weight: 800; color: #fff;
        }
    </style>
    @stack('styles')
</head>
<body>

{{-- ── Sidebar ── --}}
<aside class="a-sidebar">
    <div class="sb-brand">
        <a href="{{ route('admin.dashboard') }}">
            <div class="sb-logo"><i class="bi bi-calendar-event-fill"></i></div>
            <div>
                <div class="sb-title">EventSY</div>
                <div class="sb-sub">Control Panel</div>
            </div>
        </a>
    </div>

    <nav class="sb-nav">
        <div class="sb-section">Overview</div>
        <a class="sb-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
            <div class="sb-icon"><i class="bi bi-grid-1x2-fill"></i></div>
            Dashboard
        </a>

        <div class="sb-section">Management</div>
        <a class="sb-link {{ request()->routeIs('admin.events*') ? 'active' : '' }}" href="{{ route('admin.events.index') }}">
            <div class="sb-icon"><i class="bi bi-calendar3"></i></div>
            Events
        </a>
        <a class="sb-link {{ request()->routeIs('admin.bookings') ? 'active' : '' }}" href="{{ route('admin.bookings') }}">
            <div class="sb-icon"><i class="bi bi-ticket-perforated"></i></div>
            Bookings
        </a>

        <div class="sb-section">External</div>
        <a class="sb-link" href="{{ route('events.index') }}" target="_blank">
            <div class="sb-icon"><i class="bi bi-globe2"></i></div>
            Public Site
            <span class="sb-badge"><i class="bi bi-box-arrow-up-right" style="font-size:.6rem"></i></span>
        </a>
    </nav>

    <div class="sb-footer">
        <div class="sb-user">
            <div class="sb-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
            <div style="min-width:0">
                <div class="sb-uname">{{ auth()->user()->name }}</div>
                <div class="sb-urole">{{ auth()->user()->email }}</div>
            </div>
        </div>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="sb-logout">
                <i class="bi bi-power"></i>Sign Out
            </button>
        </form>
    </div>
</aside>

{{-- ── Main Area ── --}}
<div class="a-main">
    <header class="a-topbar">
        <div class="tb-left">
            <div>
                <div class="tb-title">@yield('title', 'Dashboard')</div>
                <div class="tb-breadcrumb mt-1">
                    <span class="bc-prev">Admin</span>
                    <span class="bc-sep">/</span>
                    <span class="bc-curr">@yield('title', 'Dashboard')</span>
                </div>
            </div>
        </div>
        <div class="tb-right">
            <div class="tb-date">
                <i class="bi bi-calendar3"></i>
                {{ now()->format('D, M j Y') }}
            </div>
            <a href="{{ route('admin.events.create') }}" class="tb-new-event">
                <i class="bi bi-plus-lg"></i>New Event
            </a>
        </div>
    </header>

    <main class="a-content">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show d-flex align-items-center gap-2 mb-4">
                <i class="bi bi-check-circle-fill flex-shrink-0"></i>
                <span>{{ session('success') }}</span>
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center gap-2 mb-4">
                <i class="bi bi-exclamation-triangle-fill flex-shrink-0"></i>
                <span>{{ session('error') }}</span>
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if($errors->any())
            <div class="alert alert-danger mb-4">
                @foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach
            </div>
        @endif

        @yield('content')
    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>
