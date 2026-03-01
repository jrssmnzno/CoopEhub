<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LoanRequest;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LoanRequestController extends Controller
{
    /**
     * Show all pending loan requests
     */
    public function index(): View
    {
        $pendingRequests = LoanRequest::where('status', 'pending')
            ->with('member')
            ->latest('created_at')
            ->get();

        $approvedRequests = LoanRequest::where('status', 'approved')
            ->with('member')
            ->latest('approved_at')
            ->limit(20)
            ->get();

        $rejectedRequests = LoanRequest::where('status', 'rejected')
            ->with('member')
            ->latest('approved_at')
            ->limit(20)
            ->get();

        return view('admin.loan-requests', compact('pendingRequests', 'approvedRequests', 'rejectedRequests'));
    }

    /**
     * Get pending loan requests as JSON
     */
    public function getPending(): JsonResponse
    {
        $requests = LoanRequest::where('status', 'pending')
            ->with('member')
            ->latest('created_at')
            ->get()
            ->map(fn($r) => [
                'id' => $r->id,
                'member_id' => $r->member->member_id,
                'member_name' => $r->member->full_name,
                'amount' => '$' . number_format($r->requested_amount, 2),
                'term' => $r->requested_term_months . ' months',
                'monthly_payment' => '$' . number_format($r->monthly_payment, 2),
                'total_interest' => '$' . number_format($r->calculateTotalInterest(), 2),
                'created_at' => $r->created_at->format('M d, Y'),
                'actions' => ['approve', 'reject'],
            ]);

        return response()->json([
            'requests' => $requests,
            'count' => $requests->count(),
        ]);
    }

    /**
     * Approve a loan request
     */
    public function approve(Request $request, LoanRequest $loanRequest): JsonResponse
    {
        if (!Auth::user()->isAdmin()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        if ($loanRequest->status !== 'pending') {
            return response()->json(['error' => 'Only pending requests can be approved'], 400);
        }

        try {
            DB::beginTransaction();

            $notes = $request->input('notes', '');
            $loan = $loanRequest->approve(Auth::user(), $notes);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Loan request approved and loan created',
                'loan' => [
                    'id' => $loan->id,
                    'loan_number' => $loan->loan_number,
                    'amount' => $loan->principal_amount,
                    'status' => $loan->status,
                ]
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Failed to approve request: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Reject a loan request
     */
    public function reject(Request $request, LoanRequest $loanRequest): JsonResponse
    {
        if (!Auth::user()->isAdmin()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        if ($loanRequest->status !== 'pending') {
            return response()->json(['error' => 'Only pending requests can be rejected'], 400);
        }

        $validated = $request->validate([
            'notes' => ['required', 'string', 'max:500'],
        ]);

        try {
            DB::beginTransaction();

            $loanRequest->reject(Auth::user(), $validated['notes']);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Loan request rejected',
                'request' => [
                    'id' => $loanRequest->id,
                    'status' => $loanRequest->status,
                    'notes' => $loanRequest->admin_notes,
                ]
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Failed to reject request: ' . $e->getMessage()], 500);
        }
    }

    /**
     * View details of a specific loan request
     */
    public function show(LoanRequest $loanRequest): JsonResponse
    {
        if (!Auth::user()->isAdmin()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $member = $loanRequest->member;

        return response()->json([
            'request' => [
                'id' => $loanRequest->id,
                'status' => $loanRequest->status,
                'created_at' => $loanRequest->created_at->format('M d, Y'),
                'requested_amount' => '$' . number_format($loanRequest->requested_amount, 2),
                'requested_term' => $loanRequest->requested_term_months . ' months',
                'interest_rate' => $loanRequest->interest_rate . '%',
                'monthly_payment' => '$' . number_format($loanRequest->monthly_payment, 2),
                'total_interest' => '$' . number_format($loanRequest->calculateTotalInterest(), 2),
                'total_amount' => '$' . number_format($loanRequest->requested_amount + $loanRequest->calculateTotalInterest(), 2),
                'admin_notes' => $loanRequest->admin_notes,
                'approved_by' => $loanRequest->approvedBy?->name,
                'approved_at' => $loanRequest->approved_at?->format('M d, Y'),
            ],
            'member' => [
                'id' => $member->id,
                'member_id' => $member->member_id,
                'name' => $member->full_name,
                'email' => $member->email,
                'phone' => $member->phone,
                'address' => $member->address,
                'status' => $member->status,
                'outstanding_balance' => '$' . number_format($member->calculateOutstandingBalance(), 2),
                'total_loans' => $member->total_loans,
            ]
        ]);
    }
}
