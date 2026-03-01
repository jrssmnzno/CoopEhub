<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'activity',
        'subject_type',
        'subject_id',
        'module',
        'old_values',
        'new_values',
        'changes_summary',
        'ip_address',
        'user_agent',
        'browser',
        'status',
        'notes',
        'created_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    /**
     * Get the user who performed the action
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Create audit log entry
     */
    public static function log(
        ?User $user,
        string $activity,
        string $module,
        string $subjectType,
        string $subjectId = null,
        string $status = 'success',
        array $oldValues = null,
        array $newValues = null,
        string $notes = null
    ): self {
        return self::create([
            'user_id' => $user?->id,
            'activity' => $activity,
            'module' => $module,
            'subject_type' => $subjectType,
            'subject_id' => $subjectId,
            'status' => $status,
            'old_values' => $oldValues ? json_encode($oldValues) : null,
            'new_values' => $newValues ? json_encode($newValues) : null,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'browser' => self::parseBrowser(request()->userAgent()),
            'notes' => $notes,
            'created_at' => now(),
        ]);
    }

    /**
     * Parse browser from user agent
     */
    private static function parseBrowser($userAgent): string
    {
        if (preg_match('/MSIE|Trident|Edge/i', $userAgent)) {
            return 'Internet Explorer / Edge';
        } elseif (preg_match('/Firefox/i', $userAgent)) {
            return 'Firefox';
        } elseif (preg_match('/Chrome/i', $userAgent)) {
            return 'Chrome';
        } elseif (preg_match('/Safari/i', $userAgent)) {
            return 'Safari';
        }
        return 'Unknown';
    }
}
