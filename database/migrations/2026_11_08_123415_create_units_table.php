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

        Schema::create('units', function (Blueprint $table) {
            $table->id('unit_id');
            $table->string('name');
            $table->string('symbol', 20)->unique();
            $table->string('unit_type', 50);
            $table->string('status')->default("active");
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('units');
    }
};
