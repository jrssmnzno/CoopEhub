@extends('layouts.app')

@section('title', 'Loan Requests')
@section('subtitle', 'Review and manage member loan requests')

@section('content')
<div class="container-fluid">
    <!-- Statistics Row -->
    <div class="row mb-4">
        <div class="col-md-4 mb-3">
            <div class="summary-card warning">
                <div class="summary-card-title">Pending Requests</div>
                <div class="summary-card-value" id="pending-count">0</div>
                <small class="text-muted">Awaiting approval</small>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="summary-card success">
                <div class="summary-card-title">Approved</div>
                <div class="summary-card-value" id="approved-count">0</div>
                <small class="text-muted">This month</small>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="summary-card danger">
                <div class="summary-card-title">Rejected</div>
                <div class="summary-card-value" id="rejected-count">0</div>
                <small class="text-muted">This month</small>
            </div>
        </div>
    </div>

    <!-- Pending Requests Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-hourglass-half text-warning"></i> Pending Loan Requests
                    </h5>
                </div>
                <div class="card-body">
                    <div id="pending-requests-list" class="row">
                        <div class="col-12 text-center py-4">
                            <p class="text-muted">Loading pending requests...</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Approved and Rejected Sections -->
    <div class="row">
        <div class="col-lg-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-check-circle text-success"></i> Recently Approved
                    </h5>
                </div>
                <div class="card-body">
                    <div id="approved-requests-list">
                        <p class="text-muted text-center py-3">Loading approved requests...</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-times-circle text-danger"></i> Recently Rejected
                    </h5>
                </div>
                <div class="card-body">
                    <div id="rejected-requests-list">
                        <p class="text-muted text-center py-3">Loading rejected requests...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Request Detail Modal -->
<div class="modal fade" id="requestDetailModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Loan Request Details</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body" id="requestDetailsContent">
                <p class="text-muted">Loading details...</p>
            </div>

            <div class="modal-footer" id="requestDetailsActions">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Rejection Reason Modal -->
<div class="modal fade" id="rejectionModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">Reject Loan Request</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form id="rejectionForm">
                <div class="modal-body">
                    <input type="hidden" id="rejectionRequestId" value="">
                    <div class="mb-3">
                        <label for="rejectionNotes" class="form-label fw-500">Reason for Rejection</label>
                        <textarea class="form-control" id="rejectionNotes" name="notes" rows="4" placeholder="Provide a reason for rejection..." required></textarea>
                        <small class="form-text text-muted">This message will be sent to the member</small>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-times"></i> Reject Request
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Helper function to capitalize first letter of a string
function capitalizeFirst(str) {
    if (!str) return 'Personal';
    return str.charAt(0).toUpperCase() + str.slice(1).toLowerCase();
}

// Helper function to get loan type color
function getLoanTypeColor(type) {
    const colors = {
        'cash': '#2ecc71',
        'swine': '#e74c3c',
        'goat': '#f39c12',
        'fertilizers': '#27ae60'
    };
    return colors[(type || 'cash').toLowerCase()] || '#2ecc71';
}

document.addEventListener('DOMContentLoaded', function() {
    loadPendingRequests();
    document.getElementById('rejectionForm').addEventListener('submit', handleRejection);
});

function loadPendingRequests() {
    fetch('/api/admin/loan-requests/pending')
        .then(response => response.json())
        .then(data => {
            document.getElementById('pending-count').textContent = data.count;
            
            if (data.requests.length === 0) {
                document.getElementById('pending-requests-list').innerHTML = `
                    <div class="col-12 text-center py-4">
                        <i class="fas fa-inbox" style="font-size: 3rem; color: #dee2e6; margin-bottom: 1rem; display: block;"></i>
                        <p class="text-muted mb-0">No pending loan requests</p>
                    </div>
                `;
            } else {
                const html = data.requests.map(req => `
                    <div class="col-md-6 mb-3">
                        <div class="card h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <div>
                                        <h6 class="card-title mb-0">${req.member_name}</h6>
                                        <small class="text-muted">${req.member_id}</small>
                                    </div>
                                    <span class="badge bg-warning text-dark">Pending</span>
                                </div>
                                
                                <div class="row g-3 mb-3">
                                    <div class="col-6">
                                        <small class="text-muted d-block">Amount</small>
                                        <strong>${req.amount}</strong>
                                    </div>
                                    <div class="col-6">
                                        <small class="text-muted d-block">Term</small>
                                        <strong>${req.term}</strong>
                                    </div>
                                    <div class="col-6">
                                        <small class="text-muted d-block">Loan Type</small>
                                        <strong>${req.loan_type ? capitalizeFirst(req.loan_type) : 'Personal'}</strong>
                                    </div>
                                    <div class="col-6">
                                        <small class="text-muted d-block">Monthly Payment</small>
                                        <strong class="text-primary">${req.monthly_payment}</strong>
                                    </div>
                                    <div class="col-6">
                                        <small class="text-muted d-block">Total Interest</small>
                                        <strong class="text-warning">${req.total_interest}</strong>
                                    </div>
                                </div>
                                
                                <small class="text-muted d-block mb-3">
                                    <i class="far fa-calendar"></i> ${req.created_at}
                                </small>
                                
                                <div class="d-grid gap-2">
                                    <button type="button" class="btn btn-success btn-sm" onclick="viewAndApprove(${req.id})">
                                        <i class="fas fa-check"></i> Approve
                                    </button>
                                    <button type="button" class="btn btn-outline-danger btn-sm" onclick="showRejectionModal(${req.id})">
                                        <i class="fas fa-times"></i> Reject
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                `).join('');
                document.getElementById('pending-requests-list').innerHTML = html;
            }
        })
        .catch(error => console.error('Error loading pending requests:', error));
}

function viewAndApprove(requestId) {
    fetch(`/api/admin/loan-requests/${requestId}`)
        .then(response => response.json())
        .then(data => {
            const detailsHtml = `
                <div class="row">
                    <div class="col-12 mb-4">
                        <h6 class="text-muted mb-3">Member Information</h6>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <small class="text-muted d-block">Member ID</small>
                                <strong>${data.member.member_id}</strong>
                            </div>
                            <div class="col-md-6">
                                <small class="text-muted d-block">Name</small>
                                <strong>${data.member.name}</strong>
                            </div>
                            <div class="col-md-6">
                                <small class="text-muted d-block">Email</small>
                                <p class="mb-0">${data.member.email}</p>
                            </div>
                            <div class="col-md-6">
                                <small class="text-muted d-block">Phone</small>
                                <p class="mb-0">${data.member.phone}</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-12 mb-4">
                        <hr>
                        <h6 class="text-muted mb-3">Loan Request Details</h6>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <small class="text-muted d-block">Requested Amount</small>
                                <strong class="text-success" style="font-size: 1.15rem;">${data.request.requested_amount}</strong>
                            </div>
                            <div class="col-md-6">
                                <small class="text-muted d-block">Term</small>
                                <strong>${data.request.requested_term}</strong>
                            </div>
                            <div class="col-md-6">
                                <small class="text-muted d-block">Loan Type</small>
                                <strong><span style="background: ${getLoanTypeColor(data.request.loan_type)}; color: white; padding: 0.25rem 0.75rem; border-radius: 15px; font-size: 0.85rem;">${capitalizeFirst(data.request.loan_type || 'personal')}</span></strong>
                            </div>
                            <div class="col-md-6">
                                <small class="text-muted d-block">Interest Rate</small>
                                <strong>${data.request.interest_rate}</strong>
                            </div>
                            <div class="col-md-6">
                                <small class="text-muted d-block">Monthly Payment</small>
                                <strong class="text-primary">${data.request.monthly_payment}</strong>
                            </div>
                            <div class="col-md-6">
                                <small class="text-muted d-block">Total Interest</small>
                                <strong class="text-warning">${data.request.total_interest}</strong>
                            </div>
                            <div class="col-md-6">
                                <small class="text-muted d-block">Total to Repay</small>
                                <strong style="font-size: 1.15rem;">${data.request.total_amount}</strong>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-12">
                        <hr>
                        <h6 class="text-muted mb-3">Member Financial Status</h6>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <small class="text-muted d-block">Outstanding Balance</small>
                                <strong>${data.member.outstanding_balance}</strong>
                            </div>
                            <div class="col-md-6">
                                <small class="text-muted d-block">Total Active Loans</small>
                                <strong>${data.member.total_loans}</strong>
                            </div>
                        </div>
                    </div>
                </div>
            `;

            document.getElementById('requestDetailsContent').innerHTML = detailsHtml;
            
            const actionsHtml = `
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-success" onclick="approveRequest(${requestId})">
                    <i class="fas fa-check"></i> Approve This Request
                </button>
            `;
            document.getElementById('requestDetailsActions').innerHTML = actionsHtml;

            const modal = new bootstrap.Modal(document.getElementById('requestDetailModal'));
            modal.show();
        })
        .catch(error => {
            console.error('Error loading request details:', error);
            alert('Error loading request details');
        });
}

function approveRequest(requestId) {
    if (!confirm('Are you sure you want to approve this loan request?')) {
        return;
    }

    const notes = prompt('Add approval notes (optional):', '');

    fetch(`/api/admin/loan-requests/${requestId}/approve`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        },
        body: JSON.stringify({
            notes: notes || ''
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Loan request approved successfully! Loan #' + data.loan.loan_number + ' created.');
            bootstrap.Modal.getInstance(document.getElementById('requestDetailModal')).hide();
            loadPendingRequests();
        } else {
            alert('Error: ' + (data.error || 'Failed to approve request'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error approving request');
    });
}

function showRejectionModal(requestId) {
    document.getElementById('rejectionRequestId').value = requestId;
    const modal = new bootstrap.Modal(document.getElementById('rejectionModal'));
    modal.show();
}

function handleRejection(e) {
    e.preventDefault();

    const requestId = document.getElementById('rejectionRequestId').value;
    const notes = document.getElementById('rejectionNotes').value;

    fetch(`/api/admin/loan-requests/${requestId}/reject`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        },
        body: JSON.stringify({
            notes: notes
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Loan request rejected successfully.');
            bootstrap.Modal.getInstance(document.getElementById('rejectionModal')).hide();
            document.getElementById('rejectionForm').reset();
            loadPendingRequests();
        } else {
            alert('Error: ' + (data.error || 'Failed to reject request'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error rejecting request');
    });
}
</script>

@endsection
