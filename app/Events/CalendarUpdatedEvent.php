<?php

namespace App\Events;

use Illuminate\Queue\SerializesModels;
use App\Listeners\UpdateEventListListener;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Support\Facades\Event;

class CalendarUpdatedEvent extends BaseEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

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

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('channel-name'),
        ];
    }
}
