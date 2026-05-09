<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Services\AuthService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class LoginController extends Controller
{
    public function __construct(private readonly AuthService $authService) {}

    public function showForm(): View
    {
        return view('auth.login');
    }

    public function login(LoginRequest $request): RedirectResponse
    {
        if (!$this->authService->attempt($request->email, $request->password)) {
            return back()->withErrors(['email' => 'Invalid credentials.'])->withInput();
        }

        $request->session()->regenerate();

        return auth()->user()->isAdmin()
            ? redirect()->route('admin.dashboard')
            : redirect()->route('events.index');
    }

    public function logout(): RedirectResponse
    {
        $this->authService->logout();
        return redirect()->route('login');
    }
}
