@extends('layouts.app')

@section('title', 'Receipt Log')
@section('subtitle', 'View and manage all transaction records')

@section('content')
<div class="container-fluid">
    <!-- Filter Section -->
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0">Filters</h5>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('receipt-log.index') }}" class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Search & Filter</label>
                    <input type="text" name="search" id="receiptFilter" class="form-control" 
                           placeholder="Search by member, amount, or date..."
                           value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Transaction Type</label>
                    <select name="type" class="form-select">
                        <option value="">All Types</option>
                        <option value="payment" {{ request('type') === 'payment' ? 'selected' : '' }}>Payment</option>
                        <option value="loan_disbursement" {{ request('type') === 'loan_disbursement' ? 'selected' : '' }}>Loan Disbursement</option>
                        <option value="penalty" {{ request('type') === 'penalty' ? 'selected' : '' }}>Penalty</option>
                        <option value="refund" {{ request('type') === 'refund' ? 'selected' : '' }}>Refund</option>
                        <option value="interest_payment" {{ request('type') === 'interest_payment' ? 'selected' : '' }}>Interest Payment</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">From Date</label>
                    <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label">To Date</label>
                    <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
                </div>
                <div class="col-md-1 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-search"></i> Search
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Receipt Log Table -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Transaction History</h5>
            <div>
                <button class="btn btn-sm btn-outline-secondary" onclick="exportTableToCSV('receiptLogTable', 'receipt_log.csv')">
                    <i class="fas fa-download"></i> Export CSV
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="receiptLogTable" class="table table-hover transaction-table">
                    <thead>
                        <tr>
                            <th>Receipt #</th>
                            <th>Member Entity</th>
                            <th>Transaction Type</th>
                            <th>Amount</th>
                            <th>Process Date</th>
                            <th>Reference</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($receipts as $receipt)
                            <tr>
                                <td>#{{ $receipt->number }}</td>
                                <td>{{ $receipt->member }}</td>
                                <td>{{ $receipt->type }}</td>
                                <td class="{{ $receipt->type === 'Payment' ? 'amount-positive' : 'amount-negative' }}">₱{{ number_format($receipt->amount, 2) }}</td>
                                <td>{{ $receipt->date }}</td>
                                <td>{{ $receipt->reference }}</td>
                                <td><span class="badge status-{{ $receipt->status === 'completed' ? 'active' : 'client' }}">{{ ucfirst($receipt->status) }}</span></td>
                                <td>
                                    <a href="{{ route('receipt-log.show', $receipt->id) }}" class="btn btn-sm btn-outline-primary">
                                        View
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted py-4">No receipts found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <nav aria-label="Page navigation" class="mt-4">
                {{ $transactionsPaginated->links() }}
            </nav>
        </div>
    </div>
</div>

<!-- View Modal -->
<div class="modal fade" id="viewModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Transaction Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Receipt Number</label>
                    <input type="text" class="form-control" value="#RCP-2024-001" readonly>
                </div>
                <div class="mb-3">
                    <label class="form-label">Member</label>
                    <input type="text" class="form-control" value="John Smith" readonly>
                </div>
                <div class="mb-3">
                    <label class="form-label">Amount</label>
                    <input type="text" class="form-control" value="₱5,000.00" readonly>
                </div>
                <div class="mb-3">
                    <label class="form-label">Date</label>
                    <input type="text" class="form-control" value="{{ now()->format('M d, Y H:i') }}" readonly>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Print Receipt</button>
            </div>
        </div>
    </div>
</div>
@endsection
