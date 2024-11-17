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
        Schema::create('salaries', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('employee_id');
            $table->unsignedBigInteger('basic_salary');
            $table->unsignedBigInteger('allowances');
            $table->unsignedBigInteger('deductions');
            $table->unsignedBigInteger('net_salary');
            $table->timestamp('payment_date');
            $table->enum('payment_status',['paid','pending']);
            $table->timestamps();
            $table->softDeletes();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('salaries');
    }
};
