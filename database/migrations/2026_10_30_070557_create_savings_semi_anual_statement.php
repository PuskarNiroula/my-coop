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
        Schema::create('saving_statements', function (Blueprint $table) {
            $table->id('statement_id');
            $table->unsignedBigInteger('fiscal_year_id');
            $table->unsignedBigInteger('year_id');
            $table->unsignedBigInteger('month_id');
            $table->unsignedBigInteger('member_account_id');
            $table->unsignedBigInteger('interest_distribution_id')->nullable();
            $table->float('opening_balance');
            $table->float('closing_balance');
            $table->string('status');
            $table->timestamps();


            $table->foreign('fiscal_year_id')->references('fiscal_year_id')->on('fiscal_years');
            $table->foreign('year_id')->references('year_id')->on('years');
            $table->foreign('month_id')->references('month_id')->on('months');
            $table->foreign('member_account_id')->references('member_account_id')->on('member_accounts');
            $table->foreign('interest_distribution_id')->references('id')->on('interest_distributions');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('saving_statements');
    }
};
