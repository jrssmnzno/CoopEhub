@extends('layouts.app')

@section('title', 'Meeting Management')
@section('subtitle', 'Manage meetings and attendance')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-8">
            <h3 style="margin: 0; font-weight: 600;">Meeting Management</h3>
        </div>
        <div class="col-md-4 text-end">
            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#scheduleMeetingModal">
                <i class="fas fa-plus"></i> Schedule New Meeting
            </button>
        </div>
    </div>

    @php
        $draftMeetings = $meetings->filter(fn($m) => $m->status === 'draft');
        $scheduledMeetings = $meetings->filter(fn($m) => $m->status === 'scheduled');
        $ongoingMeetings = $meetings->filter(fn($m) => $m->status === 'ongoing');
        $completedMeetings = $meetings->filter(fn($m) => $m->status === 'completed');
    @endphp

    @if($meetings->count() > 0)
        <!-- Draft Meetings Section -->
        @if($draftMeetings->count() > 0)
            <div class="mb-5">
                <h5 style="margin-bottom: 1.5rem; font-weight: 600; color: #6c757d;">
                    <i class="fas fa-file-alt"></i> Draft Meetings ({{ $draftMeetings->count() }})
                </h5>
                <div class="row">
                    @foreach($draftMeetings as $meeting)
                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="card" style="border: none; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); height: 100%; overflow: hidden;">
                                <!-- Status Badge -->
                                <div style="background: linear-gradient(135deg, #334b7c 0%, #172746 100%); color: white; padding: 1rem; position: relative;">
                                    <h5 style="margin: 0 0 0.5rem; font-weight: 600; color: white;">{{ $meeting->title }}</h5>
                                    <span style="background: rgba(255, 255, 255, 0.3); color: white; padding: 0.25rem 0.75rem; border-radius: 15px; font-size: 0.75rem; font-weight: 500;">
                                        <i class="fas fa-pen"></i> DRAFT
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

                                    <!-- Actions -->
                                    <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
                                        <a href="{{ route('admin.meetings.show', $meeting) }}" class="btn btn-sm btn-primary" style="flex: 1;">
                                            <i class="fas fa-eye"></i> View
                                        </a>
                                        <a href="{{ route('admin.meetings.edit', $meeting) }}" class="btn btn-sm btn-warning">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Scheduled Meetings Section -->
        @if($scheduledMeetings->count() > 0)
            <div class="mb-5">
                <h5 style="margin-bottom: 1.5rem; font-weight: 600; color: #0d6efd;">
                    <i class="fas fa-calendar-check"></i> Scheduled Meetings ({{ $scheduledMeetings->count() }})
                </h5>
                <div class="row">
                    @foreach($scheduledMeetings as $meeting)
                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="card" style="border: none; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); height: 100%; overflow: hidden;">
                                <!-- Status Badge -->
                                <div style="background: linear-gradient(135deg, #0d6efd 0%, #0b5ed7 100%); color: white; padding: 1rem; position: relative;">
                                    <h5 style="margin: 0 0 0.5rem; font-weight: 600; color: white;">{{ $meeting->title }}</h5>
                                    <span style="background: #ffc107; color: white; padding: 0.25rem 0.75rem; border-radius: 15px; font-size: 0.75rem; font-weight: 500;">
                                        SCHEDULED
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

                                    <!-- Actions -->
                                    <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
                                        <a href="{{ route('admin.meetings.show', $meeting) }}" class="btn btn-sm btn-primary" style="flex: 1;">
                                            <i class="fas fa-eye"></i> View
                                        </a>
                                        <a href="{{ route('admin.meetings.edit', $meeting) }}" class="btn btn-sm btn-warning">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Ongoing Meetings Section -->
        @if($ongoingMeetings->count() > 0)
            <div class="mb-5">
                <h5 style="margin-bottom: 1.5rem; font-weight: 600; color: #00a86b;">
                    <i class="fas fa-events"></i> Ongoing Meetings ({{ $ongoingMeetings->count() }})
                </h5>
                <div class="row">
                    @foreach($ongoingMeetings as $meeting)
                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="card" style="border: none; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); height: 100%; overflow: hidden;">
                                <!-- Status Badge -->
                                <div style="background: linear-gradient(135deg, #00a86b 0%, #008b5e 100%); color: white; padding: 1rem; position: relative;">
                                    <h5 style="margin: 0 0 0.5rem; font-weight: 600; color: white;">{{ $meeting->title }}</h5>
                                    <span style="background: rgba(255, 255, 255, 0.3); color: white; padding: 0.25rem 0.75rem; border-radius: 15px; font-size: 0.75rem; font-weight: 500;">
                                        <i class="fas fa-circle-notch fa-spin"></i> ONGOING
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
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Completed Meetings Section -->
        @if($completedMeetings->count() > 0)
            <div class="mb-5">
                <h5 style="margin-bottom: 1.5rem; font-weight: 600; color: #6c757d;">
                    <i class="fas fa-check-circle"></i> Completed Meetings ({{ $completedMeetings->count() }})
                </h5>
                <div class="row">
                    @foreach($completedMeetings as $meeting)
                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="card" style="border: none; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); height: 100%; overflow: hidden; opacity: 0.8;">
                                <!-- Status Badge -->
                                <div style="background: linear-gradient(135deg, #6c757d 0%, #5a6268 100%); color: white; padding: 1rem; position: relative;">
                                    <h5 style="margin: 0 0 0.5rem; font-weight: 600; color: white;">{{ $meeting->title }}</h5>
                                    <span style="background: rgba(255, 255, 255, 0.3); color: white; padding: 0.25rem 0.75rem; border-radius: 15px; font-size: 0.75rem; font-weight: 500;">
                                        <i class="fas fa-check"></i> COMPLETED
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
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

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
                <p style="margin: 0; color: #999;">
                    No meetings scheduled yet. 
                    <button type="button" class="btn btn-link p-0" data-bs-toggle="modal" data-bs-target="#scheduleMeetingModal" style="text-decoration: none;">
                        Create one now
                    </button>
                </p>
            </div>
        </div>
    @endif
</div>

<!-- Schedule New Meeting Modal -->
<div class="modal fade" id="scheduleMeetingModal" tabindex="-1" aria-labelledby="scheduleMeetingModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="border: none; border-radius: 10px;">
            <div style="background: linear-gradient(135deg, #00a86b 0%, #008b5e 100%); color: white; padding: 1.5rem; border-radius: 10px 10px 0 0;">
                <h5 class="modal-title" id="scheduleMeetingModalLabel" style="margin: 0; font-weight: 600;">Schedule New Meeting</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body" style="padding: 2rem;">
                <div id="modalErrorAlert" class="alert alert-danger" role="alert" style="display: none;">
                    <strong>Error:</strong>
                    <ul id="modalErrorList" style="margin: 0.5rem 0 0; padding-left: 1.5rem;"></ul>
                </div>

                <form id="scheduleMeetingForm" action="{{ route('admin.meetings.store') }}" method="POST">
                    @csrf

                    <div class="mb-4">
                        <label for="modal_title" class="form-label" style="font-weight: 500;">Meeting Title *</label>
                        <input 
                            type="text" 
                            class="form-control" 
                            id="modal_title" 
                            name="title" 
                            placeholder="e.g., Monthly Board Meeting"
                            required
                            style="border-radius: 8px; padding: 0.75rem; border: 1px solid #ddd;"
                        >
                        <span class="invalid-feedback" style="display: none;"></span>
                    </div>

                    <div class="mb-4">
                        <label for="modal_objective" class="form-label" style="font-weight: 500;">Objective *</label>
                        <textarea 
                            class="form-control" 
                            id="modal_objective" 
                            name="objective" 
                            rows="4"
                            placeholder="Describe the purpose and objectives of this meeting..."
                            required
                            style="border-radius: 8px; padding: 0.75rem; border: 1px solid #ddd;"
                        ></textarea>
                        <span class="invalid-feedback" style="display: none;"></span>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <label for="modal_meeting_date" class="form-label" style="font-weight: 500;">Date & Time (Philippines) *</label>
                            <input 
                                type="datetime-local" 
                                class="form-control" 
                                id="modal_meeting_date" 
                                name="meeting_date" 
                                required
                                style="border-radius: 8px; padding: 0.75rem; border: 1px solid #ddd;"
                            >
                            <small class="form-text text-muted" style="display: block; margin-top: 0.5rem;">
                                <i class="fas fa-clock"></i> Selected time: <span id="modal_datePreview" style="font-weight: 500; color: #00a86b;">Not selected</span>
                            </small>
                            <small class="form-text text-danger" id="modal_dateError" style="display: none; margin-top: 0.5rem;">
                                <i class="fas fa-exclamation-circle"></i> <span id="modal_dateErrorText"></span>
                            </small>
                            <span class="invalid-feedback" style="display: none;"></span>
                        </div>

                        <div class="col-md-6 mb-4">
                            <label for="modal_location" class="form-label" style="font-weight: 500;">Location *</label>
                            <input 
                                type="text" 
                                class="form-control" 
                                id="modal_location" 
                                name="location" 
                                placeholder="e.g., Conference Room A"
                                required
                                style="border-radius: 8px; padding: 0.75rem; border: 1px solid #ddd;"
                            >
                            <span class="invalid-feedback" style="display: none;"></span>
                        </div>
                    </div>

                    <div style="display: flex; gap: 1rem; margin-top: 2rem;">
                        <button type="submit" class="btn btn-success" id="modal_submitBtn" style="border-radius: 8px; padding: 0.75rem 2rem; font-weight: 500;">
                            <i class="fas fa-check"></i> Schedule Meeting
                        </button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="border-radius: 8px; padding: 0.75rem 2rem;">
                            <i class="fas fa-times"></i> Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    const dateInput = document.getElementById('modal_meeting_date');
    const datePreview = document.getElementById('modal_datePreview');
    const dateError = document.getElementById('modal_dateError');
    const dateErrorText = document.getElementById('modal_dateErrorText');
    const submitBtn = document.getElementById('modal_submitBtn');
    const scheduleMeetingForm = document.getElementById('scheduleMeetingForm');
    const modalErrorAlert = document.getElementById('modalErrorAlert');
    const modalErrorList = document.getElementById('modalErrorList');
    const scheduleMeetingModal = document.getElementById('scheduleMeetingModal');

    function setMinDate() {
        const now = new Date();
        const year = now.getFullYear();
        const month = String(now.getMonth() + 1).padStart(2, '0');
        const day = String(now.getDate()).padStart(2, '0');
        const hours = String(now.getHours()).padStart(2, '0');
        const minutes = String(now.getMinutes()).padStart(2, '0');
        
        const minDateTime = `${year}-${month}-${day}T${hours}:${minutes}`;
        dateInput.min = minDateTime;
    }
    
    function updateDatePreview() {
        if (dateInput.value) {
            const date = new Date(dateInput.value + ':00');
            const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit', timeZone: 'Asia/Manila' };
            const formatted = date.toLocaleDateString('en-US', options);
            
            const now = new Date();
            const selectedDate = new Date(dateInput.value);
            
            if (selectedDate <= now) {
                dateError.style.display = 'block';
                dateErrorText.textContent = 'Please select a future date and time. Past dates cannot be selected.';
                datePreview.textContent = formatted;
                submitBtn.disabled = true;
                dateInput.classList.add('is-invalid');
            } else {
                dateError.style.display = 'none';
                datePreview.textContent = formatted;
                submitBtn.disabled = false;
                dateInput.classList.remove('is-invalid');
            }
        } else {
            datePreview.textContent = 'Not selected';
            dateError.style.display = 'none';
            submitBtn.disabled = true;
        }
    }
    
    dateInput.addEventListener('change', updateDatePreview);
    dateInput.addEventListener('input', updateDatePreview);
    
    // Handle form submission with AJAX
    scheduleMeetingForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(scheduleMeetingForm);
        const submitBtnText = submitBtn.innerHTML;
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Creating...';
        
        fetch(scheduleMeetingForm.action, {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
            },
            body: formData
        })
        .then(response => {
            if (!response.ok) {
                return response.json().then(data => {
                    throw data;
                });
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                // Hide modal
                const modalInstance = bootstrap.Modal.getInstance(scheduleMeetingModal);
                modalInstance.hide();
                
                // Reset form
                scheduleMeetingForm.reset();
                modalErrorAlert.style.display = 'none';
                datePreview.textContent = 'Not selected';
                
                // Show success message and redirect after 1 second
                window.location.href = data.redirect_url;
            }
        })
        .catch(error => {
            submitBtn.disabled = false;
            submitBtn.innerHTML = submitBtnText;
            
            // Display validation errors
            if (error.errors) {
                modalErrorList.innerHTML = '';
                Object.values(error.errors).forEach(messages => {
                    if (Array.isArray(messages)) {
                        messages.forEach(msg => {
                            const li = document.createElement('li');
                            li.textContent = msg;
                            modalErrorList.appendChild(li);
                        });
                    }
                });
                modalErrorAlert.style.display = 'block';
            } else if (error.message) {
                const li = document.createElement('li');
                li.textContent = error.message;
                modalErrorList.innerHTML = '';
                modalErrorList.appendChild(li);
                modalErrorAlert.style.display = 'block';
            }
        });
    });
    
    // Reset form when modal is closed
    scheduleMeetingModal.addEventListener('hidden.bs.modal', function() {
        scheduleMeetingForm.reset();
        modalErrorAlert.style.display = 'none';
        datePreview.textContent = 'Not selected';
        submitBtn.disabled = true;
        ['modal_title', 'modal_objective', 'modal_meeting_date', 'modal_location'].forEach(id => {
            document.getElementById(id).classList.remove('is-invalid');
        });
    });
    
    // Initialize min date on modal show
    scheduleMeetingModal.addEventListener('show.bs.modal', function() {
        setMinDate();
        updateDatePreview();
    });
    
    // Update min date periodically
    setInterval(setMinDate, 60000);
</script>
@endsection
