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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('transaction_number')->unique(); // TXN-YYYY-XXX format
            $table->foreignId('member_id')->constrained('members')->onDelete('cascade');
            $table->foreignId('loan_id')->nullable()->constrained('loans')->onDelete('cascade');
            $table->foreignId('created_by')->constrained('users')->onDelete('restrict');
            
            // Transaction details
            $table->enum('type', ['capital_share', 'loan_release', 'interest_payment', 'principal_payment', 'penalty', 'refund']);
            $table->string('reference_number')->nullable();
            $table->text('description')->nullable();
            
            // Amount breakdown (all in transaction, for audit)
            $table->decimal('capital_share_amount', 12, 2)->default(0);
            $table->decimal('principal_amount', 12, 2)->default(0);
            $table->decimal('interest_amount', 12, 2)->default(0);
            $table->decimal('penalty_amount', 12, 2)->default(0);
            $table->decimal('total_amount', 12, 2);
            
            // Running balances after transaction
            $table->decimal('member_balance_after', 12, 2);
            $table->decimal('loan_balance_after', 12, 2)->nullable();
            
            // Tracking
            $table->timestamp('processed_at');
            $table->string('payment_method')->default('cash'); // cash, check, bank_transfer
            $table->string('ip_address')->nullable();
            
            $table->timestamps();

            $table->index('member_id');
            $table->index('type');
            $table->index('processed_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
