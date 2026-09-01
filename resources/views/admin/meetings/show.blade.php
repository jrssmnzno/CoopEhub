@extends('layouts.app')

@section('title', $meeting->title)
@section('subtitle', 'Meeting Details & Attendance')

@section('content')
<div class="container-fluid">
    <!-- Meeting Header -->
    <div style="background: linear-gradient(135deg, #276da9 0%, #2c4cb3 100%); color: white; padding: 2rem; border-radius: 10px; margin-bottom: 2rem; box-shadow: 0 4px 15px rgba(0,168,107,0.2);">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h1 style="margin: 0; font-weight: 700; color: white !important; -webkit-text-fill-color: white !important; background: none !important;">{{ $meeting->title }}</h1>
                <p style="margin: 0.5rem 0 0; opacity: 0.9; color: white !important;">{{ $meeting->meeting_date->setTimezone('Asia/Manila')->format('F d, Y • h:i A') }}</p>
            </div>
            <div class="col-md-4 text-end">
                <span style="background: rgba(255,255,255,0.3); padding: 0.5rem 1rem; border-radius: 20px; font-weight: 500;">
                    {{ ucfirst($meeting->status) }}
                </span>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="row mb-3">
        <div class="col-12">
            @if($meeting->status === 'draft')
                <form action="{{ route('admin.meetings.publishDraft', $meeting) }}" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit" class="btn btn-success" onclick="return confirm('Publish this meeting to schedule it?')">
                        <i class="fas fa-check-circle"></i> Publish Meeting
                    </button>
                </form>
                <a href="{{ route('admin.meetings.edit', $meeting) }}" class="btn btn-primary">
                    <i class="fas fa-pencil-alt"></i> Continue Editing
                </a>
                <form action="{{ route('admin.meetings.destroy', $meeting) }}" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger" onclick="return confirm('Delete this draft meeting?')">
                        <i class="fas fa-trash"></i> Delete Draft
                    </button>
                </form>
            @elseif($meeting->status === 'scheduled')
                <form action="{{ route('admin.meetings.openAttendance', $meeting) }}" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit" class="btn btn-success" onclick="return confirm('Open attendance for this meeting?')">
                        <i class="fas fa-lock-open"></i> Open Attendance
                    </button>
                </form>
                <a href="{{ route('admin.meetings.edit', $meeting) }}" class="btn btn-warning">
                    <i class="fas fa-edit"></i> Edit Meeting
                </a>
                <form action="{{ route('admin.meetings.destroy', $meeting) }}" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger" onclick="return confirm('Delete this meeting?')">
                        <i class="fas fa-trash"></i> Delete
                    </button>
                </form>
            @elseif($meeting->status === 'ongoing')
                <form action="{{ route('admin.meetings.closeAttendance', $meeting) }}" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit" class="btn btn-danger" onclick="return confirm('Close attendance and mark meeting as completed?')">
                        <i class="fas fa-lock"></i> Close Meeting
                    </button>
                </form>
                <a href="{{ route('attendance.kiosk.show', $meeting) }}" class="btn btn-primary" target="_blank">
                    <i class="fas fa-qrcode"></i> Open Kiosk
                </a>
            @endif
        </div>
    </div>

    <div class="row">
        <!-- Meeting Details -->
        <div class="col-md-4 mb-4">
            <div class="card" style="border: none; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
                <div class="card-header" style="background: #f8f9fa; border-bottom: 2px solid #dee2e6;">
                    <h5 style="margin: 0; font-weight: 600;">Meeting Information</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <h6 style="color: #666; margin-bottom: 0.5rem;">Location</h6>
                        <p style="margin: 0; color: #333;">{{ $meeting->location }}</p>
                    </div>
                    <div class="mb-3">
                        <h6 style="color: #666; margin-bottom: 0.5rem;">Objective</h6>
                        <p style="margin: 0; color: #333; line-height: 1.5;">{{ $meeting->objective }}</p>
                    </div>
                    <hr>
                    <div class="mb-3">
                        <h6 style="color: #666; margin-bottom: 0.5rem;">Status</h6>
                        <p style="margin: 0;">
                            <span style="background: {{ $meeting->status === 'draft' ? '#ffc107' : ($meeting->status === 'scheduled' ? '#0d6efd' : ($meeting->status === 'ongoing' ? '#00a86b' : '#6c757d')) }}; color: white; padding: 0.25rem 0.75rem; border-radius: 15px; font-size: 0.85rem; font-weight: 500;">
                                {{ ucfirst($meeting->status) }}
                            </span>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Attendance Statistics -->
        <div class="col-md-4 mb-4">
            <div class="card" style="border: none; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
                <div class="card-header" style="background: #f8f9fa; border-bottom: 2px solid #dee2e6;">
                    <h5 style="margin: 0; font-weight: 600;">Attendance Summary</h5>
                </div>
                <div class="card-body">
                    <div style="display: grid; grid-template-columns: 1fr; gap: 1rem;">
                        <div style="background: linear-gradient(135deg, #e8f5e9 0%, #f1f8e9 100%); padding: 1rem; border-radius: 8px; border-left: 4px solid #00a86b;">
                            <p style="margin: 0; font-size: 0.9rem; color: #2e7d32;">Present</p>
                            <p style="margin: 0.5rem 0 0; font-size: 2rem; font-weight: 700; color: #00a86b;">{{ $attendanceStats['present'] }}</p>
                        </div>
                        <div style="background: linear-gradient(135deg, #ffebee 0%, #ffebee 100%); padding: 1rem; border-radius: 8px; border-left: 4px solid #e94b3c;">
                            <p style="margin: 0; font-size: 0.9rem; color: #c62828;">Absent</p>
                            <p style="margin: 0.5rem 0 0; font-size: 2rem; font-weight: 700; color: #e94b3c;">{{ $attendanceStats['absent'] }}</p>
                        </div>
                        <div style="background: linear-gradient(135deg, #fff3cd 0%, #fff8e1 100%); padding: 1rem; border-radius: 8px; border-left: 4px solid #f5a623;">
                            <p style="margin: 0; font-size: 0.9rem; color: #856404;">Excused</p>
                            <p style="margin: 0.5rem 0 0; font-size: 2rem; font-weight: 700; color: #f5a623;">{{ $attendanceStats['excused'] }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Attendance Rate -->
        <div class="col-md-4 mb-4">
            <div class="card" style="border: none; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
                <div class="card-header" style="background: #f8f9fa; border-bottom: 2px solid #dee2e6;">
                    <h5 style="margin: 0; font-weight: 600;">Attendance Rate</h5>
                </div>
                <div class="card-body">
                    <?php 
                        $total = $attendanceStats['present'] + $attendanceStats['absent'] + $attendanceStats['excused'];
                        $rate = $total > 0 ? round(($attendanceStats['present'] / $total) * 100) : 0;
                    ?>
                    <div style="text-align: center;">
                        <p style="margin: 0; font-size: 0.9rem; color: #666;">Overall Attendance</p>
                        <p style="margin: 1rem 0 0; font-size: 3rem; font-weight: 700; color: #00a86b;">{{ $rate }}%</p>
                        <div style="margin-top: 1rem; width: 100%; height: 8px; background: #e0e0e0; border-radius: 10px; overflow: hidden;">
                            <div style="width: {{ $rate }}%; height: 100%; background: linear-gradient(90deg, #00a86b, #008b5e); transition: width 0.3s ease;"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Attendance Records -->
    <div class="row">
        <div class="col-12">
            <div class="card" style="border: none; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
                <div class="card-header" style="background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%); border-bottom: 2px solid #0a58ca;">
                    <h5 style="margin: 0; font-weight: 700; color: white;">
                        <i class="fas fa-clipboard-list me-2"></i>Member Attendance Records
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table mb-0">
                            <thead>
                                <tr style="background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%); border-bottom: 2px solid #0a58ca;">
                                    <th style="color: white; font-weight: 700; padding: 1rem;">Member ID</th>
                                    <th style="color: white; font-weight: 700; padding: 1rem;">Member Name</th>
                                    <th style="color: white; font-weight: 700; padding: 1rem;">Status</th>
                                    <th style="color: white; font-weight: 700; padding: 1rem;">Time In</th>
                                    <th style="color: white; font-weight: 700; padding: 1rem;">Remarks</th>
                                    @if($meeting->status === 'ongoing')
                                        <th style="color: white; font-weight: 700; padding: 1rem;">Actions</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($meeting->attendances as $attendance)
                                    <tr style="border-bottom: 1px solid #e9ecef; transition: all 0.2s ease;" onmouseover="this.style.backgroundColor='#f8f9fa';" onmouseout="this.style.backgroundColor='white';">
                                        <td style="color: #212529; font-weight: 600; padding: 1rem;">{{ $attendance->member->member_id }}</td>
                                        <td style="color: #212529; font-weight: 500; padding: 1rem;">{{ $attendance->member->full_name }}</td>
                                        <td style="padding: 1rem;">
                                            @if($attendance->status === 'present')
                                                <span style="background: #d1e7dd; color: #0f5132; padding: 0.35rem 0.75rem; border-radius: 20px; font-size: 0.85rem; font-weight: 600; display: inline-block;">
                                                    <i class="fas fa-check-circle me-1"></i>Present
                                                </span>
                                            @elseif($attendance->status === 'absent')
                                                <span style="background: #f8d7da; color: #842029; padding: 0.35rem 0.75rem; border-radius: 20px; font-size: 0.85rem; font-weight: 600; display: inline-block;">
                                                    <i class="fas fa-times-circle me-1"></i>Absent
                                                </span>
                                            @else
                                                <span style="background: #fff3cd; color: #664d03; padding: 0.35rem 0.75rem; border-radius: 20px; font-size: 0.85rem; font-weight: 600; display: inline-block;">
                                                    <i class="fas fa-question-circle me-1"></i>{{ ucfirst($attendance->status) }}
                                                </span>
                                            @endif
                                        </td>
                                        <td style="color: #495057; font-weight: 500; padding: 1rem;">
                                            @if($attendance->time_in)
                                                <i class="fas fa-clock text-primary me-2"></i>{{ $attendance->time_in->setTimezone('Asia/Manila')->format('h:i A') }}
                                            @else
                                                <span style="color: #adb5bd;">N/A</span>
                                            @endif
                                        </td>
                                        <td style="color: #495057; padding: 1rem;">
                                            @if($attendance->remarks)
                                                <span style="background: #e7f3ff; color: #0c5aa0; padding: 0.25rem 0.5rem; border-radius: 4px; font-size: 0.85rem;">{{ $attendance->remarks }}</span>
                                            @else
                                                <span style="color: #adb5bd;">—</span>
                                            @endif
                                        </td>
                                        @if($meeting->status === 'ongoing')
                                            <td style="padding: 1rem;">
                                                @if($attendance->status === 'absent')
                                                    <button class="btn btn-sm btn-outline-warning" data-member-id="{{ $attendance->member->id }}" onclick="markExcused(this)" style="border-color: #ff9800; color: #ff9800; font-weight: 600;">
                                                        <i class="fas fa-exclamation-circle me-1"></i>Excuse
                                                    </button>
                                                @else
                                                    <span style="color: #adb5bd; font-size: 0.85rem;">—</span>
                                                @endif
                                            </td>
                                        @endif
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" style="text-align: center; padding: 3rem 2rem; color: #6c757d;">
                                            <i class="fas fa-inbox" style="font-size: 2rem; color: #dee2e6; display: block; margin-bottom: 1rem;"></i>
                                            No attendance records yet.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function markExcused(button) {
    const memberId = button.getAttribute('data-member-id');
    const remarks = prompt('Enter remarks (optional):');
    
    if (remarks === null) return;

    const token = document.querySelector('input[name="_token"]')?.value || document.querySelector('meta[name="csrf-token"]')?.content;

    fetch('{{ route("admin.meetings.markExcused", $meeting) }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': token,
        },
        body: JSON.stringify({
            member_id: memberId,
            remarks: remarks || null,
        }),
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            location.reload();
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while updating the status.');
    });
}
</script>
@endsection
