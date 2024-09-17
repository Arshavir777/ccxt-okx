<?php

namespace App\Livewire;

use App\Models\Exchange;
use Livewire\Component;
use Filament\Tables\Table;
use App\Models\MarketTicker;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Illuminate\Contracts\View\View;

class ListCurrencies extends Component implements HasForms, HasTable
{
    use InteractsWithTable;
    use InteractsWithForms;

    // TODO: handle specific ticker and update ... :)

    // #[On('echo:market-ticker,MarketTickerUpdate')]
    // function notifyUpdatedMarket($event)
    // {
    //     $event['content'];
    // }

    public function getListeners()
    {
        return [
            "echo:market-ticker,MarketTickerUpdated" => '$refresh',
        ];
    }

    public function table(Table $table): Table
    {
        return $table
            ->heading('Today’s cryptocurrency prices: REAL-TIME')
            ->description('View the latest prices for the hundreds of digital assets listed on OKX, alongside their daily price change and market cap statistics.')
            ->query(MarketTicker::query()->where('exchange', Exchange::OKX)->orderBy('volume', 'desc'))
            ->columns([
                ImageColumn::make('icon')
                    ->state(function ($record) {
                        $symbol = strtolower($record->symbol);
                        return "https://www.okx.com/cdn/oksupport/asset/currency/icon/{$symbol}.png";
                    }),
                TextColumn::make('symbol')->searchable(),
                TextColumn::make('price')->money('USD')->sortable(),
                TextColumn::make('volume')->money('USD')->sortable(),
                TextColumn::make('timestamp')->dateTime(),
            ])
            ->emptyStateHeading(heading: 'No pairs yet')
            ->emptyStateDescription('Please check `okx:watch-tickers` command');
        //TODO: Another way to show up to date prices :)
        // ->poll('1s');
    }

    public function render(): View
    {
        return view('livewire.list-currencies');
    }
}
