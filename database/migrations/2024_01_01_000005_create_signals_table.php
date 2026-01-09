<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('signals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->string('timeframe');
            $table->date('as_of_date')->nullable();
            $table->decimal('last_close', 12, 2)->nullable();
            $table->decimal('pct_change', 8, 2)->nullable();
            $table->decimal('rsi14', 8, 2)->nullable();
            $table->decimal('macd', 12, 4)->nullable();
            $table->decimal('macd_signal', 12, 4)->nullable();
            $table->decimal('macd_hist', 12, 4)->nullable();
            $table->decimal('vol_ratio', 8, 2)->nullable();
            $table->integer('score')->nullable();
            $table->string('recommendation')->nullable();
            $table->json('reasons')->nullable();
            $table->json('targets')->nullable();
            $table->timestamp('computed_at')->nullable();
            $table->timestamps();

            $table->index(['company_id', 'timeframe']);
            $table->index('computed_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('signals');
    }
};
