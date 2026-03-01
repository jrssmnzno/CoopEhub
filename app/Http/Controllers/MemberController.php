<?php

namespace App\Http\Controllers;

use App\Models\Member;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class MemberController extends Controller
{
    /**
     * Display a listing of members.
     */
    public function index(): View
    {
        $members = Member::with('user')
            ->get()
            ->map(function ($member) {
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
        ]);

        // Generate next Member ID
        $lastMember = Member::orderBy('member_id', 'desc')->first();
        $nextNumber = 1;
        if ($lastMember) {
            $lastNumber = intval(substr($lastMember->member_id, 3)); // Extract number from MM-XXX
            $nextNumber = $lastNumber + 1;
        }
        $memberId = 'MM-' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);

        // Create new member
        $member = Member::create([
            'member_id' => $memberId,
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'address' => $validated['address'],
            'date_of_birth' => $validated['date_of_birth'],
            'status' => 'active',
            'joined_date' => today(),
        ]);

        return redirect()->route('members.index')
            ->with('success', "Member {$memberId} - {$member->full_name} registered successfully!");
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
                'principal' => $loan->principal,
                'rate' => $loan->interest_rate,
                'balance' => $loan->running_balance,
                'payment' => $loan->calculateMonthlyPayment(),
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
            'status' => 'required|in:active,inactive,suspended',
        ]);

        $member->update($validated);

        return redirect()->route('members.show', $id)->with('success', 'Member updated successfully!');
    }

    /**
     * Delete the specified member.
     */
    public function destroy($id): RedirectResponse
    {
        $member = Member::findOrFail($id);
        $name = $member->full_name;
        $member->delete();

        return redirect()->route('members.index')
            ->with('success', "Member {$name} deleted successfully!");
    }
}
