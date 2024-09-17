<?php

namespace App\Events;

use App\Models\MarketTicker;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MarketTickerUpdated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * The market ticker instance.
     *
     * @var MarketTicker
     */
    public $marketTicker;

    /**
     * Create a new event instance.
     */
    public function __construct(MarketTicker $marketTicker)
    {
        $this->marketTicker = $marketTicker;
    }

    public function broadcastOn()
    {
        return new Channel('market-ticker');
    }
}
