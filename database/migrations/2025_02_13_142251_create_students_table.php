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
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('admission_no')->unique();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('gender');
            $table->date('date_of_birth');
            $table->string('religion')->nullable();
            $table->string('blood_group')->nullable();
            $table->string('address');
            $table->string('phone');
            $table->string('email')->unique();
            $table->string('parent_name');
            $table->string('parent_phone');
            $table->string('parent_occupation')->nullable();
            $table->decimal('total_fee', 10, 2)->default(0);
            $table->foreignId('class_id')->constrained()->onDelete('cascade');
            $table->string('profile_photo')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
