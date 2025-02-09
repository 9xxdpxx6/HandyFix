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
        Schema::table('material_entries', function (Blueprint $table) {
            // Удаляем значения по умолчанию и делаем поля nullable
            $table->decimal('incoming', 15, 2)->nullable()->change();
            $table->decimal('issuance', 15, 2)->nullable()->change();
            $table->decimal('waste', 15, 2)->nullable()->change();
            $table->decimal('balance', 15, 2)->nullable()->change();
            $table->string('description')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('material_entries', function (Blueprint $table) {
            // Возвращаем предыдущие значения по умолчанию и делаем поля не-nullable
            $table->decimal('incoming', 15, 2)->default(0)->change();
            $table->decimal('issuance', 15, 2)->default(0)->change();
            $table->decimal('waste', 15, 2)->default(0)->change();
            $table->decimal('balance', 15, 2)->default(0)->change();
            $table->string('description')->nullable(false)->change();
        });
    }
};
