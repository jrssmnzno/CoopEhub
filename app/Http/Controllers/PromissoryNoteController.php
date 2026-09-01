<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use Illuminate\View\View;
use Illuminate\Http\Response;
use Illuminate\Http\Request;

class PromissoryNoteController extends Controller
{
    /**
     * Display the promissory note.
     */
    public function show($loanId): View
    {
        $loanData = Loan::with('member')->findOrFail($loanId);

        $loan = (object)[
            'id' => $loanData->id,
            'number' => $loanData->loan_number,
            'borrower' => $loanData->member?->full_name ?? 'N/A',
            'principal' => $loanData->principal_amount,
            'rate' => $loanData->interest_rate,
            'term' => $loanData->term_months,
            'startDate' => $loanData->created_at->format('Y-m-d'),
            'monthlyPayment' => Loan::calculateMonthlyPayment(
                (float)$loanData->principal_amount,
                (float)$loanData->interest_rate,
                (int)$loanData->term_months
            ),
        ];

        // Generate payment schedule
        $schedule = [];
        $balance = $loanData->principal_amount;
        $monthlyRate = $loanData->interest_rate / 100 / 12;
        $monthlyPayment = Loan::calculateMonthlyPayment(
            (float)$loanData->principal_amount,
            (float)$loanData->interest_rate,
            (int)$loanData->term_months
        );

        for ($month = 1; $month <= $loanData->term_months; $month++) {
            $interest = $balance * $monthlyRate;
            $principal = $monthlyPayment - $interest;
            $balance -= $principal;

            $schedule[] = (object)[
                'month' => $month,
                'date' => $loanData->created_at->addMonths($month)->format('Y-m-d'),
                'payment' => round($monthlyPayment, 2),
                'principal' => round($principal, 2),
                'interest' => round($interest, 2),
                'balance' => max(0, round($balance, 2)),
            ];
        }

        return view('promissory-note.show', compact('loan', 'schedule'));
    }

    /**
     * Print the promissory note.
     */
    public function print($loanId): Response
    {
        $loanData = Loan::with('member')->findOrFail($loanId);

        $loan = (object)[
            'id' => $loanData->id,
            'number' => $loanData->loan_number,
            'borrower' => $loanData->member?->full_name ?? 'N/A',
            'principal' => $loanData->principal_amount,
            'rate' => $loanData->interest_rate,
            'term' => $loanData->term_months,
            'startDate' => $loanData->created_at->format('Y-m-d'),
            'monthlyPayment' => Loan::calculateMonthlyPayment(
                (float)$loanData->principal_amount,
                (float)$loanData->interest_rate,
                (int)$loanData->term_months
            ),
        ];

        $html = view('promissory-note.template', compact('loan'))->render();

        return response()->make($html, 200, [
            'Content-Type' => 'text/html; charset=utf-8',
        ]);
    }

    /**
     * Generate PDF of the promissory note.
     */
    public function pdf($loanId): Response
    {
        $loanData = Loan::with('member')->findOrFail($loanId);

        $loan = (object)[
            'id' => $loanData->id,
            'number' => $loanData->loan_number,
            'borrower' => $loanData->member?->full_name ?? 'N/A',
            'principal' => $loanData->principal_amount,
            'rate' => $loanData->interest_rate,
            'term' => $loanData->term_months,
            'startDate' => $loanData->created_at->format('Y-m-d'),
            'monthlyPayment' => Loan::calculateMonthlyPayment(
                (float)$loanData->principal_amount,
                (float)$loanData->interest_rate,
                (int)$loanData->term_months
            ),
        ];

        $html = view('promissory-note.template', compact('loan'))->render();

        return response()->make($html, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="Promissory_Note_' . $loanData->loan_number . '.pdf"',
        ]);
    }
}
