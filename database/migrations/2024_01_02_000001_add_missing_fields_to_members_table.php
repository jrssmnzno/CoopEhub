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
        Schema::table('members', function (Blueprint $table) {
            // Add missing personal information fields
            $table->string('relationship_status')->nullable()->after('date_of_birth');
            $table->string('employment_status')->nullable()->after('relationship_status');
            $table->string('member_type')->default('individual')->after('employment_status');
            $table->decimal('monthly_income', 12, 2)->nullable()->after('member_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('members', function (Blueprint $table) {
            $table->dropColumn([
                'relationship_status',
                'employment_status',
                'member_type',
                'monthly_income',
            ]);
        });
    }
};
