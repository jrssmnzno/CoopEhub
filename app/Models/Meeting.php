<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Meeting extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'objective',
        'meeting_date',
        'location',
        'status',
        'attendance_opened_at',
        'attendance_closed_at',
    ];

    protected $casts = [
        'meeting_date' => 'datetime',
        'attendance_opened_at' => 'datetime',
        'attendance_closed_at' => 'datetime',
    ];

    /**
     * Get the attendances for the meeting
     */
    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }

    /**
     * Get present members count
     */
    public function getPresentCount(): int
    {
        return $this->attendances()->where('status', 'present')->count();
    }

    /**
     * Get absent members count
     */
    public function getAbsentCount(): int
    {
        return $this->attendances()->where('status', 'absent')->count();
    }

    /**
     * Get excused members count
     */
    public function getExcusedCount(): int
    {
        return $this->attendances()->where('status', 'excused')->count();
    }

    /**
     * Check if meeting is in draft status
     */
    public function isDraft(): bool
    {
        return $this->status === 'draft';
    }

    /**
     * Check if attendance is currently open
     */
    public function isAttendanceOpen(): bool
    {
        return $this->status === 'ongoing' && $this->attendance_opened_at !== null && $this->attendance_closed_at === null;
    }

    /**
     * Open attendance for the meeting
     */
    public function openAttendance(): void
    {
        $this->update([
            'status' => 'ongoing',
            'attendance_opened_at' => now(),
        ]);

        // Create attendance records for all active members marked as absent by default
        $activeMembers = Member::where('status', 'active')->get();
        foreach ($activeMembers as $member) {
            Attendance::firstOrCreate(
                [
                    'meeting_id' => $this->id,
                    'member_id' => $member->id,
                ],
                [
                    'status' => 'absent',
                ]
            );
        }
    }

    /**
     * Close attendance for the meeting
     */
    public function closeAttendance(): void
    {
        $this->update([
            'status' => 'completed',
            'attendance_closed_at' => now(),
        ]);
    }
}
