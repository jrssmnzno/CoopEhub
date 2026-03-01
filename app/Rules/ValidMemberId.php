<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\ValidationRule;

class ValidMemberId implements ValidationRule
{
    /**
     * Validate Member ID format and existence
     * Format: MM-XXX
     */
    public function validate(string $attribute, mixed $value, \Closure $fail): void
    {
        // Check format MM-XXX
        if (!preg_match('/^MM-\d{3}$/', $value)) {
            $fail('The '.$attribute.' must be in format MM-XXX (e.g., MM-001).');
            return;
        }

        // Check if member ID exists in database
        $exists = \App\Models\Member::where('member_id', $value)->exists();
        if (!$exists) {
            $fail('The '.$attribute.' does not exist in our system. Please contact the office.');
            return;
        }

        // Check if member already has an account
        $member = \App\Models\Member::where('member_id', $value)->first();
        if ($member->user_id !== null) {
            $fail('This member ID already has an active account.');
            return;
        }
    }
}
