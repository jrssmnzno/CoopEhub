<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\User;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class MemberController extends Controller
{
    use AuthorizesRequests;
    /**
     * Display a listing of members.
     */
    public function index(Request $request): View
    {
        $query = Member::with('user');
        
        // Search by name, member ID, or email
        if ($request->has('search') && $request->input('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('member_id', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }
        
        // Filter by status if provided
        if ($request->has('status') && $request->input('status')) {
            $query->where('status', $request->input('status'));
        }
        
        $members = $query->paginate(15)
            ->through(function ($member) {
                return (object)[
                    'id' => $member->id,
                    'member_id' => $member->member_id,
                    'name' => $member->full_name,
                    'email' => $member->email,
                    'contact' => $member->phone,
                    'joined' => $member->joined_date,
                    'status' => $member->status,
                    'totalLoans' => $member->loans()->count(),
                    'balance' => $member->calculateOutstandingBalance(),
                ];
            });

        return view('members.index', compact('members'));
    }

    /**
     * Show the form for creating a new member.
     */
    public function create(): View
    {
        return view('members.create');
    }

    /**
     * Store a newly created member.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:members',
            'phone' => 'required|string',
            'address' => 'required|string',
            'date_of_birth' => 'required|date',
            'relationship_status' => 'required|string',
            'employment_status' => 'required|string',
            'member_type' => 'required|in:individual,organization',
            'monthly_income' => 'required|numeric|min:0',
            'emergency_contact_name' => 'required|string',
            'emergency_contact_phone' => 'required|string',
            'emergency_contact_relationship' => 'required|string',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        // Generate next Member ID
        $lastMember = Member::orderBy('member_id', 'desc')->first();
        $nextNumber = 1;
        if ($lastMember) {
            $lastNumber = intval(substr($lastMember->member_id, 3)); // Extract number from MM-XXX
            $nextNumber = $lastNumber + 1;
        }
        $memberId = 'MM-' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);

        $userId = null;

        // Create user account only if password is provided
        if ($validated['password']) {
            // Check if email is unique in users table
            if (\App\Models\User::where('email', $validated['email'])->exists()) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'This email is already used for another account.');
            }

            $user = \App\Models\User::create([
                'name' => $validated['first_name'] . ' ' . $validated['last_name'],
                'email' => $validated['email'],
                'password' => $validated['password'],
                'role' => 'member',
                'is_active' => 1,
            ]);
            $userId = $user->id;
        }

        // Create new member
        $member = Member::create([
            'user_id' => $userId,
            'member_id' => $memberId,
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'address' => $validated['address'],
            'date_of_birth' => $validated['date_of_birth'],
            'relationship_status' => $validated['relationship_status'],
            'employment_status' => $validated['employment_status'],
            'member_type' => $validated['member_type'],
            'monthly_income' => $validated['monthly_income'],
            'status' => 'active',
            'joined_date' => today(),
            'emergency_contact_name' => $validated['emergency_contact_name'],
            'emergency_contact_phone' => $validated['emergency_contact_phone'],
            'emergency_contact_relation' => $validated['emergency_contact_relationship'],
        ]);

        $message = "Member {$memberId} - {$member->full_name} registered successfully!";
        if ($userId) {
            $message .= " Account created with email: {$validated['email']}";
        } else {
            $message .= " Member can create their own account later.";
        }

        return redirect()->route('members.index')
            ->with('success', $message);
    }

    /**
     * Display the specified member.
     */
    public function show($id): View
    {
        $member = Member::with('user', 'loans', 'transactions')->findOrFail($id);

        $loans = $member->loans()->get()->map(function ($loan) {
            return (object)[
                'id' => $loan->id,
                'number' => $loan->loan_number,
                'principal' => $loan->principal_amount,
                'rate' => $loan->interest_rate,
                'balance' => $loan->running_balance,
                'payment' => $loan->monthly_payment ?? \App\Models\Loan::calculateMonthlyPayment($loan->principal_amount, $loan->interest_rate, $loan->term_months),
                'status' => $loan->status,
            ];
        });

        $payments = $member->transactions()->latest()->limit(10)->get()->map(function ($trans) {
            return (object)[
                'date' => $trans->created_at->format('Y-m-d'),
                'type' => str_replace('_', ' ', ucfirst($trans->type)),
                'principal' => $trans->principal_amount ?? 0,
                'interest' => $trans->interest_amount ?? 0,
                'total' => $trans->total_amount,
                'reference' => $trans->reference_number ?? 'N/A',
            ];
        });

        return view('members.show', compact('member', 'loans', 'payments'));
    }

    /**
     * Show the form for editing a member.
     */
    public function edit($id): View
    {
        $member = Member::findOrFail($id);
        return view('members.edit', compact('member'));
    }

    /**
     * Update the specified member.
     */
    public function update(Request $request, $id): RedirectResponse
    {
        $member = Member::findOrFail($id);

        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:members,email,' . $member->id,
            'phone' => 'required|string',
            'address' => 'required|string',
            'date_of_birth' => 'required|date',
            'relationship_status' => 'required|string',
            'employment_status' => 'required|string',
            'member_type' => 'required|in:individual,organization',
            'monthly_income' => 'required|numeric|min:0',
            'emergency_contact_name' => 'required|string',
            'emergency_contact_phone' => 'required|string',
            'emergency_contact_relationship' => 'required|string',
            'status' => 'required|in:active,inactive,suspended',
        ]);

        // Map emergency_contact_relationship to emergency_contact_relation for database
        $validated['emergency_contact_relation'] = $validated['emergency_contact_relationship'];
        unset($validated['emergency_contact_relationship']);

        $member->update($validated);

        return redirect()->route('members.show', $id)->with('success', 'Member updated successfully!');
    }

    /**
     * Delete the specified member.
     */
    public function destroy($id): RedirectResponse
    {
        try {
            $member = Member::findOrFail($id);
            
            // Authorize the delete action
            $this->authorize('delete', $member);
            
            $name = $member->full_name;
            
            // Delete associated user account if exists
            if ($member->user_id) {
                User::where('id', $member->user_id)->delete();
            }
            
            // Delete the member
            $member->delete();

            return redirect()->route('members.index')
                ->with('success', "Member {$name} deleted successfully!");
        } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
            return redirect()->route('members.index')
                ->with('error', 'You are not authorized to delete this member.');
        } catch (\Exception $e) {
            return redirect()->route('members.index')
                ->with('error', 'An error occurred while deleting the member: ' . $e->getMessage());
        }
    }

    /**
     * Create a user account for a member
     */
    public function createAccount(Request $request, $memberId): JsonResponse
    {
        $member = Member::findOrFail($memberId);

        // Check if user is authorized
        if (!Auth::user()->can('createAccount', $member)) {
            return response()->json([
                'error' => 'Unauthorized. This member already has an account or you do not have permission.'
            ], 403);
        }

        // Validate the request
        $validated = $request->validate([
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'min:8', 'confirmed'],
        ]);

        try {
            DB::beginTransaction();

            // Create the user account
            $user = User::create([
                'name' => $member->full_name,
                'email' => $validated['email'],
                'password' => $validated['password'],
                'role' => 'member',
                'is_active' => 1,
            ]);

            // Link the user to the member
            $member->update(['user_id' => $user->id]);

            // Log the action
            \App\Models\AuditLog::log(
                Auth::user(),
                'create',
                'Users',
                'User',
                $user->id,
                'success',
                null,
                [
                    'member_id' => $member->id,
                    'email' => $validated['email'],
                    'role' => 'member'
                ],
                'Admin created account for member ' . $member->member_id
            );

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Account created successfully for ' . $member->full_name,
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                ]
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'error' => 'Failed to create account: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a new loan for a member (face-to-face disbursement)
     */
    public function storeLoan(Request $request, $memberId): RedirectResponse
    {
        $member = Member::findOrFail($memberId);

        $validated = $request->validate([
            'loan_type' => 'required|string',
            'principal_amount' => 'required|numeric|min:100',
            'interest_rate' => 'required|numeric|min:0|max:100',
            'term_months' => 'required|integer|min:1|max:60',
        ]);

        // Cast to correct types
        $termMonths = (int)$validated['term_months'];
        $principalAmount = (float)$validated['principal_amount'];
        $interestRate = (float)$validated['interest_rate'];
        
        // Set disbursement date
        $disbursementDate = today();

        // Generate loan number
        $lastLoan = \App\Models\Loan::latest('id')->first();
        $loanNumber = 'LOAN-' . date('Y') . '-' . str_pad(($lastLoan?->id ?? 0) + 1, 6, '0', STR_PAD_LEFT);

        // Calculate monthly payment
        $monthlyPayment = \App\Models\Loan::calculateMonthlyPayment(
            $principalAmount,
            $interestRate,
            $termMonths
        );

        // Create the loan
        $loan = \App\Models\Loan::create([
            'member_id' => $member->id,
            'loan_number' => $loanNumber,
            'loan_type' => $validated['loan_type'],
            'principal_amount' => $principalAmount,
            'original_principal' => $principalAmount,
            'interest_rate' => $interestRate,
            'term_months' => $termMonths,
            'running_balance' => $principalAmount,
            'monthly_payment' => $monthlyPayment,
            'status' => 'active',
            'disbursement_date' => $disbursementDate,
            'maturity_date' => $disbursementDate->copy()->addMonths($termMonths),
            'next_payment_date' => $disbursementDate->copy()->addMonth(),
            'principal_paid' => 0,
            'interest_paid' => 0,
            'interest_due' => 0,
            'payments_made' => 0,
        ]);
        $loan->createInstallmentSchedule();

        // Create audit log entry
        \App\Models\AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'create_loan',
            'description' => "Created loan {$loanNumber} for {$member->full_name} - Amount: ₱" . number_format($principalAmount, 2),
            'model' => \App\Models\Loan::class,
            'model_id' => $loan->id,
        ]);

        return redirect()->route('members.show', $memberId)
            ->with('success', "Loan {$loanNumber} created successfully for {$member->full_name}! Monthly payment: ₱" . number_format($monthlyPayment, 2));
    }
}
