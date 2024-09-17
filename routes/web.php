<?php

use App\Livewire\ListCurrencies;
use Illuminate\Support\Facades\Route;

Route::redirect('/', 'okx/markets');
Route::get('okx/markets', ListCurrencies::class);
