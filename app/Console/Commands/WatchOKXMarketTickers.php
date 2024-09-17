<?php

namespace App\Console\Commands;

use App\Models\Exchange;
use App\Models\MarketTicker;
use ccxt\pro\okx;
use Illuminate\Console\Command;
use React\EventLoop\Factory as LoopFactory;

class WatchOKXMarketTickers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'okx:watch-tickers';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch and watch specific market tickers';

    public function handle()
    {
        $loop = LoopFactory::create();

        // Init ccxt.pro for OKX
        $exchange = new okx();

        $exchange->fetch_tickers()
            ->then(function ($marketTickers) use ($exchange, $loop) {

                $topTickers = $this->storeTop100MarketTickers($marketTickers);

                $this->info('>>> Watch top 100 market tickers ...');

                $loop->futureTick(function () use ($exchange, $topTickers, $loop) {
                    $this->watchTickers($exchange, $topTickers, $loop);
                });
            })->catch(function ($e) {
                $this->error("Error loading markets: " . $e->getMessage());
            });

        $loop->run();
    }

    private function storeTop100MarketTickers($markets)
    {
        $marketTickersWithVolume = [];
        foreach ($markets as $pair => $marketTicker) {
            if (str_ends_with($pair, "/USDT")) {
                $symbol = explode("/", $pair)[0];
                $volume = isset($marketTicker['quoteVolume']) ? $marketTicker['quoteVolume'] : 0;
                $timestamp = $marketTicker['datetime'];
                $price = $marketTicker['average'];
                $volume = $marketTicker['quoteVolume'];

                $marketTickersWithVolume[] = [
                    'pair' => $pair,
                    'symbol' => $symbol,
                    'price' => $price,
                    'volume' => $volume,
                    'timestamp' => $timestamp
                ];
            }
        }

        // Sort markets by volume in descending order
        usort($marketTickersWithVolume, function ($a, $b) {
            return $b['volume'] - $a['volume'];
        });

        // Get the top 100 markets
        $topMarketTickers = array_slice($marketTickersWithVolume, 0, 100);

        $this->info('>>> Fetch and store top 100 market tickers');

        foreach ($topMarketTickers as $marketTicker) {
            MarketTicker::updateOrCreate([
                'symbol' => $marketTicker['symbol'],
                'exchange' => Exchange::OKX
            ], [
                'symbol' => $marketTicker['symbol'],
                'price' => $marketTicker['price'],
                'volume' => $marketTicker['volume'],
                'timestamp' => $marketTicker['timestamp'],
            ]);
        }

        return array_map(function ($ticker) {
            return $ticker['pair'];
        }, $topMarketTickers);
    }


    private function watchTickers(okx $exchange, $topTickers, $loop)
    {
        $exchange
            ->watch_tickers($topTickers)
            ->then(function ($updatedTickers) use ($loop, $topTickers, $exchange) {

                // Process and store the ticker data in the database
                $this->updateTickers($updatedTickers);

                // Schedule the next call to watchTickers by re-enqueuing it in the loop
                $loop->futureTick(function () use ($exchange, $topTickers, $loop): void {
                    $this->watchTickers($exchange, $topTickers, $loop);
                });
            })->catch(function ($e) {
                $this->error("Error: " . $e->getMessage());
            });
    }

    /**
     * Update market tickers on DB
     * @param mixed $tickers
     * @return never
     */
    protected function updateTickers($tickers): void
    {
        foreach ($tickers as $pair => $ticker) {
            $symbol = explode("/", $pair)[0];
            $timestamp = $ticker['datetime'];
            $price = $ticker['average'];
            $volume = $ticker['quoteVolume'];

            MarketTicker::updateOrCreate([
                'symbol' => $symbol,
                'exchange' => Exchange::OKX
            ], [
                'symbol' => $symbol,
                'price' => $price,
                'volume' => $volume,
                'timestamp' => $timestamp
            ]);
        }
    }
}
