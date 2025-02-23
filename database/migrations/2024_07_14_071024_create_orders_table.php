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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->index()->constrained('customers')->onDelete('cascade');
            $table->foreignId('vehicle_id')->index()->constrained('vehicles')->onDelete('cascade');
            $table->foreignId('manager_id')->nullable()->constrained('employees')->onDelete('set null');
            $table->decimal('total', 10, 2)->nullable();
            $table->text('comment')->nullable();
            $table->text('note')->nullable();
            $table->foreignId('status_id')->index()->constrained('statuses')->onDelete('cascade');
            $table->dateTime('completed_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['customer_id']);
            $table->dropForeign(['vehicle_id']);
            $table->dropForeign(['manager_id']);
            $table->dropForeign(['status_id']);
        });

        Schema::dropIfExists('orders');
    }
};
