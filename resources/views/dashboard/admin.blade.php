@extends('layouts.app')

@section('title', 'Dashboard')
@section('subtitle', 'Admin Dashboard - System Overview')

@section('content')
<div class="container-fluid">
    <!-- Dashboard Statistics -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="summary-card">
                <div class="summary-card-title">Total Members</div>
                <div class="summary-card-value">{{ $stats['members'] ?? 0 }}</div>
                <small class="text-muted">Active accounts</small>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="summary-card success">
                <div class="summary-card-title">Active Loans</div>
                <div class="summary-card-value">{{ $stats['loans'] ?? 0 }}</div>
                <small class="text-muted">Total portfolio</small>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="summary-card warning">
                <div class="summary-card-title">Total Collections</div>
                <div class="summary-card-value">₱{{ number_format($stats['collections'] ?? 0, 0) }}</div>
                <small class="text-muted">All payments</small>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="summary-card danger">
                <div class="summary-card-title">Overdue Amount</div>
                <div class="summary-card-value">₱{{ number_format($stats['overdue'] ?? 0, 0) }}</div>
                <small class="text-muted">Requiring attention</small>
            </div>
        </div>
    </div>

    <!-- Main Content Row -->
    <div class="row">
        <!-- Pending Requests Section -->
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-hourglass-half text-warning"></i> Pending Loan Requests
                        <span class="badge bg-warning text-dark ms-2">{{ $pendingLoans ?? 0 }}</span>
                    </h5>
                </div>
                <div class="card-body">
                    <p class="text-muted mb-3">Awaiting your review and approval. Applications are processed on a first-come, first-served basis.</p>
                    <div class="text-center" style="padding: 2rem;">
                        <i class="fas fa-inbox" style="font-size: 3rem; color: #dee2e6; margin-bottom: 1rem;"></i>
                        <p class="text-muted">
                            @if($pendingLoans > 0)
                                <strong>{{ $pendingLoans }} pending request(s)</strong> waiting for approval
                            @else
                                No pending loan requests at this time
                            @endif
                        </p>
                        <a href="/admin/loan-requests" class="btn btn-primary">
                            <i class="fas fa-arrow-right"></i> Review Requests
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="/members" class="btn btn-primary">
                            <i class="fas fa-users"></i> Manage Members
                        </a>
                        <a href="/admin/loan-requests" class="btn btn-outline-primary">
                            <i class="fas fa-file-contract"></i> Loan Requests
                        </a>
                        <a href="/loan-portfolio" class="btn btn-outline-primary">
                            <i class="fas fa-briefcase"></i> Loan Portfolio
                        </a>
                        <a href="/receipt-log" class="btn btn-outline-primary">
                            <i class="fas fa-receipt"></i> Receipt Log
                        </a>
                        <a href="/audit-ledger" class="btn btn-outline-primary">
                            <i class="fas fa-list"></i> Audit Ledger
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Transactions -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Recent Transactions</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Member</th>
                                    <th>Member ID</th>
                                    <th>Transaction Type</th>
                                    <th>Amount</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if($recentTransactions && count($recentTransactions) > 0)
                                    @foreach($recentTransactions as $transaction)
                                        <tr>
                                            <td>
                                                <strong>{{ $transaction->member?->full_name ?? 'N/A' }}</strong>
                                            </td>
                                            <td>
                                                <small class="text-muted">{{ $transaction->member?->member_id ?? 'N/A' }}</small>
                                            </td>
                                            <td>
                                                <span class="badge bg-light text-dark">{{ str_replace('_', ' ', ucfirst($transaction->type)) }}</span>
                                            </td>
                                            <td>
                                                <strong>₱{{ number_format($transaction->total_amount, 2) }}</strong>
                                            </td>
                                            <td>
                                                <small class="text-muted">{{ $transaction->created_at->format('M d, Y') }}</small>
                                            </td>
                                            <td>
                                                <span class="badge bg-success">Completed</span>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="6" class="text-center text-muted py-4">No transactions yet</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                    <div class="text-center mt-3">
                        <a href="/receipt-log" class="btn btn-primary btn-sm">View All Transactions</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- System Features -->
    <div class="row mt-4">
        <div class="col-lg-6 mb-3">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-shield-alt text-primary"></i> Security Features
                    </h5>
                </div>
                <div class="card-body">
                    <ul style="list-style: none; padding: 0;">
                        <li class="mb-2">
                            <i class="fas fa-check text-success"></i> 
                            <strong>Role-Based Access Control</strong>
                            <p class="text-muted ms-4 mb-0" style="font-size: 0.9rem;">Members only see their own data</p>
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check text-success"></i> 
                            <strong>3-Strike Rule</strong>
                            <p class="text-muted ms-4 mb-0" style="font-size: 0.9rem;">Account locked for 3 minutes after 3 failed attempts</p>
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check text-success"></i> 
                            <strong>Password Policy</strong>
                            <p class="text-muted ms-4 mb-0" style="font-size: 0.9rem;">8-12 chars with mixed case, numbers, and special chars</p>
                        </li>
                        <li>
                            <i class="fas fa-check text-success"></i> 
                            <strong>Complete Audit Trail</strong>
                            <p class="text-muted ms-4 mb-0" style="font-size: 0.9rem;">All activities logged with timestamps and IP addresses</p>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-lg-6 mb-3">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-calculator text-success"></i> Financial Features
                    </h5>
                </div>
                <div class="card-body">
                    <ul style="list-style: none; padding: 0;">
                        <li class="mb-2">
                            <i class="fas fa-check text-success"></i> 
                            <strong>Interest-First Payments</strong>
                            <p class="text-muted ms-4 mb-0" style="font-size: 0.9rem;">All payments apply to interest first, then principal</p>
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check text-success"></i> 
                            <strong>Loan Consolidation</strong>
                            <p class="text-muted ms-4 mb-0" style="font-size: 0.9rem;">Top-up existing loans instead of creating new entries</p>
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check text-success"></i> 
                            <strong>Smart Calculations</strong>
                            <p class="text-muted ms-4 mb-0" style="font-size: 0.9rem;">Accurate compound interest formulas using M = P[r(1+r)^n]/[(1+r)^n-1]</p>
                        </li>
                        <li>
                            <i class="fas fa-check text-success"></i> 
                            <strong>Loan Workflows</strong>
                            <p class="text-muted ms-4 mb-0" style="font-size: 0.9rem;">Request → Approve → Disburse → Manage → Collect</p>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
