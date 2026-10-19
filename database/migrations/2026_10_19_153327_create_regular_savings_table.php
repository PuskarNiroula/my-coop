<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('regular_savings', function (Blueprint $table) {
            $table->id('regular_saving_id');
            $table->foreignId('member_id')->constrained('members','member_id')->restrictOnDelete();
            $table->decimal('balance')->default(0);
            $table->enum('status', ['active', 'frozen', 'closed'])->default('active');
            $table->timestamps();
            $table->unique('member_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('regular_savings');
    }
};
