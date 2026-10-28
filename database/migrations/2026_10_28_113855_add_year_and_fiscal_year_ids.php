<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('savings_transactions', function (Blueprint $table) {
            $table->unsignedBigInteger('year_id')->after('month_id');
            $table->unsignedBigInteger('fiscal_year_id')->after('year_id');

            $table->foreign('year_id','fx_year_id')
                ->references('year_id')
                ->on('years')
                ->onDelete('restrict');

            $table->foreign('fiscal_year_id','fx_fiscal_year_id')
                ->references('fiscal_year_id')
                ->on('fiscal_years')
                ->onDelete('restrict');
        });
    }

    public function down(): void
    {
        Schema::table('savings_transactions', function (Blueprint $table) {
            $table->dropForeign(['year_id']);
            $table->dropForeign(['fiscal_year_id']);
        });
    }
};
