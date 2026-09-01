# Forgot Password Logic Implementation

## Overview
The forgot password feature allows users to securely reset their passwords through email verification. This system uses Laravel's password reset token mechanism with secure, time-limited tokens.

---

## System Architecture

### Flow Diagram
```
1. User clicks "Forgot Password" on login page
   ↓
2. User enters email address on forgot-password form
   ↓
3. System validates email exists and account is active
   ↓
4. System generates secure reset token
   ↓
5. System sends email with reset link
   ↓
6. User clicks link in email
   ↓
7. System validates token (not expired, correct format)
   ↓
8. User sees reset-password form with email pre-filled
   ↓
9. User enters new password (8-12 chars, with uppercase, lowercase, number, special char)
   ↓
10. System validates password requirements
    ↓
11. System updates user password in database
    ↓
12. System deletes used token
    ↓
13. User redirected to login with success message
```

---

## Files Involved

### Controllers
**File:** `app/Http/Controllers/Auth/PasswordResetController.php`

Methods:
- `showForgotForm()` - Displays forgot password form
- `sendResetLink()` - Validates email and sends reset email
- `showResetForm($token)` - Displays password reset form with token validation
- `resetPassword()` - Validates and updates password

### Mailable
**File:** `app/Mail/ResetPasswordMail.php`

- Sends password reset email to user
- Includes reset link valid for 60 minutes
- Professional HTML template with security notices

### Views
- `resources/views/auth/forgot-password.blade.php` - Forgot password form
- `resources/views/auth/reset-password.blade.php` - Password reset form
- `resources/views/emails/password-reset.blade.php` - Email template

### Routes
**File:** `routes/web.php`

```php
Route::get('/forgot-password', [PasswordResetController::class, 'showForgotForm'])->name('password.request');
Route::post('/forgot-password', [PasswordResetController::class, 'sendResetLink'])->name('password.email');
Route::get('/reset-password/{token}', [PasswordResetController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [PasswordResetController::class, 'resetPassword'])->name('password.update');
```

### Database Table
**Table:** `password_reset_tokens`

Columns:
- `email` - User email address (indexed)
- `token` - Hashed reset token
- `created_at` - Token creation timestamp

---

## Detailed Logic

### 1. Show Forgot Password Form
**Route:** `GET /forgot-password`  
**Controller Method:** `showForgotForm()`

Returns the forgot password view with:
- Email input field
- Submit button
- Link back to login

### 2. Send Password Reset Link
**Route:** `POST /forgot-password`  
**Controller Method:** `sendResetLink()`

**Validation:**
- Email is required
- Email must be valid format
- Email must exist in users table

**Logic:**
1. Find user by email
2. Check if user account is active (`is_active = true`)
3. Delete any existing reset tokens for this email
4. Generate new random 64-character token
5. Hash the token using SHA-256
6. Store in `password_reset_tokens` table with email and created_at timestamp
7. Send email with reset link containing:
   - Unhashed token (only in the link, not stored)
   - Email address as query parameter
   - Reset link format: `/reset-password/{token}?email={email}`

**Email Contents:**
- Professional HTML template
- 60-minute expiration notice
- Reset button with link
- Security tips
- Plain text copy of link for email clients that don't render HTML

**Response:**
- Redirects back with success message: "We have emailed your password reset link!"
- Returns errors if email doesn't exist or account is inactive

### 3. Show Password Reset Form
**Route:** `GET /reset-password/{token}`  
**Controller Method:** `showResetForm($token)`

**Parameters:**
- `{token}` - URL parameter (unhashed token)
- `email` - Query string parameter from email link

**Validation:**
1. Check if email query parameter exists (required)
2. Look up reset token in database by email
3. Compare provided token with stored hash using `Hash::check()`
4. Check token is not older than 60 minutes
   - If expired: delete token, redirect to forgot-password with error
5. If any validation fails: redirect to login with error message

**Response:**
- Renders reset-password form with:
  - Email field (pre-filled from URL)
  - New password field
  - Confirm password field
  - Hidden token field
  - Submit button

### 4. Reset Password
**Route:** `POST /reset-password`  
**Controller Method:** `resetPassword()`

**Form Input Validation:**
- Email: required, valid format, exists in users table
- Token: required
- Password: required, must satisfy ValidMemberPassword rules
  - 8-12 characters
  - At least one uppercase letter (A-Z)
  - At least one lowercase letter (a-z)
  - At least one number (0-9)
  - At least one special character (!@#$%^&*()_+-=[]{}...etc)
- Password confirmation: required, must match password field

**Logic:**
1. Validate all form inputs
2. Verify token exists in database for provided email
3. Use `Hash::check()` to verify token matches stored hash
4. Check token is not older than 60 minutes
   - If expired: delete token, return error
5. Find user by email
6. Update user's password:
   - Hash new password using `Hash::make()`
   - Update password column in users table
7. Delete used reset token from database
8. Redirect to login with success message

**Error Handling:**
- Invalid/expired token: "Invalid password reset token"
- Expired token (>60 mins): "This password reset link has expired"
- Email not found: "User not found"
- Password validation fails: Specific error for each requirement
- Password mismatch: "Password confirmation does not match"

---

## Security Features

### 1. Token Security
- Tokens are random 64-character strings generated by `Str::random(64)`
- Stored as cryptographic hash using `Hash::make()`
- Tokens are compared using `Hash::check()` (constant-time comparison)
- Tokens are deleted after use (one-time use)
- Tokens expire after 60 minutes

### 2. Email Verification
- User must have valid email address
- Email must exist in system
- Only account owner receives reset link
- Email link includes all necessary parameters

### 3. Password Strength
- Enforced using `ValidMemberPassword` rule
- Requires mix of uppercase, lowercase, numbers, and special characters
- Length restriction (8-12 characters)
- Same rules as registration for consistency

### 4. Account Protection
- Only active accounts can reset password
- Failed reset attempts don't expose user existence
- Multiple reset requests delete previous unused tokens
- No sensitive information in error messages

### 5. Rate Limiting (Optional)
- Can be added using Laravel's throttle middleware
- Prevents brute force attacks on reset endpoint
- Example: `Route::post('/forgot-password, ...)->throttle('6,1')` (6 requests per minute)

---

## Email Configuration

### Required Settings
In `.env` file:
```
MAIL_MAILER=smtp
MAIL_HOST=your-mail-server
MAIL_PORT=your-port
MAIL_USERNAME=your-username
MAIL_PASSWORD=your-password
MAIL_FROM_ADDRESS=noreply@coopehhub.com
MAIL_FROM_NAME="COOP Ehub"
```

### Testing Locally
For development, use `log` driver:
```
MAIL_MAILER=log
```
This writes emails to `storage/logs/laravel.log`

### Fallback to Array Driver
For testing without actual SMTP:
```
MAIL_MAILER=array
```
Check `config('mail.array_log')` in tests

---

## Usage Examples

### For Users
1. On login page, click "Forgot Password?"
2. Enter email address
3. Check email (spam folder if needed)
4. Click "Reset Password" button in email
5. Enter new password
6. Click "Reset Password" button
7. Log in with new password

### For Administrators
- No special admin reset password needed (uses same system)
- Change password option available after login in profile
- Admin change password at: `/admin/change-password`

---

## Testing

### Unit Test Example
```php
public function test_forgot_password_sends_email()
{
    Mail::fake();
    
    // Post to forgot-password with valid email
    $response = $this->post('/forgot-password', [
        'email' => 'user@example.com'
    ]);
    
    // Assert email was sent
    Mail::assertSent(ResetPasswordMail::class);
    
    // Assert token exists in database
    $this->assertDatabaseHas('password_reset_tokens', [
        'email' => 'user@example.com'
    ]);
}

public function test_reset_password_with_valid_token()
{
    // Create reset token
    $token = Str::random(64);
    DB::table('password_reset_tokens')->insert([
        'email' => 'user@example.com',
        'token' => Hash::make($token),
        'created_at' => now(),
    ]);
    
    // Post reset with valid token
    $response = $this->post('/reset-password', [
        'email' => 'user@example.com',
        'token' => $token,
        'password' => 'NewPass123!',
        'password_confirmation' => 'NewPass123!'
    ]);
    
    // Assert password was updated
    $user = User::where('email', 'user@example.com')->first();
    $this->assertTrue(Hash::check('NewPass123!', $user->password));
    
    // Assert token was deleted
    $this->assertDatabaseMissing('password_reset_tokens', [
        'email' => 'user@example.com'
    ]);
}
```

---

## Troubleshooting

### Email Not Sending
- Check mail configuration in `.env`
- Verify `MAIL_MAILER` is set to `smtp` or valid driver
- For development, use `log` driver and check `storage/logs/laravel.log`
- Check Laravel queue is running if using async: `php artisan queue:work`

### Token Validation Failures
- Token is case-sensitive (stored as hash)
- URL must include both token and email query parameter
- Token expires after 60 minutes
- Token is deleted after first use (invalid on second attempt)

### Password Reset Fails
- Check password meets all requirements (check form for hints)
- Confirm password must exactly match password field
- Email must exist in system
- Check browser console for validation errors

### User Can't See Reset Form
- Email parameter may be missing from URL
- Token may be expired (>60 minutes old)
- Token may be invalid (malformed or wrong email)
- Check that user account `is_active` is true

### Password Works But User Can't Login
- May need to clear browser cache
- Check that MAIL_MAILER setting is correct
- Verify password was actually updated in database: 
  ```sql
  SELECT id, email, password FROM users WHERE email = 'user@example.com';
  ```

---

## Future Enhancements

1. **Rate Limiting** - Add throttle middleware to prevent abuse
2. **Email Verification** - Verify email before allowing password reset
3. **Security Questions** - Additional verification beyond email
4. **OTP/2FA** - One-time password or two-factor authentication
5. **Account Recovery** - Backup codes for account recovery
6. **Audit Logging** - Track password reset attempts and success
7. **Suspicious Activity** - Detect unusual reset patterns
8. **Custom Token Expiry** - Configurable token lifetime
9. **Multiple Reset Methods** - SMS, security key, etc.

---

## Password Reset Token Table Schema

```sql
CREATE TABLE password_reset_tokens (
    email VARCHAR(255) PRIMARY KEY,
    token VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create index for faster lookups
CREATE INDEX password_reset_tokens_email_idx ON password_reset_tokens(email);
```

---

## Related Documentation

- [Laravel Password Reset](https://laravel.com/docs/11.x/authentication#password-reset-link)
- [Laravel Hashing](https://laravel.com/docs/11.x/hashing)
- [Laravel Mailable](https://laravel.com/docs/11.x/mail)
- [COOP Ehub Authentication](./CONTROLLER_IMPLEMENTATION.md)

---

## Summary

The forgot password system provides a secure, user-friendly way for users to reset their passwords through email verification. It uses:
- Secure random token generation
- Cryptographic hashing for token storage
- Time-limited tokens (60 minutes)
- Strong password requirements
- Professional email templates
- Complete error handling and validation
- One-time use tokens (delete after use)

All security best practices are implemented to protect user accounts while maintaining usability.
