<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Show the dashboard - route to appropriate view.
     */
    public function index(): View
    {
        $user = Auth::user();

        if ($user->isAdmin()) {
            return $this->adminDashboard();
        }

        return $this->memberDashboard();
    }

    /**
     * Admin dashboard view
     */
    private function adminDashboard(): View
    {
        $stats = [
            'members' => \App\Models\Member::count(),
            'loans' => \App\Models\Loan::count(),
            'collections' => \App\Models\Transaction::whereIn('type', ['interest_payment', 'principal_payment'])->sum('total_amount'),
            'overdue' => \App\Models\Loan::where('status', 'overdue')->sum('running_balance'),
        ];

        $recentTransactions = \App\Models\Transaction::latest('created_at')->limit(10)->get();
        $pendingLoans = \App\Models\LoanRequest::where('status', 'pending')->count();
        $totalMembers = \App\Models\Member::count();

        return view('dashboard.admin', compact('stats', 'recentTransactions', 'pendingLoans', 'totalMembers'));
    }

    /**
     * Member dashboard view
     */
    private function memberDashboard(): View
    {
        $user = Auth::user();
        $member = $user->member;

        if (!$member) {
            return view('dashboard.member');
        }

        // Get financial summary
        $activeLoans = $member->loans()->where('status', 'active')->get();
        $totalOutstanding = $member->calculateOutstandingBalance();
        $remainingPrincipal = $activeLoans->sum('running_balance');
        
        // Get paid statistics
        $principalPaid = $member->transactions()
            ->whereIn('type', ['principal_payment'])
            ->sum('principal_amount');
        $interestPaid = $member->transactions()
            ->whereIn('type', ['interest_payment'])
            ->sum('interest_amount');

        // Get pending requests
        $pendingRequests = $member->loanRequests()
            ->where('status', 'pending')
            ->get();

        // Get recent transactions
        $recentTransactions = $member->transactions()
            ->latest('created_at')
            ->limit(10)
            ->get();

        // Get payment history data for chart
        $paymentHistory = $member->transactions()
            ->whereIn('type', ['principal_payment', 'interest_payment'])
            ->where('created_at', '>=', now()->subDays(30))
            ->latest('created_at')
            ->limit(10)
            ->get();

        // Get upcoming meetings
        $upcomingMeeting = null;
        try {
            $upcomingMeeting = \App\Models\Meeting::where('meeting_date', '>=', now())
                ->whereIn('status', ['scheduled', 'ongoing'])
                ->orderBy('meeting_date', 'asc')
                ->first();
        } catch (\Exception $e) {
            // Table doesn't exist yet or other error - meetings table not created
            $upcomingMeeting = null;
        }

        return view('dashboard.member', compact(
            'member',
            'activeLoans',
            'totalOutstanding',
            'remainingPrincipal',
            'principalPaid',
            'interestPaid',
            'pendingRequests',
            'recentTransactions',
            'paymentHistory',
            'upcomingMeeting'
        ));
    }

    /**
     * API endpoint: Get member dashboard data
     */
    public function getDashboardData(): JsonResponse
    {
        $user = Auth::user();
        $member = $user->member;

        if (!$member) {
            return response()->json(['error' => 'Member profile not found'], 404);
        }

        // Get financial summary
        $activeLoans = $member->activeLoans()->get();
        $totalOutstanding = $member->calculateOutstandingBalance();
        $remainingPrincipal = $activeLoans->sum('running_balance');

        // Get last 5 transactions
        $recentTransactions = $member->transactions()
            ->latest('created_at')
            ->limit(5)
            ->get()
            ->map(fn($t) => [
                'id' => $t->id,
                'date' => $t->created_at->format('M d, Y'),
                'type' => str_replace('_', ' ', ucfirst($t->type)),
                'amount' => '$' . number_format($t->total_amount, 2),
                'status' => 'Completed',
                'description' => $t->description ?? 'Transaction',
            ]);

        // Get pending loan requests
        $pendingRequests = $member->loanRequests()
            ->where('status', 'pending')
            ->get();

        // Get next payment info
        $nextPayment = null;
        if ($activeLoans->isNotEmpty()) {
            $loan = $activeLoans->first();
            $nextPayment = [
                'amount' => $loan->monthly_payment,
                'due_date' => $loan->next_payment_date->format('M d, Y'),
                'days_until' => now()->diffInDays($loan->next_payment_date),
                'is_overdue' => $loan->isOverdue(),
            ];
        }

        return response()->json([
            'member' => [
                'id' => $member->id,
                'member_id' => $member->member_id,
                'name' => $member->full_name,
                'status' => $member->status,
            ],
            'summary' => [
                'total_outstanding' => '$' . number_format($totalOutstanding, 2),
                'remaining_principal' => '$' . number_format($remainingPrincipal, 2),
                'active_loans' => $activeLoans->count(),
                'pending_requests' => $pendingRequests->count(),
            ],
            'next_payment' => $nextPayment,
            'recent_transactions' => $recentTransactions,
            'pending_requests' => $pendingRequests->map(fn($r) => [
                'id' => $r->id,
                'amount' => '$' . number_format($r->requested_amount, 2),
                'term' => $r->requested_term_months . ' months',
                'status' => ucfirst($r->status),
                'created_at' => $r->created_at->format('M d, Y'),
            ]),
        ]);
    }

    /**
     * API endpoint: Get loan calculation preview
     */
    public function calculateLoanPreview(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'amount' => ['required', 'numeric', 'min:100'],
            'term_months' => ['required', 'integer', 'min:1', 'max:60'],
        ]);

        $principal = $validated['amount'];
        $termMonths = $validated['term_months'];
        $interestRate = 12; // Default 12% annual

        // Calculate monthly payment
        $monthlyRate = ($interestRate / 100) / 12;
        if ($monthlyRate == 0) {
            $monthlyPayment = $principal / $termMonths;
        } else {
            $numerator = $monthlyRate * pow(1 + $monthlyRate, $termMonths);
            $denominator = pow(1 + $monthlyRate, $termMonths) - 1;
            $monthlyPayment = $principal * ($numerator / $denominator);
        }

        $totalInterest = ($monthlyPayment * $termMonths) - $principal;

        return response()->json([
            'principal' => number_format($principal, 2),
            'interest_rate' => $interestRate . '%',
            'term_months' => $termMonths,
            'monthly_payment' => number_format($monthlyPayment, 2),
            'total_interest' => number_format($totalInterest, 2),
            'total_amount' => number_format($principal + $totalInterest, 2),
        ]);
    }
}
