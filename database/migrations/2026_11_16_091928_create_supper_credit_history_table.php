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
        Schema::create('supplier_credit_history', function (Blueprint $table) {
            $table->id('supplier_credit_history_id');
            $table->unsignedBigInteger('supplier_id');
            $table->float('amount');
            $table->float('remaining_amount');
            $table->unsignedBigInteger('transaction_id');
            $table->timestamps();

            $table->foreign('supplier_id')->references('supplier_id')->on('suppliers');
            $table->foreign('transaction_id')->references('transaction_id')->on('inventory_transactions');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('supplier_credit_history');
    }
};
