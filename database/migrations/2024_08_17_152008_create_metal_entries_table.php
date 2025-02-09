<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('metal_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('day_ledger_id')->index()->constrained('day_ledgers')->onDelete('cascade');
            $table->decimal('incoming', 15, 2)->default(0);
            $table->decimal('issuance', 15, 2)->default(0);
            $table->decimal('waste', 15, 2)->default(0);
            $table->decimal('balance', 15, 2)->default(0);
            $table->string('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('metal_entries');
    }
};
