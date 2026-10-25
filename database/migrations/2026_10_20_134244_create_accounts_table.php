<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('member_accounts', function (Blueprint $table) {
            $table->id('member_account_id');

            $table->foreignId('member_id')
                ->constrained('members','member_id')
                ->restrictOnDelete();

            $table->foreignId('account_type_id')
                ->constrained('account_types','account_type_id')
                ->restrictOnDelete();

            $table->date('opened_at');
            $table->date('closed_at')->nullable();

            $table->string('status', 20)->default('ACTIVE');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('member_accounts');
    }
};
