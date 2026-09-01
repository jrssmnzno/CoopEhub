<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Loan;
use App\Models\Transaction;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    /**
     * Process a payment with interest-first logic
     */
    public function processPayment(Request $request, Loan $loan)
    {
        $validated = $request->validate([
            'amount' => ['required', 'numeric', 'min:0.01'],
            'payment_date' => ['required', 'date', 'date_format:Y-m-d', 'before_or_equal:today'],
            'payment_method' => ['nullable', 'in:cash,check,bank_transfer'],
            'reference_number' => ['nullable', 'string'],
            'notes' => ['nullable', 'string'],
        ]);

        try {
            DB::beginTransaction();

            $paymentAmount = $validated['amount'];
            $member = $loan->member;

            // Process payment with interest-first logic
            $breakdown = $loan->processPayment($paymentAmount);

            // Update loan record
            $loan->increment('interest_paid', $breakdown['interest']);
            $loan->increment('principal_paid', $breakdown['principal']);
            $loan->decrement('running_balance', $breakdown['principal']);
            $loan->increment('payments_made');

            // Update next payment date
            if ($loan->running_balance > 0) {
                $loan->update([
                    'next_payment_date' => now()->addMonth(),
                ]);
            } else {
                // Clear next payment date for fully paid loans
                $loan->update([
                    'next_payment_date' => null,
                ]);
            }

            // Update loan status based on current running balance
            $loan->updateStatus();
            $loan->refresh();

            // Update member balance
            $member->calculateOutstandingBalance();

            // Create transaction record for audit
            $transaction = Transaction::create([
                'transaction_number' => Transaction::generateTransactionNumber(),
                'member_id' => $member->id,
                'loan_id' => $loan->id,
                'created_by' => Auth::id(),
                'type' => 'principal_payment',
                'reference_number' => $validated['reference_number'] ?? null,
                'description' => 'Payment processed for ' . $loan->loan_number,
                'principal_amount' => $breakdown['principal'],
                'interest_amount' => $breakdown['interest'],
                'penalty_amount' => 0,
                'total_amount' => $paymentAmount,
                'member_balance_after' => $member->outstanding_balance,
                'loan_balance_after' => $loan->running_balance,
                'processed_at' => now()->setTimeFromTimeString($validated['payment_date'] . ' ' . now()->format('H:i:s')),
                'payment_method' => $validated['payment_method'] ?? 'cash',
                'ip_address' => request()->ip(),
            ]);

            // Log the transaction
            AuditLog::log(
                Auth::user(),
                'create',
                'Payments',
                'Transaction',
                $transaction->id,
                'success',
                null,
                [
                    'loan_id' => $loan->id,
                    'principal' => $breakdown['principal'],
                    'interest' => $breakdown['interest'],
                    'total' => $paymentAmount,
                ],
                'Payment processed: Principal ₱' . number_format($breakdown['principal'], 2) . 
                ', Interest ₱' . number_format($breakdown['interest'], 2)
            );

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Payment processed successfully',
                'transaction' => [
                    'number' => $transaction->transaction_number,
                    'principal' => $breakdown['principal'],
                    'interest' => $breakdown['interest'],
                    'remaining' => $breakdown['remaining'],
                    'loan_balance' => $loan->running_balance,
                ],
                'loan' => [
                    'id' => $loan->id,
                    'status' => $loan->status,
                    'running_balance' => $loan->running_balance,
                    'next_payment_date' => $loan->next_payment_date,
                ],
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Failed to process payment: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Top-up an existing loan
     */
    public function topUpLoan(Request $request, Loan $loan)
    {
        $validated = $request->validate([
            'amount' => ['required', 'numeric', 'min:0.01'],
            'notes' => ['nullable', 'string'],
        ]);

        try {
            DB::beginTransaction();

            $additionalAmount = $validated['amount'];
            $member = $loan->member;

            $oldBalance = $loan->running_balance;

            // Top-up the loan
            $loan->topUp($additionalAmount);

            // Create transaction record
            $transaction = Transaction::create([
                'transaction_number' => Transaction::generateTransactionNumber(),
                'member_id' => $member->id,
                'loan_id' => $loan->id,
                'created_by' => Auth::id(),
                'type' => 'loan_release',
                'description' => 'Loan top-up for ' . $loan->loan_number,
                'principal_amount' => $additionalAmount,
                'total_amount' => $additionalAmount,
                'member_balance_after' => $member->outstanding_balance,
                'loan_balance_after' => $loan->running_balance,
                'processed_at' => now(),
                'ip_address' => request()->ip(),
            ]);

            // Update member total loans
            $member->increment('total_loans', $additionalAmount);
            $member->calculateOutstandingBalance();

            // Log the transaction
            AuditLog::log(
                Auth::user(),
                'update',
                'Loans',
                'Loan',
                $loan->id,
                'success',
                ['running_balance' => $oldBalance],
                ['running_balance' => $loan->running_balance],
                'Loan top-up: ₱' . number_format($additionalAmount, 2)
            );

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Loan top-up processed successfully',
                'loan' => [
                    'number' => $loan->loan_number,
                    'new_balance' => $loan->running_balance,
                    'monthly_payment' => $loan->monthly_payment,
                ],
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Failed to top-up loan: ' . $e->getMessage(),
            ], 500);
        }
    }
}
