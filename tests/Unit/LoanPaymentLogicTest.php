<?php

namespace Tests\Unit;

use App\Models\Loan;
use App\Models\Member;
use PHPUnit\Framework\Attributes\Test;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoanPaymentLogicTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_carries_partial_interest_and_does_not_advance_an_unpaid_installment(): void
    {
        $loan = $this->makeLoan();

        $breakdown = $loan->processPayment(5.00, today());
        $installment = $loan->installments()->first();

        $this->assertSame(5.0, round((float) $breakdown['interest'], 2));
        $this->assertSame(0.0, round((float) $breakdown['principal'], 2));
        $this->assertSame(0.0, round((float) $breakdown['remaining'], 2));
        $this->assertSame('partial', $installment->fresh()->status);
        $this->assertSame(5.0, round((float) $installment->fresh()->interest_paid, 2));
        $this->assertSame(today()->toDateString(), $loan->fresh()->next_payment_date->toDateString());
        $this->assertSame(5.0, round((float) $loan->fresh()->interest_due, 2));
        $this->assertSame(0, (int) $loan->fresh()->payments_made);
    }

    #[Test]
    public function it_applies_excess_after_due_installments_to_principal_and_recasts_future_installments(): void
    {
        $loan = $this->makeLoan();
        $first = $loan->installments()->first();
        $amountToCompleteFirstAndPrepayPrincipal = (float) $first->principal_due
            + (float) $first->interest_due
            + 100;

        $breakdown = $loan->processPayment($amountToCompleteFirstAndPrepayPrincipal, today());

        $this->assertSame(100.0, round((float) $breakdown['extra_principal'], 2));
        $this->assertSame(1, $breakdown['installments_paid']);
        $this->assertSame('paid', $first->fresh()->status);
        $this->assertSame(1, (int) $loan->fresh()->payments_made);
        $this->assertSame(
            today()->addMonthNoOverflow()->toDateString(),
            $loan->fresh()->next_payment_date->toDateString()
        );
        $this->assertLessThan(
            (float) $first->principal_due,
            (float) $loan->installments()->orderBy('installment_number')->skip(1)->first()->principal_due
        );
    }

    #[Test]
    public function it_calculates_maximum_payment_as_principal_plus_interest_already_due(): void
    {
        $loan = $this->makeLoan(dueDate: today()->addDay());
        $first = $loan->installments()->first();

        $this->assertSame(1000.0, $loan->calculateMaximumPaymentAmount(today()));
        $this->assertSame(
            round(1000 + (float) $first->interest_due, 2),
            $loan->calculateMaximumPaymentAmount(today()->addDay())
        );
    }

    #[Test]
    public function it_marks_the_loan_overdue_when_an_installment_remains_unpaid_after_its_due_date(): void
    {
        $loan = $this->makeLoan(dueDate: today()->subDay());

        $this->assertTrue($loan->isOverdue());
    }

    #[Test]
    public function it_cancels_unearned_future_installments_when_the_loan_is_paid_off_early(): void
    {
        $loan = $this->makeLoan();

        $loan->processPayment($loan->calculateMaximumPaymentAmount(today()), today());

        $this->assertSame('fully_paid', $loan->fresh()->status);
        $this->assertSame(0.0, round((float) $loan->fresh()->interest_due, 2));
        $this->assertSame(
            'cancelled',
            $loan->installments()->orderBy('installment_number')->skip(1)->first()->status
        );
    }

    private function makeLoan(?\Carbon\Carbon $dueDate = null): Loan
    {
        $member = Member::create([
            'member_id' => 'MM-001',
            'first_name' => 'Test',
            'last_name' => 'Member',
            'email' => 'member-' . uniqid() . '@example.test',
            'phone' => '09170000000',
            'address' => 'Test address',
            'date_of_birth' => '1990-01-01',
            'joined_date' => today(),
            'status' => 'active',
        ]);
        $dueDate ??= today();
        $disbursementDate = $dueDate->copy()->subMonthNoOverflow();

        $loan = Loan::create([
            'loan_number' => 'LN-' . uniqid(),
            'loan_type' => 'cash',
            'member_id' => $member->id,
            'principal_amount' => 1000,
            'interest_rate' => 12,
            'term_months' => 2,
            'original_principal' => 1000,
            'running_balance' => 1000,
            'status' => 'active',
            'disbursement_date' => $disbursementDate,
            'maturity_date' => $disbursementDate->copy()->addMonths(2),
            'next_payment_date' => $dueDate,
            'payments_made' => 0,
            'monthly_payment' => Loan::calculateMonthlyPayment(1000, 12, 2),
        ]);
        $loan->createInstallmentSchedule();

        return $loan;
    }
}
