@extends('layouts.app')

@section('title', 'Members')
@section('subtitle', 'Manage member information and accounts')

@section('content')
<div class="container-fluid">
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Action Bar -->
    <div class="card shadow-md mb-4">
        <div class="card-body d-flex justify-content-between align-items-center gap-3 flex-wrap">
            <div style="flex: 1; min-width: 250px;">
                <input type="text" id="memberSearch" class="form-control" placeholder="🔍 Search members by name, ID, or email..." onkeyup="searchMembers()">
            </div>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addMemberModal">
                <i class="fas fa-plus"></i> Add New Member
            </button>
        </div>
    </div>

    <!-- Members Table -->
    <div class="card shadow-lg">
        <div class="card-header bg-gradient">
            <div class="d-flex justify-content-between align-items-center gap-3">
                <h5 class="mb-0">
                    <i class="fas fa-users me-2"></i>Member Directory
                </h5>
                <small class="text-muted" id="memberCount" style="white-space: nowrap;">Loading...</small>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Name & ID</th>
                            <th>Contact Information</th>
                            <th>Member Since</th>
                            <th>Status</th>
                            <th>Active Loans</th>
                            <th>Outstanding Balance</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="memberTableBody">
                        @forelse($members as $member)
                            <tr>
                                <td>
                                    <div>
                                        <strong class="text-primary">{{ $member->name }}</strong><br>
                                        <small class="text-muted">{{ $member->member_id }}</small>
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <div><i class="fas fa-phone text-primary me-2"></i>{{ $member->contact }}</div>
                                        <small class="text-muted"><i class="fas fa-envelope me-1"></i>{{ $member->email }}</small>
                                    </div>
                                </td>
                                <td>
                                    <span class="text-muted">{{ \Carbon\Carbon::parse($member->joined)->format('M d, Y') }}</span>
                                </td>
                                <td>
                                    @if($member->status === 'active')
                                        <span class="badge status-active">
                                            <i class="fas fa-check-circle me-1"></i>Active
                                        </span>
                                    @elseif($member->status === 'inactive')
                                        <span class="badge status-inactive">
                                            <i class="fas fa-times-circle me-1"></i>Inactive
                                        </span>
                                    @else
                                        <span class="badge status-pending">
                                            <i class="fas fa-clock me-1"></i>{{ ucfirst($member->status) }}
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-info">{{ $member->totalLoans }}</span>
                                </td>
                                <td>
                                    <strong class="text-danger">₱{{ number_format($member->balance, 2) }}</strong>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="{{ route('members.show', $member->id) }}" class="btn btn-outline-primary" title="View Member">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('members.edit', $member->id) }}" class="btn btn-outline-secondary" title="Edit Member">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" class="btn btn-outline-danger" title="Delete Member" onclick="openDeleteModal({{ $member->id }}, '{{ $member->name }}')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    <div style="opacity: 0.5;">
                                        <i class="fas fa-inbox" style="font-size: 2.5rem; color: #dee2e6; display: block; margin-bottom: 1rem;"></i>
                                        <p class="text-muted mb-0">No members found yet</p>
                                        <small class="text-muted"><a href="#" data-bs-toggle="modal" data-bs-target="#addMemberModal">Add the first member</a> to get started</small>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="card-footer bg-light">
                <nav aria-label="Page navigation" id="memberPagination">
                    {{ $members->links('pagination::bootstrap-5') }}
                </nav>
            </div>
        </div>
    </div>
</div>

<!-- Include Add Member Modal -->
@include('members.modals.add-modal')

<!-- Delete Member Modal -->
<div class="modal fade" id="deleteMemberModal" tabindex="-1" aria-labelledby="deleteMemberModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-danger">
                <h5 class="modal-title text-danger" id="deleteMemberModalLabel">
                    <i class="fas fa-exclamation-triangle me-2"></i>Delete Member
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete <strong id="memberNameInModal">this member</strong>?</p>
                <p class="text-muted small mb-0">
                    <i class="fas fa-info-circle me-1"></i>
                    This action cannot be undone. All associated data will be permanently deleted.
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" onclick="confirmDeleteMember()">
                    <i class="fas fa-trash me-2"></i>Delete Member
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Hidden form for delete -->
<form id="deleteMemberForm" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // If there are validation errors, reopen the modal
    @if ($errors->any())
        const modal = new bootstrap.Modal(document.getElementById('addMemberModal'));
        modal.show();
    @endif

    // Handle form reset on modal close
    const addMemberModal = document.getElementById('addMemberModal');
    addMemberModal.addEventListener('hidden.bs.modal', function() {
        // Optionally reset the form when modal is closed without submitting
        // document.getElementById('addMemberForm').reset();
    });

    // Update member count
    updateMemberCount();
});

function updateMemberCount() {
    const tableBody = document.getElementById('memberTableBody');
    const rows = tableBody.querySelectorAll('tr');
    
    // Check if the only row is the empty state
    const isEmptyState = rows.length === 1 && rows[0].querySelector('td[colspan="7"]');
    
    const count = isEmptyState ? 0 : rows.length;
    const memberCountElement = document.getElementById('memberCount');
    
    if (count === 0) {
        memberCountElement.textContent = 'No members';
    } else if (count === 1) {
        memberCountElement.textContent = '1 member';
    } else {
        memberCountElement.textContent = count + ' members';
    }
}
</script>

@endsection

@section('scripts')
<script>
let memberToDeleteId = null;

function openDeleteModal(memberId, memberName) {
    memberToDeleteId = memberId;
    document.getElementById('memberNameInModal').textContent = memberName;
    const deleteModal = new bootstrap.Modal(document.getElementById('deleteMemberModal'));
    deleteModal.show();
}

function confirmDeleteMember() {
    if (memberToDeleteId) {
        const form = document.getElementById('deleteMemberForm');
        form.action = '{{ route("members.destroy", ":id") }}'.replace(':id', memberToDeleteId);
        form.submit();
    }
}

function searchMembers() {
    const search = document.getElementById('memberSearch').value;
    
    if (!search) {
        // Reload page if search is empty
        window.location.reload();
        return;
    }

    const params = new URLSearchParams();
    params.append('search', search);

    fetch('{{ route("members.index") }}?' + params.toString(), {
        headers: {
            'Accept': 'application/json',
        }
    })
    .then(response => response.text())
    .then(html => {
        // Extract the table body from the response
        const parser = new DOMParser();
        const doc = parser.parseFromString(html, 'text/html');
        const newTableBody = doc.querySelector('#memberTableBody');
        const newPagination = doc.querySelector('#memberPagination');
        
        if (newTableBody && newPagination) {
            document.getElementById('memberTableBody').innerHTML = newTableBody.innerHTML;
            document.getElementById('memberPagination').innerHTML = newPagination.innerHTML;
            // Update the member count after loading search results
            updateMemberCount();
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
}
</script>
@endsection
