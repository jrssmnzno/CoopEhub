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
        Schema::create('members', function (Blueprint $table) {
            $table->id();
            $table->string('member_id')->unique(); // Format: MM-XXX
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->unique();
            $table->string('phone');
            $table->string('address');
            $table->date('date_of_birth');
            $table->date('joined_date')->default(now());
            $table->enum('status', ['active', 'client', 'inactive', 'suspended'])->default('active');
            
            // Capital share tracking
            $table->decimal('capital_share', 12, 2)->default(0);
            $table->decimal('total_loans', 12, 2)->default(0);
            $table->decimal('outstanding_balance', 12, 2)->default(0);
            
            // Emergency contact
            $table->string('emergency_contact_name')->nullable();
            $table->string('emergency_contact_phone')->nullable();
            $table->string('emergency_contact_relation')->nullable();
            
            $table->timestamps();

            $table->index('member_id');
            $table->index('status');
            $table->index('email');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('members');
    }
};
