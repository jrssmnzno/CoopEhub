<?php

namespace App\Policies;

use App\Models\User;
use App\Models\LoanRequest;

class LoanRequestPolicy
{
    /**
     * Determine if the user can view the loan request.
     */
    public function view(User $user, LoanRequest $loanRequest): bool
    {
        // Admin can view any loan request
        if ($user->isAdmin()) {
            return true;
        }

        // Member can only view their own loan requests
        return $user->member?->id === $loanRequest->member_id;
    }

    /**
     * Determine if the user can create a loan request.
     */
    public function create(User $user): bool
    {
        // Only members can create loan requests
        return $user->isMember();
    }

    /**
     * Determine if the user can approve/reject loan requests (admin only).
     */
    public function approve(User $user): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine if the user can view all loan requests (admin only).
     */
    public function viewAll(User $user): bool
    {
        return $user->isAdmin();
    }
}
