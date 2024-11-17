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
        Schema::table('salaries', function (Blueprint $table) {
            $table->date('payment_date')->nullable()->change();
            $table->unsignedInteger('deductions')->default(0)->change();
            $table->unsignedBigInteger('allowances')->default(0)->change();
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('salaries', function (Blueprint $table) {
            $table->timestamp('payment_date')->change();
            $table->unsignedBigInteger('deductions')->change();
            $table->unsignedBigInteger('allowances')->change();
        });
    }
};
