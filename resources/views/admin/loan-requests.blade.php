@extends('layouts.app')

@section('title', 'Loan Requests')
@section('subtitle', 'Review and manage member loan requests')

@section('content')
<!-- Notification Container -->
<div id="notificationContainer" style="position: fixed; top: 20px; right: 20px; z-index: 9999;"></div>

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
                            <div class="spinner-border text-primary mb-3" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
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

// Helper function to show notifications
function showNotification(message, type = 'success', duration = 5000) {
    const container = document.getElementById('notificationContainer');
    const alertClass = type === 'success' ? 'alert-success' : type === 'error' ? 'alert-danger' : 'alert-info';
    const icon = type === 'success' ? 'fas fa-check-circle' : type === 'error' ? 'fas fa-exclamation-circle' : 'fas fa-info-circle';
    
    const notificationEl = document.createElement('div');
    notificationEl.className = `alert ${alertClass} alert-dismissible fade show`;
    notificationEl.role = 'alert';
    notificationEl.innerHTML = `
        <i class="${icon}" style="margin-right: 0.5rem;"></i>
        <strong>${message}</strong>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    `;
    
    container.appendChild(notificationEl);
    
    setTimeout(() => {
        notificationEl.remove();
    }, duration);
}

document.addEventListener('DOMContentLoaded', function() {
    loadPendingRequests();
    loadApprovedRequests();
    loadRejectedRequests();
    document.getElementById('rejectionForm').addEventListener('submit', handleRejection);
});

function loadPendingRequests() {
    console.log('Loading pending requests...');
    fetch('/admin/api/loan-requests/pending')
        .then(response => {
            console.log('Response status:', response.status);
            console.log('Response ok:', response.ok);
            if (!response.ok) {
                return response.text().then(text => {
                    console.error('Response text:', text);
                    throw new Error('Network response was not ok - Status: ' + response.status + ' - ' + text);
                });
            }
            return response.json();
        })
        .then(data => {
            console.log('Received data:', data);
            console.log('Requests array:', data.requests);
            console.log('Requests length:', data.requests ? data.requests.length : 'no requests');
            document.getElementById('pending-count').textContent = data.count;
            
            if (!data.requests || data.requests.length === 0) {
                document.getElementById('pending-requests-list').innerHTML = `
                    <div class="col-12 text-center py-4">
                        <i class="fas fa-inbox" style="font-size: 3rem; color: #dee2e6; margin-bottom: 1rem; display: block;"></i>
                        <p class="text-muted mb-0">No pending loan requests</p>
                    </div>
                `;
            } else {
                try {
                    const html = data.requests.map(req => {
                        console.log('Rendering request:', req);
                        return `
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
                `;
                    }).join('');
                    console.log('Generated HTML length:', html.length);
                    document.getElementById('pending-requests-list').innerHTML = html;
                } catch(error) {
                    console.error('Error rendering requests:', error);
                    showNotification('Error rendering requests: ' + error.message, 'error');
                    document.getElementById('pending-requests-list').innerHTML = `
                        <div class="col-12 text-center py-4">
                            <i class="fas fa-exclamation-triangle text-danger"></i>
                            <p class="text-danger">Error rendering requests. Check console for details.</p>
                        </div>
                    `;
                }
            }
        })
        .catch(error => {
            console.error('Error loading pending requests:', error);
            console.error('Error message:', error.message);
            showNotification('Error loading pending requests: ' + error.message, 'error');
            document.getElementById('pending-requests-list').innerHTML = `
                <div class="col-12 text-center py-4">
                    <i class="fas fa-exclamation-triangle text-danger" style="font-size: 3rem; margin-bottom: 1rem; display: block;"></i>
                    <p class="text-danger mb-2">Error loading requests. Please try again.</p>
                    <small class="text-muted d-block mb-3">Check browser console (F12) for details</small>
                    <button type="button" class="btn btn-primary btn-sm" onclick="loadPendingRequests()">
                        <i class="fas fa-redo"></i> Retry
                    </button>
                </div>
            `;
        });
}

function viewAndApprove(requestId) {
    fetch(`/admin/api/loan-requests/${requestId}`)
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
                    <i class="fas fa-check"></i> Approve Request
                </button>
                <button type="button" class="btn btn-danger" onclick="showRejectionModal(${requestId})">
                    <i class="fas fa-times"></i> Reject Request
                </button>
            `;
            
            document.getElementById('requestDetailsActions').innerHTML = actionsHtml;
            
            const modal = new bootstrap.Modal(document.getElementById('requestDetailModal'));
            modal.show();
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Error loading request details', 'error');
        });
}

function approveRequest(requestId) {
    console.log('Approving request ID:', requestId);
    
    // Show loading notification
    showNotification('Processing approval...', 'info', 2000);
    
    const url = `/admin/api/loan-requests/${requestId}/approve`;
    console.log('Approval URL:', url);
    
    fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            notes: ''
        })
    })
    .then(response => {
        console.log('Response status:', response.status);
        console.log('Response ok:', response.ok);
        
        // Clone the response to read it multiple times
        return response.clone().text().then(text => {
            console.log('Raw response:', text);
            
            if (!response.ok) {
                throw new Error(`HTTP ${response.status}: ${text}`);
            }
            
            // Try to parse as JSON
            try {
                return JSON.parse(text);
            } catch (e) {
                console.error('Failed to parse JSON:', e);
                throw new Error('Invalid JSON response: ' + text.substring(0, 100));
            }
        });
    })
    .then(data => {
        console.log('Approval response data:', data);
        if (data.success) {
            // Close the detail modal
            const detailModal = bootstrap.Modal.getInstance(document.getElementById('requestDetailModal'));
            if (detailModal) {
                detailModal.hide();
            }
            
            // Show success notification with loan number
            showNotification(`✓ Loan request approved successfully! Loan #${data.loan.loan_number} created.`, 'success', 6000);
            
            // Reload the pending requests list
            setTimeout(() => {
                loadPendingRequests();
            }, 500);
        } else {
            showNotification('❌ Error: ' + (data.error || 'Failed to approve request'), 'error', 6000);
        }
    })
    .catch(error => {
        console.error('Error approving request:', error);
        console.error('Error stack:', error.stack);
        showNotification('❌ Error: ' + error.message, 'error', 8000);
    });
}

function showRejectionModal(requestId) {
    document.getElementById('rejectionRequestId').value = requestId;
    
    // Hide detail modal if open
    const detailModal = bootstrap.Modal.getInstance(document.getElementById('requestDetailModal'));
    if (detailModal) {
        detailModal.hide();
    }
    
    // Show rejection modal
    const modal = new bootstrap.Modal(document.getElementById('rejectionModal'));
    modal.show();
}

function handleRejection(e) {
    e.preventDefault();
    
    const requestId = document.getElementById('rejectionRequestId').value;
    const notes = document.getElementById('rejectionNotes').value;
    
    console.log('Rejecting request ID:', requestId);
    console.log('Rejection notes:', notes);
    
    if (!notes || notes.trim() === '') {
        showNotification('⚠️ Please provide a reason for rejection', 'error', 4000);
        return;
    }

    const submitBtn = e.target.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';

    const url = `/admin/api/loan-requests/${requestId}/reject`;
    console.log('Rejection URL:', url);

    fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            notes: notes
        })
    })
    .then(response => {
        console.log('Response status:', response.status);
        console.log('Response ok:', response.ok);
        
        return response.clone().text().then(text => {
            console.log('Raw response:', text);
            
            if (!response.ok) {
                throw new Error(`HTTP ${response.status}: ${text}`);
            }
            
            try {
                return JSON.parse(text);
            } catch (e) {
                console.error('Failed to parse JSON:', e);
                throw new Error('Invalid JSON response: ' + text.substring(0, 100));
            }
        });
    })
    .then(data => {
        console.log('Rejection response data:', data);
        if (data.success) {
            // Close the rejection modal
            const rejectionModal = bootstrap.Modal.getInstance(document.getElementById('rejectionModal'));
            if (rejectionModal) {
                rejectionModal.hide();
            }
            
            // Reset the form
            document.getElementById('rejectionForm').reset();
            
            // Show success notification
            showNotification('✓ Loan request rejected successfully.', 'success', 6000);
            
            // Reload the pending requests list
            setTimeout(() => {
                loadPendingRequests();
            }, 500);
        } else {
            showNotification('❌ Error: ' + (data.error || 'Failed to reject request'), 'error', 6000);
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
        }
    })
    .catch(error => {
        console.error('Error rejecting request:', error);
        console.error('Error stack:', error.stack);
        showNotification('❌ Error: ' + error.message, 'error', 8000);
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;
    });
}

function loadApprovedRequests() {
    fetch('/admin/api/loan-requests/approved')
        .then(response => response.json())
        .then(data => {
            document.getElementById('approved-count').textContent = data.approved_count || 0;
            
            if (!data.requests || data.requests.length === 0) {
                document.getElementById('approved-requests-list').innerHTML = `
                    <p class="text-muted text-center py-3">No approved requests this month</p>
                `;
            } else {
                const html = data.requests.map(req => `
                    <div class="list-group-item p-3 mb-2 border rounded">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h6 class="mb-1">${req.member_name}</h6>
                                <small class="text-muted">${req.member_id}</small>
                            </div>
                            <span class="badge bg-success">Approved</span>
                        </div>
                        <div class="row g-2 mt-2 text-sm">
                            <div class="col-6">
                                <small class="text-muted d-block">Amount</small>
                                <small><strong>${req.amount}</strong></small>
                            </div>
                            <div class="col-6">
                                <small class="text-muted d-block">Type</small>
                                <small><strong>${capitalizeFirst(req.loan_type)}</strong></small>
                            </div>
                        </div>
                        <small class="text-muted d-block mt-2">
                            <i class="far fa-calendar"></i> Approved ${formatDate(req.approved_at)}
                        </small>
                    </div>
                `).join('');
                document.getElementById('approved-requests-list').innerHTML = html;
            }
        })
        .catch(error => {
            console.error('Error loading approved requests:', error);
            document.getElementById('approved-requests-list').innerHTML = `
                <p class="text-danger text-center py-3">Error loading approved requests</p>
            `;
        });
}

function loadRejectedRequests() {
    fetch('/admin/api/loan-requests/rejected')
        .then(response => response.json())
        .then(data => {
            document.getElementById('rejected-count').textContent = data.rejected_count || 0;
            
            if (!data.requests || data.requests.length === 0) {
                document.getElementById('rejected-requests-list').innerHTML = `
                    <p class="text-muted text-center py-3">No rejected requests this month</p>
                `;
            } else {
                const html = data.requests.map(req => `
                    <div class="list-group-item p-3 mb-2 border rounded">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h6 class="mb-1">${req.member_name}</h6>
                                <small class="text-muted">${req.member_id}</small>
                            </div>
                            <span class="badge bg-danger">Rejected</span>
                        </div>
                        <div class="row g-2 mt-2 text-sm">
                            <div class="col-6">
                                <small class="text-muted d-block">Amount</small>
                                <small><strong>${req.amount}</strong></small>
                            </div>
                            <div class="col-6">
                                <small class="text-muted d-block">Type</small>
                                <small><strong>${capitalizeFirst(req.loan_type)}</strong></small>
                            </div>
                        </div>
                        <small class="text-muted d-block mt-2">
                            <i class="fas fa-comment"></i> ${req.reason || 'No reason provided'}
                        </small>
                        <small class="text-muted d-block">
                            <i class="far fa-calendar"></i> Rejected ${formatDate(req.rejected_at)}
                        </small>
                    </div>
                `).join('');
                document.getElementById('rejected-requests-list').innerHTML = html;
            }
        })
        .catch(error => {
            console.error('Error loading rejected requests:', error);
            document.getElementById('rejected-requests-list').innerHTML = `
                <p class="text-danger text-center py-3">Error loading rejected requests</p>
            `;
        });
}

function formatDate(dateString) {
    const date = new Date(dateString);
    const options = { year: 'numeric', month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit' };
    return date.toLocaleDateString('en-US', options);
}
</script>

@endsection
