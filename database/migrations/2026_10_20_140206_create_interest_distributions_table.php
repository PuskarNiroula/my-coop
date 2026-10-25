<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('interest_distributions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('account_type_id')
                ->constrained('account_types','account_type_id')
                ->restrictOnDelete();

            $table->date('period_start');
            $table->date('period_start_nepali');
            $table->date('period_end');
            $table->date('period_end_nepali');

            $table->decimal('interest_rate', 5, 2);

            $table->string('status', 20)->default('PENDING');

            $table->timestamp('processed_at')->nullable();

            $table->timestamps();

            $table->unique(
                ['account_type_id', 'period_start', 'period_end'],
                'interest_distribution_period_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('interest_distributions');
    }
};
