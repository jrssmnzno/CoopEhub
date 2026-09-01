@extends('layouts.member-app')

@section('title', 'Member Dashboard')
@section('subtitle', 'Member ID: ' . ($member->member_id ?? 'N/A') . ' • Status: ' . ucfirst($member->status ?? 'pending'))

@section('content')

<div class="member-dashboard" style="background: linear-gradient(135deg, #f0f9ff 0%, #e0f7ff 100%); min-height: 100vh; padding: 2rem 0;">
    <div class="container" style="animation: fadeInUp calc(var(--ss-animation-duration) * 1.1) ease-out;">
        <!-- Member Profile Section -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="profile-card" style="padding: 2.5rem;">
                    <div class="row align-items-center gap-3 gap-md-0">
                        <!-- Profile Avatar -->
                        <div class="col-md-auto mb-3 mb-md-0 d-flex justify-content-center justify-content-md-start">
                            <div class="profile-avatar">
                                <i class="fas fa-user"></i>
                            </div>
                        </div>
                        
                        <!-- Profile Info -->
                        <div class="col-md flex-grow-1">
                            <h3 class="text-heading-lg mb-2" style="color: white;">{{ Auth::user()->name }}</h3>
                            <p class="mb-3" style="opacity: 0.9; font-size: 1.05rem; color: white;">Member ID: <strong>#{{ $member->member_id }}</strong></p>
                            <div class="profile-info-section" style="flex-direction: row; flex-wrap: wrap; gap: 2rem;">
                                <div class="profile-info-item">
                                    <span class="profile-info-label" style="color: rgba(255,255,255,0.85);">Status</span>
                                    <p style="margin: 0.5rem 0 0; font-weight: 600; font-size: 1rem;">
                                        @if($member->status === 'active')
                                            <span class="status-badge active">
                                                <i class="fas fa-check-circle"></i> Active
                                            </span>
                                        @elseif($member->status === 'inactive')
                                            <span class="status-badge inactive">
                                                <i class="fas fa-times-circle"></i> Inactive
                                            </span>
                                        @else
                                            <span class="status-badge pending" style="background: rgba(255,193,7,0.2); color: #fff;">
                                                <i class="fas fa-clock"></i> Pending
                                            </span>
                                        @endif
                                    </p>
                                </div>
                                <div class="profile-info-item">
                                    <span class="profile-info-label" style="color: rgba(255,255,255,0.85);">Joined</span>
                                    <p style="margin: 0.5rem 0 0; font-weight: 600; color: white;">{{ $member->created_at->format('M d, Y') }}</p>
                                </div>
                                <div class="profile-info-item">
                                    <span class="profile-info-label" style="color: rgba(255,255,255,0.85);">Email</span>
                                    <p style="margin: 0.5rem 0 0; font-weight: 600; color: white;">{{ Auth::user()->email }}</p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Change Password Button -->
                        <div class="col-md-auto">
                            <a href="{{ route('member.change-password.show') }}" class="btn btn-light" style="border-radius: 8px; padding: 0.75rem 1.5rem; font-weight: 600; color: #00a86b;">
                                <i class="fas fa-key me-2"></i>Change Password
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Upcoming Meeting Announcement -->
        @include('components.upcoming-meeting')

        <!-- Key Metrics Row -->
        <div class="row mb-4 g-3">
            <!-- Total Outstanding Balance -->
            <div class="col-md-6 col-lg-3">
                <div class="metric-card" style="background: linear-gradient(135deg, #00a86b 0%, #008b5e 100%);">
                    <div class="card-body" style="padding: 1.5rem;">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="metric-label">Outstanding Balance</p>
                                <h2 class="metric-value">₱{{ number_format($totalOutstanding, 2) }}</h2>
                                <p class="metric-description">{{ $activeLoans->count() }} active loan(s)</p>
                            </div>
                            <div class="metric-icon">
                                <i class="fas fa-wallet"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Principal Paid -->
            <div class="col-md-6 col-lg-3">
                <div class="metric-card" style="background: linear-gradient(135deg, #4a90e2 0%, #357abd 100%);">
                    <div class="card-body" style="padding: 1.5rem;">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="metric-label">Principal Paid</p>
                                <h2 class="metric-value">₱{{ number_format($principalPaid, 2) }}</h2>
                                <p class="metric-description">Total repaid</p>
                            </div>
                            <div class="metric-icon">
                                <i class="fas fa-check-circle"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Interest Paid -->
            <div class="col-md-6 col-lg-3">
                <div class="metric-card" style="background: linear-gradient(135deg, #f5a623 0%, #d68910 100%);">
                    <div class="card-body" style="padding: 1.5rem;">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="metric-label">Interest Paid</p>
                                <h2 class="metric-value">₱{{ number_format($interestPaid, 2) }}</h2>
                                <p class="metric-description">Total interest</p>
                            </div>
                            <div class="metric-icon">
                                <i class="fas fa-chart-pie"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Remaining Principal -->
            <div class="col-md-6 col-lg-3">
                <div class="metric-card" style="background: linear-gradient(135deg, #e94b3c 0%, #c62f20 100%);">
                    <div class="card-body" style="padding: 1.5rem;">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="metric-label">Remaining Principal</p>
                                <h2 class="metric-value">₱{{ number_format($remainingPrincipal, 2) }}</h2>
                                <p class="metric-description">To be repaid</p>
                            </div>
                            <div class="metric-icon">
                                <i class="fas fa-chart-line"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-3 mb-4">
            <!-- Active Loans -->
            <div class="col-lg-6">
                <div class="dashboard-card elevated">
                    <div class="card-header">
                        <h5 class="section-title">
                            <i class="fas fa-credit-card icon-animate"></i> Active Loans
                        </h5>
                    </div>
                    <div class="card-body" style="padding: 1.5rem;">
                        @if($activeLoans->count() > 0)
                            @foreach($activeLoans as $loan)
                                <div class="loan-card">
                                    <div class="loan-header">
                                        <div>
                                            <h6 class="loan-number">#{{ $loan->loan_number }}</h6>
                                            <span class="loan-type-badge">{{ ucfirst(str_replace('_', ' ', $loan->loan_type ?? 'personal')) }}</span>
                                        </div>
                                        <span class="status-badge active">
                                            <i class="fas fa-check-circle"></i> {{ ucfirst($loan->status) }}
                                        </span>
                                    </div>
                                    <div class="loan-details">
                                        <div class="loan-detail-item">
                                            <p class="loan-detail-label">Principal</p>
                                            <p class="loan-detail-value">₱{{ number_format($loan->principal_amount, 2) }}</p>
                                        </div>
                                        <div class="loan-detail-item">
                                            <p class="loan-detail-label">Interest Rate</p>
                                            <p class="loan-detail-value">{{ $loan->interest_rate }}%/yr</p>
                                        </div>
                                        <div class="loan-detail-item">
                                            <p class="loan-detail-label">Balance</p>
                                            <p class="loan-detail-value balance">₱{{ number_format($loan->running_balance, 2) }}</p>
                                        </div>
                                        <div class="loan-detail-item">
                                            <p class="loan-detail-label">Monthly Payment</p>
                                            <p class="loan-detail-value">₱{{ number_format($loan->monthly_payment, 2) }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="empty-state">
                                <i class="fas fa-inbox empty-state-icon"></i>
                                <p class="empty-state-text">No active loans</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Pending Loan Requests -->
            <div class="col-lg-6">
                <div class="dashboard-card elevated">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="section-title mb-0">
                            <i class="fas fa-file-contract icon-animate"></i> Loan Requests
                        </h5>
                        <button class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#loanRequestModal" style="border-radius: 20px; font-size: 0.85rem;">
                            <i class="fas fa-plus"></i> New Request
                        </button>
                    </div>
                    <div class="card-body" style="padding: 1.5rem;">
                        @if($pendingRequests->count() > 0)
                            @foreach($pendingRequests as $request)
                                <div class="loan-card pending">
                                    <div class="loan-header">
                                        <div>
                                            <h6 class="loan-number">₱{{ number_format($request->requested_amount, 2) }}</h6>
                                            <p style="margin: 0.25rem 0 0; font-size: 0.85rem; color: #666;">{{ $request->requested_term_months }} months</p>
                                        </div>
                                        <div class="d-flex align-items-center gap-2">
                                            <span class="status-badge pending">
                                                <i class="fas fa-clock"></i> {{ ucfirst($request->status) }}
                                            </span>
                                            <button class="btn btn-sm btn-outline-danger" onclick="cancelLoanRequest({{ $request->id }}, this)" style="border-radius: 4px; padding: 0.25rem 0.5rem; font-size: 0.8rem;">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <p style="margin: 0; font-size: 0.85rem; color: #666; padding-top: 0.5rem;">Requested: {{ $request->created_at->format('M d, Y') }}</p>
                                </div>
                            @endforeach
                        @else
                            <div class="empty-state">
                                <i class="fas fa-inbox empty-state-icon"></i>
                                <p class="empty-state-text mb-3">No pending requests</p>
                                <button class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#loanRequestModal" style="border-radius: 20px;">
                                    <i class="fas fa-plus"></i> Request a Loan
                                </button>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Transactions -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="dashboard-card elevated">
                    <div class="card-header">
                        <h5 class="section-title mb-0">
                            <i class="fas fa-history icon-animate"></i> Recent Transactions
                        </h5>
                    </div>
                    <div class="card-body" style="padding: 1.5rem;">
                        @if($recentTransactions->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover mb-0 transaction-table">
                                    <thead style="background-color: #f8f9fa;">
                                        <tr>
                                            <th style="color: white; font-weight: 600; font-size: 0.9rem; border: none; background-color: #004a8f;">Date</th>
                                            <th style="color: white; font-weight: 600; font-size: 0.9rem; border: none; background-color: #004a8f;">Type</th>
                                            <th style="color: white; font-weight: 600; font-size: 0.9rem; border: none; background-color: #004a8f;">Principal</th>
                                            <th style="color: white; font-weight: 600; font-size: 0.9rem; border: none; background-color: #004a8f;">Interest</th>
                                            <th style="color: white; font-weight: 600; font-size: 0.9rem; border: none; background-color: #004a8f;">Total Amount</th>
                                            <th style="color: white; font-weight: 600; font-size: 0.9rem; border: none; background-color: #004a8f;">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($recentTransactions as $transaction)
                                            <tr>
                                                <td>{{ $transaction->created_at->format('M d, Y') }}</td>
                                                <td>
                                                    <span class="transaction-type-badge">
                                                        <i class="fas fa-exchange-alt"></i> {{ str_replace('_', ' ', ucfirst($transaction->type)) }}
                                                    </span>
                                                </td>
                                                <td>₱{{ number_format($transaction->principal_amount ?? 0, 2) }}</td>
                                                <td>₱{{ number_format($transaction->interest_amount ?? 0, 2) }}</td>
                                                <td class="transaction-amount">₱{{ number_format($transaction->total_amount, 2) }}</td>
                                                <td>
                                                    <span class="transaction-status-badge">
                                                        <i class="fas fa-check"></i> {{ ucfirst($transaction->status ?? 'completed') }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="empty-state">
                                <i class="fas fa-inbox empty-state-icon"></i>
                                <p class="empty-state-text">No transactions yet</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Loan Request Modal -->
<div class="modal fade" id="loanRequestModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="border: none; border-radius: 10px; box-shadow: 0 10px 40px rgba(0,0,0,0.2);">
            <div class="modal-header" style="background: linear-gradient(135deg, #00a86b 0%, #008b5e 100%); color: white; border: none;">
                <h5 class="modal-title" style="font-weight: 600;">
                    <i class="fas fa-credit-card"></i> Request New Loan
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form id="loanRequestForm">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="loanAmount" class="form-label" style="font-weight: 500;">Loan Amount (PHP)</label>
                        <input type="number" class="form-control" id="loanAmount" name="amount" placeholder="Enter loan amount" min="1000" step="100" required style="border-radius: 8px; border: 1px solid #ddd; padding: 0.75rem;">
                        <small class="form-text text-muted">Minimum: ₱1,000</small>
                    </div>

                    <div class="mb-3">
                        <label for="loanType" class="form-label" style="font-weight: 500;">Loan Type</label>
                        <select class="form-control" id="loanType" name="loan_type" required style="border-radius: 8px; border: 1px solid #ddd; padding: 0.75rem;">
                            <option value="">Select loan type...</option>
                            <option value="cash">Cash</option>
                            <option value="swine">Swine</option>
                            <option value="goat">Goat</option>
                            <option value="fertilizers">Fertilizers</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="loanTerm" class="form-label" style="font-weight: 500;">Term (Months)</label>
                        <select class="form-control" id="loanTerm" name="term_months" required style="border-radius: 8px; border: 1px solid #ddd; padding: 0.75rem;">
                            <option value="">Select term...</option>
                            <option value="3">3 Months</option>
                            <option value="6">6 Months</option>
                            <option value="12">12 Months</option>
                            <option value="24">24 Months</option>
                            <option value="36">36 Months</option>
                        </select>
                    </div>

                    <!-- Loan Calculation Preview -->
                    <div id="calculationPreview" style="background: #f9f9f9; padding: 1rem; border-radius: 8px; border-left: 4px solid #00a86b; display: none;">
                        <h6 style="font-weight: 600; margin-bottom: 1rem;">Loan Calculation Preview</h6>
                        <div class="row">
                            <div class="col-6 mb-2">
                                <p style="margin: 0; font-size: 0.9rem; color: #666;">Principal Amount:</p>
                                <p id="preview-principal" style="margin: 0; font-weight: 600; font-size: 1.1rem; color: #00a86b;">₱0.00</p>
                            </div>
                            <div class="col-6 mb-2">
                                <p style="margin: 0; font-size: 0.9rem; color: #666;">Interest Rate:</p>
                                <p id="preview-rate" style="margin: 0; font-weight: 600; font-size: 1.1rem; color: #00a86b;">12%</p>
                            </div>
                            <div class="col-6 mb-2">
                                <p style="margin: 0; font-size: 0.9rem; color: #666;">Monthly Payment:</p>
                                <p id="preview-monthly" style="margin: 0; font-weight: 600; font-size: 1.1rem; color: #4a90e2;">₱0.00</p>
                            </div>
                            <div class="col-6 mb-2">
                                <p style="margin: 0; font-size: 0.9rem; color: #666;">Total Interest:</p>
                                <p id="preview-interest" style="margin: 0; font-weight: 600; font-size: 1.1rem; color: #f5a623;">₱0.00</p>
                            </div>
                            <div class="col-12" style="border-top: 2px solid #ddd; padding-top: 0.75rem;">
                                <p style="margin: 0; font-size: 0.9rem; color: #666;">Total to Repay:</p>
                                <p id="preview-total" style="margin: 0; font-weight: 700; font-size: 1.3rem; color: #00a86b;">₱0.00</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="border-radius: 25px;">Cancel</button>
                    <button type="submit" class="btn" style="background: linear-gradient(135deg, #00a86b 0%, #008b5e 100%); color: white; border: none; border-radius: 25px; padding: 0.6rem 1.5rem;">
                        <i class="fas fa-send"></i> Submit Request
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Set up event listeners on page load
document.addEventListener('DOMContentLoaded', function() {
    // Set up live loan calculation preview
    const loanAmountEl = document.getElementById('loanAmount');
    const loanTermEl = document.getElementById('loanTerm');
    const loanRequestFormEl = document.getElementById('loanRequestForm');
    
    if (loanAmountEl) {
        loanAmountEl.addEventListener('input', calculateLoanPreview);
    }
    
    if (loanTermEl) {
        loanTermEl.addEventListener('change', calculateLoanPreview);
    }
    
    // Handle loan request form submission
    if (loanRequestFormEl) {
        loanRequestFormEl.addEventListener('submit', handleLoanRequestSubmit);
    }
});

function calculateLoanPreview() {
    const amount = document.getElementById('loanAmount').value;
    const termMonths = document.getElementById('loanTerm').value;

    if (!amount || !termMonths) {
        document.getElementById('calculationPreview').style.display = 'none';
        return;
    }

    const token = document.querySelector('input[name="_token"]')?.value;
    
    fetch('/api/dashboard/calculate-preview', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': token || '',
        },
        body: JSON.stringify({
            amount: parseFloat(amount),
            term_months: parseInt(termMonths),
        })
    })
    .then(response => response.json())
    .then(data => {
        document.getElementById('preview-principal').textContent = '₱' + parseFloat(data.principal).toLocaleString('fil-PH', {minimumFractionDigits: 2, maximumFractionDigits: 2});
        document.getElementById('preview-monthly').textContent = '₱' + parseFloat(data.monthly_payment).toLocaleString('fil-PH', {minimumFractionDigits: 2, maximumFractionDigits: 2});
        document.getElementById('preview-interest').textContent = '₱' + parseFloat(data.total_interest).toLocaleString('fil-PH', {minimumFractionDigits: 2, maximumFractionDigits: 2});
        document.getElementById('preview-total').textContent = '₱' + parseFloat(data.total_amount).toLocaleString('fil-PH', {minimumFractionDigits: 2, maximumFractionDigits: 2});
        document.getElementById('calculationPreview').style.display = 'block';
    })
    .catch(error => console.error('Error calculating preview:', error));
}

function handleLoanRequestSubmit(e) {
    e.preventDefault();

    // Validate that all fields are filled
    const amountEl = document.getElementById('loanAmount');
    const loanTypeEl = document.getElementById('loanType');
    const termEl = document.getElementById('loanTerm');
    
    if (!amountEl || !amountEl.value) {
        alert('Please enter a loan amount');
        return;
    }
    
    if (!loanTypeEl || !loanTypeEl.value) {
        alert('Please select a loan type');
        return;
    }
    
    if (!termEl || !termEl.value) {
        alert('Please select a loan term');
        return;
    }

    const formData = {
        amount: parseFloat(amountEl.value),
        loan_type: loanTypeEl.value,
        term_months: parseInt(termEl.value),
    };

    // Get CSRF token from form
    const token = document.querySelector('input[name="_token"]').value;
    
    fetch('/api/loan-requests', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': token,
        },
        body: JSON.stringify(formData)
    })
    .then(response => {
        if (!response.ok) {
            return response.json().then(data => {
                throw new Error(data.message || 'Server error');
            });
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            alert('Loan request submitted successfully!');
            document.getElementById('loanRequestForm').reset();
            document.getElementById('calculationPreview').style.display = 'none';
            const modal = bootstrap.Modal.getInstance(document.getElementById('loanRequestModal'));
            modal.hide();
            // Refresh dashboard
            location.reload();
        } else {
            alert('Error: ' + (data.error || data.message || 'Failed to submit request'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error submitting loan request: ' + error.message);
    });
}

function cancelLoanRequest(requestId, button) {
    // Confirm cancellation
    if (!confirm('Are you sure you want to cancel this loan request? This action cannot be undone.')) {
        return;
    }

    // Disable button and show loading state
    const originalText = button.innerHTML;
    button.disabled = true;
    button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Cancelling...';

    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    fetch(`/api/loan-requests/${requestId}/cancel`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': token,
        }
    })
    .then(response => {
        if (!response.ok) {
            return response.json().then(data => {
                throw new Error(data.error || data.message || 'Server error');
            });
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            // Show success message
            alert('Loan request cancelled successfully');
            // Refresh dashboard
            location.reload();
        } else {
            alert('Error: ' + (data.error || data.message || 'Failed to cancel request'));
            button.disabled = false;
            button.innerHTML = originalText;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error cancelling loan request: ' + error.message);
        button.disabled = false;
        button.innerHTML = originalText;
    });
}
</script>

@endsection
