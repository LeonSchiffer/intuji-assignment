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
     * Display a listing of the resource.
     */
    public function index()
    {
        $events = Cache::remember("events", (1 * 60 * 60), function () {
            return $this->calendar->getEvents();
        });
        return EventResource::collection($events);
    }

    /**
     * Store a newly created resource in storage.
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
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $event_id)
    {
        $this->calendar->delete($event_id);
        return response()->noContent();
    }
}
