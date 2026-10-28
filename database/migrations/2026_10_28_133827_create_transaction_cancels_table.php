<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transaction_cancels', function (Blueprint $table) {
            $table->id('transaction_cancel_id');
            $table->unsignedBigInteger('saving_transaction_id')->unique();
            $table->text('reason');
            $table->date('cancellation_date');
            $table->string('cancellation_date_nepali');
            $table->unsignedBigInteger('cancelled_by');
            $table->timestamps();

            $table->foreign('saving_transaction_id', 'fx_transaction_cancel')
                ->references('id')
                ->on('savings_transactions')
                ->restrictOnDelete();

            $table->foreign('cancelled_by')
                ->references('id')
                ->on('users')
                ->restrictOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transaction_cancels');
    }
};
