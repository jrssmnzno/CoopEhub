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

                        <!-- Personal Information Section -->
                        <div class="mb-5">
                            <div class="d-flex align-items-center mb-4">
                                <div style="width: 4px; height: 24px; background: linear-gradient(135deg, var(--ss-primary) 0%, var(--ss-primary-light) 100%); border-radius: 2px; margin-right: 12px;"></div>
                                <h6 class="mb-0" style="color: var(--ss-primary); font-weight: 700; font-size: 1.05rem; text-transform: uppercase; letter-spacing: 0.5px;">Personal Information</h6>
                            </div>
                            
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">
                                            <i class="fas fa-user-circle me-2"></i>First Name
                                            <span class="required">*</span>
                                        </label>
                                        <input type="text" name="first_name" class="form-control @error('first_name') is-invalid @enderror" 
                                               value="{{ old('first_name', $member->first_name) }}" placeholder="Enter first name" required>
                                        @error('first_name')
                                            <div class="invalid-feedback"><i class="fas fa-exclamation-circle me-1"></i>{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">
                                            <i class="fas fa-user-circle me-2"></i>Last Name
                                            <span class="required">*</span>
                                        </label>
                                        <input type="text" name="last_name" class="form-control @error('last_name') is-invalid @enderror" 
                                               value="{{ old('last_name', $member->last_name) }}" placeholder="Enter last name" required>
                                        @error('last_name')
                                            <div class="invalid-feedback"><i class="fas fa-exclamation-circle me-1"></i>{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">
                                            <i class="fas fa-envelope me-2"></i>Email Address
                                            <span class="required">*</span>
                                        </label>
                                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" 
                                               value="{{ old('email', $member->email) }}" placeholder="Enter email address" required>
                                        @error('email')
                                            <div class="invalid-feedback"><i class="fas fa-exclamation-circle me-1"></i>{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">
                                            <i class="fas fa-phone me-2"></i>Phone Number
                                            <span class="required">*</span>
                                        </label>
                                        <input type="tel" name="phone" class="form-control @error('phone') is-invalid @enderror" 
                                               value="{{ old('phone', $member->phone) }}" placeholder="Enter phone number" required>
                                        @error('phone')
                                            <div class="invalid-feedback"><i class="fas fa-exclamation-circle me-1"></i>{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-map-marker-alt me-2"></i>Address
                                    <span class="required">*</span>
                                </label>
                                <textarea name="address" class="form-control @error('address') is-invalid @enderror" 
                                          rows="3" placeholder="Enter complete address" required>{{ old('address', $member->address) }}</textarea>
                                @error('address')
                                    <div class="invalid-feedback"><i class="fas fa-exclamation-circle me-1"></i>{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Divider -->
                        <div style="border-top: 2px solid var(--ss-border-color); margin: 3rem 0;"></div>

                        <!-- Account Information Section -->
                        <div class="mb-5">
                            <div class="d-flex align-items-center mb-4">
                                <div style="width: 4px; height: 24px; background: linear-gradient(135deg, var(--ss-success) 0%, #34d399 100%); border-radius: 2px; margin-right: 12px;"></div>
                                <h6 class="mb-0" style="color: var(--ss-success); font-weight: 700; font-size: 1.05rem; text-transform: uppercase; letter-spacing: 0.5px;">Account Information</h6>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">
                                            <i class="fas fa-birthday-cake me-2"></i>Date of Birth
                                            <span class="required">*</span>
                                        </label>
                                        <input type="date" name="date_of_birth" class="form-control @error('date_of_birth') is-invalid @enderror" 
                                               value="{{ old('date_of_birth', $member->date_of_birth) }}" required>
                                        @error('date_of_birth')
                                            <div class="invalid-feedback"><i class="fas fa-exclamation-circle me-1"></i>{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">
                                            <i class="fas fa-sitemap me-2"></i>Member Type
                                            <span class="required">*</span>
                                        </label>
                                        <select name="member_type" class="form-select @error('member_type') is-invalid @enderror" required>
                                            <option value="">-- Select Type --</option>
                                            <option value="individual" {{ old('member_type', $member->member_type) === 'individual' ? 'selected' : '' }}>Individual</option>
                                            <option value="organization" {{ old('member_type', $member->member_type) === 'organization' ? 'selected' : '' }}>Organization</option>
                                        </select>
                                        @error('member_type')
                                            <div class="invalid-feedback"><i class="fas fa-exclamation-circle me-1"></i>{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">
                                            <i class="fas fa-heart me-2"></i>Relationship Status
                                            <span class="required">*</span>
                                        </label>
                                        <select name="relationship_status" class="form-select @error('relationship_status') is-invalid @enderror" required>
                                            <option value="">-- Select Status --</option>
                                            <option value="single" {{ old('relationship_status', $member->relationship_status) === 'single' ? 'selected' : '' }}>Single</option>
                                            <option value="married" {{ old('relationship_status', $member->relationship_status) === 'married' ? 'selected' : '' }}>Married</option>
                                    <option value="widowed" {{ old('relationship_status', $member->relationship_status) === 'widowed' ? 'selected' : '' }}>Widowed</option>
                                    <option value="separated" {{ old('relationship_status', $member->relationship_status) === 'separated' ? 'selected' : '' }}>Separated</option>
                                </select>
                                @error('relationship_status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    <div class="invalid-feedback"><i class="fas fa-exclamation-circle me-1"></i>{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">
                                        <i class="fas fa-briefcase me-2"></i>Employment Status
                                        <span class="required">*</span>
                                    </label>
                                    <select name="employment_status" class="form-select @error('employment_status') is-invalid @enderror" required>
                                        <option value="">-- Select Status --</option>
                                        <option value="employed" {{ old('employment_status', $member->employment_status) === 'employed' ? 'selected' : '' }}>Employed</option>
                                        <option value="self_employed" {{ old('employment_status', $member->employment_status) === 'self_employed' ? 'selected' : '' }}>Self-Employed</option>
                                        <option value="unemployed" {{ old('employment_status', $member->employment_status) === 'unemployed' ? 'selected' : '' }}>Unemployed</option>
                                        <option value="retired" {{ old('employment_status', $member->employment_status) === 'retired' ? 'selected' : '' }}>Retired</option>
                                    </select>
                                    @error('employment_status')
                                        <div class="invalid-feedback"><i class="fas fa-exclamation-circle me-1"></i>{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-money-bill me-2"></i>Monthly Income (₱)
                                <span class="required">*</span>
                            </label>
                            <input type="number" name="monthly_income" class="form-control @error('monthly_income') is-invalid @enderror" 
                                   value="{{ old('monthly_income', $member->monthly_income) }}" placeholder="Enter monthly income" step="100" min="0" required>
                            @error('monthly_income')
                                <div class="invalid-feedback"><i class="fas fa-exclamation-circle me-1"></i>{{ $message }}</div>
                            @enderror
                        </div>
                        </div>

                        <!-- Divider -->
                        <div style="border-top: 2px solid var(--ss-border-color); margin: 3rem 0;"></div>

                        <!-- Emergency Contact Section -->
                        <div class="mb-5">
                            <div class="d-flex align-items-center mb-4">
                                <div style="width: 4px; height: 24px; background: linear-gradient(135deg, var(--ss-warning) 0%, #fbbf24 100%); border-radius: 2px; margin-right: 12px;"></div>
                                <h6 class="mb-0" style="color: var(--ss-warning); font-weight: 700; font-size: 1.05rem; text-transform: uppercase; letter-spacing: 0.5px;">Emergency Contact</h6>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">
                                            <i class="fas fa-user-alt me-2"></i>Contact Name
                                            <span class="required">*</span>
                                        </label>
                                        <input type="text" name="emergency_contact_name" class="form-control @error('emergency_contact_name') is-invalid @enderror" 
                                               value="{{ old('emergency_contact_name', $member->emergency_contact_name) }}" placeholder="Enter contact name" required>
                                        @error('emergency_contact_name')
                                            <div class="invalid-feedback"><i class="fas fa-exclamation-circle me-1"></i>{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">
                                            <i class="fas fa-phone me-2"></i>Contact Phone
                                            <span class="required">*</span>
                                        </label>
                                        <input type="tel" name="emergency_contact_phone" class="form-control @error('emergency_contact_phone') is-invalid @enderror" 
                                               value="{{ old('emergency_contact_phone', $member->emergency_contact_phone) }}" placeholder="Enter phone number" required>
                                        @error('emergency_contact_phone')
                                            <div class="invalid-feedback"><i class="fas fa-exclamation-circle me-1"></i>{{ $message }}</div>
                                        @enderror
                            </div>

                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-link me-2"></i>Relationship to Member
                                    <span class="required">*</span>
                                </label>
                                <input type="text" name="emergency_contact_relationship" class="form-control @error('emergency_contact_relationship') is-invalid @enderror" 
                                       value="{{ old('emergency_contact_relationship', $member->emergency_contact_relation) }}" placeholder="e.g., Spouse, Parent, Sibling" required>
                                @error('emergency_contact_relationship')
                                    <div class="invalid-feedback"><i class="fas fa-exclamation-circle me-1"></i>{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Divider -->
                        <div style="border-top: 2px solid var(--ss-border-color); margin: 3rem 0;"></div>

                        <!-- Account Status Section -->
                        <div class="mb-5">
                            <div class="d-flex align-items-center mb-4">
                                <div style="width: 4px; height: 24px; background: linear-gradient(135deg, var(--ss-info) 0%, #22d3ee 100%); border-radius: 2px; margin-right: 12px;"></div>
                                <h6 class="mb-0" style="color: var(--ss-info); font-weight: 700; font-size: 1.05rem; text-transform: uppercase; letter-spacing: 0.5px;">Account Status</h6>
                            </div>

                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-toggle-on me-2"></i>Status
                                </label>
                                <select name="status" class="form-select">
                                    <option value="active" {{ old('status', $member->status) === 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="inactive" {{ old('status', $member->status) === 'inactive' ? 'selected' : '' }}>Inactive</option>
                                    <option value="suspended" {{ old('status', $member->status) === 'suspended' ? 'selected' : '' }}>Suspended</option>
                                </select>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="mt-5 pt-4 border-top" style="border-top-color: var(--ss-border-color) !important;">
                            <div class="d-flex gap-3 justify-content-between flex-wrap">
                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-check me-2"></i>Save Changes
                                    </button>
                                    <a href="{{ route('members.index') }}" class="btn btn-outline-secondary">
                                        <i class="fas fa-arrow-left me-2"></i>Back to List
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
