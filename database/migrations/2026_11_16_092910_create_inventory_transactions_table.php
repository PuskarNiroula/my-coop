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
        Schema::create('inventory_transactions', function (Blueprint $table) {
            $table->id('transaction_id');
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('unit_id');
            $table->float('quantity');
            $table->float('rate');
            $table->float('amount_before_discount');
            $table->float('discount');
            $table->float('payable_amount');
            $table->float('paid_amount');
            $table->string('transaction_type');
            $table->string('transaction_date_nepali');
            $table->date('transaction_date_english');
            $table->unsignedBigInteger('supplier_id');
            $table->unsignedBigInteger('customer_id');
            $table->unsignedBigInteger('created_by');
            $table->timestamps();

            $table->foreign('product_id')->references('product_id')->on('products');
            $table->foreign('unit_id')->references('unit_id')->on('units');
            $table->foreign('customer_id')->references('customer_id')->on('customers');
            $table->foreign('created_by')->references('id')->on('users');
            $table->foreign('supplier_id')->references('supplier_id')->on('suppliers');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_transactions');
    }
};
