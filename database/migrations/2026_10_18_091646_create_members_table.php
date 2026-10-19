
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('members', function (Blueprint $table) {
            $table->bigIncrements('member_id');
            $table->string('name');
            $table->string('phone');
            $table->string('father_name')->nullable();
            $table->string('mother_name')->nullable();
            $table->date('birth_date_eng')->nullable();
            $table->string('gender');
            $table->text('address');
            $table->string('image')->nullable();
            $table->string('citizenship_number');
            $table->string('citizenship_front')->nullable();
            $table->string('citizenship_back')->nullable();
            $table->string('legal_aid_relation');
            $table->string('legal_aid_name');
            $table->string('legal_aid_citizenship_number');
            $table->string('birth_date_nepali')->nullable();
            $table->string('profile_image')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('members');
    }
};
