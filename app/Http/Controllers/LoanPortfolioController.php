<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Loan;
use Illuminate\View\View;
use Illuminate\Http\Response;
use Illuminate\Http\Request;

class LoanPortfolioController extends Controller
{
    /**
     * Display the loan portfolio.
     */
    public function index(): View
    {
        $members = Member::where('status', 'active')
            ->with('loans')
            ->get();

        $member = null;
        $summary = null;
        $ledger = collect();
        $loans = collect();

        return view('loan-portfolio.index', compact('members', 'member', 'summary', 'ledger', 'loans'));
    }

    /**
     * Display member's loan portfolio.
     */
    public function showMember($memberId): View
    {
        $member = Member::findOrFail($memberId);

        $summary = (object)[
            'status' => $member->status,
            'principalBalance' => $member->calculateOutstandingBalance(),
            'interestAccrued' => 0, // Calculate from loans
        ];

        $ledger = $member->transactions()
            ->latest()
            ->limit(20)
            ->get()
            ->map(function ($trans) {
                return (object)[
                    'date' => $trans->created_at->format('Y-m-d'),
                    'description' => str_replace('_', ' ', ucfirst($trans->type)),
                    'principal' => $trans->principal_amount ?? 0,
                    'interest' => $trans->interest_amount ?? 0,
                    'balance' => $trans->loan_balance_after ?? 0,
                    'reference' => $trans->reference_number ?? 'N/A',
                ];
            });

        $loans = $member->loans()
            ->where('status', 'active')
            ->get()
            ->map(function ($loan) {
                $monthlyPayment = Loan::calculateMonthlyPayment(
                    $loan->principal_amount,
                    $loan->interest_rate,
                    $loan->term_months
                );
                
                return (object)[
                    'id' => $loan->id,
                    'number' => $loan->loan_number,
                    'principal' => $loan->principal_amount,
                    'principal_amount' => $loan->principal_amount,
                    'interest_rate' => $loan->interest_rate,
                    'rate' => $loan->interest_rate,
                    'term' => $loan->term_months,
                    'term_months' => $loan->term_months,
                    'balance' => $loan->running_balance,
                    'running_balance' => $loan->running_balance,
                    'payment' => $monthlyPayment,
                    'monthly_payment' => $monthlyPayment,
                    'next_payment_date' => $loan->next_payment_date,
                    'status' => $loan->status,
                ];
            });

        $members = Member::where('status', 'active')->get();

        return view('loan-portfolio.index', compact('member', 'summary', 'ledger', 'loans', 'members'));
    }

    /**
     * Generate Statement of Account.
     */
    public function generateSOA(Request $request): Response
    {
        $data = $request->validate([
            'member_id' => 'required|integer',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'format' => 'required|in:pdf,csv',
        ]);

        // Generate SOA data from actual transactions
        $member = Member::findOrFail($data['member_id']);
        $soaData = $member->transactions()
            ->whereBetween('created_at', [$data['start_date'], $data['end_date']])
            ->latest()
            ->get()
            ->map(function ($trans) {
                return [
                    $trans->created_at->format('Y-m-d'),
                    str_replace('_', ' ', ucfirst($trans->type)),
                    $trans->principal_amount ?? 0,
                    $trans->interest_amount ?? 0,
                    $trans->reference_number ?? 'N/A',
                ];
            });

        if ($data['format'] === 'csv') {
            $filename = 'soa_' . now()->format('Y-m-d') . '.csv';
            $handle = fopen('php://memory', 'r+');
            fputcsv($handle, ['Date', 'Description', 'Principal', 'Interest', 'Reference']);

            foreach ($soaData as $row) {
                fputcsv($handle, $row);
            }

            rewind($handle);
            $csv = stream_get_contents($handle);
            fclose($handle);

            return response()->make($csv, 200, [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            ]);
        }

        return response()->json(['status' => 'success']);
    }

    /**
     * Export loan portfolio to CSV.
     */
    public function export(Request $request): Response
    {
        $filename = 'loan_portfolio_' . now()->format('Y-m-d') . '.csv';
        $handle = fopen('php://memory', 'r+');
        fputcsv($handle, ['Loan Number', 'Principal', 'Rate', 'Term', 'Balance', 'Monthly Payment']);

        $loans = Loan::where('status', 'active')
            ->get()
            ->map(function ($loan) {
                return [
                    $loan->loan_number,
                    $loan->principal,
                    $loan->interest_rate,
                    $loan->term_months,
                    $loan->running_balance,
                    $loan->calculateMonthlyPayment(),
                ];
            });

        foreach ($loans as $loan) {
            fputcsv($handle, $loan);
        }

        rewind($handle);
        $csv = stream_get_contents($handle);
        fclose($handle);

        return response()->make($csv, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }
}
