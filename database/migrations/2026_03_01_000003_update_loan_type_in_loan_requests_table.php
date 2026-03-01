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
        Schema::table('loan_requests', function (Blueprint $table) {
            // Change enum type to use new loan types
            $table->enum('loan_type', ['cash', 'swine', 'goat', 'fertilizers'])
                ->default('cash')
                ->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('loan_requests', function (Blueprint $table) {
            // Revert to old enum type
            $table->enum('loan_type', ['personal', 'business', 'emergency', 'education', 'medical', 'home', 'agricultural', 'other'])
                ->default('personal')
                ->change();
        });
    }
};
