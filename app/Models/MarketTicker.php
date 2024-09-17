<?php

namespace App\Models;

use App\Events\MarketTickerUpdated;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Log;

// TODO: will be good to have separated exchange/provider 
class MarketTicker extends Model
{
    use HasFactory;

    // Disable default timestamps
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'exchange',
        'symbol',
        'icon',
        'price',
        'volume',
        'timestamp',
    ];

    protected static function booted()
    {
        static::updated(function ($data) {
            Log::info('Dispatching MarketTickerUpdated event for data: ' . $data->symbol);
            event(new MarketTickerUpdated($data));
        });
    }
}
