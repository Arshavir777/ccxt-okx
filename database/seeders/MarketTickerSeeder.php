<?php

namespace Database\Seeders;

use DB;
use App\Models\Exchange;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class MarketTickerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('market_tickers')->insert([
            [
                'symbol' => 'BTC',
                'exchange' => Exchange::OKX,
                'icon' => 'https://www.okx.com/cdn/oksupport/asset/currency/icon/btc.png',
                'price' => 27350.75,
                'volume' => 45924000.50,
                'timestamp' => Carbon::now(),
            ],
            [
                'symbol' => 'ETH',
                'exchange' => Exchange::OKX,
                'icon' => 'https://www.okx.com/cdn/oksupport/asset/currency/icon/eth.png',
                'price' => 1750.15,
                'volume' => 21562000.85,
                'timestamp' => Carbon::now(),
            ],
            [
                'symbol' => 'BNB',
                'exchange' => Exchange::OKX,
                'icon' => 'https://www.okx.com/cdn/oksupport/asset/currency/icon/bnb.png',
                'price' => 220.50,
                'volume' => 12563000.25,
                'timestamp' => Carbon::now(),
            ]
        ]);
    }
}
