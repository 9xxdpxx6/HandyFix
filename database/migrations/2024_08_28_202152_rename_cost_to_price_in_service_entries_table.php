<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('service_entries', function (Blueprint $table) {
            // Создаем новый столбец price и nullable поле description
            $table->decimal('price', 15, 2)->after('cost');
            $table->string('description')->nullable()->after('price');
        });

        // Копируем данные из старого столбца cost в новый столбец price
        DB::statement('UPDATE service_entries SET price = cost');

        Schema::table('service_entries', function (Blueprint $table) {
            // Удаляем старый столбец cost
            $table->dropColumn('cost');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('service_entries', function (Blueprint $table) {
            // Создаем столбец cost
            $table->decimal('cost', 15, 2)->after('price');
        });

        // Копируем данные обратно из столбца price в cost
        DB::statement('UPDATE service_entries SET cost = price');

        Schema::table('service_entries', function (Blueprint $table) {
            // Удаляем столбец price и description
            $table->dropColumn('price');
            $table->dropColumn('description');
        });
    }
};
