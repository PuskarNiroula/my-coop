<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('savings_transactions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('account_id')
                ->constrained('member_accounts', 'member_account_id')
                ->restrictOnDelete();

            $table->string('transaction_type', 20);

            $table->decimal('amount', 15, 4);

            $table->date('transaction_date');
            $table->string('transaction_date_nepali');

            $table->string('description', 255)->nullable();

            $table->string('status', 20)->default('PENDING');

            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();

            $table->index(['account_id', 'transaction_date']);
            $table->index(['account_id', 'transaction_date_nepali']);
            $table->index(['status', 'transaction_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('savings_transactions');
    }
};
