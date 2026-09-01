<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    use HasFactory;

    protected $fillable = [
        'member_id',
        'user_id',
        'first_name',
        'last_name',
        'email',
        'phone',
        'address',
        'date_of_birth',
        'joined_date',
        'status',
        'relationship_status',
        'employment_status',
        'member_type',
        'monthly_income',
        'capital_share',
        'total_loans',
        'outstanding_balance',
        'emergency_contact_name',
        'emergency_contact_phone',
        'emergency_contact_relation',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'joined_date' => 'date',
        'capital_share' => 'decimal:2',
        'total_loans' => 'decimal:2',
        'outstanding_balance' => 'decimal:2',
        'monthly_income' => 'decimal:2',
    ];

    /**
     * Get the user associated with this member
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get member's loans
     */
    public function loans()
    {
        return $this->hasMany(Loan::class);
    }

    /**
     * Get member's transactions
     */
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    /**
     * Get member's loan requests
     */
    public function loanRequests()
    {
        return $this->hasMany(LoanRequest::class);
    }

    /**
     * Get member's attendance records
     */
    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    /**
     * Get active loans
     */
    public function activeLoans()
    {
        return $this->loans()->whereIn('status', ['active', 'overdue']);
    }

    /**
     * Get full name
     */
    public function getFullNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }

    /**
     * Calculate total outstanding balance
     */
    public function calculateOutstandingBalance()
    {
        $balance = $this->loans()
            ->whereIn('status', ['active', 'overdue'])
            ->sum('running_balance');
        
        $this->outstanding_balance = $balance;
        $this->save();
        
        return $balance;
    }

    /**
     * Check if member can borrow
     */
    public function canBorrow(): bool
    {
        return $this->status === 'active' && !$this->user()->exists() || $this->user?->is_active;
    }
}
