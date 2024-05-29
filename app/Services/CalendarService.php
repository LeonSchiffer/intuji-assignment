<?php

namespace App\Services;

use Exception;
use Spatie\GoogleCalendar\Event;
use App\Events\CalendarUpdatedEvent;
use App\DataTransferObjects\EventDto;
use Illuminate\Support\Facades\Cache;

class CalendarService
{
    /**
     * @return \Illuminate\Support\Collection<int, Event>
     */
    public function getEvents()
    {
        return Event::get(queryParameters: [
            "timeZone" => "UTC"
        ]);
    }

    /**
     * @param EventDto $eventDto
     * @return mixed
     */
    public function store(EventDto $eventDto)
    {
        $event = Event::create(properties:[
            "name" => $eventDto->title,
            "startDateTime" => $eventDto->start_time,
            "endDateTime" => $eventDto->end_time,
        ]);
        CalendarUpdatedEvent::dispatch();
        return $event;
    }

    /**
     * @param string $event_id
     * @return Event
     */
    public function find(string $event_id): Event
    {
        return Event::find($event_id);
    }

    /**
     * @param string $event_id
     * @return void
     */
    public function delete(string $event_id)
    {
        /** @var ?Event $event */
        $event = $this->find($event_id);
        if (is_null($event))
            throw new Exception("Event not found", 404);
        $event->delete();
        $this->removeEventFromCache($event_id);
    }

    /**
     * Removes the event only if the event list has been cached
     * @param string $event_id
     * @return void
     */
    public function removeEventFromCache(string $event_id)
    {
        if (!Cache::has("events"))
            return;

        /** @var \Illuminate\Support\Collection<int, \Google\Service\Calendar\Event> $events */
        $events = Cache::get("events");
        Cache::set(
            "events",
            $events->filter(fn($item) => $item->id != $event_id)
        );
    }
}
