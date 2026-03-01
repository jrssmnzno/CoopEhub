@extends('layouts.app')

@section('title', 'Loan Portfolio')
@section('subtitle', 'View member loan history and statement of account')

@section('content')
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

    <!-- Loan Ledger -->
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Loan Transactions</h5>
            <div>
                <button class="btn btn-sm btn-outline-secondary" onclick="exportTableToCSV('loanLedgerTable', 'loan_ledger.csv')">
                    <i class="fas fa-download"></i> Export CSV
                </button>
                <button class="btn btn-sm btn-outline-primary ms-2" data-bs-toggle="modal" data-bs-target="#generateSOA">
                    <i class="fas fa-file-pdf"></i> Generate SOA
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="mb-3">
                <input type="text" id="ledgerFilter" class="form-control" 
                       placeholder="Search by date, description, or amount...">
            </div>

            <div class="table-responsive">
                <table id="loanLedgerTable" class="table table-hover transaction-table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Description</th>
                            <th>Principal Paid</th>
                            <th>Interest Paid</th>
                            <th>Running Balance</th>
                            <th>Reference</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($ledger as $entry)
                            <tr>
                                <td>{{ $entry->date }}</td>
                                <td>{{ $entry->description }}</td>
                                <td class="amount-positive">₱{{ number_format($entry->principal, 2) }}</td>
                                <td class="amount-positive">₱{{ number_format($entry->interest, 2) }}</td>
                                <td>₱{{ number_format($entry->balance, 2) }}</td>
                                <td>{{ $entry->reference }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">No transactions found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Summary -->
            <div class="row mt-4">
                <div class="col-md-6">
                    <div class="card bg-light">
                        <div class="card-body">
                            <div class="row mb-2">
                                <div class="col-6"><strong>Principal Paid:</strong></div>
                                <div class="col-6 text-end text-success"><strong>₱{{ number_format($ledger->sum('principal'), 2) }}</strong></div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-6"><strong>Interest Paid:</strong></div>
                                <div class="col-6 text-end text-success"><strong>₱{{ number_format($ledger->sum('interest'), 2) }}</strong></div>
                            </div>
                            <div class="row">
                                <div class="col-6"><strong>Current Balance:</strong></div>
                                <div class="col-6 text-end text-danger"><strong>₱{{ number_format($summary->principalBalance ?? 0, 2) }}</strong></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card bg-light">
                        <div class="card-body">
                            <div class="row mb-2">
                                <div class="col-6"><strong>Total Interest Accrued:</strong></div>
                                <div class="col-6 text-end text-warning"><strong>₱{{ number_format($summary->interestAccrued ?? 0, 2) }}</strong></div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-6"><strong>Overdue Payment:</strong></div>
                                <div class="col-6 text-end"><span class="badge status-active">None</span></div>
                            </div>
                            <div class="row">
                                <div class="col-6"><strong>Next Payment Due:</strong></div>
                                <div class="col-6 text-end"><strong>{{ now()->addMonth()->format('M d, Y') }}</strong></div>
                            </div>
                        </div>
                    </div>
                </div>
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
                                    <div class="col-6 text-end">₱{{ number_format($loan->principal, 2) }}</div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-6"><strong>Interest Rate:</strong></div>
                                    <div class="col-6 text-end">{{ $loan->rate }}% per annum</div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-6"><strong>Loan Term:</strong></div>
                                    <div class="col-6 text-end">{{ $loan->term }} months</div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-6"><strong>Current Balance:</strong></div>
                                    <div class="col-6 text-end text-primary"><strong>₱{{ number_format($loan->balance, 2) }}</strong></div>
                                </div>
                                <div class="row">
                                    <div class="col-6"><strong>Monthly Installment:</strong></div>
                                    <div class="col-6 text-end">₱{{ number_format($loan->payment, 2) }}</div>
                                </div>
                                <button class="btn btn-sm btn-primary mt-3 w-100" data-bs-toggle="modal" data-bs-target="#promissoryModal">
                                    <i class="fas fa-file"></i> View Promissory Note
                                </button>
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

<!-- Promissory Note Modal -->
<div class="modal fade" id="promissoryModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Promissory Note</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="promissoryNoteContent">
                    @include('promissory-note.template')
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="printPromissoryNote()">
                    <i class="fas fa-print"></i> Print
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.getElementById('loadDetailsBtn').addEventListener('click', function() {
    const memberId = document.getElementById('memberSelect').value;
    
    if (!memberId) {
        alert('Please select a member first.');
        return;
    }
    
    // Navigate to member portfolio page
    window.location.href = `/loan-portfolio/member/${memberId}`;
});

// Allow Enter key to load member details
document.getElementById('memberSelect').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        document.getElementById('loadDetailsBtn').click();
    }
});
</script>
@endsection
