<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class RequestTracing
{
    public function handle(Request $request, Closure $next): Response
    {
        // Generate or inherit trace ID for distributed tracing
        $traceId = $request->header('X-Trace-ID', (string) Str::uuid());
        $request->headers->set('X-Trace-ID', $traceId);

        // Share trace context with all log entries for this request
        Log::shareContext([
            'trace_id'   => $traceId,
            'ip'         => $request->ip(),
            'user_id'    => auth()->id(),
            'request_id' => Str::random(8),
        ]);

        // Log API requests to the api channel
        if ($request->is('api/*')) {
            Log::channel('api')->debug('API Request', [
                'method'   => $request->method(),
                'path'     => $request->path(),
                'user_id'  => auth()->id(),
                'trace_id' => $traceId,
            ]);
        }

        $response = $next($request);

        // Echo trace ID back in response header so callers can reference it
        $response->headers->set('X-Trace-ID', $traceId);

        return $response;
    }
}
