<?php

use App\Exceptions\BookingConflictException;
use App\Exceptions\InsufficientSeatsException;
use App\Exceptions\PaymentFailedException;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\RequestTracing;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Global middleware — runs on every request
        $middleware->append(RequestTracing::class);

        // Named middleware aliases for route groups
        $middleware->alias([
            'admin' => AdminMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->renderable(function (InsufficientSeatsException $e, Request $request) {
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => $e->getMessage()], 409);
            }
            return back()->withErrors(['seats' => $e->getMessage()])->withInput();
        });

        $exceptions->renderable(function (BookingConflictException $e, Request $request) {
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
            }
            return back()->withErrors(['booking' => $e->getMessage()])->withInput();
        });

        $exceptions->renderable(function (PaymentFailedException $e, Request $request) {
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => $e->getMessage()], 402);
            }
            return back()->withErrors(['payment' => $e->getMessage()])->withInput();
        });
    })->create();
