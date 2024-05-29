<?php

namespace App\Events;

use Illuminate\Support\Facades\Event;
use Illuminate\Queue\SerializesModels;
use App\Listeners\UpdateEventListListener;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

class BaseEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /** @var array<int, mixed> */
    protected array $listeners = [];

    /**
     * Create a new event instance.
     */
    public function __construct()
    {
        foreach ($this->listeners as $listener)
            Event::listen(static::class, $listener);
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
