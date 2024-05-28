<?php

namespace App\Http\Controllers\Api;

use App\DataTransferObjects\EventDto;
use Illuminate\Http\Request;
use App\Http\Requests\EventRequest;
use App\Http\Controllers\Controller;
use App\Http\Resources\EventResource;
use App\Services\CalendarService;
use Illuminate\Support\Facades\Cache;

class EventController extends Controller
{
    public function __construct(private CalendarService $calendar)
    {

    }

    /**
     * Get a list of events from google calendar
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
    public function store(EventRequest $request): \Illuminate\Http\Response
    {
        $event = $this->calendar->store(
            EventDto::fromRequest($request)
        );
        Cache::forget("events");
        return response(EventResource::make($event), 201);
    }

    /**
     * Remove the specified event from google calendar
     */
    public function destroy(string $event_id)
    {
        $this->calendar->delete($event_id);
        return response()->noContent();
    }
}
