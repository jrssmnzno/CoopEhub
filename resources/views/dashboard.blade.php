@extends('layouts.app')

@section('title', 'Dashboard')
@section('subtitle', 'Welcome to Coop eHub Loan Management System')

@section('content')
<div class="container-fluid">
    <!-- Dashboard Stats -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="summary-card">
                <div class="summary-card-title">Total Members</div>
                <div class="summary-card-value">{{ $totalMembers ?? 0 }}</div>
                <small class="text-muted">Active accounts</small>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="summary-card success">
                <div class="summary-card-title">Total Loans Outstanding</div>
                <div class="summary-card-value">₱{{ number_format($totalLoans ?? 0, 2) }}</div>
                <small class="text-muted">Principal balance</small>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="summary-card warning">
                <div class="summary-card-title">Total Collections</div>
                <div class="summary-card-value">₱{{ number_format($totalCollections ?? 0, 2) }}</div>
                <small class="text-muted">This month</small>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="summary-card danger">
                <div class="summary-card-title">Overdue Accounts</div>
                <div class="summary-card-value">{{ $overdueAccounts ?? 0 }}</div>
                <small class="text-muted">Requiring attention</small>
            </div>
        </div>
    </div>

    <!-- Recent Activities -->
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Recent Transactions</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Member Entity</th>
                                    <th>Transaction Type</th>
                                    <th>Amount</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- Sample Data - Replace with actual data from controller --}}
                                <tr>
                                    <td>John Smith</td>
                                    <td>Payment</td>
                                    <td class="amount-positive">+ ₱5,000.00</td>
                                    <td>{{ now()->format('M d, Y') }}</td>
                                    <td><span class="badge status-active">Completed</span></td>
                                </tr>
                                <tr>
                                    <td>Maria Garcia</td>
                                    <td>Loan Disbursement</td>
                                    <td class="amount-negative">- ₱25,000.00</td>
                                    <td>{{ now()->subDay()->format('M d, Y') }}</td>
                                    <td><span class="badge status-active">Completed</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="text-center mt-3">
                        <a href="{{ route('receipt-log.index') }}" class="btn btn-primary btn-sm">View All Transactions</a>
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
                        <a href="{{ route('members.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Add New Member
                        </a>
                        <a href="#" class="btn btn-outline-primary">
                            <i class="fas fa-plus-circle"></i> Create New Loan
                        </a>
                        <a href="{{ route('receipt-log.index') }}" class="btn btn-outline-primary">
                            <i class="fas fa-receipt"></i> Record Receipt
                        </a>
                        <a href="{{ route('audit-ledger.index') }}" class="btn btn-outline-primary">
                            <i class="fas fa-file-alt"></i> View Audit Log
                        </a>
                    </div>
                </div>
            </div>

            <!-- System Info -->
            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="mb-0">System Status</h5>
                </div>
                <div class="card-body">
                    <div class="mb-2">
                        <small class="text-muted">Last Backup</small>
                        <div>{{ now()->format('M d, Y H:i') }}</div>
                    </div>
                    <div class="mb-2">
                        <small class="text-muted">Database Status</small>
                        <div><span class="badge status-active">Online</span></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
