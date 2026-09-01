@extends('layouts.app')

@section('title', 'Member Profile')
@section('subtitle', 'Member details and loan information')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <!-- Member Info Card -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Member Information</h5>
                </div>
                <div class="card-body">
                    <div class="text-center mb-4">
                        <div style="width: 100px; height: 100px; background-color: #0d6efd; border-radius: 50%; margin: 0 auto; display: flex; align-items: center; justify-content: center; color: white; font-size: 3rem; font-weight: 600;">
                            {{ strtoupper(substr($member->first_name, 0, 1) . substr($member->last_name, 0, 1)) }}
                        </div>
                    </div>
                    <div class="mb-3 pb-3 border-bottom">
                        <h5 class="mb-1">{{ $member->full_name }}</h5>
                        <p class="text-muted mb-0">{{ $member->member_id }}</p>
                    </div>
                    <div class="mb-2">
                        <small class="text-muted">Email</small>
                        <div>{{ $member->user?->email ?? 'N/A' }}</div>
                    </div>
                    <div class="mb-2">
                        <small class="text-muted">Phone</small>
                        <div>{{ $member->phone ?? 'N/A' }}</div>
                    </div>
                    <div class="mb-2">
                        <small class="text-muted">Member Since</small>
                        <div>{{ $member->created_at->format('F d, Y') ?? 'N/A' }}</div>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted">Status</small>
                        <div><span class="badge status-active">Active</span></div>
                    </div>
                    <div class="d-grid gap-2">
                        <a href="{{ route('members.edit', $member->id) }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-edit"></i> Edit Information
                        </a>
                        @if(!$member->user_id)
                            <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#createAccountModal">
                                <i class="fas fa-user-plus"></i> Create Account
                            </button>
                        @else
                            <div class="alert alert-info mb-0" style="padding: 0.5rem; font-size: 0.85rem;">
                                <i class="fas fa-check-circle"></i> Account created: {{ $member->user?->email }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Member Details -->
        <div class="col-md-8">
            <!-- Personal & Account Info -->
            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="mb-0">Personal & Account Details</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <small class="text-muted">Full Name</small>
                            <div>{{ $member->full_name }}</div>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted">Date of Birth</small>
                            <div>{{ $member->date_of_birth ? $member->date_of_birth->format('F d, Y') : 'N/A' }}</div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <small class="text-muted">Relationship Status</small>
                            <div>{{ $member->relationship_status ?? 'N/A' }}</div>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted">Employment Status</small>
                            <div>{{ $member->employment_status ?? 'N/A' }}</div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <small class="text-muted">Monthly Income</small>
                            <div>₱{{ number_format($member->monthly_income ?? 0, 2) }}</div>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted">Member Type</small>
                            <div>{{ $member->member_type ?? 'Individual' }}</div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <small class="text-muted">Address</small>
                            <div>{{ $member->address ?? 'N/A' }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Emergency Contact -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Emergency Contact</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <small class="text-muted">Contact Name</small>
                            <div>{{ $member->emergency_contact_name ?? 'N/A' }}</div>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted">Phone</small>
                            <div>{{ $member->emergency_contact_phone ?? 'N/A' }}</div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <small class="text-muted">Relationship</small>
                            <div>{{ $member->emergency_contact_relation ?? 'N/A' }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Loan Information -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Active Loans</h5>
                    <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#newLoanModal">
                        <i class="fas fa-plus"></i> New Loan
                    </button>
                </div>
                <div class="card-body">
                    @if($loans && count($loans) > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Loan #</th>
                                        <th>Principal Amount</th>
                                        <th>Interest Rate</th>
                                        <th>Current Balance</th>
                                        <th>Monthly Payment</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($loans as $loan)
                                        <tr>
                                            <td><strong>{{ $loan->number }}</strong></td>
                                            <td>₱{{ number_format($loan->principal, 2) }}</td>
                                            <td>{{ $loan->rate }}% p.a.</td>
                                            <td>₱{{ number_format($loan->balance, 2) }}</td>
                                            <td>₱{{ number_format($loan->payment, 2) }}</td>
                                            <td><span class="badge status-{{ $loan->status === 'active' ? 'active' : 'secondary' }}">{{ ucfirst($loan->status) }}</span></td>
                                            <td>
                                                <a href="{{ route('loan-portfolio.index') }}" class="btn btn-sm btn-outline-primary">Details</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <p class="text-muted">No active loans for this member</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Payment History -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Payment History (Last 10 Payments)</h5>
                </div>
                <div class="card-body">
                    @if($payments && count($payments) > 0)
                        <div class="table-responsive">
                            <table class="table table-sm table-hover">
                                <thead style="background-color: #f8f9fa;">
                                    <tr>
                                        <th style="color: #212529;">Date</th>
                                        <th style="color: #212529;">Type</th>
                                        <th style="color: #212529;">Principal</th>
                                        <th style="color: #212529;">Interest</th>
                                        <th style="color: #212529;">Total Amount</th>
                                        <th style="color: #212529;">Reference</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($payments as $payment)
                                        <tr>
                                            <td>{{ $payment->date }}</td>
                                            <td>{{ $payment->type }}</td>
                                            <td class="amount-positive">₱{{ number_format($payment->principal, 2) }}</td>
                                            <td class="amount-positive">₱{{ number_format($payment->interest, 2) }}</td>
                                            <td class="text-primary"><strong>₱{{ number_format($payment->total, 2) }}</strong></td>
                                            <td>{{ $payment->reference }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <p class="text-muted">No payment history for this member</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- New Loan Modal -->
<div class="modal fade" id="newLoanModal" tabindex="-1" aria-labelledby="newLoanModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="newLoanModalLabel">
                    <i class="fas fa-plus"></i> Create New Loan for {{ $member->full_name }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('members.storeLoan', $member->id) }}" method="POST" id="newLoanForm">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="loan_type" class="form-label">Loan Type <span class="text-danger">*</span></label>
                        <select class="form-select @error('loan_type') is-invalid @enderror" id="loan_type" name="loan_type" required>
                            <option value="">-- Select Loan Type --</option>
                            <option value="cash">Cash</option>
                            <option value="swine">Swine</option>
                            <option value="goat">Goat</option>
                            <option value="fertilizers">Fertilizers</option>
                        </select>
                        @error('loan_type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="principal_amount" class="form-label">Principal Amount (₱) <span class="text-danger">*</span></label>
                        <input type="number" class="form-control @error('principal_amount') is-invalid @enderror" id="principal_amount" name="principal_amount" placeholder="50000" step="0.01" min="100" required>
                        @error('principal_amount')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="interest_rate" class="form-label">Interest Rate (%) <span class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('interest_rate') is-invalid @enderror" id="interest_rate" name="interest_rate" placeholder="12" step="0.01" min="0" max="100" required>
                                @error('interest_rate')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="term_months" class="form-label">Term (Months) <span class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('term_months') is-invalid @enderror" id="term_months" name="term_months" placeholder="12" min="1" max="60" required>
                                @error('term_months')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="alert alert-info" role="alert">
                        <small><strong>Monthly Payment Preview:</strong> <span id="monthlyPaymentPreview">₱0.00</span></small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-check"></i> Create Loan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Calculate monthly payment preview
    function calculateMonthlyPayment() {
        const principal = parseFloat(document.getElementById('principal_amount').value) || 0;
        const interestRate = parseFloat(document.getElementById('interest_rate').value) || 0;
        const termMonths = parseInt(document.getElementById('term_months').value) || 0;

        if (principal > 0 && termMonths > 0) {
            const monthlyRate = interestRate / 100 / 12;
            let monthlyPayment;

            if (monthlyRate === 0) {
                monthlyPayment = principal / termMonths;
            } else {
                const numerator = principal * monthlyRate * Math.pow(1 + monthlyRate, termMonths);
                const denominator = Math.pow(1 + monthlyRate, termMonths) - 1;
                monthlyPayment = numerator / denominator;
            }

            document.getElementById('monthlyPaymentPreview').textContent = '₱' + monthlyPayment.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
        } else {
            document.getElementById('monthlyPaymentPreview').textContent = '₱0.00';
        }
    }

    // Add event listeners for real-time calculation
    document.getElementById('principal_amount').addEventListener('input', calculateMonthlyPayment);
    document.getElementById('interest_rate').addEventListener('input', calculateMonthlyPayment);
    document.getElementById('term_months').addEventListener('input', calculateMonthlyPayment);

    // Reset form when modal is closed
    document.getElementById('newLoanModal').addEventListener('hidden.bs.modal', function() {
        document.getElementById('newLoanForm').reset();
        calculateMonthlyPayment();
    });
</script>

@endsection
