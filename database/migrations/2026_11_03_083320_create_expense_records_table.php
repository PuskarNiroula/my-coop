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
        Schema::create('expense_records', function (Blueprint $table) {
            $table->id('expense_record_id');
            $table->unsignedBigInteger('expense_head_id');
            $table->float('amount');
            $table->text('description');
            $table->unsignedBigInteger('fiscal_year_id');
            $table->unsignedBigInteger('year_id');
            $table->unsignedBigInteger('month_id');
            $table->unsignedBigInteger('day_book_id');
            $table->unsignedBigInteger('created_by');
            $table->timestamps();


            $table->foreign('expense_head_id')->references('expense_head_id')->on('expense_heads');
            $table->foreign('month_id')->references('month_id')->on('months');
            $table->foreign('day_book_id')->references('day_book_id')->on('day_books');
            $table->foreign('year_id')->references('year_id')->on('years');
            $table->foreign('fiscal_year_id')->references('fiscal_year_id')->on('fiscal_years');
            $table->foreign('created_by')->references('id')->on('users');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expense_records');
    }
};
