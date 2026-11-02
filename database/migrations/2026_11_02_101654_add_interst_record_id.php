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
        Schema::table('savings_transactions', function (Blueprint $table) {
            $table->UnsignedBigInteger('interest_record_id')->after('fiscal_year_id')->nullable()->default(null);

            $table->foreign('interest_record_id')->references('interest_record_id')->on('interest_records')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('savings_transactions', function (Blueprint $table) {
            $table->dropForeign('savings_transactions_interest_record_id_foreign');
            $table->dropColumn('interest_record_id');
        });
    }
};
