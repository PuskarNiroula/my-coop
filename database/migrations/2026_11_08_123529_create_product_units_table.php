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
        Schema::create('product_units', function (Blueprint $table) {
            $table->id('product_unit_id');
            $table->foreignId('product_id')->constrained('products','product_id')->cascadeOnDelete();
            $table->foreignId('unit_id')->constrained('units','unit_id')->restrictOnDelete();
            $table->decimal('conversion_factor', 15, 6)->default(1);
            $table->boolean('is_base_unit')->default(false);
            $table->boolean('is_purchase_unit')->default(false);
            $table->boolean('is_sale_unit')->default(false);
            $table->boolean('status')->default(true);
            $table->timestamps();
            $table->unique(['product_id', 'unit_id']);
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_units');
    }
};
