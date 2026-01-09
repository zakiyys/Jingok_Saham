<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('eod_prices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->date('date')->index();
            $table->decimal('open', 12, 2);
            $table->decimal('high', 12, 2);
            $table->decimal('low', 12, 2);
            $table->decimal('close', 12, 2);
            $table->unsignedBigInteger('volume');
            $table->timestamps();

            $table->unique(['company_id', 'date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('eod_prices');
    }
};
