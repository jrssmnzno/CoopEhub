@extends('layouts.app')

@section('title', 'Meeting Management')
@section('subtitle', 'Manage meetings and attendance')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-8">
            <h3 style="margin: 0; font-weight: 600;">All Meetings</h3>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('admin.meetings.create') }}" class="btn btn-success">
                <i class="fas fa-plus"></i> Schedule New Meeting
            </a>
        </div>
    </div>

    @if($meetings->count() > 0)
        <div class="row">
            @foreach($meetings as $meeting)
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card" style="border: none; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); height: 100%; overflow: hidden;">
                        <!-- Status Badge -->
                        <div style="background: linear-gradient(135deg, #00a86b 0%, #008b5e 100%); color: white; padding: 1rem; position: relative;">
                            <h5 style="margin: 0 0 0.5rem; font-weight: 600;">{{ $meeting->title }}</h5>
                            <span style="background: {{ $meeting->status === 'scheduled' ? '#ffc107' : ($meeting->status === 'ongoing' ? '#00a86b' : '#6c757d') }}; color: white; padding: 0.25rem 0.75rem; border-radius: 15px; font-size: 0.75rem; font-weight: 500;">
                                {{ ucfirst($meeting->status) }}
                            </span>
                        </div>

                        <div class="card-body">
                            <p style="margin: 0 0 0.5rem; font-size: 0.9rem; color: #666;">
                                <i class="fas fa-calendar-alt"></i> {{ $meeting->meeting_date->setTimezone('Asia/Manila')->format('M d, Y • h:i A') }}
                            </p>
                            <p style="margin: 0 0 0.5rem; font-size: 0.9rem; color: #666;">
                                <i class="fas fa-map-marker-alt"></i> {{ $meeting->location }}
                            </p>
                            <p style="margin: 0 1rem 1rem 0; font-size: 0.85rem; color: #999;">
                                {{ Str::limit($meeting->objective, 80) }}
                            </p>

                            <!-- Attendance stats -->
                            <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 0.5rem; margin-bottom: 1rem; text-align: center;">
                                <div style="background: #f0f0f0; padding: 0.5rem; border-radius: 5px;">
                                    <p style="margin: 0; font-size: 0.8rem; color: #666;">Present</p>
                                    <p style="margin: 0; font-size: 1.1rem; font-weight: 700; color: #00a86b;">{{ $meeting->getPresentCount() }}</p>
                                </div>
                                <div style="background: #f0f0f0; padding: 0.5rem; border-radius: 5px;">
                                    <p style="margin: 0; font-size: 0.8rem; color: #666;">Absent</p>
                                    <p style="margin: 0; font-size: 1.1rem; font-weight: 700; color: #e94b3c;">{{ $meeting->getAbsentCount() }}</p>
                                </div>
                                <div style="background: #f0f0f0; padding: 0.5rem; border-radius: 5px;">
                                    <p style="margin: 0; font-size: 0.8rem; color: #666;">Excused</p>
                                    <p style="margin: 0; font-size: 1.1rem; font-weight: 700; color: #f5a623;">{{ $meeting->getExcusedCount() }}</p>
                                </div>
                            </div>

                            <!-- Actions -->
                            <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
                                <a href="{{ route('admin.meetings.show', $meeting) }}" class="btn btn-sm btn-primary" style="flex: 1;">
                                    <i class="fas fa-eye"></i> View
                                </a>
                                @if($meeting->status === 'scheduled')
                                    <a href="{{ route('admin.meetings.edit', $meeting) }}" class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="row mt-4">
            <div class="col-12">
                {{ $meetings->links() }}
            </div>
        </div>
    @else
        <div class="card" style="border: none; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
            <div class="card-body text-center py-5">
                <i class="fas fa-inbox" style="font-size: 3rem; color: #ccc; margin-bottom: 1rem;"></i>
                <p style="margin: 0; color: #999;">No meetings scheduled yet. <a href="{{ route('admin.meetings.create') }}">Create one now</a></p>
            </div>
        </div>
    @endif
</div>
@endsection
