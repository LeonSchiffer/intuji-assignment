<?php

namespace App\DataTransferObjects;

use Carbon\Carbon;
use Illuminate\Http\Request;

class EventDto
{
    public function __construct(public string $title, public Carbon $start_time, public ?Carbon $end_time)
    {

    }

    /**
     * Will create a new EventDto object from a request
     */
    public static function fromRequest(Request $request): self
    {
        return new self(
            title: $request->title,
            start_time: Carbon::parse($request->start_time),
            end_time: $request->end_time ? Carbon::parse($request->end_time) : null
        );
    }

    /**
     * Convert the object into readable array format
     * @return array<string, mixed>
     */
    public function toArray()
    {
        return [
            "title" => $this->title,
            "start_time" => $this->start_time,
            "end_time" => $this->end_time
        ];
    }
}
