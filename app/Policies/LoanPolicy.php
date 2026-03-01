<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Loan;

class LoanPolicy
{
    /**
     * Determine if the user can view the loan.
     */
    public function view(User $user, Loan $loan): bool
    {
        // Admin can view any loan
        if ($user->isAdmin()) {
            return true;
        }

        // Member can only view their own loans
        return $user->member?->id === $loan->member_id;
    }

    /**
     * Determine if the user can process payment on the loan.
     */
    public function processPayment(User $user, Loan $loan): bool
    {
        // Admin can process payment for any loan
        if ($user->isAdmin()) {
            return true;
        }

        // Member can only process payments on their own loans
        return $user->member?->id === $loan->member_id;
    }

    /**
     * Determine if the user can view all loans (admin only).
     */
    public function viewAll(User $user): bool
    {
        return $user->isAdmin();
    }
}
