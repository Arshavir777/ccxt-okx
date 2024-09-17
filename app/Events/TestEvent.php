<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;

class TestEvent implements ShouldBroadcastNow
{
    use Dispatchable;

    public function __construct(
        public string $content
    ) {
    }

    public function broadcastOn()
    {
        return new Channel('test-channel');
    }
}
