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
        Schema::table('loan_requests', function (Blueprint $table) {
            $table->enum('loan_type', ['personal', 'business', 'emergency', 'education', 'medical', 'home', 'agricultural', 'other'])
                ->default('personal')
                ->after('requested_amount')
                ->comment('Loan category for better organization and tracking');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('loan_requests', function (Blueprint $table) {
            $table->dropColumn('loan_type');
        });
    }
};
