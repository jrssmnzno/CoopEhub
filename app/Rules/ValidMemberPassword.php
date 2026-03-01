<?php

namespace App\Rules;

use Closing;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidMemberPassword implements ValidationRule
{
    /**
     * Validate member password: 8-12 characters with uppercase, lowercase, numbers, and special characters
     */
    public function validate(string $attribute, mixed $value, \Closure $fail): void
    {
        if (strlen($value) < 8 || strlen($value) > 12) {
            $fail('The '.$attribute.' must be between 8 and 12 characters.');
            return;
        }

        if (!preg_match('/[A-Z]/', $value)) {
            $fail('The '.$attribute.' must contain at least one uppercase letter.');
            return;
        }

        if (!preg_match('/[a-z]/', $value)) {
            $fail('The '.$attribute.' must contain at least one lowercase letter.');
            return;
        }

        if (!preg_match('/[0-9]/', $value)) {
            $fail('The '.$attribute.' must contain at least one number.');
            return;
        }

        if (!preg_match('/[!@#$%^&*()_+\-=\[\]{};:\'",.<>?\/\\|`~]/', $value)) {
            $fail('The '.$attribute.' must contain at least one special character.');
            return;
        }
    }
}
