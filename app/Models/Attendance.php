<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attendance extends Model
{
    use HasFactory;

    protected $table = 'attendance';

    protected $fillable = [
        'meeting_id',
        'member_id',
        'time_in',
        'remarks',
        'status',
    ];

    protected $casts = [
        'time_in' => 'datetime',
    ];

    /**
     * Get the meeting for the attendance record
     */
    public function meeting(): BelongsTo
    {
        return $this->belongsTo(Meeting::class);
    }

    /**
     * Get the member for the attendance record
     */
    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

    /**
     * Mark member as present
     */
    public function markPresent(): void
    {
        $this->update([
            'status' => 'present',
            'time_in' => now(),
        ]);
    }

    /**
     * Mark member as absent
     */
    public function markAbsent(): void
    {
        $this->update([
            'status' => 'absent',
        ]);
    }

    /**
     * Mark member as excused
     */
    public function markExcused(?string $remarks = null): void
    {
        $this->update([
            'status' => 'excused',
            'remarks' => $remarks,
        ]);
    }
}
