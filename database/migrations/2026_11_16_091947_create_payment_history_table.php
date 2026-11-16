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
        Schema::create('payment_history', function (Blueprint $table) {
            $table->id('payment_id');
            $table->unsignedBigInteger('member_id')->nullable();
            $table->unsignedBigInteger('transaction_id')->nullable();
            $table->unsignedBigInteger('supplier_id')->nullable();
            $table->float('amount');
            $table->unsignedBigInteger('payment_method_id');
            $table->timestamps();

            $table->foreign('member_id')->references('member_id')->on('members');
            $table->foreign('transaction_id')->references('transaction_id')->on('inventory_transactions');
            $table->foreign('supplier_id')->references('supplier_id')->on('suppliers');
            $table->foreign('payment_method_id')->references('payment_method_id')->on('payment_methods');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_history');
    }
};
