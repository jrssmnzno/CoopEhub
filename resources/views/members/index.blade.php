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
    <div class="card mb-4">
        <div class="card-body d-flex justify-content-between align-items-center">
            <input type="text" class="form-control me-2" style="max-width: 300px;" placeholder="Search members...">
            <a href="{{ route('members.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Add New Member
            </a>
        </div>
    </div>

    <!-- Members Table -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Member Directory</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Contact</th>
                            <th>Member Since</th>
                            <th>Status</th>
                            <th>Total Loans</th>
                            <th>Outstanding Balance</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($members as $member)
                            <tr>
                                <td>
                                    <div>
                                        <strong>{{ $member->name }}</strong><br>
                                        <small class="text-muted">ID: {{ $member->member_id }}</small>
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <div>{{ $member->contact }}</div>
                                        <small class="text-muted">{{ $member->email }}</small>
                                    </div>
                                </td>
                                <td>{{ \Carbon\Carbon::parse($member->joined)->format('M d, Y') }}</td>
                                <td>
                                    @if($member->status === 'active')
                                        <span class="badge bg-success">Active</span>
                                    @elseif($member->status === 'inactive')
                                        <span class="badge bg-secondary">Inactive</span>
                                    @else
                                        <span class="badge bg-warning">{{ ucfirst($member->status) }}</span>
                                    @endif
                                </td>
                                <td>{{ $member->totalLoans }}</td>
                                <td class="text-center">
                                    <strong>₱{{ number_format($member->balance, 2) }}</strong>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('members.show', $member->id) }}" class="btn btn-sm btn-outline-primary">View</a>
                                        <a href="{{ route('members.edit', $member->id) }}" class="btn btn-sm btn-outline-secondary">Edit</a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">
                                    <i class="fas fa-inbox" style="font-size: 2rem; opacity: 0.5; margin-bottom: 0.5rem; display: block;"></i>
                                    No members found. <a href="{{ route('members.create') }}">Add the first member</a>
                                </td>
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
                    <li class="page-item"><a class="page-link" href="#">Next</a></li>
                </ul>
            </nav>
        </div>
    </div>
</div>
@endsection
