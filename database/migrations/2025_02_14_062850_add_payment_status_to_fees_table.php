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
        Schema::table('fees', function (Blueprint $table) {
            // Drop the old status column if it exists
            if (Schema::hasColumn('fees', 'status')) {
                $table->dropColumn('status');
            }
            
            // Add new payment_status column
            $table->enum('payment_status', ['paid', 'unpaid', 'partial'])->default('unpaid')->after('paid_amount');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('fees', function (Blueprint $table) {
            $table->dropColumn('payment_status');
            // Restore the old status column
            $table->enum('status', ['paid', 'unpaid', 'partial'])->default('unpaid');
        });
    }
};
