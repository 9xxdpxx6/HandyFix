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
        Schema::create('service_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('day_ledger_id')->index()->constrained('day_ledgers')->onDelete('cascade');
            $table->foreignId('service_type_id')->index()->constrained('service_types')->onDelete('cascade');
            $table->decimal('cost', 15, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_entries');
    }
};
