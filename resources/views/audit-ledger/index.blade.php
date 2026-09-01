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
                    <input type="text" class="form-control" id="filterSearch" placeholder="Search by user or action...">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Activity Type</label>
                    <select class="form-select" id="filterActivity">
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
                    <input type="date" class="form-control" id="filterDate">
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button class="btn btn-primary w-100" onclick="filterAudits()">
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
                        @forelse($audits as $audit)
                        <tr>
                            <td>{{ $audit->timestamp }}</td>
                            <td>{{ $audit->user }}</td>
                            <td>
                                @php
                                    $activityLower = strtolower($audit->activity);
                                    $badgeClass = match($activityLower) {
                                        'login' => 'bg-success',
                                        'create' => 'bg-success',
                                        'update' => 'bg-info',
                                        'delete' => 'bg-danger',
                                        'print' => 'bg-warning',
                                        'export' => 'bg-primary',
                                        default => 'bg-secondary'
                                    };
                                @endphp
                                <span class="badge {{ $badgeClass }}">{{ $audit->activity }}</span>
                            </td>
                            <td>{{ $audit->module }}</td>
                            <td>{{ $audit->reference }}</td>
                            <td>{{ $audit->details }}</td>
                            <td><code style="font-size: 0.8rem;">{{ $audit->ip }}</code></td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">
                                <i class="fas fa-inbox"></i> No audit logs found
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <nav aria-label="Page navigation" class="mt-4">
                {{ $audits->links('pagination::bootstrap-5') }}
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
                        <div class="col-6 text-end"><strong>{{ $statistics['logins'] ?? 0 }}</strong></div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-6">Transactions Created:</div>
                        <div class="col-6 text-end"><strong>{{ $statistics['creates'] ?? 0 }}</strong></div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-6">Updates:</div>
                        <div class="col-6 text-end"><strong>{{ $statistics['updates'] ?? 0 }}</strong></div>
                    </div>
                    <div class="row">
                        <div class="col-6">Reports Generated:</div>
                        <div class="col-6 text-end"><strong>{{ $statistics['reports'] ?? 0 }}</strong></div>
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
                    @forelse($mostActiveUsers as $activeUser)
                    <div class="row mb-2 align-items-center">
                        <div class="col-6">{{ $activeUser->user }}</div>
                        <div class="col-6 text-end">
                            @php
                                $maxCount = $mostActiveUsers->max('count');
                                $percentage = $maxCount > 0 ? ($activeUser->count / $maxCount) * 100 : 0;
                                $barColor = match(true) {
                                    $percentage >= 80 => 'bg-danger',
                                    $percentage >= 60 => 'bg-warning',
                                    $percentage >= 40 => 'bg-info',
                                    default => 'bg-success'
                                };
                            @endphp
                            <div class="progress" style="height: 20px;">
                                <div class="progress-bar {{ $barColor }}" style="width: {{ $percentage }}%">{{ $activeUser->count }}</div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <p class="text-muted text-center py-3">No user activity data available</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function filterAudits() {
    const search = document.getElementById('filterSearch').value;
    const activity = document.getElementById('filterActivity').value;
    const date = document.getElementById('filterDate').value;

    const formData = new FormData();
    if (search) formData.append('search', search);
    if (activity) formData.append('activity', activity);
    if (date) formData.append('date', date);

    fetch('{{ route("audit-ledger.filter") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json',
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        updateAuditTable(data.audits);
        updatePagination(data.pagination);
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error filtering audit logs');
    });
}

function updateAuditTable(audits) {
    const tbody = document.querySelector('#auditTable tbody');
    
    if (audits.length === 0) {
        tbody.innerHTML = '<tr><td colspan="7" class="text-center text-muted py-4"><i class="fas fa-inbox"></i> No audit logs found</td></tr>';
        return;
    }

    const badgeClass = (activity) => {
        const activityLower = activity.toLowerCase();
        return {
            'login': 'bg-success',
            'create': 'bg-success',
            'update': 'bg-info',
            'delete': 'bg-danger',
            'print': 'bg-warning',
            'export': 'bg-primary',
        }[activityLower] || 'bg-secondary';
    };

    tbody.innerHTML = audits.map(audit => `
        <tr>
            <td>${formatDateTime(audit.timestamp)}</td>
            <td>${audit.user}</td>
            <td>
                <span class="badge ${badgeClass(audit.activity)}">${audit.activity}</span>
            </td>
            <td>${audit.module}</td>
            <td>${audit.reference || '-'}</td>
            <td>${audit.details || '-'}</td>
            <td><code style="font-size: 0.8rem;">${audit.ip}</code></td>
        </tr>
    `).join('');
}

function updatePagination(pagination) {
    const nav = document.querySelector('nav[aria-label="Page navigation"]');
    
    if (!nav) return;

    let paginationHTML = '<ul class="pagination justify-content-center">';
    
    // Previous button
    if (pagination.current_page > 1) {
        paginationHTML += `<li class="page-item"><a class="page-link" href="#" onclick="goToPage(${pagination.current_page - 1})">Previous</a></li>`;
    } else {
        paginationHTML += '<li class="page-item disabled"><span class="page-link">Previous</span></li>';
    }

    // Page numbers
    for (let i = 1; i <= pagination.last_page; i++) {
        if (i === pagination.current_page) {
            paginationHTML += `<li class="page-item active"><span class="page-link">${i}</span></li>`;
        } else {
            paginationHTML += `<li class="page-item"><a class="page-link" href="#" onclick="goToPage(${i})">${i}</a></li>`;
        }
    }

    // Next button
    if (pagination.current_page < pagination.last_page) {
        paginationHTML += `<li class="page-item"><a class="page-link" href="#" onclick="goToPage(${pagination.current_page + 1})">Next</a></li>`;
    } else {
        paginationHTML += '<li class="page-item disabled"><span class="page-link">Next</span></li>';
    }

    paginationHTML += '</ul>';
    nav.innerHTML = paginationHTML;
}

function goToPage(page) {
    event.preventDefault();
    const search = document.getElementById('filterSearch').value;
    const activity = document.getElementById('filterActivity').value;
    const date = document.getElementById('filterDate').value;

    const formData = new FormData();
    if (search) formData.append('search', search);
    if (activity) formData.append('activity', activity);
    if (date) formData.append('date', date);
    formData.append('page', page);

    fetch('{{ route("audit-ledger.filter") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json',
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        updateAuditTable(data.audits);
        updatePagination(data.pagination);
        window.scrollTo(0, 0);
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error loading page');
    });
}

function formatDateTime(timestamp) {
    // Timestamp is already formatted by the server in Manila timezone
    // Just return it as-is
    return timestamp;
}
</script>
@endsection
