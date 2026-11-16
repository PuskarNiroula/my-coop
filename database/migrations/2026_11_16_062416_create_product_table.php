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

        Schema::create('products', function (Blueprint $table) {
            $table->id('product_id');
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->UnsignedBigInteger('product_type');
            $table->UnsignedBigInteger('base_unit_id');
            $table->string('status')->default('active');

            $table->foreign('product_type')->references('unit_category_id')->on('unit_categories');
            $table->foreign('base_unit_id')->references('unit_id')->on('units');


        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
