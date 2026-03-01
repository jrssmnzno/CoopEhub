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
        Schema::create('attendance', function (Blueprint $table) {
            $table->id();
            $table->foreignId('meeting_id')->constrained('meetings')->onDelete('cascade');
            $table->foreignId('member_id')->constrained('members')->onDelete('cascade');
            $table->timestamp('time_in')->nullable();
            $table->text('remarks')->nullable();
            $table->enum('status', ['present', 'absent', 'excused'])->default('absent');
            $table->timestamps();
            
            // Ensure unique attendance per member per meeting
            $table->unique(['meeting_id', 'member_id']);
            
            $table->index('status');
            $table->index('meeting_id');
            $table->index('member_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendance');
    }
};
