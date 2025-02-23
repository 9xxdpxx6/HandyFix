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
        Schema::create('brands', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('icon')->nullable();
            $table->text('description')->nullable();
            $table->boolean('is_original')->nullable();
            $table->char('registration_country_code', 2)->nullable();
            $table->char('production_country_code', 2)->nullable();
            $table->foreign('registration_country_code')->references('code')->on('countries')->onDelete('set null');
            $table->foreign('production_country_code')->references('code')->on('countries')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('brands', function (Blueprint $table) {
            $table->dropForeign(['registration_country_code']);
            $table->dropForeign(['production_country_code']);
        });

        Schema::dropIfExists('brands');
    }
};
