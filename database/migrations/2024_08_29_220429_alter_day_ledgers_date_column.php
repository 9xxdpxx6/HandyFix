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
        Schema::table('day_ledgers', function (Blueprint $table) {
            // Изменение типа столбца 'date' с datetime на date
            $table->date('date')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('day_ledgers', function (Blueprint $table) {
            // Возвращение типа столбца 'date' с date обратно на datetime
            $table->dateTime('date')->change();
        });
    }
};
