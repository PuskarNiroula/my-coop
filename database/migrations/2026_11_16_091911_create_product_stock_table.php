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
        Schema::create('product_stock', function (Blueprint $table) {
            $table->id('stock_id');
            $table->unsignedBigInteger('product_id');
            $table->float('selling_price');
            $table->integer('actual_quantity');
            $table->integer('remaining_quantity');
            $table->unsignedBigInteger('transaction_id');
            $table->string('status')->default('active');
            $table->string('purchase_date_nepali');
            $table->date('purchase_date');
            $table->timestamps();

            $table->foreign('product_id')->references('product_id')->on('products');
            $table->foreign('transaction_id')->references('transaction_id')->on('inventory_transactions');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_stock');
    }
};
