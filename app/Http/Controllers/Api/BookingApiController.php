<?php

namespace App\Http\Controllers\Api;

use App\Data\DTOs\BookingDTO;
use App\Exceptions\BookingConflictException;
use App\Exceptions\InsufficientSeatsException;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBookingRequest;
use App\Services\BookingService;
use App\Services\EventService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class BookingApiController extends Controller
{
    public function __construct(
        private readonly BookingService $bookingService,
        private readonly EventService   $eventService,
    ) {}

    /**
     * POST /api/post/bookings
     * Requires Bearer token (Sanctum).
     * Body: { "event_id": 1, "seats_requested": 2 }
     */
    public function store(StoreBookingRequest $request): JsonResponse
    {
        $event = $this->eventService->getEvent($request->event_id);
        if (!$event || $event->status !== 'active') {
            return response()->json(['success' => false, 'message' => 'Event not found or not active.'], 404);
        }

        $dto = BookingDTO::fromRequest($request->validated(), $request->user()->id, (float) $event->price);

        try {
            $booking = $this->bookingService->createBooking($dto);

            return response()->json([
                'success' => true,
                'data'    => [
                    'booking_ref'      => $booking->booking_ref,
                    'event_id'         => $booking->event_id,
                    'event_title'      => $event->title,
                    'seats_booked'     => $booking->seats_booked,
                    'total_amount'     => $booking->total_amount,
                    'status'           => $booking->status,
                    'payment_required' => true,
                    'created_at'       => $booking->created_at->toIso8601String(),
                ],
                'message' => 'Booking created. Complete payment to confirm.',
            ], 201);

        } catch (InsufficientSeatsException $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 409);
        } catch (BookingConflictException $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
        } catch (\Exception $e) {
            Log::channel('api')->error('Booking API error', [
                'error'    => $e->getMessage(),
                'user_id'  => $request->user()->id,
                'trace_id' => $request->header('X-Trace-ID'),
            ]);
            return response()->json(['success' => false, 'message' => 'An internal error occurred.'], 500);
        }
    }
}
