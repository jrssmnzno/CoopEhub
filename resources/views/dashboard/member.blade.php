@extends('layouts.app')

@section('title', 'Member Dashboard')
@section('subtitle', 'Member ID: ' . ($member->member_id ?? 'N/A') . ' • Status: ' . ucfirst($member->status ?? 'pending'))

@section('content')
<div class="member-dashboard" style="background: linear-gradient(135deg, #f0f9ff 0%, #e0f7ff 100%); min-height: 100vh; padding: 1.5rem 0;">
    <div class="container">
        <!-- Upcoming Meeting Announcement -->
        @include('components.upcoming-meeting')

        <!-- Key Metrics Row -->
        <div class="row mb-3">
            <!-- Total Outstanding Balance -->
            <div class="col-md-6 col-lg-3 mb-2">
                <div class="card" style="border: none; border-radius: 10px; background: linear-gradient(135deg, #00a86b 0%, #008b5e 100%); color: white; box-shadow: 0 4px 15px rgba(0,168,107,0.2); height: 100%;">
                    <div class="card-body" style="padding: 1rem;">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="card-text" style="opacity: 0.9; margin: 0; font-size: 0.9rem; font-weight: 500;">Outstanding Balance</p>
                                <h2 style="margin: 0.3rem 0; font-weight: 700; font-size: 1.6rem;">₱{{ number_format($totalOutstanding, 2) }}</h2>
                                <p style="margin: 0; font-size: 0.85rem; opacity: 0.8;">{{ $activeLoans->count() }} active loan(s)</p>
                            </div>
                            <div style="font-size: 2rem; opacity: 0.3;">
                                <i class="fas fa-wallet"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Principal Paid -->
            <div class="col-md-6 col-lg-3 mb-3">
                <div class="card" style="border: none; border-radius: 10px; background: linear-gradient(135deg, #4a90e2 0%, #357abd 100%); color: white; box-shadow: 0 4px 15px rgba(74,144,226,0.2); height: 100%;">
                    <div class="card-body" style="padding: 1rem;">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="card-text" style="opacity: 0.9; margin: 0; font-size: 0.9rem; font-weight: 500;">Principal Paid</p>
                                <h2 style="margin: 0.3rem 0; font-weight: 700; font-size: 1.6rem;">₱{{ number_format($principalPaid, 2) }}</h2>
                                <p style="margin: 0; font-size: 0.85rem; opacity: 0.8;">Total repaid</p>
                            </div>
                            <div style="font-size: 2rem; opacity: 0.3;">
                                <i class="fas fa-check-circle"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Interest Paid -->
            <div class="col-md-6 col-lg-3 mb-3">
                <div class="card" style="border: none; border-radius: 10px; background: linear-gradient(135deg, #f5a623 0%, #d68910 100%); color: white; box-shadow: 0 4px 15px rgba(245,166,35,0.2); height: 100%;">
                    <div class="card-body" style="padding: 1rem;">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="card-text" style="opacity: 0.9; margin: 0; font-size: 0.9rem; font-weight: 500;">Interest Paid</p>
                                <h2 style="margin: 0.3rem 0; font-weight: 700; font-size: 1.6rem;">₱{{ number_format($interestPaid, 2) }}</h2>
                                <p style="margin: 0; font-size: 0.85rem; opacity: 0.8;">Total interest</p>
                            </div>
                            <div style="font-size: 2rem; opacity: 0.3;">
                                <i class="fas fa-chart-pie"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Remaining Principal -->
            <div class="col-md-6 col-lg-3 mb-3">
                <div class="card" style="border: none; border-radius: 10px; background: linear-gradient(135deg, #e94b3c 0%, #c62f20 100%); color: white; box-shadow: 0 4px 15px rgba(233,75,60,0.2); height: 100%;">
                    <div class="card-body" style="padding: 1rem;">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="card-text" style="opacity: 0.9; margin: 0; font-size: 0.9rem; font-weight: 500;">Remaining Principal</p>
                                <h2 style="margin: 0.3rem 0; font-weight: 700; font-size: 1.6rem;">₱{{ number_format($remainingPrincipal, 2) }}</h2>
                                <p style="margin: 0; font-size: 0.85rem; opacity: 0.8;">To be repaid</p>
                            </div>
                            <div style="font-size: 2rem; opacity: 0.3;">
                                <i class="fas fa-chart-line"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Active Loans -->
            <div class="col-lg-6 mb-3">
                <div class="card" style="border: none; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
                    <div class="card-header" style="background: #f8f9fa; border-bottom: 2px solid #dee2e6; border-radius: 10px 10px 0 0;">
                        <h5 style="margin: 0; color: #00a86b; font-weight: 600;">
                            <i class="fas fa-credit-card"></i> Active Loans
                        </h5>
                    </div>
                    <div class="card-body">
                        @if($activeLoans->count() > 0)
                            @foreach($activeLoans as $loan)
                                <div style="border-left: 4px solid #00a86b; padding: 1rem; background: #f9f9f9; border-radius: 8px; margin-bottom: 1rem;">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <div>
                                            <h6 style="margin: 0; font-weight: 600;">#{{ $loan->loan_number }}</h6>
                                            <p style="margin: 0.25rem 0 0; font-size: 0.85rem; color: #666;">
                                                <span style="background: #e8f5e9; color: #00a86b; padding: 0.15rem 0.5rem; border-radius: 15px; font-size: 0.75rem;">{{ ucfirst(str_replace('_', ' ', $loan->loan_type ?? 'personal')) }}</span>
                                            </p>
                                        </div>
                                        <span style="background: #e8f5e9; color: #00a86b; padding: 0.25rem 0.75rem; border-radius: 20px; font-size: 0.8rem; font-weight: 500;">{{ ucfirst($loan->status) }}</span>
                                    </div>
                                    <div class="row" style="font-size: 0.9rem;">
                                        <div class="col-6">
                                            <p style="margin: 0; color: #666;">Principal:</p>
                                            <p style="margin: 0; font-weight: 600;">₱{{ number_format($loan->principal, 2) }}</p>
                                        </div>
                                        <div class="col-6">
                                            <p style="margin: 0; color: #666;">Rate:</p>
                                            <p style="margin: 0; font-weight: 600;">{{ $loan->interest_rate }}%/yr</p>
                                        </div>
                                        <div class="col-6">
                                            <p style="margin: 0; color: #666;">Balance:</p>
                                            <p style="margin: 0; font-weight: 600; color: #e94b3c;">₱{{ number_format($loan->running_balance, 2) }}</p>
                                        </div>
                                        <div class="col-6">
                                            <p style="margin: 0; color: #666;">Monthly Pymt:</p>
                                            <p style="margin: 0; font-weight: 600;">₱{{ number_format($loan->calculateMonthlyPayment(), 2) }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div style="text-align: center; padding: 2rem; color: #999;">
                                <i class="fas fa-inbox" style="font-size: 2.5rem; margin-bottom: 0.5rem; opacity: 0.5;"></i>
                                <p style="margin: 0;">No active loans</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Pending Loan Requests -->
            <div class="col-lg-6 mb-3">
                <div class="card" style="border: none; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
                    <div class="card-header" style="background: #f8f9fa; border-bottom: 2px solid #dee2e6; border-radius: 10px 10px 0 0; display: flex; justify-content: space-between; align-items: center;">
                        <h5 style="margin: 0; color: #00a86b; font-weight: 600;">
                            <i class="fas fa-file-contract"></i> Loan Requests
                        </h5>
                        <button class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#loanRequestModal" style="border-radius: 20px; font-size: 0.85rem;">
                            <i class="fas fa-plus"></i> New Request
                        </button>
                    </div>
                    <div class="card-body">
                        @if($pendingRequests->count() > 0)
                            @foreach($pendingRequests as $request)
                                <div style="border-left: 4px solid #f5a623; padding: 1rem; background: #f9f9f9; border-radius: 8px; margin-bottom: 1rem;">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <div>
                                            <h6 style="margin: 0; font-weight: 600;">₱{{ number_format($request->requested_amount, 2) }}</h6>
                                            <p style="margin: 0.25rem 0 0; font-size: 0.85rem; color: #666;">{{ $request->requested_term_months }} months</p>
                                        </div>
                                        <span style="background: #fff3cd; color: #856404; padding: 0.25rem 0.75rem; border-radius: 20px; font-size: 0.8rem; font-weight: 500;">{{ ucfirst($request->status) }}</span>
                                    </div>
                                    <p style="margin: 0; font-size: 0.85rem; color: #666;">Requested: {{ $request->created_at->format('M d, Y') }}</p>
                                </div>
                            @endforeach
                        @else
                            <div style="text-align: center; padding: 2rem; color: #999;">
                                <i class="fas fa-inbox" style="font-size: 2.5rem; margin-bottom: 0.5rem; opacity: 0.5;"></i>
                                <p style="margin: 0; margin-bottom: 1rem;">No pending requests</p>
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
        <div class="row mb-3">
            <div class="col-12">
                <div class="card" style="border: none; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
                    <div class="card-header" style="background: #f8f9fa; border-bottom: 2px solid #dee2e6; border-radius: 10px 10px 0 0;">
                        <h5 style="margin: 0; color: #00a86b; font-weight: 600;">
                            <i class="fas fa-history"></i> Recent Transactions
                        </h5>
                    </div>
                    <div class="card-body">
                        @if($recentTransactions->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead style="background-color: #f8f9fa;">
                                        <tr>
                                            <th style="color: #666; font-weight: 600; font-size: 0.9rem;">Date</th>
                                            <th style="color: #666; font-weight: 600; font-size: 0.9rem;">Type</th>
                                            <th style="color: #666; font-weight: 600; font-size: 0.9rem;">Principal</th>
                                            <th style="color: #666; font-weight: 600; font-size: 0.9rem;">Interest</th>
                                            <th style="color: #666; font-weight: 600; font-size: 0.9rem;">Total Amount</th>
                                            <th style="color: #666; font-weight: 600; font-size: 0.9rem;">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($recentTransactions as $transaction)
                                            <tr>
                                                <td style="font-size: 0.9rem;">{{ $transaction->created_at->format('M d, Y') }}</td>
                                                <td style="font-size: 0.9rem;"><span style="background: #f0f0f0; padding: 0.25rem 0.75rem; border-radius: 20px;">{{ str_replace('_', ' ', ucfirst($transaction->type)) }}</span></td>
                                                <td style="font-size: 0.9rem;">₱{{ number_format($transaction->principal_amount ?? 0, 2) }}</td>
                                                <td style="font-size: 0.9rem;">₱{{ number_format($transaction->interest_amount ?? 0, 2) }}</td>
                                                <td style="font-size: 0.9rem; color: #00a86b; font-weight: 600;">₱{{ number_format($transaction->total_amount, 2) }}</td>
                                                <td style="font-size: 0.9rem;"><span style="background: #e8f5e9; color: #00a86b; padding: 0.25rem 0.75rem; border-radius: 20px;">{{ ucfirst($transaction->status ?? 'completed') }}</span></td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div style="text-align: center; padding: 2rem; color: #999;">
                                <i class="fas fa-inbox" style="font-size: 2.5rem; margin-bottom: 0.5rem; opacity: 0.5;"></i>
                                <p style="margin: 0;">No transactions yet</p>
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
// Load dashboard data on page load
document.addEventListener('DOMContentLoaded', function() {
    loadDashboardData();
    
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

function loadDashboardData() {
    fetch('/api/dashboard/data')
        .then(response => response.json())
        .then(data => {
            // Update member info
            document.getElementById('member-name').textContent = data.member.name;
            document.getElementById('member-id').textContent = data.member.member_id;
            document.getElementById('member-status').textContent = data.member.status.charAt(0).toUpperCase() + data.member.status.slice(1);

            // Update summary cards
            document.getElementById('total-outstanding').textContent = data.summary.total_outstanding;
            document.getElementById('remaining-principal').textContent = data.summary.remaining_principal;
            document.getElementById('active-loans-count').textContent = data.summary.active_loans;

            // Update next payment
            if (data.next_payment) {
                document.getElementById('next-payment-date').textContent = data.next_payment.due_date;
                document.getElementById('next-payment-amount').textContent = `Amount: ${data.next_payment.amount}`;
            }

            // Update recent transactions
            const transactionsHtml = data.recent_transactions.length 
                ? data.recent_transactions.map(t => `
                    <tr>
                        <td>${t.date}</td>
                        <td><span style="background: #f0f0f0; padding: 0.25rem 0.75rem; border-radius: 20px; font-size: 0.85rem;">${t.type}</span></td>
                        <td style="color: #00a86b; font-weight: 600;">${t.amount}</td>
                        <td><span style="background: #e8f5e9; color: #00a86b; padding: 0.25rem 0.75rem; border-radius: 20px; font-size: 0.85rem;">${t.status}</span></td>
                    </tr>
                `).join('')
                : '<tr><td colspan="4" class="text-center text-muted py-4">No transactions yet</td></tr>';
            document.getElementById('recent-transactions').innerHTML = transactionsHtml;

            // Update pending requests
            if (data.pending_requests.length > 0) {
                const requestsHtml = data.pending_requests.map(r => `
                    <div class="card mb-2" style="border-left: 4px solid #f5a623; border-radius: 8px;">
                        <div class="card-body py-2">
                            <div class="row">
                                <div class="col-md-6">
                                    <p style="margin: 0; font-size: 0.9rem; color: #666;">Request Date:</p>
                                    <p style="margin: 0; font-weight: 600;">${r.created_at}</p>
                                </div>
                                <div class="col-md-6">
                                    <p style="margin: 0; font-size: 0.9rem; color: #666;">Amount:</p>
                                    <p style="margin: 0; font-weight: 600; color: #00a86b;">${r.amount}</p>
                                </div>
                                <div class="col-md-6">
                                    <p style="margin: 0; font-size: 0.9rem; color: #666;">Term:</p>
                                    <p style="margin: 0; font-weight: 600;">${r.term}</p>
                                </div>
                                <div class="col-md-6">
                                    <p style="margin: 0; font-size: 0.9rem; color: #666;">Status:</p>
                                    <p style="margin: 0;"><span style="background: #fff3cd; color: #856404; padding: 0.25rem 0.75rem; border-radius: 20px; font-size: 0.85rem; font-weight: 500;">${r.status}</span></p>
                                </div>
                            </div>
                        </div>
                    </div>
                `).join('');
                document.getElementById('pending-requests-container').innerHTML = `
                    <div class="card" style="border: none; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
                        <div class="card-body">
                            <h6 style="font-weight: 600; margin-bottom: 1rem;">Pending Requests</h6>
                            ${requestsHtml}
                        </div>
                    </div>
                `;
            } else {
                document.getElementById('pending-requests-container').innerHTML = `
                    <div class="card" style="border: none; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
                        <div class="card-body text-center py-4">
                            <p class="text-muted mb-0">No pending loan requests</p>
                        </div>
                    </div>
                `;
            }
        })
        .catch(error => console.error('Error loading dashboard data:', error));
}

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
            loadDashboardData(); // Refresh dashboard
        } else {
            alert('Error: ' + (data.error || data.message || 'Failed to submit request'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error submitting loan request: ' + error.message);
    });
}
</script>

@endsection
