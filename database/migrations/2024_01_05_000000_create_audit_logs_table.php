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
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
            
            // Activity details
            $table->enum('activity', ['login', 'logout', 'create', 'update', 'delete', 'view', 'print', 'export', 'failed_login']);
            $table->string('subject_type'); // 'Member', 'Loan', 'Transaction', etc.
            $table->string('subject_id')->nullable();
            $table->string('module'); // 'Members', 'Loans', 'Payments', 'Reports'
            
            // Change tracking
            $table->text('old_values')->nullable(); // JSON
            $table->text('new_values')->nullable(); // JSON
            $table->text('changes_summary')->nullable();
            
            // IP and device info
            $table->string('ip_address');
            $table->string('user_agent')->nullable();
            $table->string('browser')->nullable();
            
            // Status
            $table->enum('status', ['success', 'failed', 'warning'])->default('success');
            $table->text('notes')->nullable();
            
            $table->timestamp('created_at')->useCurrent();

            $table->index('user_id');
            $table->index('activity');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};
