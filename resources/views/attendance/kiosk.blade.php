@extends('layouts.app')

@section('title', 'Attendance Check-in')
@section('subtitle', 'Meeting Check-in Kiosk')

@section('content')
<div class="attendance-kiosk" style="background: linear-gradient(135deg, #f0f9ff 0%, #e0f7ff 100%); min-height: 100vh; padding: 2rem 0; display: flex; align-items: center; justify-content: center;">
    <div class="container" style="max-width: 600px;">
        <!-- Meeting Information Card -->
        <div class="card" style="border: none; border-radius: 15px; box-shadow: 0 10px 40px rgba(0,0,0,0.1); margin-bottom: 2rem; overflow: hidden;">
            <div style="background: linear-gradient(135deg, #1f439f 0%, #021c68 100%); color: white; padding: 2rem;">
                <h2 style="margin: 0; font-weight: 700; font-size: 1.8rem; color: white;" id="meetingTitle">{{ $meeting->title }}</h2>
                <p style="margin: 0.5rem 0 0; opacity: 0.9; font-size: 1.1rem; color: white;">{{ $meeting->meeting_date->setTimezone('Asia/Manila')->format('F d, Y • h:i A') }}</p>
            </div>
            <div class="card-body">
                <div style="margin-bottom: 1.5rem;">
                    <h6 style="color: #666; margin-bottom: 0.5rem; font-weight: 500;">Objective:</h6>
                    <p style="margin: 0; color: #333; font-size: 1rem;" id="meetingObjective">{{ $meeting->objective }}</p>
                </div>
                <div style="margin-bottom: 1.5rem;">
                    <h6 style="color: #666; margin-bottom: 0.5rem; font-weight: 500;">Location:</h6>
                    <p style="margin: 0; color: #333; font-size: 1rem;" id="meetingLocation">{{ $meeting->location }}</p>
                </div>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <div>
                        <h6 style="color: #666; margin-bottom: 0.5rem; font-weight: 500;">Present</h6>
                        <p style="margin: 0; color: #00a86b; font-size: 1.5rem; font-weight: 700;" id="presentCount">0</p>
                    </div>
                    <div>
                        <h6 style="color: #666; margin-bottom: 0.5rem; font-weight: 500;">Total Members</h6>
                        <p style="margin: 0; color: #4a90e2; font-size: 1.5rem; font-weight: 700;" id="totalCount">0</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Check-in Form -->
        <div class="card" style="border: none; border-radius: 15px; box-shadow: 0 10px 40px rgba(0,0,0,0.1);">
            <div class="card-body" style="padding: 2.5rem;">
                <h4 style="margin-bottom: 2rem; font-weight: 600; text-align: center; color: #333;">Enter Your Member ID</h4>
                
                <form id="attendanceForm" style="display: flex; flex-direction: column; gap: 1rem;">
                    @csrf
                    <input 
                        type="text" 
                        id="memberIdInput" 
                        name="member_id" 
                        class="form-control" 
                        placeholder="Type your Member ID..." 
                        style="font-size: 1.5rem; padding: 1.5rem; border: 2px solid #ddd; border-radius: 10px; text-align: center; font-weight: 500; letter-spacing: 2px;"
                        autocomplete="off"
                        autofocus
                    >
                </form>

                <!-- Message Display Area -->
                <div id="messageContainer" style="margin-top: 2rem; min-height: 80px;">
                    <!-- Messages will be inserted here -->
                </div>

                <!-- Success Animation -->
                <div id="successAnimation" style="display: none; text-align: center; padding: 2rem; animation: slideUp 0.5s ease;">
                    <div style="font-size: 4rem; color: #00a86b; margin-bottom: 1rem; animation: bounce 0.6s ease;">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <p id="successText" style="font-size: 1.2rem; color: #00a86b; font-weight: 600; margin: 0;"></p>
                </div>
            </div>
        </div>

        <!-- Instructions -->
        <div style="margin-top: 2rem; padding: 1.5rem; background: rgba(0,168,107,0.1); border-radius: 10px; border-left: 4px solid #00a86b;">
            <h6 style="margin: 0 0 0.5rem; color: #008b5e; font-weight: 600;">Instructions:</h6>
            <ul style="margin: 0; padding-left: 1.5rem; color: #666;">
                <li>Enter your Member ID in the field above</li>
                <li>Press Enter or wait for automatic validation</li>
                <li>A success message will appear when checked in</li>
            </ul>
        </div>
    </div>
</div>

<style>
    @keyframes slideUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes bounce {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.1); }
    }

    .error-message {
        background: #ffebee;
        border: 1px solid #ef5350;
        color: #c62828;
        padding: 1rem;
        border-radius: 8px;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        animation: slideUp 0.3s ease;
    }

    .error-message i {
        font-size: 1.5rem;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('attendanceForm');
    const memberIdInput = document.getElementById('memberIdInput');
    const messageContainer = document.getElementById('messageContainer');
    const successAnimation = document.getElementById('successAnimation');
    const successText = document.getElementById('successText');

    // Load meeting details on page load
    loadMeetingDetails();

    // Handle form submission
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        submitAttendance();
    });

    // Handle Enter key
    memberIdInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            submitAttendance();
        }
    });

    function loadMeetingDetails() {
        fetch('/api/attendance/meeting/{{ $meeting->id }}/details')
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('presentCount').textContent = data.meeting.present_count;
                    document.getElementById('totalCount').textContent = data.meeting.total_members;
                }
            })
            .catch(error => console.error('Error loading meeting details:', error));
    }

    function submitAttendance() {
        const memberId = memberIdInput.value.trim();

        if (!memberId) {
            showError('Please enter your Member ID');
            return;
        }

        const token = document.querySelector('input[name="_token"]').value;

        fetch('{{ route("attendance.kiosk.submit", $meeting->id) }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': token,
            },
            body: JSON.stringify({
                member_id: memberId,
            }),
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showSuccess(data.message, data.member_name, data.time_in);
                memberIdInput.value = '';
                
                // Reload meeting details to update counts
                setTimeout(() => {
                    loadMeetingDetails();
                }, 1500);
            } else {
                showError(data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showError('An error occurred. Please try again.');
        });
    }

    function showSuccess(message, memberName, timeIn) {
        messageContainer.style.display = 'none';
        successAnimation.style.display = 'block';
        successText.textContent = memberName + ' - Checked in at ' + timeIn;

        // Clear success animation after 2 seconds
        setTimeout(() => {
            successAnimation.style.display = 'none';
            messageContainer.style.display = 'block';
            messageContainer.innerHTML = '';
        }, 2500);
    }

    function showError(message) {
        messageContainer.innerHTML = `
            <div class="error-message">
                <i class="fas fa-exclamation-circle"></i>
                <span>${message}</span>
            </div>
        `;
    }
});
</script>
@endsection
