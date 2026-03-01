@extends('layouts.app')

@section('title', 'Edit Member')
@section('subtitle', 'Update member information')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-8 offset-lg-2">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Edit Member Information</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('members.update', $member->id ?? 1) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <!-- Personal Information -->
                        <h6 class="mb-3" style="color: #0d6efd; font-weight: 600;">Personal Information</h6>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">First Name *</label>
                                <input type="text" name="first_name" class="form-control @error('first_name') is-invalid @enderror" 
                                       value="{{ old('first_name', 'John') }}" required>
                                @error('first_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Last Name *</label>
                                <input type="text" name="last_name" class="form-control @error('last_name') is-invalid @enderror" 
                                       value="{{ old('last_name', 'Smith') }}" required>
                                @error('last_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Email Address *</label>
                                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" 
                                       value="{{ old('email', 'john@example.com') }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Phone Number *</label>
                                <input type="tel" name="phone" class="form-control @error('phone') is-invalid @enderror" 
                                       value="{{ old('phone', '(+63) 912-345-6789') }}" required>
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Address *</label>
                            <textarea name="address" class="form-control @error('address') is-invalid @enderror" 
                                      rows="3" required>{{ old('address', '123 Main Street, Barangay, City') }}</textarea>
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
                                       value="{{ old('date_of_birth', '1990-05-15') }}" required>
                                @error('date_of_birth')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Member Type *</label>
                                <select name="member_type" class="form-select @error('member_type') is-invalid @enderror" required>
                                    <option value="individual" selected>Individual</option>
                                    <option value="organization">Organization</option>
                                </select>
                                @error('member_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Relationship Status *</label>
                                <select name="relationship_status" class="form-select @error('relationship_status') is-invalid @enderror" required>
                                    <option value="single">Single</option>
                                    <option value="married" selected>Married</option>
                                    <option value="widowed">Widowed</option>
                                    <option value="separated">Separated</option>
                                </select>
                                @error('relationship_status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Employment Status *</label>
                                <select name="employment_status" class="form-select @error('employment_status') is-invalid @enderror" required>
                                    <option value="employed" selected>Employed</option>
                                    <option value="self_employed">Self-Employed</option>
                                    <option value="unemployed">Unemployed</option>
                                    <option value="retired">Retired</option>
                                </select>
                                @error('employment_status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Monthly Income (₱) *</label>
                            <input type="number" name="monthly_income" class="form-control @error('monthly_income') is-invalid @enderror" 
                                   value="{{ old('monthly_income', '50000') }}" step="100" min="0" required>
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
                                       value="{{ old('emergency_contact_name', 'Jane Smith') }}" required>
                                @error('emergency_contact_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Contact Phone *</label>
                                <input type="tel" name="emergency_contact_phone" class="form-control @error('emergency_contact_phone') is-invalid @enderror" 
                                       value="{{ old('emergency_contact_phone', '(+63) 923-456-7890') }}" required>
                                @error('emergency_contact_phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Relationship to Member *</label>
                            <input type="text" name="emergency_contact_relationship" class="form-control @error('emergency_contact_relationship') is-invalid @enderror" 
                                   value="{{ old('emergency_contact_relationship', 'Spouse') }}" required>
                            @error('emergency_contact_relationship')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Account Status -->
                        <hr class="my-4">

                        <div class="mb-3">
                            <label class="form-label">Account Status</label>
                            <select name="status" class="form-select">
                                <option value="active" selected>Active</option>
                                <option value="inactive">Inactive</option>
                                <option value="suspended">Suspended</option>
                            </select>
                        </div>

                        <!-- Form Actions -->
                        <div class="d-flex gap-2 mt-5">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Save Changes
                            </button>
                            <a href="{{ route('members.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left"></i> Back
                            </a>
                            <button type="button" class="btn btn-outline-danger ms-auto" data-bs-toggle="modal" data-bs-target="#deleteModal">
                                <i class="fas fa-trash"></i> Delete Member
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content border-danger">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">Delete Member</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p><strong>Warning:</strong> This action cannot be undone. All associated data will be deleted.</p>
                <p>Are you sure you want to delete this member?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form action="{{ route('members.destroy', $member->id ?? 1) }}" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete Member</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
