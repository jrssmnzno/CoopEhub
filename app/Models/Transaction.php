<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_number',
        'member_id',
        'loan_id',
        'created_by',
        'type',
        'reference_number',
        'description',
        'capital_share_amount',
        'principal_amount',
        'interest_amount',
        'penalty_amount',
        'total_amount',
        'member_balance_after',
        'loan_balance_after',
        'processed_at',
        'payment_method',
        'ip_address',
    ];

    protected $casts = [
        'processed_at' => 'datetime',
        'capital_share_amount' => 'decimal:2',
        'principal_amount' => 'decimal:2',
        'interest_amount' => 'decimal:2',
        'penalty_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'member_balance_after' => 'decimal:2',
        'loan_balance_after' => 'decimal:2',
    ];

    /**
     * Get associated member
     */
    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    /**
     * Get associated loan
     */
    public function loan()
    {
        return $this->belongsTo(Loan::class);
    }

    /**
     * Get user who created this transaction
     */
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Generate transaction number
     */
    public static function generateTransactionNumber(): string
    {
        $year = now()->year;
        $countsThisYear = self::whereYear('created_at', $year)->count() + 1;
        return "TXN-{$year}-" . str_pad($countsThisYear, 5, '0', STR_PAD_LEFT);
    }
}
