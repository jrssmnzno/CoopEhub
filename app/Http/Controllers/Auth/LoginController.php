<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class LoginController extends Controller
{
    protected const MAX_ATTEMPTS = 3;
    protected const LOCK_TIME_MINUTES = 3;

    /**
     * Show the login form.
     */
    public function showLoginForm(): View
    {
        return view('auth.login');
    }

    /**
     * Handle login request with throttling.
     */
    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'string'],
            'password' => ['required'],
        ]);

        // Check for rate limiting on IP
        if ($this->isTooManyAttempts($request->ip())) {
            AuditLog::log(
                null,
                'failed_login',
                'Authentication',
                'User',
                $credentials['email'],
                'failed',
                null,
                null,
                'Too many login attempts from IP: ' . $request->ip()
            );

            return back()->withErrors([
                'email' => 'Too many login attempts. Please try again in 3 minutes.',
            ])->onlyInput('email');
        }

        // Determine if input is Member ID (MM-XXX) or email
        $isMemberId = preg_match('/^MM-\d{3}$/', $credentials['email']);
        
        if ($isMemberId) {
            // Look up user by Member ID
            $member = \App\Models\Member::where('member_id', $credentials['email'])->first();
            $user = $member?->user;
        } else {
            // Look up user by email
            $user = User::where('email', $credentials['email'])->first();
        }

        // Check if account exists and is not locked
        if (!$user) {
            // Log failed attempt
            AuditLog::log(
                null,
                'failed_login',
                'Authentication',
                'User',
                $credentials['email'],
                'failed',
                null,
                null,
                'User not found'
            );

            return back()->withErrors([
                'email' => 'The provided credentials do not match our records.',
            ])->onlyInput('email');
        }

        // Check if user is locked
        if ($user->isLocked()) {
            AuditLog::log(
                $user,
                'failed_login',
                'Authentication',
                'User',
                $user->id,
                'failed',
                null,
                null,
                'Account locked due to failed attempts'
            );

            $lockedUntil = strtotime($user->locked_until);
            $currentTime = time();
            $secondsRemaining = max(0, $lockedUntil - $currentTime);

            return back()->withErrors([
                'email' => 'locked',
            ])->with([
                'lockedEmail' => $user->email,
                'secondsRemaining' => $secondsRemaining,
            ])->onlyInput('email');
        }

        // Check if user is active
        if (!$user->is_active) {
            AuditLog::log(
                $user,
                'failed_login',
                'Authentication',
                'User',
                $user->id,
                'failed',
                null,
                null,
                'Account is inactive'
            );

            return back()->withErrors([
                'email' => 'Your account is inactive. Please contact the administrator.',
            ])->onlyInput('email');
        }

        // Attempt authentication
        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            // Reset failed attempts on successful login
            $user->resetFailedAttempts();
            
            // Log successful login
            AuditLog::log(
                $user,
                'login',
                'Authentication',
                'User',
                $user->id,
                'success'
            );

            $request->session()->regenerate();
            
            return redirect()->intended(route('dashboard'));
        }

        // Increment failed attempts
        $user->incrementFailedAttempts();

        // Log failed login attempt
        AuditLog::log(
            $user,
            'failed_login',
            'Authentication',
            'User',
            $user->id,
            'failed',
            null,
            null,
            'Invalid password. Attempt ' . $user->failed_attempts . ' of ' . self::MAX_ATTEMPTS
        );

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    /**
     * Check if too many login attempts from IP
     */
    protected function isTooManyAttempts(string $ip): bool
    {
        $attempts = AuditLog::where('ip_address', $ip)
            ->where('activity', 'failed_login')
            ->where('created_at', '>=', now()->subMinutes(self::LOCK_TIME_MINUTES))
            ->count();

        return $attempts >= self::MAX_ATTEMPTS;
    }

    /**
     * Handle logout.
     */
    public function logout(Request $request): RedirectResponse
    {
        $user = Auth::user();
        
        // Log logout
        if ($user) {
            AuditLog::log(
                $user,
                'logout',
                'Authentication',
                'User',
                $user->id,
                'success'
            );
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}

