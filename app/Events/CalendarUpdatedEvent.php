<?php

namespace App\Events;

use Illuminate\Queue\SerializesModels;
use App\Listeners\UpdateEventListListener;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

class CalendarUpdatedEvent extends BaseEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * No need to register events in ServiceProviders
     * You can just add them to the array here
     * And it will automatically register them as listeners
     *
     * To understand how this works, checkout the BaseEvent parent class
     * @see \App\Events\BaseEvent
     */
    protected array $listeners = [
        UpdateEventListListener::class
    ];

    /**
     * Create a new event instance.
     */
    public function __construct()
    {
        parent::__construct();
    }
}
