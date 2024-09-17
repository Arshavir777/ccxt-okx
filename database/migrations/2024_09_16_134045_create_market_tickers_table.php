<?php

use App\Models\Exchange;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create(table: 'market_tickers', callback: function (Blueprint $table): void {
            $table->id();
            $table->string(column: 'symbol', length: 20);
            // TODO: relation by ID
            $table->string(column: 'exchange', length: 20);
            $table->text(column: 'icon')->nullable();
            $table->float(column: 'price');
            $table->float(column: 'volume');
            $table->timestamp('timestamp');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('market_tickers');
    }
};
