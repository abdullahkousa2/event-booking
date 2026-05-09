@extends('layouts.app')
@section('title', 'Sign In — EventSY')

@push('styles')
<style>
.auth-split {
    min-height: calc(100vh - 68px);
    display: flex;
    align-items: stretch;
}

/* Left branding panel */
.auth-panel-left {
    width: 42%;
    background: linear-gradient(145deg, #070b18 0%, #0f172a 30%, #1e1b4b 65%, #312e81 100%);
    position: relative;
    overflow: hidden;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    padding: 52px 48px;
}
.auth-panel-left::before {
    content: '';
    position: absolute; inset: 0;
    background:
        radial-gradient(ellipse at 80% 20%, rgba(99,102,241,.35) 0%, transparent 55%),
        radial-gradient(ellipse at 10% 85%, rgba(245,158,11,.2) 0%, transparent 50%);
}
.auth-panel-blob {
    position: absolute; border-radius: 50%; filter: blur(70px); opacity: .3;
}
.auth-panel-blob-1 { width: 380px; height: 380px; background: #4f46e5; top: -100px; right: -80px; }
.auth-panel-blob-2 { width: 240px; height: 240px; background: #7c3aed; bottom: 40px; left: -60px; }
.auth-panel-content { position: relative; z-index: 2; }

.auth-brand-logo {
    display: flex; align-items: center; gap: 10px;
    margin-bottom: 56px;
}
.auth-brand-icon {
    width: 40px; height: 40px;
    background: linear-gradient(135deg, #4f46e5, #818cf8);
    border-radius: 11px;
    display: flex; align-items: center; justify-content: center;
    font-size: 1rem; color: #fff;
}
.auth-brand-name { font-size: 1.3rem; font-weight: 800; color: #fff; letter-spacing: -.2px; }
.auth-brand-name span { color: #f59e0b; }

.auth-panel-headline {
    font-size: 2.1rem; font-weight: 800; color: #fff;
    letter-spacing: -.5px; line-height: 1.15;
    margin-bottom: 16px;
}
.auth-panel-headline .hl { background: linear-gradient(90deg, #818cf8, #c4b5fd); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; }
.auth-panel-sub { color: rgba(255,255,255,.55); font-size: .95rem; line-height: 1.65; margin-bottom: 40px; }

.auth-feature-item {
    display: flex; align-items: flex-start; gap: 12px;
    margin-bottom: 18px;
}
.auth-feature-icon {
    width: 36px; height: 36px; border-radius: 10px; flex-shrink: 0;
    background: rgba(99,102,241,.2);
    border: 1px solid rgba(99,102,241,.3);
    display: flex; align-items: center; justify-content: center;
    font-size: .9rem; color: #818cf8;
}
.auth-feature-title { font-size: .875rem; font-weight: 700; color: #fff; }
.auth-feature-desc  { font-size: .78rem; color: rgba(255,255,255,.45); margin-top: 2px; }

.auth-panel-footer {
    position: relative; z-index: 2;
    border-top: 1px solid rgba(255,255,255,.08);
    padding-top: 24px;
    font-size: .78rem; color: rgba(255,255,255,.3);
}

/* Right form panel */
.auth-panel-right {
    flex: 1;
    background: #fff;
    background-image:
        radial-gradient(circle at 90% 10%, rgba(99,102,241,.06) 0%, transparent 40%),
        radial-gradient(circle at 10% 90%, rgba(124,58,237,.05) 0%, transparent 40%);
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 52px 48px;
    overflow-y: auto;
    position: relative;
}
.auth-panel-right::after {
    content: '';
    position: absolute;
    bottom: 32px; right: 32px;
    width: 180px; height: 180px;
    background: url("data:image/svg+xml,%3Csvg width='180' height='180' xmlns='http://www.w3.org/2000/svg'%3E%3Cdefs%3E%3Cpattern id='dots' x='0' y='0' width='18' height='18' patternUnits='userSpaceOnUse'%3E%3Ccircle cx='2' cy='2' r='1.5' fill='%234f46e5' opacity='0.08'/%3E%3C/pattern%3E%3C/defs%3E%3Crect width='180' height='180' fill='url(%23dots)'/%3E%3C/svg%3E") no-repeat;
    pointer-events: none;
    opacity: .7;
}
.auth-form-wrap { width: 100%; max-width: 380px; }
.auth-form-title { font-size: 1.75rem; font-weight: 800; color: #0f172a; letter-spacing: -.4px; margin-bottom: 6px; }
.auth-form-sub   { font-size: .9rem; color: #64748b; margin-bottom: 32px; }

.auth-input-group { margin-bottom: 18px; }
.auth-input-group label { font-size: .82rem; font-weight: 700; color: #1e293b; display: block; margin-bottom: 6px; }
.auth-input-group .form-control {
    border: 1.5px solid #e2e8f0;
    border-radius: 10px;
    padding: 11px 14px;
    font-size: .94rem;
    transition: all .2s;
}
.auth-input-group .form-control:focus {
    border-color: #4f46e5;
    box-shadow: 0 0 0 3px rgba(79,70,229,.1);
}

.btn-auth-submit {
    width: 100%;
    background: linear-gradient(135deg, #4f46e5, #7c3aed);
    color: #fff; border: none; border-radius: 11px;
    font-weight: 700; font-size: 1rem; padding: 13px;
    transition: all .2s; letter-spacing: .1px;
}
.btn-auth-submit:hover { transform: translateY(-1px); box-shadow: 0 8px 24px rgba(79,70,229,.4); color: #fff; }

.auth-divider {
    position: relative; text-align: center; margin: 24px 0;
}
.auth-divider::before {
    content: ''; position: absolute; left: 0; right: 0; top: 50%;
    border-top: 1px solid #e2e8f0;
}
.auth-divider span {
    position: relative; background: #fff;
    padding: 0 12px; font-size: .72rem; color: #94a3b8; font-weight: 700;
    letter-spacing: .5px; text-transform: uppercase;
}

.demo-box {
    background: #f8fafc;
    border: 1.5px solid #e2e8f0;
    border-radius: 12px; padding: 14px 16px;
    font-size: .8rem; color: #475569;
}
.demo-box .demo-row { display: flex; align-items: center; justify-content: space-between; }
.demo-box .demo-role { font-weight: 700; color: #1e293b; }

@media (max-width: 768px) {
    .auth-panel-left { display: none; }
    .auth-panel-right { padding: 40px 24px; }
}
/* Remove default layout gaps on auth pages */
main.pb-5 { padding-bottom: 0 !important; }
footer { margin-top: 0 !important; }
</style>
@endpush

@section('content')
<div class="auth-split">

    {{-- Left branding panel --}}
    <div class="auth-panel-left">
        <div class="auth-panel-blob auth-panel-blob-1"></div>
        <div class="auth-panel-blob auth-panel-blob-2"></div>

        <div class="auth-panel-content">
            <div class="auth-brand-logo">
                <div class="auth-brand-icon"><i class="bi bi-calendar-event-fill"></i></div>
                <div class="auth-brand-name">Event<span>SY</span></div>
            </div>

            <h2 class="auth-panel-headline">
                Your gateway to<br>
                <span class="hl">unforgettable events</span>
            </h2>
            <p class="auth-panel-sub">
                Join thousands of people discovering the best events across Syria — from workshops to concerts, all in one platform.
            </p>

            <div class="auth-feature-item">
                <div class="auth-feature-icon"><i class="bi bi-lightning-charge-fill"></i></div>
                <div>
                    <div class="auth-feature-title">Instant Booking</div>
                    <div class="auth-feature-desc">Reserve seats in seconds with real-time availability</div>
                </div>
            </div>
            <div class="auth-feature-item">
                <div class="auth-feature-icon"><i class="bi bi-ticket-perforated-fill"></i></div>
                <div>
                    <div class="auth-feature-title">Manage Bookings</div>
                    <div class="auth-feature-desc">Track all your reservations from a single dashboard</div>
                </div>
            </div>
            <div class="auth-feature-item">
                <div class="auth-feature-icon"><i class="bi bi-shield-check-fill"></i></div>
                <div>
                    <div class="auth-feature-title">Secure Payments</div>
                    <div class="auth-feature-desc">Your data and transactions are always protected</div>
                </div>
            </div>
        </div>

        <div class="auth-panel-footer">
            &copy; {{ date('Y') }} EventSY &mdash; Syria's Event Booking Platform
        </div>
    </div>

    {{-- Right form panel --}}
    <div class="auth-panel-right">
        <div class="auth-form-wrap">
            <div class="auth-form-title">Welcome back</div>
            <div class="auth-form-sub">Sign in to your EventSY account to continue</div>

            <form method="POST" action="/login">
                @csrf
                <div class="auth-input-group">
                    <label>Email Address</label>
                    <input type="email" name="email"
                           class="form-control @error('email') is-invalid @enderror"
                           value="{{ old('email') }}"
                           placeholder="you@example.com"
                           required autofocus>
                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="auth-input-group" style="margin-bottom:24px">
                    <label>Password</label>
                    <input type="password" name="password" class="form-control"
                           placeholder="••••••••" required>
                </div>
                <button type="submit" class="btn-auth-submit">
                    <i class="bi bi-box-arrow-in-right me-2"></i>Sign In
                </button>
            </form>

            <p class="text-center mt-4 mb-0" style="font-size:.875rem;color:#64748b">
                Don't have an account?
                <a href="{{ route('register') }}" style="color:#4f46e5;font-weight:700">Create one free</a>
            </p>

            <div class="auth-divider mt-4"><span>Demo Accounts</span></div>

            <div class="demo-box">
                <div class="demo-row mb-2">
                    <span class="demo-role"><i class="bi bi-shield-fill-check me-1" style="color:#4f46e5"></i>Admin</span>
                    <span style="font-family:monospace;font-size:.75rem">admin@test.com / password</span>
                </div>
                <div class="demo-row">
                    <span class="demo-role"><i class="bi bi-person-fill me-1" style="color:#10b981"></i>User</span>
                    <span style="font-family:monospace;font-size:.75rem">user1@test.com / password</span>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
