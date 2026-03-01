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
                            JS
                        </div>
                    </div>
                    <div class="mb-3 pb-3 border-bottom">
                        <h5 class="mb-1">John Smith</h5>
                        <p class="text-muted mb-0">MEM-001</p>
                    </div>
                    <div class="mb-2">
                        <small class="text-muted">Email</small>
                        <div>john@example.com</div>
                    </div>
                    <div class="mb-2">
                        <small class="text-muted">Phone</small>
                        <div>(+63) 912-345-6789</div>
                    </div>
                    <div class="mb-2">
                        <small class="text-muted">Member Since</small>
                        <div>January 15, 2023</div>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted">Status</small>
                        <div><span class="badge status-active">Active</span></div>
                    </div>
                    <div class="d-grid gap-2">
                        <a href="{{ route('members.edit', 1) }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-edit"></i> Edit Information
                        </a>
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
                            <div>John Smith</div>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted">Date of Birth</small>
                            <div>May 15, 1990</div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <small class="text-muted">Relationship Status</small>
                            <div>Married</div>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted">Employment Status</small>
                            <div>Employed</div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <small class="text-muted">Monthly Income</small>
                            <div>₱50,000.00</div>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted">Member Type</small>
                            <div>Individual</div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <small class="text-muted">Address</small>
                            <div>123 Main Street, Barangay, City</div>
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
                            <div>Jane Smith</div>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted">Phone</small>
                            <div>(+63) 923-456-7890</div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <small class="text-muted">Relationship</small>
                            <div>Spouse</div>
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
                    <button class="btn btn-sm btn-primary">
                        <i class="fas fa-plus"></i> New Loan
                    </button>
                </div>
                <div class="card-body">
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
                                <tr>
                                    <td><strong>LOAN-2024-001</strong></td>
                                    <td>₱45,000.00</td>
                                    <td>12% p.a.</td>
                                    <td>₱43,000.00</td>
                                    <td>₱2,100.00</td>
                                    <td><span class="badge status-active">Active</span></td>
                                    <td>
                                        <a href="{{ route('loan-portfolio.index') }}" class="btn btn-sm btn-outline-primary">Details</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>LOAN-2024-002</strong></td>
                                    <td>₱20,000.00</td>
                                    <td>10% p.a.</td>
                                    <td>₱10,000.00</td>
                                    <td>₱1,700.00</td>
                                    <td><span class="badge status-active">Active</span></td>
                                    <td>
                                        <a href="{{ route('loan-portfolio.index') }}" class="btn btn-sm btn-outline-primary">Details</a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
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
                    <div class="table-responsive">
                        <table class="table table-sm table-hover">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Type</th>
                                    <th>Principal</th>
                                    <th>Interest</th>
                                    <th>Total Amount</th>
                                    <th>Reference</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>{{ now()->format('M d, Y') }}</td>
                                    <td>Payment</td>
                                    <td class="amount-positive">₱2,000.00</td>
                                    <td class="amount-positive">₱500.00</td>
                                    <td class="text-primary"><strong>₱2,500.00</strong></td>
                                    <td>PYMNT-001</td>
                                </tr>
                                <tr>
                                    <td>{{ now()->subMonth()->format('M d, Y') }}</td>
                                    <td>Payment</td>
                                    <td class="amount-positive">₱2,000.00</td>
                                    <td class="amount-positive">₱500.00</td>
                                    <td class="text-primary"><strong>₱2,500.00</strong></td>
                                    <td>PYMNT-000</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
