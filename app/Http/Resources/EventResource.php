<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Spatie\GoogleCalendar\Event;
use Illuminate\Http\Resources\Json\JsonResource;
use Google\Service\Calendar\Event as GoogleEvent;

class EventResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        /** @var Event $this */
        $googleEvent = $this->googleEvent;
        return [
            "id" => $googleEvent->id,
            "title" => $googleEvent->summary,
            "start_time" => Carbon::parse($googleEvent->getStart()->dateTime)->utc()->format("Y-m-d h:i a"),
            "end_time" => Carbon::parse($googleEvent->getEnd()->dateTime)->utc()->format("Y-m-d h:i a")
        ];
    }
}
