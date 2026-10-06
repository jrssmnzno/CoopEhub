<?php

namespace App\Models;

use Carbon\Carbon;
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

    public function installments()
    {
        return $this->hasMany(LoanInstallment::class)->orderBy('installment_number');
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

    public function createInstallmentSchedule(): void
    {
        if ($this->installments()->exists() || $this->running_balance <= 0) {
            return;
        }

        $count = max(1, (int) $this->term_months - (int) $this->payments_made);
        $balance = round((float) $this->running_balance, 2);
        $rate = (float) $this->interest_rate / 100 / 12;
        $payment = (float) $this->monthly_payment;
        if ($payment <= 0) {
            $payment = self::calculateMonthlyPayment($balance, (float) $this->interest_rate, $count);
        }

        $firstDueDate = $this->next_payment_date
            ? Carbon::parse($this->next_payment_date)
            : Carbon::parse($this->disbursement_date)->addMonthNoOverflow();

        for ($number = 1; $number <= $count && $balance > 0; $number++) {
            $interest = round($balance * $rate, 2);
            $principal = $number === $count
                ? $balance
                : min($balance, max(0, round($payment - $interest, 2)));

            $this->installments()->create([
                'installment_number' => (int) $this->payments_made + $number,
                'due_date' => $firstDueDate->copy()->addMonthsNoOverflow($number - 1)->toDateString(),
                'principal_due' => $principal,
                'interest_due' => $interest,
                'principal_paid' => 0,
                'interest_paid' => 0,
                'interest_accrued' => false,
                'status' => 'pending',
            ]);

            $balance = round($balance - $principal, 2);
        }

        $this->interest_due = $this->calculateInterestOutstanding(today());
        $this->save();
    }

    public function calculateInterestOutstanding(Carbon|string|null $asOf = null): float
    {
        $date = $asOf instanceof Carbon ? $asOf : Carbon::parse($asOf ?? today());

        return round((float) $this->installments()
            ->whereIn('status', ['pending', 'partial'])
            ->whereDate('due_date', '<=', $date->toDateString())
            ->get()
            ->sum(fn (LoanInstallment $installment) => max(
                0,
                (float) $installment->interest_due - (float) $installment->interest_paid
            )), 2);
    }

    public function calculateMaximumPaymentAmount(Carbon|string|null $paymentDate = null): float
    {
        if ($this->running_balance <= 0) {
            return 0;
        }

        $date = $paymentDate instanceof Carbon ? $paymentDate : Carbon::parse($paymentDate ?? now());
        $interestDue = $this->installments()
            ->whereIn('status', ['pending', 'partial'])
            ->whereDate('due_date', '<=', $date->toDateString())
            ->get()
            ->sum(function (LoanInstallment $installment): float {
                $interestDue = $installment->interest_accrued
                    ? (float) $installment->interest_due
                    : round((float) $this->running_balance * (float) $this->interest_rate / 100 / 12, 2);

                return max(0, round($interestDue - (float) $installment->interest_paid, 2));
            });

        return round((float) $this->running_balance + $interestDue, 2);
    }

    /**
     * Advance the scheduled payment date using the payment date provided for the transaction.
     */
    public function getNextPaymentDateFor(Carbon|string|null $baseDate = null): Carbon
    {
        $date = $baseDate instanceof Carbon ? $baseDate : Carbon::parse($baseDate ?? Carbon::now());

        return $date->copy()->addMonth();
    }

    /**
     * Process payment with interest-first logic
     * Returns array with breakdown: [principal, interest, remaining]
     */
    public function processPayment(float $paymentAmount, Carbon|string|null $paymentDate = null): array
    {
        if ($paymentAmount <= 0) {
            throw new \InvalidArgumentException('Payment amount must be greater than zero.');
        }

        $this->createInstallmentSchedule();

        $date = $paymentDate instanceof Carbon ? $paymentDate : Carbon::parse($paymentDate ?? now());
        $remaining = round($paymentAmount, 2);
        $interestPayment = 0.0;
        $scheduledPrincipalPayment = 0.0;
        $installmentsPaid = 0;
        $allocations = [];

        $dueInstallments = $this->installments()
            ->whereIn('status', ['pending', 'partial'])
            ->whereDate('due_date', '<=', $date->toDateString())
            ->orderBy('due_date')
            ->orderBy('installment_number')
            ->get();

        foreach ($dueInstallments as $installment) {
            if (!$installment->interest_accrued) {
                $installment->interest_due = round(
                    (float) $this->running_balance * (float) $this->interest_rate / 100 / 12,
                    2
                );
                $installment->interest_accrued = true;
                $installment->save();
            }
        }

        foreach ($dueInstallments as $installment) {
            if ($remaining <= 0) {
                break;
            }

            $interestOutstanding = max(0, round(
                (float) $installment->interest_due - (float) $installment->interest_paid,
                2
            ));
            $interestApplied = min($remaining, $interestOutstanding);
            $installment->interest_paid = round((float) $installment->interest_paid + $interestApplied, 2);
            $remaining = round($remaining - $interestApplied, 2);
            $interestPayment = round($interestPayment + $interestApplied, 2);

            $principalOutstanding = max(0, round(
                (float) $installment->principal_due - (float) $installment->principal_paid,
                2
            ));
            $principalApplied = min($remaining, $principalOutstanding);
            $installment->principal_paid = round((float) $installment->principal_paid + $principalApplied, 2);
            $remaining = round($remaining - $principalApplied, 2);
            $scheduledPrincipalPayment = round($scheduledPrincipalPayment + $principalApplied, 2);

            $isPaid = (float) $installment->interest_paid >= (float) $installment->interest_due - 0.001
                && (float) $installment->principal_paid >= (float) $installment->principal_due - 0.001;
            $installment->status = $isPaid ? 'paid' : 'partial';
            $installment->paid_at = $isPaid ? $date->copy()->setTimeFromTimeString(now()->format('H:i:s')) : null;
            $installment->save();

            if ($interestApplied > 0 || $principalApplied > 0) {
                $allocations[] = [
                    'installment_id' => $installment->id,
                    'interest_amount' => $interestApplied,
                    'principal_amount' => $principalApplied,
                ];
            }

            if ($isPaid) {
                $installmentsPaid++;
            }
        }

        $extraPrincipalPayment = min($remaining, (float) $this->running_balance);
        $remaining = round($remaining - $extraPrincipalPayment, 2);
        $principalPayment = round($scheduledPrincipalPayment + $extraPrincipalPayment, 2);
        $newBalance = max(0, round((float) $this->running_balance - $principalPayment, 2));

        $this->running_balance = $newBalance;
        $this->principal_paid = round((float) $this->principal_paid + $principalPayment, 2);
        $this->interest_paid = round((float) $this->interest_paid + $interestPayment, 2);
        $this->payments_made = (int) $this->payments_made + $installmentsPaid;

        if ($newBalance > 0) {
            $this->recalculateFutureInstallments($date);
            $nextInstallment = $this->installments()->whereIn('status', ['pending', 'partial'])->orderBy('due_date')->first();
            $this->next_payment_date = $nextInstallment?->due_date;
        } else {
            $this->installments()
                ->whereIn('status', ['pending', 'partial'])
                ->whereDate('due_date', '>', $date->toDateString())
                ->update(['status' => 'cancelled']);
            $this->next_payment_date = null;
        }

        $this->interest_due = $newBalance > 0
            ? $this->calculateInterestOutstanding(today())
            : 0;
        $this->updateStatus();

        return [
            'principal' => $principalPayment,
            'interest' => $interestPayment,
            'remaining' => $remaining,
            'extra_principal' => $extraPrincipalPayment,
            'installments_paid' => $installmentsPaid,
            'interest_due_remaining' => round((float) $this->interest_due, 2),
            'next_payment_date' => $this->next_payment_date?->toDateString(),
            'allocations' => $allocations,
        ];
    }

    private function recalculateFutureInstallments(Carbon $asOf): void
    {
        $futureInstallments = $this->installments()
            ->where('status', 'pending')
            ->whereDate('due_date', '>', $asOf->toDateString())
            ->orderBy('due_date')
            ->get();

        $count = $futureInstallments->count();
        if ($count === 0) {
            return;
        }

        $unpaidDuePrincipal = (float) $this->installments()
            ->whereIn('status', ['pending', 'partial'])
            ->whereDate('due_date', '<=', $asOf->toDateString())
            ->get()
            ->sum(fn (LoanInstallment $installment) => max(
                0,
                (float) $installment->principal_due - (float) $installment->principal_paid
            ));
        $balance = max(0, round((float) $this->running_balance - $unpaidDuePrincipal, 2));
        if ($balance <= 0) {
            foreach ($futureInstallments as $installment) {
                $installment->update(['status' => 'cancelled']);
            }
            return;
        }

        $rate = (float) $this->interest_rate / 100 / 12;
        $payment = self::calculateMonthlyPayment($balance, (float) $this->interest_rate, $count);
        $this->monthly_payment = round($payment, 2);

        foreach ($futureInstallments as $index => $installment) {
            $interest = round($balance * $rate, 2);
            $principal = $index === $count - 1
                ? $balance
                : min($balance, max(0, round($payment - $interest, 2)));

            $installment->update([
                'principal_due' => $principal,
                'interest_due' => $interest,
            ]);
            $balance = round($balance - $principal, 2);
        }
    }

    /**
     * Top-up loan with new principal
     */
    public function topUp(float $additionalPrincipal): void
    {
        $this->original_principal += $additionalPrincipal;
        $this->running_balance += $additionalPrincipal;
        
        // Recalculate maturity and monthly payment
        $this->save();
        $this->recalculateFutureInstallments(Carbon::today());
        $this->save();
    }

    /**
     * Check if loan is overdue
     */
    public function isOverdue(): bool
    {
        if ($this->running_balance <= 0) {
            return false;
        }

        if ($this->installments()->exists()) {
            return $this->installments()
                ->whereIn('status', ['pending', 'partial'])
                ->whereDate('due_date', '<', today()->toDateString())
                ->exists();
        }

        return $this->next_payment_date !== null
            && Carbon::parse($this->next_payment_date)->lt(today());
    }

    /**
     * Update loan status based on balance and due date.
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
