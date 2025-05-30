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
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('customers')->onDelete('cascade');
            $table->foreignId('model_id')->constrained('vehicle_models')->onDelete('cascade');
            $table->year('year');
            $table->string('license_plate')->unique();
            $table->string('vin')->unique()->nullable();
            $table->integer('mileage')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vehicles', function (Blueprint $table) {
            $table->dropForeign(['customer_id']);
            $table->dropForeign(['model_id']);
        });

        Schema::dropIfExists('vehicles');
    }
};
