@extends('layouts.app')

@section('title', 'Change Password')
@section('subtitle', 'Update Your Account Password')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-6 col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-lock"></i> Change Password
                    </h5>
                </div>
                <div class="card-body">
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

                    <form method="POST" action="{{ route('admin.change-password.update') }}" id="changePasswordForm">
                        @csrf

                        <!-- Current Password Field -->
                        <div class="mb-3">
                            <label for="current_password" class="form-label">
                                <i class="fas fa-key"></i> Current Password <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <input type="password" 
                                       class="form-control @error('current_password') is-invalid @enderror" 
                                       id="current_password" 
                                       name="current_password" 
                                       required 
                                       placeholder="Enter your current password">
                                <button class="btn btn-outline-secondary toggle-password" 
                                        type="button" 
                                        data-target="current_password">
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
                            <label for="new_password" class="form-label">
                                <i class="fas fa-lock"></i> New Password <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <input type="password" 
                                       class="form-control @error('new_password') is-invalid @enderror" 
                                       id="new_password" 
                                       name="new_password" 
                                       required 
                                       placeholder="Enter your new password">
                                <button class="btn btn-outline-secondary toggle-password" 
                                        type="button" 
                                        data-target="new_password">
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
                            <label for="new_password_confirmation" class="form-label">
                                <i class="fas fa-lock"></i> Confirm New Password <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <input type="password" 
                                       class="form-control @error('new_password_confirmation') is-invalid @enderror" 
                                       id="new_password_confirmation" 
                                       name="new_password_confirmation" 
                                       required 
                                       placeholder="Confirm your new password">
                                <button class="btn btn-outline-secondary toggle-password" 
                                        type="button" 
                                        data-target="new_password_confirmation">
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
                        <div class="alert alert-info mb-4">
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
                            <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Change Password
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Security Tips -->
            <div class="card mt-4">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="fas fa-shield-alt"></i> Security Tips
                    </h6>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2">
                            <i class="fas fa-check text-success"></i>
                            <strong>Use a strong password</strong> - Combine uppercase, lowercase, numbers, and special characters.
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check text-success"></i>
                            <strong>Unique password</strong> - Don't use the same password as other accounts.
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check text-success"></i>
                            <strong>Change regularly</strong> - Update your password periodically for security.
                        </li>
                        <li>
                            <i class="fas fa-check text-success"></i>
                            <strong>Keep it private</strong> - Never share your password with anyone, including staff.
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Toggle password visibility
        document.querySelectorAll('.toggle-password').forEach(button => {
            button.addEventListener('click', function() {
                const targetId = this.getAttribute('data-target');
                const input = document.getElementById(targetId);
                const icon = this.querySelector('i');

                if (input.type === 'password') {
                    input.type = 'text';
                    icon.classList.remove('fa-eye');
                    icon.classList.add('fa-eye-slash');
                } else {
                    input.type = 'password';
                    icon.classList.remove('fa-eye-slash');
                    icon.classList.add('fa-eye');
                }
            });
        });

        // Form validation
        const form = document.getElementById('changePasswordForm');
        form.addEventListener('submit', function(e) {
            const currentPassword = document.getElementById('current_password').value;
            const newPassword = document.getElementById('new_password').value;
            const confirmPassword = document.getElementById('new_password_confirmation').value;

            if (currentPassword === newPassword) {
                e.preventDefault();
                alert('New password must be different from your current password.');
                return;
            }

            if (newPassword !== confirmPassword) {
                e.preventDefault();
                alert('Passwords do not match.');
                return;
            }
        });
    });
</script>

<style>
    .input-group .toggle-password {
        border: 1px solid #dee2e6;
        padding: 0.375rem 0.75rem;
    }

    .input-group .toggle-password:hover {
        background-color: #f8f9fa;
    }

    .invalid-feedback {
        display: block;
        color: #dc3545;
        font-size: 0.875em;
        margin-top: 0.25rem;
    }

    .alert-info {
        background-color: #e7f3ff;
        border-color: #b3d9ff;
        color: #004085;
    }

    .alert-info strong {
        color: #002752;
    }
</style>
@endsection
