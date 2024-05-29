<?php

namespace App\Http\Controllers\Api;

use App\DataTransferObjects\EventDto;
use App\Events\CalendarUpdatedEvent;
use Illuminate\Http\Request;
use App\Http\Requests\CreateEventRequest;
use App\Http\Controllers\Controller;
use App\Http\Resources\EventResource;
use App\Services\CalendarService;
use Exception;
use Illuminate\Support\Facades\Cache;

class EventController extends Controller
{
    public function __construct(private CalendarService $calendar)
    {
    }

    /**
     * Get a list of events from google calendar
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        $events = Cache::remember("events", (1 * 60 * 60), function () {
            return $this->calendar->getEvents();
        });
        return EventResource::collection($events);
    }

    /**
     * Create a new event in Google calendar
     */
    public function store(CreateEventRequest $request): \Illuminate\Http\JsonResponse
    {
        $event = $this->calendar->store(
            EventDto::fromRequest($request)
        );
        CalendarUpdatedEvent::dispatch();
        return response()->json(
            EventResource::make($event)->toArray($request),
            201
        );
    }

    // public function show(string $event_id)
    // {
    //     return dd($this->calendar->find($event_id));
    // }

    // public function update(CreateEventRequest $request, string $event_id): \Illuminate\Http\JsonResponse
    // {
    //     $event = $this->calendar->update(
    //         $event_id,
    //         EventDto::fromRequest($request)
    //     );
    //     return response()->json(EventResource::make($event));
    // }

    /**
     * Remove the specified event from google calendar
     * @return \Illuminate\Http\Response
     */
    public function destroy(string $event_id)
    {
        try {
            $this->calendar->delete($event_id);
            CalendarUpdatedEvent::dispatch();
            return response()->noContent();
        } catch (Exception $ex) {
            return response(["message" => $ex->getMessage()], $ex->getCode());
        }
    }
}
