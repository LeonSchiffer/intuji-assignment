<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EventResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // dd($this);
        return [
            "id" => $this->id,
            "title" => $this->summary,
            "start_time" => Carbon::parse($this->start->dateTime, "Asia/Kathmandu")->format("Y-m-d h:i a"),
            "end_time" => $this->start->dateTime ? Carbon::parse($this->end->dateTime, "Asia/Kathmandu")->format("Y-m-d h:i a") : null
        ];
    }
}
