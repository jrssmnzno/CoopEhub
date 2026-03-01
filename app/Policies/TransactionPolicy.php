<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Transaction;

class TransactionPolicy
{
    /**
     * Determine if the user can view the transaction.
     */
    public function view(User $user, Transaction $transaction): bool
    {
        // Admin can view any transaction
        if ($user->isAdmin()) {
            return true;
        }

        // Member can only view their own transactions
        return $user->member?->id === $transaction->member_id;
    }

    /**
     * Determine if the user can view all transactions (admin only).
     */
    public function viewAll(User $user): bool
    {
        return $user->isAdmin();
    }
}
