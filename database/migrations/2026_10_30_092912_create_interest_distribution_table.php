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
        Schema::create('interest_distribution', function (Blueprint $table) {
            $table->id('interest_distribution_id');
            $table->unsignedBigInteger('account_id');
            $table->unsignedBigInteger('interest_record_id');
            $table->float('interest_rate');
            $table->float('opening_balance');
            $table->float('interest_amount');
            $table->float('closing_balance');
            $table->string('status', 20)->default('active');
            $table->timestamps();


            $table->foreign('account_id')->references('member_account_id')->on('member_accounts');
            $table->foreign('interest_record_id')->references('interest_record_id')->on('interest_records');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('interest_distribution');
    }
};
