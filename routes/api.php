<?php

use App\Http\Controllers\OKXMarketController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('okx/top-100-markets', [OKXMarketController::class, 'getTop100Markets']);
