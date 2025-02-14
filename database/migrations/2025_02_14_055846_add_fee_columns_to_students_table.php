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
        Schema::table('students', function (Blueprint $table) {
            $table->decimal('admission_fee', 10, 2)->default(0.00)->after('status');
            $table->decimal('tuition_fee', 10, 2)->default(0.00)->after('admission_fee');
            // Modify total_fee to be calculated from admission_fee + tuition_fee
            $table->decimal('total_fee', 10, 2)->default(0.00)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn(['admission_fee', 'tuition_fee']);
            // Revert total_fee to original state
            $table->decimal('total_fee', 10, 2)->default(0.00)->change();
        });
    }
};
