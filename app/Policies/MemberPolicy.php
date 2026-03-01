<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Member;

class MemberPolicy
{
    /**
     * Determine if the user can view the member.
     */
    public function view(User $user, Member $member): bool
    {
        // Admin can view any member
        if ($user->isAdmin()) {
            return true;
        }

        // Member can only view their own profile
        return $user->member?->id === $member->id;
    }

    /**
     * Determine if the user can update the member.
     */
    public function update(User $user, Member $member): bool
    {
        // Admin can update any member
        if ($user->isAdmin()) {
            return true;
        }

        // Member can only update their own profile
        return $user->member?->id === $member->id;
    }

    /**
     * Determine if the user can view all members (admin only).
     */
    public function viewAll(User $user): bool
    {
        return $user->isAdmin();
    }
}
