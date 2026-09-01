<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // For MySQL, we need to change the enum to include 'cancelled'
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE loan_requests CHANGE status status ENUM('pending', 'approved', 'rejected', 'cancelled') DEFAULT 'pending'");
        } else {
            // For other databases, add a check constraint or similar approach
            Schema::table('loan_requests', function (Blueprint $table) {
                // Different approach for non-MySQL databases
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert enum back to original values
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE loan_requests CHANGE status status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending'");
        }
    }
};
