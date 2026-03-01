@extends('layouts.app')

@section('title', 'Audit Ledger')
@section('subtitle', 'System activity and transaction audit log')

@section('content')
<div class="container-fluid">
    <!-- Filter Section -->
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0">Audit Log Filters</h5>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">User/Action</label>
                    <input type="text" class="form-control" placeholder="Search by user or action...">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Activity Type</label>
                    <select class="form-select">
                        <option value="">All Activities</option>
                        <option value="login">Login</option>
                        <option value="create">Create</option>
                        <option value="update">Update</option>
                        <option value="delete">Delete</option>
                        <option value="print">Print</option>
                        <option value="export">Export</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Date Range</label>
                    <input type="date" class="form-control">
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button class="btn btn-primary w-100">
                        <i class="fas fa-search"></i> Filter
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Audit Log Table -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Activity Log</h5>
            <button class="btn btn-sm btn-outline-secondary" onclick="exportTableToCSV('auditTable', 'audit_log.csv')">
                <i class="fas fa-download"></i> Export
            </button>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="auditTable" class="table table-hover transaction-table">
                    <thead>
                        <tr>
                            <th>Timestamp</th>
                            <th>User</th>
                            <th>Activity</th>
                            <th>Module</th>
                            <th>Reference</th>
                            <th>Details</th>
                            <th>IP Address</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- Sample Data - Replace with actual data from controller --}}
                        <tr>
                            <td>{{ now()->format('M d, Y H:i:s') }}</td>
                            <td>Admin User</td>
                            <td><span class="badge bg-success">Create</span></td>
                            <td>Receipt Log</td>
                            <td>#RCP-2024-001</td>
                            <td>Created new receipt entry</td>
                            <td>192.168.1.100</td>
                        </tr>
                        <tr>
                            <td>{{ now()->subMinutes(15)->format('M d, Y H:i:s') }}</td>
                            <td>Officer Juan</td>
                            <td><span class="badge bg-info">Update</span></td>
                            <td>Members</td>
                            <td>MEM-001</td>
                            <td>Updated member contact information</td>
                            <td>192.168.1.101</td>
                        </tr>
                        <tr>
                            <td>{{ now()->subHours(1)->format('M d, Y H:i:s') }}</td>
                            <td>Manager Rosa</td>
                            <td><span class="badge bg-warning">Print</span></td>
                            <td>Promissory Note</td>
                            <td>PN-2024-001</td>
                            <td>Printed promissory note for member</td>
                            <td>192.168.1.102</td>
                        </tr>
                        <tr>
                            <td>{{ now()->subHours(2)->format('M d, Y H:i:s') }}</td>
                            <td>Cashier Mark</td>
                            <td><span class="badge bg-success">Create</span></td>
                            <td>Loan Portfolio</td>
                            <td>LOAN-2024-001</td>
                            <td>Disbursed loan to member</td>
                            <td>192.168.1.103</td>
                        </tr>
                        <tr>
                            <td>{{ now()->subHours(3)->format('M d, Y H:i:s') }}</td>
                            <td>Admin User</td>
                            <td><span class="badge bg-success">Login</span></td>
                            <td>System</td>
                            <td>SESSION-001</td>
                            <td>User logged in successfully</td>
                            <td>192.168.1.100</td>
                        </tr>
                        <tr>
                            <td>{{ now()->subHours(4)->format('M d, Y H:i:s') }}</td>
                            <td>Officer Juan</td>
                            <td><span class="badge bg-primary">Export</span></td>
                            <td>Receipt Log</td>
                            <td>EXPORT-001</td>
                            <td>Exported receipt log to CSV</td>
                            <td>192.168.1.101</td>
                        </tr>
                        <tr>
                            <td>{{ now()->subHours(5)->format('M d, Y H:i:s') }}</td>
                            <td>Manager Rosa</td>
                            <td><span class="badge bg-info">Update</span></td>
                            <td>Loan Portfolio</td>
                            <td>LOAN-2024-002</td>
                            <td>Updated loan payment terms</td>
                            <td>192.168.1.102</td>
                        </tr>
                        <tr>
                            <td>{{ now()->subHours(6)->format('M d, Y H:i:s') }}</td>
                            <td>Cashier Mark</td>
                            <td><span class="badge bg-success">Create</span></td>
                            <td>Members</td>
                            <td>MEM-004</td>
                            <td>Registered new member</td>
                            <td>192.168.1.103</td>
                        </tr>
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

    <!-- Audit Statistics -->
    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Activity Summary (Today)</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-2">
                        <div class="col-6">Logins:</div>
                        <div class="col-6 text-end"><strong>12</strong></div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-6">Transactions Created:</div>
                        <div class="col-6 text-end"><strong>28</strong></div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-6">Updates:</div>
                        <div class="col-6 text-end"><strong>15</strong></div>
                    </div>
                    <div class="row">
                        <div class="col-6">Reports Generated:</div>
                        <div class="col-6 text-end"><strong>8</strong></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Most Active Users (Today)</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-2 align-items-center">
                        <div class="col-6">Admin User</div>
                        <div class="col-6 text-end">
                            <div class="progress" style="height: 20px;">
                                <div class="progress-bar" style="width: 100%">12</div>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-2 align-items-center">
                        <div class="col-6">Officer Juan</div>
                        <div class="col-6 text-end">
                            <div class="progress" style="height: 20px;">
                                <div class="progress-bar bg-success" style="width: 75%">9</div>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-2 align-items-center">
                        <div class="col-6">Manager Rosa</div>
                        <div class="col-6 text-end">
                            <div class="progress" style="height: 20px;">
                                <div class="progress-bar bg-info" style="width: 60%">7</div>
                            </div>
                        </div>
                    </div>
                    <div class="row align-items-center">
                        <div class="col-6">Cashier Mark</div>
                        <div class="col-6 text-end">
                            <div class="progress" style="height: 20px;">
                                <div class="progress-bar bg-warning" style="width: 50%">6</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
