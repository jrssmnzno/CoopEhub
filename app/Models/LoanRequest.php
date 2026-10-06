<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoanRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'member_id',
        'loan_type',
        'requested_amount',
        'requested_term_months',
        'interest_rate',
        'monthly_payment',
        'status',
        'admin_notes',
        'approved_by',
        'approved_at',
    ];

    protected $casts = [
        'approved_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the member that this loan request belongs to
     */
    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    /**
     * Get the admin who approved this request
     */
    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Calculate monthly payment using compound interest formula
     * M = P * [r(1+r)^n] / [(1+r)^n - 1]
     */
    public function calculateMonthlyPayment(): float
    {
        $principal = $this->requested_amount;
        $annualRate = $this->interest_rate / 100;
        $monthlyRate = $annualRate / 12;
        $months = $this->requested_term_months;

        if ($monthlyRate == 0) {
            return $principal / $months;
        }

        $numerator = $monthlyRate * pow(1 + $monthlyRate, $months);
        $denominator = pow(1 + $monthlyRate, $months) - 1;
        
        return round($principal * ($numerator / $denominator), 2);
    }

    /**
     * Calculate total interest over loan term
     */
    public function calculateTotalInterest(): float
    {
        $monthlyPayment = $this->monthly_payment ?? $this->calculateMonthlyPayment();
        $totalPaid = $monthlyPayment * $this->requested_term_months;
        return round($totalPaid - $this->requested_amount, 2);
    }

    /**
     * Approve the loan request and create a Loan
     */
    public function approve(User $approvedBy, ?string $notes = null): Loan
    {
        $this->update([
            'status' => 'approved',
            'approved_by' => $approvedBy->id,
            'approved_at' => now(),
            'admin_notes' => $notes,
        ]);

        // Create the actual loan
        $loan = Loan::create([
            'member_id' => $this->member_id,
            'loan_type' => $this->loan_type,
            'loan_number' => 'LN-' . now()->format('Y') . '-' . str_pad(Loan::count() + 1, 5, '0', STR_PAD_LEFT),
            'principal_amount' => $this->requested_amount,
            'interest_rate' => $this->interest_rate,
            'term_months' => $this->requested_term_months,
            'original_principal' => $this->requested_amount,
            'running_balance' => $this->requested_amount,
            'monthly_payment' => $this->monthly_payment,
            'disbursement_date' => now(),
            'maturity_date' => now()->addMonths($this->requested_term_months),
            'next_payment_date' => now()->addMonth(),
            'status' => 'active',
        ]);
        $loan->createInstallmentSchedule();

        // Log the approval
        AuditLog::log(
            $approvedBy,
            'update',
            'Loans',
            'LoanRequest',
            $this->id,
            'success',
            ['status' => 'pending'],
            ['status' => 'approved', 'loan_id' => $loan->id],
            'Loan request approved and loan created'
        );

        return $loan;
    }

    /**
     * Reject the loan request
     */
    public function reject(User $rejectedBy, string $notes = ''): void
    {
        $this->update([
            'status' => 'rejected',
            'approved_by' => $rejectedBy->id,
            'approved_at' => now(),
            'admin_notes' => $notes,
        ]);

        AuditLog::log(
            $rejectedBy,
            'update',
            'Loans',
            'LoanRequest',
            $this->id,
            'success',
            ['status' => 'pending'],
            ['status' => 'rejected'],
            'Loan request rejected. Reason: ' . $notes
        );
    }
}
