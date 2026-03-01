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
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Search & Filter</label>
                    <input type="text" id="receiptFilter" class="form-control" 
                           placeholder="Search by member, amount, or date...">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Transaction Type</label>
                    <select class="form-select">
                        <option value="">All Types</option>
                        <option value="payment">Payment</option>
                        <option value="disbursement">Loan Disbursement</option>
                        <option value="penalty">Penalty</option>
                        <option value="refund">Refund</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Date Range</label>
                    <input type="date" class="form-control">
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button class="btn btn-primary w-100">
                        <i class="fas fa-search"></i> Search
                    </button>
                </div>
            </div>
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
                <ul class="pagination">
                    <li class="page-item disabled"><a class="page-link" href="#">Previous</a></li>
                    <li class="page-item active"><a class="page-link" href="#">1</a></li>
                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                    <li class="page-item"><a class="page-link" href="#">Next</a></li>
                </ul>
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
