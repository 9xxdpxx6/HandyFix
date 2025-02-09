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
        Schema::table('expense_entries', function (Blueprint $table) {
            // Делаем поле description nullable
            $table->string('description')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('expense_entries', function (Blueprint $table) {
            // Если требуется отменить миграцию, сделаем поле non-nullable
            $table->string('description')->nullable(false)->change();
        });
    }
};
