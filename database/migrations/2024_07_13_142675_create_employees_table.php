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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('qualification_id')->nullable()->constrained('qualifications')->onDelete('set null');
            $table->foreignId('specialization_id')->nullable()->constrained('specializations')->onDelete('set null');
            $table->decimal('fixed_salary', 8, 2)->nullable();
            $table->decimal('commission_rate', 5, 2)->nullable();
            $table->integer('seniority');
            $table->date('hire_date');
            $table->date('termination_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['qualification_id']);
            $table->dropForeign(['specialization_id']);
        });

        Schema::dropIfExists('employees');
    }
};
