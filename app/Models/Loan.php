<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    use HasFactory;

    protected $fillable = [
        'loan_number',
        'loan_type',
        'member_id',
        'principal_amount',
        'interest_rate',
        'term_months',
        'original_principal',
        'running_balance',
        'principal_paid',
        'interest_paid',
        'interest_due',
        'status',
        'disbursement_date',
        'maturity_date',
        'next_payment_date',
        'payments_made',
        'monthly_payment',
    ];

    protected $casts = [
        'disbursement_date' => 'date',
        'maturity_date' => 'date',
        'next_payment_date' => 'date',
        'principal_amount' => 'decimal:2',
        'interest_rate' => 'decimal:2',
        'original_principal' => 'decimal:2',
        'running_balance' => 'decimal:2',
        'principal_paid' => 'decimal:2',
        'interest_paid' => 'decimal:2',
        'interest_due' => 'decimal:2',
        'monthly_payment' => 'decimal:2',
    ];

    /**
     * Get member
     */
    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    /**
     * Get transactions
     */
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    /**
     * Calculate monthly payment
     * Formula: M = P * [r(1+r)^n] / [(1+r)^n - 1]
     */
    public static function calculateMonthlyPayment(
        float $principal,
        float $interestRate,
        int $termMonths
    ): float {
        if ($termMonths === 0) return 0;
        
        $monthlyRate = $interestRate / 100 / 12;
        
        if ($monthlyRate === 0) {
            return $principal / $termMonths;
        }
        
        $numerator = $principal * $monthlyRate * pow(1 + $monthlyRate, $termMonths);
        $denominator = pow(1 + $monthlyRate, $termMonths) - 1;
        
        return $numerator / $denominator;
    }

    /**
     * Calculate interest due for next payment
     */
    public function calculateInterestDue(): float
    {
        if ($this->running_balance <= 0) {
            return 0;
        }

        $monthlyRate = $this->interest_rate / 100 / 12;
        return $this->running_balance * $monthlyRate;
    }

    /**
     * Process payment with interest-first logic
     * Returns array with breakdown: [principal, interest, remaining]
     */
    public function processPayment(float $paymentAmount): array
    {
        if ($this->running_balance <= 0) {
            return ['principal' => 0, 'interest' => 0, 'remaining' => $paymentAmount];
        }

        $interestDue = $this->calculateInterestDue();
        $interestPayment = 0;
        $principalPayment = 0;
        $remaining = 0;

        // Apply to interest first
        if ($paymentAmount >= $interestDue) {
            $interestPayment = $interestDue;
            $remaining = $paymentAmount - $interestDue;

            // Apply remaining to principal
            if ($remaining > 0) {
                $principalPayment = min($remaining, $this->running_balance);
                $remaining = $remaining - $principalPayment;
            }
        } else {
            // Partial interest payment
            $interestPayment = $paymentAmount;
        }

        return [
            'principal' => $principalPayment,
            'interest' => $interestPayment,
            'remaining' => $remaining,
        ];
    }

    /**
     * Top-up loan with new principal
     */
    public function topUp(float $additionalPrincipal): void
    {
        $this->original_principal += $additionalPrincipal;
        $this->running_balance += $additionalPrincipal;
        
        // Recalculate maturity and monthly payment
        $remainingTerm = $this->term_months - $this->payments_made;
        $this->monthly_payment = self::calculateMonthlyPayment(
            $this->running_balance,
            $this->interest_rate,
            $remainingTerm
        );
        
        $this->save();
    }

    /**
     * Check if loan is overdue
     */
    public function isOverdue(): bool
    {
        return $this->status === 'overdue' || 
               ($this->status === 'active' && $this->next_payment_date < now()->toDateString());
    }

    /**
     * Update loan status based on balance
     */
    public function updateStatus(): void
    {
        if ($this->running_balance <= 0) {
            $this->status = 'fully_paid';
        } elseif ($this->isOverdue()) {
            $this->status = 'overdue';
        } else {
            $this->status = 'active';
        }
        $this->save();
    }
}
