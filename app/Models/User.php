<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'is_active',
        'failed_attempts',
        'locked_until',
        'ip_address',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'locked_until' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Check if user is admin
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Check if user is member
     */
    public function isMember(): bool
    {
        return $this->role === 'member';
    }

    /**
     * Check if account is locked
     */
    public function isLocked(): bool
    {
        return $this->locked_until && now()->lessThan($this->locked_until);
    }

    /**
     * Increment failed attempts
     */
    public function incrementFailedAttempts(): void
    {
        $this->increment('failed_attempts');
        
        if ($this->failed_attempts >= 3) {
            $this->update([
                'locked_until' => now()->addMinutes(3),
            ]);
        }
    }

    /**
     * Reset failed attempts
     */
    public function resetFailedAttempts(): void
    {
        $this->update([
            'failed_attempts' => 0,
            'locked_until' => null,
        ]);
    }

    /**
     * Relationship to member
     */
    public function member()
    {
        return $this->hasOne(Member::class);
    }

    /**
     * Relationship to audit logs
     */
    public function auditLogs()
    {
        return $this->hasMany(AuditLog::class);
    }

    /**
     * Relationship to transactions
     */
    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'created_by');
    }
}
