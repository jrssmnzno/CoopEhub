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
        Schema::create('loans', function (Blueprint $table) {
            $table->id();
            $table->string('loan_number')->unique(); // LN-YYYY-XXX format
            $table->foreignId('member_id')->constrained('members')->onDelete('cascade');
            
            // Loan details
            $table->decimal('principal_amount', 12, 2);
            $table->decimal('interest_rate', 5, 2); // Percentage per annum
            $table->integer('term_months'); // Loan term in months
            
            // Running balances
            $table->decimal('original_principal', 12, 2);
            $table->decimal('running_balance', 12, 2); // Includes consolidated top-ups
            $table->decimal('principal_paid', 12, 2)->default(0);
            $table->decimal('interest_paid', 12, 2)->default(0);
            $table->decimal('interest_due', 12, 2)->default(0);
            
            // Loan status
            $table->enum('status', ['active', 'fully_paid', 'overdue', 'suspended'])->default('active');
            $table->date('disbursement_date');
            $table->date('maturity_date');
            $table->date('next_payment_date')->nullable();
            
            // Payment tracking
            $table->integer('payments_made')->default(0);
            $table->decimal('monthly_payment', 12, 2);
            
            $table->timestamps();

            $table->index('member_id');
            $table->index('status');
            $table->index('loan_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loans');
    }
};
