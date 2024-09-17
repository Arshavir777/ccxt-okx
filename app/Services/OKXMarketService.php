<?php
namespace App\Services;
use App\Models\Exchange;
use App\Models\MarketTicker;

class OKXMarketService
{
    function getTop100Markets()
    {
        return MarketTicker::orderBy('volume', 'desc')
            ->where('exchange', Exchange::OKX)
            ->limit(100)
            ->get();
    }
}
