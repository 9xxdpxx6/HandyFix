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
        Schema::table('expense_entries', function (Blueprint $table) {
            // Создаем новый столбец description
            $table->string('description')->after('day_ledger_id');
            // Создаем новый столбец price
            $table->decimal('price', 15, 2)->after('description');
        });

        // Копируем данные из старого столбца name в description
        DB::statement('UPDATE expense_entries SET description = name');
        // Копируем данные из старого столбца cost в price
        DB::statement('UPDATE expense_entries SET price = cost');

        Schema::table('expense_entries', function (Blueprint $table) {
            // Удаляем старые столбцы name и cost
            $table->dropColumn('name');
            $table->dropColumn('cost');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('expense_entries', function (Blueprint $table) {
            // Создаем столбцы name и cost
            $table->string('name')->after('day_ledger_id');
            $table->decimal('cost', 15, 2)->after('name');
        });

        // Копируем данные обратно из description в name
        DB::statement('UPDATE expense_entries SET name = description');
        // Копируем данные обратно из price в cost
        DB::statement('UPDATE expense_entries SET cost = price');

        Schema::table('expense_entries', function (Blueprint $table) {
            // Удаляем новые столбцы description и price
            $table->dropColumn('description');
            $table->dropColumn('price');
        });
    }
};
