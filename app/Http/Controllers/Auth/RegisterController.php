<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Member;
use App\Models\AuditLog;
use App\Rules\ValidMemberId;
use App\Rules\ValidMemberPassword;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class RegisterController extends Controller
{
    /**
     * Show the registration form.
     */
    public function showRegistrationForm(): View
    {
        return view('auth.register');
    }

    /**
     * Handle member registration with Member ID verification.
     */
    public function register(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'member_id' => ['required', 'string', new ValidMemberId()],
            'email' => ['required', 'email'],
            'password' => ['required', 'string', new ValidMemberPassword()],
            'password_confirmation' => ['required', 'same:password'],
        ]);

        try {
            DB::beginTransaction();

            // Fetch member by ID
            $member = Member::where('member_id', $validated['member_id'])->first();

            if (!$member) {
                DB::rollBack();
                return redirect()->back()->withErrors([
                    'member_id' => 'Member ID not found.',
                ]);
            }

            // Create user account
            $user = User::create([
                'name' => $member->full_name,
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role' => 'member',
                'is_active' => true,
                'ip_address' => $request->ip(),
            ]);

            // Link user to member
            $member->update(['user_id' => $user->id]);

            // Log the registration
            AuditLog::log(
                $user,
                'create',
                'Members',
                'Member',
                $member->id,
                'success',
                null,
                ['user_id' => $user->id, 'status' => 'registered'],
                'Member account created through self-registration'
            );

            DB::commit();

            session()->flash('success', 'Registration successful! You can now log in with your credentials.');

            return redirect()->route('login');

        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->withErrors([
                'error' => 'An error occurred during registration. Please try again.',
            ])->withInput();
        }
    }

    /**
     * Validate Member ID via AJAX (for real-time validation)
     */
    public function validateMemberId(Request $request)
    {
        $memberId = $request->get('member_id');

        // Check format
        if (!preg_match('/^MM-\d{3}$/', $memberId)) {
            return response()->json([
                'valid' => false,
                'message' => 'Invalid Member ID format. Use MM-XXX',
            ]);
        }

        // Check existence
        $member = Member::where('member_id', $memberId)->first();

        if (!$member) {
            return response()->json([
                'valid' => false,
                'message' => 'Member ID not found in system.',
            ]);
        }

        // Check if already has account
        if ($member->user_id !== null) {
            return response()->json([
                'valid' => false,
                'message' => 'This Member ID already has an active account.',
            ]);
        }

        return response()->json([
            'valid' => true,
            'message' => 'Member ID verified.',
            'member' => [
                'name' => $member->full_name,
                'email' => $member->email,
            ],
        ]);
    }

    /**
     * Check password strength via AJAX
     */
    public function checkPasswordStrength(Request $request)
    {
        $password = $request->get('password');
        $score = 0;
        $feedback = [];

        // Length check
        if (strlen($password) >= 8 && strlen($password) <= 12) {
            $score += 20;
        } else {
            $feedback[] = 'Password must be 8-12 characters';
        }

        // Uppercase check
        if (preg_match('/[A-Z]/', $password)) {
            $score += 20;
        } else {
            $feedback[] = 'Add uppercase letters';
        }

        // Lowercase check
        if (preg_match('/[a-z]/', $password)) {
            $score += 20;
        } else {
            $feedback[] = 'Add lowercase letters';
        }

        // Number check
        if (preg_match('/[0-9]/', $password)) {
            $score += 20;
        } else {
            $feedback[] = 'Add numbers';
        }

        // Special character check
        if (preg_match('/[!@#$%^&*()_+\-=\[\]{};:\'",.<>?\/\\|`~]/', $password)) {
            $score += 20;
        } else {
            $feedback[] = 'Add special characters (!@#$%^&*)';
        }

        // Determine strength level
        $strength = match (true) {
            $score < 40 => 'weak',
            $score < 80 => 'fair',
            $score < 100 => 'good',
            default => 'strong',
        };

        return response()->json([
            'score' => $score,
            'strength' => $strength,
            'feedback' => $feedback,
            'requirements' => [
                'length' => strlen($password) >= 8 && strlen($password) <= 12,
                'uppercase' => (bool) preg_match('/[A-Z]/', $password),
                'lowercase' => (bool) preg_match('/[a-z]/', $password),
                'numbers' => (bool) preg_match('/[0-9]/', $password),
                'special' => (bool) preg_match('/[!@#$%^&*()_+\-=\[\]{};:\'",.<>?\/\\|`~]/', $password),
            ],
        ]);
    }
}
