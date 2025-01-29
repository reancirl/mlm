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
        Schema::create('payslips', function (Blueprint $table) {
            $table->id();
            $table->decimal('basic_salary', 8, 2);
            $table->decimal('deductions', 8, 2)->nullable();
            $table->decimal('sss_contribution', 8, 2)->nullable();
            $table->decimal('pagibig_contribution', 8, 2)->nullable();
            $table->decimal('philhealth_contribution', 8, 2)->nullable();
            $table->decimal('overtime', 8, 2)->nullable();
            $table->decimal('net_pay', 8, 2);
            $table->date('period_start');
            $table->date('period_end');
            $table->timestamp('generated_at');
            $table->foreignId('employee_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payslips');
    }
};
