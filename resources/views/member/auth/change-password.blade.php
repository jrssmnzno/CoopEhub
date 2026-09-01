@extends('layouts.member-app')

@section('title', 'Change Password')
@section('subtitle', 'Update Your Account Password')

@section('content')
<div class="container" style="max-width: 600px; margin-top: 3rem; margin-bottom: 5rem;">
    <div class="card" style="border: none; border-radius: 15px; box-shadow: 0 4px 20px rgba(0,0,0,0.1);">
        <div class="card-header" style="background: linear-gradient(135deg, #236cb0 0%, #155cc1 100%); color: white; border-radius: 15px 15px 0 0;">
            <h5 class="mb-0" style="color: white;">
                <i class="fas fa-lock"></i> Change Password
            </h5>
        </div>
        <div class="card-body" style="padding: 2rem;">
            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong><i class="fas fa-exclamation-circle"></i> Errors:</strong>
                    <ul class="mb-0 mt-2">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <form method="POST" action="{{ route('member.change-password.update') }}" id="changePasswordForm">
                @csrf

                <!-- Current Password Field -->
                <div class="mb-3">
                    <label for="current_password" class="form-label" style="font-weight: 600;">
                        <i class="fas fa-key"></i> Current Password <span class="text-danger">*</span>
                    </label>
                    <div class="input-group">
                        <input type="password" 
                               class="form-control @error('current_password') is-invalid @enderror" 
                               id="current_password" 
                               name="current_password" 
                               required 
                               placeholder="Enter your current password"
                               style="border-radius: 8px 0 0 8px;">
                        <button class="btn btn-outline-secondary toggle-password" 
                                type="button" 
                                data-target="current_password"
                                style="border-radius: 0 8px 8px 0;">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                    @error('current_password')
                        <div class="invalid-feedback d-block">
                            <i class="fas fa-times-circle"></i> {{ $message }}
                        </div>
                    @enderror
                    <small class="text-muted d-block mt-1">
                        <i class="fas fa-info-circle"></i> Enter your current password for verification.
                    </small>
                </div>

                <!-- New Password Field -->
                <div class="mb-3">
                    <label for="new_password" class="form-label" style="font-weight: 600;">
                        <i class="fas fa-lock"></i> New Password <span class="text-danger">*</span>
                    </label>
                    <div class="input-group">
                        <input type="password" 
                               class="form-control @error('new_password') is-invalid @enderror" 
                               id="new_password" 
                               name="new_password" 
                               required 
                               placeholder="Enter your new password"
                               style="border-radius: 8px 0 0 8px;">
                        <button class="btn btn-outline-secondary toggle-password" 
                                type="button" 
                                data-target="new_password"
                                style="border-radius: 0 8px 8px 0;">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                    @error('new_password')
                        <div class="invalid-feedback d-block">
                            <i class="fas fa-times-circle"></i> {{ $message }}
                        </div>
                    @enderror
                    <small class="text-muted d-block mt-1">
                        <i class="fas fa-info-circle"></i> Password must contain at least 8 characters, including uppercase, lowercase, numbers, and special characters (@$!%*?&).
                    </small>
                </div>

                <!-- Confirm Password Field -->
                <div class="mb-4">
                    <label for="new_password_confirmation" class="form-label" style="font-weight: 600;">
                        <i class="fas fa-lock"></i> Confirm New Password <span class="text-danger">*</span>
                    </label>
                    <div class="input-group">
                        <input type="password" 
                               class="form-control @error('new_password_confirmation') is-invalid @enderror" 
                               id="new_password_confirmation" 
                               name="new_password_confirmation" 
                               required 
                               placeholder="Confirm your new password"
                               style="border-radius: 8px 0 0 8px;">
                        <button class="btn btn-outline-secondary toggle-password" 
                                type="button" 
                                data-target="new_password_confirmation"
                                style="border-radius: 0 8px 8px 0;">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                    @error('new_password_confirmation')
                        <div class="invalid-feedback d-block">
                            <i class="fas fa-times-circle"></i> {{ $message }}
                        </div>
                    @enderror
                </div>

                <!-- Password Requirements -->
                <div class="alert alert-info mb-4" style="border-radius: 8px;">
                    <small>
                        <strong><i class="fas fa-check-circle"></i> Password Requirements:</strong>
                        <ul class="mb-0 mt-2">
                            <li>Minimum 8 characters</li>
                            <li>At least one uppercase letter (A-Z)</li>
                            <li>At least one lowercase letter (a-z)</li>
                            <li>At least one number (0-9)</li>
                            <li>At least one special character (@$!%*?&)</li>
                            <li>Cannot be the same as your current password</li>
                        </ul>
                    </small>
                </div>

                <!-- Action Buttons -->
                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary" style="border-radius: 8px; padding: 0.75rem 1.5rem;">
                        <i class="fas fa-times"></i> Cancel
                    </a>
                    <button type="submit" class="btn btn-success" style="border-radius: 8px; padding: 0.75rem 1.5rem; background: linear-gradient(135deg, #236cb0 0%, #155cc1 100%); border: none;">
                        <i class="fas fa-save"></i> Change Password
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Security Tips -->
    <div class="card mt-4" style="border: none; border-radius: 15px; box-shadow: 0 4px 20px rgba(0,0,0,0.1);">
        <div class="card-body" style="padding: 2rem;">
            <h6 style="color: #333; margin-bottom: 1rem; font-weight: 600;">
                <i class="fas fa-shield-alt"></i> Security Tips
            </h6>
            <ul style="margin: 0; padding-left: 1.5rem; color: #666; line-height: 1.8;">
                <li>Use a strong password that is unique to this account</li>
                <li>Never share your password with anyone else</li>
                <li>Avoid using easily guessable information like birthdays or names</li>
                <li>Change your password regularly for better security</li>
                <li>If you suspect unauthorized access, change your password immediately</li>
            </ul>
        </div>
    </div>
</div>

<script>
// Toggle password visibility
document.querySelectorAll('.toggle-password').forEach(button => {
    button.addEventListener('click', function() {
        const targetId = this.getAttribute('data-target');
        const targetInput = document.getElementById(targetId);
        const icon = this.querySelector('i');

        if (targetInput.type === 'password') {
            targetInput.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            targetInput.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    });
});
</script>
@endsection
