<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\EventService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EventApiController extends Controller
{
    public function __construct(private readonly EventService $eventService) {}

    /**
     * GET /api/get/events
     * Returns paginated list of active events.
     * Query params: ?location=&date=&available=true&per_page=10
     */
    public function index(Request $request): JsonResponse
    {
        $filters = $request->only(['location', 'date', 'available']);
        $perPage = (int) $request->get('per_page', 10);
        $events  = $this->eventService->paginatedEvents($perPage, $filters);

        return response()->json([
            'success' => true,
            'data'    => $events->items(),
            'meta'    => [
                'total'        => $events->total(),
                'count'        => count($events->items()),
                'per_page'     => $events->perPage(),
                'current_page' => $events->currentPage(),
                'last_page'    => $events->lastPage(),
                'timestamp'    => now()->toIso8601String(),
            ],
        ]);
    }
}
