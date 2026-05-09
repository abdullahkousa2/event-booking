<?php

namespace App\Http\Controllers\Admin;

use App\Data\DTOs\EventDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEventRequest;
use App\Http\Requests\UpdateEventRequest;
use App\Services\EventService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class EventController extends Controller
{
    public function __construct(private readonly EventService $eventService) {}

    public function index(): View
    {
        $events = $this->eventService->paginatedEvents(15);
        return view('admin.events.index', compact('events'));
    }

    public function create(): View
    {
        return view('admin.events.create');
    }

    public function store(StoreEventRequest $request): RedirectResponse
    {
        $dto = EventDTO::fromRequest($request->validated(), auth()->id());
        $this->eventService->createEvent($dto);
        return redirect()->route('admin.events.index')->with('success', 'Event created successfully.');
    }

    public function show(int $id): View
    {
        $event = $this->eventService->getEvent($id);
        abort_if(!$event, 404);
        return view('admin.events.show', compact('event'));
    }

    public function edit(int $id): View
    {
        $event = $this->eventService->getEvent($id);
        abort_if(!$event, 404);
        return view('admin.events.edit', compact('event'));
    }

    public function update(UpdateEventRequest $request, int $id): RedirectResponse
    {
        $dto = EventDTO::fromRequest($request->validated(), auth()->id());
        $this->eventService->updateEvent($id, $dto);
        return redirect()->route('admin.events.index')->with('success', 'Event updated successfully.');
    }

    public function destroy(int $id): RedirectResponse
    {
        $this->eventService->deleteEvent($id);
        return redirect()->route('admin.events.index')->with('success', 'Event deleted.');
    }
}
