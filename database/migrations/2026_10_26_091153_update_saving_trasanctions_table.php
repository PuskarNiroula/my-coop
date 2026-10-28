<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


return new class extends Migration
{
    public function up(): void
    {
        Schema::table('savings_transactions', function (Blueprint $table) {
            $table->unsignedBigInteger('month_id');
            $table->foreign('month_id', 'savings_transactions_month_id_foreign')
                ->references('month_id')
                ->on('months');
            $table->float('amount_after_transaction')->after('amount');
        });
    }

    public function down(): void
    {
        Schema::table('savings_transactions', function (Blueprint $table) {
            $table->dropForeign('savings_transactions_month_id_foreign');
            $table->dropColumn('month_id');
            $table->dropColumn('amount_after_transaction');
        });
    }
};
