@extends('layouts.app')

@section('title', 'Loan Portfolio')
@section('subtitle', 'View member loan history and statement of account')

@section('content')
<!-- Custom Alert Notification -->
<div id="customAlert" class="custom-alert" style="display: none;">
    <div class="alert-content">
        <div class="alert-icon">
            <i class="fas fa-info-circle"></i>
        </div>
        <div class="alert-message">
            <strong id="alertTitle">Notification</strong>
            <p id="alertText"></p>
        </div>
        <button type="button" class="btn-close" onclick="closeCustomAlert()"></button>
    </div>
</div>

<div class="container-fluid">
    <!-- Member Selection -->
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0">Select Member</h5>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Member Name</label>
                    <select class="form-select" id="memberSelect">
                        <option value="">Select a member...</option>
                        @forelse($members as $member)
                            <option value="{{ $member->id }}">{{ $member->full_name }}</option>
                        @empty
                            <option disabled>No members available</option>
                        @endforelse
                    </select>
                </div>
                <div class="col-md-6 d-flex align-items-end">
                    <button type="button" class="btn btn-primary w-100" id="loadDetailsBtn">
                        <i class="fas fa-search"></i> Load Member Details
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div data-member-content>
    @if($member)
    <!-- Member Summary -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="summary-card">
                <div class="summary-card-title">Member Status</div>
                <div class="summary-card-value"><span class="badge {{ $member->status === 'active' ? 'status-active' : 'status-client' }}">{{ ucfirst($member->status) }}</span></div>
                <small class="text-muted">Member since {{ $member->created_at->format('M d, Y') }}</small>
            </div>
        </div>
        <div class="col-md-4">
            <div class="summary-card success">
                <div class="summary-card-title">Total Principal Balance</div>
                <div class="summary-card-value">₱{{ number_format($summary->principalBalance ?? 0, 2) }}</div>
                <small class="text-muted">Across {{ $member->loans()->where('status', 'active')->count() }} active loans</small>
            </div>
        </div>
        <div class="col-md-4">
            <div class="summary-card warning">
                <div class="summary-card-title">Interest Accrued</div>
                <div class="summary-card-value">₱{{ number_format($summary->interestAccrued ?? 0, 2) }}</div>
                <small class="text-muted">Total outstanding interest</small>
            </div>
        </div>
    </div>

    <!-- Active Loans -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Active Loans</h5>
        </div>
        <div class="card-body">
            <div class="row">
                @forelse($loans as $loan)
                    <div class="col-md-6 mb-3">
                        <div class="card border-primary">
                            <div class="card-header bg-light">
                                <h6 class="mb-0">Loan #{{ $loan->number }}</h6>
                            </div>
                            <div class="card-body">
                                <div class="row mb-2">
                                    <div class="col-6"><strong>Principal Amount:</strong></div>
                                    <div class="col-6 text-end">₱{{ number_format($loan->principal_amount, 2) }}</div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-6"><strong>Interest Rate:</strong></div>
                                    <div class="col-6 text-end">{{ $loan->interest_rate }}% per annum</div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-6"><strong>Loan Term:</strong></div>
                                    <div class="col-6 text-end">{{ $loan->term_months }} months</div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-6"><strong>Current Balance:</strong></div>
                                    <div class="col-6 text-end text-primary"><strong>₱{{ number_format($loan->running_balance, 2) }}</strong></div>
                                </div>
                                <div class="row">
                                    <div class="col-6"><strong>Monthly Installment:</strong></div>
                                    <div class="col-6 text-end">₱{{ number_format($loan->monthly_payment, 2) }}</div>
                                </div>
                                <a href="{{ route('promissory-note.show', $loan->id) }}" class="btn btn-sm btn-primary mt-3 w-100">
                                    <i class="fas fa-file"></i> View Promissory Note
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="alert alert-info" role="alert">
                            No active loans found for this member.
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Record New Payment Section -->
    @if($member && $loans->count() > 0)
    <div class="card mt-4">
        <div class="card-header">
            <h5 class="mb-0">Record New Payment</h5>
        </div>
        <div class="card-body">
            <form id="recordPaymentForm" method="POST">
                @csrf
                <div class="row mb-3">
                    <div class="col-md-12">
                        <label class="form-label">Select Loan *</label>
                        <select class="form-select @error('loan_id') is-invalid @enderror" id="loanSelect" required onchange="updatePaymentForm()">
                            <option value="">Choose a loan...</option>
                            @foreach($loans as $loan)
                                <option value="{{ $loan->id }}" 
                                    data-balance="{{ $loan->running_balance }}"
                                    data-due-date="{{ $loan->next_payment_date->format('Y-m-d') }}"
                                    data-monthly="{{ $loan->monthly_payment }}">
                                    Loan #{{ $loan->number }} - Balance: ₱{{ number_format($loan->running_balance, 2) }} | Due: {{ $loan->next_payment_date->format('M d, Y') }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Due Date Reminder Tag -->
                <div class="mb-3" id="dueDateReminder" style="display: none;">
                    <div class="alert alert-warning d-flex align-items-center" role="alert">
                        <i class="fas fa-calendar-alt me-2"></i>
                        <div>
                            <strong>Payment Due:</strong> <span id="dueDateText"></span>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Payment Date *</label>
                            <input type="date" name="payment_date" class="form-control @error('payment_date') is-invalid @enderror" 
                                   id="paymentDate" value="{{ old('payment_date', now()->format('Y-m-d')) }}" required max="{{ now()->format('Y-m-d') }}">
                            <small class="form-text text-muted d-block mt-1">Date cannot be in the future</small>
                            @error('payment_date')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Payment Amount (₱) *</label>
                            <div class="input-group">
                                <input type="number" name="amount" class="form-control @error('amount') is-invalid @enderror" 
                                       id="paymentAmount" step="0.01" min="0.01" placeholder="0.00" required 
                                       oninput="validatePaymentAmount()">
                                <span class="input-group-text" id="maxAmountText">Max: ₱0.00</span>
                            </div>
                            <small class="form-text text-muted d-block mt-1">
                                Maximum allowed: <strong id="maxAmountValue">₱0.00</strong> (pending balance)
                            </small>
                            @error('amount')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Payment Summary Preview -->
                <div id="paymentSummary" style="display: none;">
                    <div class="card bg-light mb-3">
                        <div class="card-body">
                            <div class="row mb-2">
                                <div class="col-6"><strong>Payment Amount:</strong></div>
                                <div class="col-6 text-end text-success"><strong id="previewAmount">₱0.00</strong></div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-6"><strong>Loan Balance After:</strong></div>
                                <div class="col-6 text-end"><strong id="previewBalance">₱0.00</strong></div>
                            </div>
                            <div class="row">
                                <div class="col-6"><strong>Monthly Installment:</strong></div>
                                <div class="col-6 text-end"><strong id="previewMonthly">₱0.00</strong></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-success" id="postPaymentBtn" disabled>
                        <i class="fas fa-check-circle"></i> Post Payment
                    </button>
                    <button type="reset" class="btn btn-outline-secondary" onclick="resetPaymentForm()">
                        <i class="fas fa-redo"></i> Reset
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Recent Payments History -->
    <div class="card mt-4">
        <div class="card-header">
            <h5 class="mb-0">Recent Payments</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="recentPaymentsTable" class="table table-sm table-hover">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Ref #</th>
                            <th>Principal</th>
                            <th>Interest</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Balance After</th>
                        </tr>
                    </thead>
                    <tbody id="paymentsTableBody">
                        @forelse($ledger->filter(fn($row) => strpos(strtolower($row->description), 'payment') !== false)->take(10) as $payment)
                            <tr>
                                <td>{{ $payment->date }}</td>
                                <td><small>{{ $payment->reference }}</small></td>
                                <td class="text-success">₱{{ number_format($payment->principal, 2) }}</td>
                                <td class="text-success">₱{{ number_format($payment->interest, 2) }}</td>
                                <td><strong>₱{{ number_format($payment->principal + $payment->interest, 2) }}</strong></td>
                                <td><span class="badge bg-success">Paid</span></td>
                                <td>₱{{ number_format($payment->balance, 2) }}</td>
                            </tr>
                        @empty
                            <tr id="noPaymentsRow">
                                <td colspan="7" class="text-center text-muted py-3">No payment records found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif
    @else
    <div class="alert alert-info" role="alert">
        <i class="fas fa-info-circle"></i> Select a member above to view their loan portfolio, transaction history, and active loans.
    </div>
    @endif
    </div>

<!-- Generate SOA Modal -->
<div class="modal fade" id="generateSOA" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Generate Statement of Account</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Start Date</label>
                    <input type="date" class="form-control">
                </div>
                <div class="mb-3">
                    <label class="form-label">End Date</label>
                    <input type="date" class="form-control">
                </div>
                <div class="mb-3">
                    <label class="form-label">Document Format</label>
                    <select class="form-select">
                        <option>PDF</option>
                        <option>Excel</option>
                        <option>Print</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary">Generate</button>
            </div>
        </div>
    </div>
</div>

<!-- Promissory Note Modal - REMOVED (now uses separate page) -->
@endsection

@section('scripts')
<style>
    /* Custom Alert Styles */
    .custom-alert {
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 9999;
        animation: slideIn 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
    }

    .custom-alert.hide {
        animation: slideOut 0.3s ease-in-out forwards;
    }

    @keyframes slideIn {
        from {
            transform: translateX(400px);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }

    @keyframes slideOut {
        from {
            transform: translateX(0);
            opacity: 1;
        }
        to {
            transform: translateX(400px);
            opacity: 0;
        }
    }

    .alert-content {
        background: white;
        border-radius: 8px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
        padding: 16px 20px;
        display: flex;
        gap: 16px;
        align-items: flex-start;
        max-width: 400px;
        border-left: 4px solid #0d6efd;
        backdrop-filter: blur(10px);
    }

    .custom-alert.success .alert-content {
        border-left-color: #198754;
    }

    .custom-alert.warning .alert-content {
        border-left-color: #ffc107;
    }

    .custom-alert.error .alert-content {
        border-left-color: #dc3545;
    }

    .alert-icon {
        font-size: 20px;
        color: #0d6efd;
        min-width: 24px;
        margin-top: 2px;
    }

    .custom-alert.success .alert-icon {
        color: #198754;
    }

    .custom-alert.warning .alert-icon {
        color: #ffc107;
    }

    .custom-alert.error .alert-icon {
        color: #dc3545;
    }

    .alert-message {
        flex: 1;
    }

    #alertTitle {
        display: block;
        font-size: 14px;
        font-weight: 600;
        color: #212529;
        margin-bottom: 4px;
    }

    #alertText {
        font-size: 14px;
        color: #6c757d;
        margin: 0;
        line-height: 1.5;
    }

    .btn-close {
        background: transparent;
        border: none;
        cursor: pointer;
        color: #6c757d;
        font-size: 20px;
        padding: 0;
        margin-top: -2px;
        transition: color 0.2s ease;
    }

    .btn-close:hover {
        color: #212529;
    }

    /* Payment Form Styles */
    .card {
        border: 1px solid #e9ecef;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    }

    .card-header {
        background-color: #f8f9fa;
        border-bottom: 2px solid #e9ecef;
        padding: 1.25rem;
    }

    .card-header h5 {
        color: #212529;
        font-weight: 600;
        margin: 0;
    }

    /* Payment Amount Input Styling */
    #paymentAmount.is-invalid {
        border-color: #dc3545;
        background-image: none;
    }

    .input-group-text {
        background-color: #f8f9fa;
        border: 1px solid #dee2e6;
        font-weight: 500;
        color: #6c757d;
    }

    /* Payment Summary Card */
    .bg-light {
        background-color: #f8f9fa !important;
    }

    /* Button Styles */
    .btn-success {
        background-color: #198754;
        border-color: #198754;
        transition: all 0.2s ease;
    }

    .btn-success:hover:not(:disabled) {
        background-color: #157347;
        border-color: #157347;
        box-shadow: 0 4px 12px rgba(25, 135, 84, 0.3);
    }

    .btn-success:disabled {
        opacity: 0.65;
        cursor: not-allowed;
    }

    .btn-outline-secondary:hover {
        background-color: #6c757d;
        border-color: #6c757d;
    }

    /* Due Date Reminder Styles */
    .alert-warning {
        background-color: #fff3cd;
        border-color: #ffecb5;
        color: #856404;
        border-left: 4px solid #ffc107;
    }

    .alert-warning strong {
        color: #333;
    }

    /* Responsiveness */
    @media (max-width: 576px) {
        .custom-alert {
            top: 10px;
            right: 10px;
            left: 10px;
        }

        .alert-content {
            max-width: 100%;
        }

        .card-body {
            padding: 1rem;
        }

        .row {
            gap: 0.5rem;
        }
    }
</style>

<script>
// Custom Alert Function
function showCustomAlert(message, title = 'Notification', type = 'info', duration = 5000) {
    const alertBox = document.getElementById('customAlert');
    const alertTitle = document.getElementById('alertTitle');
    const alertText = document.getElementById('alertText');
    
    // Set content
    alertTitle.textContent = title;
    alertText.textContent = message;
    
    // Remove previous type classes and add new one
    alertBox.className = 'custom-alert ' + type;
    alertBox.style.display = 'block';
    
    // Auto-close after duration
    if (duration > 0) {
        setTimeout(() => {
            closeCustomAlert();
        }, duration);
    }
}

function closeCustomAlert() {
    const alertBox = document.getElementById('customAlert');
    alertBox.classList.add('hide');
    setTimeout(() => {
        alertBox.style.display = 'none';
        alertBox.classList.remove('hide');
    }, 300);
}

/* Populate Promissory Note REMOVED - now uses separate page route */

document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded, initializing loan portfolio script');
    
    const loadDetailsBtn = document.getElementById('loadDetailsBtn');
    const memberSelect = document.getElementById('memberSelect');
    
    console.log('Button element:', loadDetailsBtn);
    console.log('Select element:', memberSelect);
    
    if (loadDetailsBtn) {
        loadDetailsBtn.addEventListener('click', function() {
            console.log('Load Details button clicked');
            const memberId = memberSelect.value;
            const selectedText = memberSelect.options[memberSelect.selectedIndex].text;
            
            console.log('Selected member ID:', memberId);
            console.log('Selected member name:', selectedText);
            
            if (!memberId) {
                showCustomAlert('Please select a member from the dropdown list.', 'Selection Required', 'warning', 4000);
                return;
            }
            
            console.log('Navigating to: /loan-portfolio/member/' + memberId);
            showCustomAlert('Loading portfolio for ' + selectedText + '...', 'Loading Details', 'info', 2000);
            
            // Navigate to member portfolio page after a short delay
            setTimeout(() => {
                window.location.href = `/loan-portfolio/member/${memberId}`;
            }, 500);
        });
    } else {
        console.error('Load Details button not found!');
    }
    
    // Allow Enter key to load member details
    if (memberSelect) {
        memberSelect.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                loadDetailsBtn.click();
            }
        });
    }

    // Handle record payment form submission
    const recordPaymentForm = document.getElementById('recordPaymentForm');
    if (recordPaymentForm) {
        recordPaymentForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const loanSelect = document.getElementById('loanSelect');
            const paymentAmount = document.getElementById('paymentAmount');
            const paymentDate = document.getElementById('paymentDate');
            const loanId = loanSelect.value;
            const maxAmount = parseFloat(loanSelect.options[loanSelect.selectedIndex].dataset.balance);
            const selectedOption = loanSelect.options[loanSelect.selectedIndex];
            
            // Validate loan selection
            if (!loanId) {
                showCustomAlert('Please select a loan', 'Validation Error', 'warning', 3000);
                return;
            }
            
            // Validate amount
            const amount = parseFloat(paymentAmount.value);
            if (!amount || amount <= 0) {
                showCustomAlert('Please enter a valid payment amount', 'Validation Error', 'warning', 3000);
                return;
            }
            
            if (amount > maxAmount) {
                showCustomAlert(`Payment amount cannot exceed ₱${number_format(maxAmount, 2)}`, 'Amount Exceeded', 'error', 3000);
                return;
            }
            
            showCustomAlert('Processing payment...', 'Submitting', 'info', 2000);
            
            // Get CSRF token
            const csrfToken = document.querySelector('[name="_token"]').value;
            
            // Prepare form data
            const formData = new FormData(recordPaymentForm);
            
            // Submit form via AJAX to the correct endpoint
            fetch(`/payments/loan/${loanId}/process`, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': csrfToken
                }
            })
            .then(response => {
                // Handle both successful and error responses
                if (!response.ok && response.status === 422) {
                    // Validation error
                    return response.json().then(data => {
                        const errors = data.errors;
                        let errorMsg = 'Validation Error:\n';
                        for (const field in errors) {
                            errorMsg += `- ${field}: ${errors[field][0]}\n`;
                        }
                        showCustomAlert(errorMsg, 'Validation Error', 'warning', 5000);
                        throw new Error('Validation failed');
                    });
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    const transaction = data.transaction;
                    const loanData = data.loan;
                    const isFullyPaid = loanData && loanData.status === 'fully_paid';
                    
                    // Show appropriate success message
                    const msg = isFullyPaid 
                        ? 'Payment processed successfully! Loan is now fully paid!'
                        : 'Payment processed successfully';
                    showCustomAlert(msg, 'Payment Successful', 'success', 4000);
                    
                    // Update the Recent Payments table immediately
                    const dateObj = new Date(paymentDate.value);
                    const formattedDate = dateObj.toISOString().split('T')[0];
                    
                    const newRow = `<tr>
                        <td>${formattedDate}</td>
                        <td><small>-</small></td>
                        <td class="text-success">₱${number_format(parseFloat(transaction.principal), 2)}</td>
                        <td class="text-success">₱${number_format(parseFloat(transaction.interest), 2)}</td>
                        <td><strong>₱${number_format(amount, 2)}</strong></td>
                        <td><span class="badge bg-success">Paid</span></td>
                        <td>₱${number_format(parseFloat(transaction.loan_balance), 2)}</td>
                    </tr>`;
                    
                    // Insert new row at the top of the table
                    const tableBody = document.getElementById('paymentsTableBody');
                    const noPaymentsRow = document.getElementById('noPaymentsRow');
                    
                    // Remove "no payments" message if it exists
                    if (noPaymentsRow) {
                        noPaymentsRow.remove();
                    }
                    
                    // Insert new payment at the beginning
                    tableBody.insertAdjacentHTML('afterbegin', newRow);
                    
                    // Reset form
                    recordPaymentForm.reset();
                    updatePaymentForm();
                    
                    // Update loan balance in dropdown
                    selectedOption.dataset.balance = transaction.loan_balance;
                    const loanText = `Loan #${selectedOption.text.split('#')[1].split(' -')[0]} - Balance: ₱${number_format(parseFloat(transaction.loan_balance), 2)} | Due: ${selectedOption.text.split('Due: ')[1]}`;
                    selectedOption.textContent = loanText;
                    
                    // If fully paid, disable the loan selection and show message
                    if (isFullyPaid) {
                        selectedOption.disabled = true;
                        selectedOption.textContent += ' [FULLY PAID]';
                        document.getElementById('loanSelect').value = '';
                    }
                    
                    // Reload after 3 seconds to sync all data
                    setTimeout(() => {
                        location.reload();
                    }, 3000);
                } else {
                    showCustomAlert(data.message || 'An error occurred', 'Error', 'error', 4000);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showCustomAlert('An error occurred while processing the payment', 'Error', 'error', 4000);
            });
        });
    }
});

// Helper function to format numbers
function number_format(num, decimals) {
    return num.toFixed(decimals).replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}

// Update form when loan is selected
function updatePaymentForm() {
    const loanSelect = document.getElementById('loanSelect');
    const selectedOption = loanSelect.options[loanSelect.selectedIndex];
    const dueDateReminder = document.getElementById('dueDateReminder');
    const dueDateText = document.getElementById('dueDateText');
    const maxAmountValue = document.getElementById('maxAmountValue');
    const maxAmountText = document.getElementById('maxAmountText');
    const paymentAmount = document.getElementById('paymentAmount');
    const postPaymentBtn = document.getElementById('postPaymentBtn');
    
    if (!loanSelect.value) {
        dueDateReminder.style.display = 'none';
        maxAmountValue.textContent = '₱0.00';
        maxAmountText.textContent = 'Max: ₱0.00';
        paymentAmount.max = '0';
        paymentAmount.value = '';
        postPaymentBtn.disabled = true;
        return;
    }
    
    const balance = parseFloat(selectedOption.dataset.balance);
    const dueDate = selectedOption.dataset.dueDate;
    
    // Update max amount
    maxAmountValue.textContent = number_format(balance, 2);
    maxAmountText.textContent = number_format(balance, 2);
    maxAmountValue.innerHTML = '₱' + number_format(balance, 2);
    paymentAmount.max = balance;
    
    // Update due date reminder
    const dateObj = new Date(dueDate);
    const formattedDate = dateObj.toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' });
    dueDateText.textContent = formattedDate;
    dueDateReminder.style.display = 'block';
    
    // Enable submit button if form is valid
    postPaymentBtn.disabled = false;
    
    // Clear payment amount to force fresh input
    paymentAmount.value = '';
}

// Validate payment amount doesn't exceed maximum
function validatePaymentAmount() {
    const loanSelect = document.getElementById('loanSelect');
    const paymentAmount = document.getElementById('paymentAmount');
    const paymentSummary = document.getElementById('paymentSummary');
    const previewAmount = document.getElementById('previewAmount');
    const previewBalance = document.getElementById('previewBalance');
    const previewInterest = document.getElementById('previewInterest');
    const previewPrincipal = document.getElementById('previewPrincipal');
    
    if (!loanSelect.value) {
        paymentSummary.style.display = 'none';
        return;
    }
    
    const selectedOption = loanSelect.options[loanSelect.selectedIndex];
    const balance = parseFloat(selectedOption.dataset.balance);
    const amount = parseFloat(paymentAmount.value) || 0;
    
    // Check if amount exceeds balance
    if (amount > balance) {
        paymentAmount.classList.add('is-invalid');
        paymentSummary.style.display = 'none';
        return;
    } else {
        paymentAmount.classList.remove('is-invalid');
    }
    
    // Calculate interest due (monthly rate * balance)
    // Get interest rate from loan
    const loanSelects = document.querySelectorAll('#loanSelect option');
    let interestRate = 0;
    for (let option of loanSelects) {
        if (option.value === loanSelect.value) {
            // Get from data attribute - we'd need to add this
            // For now, estimate or fetch from server
            break;
        }
    }
    
    // Show/hide summary
    if (amount > 0) {
        const remainingBalance = balance - amount;
        
        // Estimate interest payment (simple calculation)
        // In reality, this should come from the server
        const estimatedMonthlyInterest = (balance * 0.08) / 12; // Rough estimate with 8% rate
        const interestPortion = Math.min(amount, estimatedMonthlyInterest);
        const principalPortion = amount - interestPortion;
        
        previewAmount.textContent = '₱' + number_format(amount, 2);
        previewBalance.textContent = '₱' + number_format(Math.max(0, remainingBalance), 2);
        previewInterest.textContent = '₱' + number_format(Math.max(0, interestPortion), 2);
        previewPrincipal.textContent = '₱' + number_format(Math.max(0, principalPortion), 2);
        paymentSummary.style.display = 'block';
    } else {
        paymentSummary.style.display = 'none';
    }
}

// Reset form and related fields
function resetPaymentForm() {
    const loanSelect = document.getElementById('loanSelect');
    const paymentAmount = document.getElementById('paymentAmount');
    const paymentSummary = document.getElementById('paymentSummary');
    const dueDateReminder = document.getElementById('dueDateReminder');
    
    loanSelect.value = '';
    paymentAmount.value = '';
    paymentAmount.classList.remove('is-invalid');
    paymentSummary.style.display = 'none';
    dueDateReminder.style.display = 'none';
}
</script>
@endsection
