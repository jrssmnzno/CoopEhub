@extends('layouts.app')

@section('title', 'Add Member')
@section('subtitle', 'Register a new member to the system')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-8 offset-lg-2">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">New Member Registration</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('members.store') }}" method="POST" novalidate id="memberForm">
                        @csrf

                        <!-- Personal Information -->
                        <h6 class="mb-3" style="color: #0d6efd; font-weight: 600;">Personal Information</h6>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">First Name *</label>
                                <input type="text" name="first_name" class="form-control @error('first_name') is-invalid @enderror" 
                                       value="{{ old('first_name') }}" required>
                                @error('first_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Last Name *</label>
                                <input type="text" name="last_name" class="form-control @error('last_name') is-invalid @enderror" 
                                       value="{{ old('last_name') }}" required>
                                @error('last_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Email Address *</label>
                                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" 
                                       value="{{ old('email') }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Phone Number *</label>
                                <input type="tel" name="phone" class="form-control @error('phone') is-invalid @enderror" 
                                       value="{{ old('phone') }}" placeholder="(+63) 9XX-XXX-XXXX" required>
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Address *</label>
                            <textarea name="address" class="form-control @error('address') is-invalid @enderror" 
                                      rows="3" required>{{ old('address') }}</textarea>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <hr class="my-4">

                        <!-- Account Information -->
                        <h6 class="mb-3" style="color: #0d6efd; font-weight: 600;">Account Information</h6>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Date of Birth *</label>
                                <input type="date" name="date_of_birth" class="form-control @error('date_of_birth') is-invalid @enderror" 
                                       value="{{ old('date_of_birth') }}" required>
                                @error('date_of_birth')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Member Type *</label>
                                <select name="member_type" class="form-select @error('member_type') is-invalid @enderror" required>
                                    <option value="">Select member type...</option>
                                    <option value="individual" {{ old('member_type') == 'individual' ? 'selected' : '' }}>Individual</option>
                                    <option value="organization" {{ old('member_type') == 'organization' ? 'selected' : '' }}>Organization</option>
                                </select>
                                @error('member_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Login Credentials -->
                        <h6 class="mb-3" style="color: #0d6efd; font-weight: 600;">Login Credentials <span style="font-size: 0.85rem; color: #6c757d;">(Optional)</span></h6>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label no-asterisk-label" style="color: #6c757d;">Password</label>
                                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" 
                                       autocomplete="new-password">
                                <small class="form-text text-muted">Minimum 8 characters, include uppercase, lowercase, number, and special character. Leave blank if member will create their own account later.</small>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label no-asterisk-label" style="color: #6c757d;">Confirm Password</label>
                                <input type="password" name="password_confirmation" class="form-control @error('password_confirmation') is-invalid @enderror" 
                                       autocomplete="new-password">
                                @error('password_confirmation')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <hr class="my-4">

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Relationship Status *</label>
                                <select name="relationship_status" class="form-select @error('relationship_status') is-invalid @enderror" required>
                                    <option value="">Select...</option>
                                    <option value="single" {{ old('relationship_status') == 'single' ? 'selected' : '' }}>Single</option>
                                    <option value="married" {{ old('relationship_status') == 'married' ? 'selected' : '' }}>Married</option>
                                    <option value="widowed" {{ old('relationship_status') == 'widowed' ? 'selected' : '' }}>Widowed</option>
                                    <option value="separated" {{ old('relationship_status') == 'separated' ? 'selected' : '' }}>Separated</option>
                                </select>
                                @error('relationship_status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Employment Status *</label>
                                <select name="employment_status" class="form-select @error('employment_status') is-invalid @enderror" required>
                                    <option value="">Select...</option>
                                    <option value="employed" {{ old('employment_status') == 'employed' ? 'selected' : '' }}>Employed</option>
                                    <option value="self_employed" {{ old('employment_status') == 'self_employed' ? 'selected' : '' }}>Self-Employed</option>
                                    <option value="unemployed" {{ old('employment_status') == 'unemployed' ? 'selected' : '' }}>Unemployed</option>
                                    <option value="retired" {{ old('employment_status') == 'retired' ? 'selected' : '' }}>Retired</option>
                                </select>
                                @error('employment_status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Monthly Income (₱) *</label>
                            <input type="number" name="monthly_income" class="form-control @error('monthly_income') is-invalid @enderror" 
                                   value="{{ old('monthly_income') }}" step="100" min="0" required>
                            @error('monthly_income')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Emergency Contact -->
                        <hr class="my-4">

                        <h6 class="mb-3" style="color: #0d6efd; font-weight: 600;">Emergency Contact</h6>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Contact Name *</label>
                                <input type="text" name="emergency_contact_name" class="form-control @error('emergency_contact_name') is-invalid @enderror" 
                                       value="{{ old('emergency_contact_name') }}" required>
                                @error('emergency_contact_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Contact Phone *</label>
                                <input type="tel" name="emergency_contact_phone" class="form-control @error('emergency_contact_phone') is-invalid @enderror" 
                                       value="{{ old('emergency_contact_phone') }}" required>
                                @error('emergency_contact_phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Relationship to Member *</label>
                            <input type="text" name="emergency_contact_relationship" class="form-control @error('emergency_contact_relationship') is-invalid @enderror" 
                                   value="{{ old('emergency_contact_relationship') }}" required>
                            @error('emergency_contact_relationship')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Form Actions -->
                        <div class="d-flex gap-2 mt-5">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Register Member
                            </button>
                            <a href="{{ route('members.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left"></i> Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('memberForm').addEventListener('submit', function(e) {
    const passwordField = document.querySelector('input[name="password"]');
    const confirmPasswordField = document.querySelector('input[name="password_confirmation"]');
    
    // Only validate password if one field has content
    if (passwordField.value.trim() || confirmPasswordField.value.trim()) {
        // If one is filled, both must be filled and match
        if (!passwordField.value.trim()) {
            e.preventDefault();
            passwordField.focus();
            alert('Please enter a password');
            return false;
        }
        if (!confirmPasswordField.value.trim()) {
            e.preventDefault();
            confirmPasswordField.focus();
            alert('Please confirm your password');
            return false;
        }
        if (passwordField.value !== confirmPasswordField.value) {
            e.preventDefault();
            passwordField.focus();
            alert('Passwords do not match');
            return false;
        }
    }
    // If both are empty, form will submit without account creation
});
</script>
@endsection

<style>
/* Hide asterisks from optional password label fields */
.no-asterisk-label::after {
    content: "" !important;
    display: none !important;
}

.no-asterisk-label {
    position: relative;
}

.no-asterisk-label::after {
    all: unset;
}
</style>
