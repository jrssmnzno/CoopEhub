<?php

namespace App\Http\Controllers;

use App\Models\LoanRequest;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LoanRequestController extends Controller
{
    /**
     * Store a new loan request from a member
     */
    public function store(Request $request): JsonResponse
    {
        // Only members can create loan requests
        if (!Auth::user()->isMember()) {
            return response()->json(['error' => 'Only members can request loans'], 403);
        }

        $validated = $request->validate([
            'amount' => ['required', 'numeric', 'min:1000', 'max:1000000'],
            'loan_type' => ['required', 'string', 'in:cash,swine,goat,fertilizers'],
            'term_months' => ['required', 'integer', 'min:1', 'max:60'],
        ]);

        $user = Auth::user();
        $member = $user->member;

        if (!$member) {
            return response()->json(['error' => 'Member profile not found'], 404);
        }

        try {
            DB::beginTransaction();

            // Create the loan request
            $loanRequest = LoanRequest::create([
                'member_id' => $member->id,
                'requested_amount' => $validated['amount'],
                'loan_type' => $validated['loan_type'],
                'requested_term_months' => $validated['term_months'],
                'interest_rate' => 12, // Default 12% annual
                'monthly_payment' => null, // Calculate on approval
                'status' => 'pending',
            ]);

            // Calculate monthly payment
            $monthlyPayment = $loanRequest->calculateMonthlyPayment();
            $loanRequest->update(['monthly_payment' => $monthlyPayment]);

            // Log the request
            \App\Models\AuditLog::log(
                $user,
                'create',
                'Loans',
                'LoanRequest',
                $loanRequest->id,
                'success',
                null,
                [
                    'amount' => $validated['amount'],
                    'loan_type' => $validated['loan_type'],
                    'term_months' => $validated['term_months'],
                    'status' => 'pending'
                ],
                'Member submitted loan request'
            );

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Loan request submitted successfully',
                'loan_request' => [
                    'id' => $loanRequest->id,
                    'amount' => $loanRequest->requested_amount,
                    'loan_type' => $loanRequest->loan_type,
                    'term_months' => $loanRequest->requested_term_months,
                    'monthly_payment' => $loanRequest->monthly_payment,
                    'status' => $loanRequest->status,
                ]
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Failed to submit loan request: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Get pending loan requests for member
     */
    public function myRequests(): JsonResponse
    {
        $user = Auth::user();
        $member = $user->member;

        if (!$member) {
            return response()->json(['error' => 'Member profile not found'], 404);
        }

        $requests = $member->loanRequests()
            ->latest('created_at')
            ->get()
            ->map(fn($r) => [
                'id' => $r->id,
                'amount' => $r->requested_amount,
                'term_months' => $r->requested_term_months,
                'monthly_payment' => $r->monthly_payment,
                'total_interest' => $r->calculateTotalInterest(),
                'status' => $r->status,
                'created_at' => $r->created_at->format('M d, Y'),
                'approved_at' => $r->approved_at?->format('M d, Y'),
                'notes' => $r->admin_notes,
            ]);

        return response()->json([
            'requests' => $requests,
            'count' => $requests->count(),
        ]);
    }
}
