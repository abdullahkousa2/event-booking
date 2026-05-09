<?php

namespace App\Http\Controllers;

use App\Services\EventService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EventController extends Controller
{
    public function __construct(private readonly EventService $eventService) {}

    public function index(Request $request): View
    {
        $filters = $request->only(['location', 'date', 'available']);
        $events  = $this->eventService->paginatedEvents(9, $filters);
        return view('events.index', compact('events', 'filters'));
    }

    public function show(int $id): View
    {
        $event = $this->eventService->getEvent($id);
        abort_if(!$event, 404);
        return view('events.show', compact('event'));
    }
}
