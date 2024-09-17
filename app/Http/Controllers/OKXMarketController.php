<?php

namespace App\Http\Controllers;

use App\Services\OKXMarketService;

class OKXMarketController extends Controller
{
    public function getTop100Markets(OKXMarketService $marketService)
    {
        return response()->json([
            'markets' => $marketService->getTop100Markets()
        ]);
    }
}
