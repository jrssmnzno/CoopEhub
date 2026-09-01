<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\ResetPasswordMail;
use App\Models\User;
use App\Rules\ValidMemberPassword;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class PasswordResetController extends Controller
{
    /**
     * Display the forgot password form.
     */
    public function showForgotForm(): View
    {
        return view('auth.forgot-password');
    }

    /**
     * Send password reset link to user email.
     */
    public function sendResetLink(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email', 'exists:users,email'],
        ], [
            'email.required' => 'Please enter your email address.',
            'email.email' => 'Please enter a valid email address.',
            'email.exists' => 'No account found with this email address.',
        ]);

        // Check if user exists and is active
        $user = User::where('email', $request->email)->first();
        
        if (!$user || !$user->is_active) {
            return back()->withErrors([
                'email' => 'This account is inactive or does not exist.',
            ]);
        }

        // Delete existing tokens for this email
        DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->delete();

        // Create password reset token (valid for 60 minutes)
        $token = Str::random(64);

        DB::table('password_reset_tokens')->insert([
            'email' => $request->email,
            'token' => Hash::make($token),
            'created_at' => now(),
        ]);

        // Send reset email
        Mail::to($user->email)->send(new ResetPasswordMail($user, $token));

        return back()->with('status', 'We have emailed your password reset link! Please check your email.');
    }

    /**
     * Display the password reset form.
     */
    public function showResetForm(Request $request, $token): View|RedirectResponse
    {
        $email = $request->query('email');

        if (!$email) {
            return redirect('/forgot-password')->withErrors([
                'email' => 'Email parameter is missing.',
            ]);
        }

        // Check if token exists and is valid
        $resetToken = DB::table('password_reset_tokens')
            ->where('email', $email)
            ->first();

        if (!$resetToken || !Hash::check($token, $resetToken->token)) {
            return redirect('/login')->withErrors([
                'token' => 'Invalid or expired password reset link.',
            ]);
        }

        // Check if token is not older than 60 minutes
        if ($resetToken->created_at < now()->subMinutes(60)) {
            DB::table('password_reset_tokens')
                ->where('email', $email)
                ->delete();

            return redirect('/forgot-password')->withErrors([
                'token' => 'This password reset link has expired. Please request a new one.',
            ]);
        }

        return view('auth.reset-password', ['token' => $token]);
    }

    /**
     * Reset the password.
     */
    public function resetPassword(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email', 'exists:users,email'],
            'token' => ['required'],
            'password' => ['required', 'string', new ValidMemberPassword(), 'confirmed'],
            'password_confirmation' => ['required'],
        ], [
            'email.required' => 'Email is required.',
            'email.email' => 'Please enter a valid email address.',
            'email.exists' => 'No account found with this email address.',
            'token.required' => 'Password reset token is required.',
            'password.required' => 'Password is required.',
            'password.confirmed' => 'Password confirmation does not match.',
        ]);

        // Verify token
        $resetToken = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->first();

        if (!$resetToken || !Hash::check($request->token, $resetToken->token)) {
            return back()->withErrors([
                'token' => 'Invalid password reset token.',
            ])->withInput();
        }

        // Check if token is not older than 60 minutes
        if ($resetToken->created_at < now()->subMinutes(60)) {
            DB::table('password_reset_tokens')
                ->where('email', $request->email)
                ->delete();

            return back()->withErrors([
                'token' => 'This password reset link has expired.',
            ]);
        }

        // Find user and update password
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors([
                'email' => 'User not found.',
            ]);
        }

        // Update user password
        $user->update([
            'password' => Hash::make($request->password),
        ]);

        // Delete the used token
        DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->delete();

        return redirect('/login')->with('status', 'Your password has been reset successfully. Please log in with your new password.');
    }
}
