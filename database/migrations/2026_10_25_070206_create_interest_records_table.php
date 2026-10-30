<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('interest_records', function (Blueprint $table) {
            $table->id();

            $table->foreignId('start_month_id')
                ->constrained('months', 'month_id')
                ->restrictOnDelete();

            $table->foreignId('end_month_id')
                ->constrained('months', 'month_id')
                ->restrictOnDelete();

            $table->foreignId('start_year_id')
                ->constrained('years', 'year_id')
                ->restrictOnDelete();

            $table->foreignId('end_year_id')
                ->constrained('years', 'year_id')
                ->restrictOnDelete();

            $table->foreignId('fiscal_year_id')
                ->constrained('fiscal_years', 'fiscal_year_id')
                ->restrictOnDelete();

            $table->float('total_interest');

            $table->string('status', 20)->default('active');

            $table->timestamps();

            $table->unique(
                ['start_month_id', 'end_month_id', 'start_year_id', 'end_year_id', 'fiscal_year_id'],
                'interest_distribution_period_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('interest_records');
    }
};
