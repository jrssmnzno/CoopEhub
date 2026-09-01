<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class ChangePasswordController extends Controller
{
    public function showChangePasswordForm(): View
    {
        return view('member.auth.change-password');
    }

    public function updatePassword(Request $request): RedirectResponse
    {
        // Validate the input
        $validated = $request->validate([
            'current_password' => 'required',
            'new_password' => [
                'required',
                'min:8',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[a-zA-Z\d@$!%*?&]/i',
                'different:current_password',
            ],
            'new_password_confirmation' => 'required|same:new_password',
        ], [
            'current_password.required' => 'Current password is required.',
            'new_password.required' => 'New password is required.',
            'new_password.min' => 'New password must be at least 8 characters long.',
            'new_password.regex' => 'Password must contain at least one uppercase letter, one lowercase letter, one number, and one special character (@$!%*?&).',
            'new_password.different' => 'New password must be different from your current password.',
            'new_password_confirmation.required' => 'Please confirm your new password.',
            'new_password_confirmation.same' => 'Password confirmation does not match.',
        ]);

        $user = Auth::user();

        // Check if the current password is correct
        if (!Hash::check($request->current_password, $user->password)) {
            return back()
                ->withInput()
                ->withErrors(['current_password' => 'The current password is incorrect.']);
        }

        // Update the password
        $user->password = Hash::make($validated['new_password']);
        $user->save();

        return redirect()
            ->route('dashboard')
            ->with('success', 'Password changed successfully!');
    }
}
