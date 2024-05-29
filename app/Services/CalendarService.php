<?php

namespace App\Services;

use App\DataTransferObjects\EventDto;
use Exception;
use Illuminate\Support\Facades\Cache;
use Spatie\GoogleCalendar\Event;

class CalendarService
{
    /**
     * @param ?string $start_time
     * @param ?string $end_time
     * @param array<string, mixed> $parameters
     * @return \Illuminate\Support\Collection<int, Event>
     */
    public function getEvents(string $start_time = null, string $end_time = null, array $parameters = [])
    {
        return Event::get();
    }

    /**
     * @param EventDto $eventDto
     * @return mixed
     */
    public function store(EventDto $eventDto)
    {
        return Event::create([
            "name" => $eventDto->title,
            "startDateTime" => $eventDto->start_time,
            "endDateTime" => $eventDto->end_time,
        ]);
    }

    /**
     * @param string $event_id
     * @param EventDto $eventDto
     * @return Event
     */
    public function update(string $event_id, EventDto $eventDto)
    {
        /** @var ?Event $event */
        $event = $this->find($event_id);
        if (is_null($event))
            throw new Exception("Event not found", 404);
        $event = $event->update([
            "name" => $eventDto->title,
            "startDateTime" => $eventDto->start_time,
            "endDateTime" => $eventDto->end_time,
        ]);
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
        // $this->removeEventFromCache($event_id);
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
