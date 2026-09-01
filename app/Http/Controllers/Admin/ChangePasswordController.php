<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class ChangePasswordController extends Controller
{
    /**
     * Show the change password form for admin
     */
    public function showChangePasswordForm(): View
    {
        return view('admin.auth.change-password');
    }

    /**
     * Update the password for authenticated admin
     */
    public function updatePassword(Request $request): RedirectResponse
    {
        // Validate the input
        $validated = $request->validate([
            'current_password' => [
                'required',
                'string',
                function ($attribute, $value, $fail) {
                    if (!Hash::check($value, Auth::user()->password)) {
                        $fail('The current password is incorrect.');
                    }
                },
            ],
            'new_password' => [
                'required',
                'string',
                'min:8',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]+$/',
                'confirmed',
                function ($attribute, $value, $fail) {
                    if (Hash::check($value, Auth::user()->password)) {
                        $fail('The new password cannot be the same as your current password.');
                    }
                },
            ],
            'new_password_confirmation' => ['required', 'string'],
        ], [
            'current_password.required' => 'Current password is required.',
            'new_password.required' => 'New password is required.',
            'new_password.min' => 'Password must be at least 8 characters long.',
            'new_password.regex' => 'Password must contain at least one uppercase letter, one lowercase letter, one number, and one special character (@$!%*?&).',
            'new_password.confirmed' => 'Password confirmation does not match.',
        ]);

        // Update the password
        Auth::user()->update([
            'password' => Hash::make($validated['new_password']),
        ]);

        return redirect()->route('dashboard')
            ->with('success', 'Password has been changed successfully.');
    }
}
