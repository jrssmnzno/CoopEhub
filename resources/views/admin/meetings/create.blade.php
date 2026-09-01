@extends('layouts.app')

@section('title', 'Schedule New Meeting')
@section('subtitle', 'Create a new meeting')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card" style="border: none; border-radius: 10px; box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
                <div style="background: linear-gradient(135deg, #00a86b 0%, #008b5e 100%); color: white; padding: 2rem; border-radius: 10px 10px 0 0;">
                    <h3 style="margin: 0; font-weight: 600;">Schedule New Meeting</h3>
                </div>

                <div class="card-body" style="padding: 2rem;">
                    @if ($errors->any())
                        <div class="alert alert-danger" role="alert">
                            <strong>Error:</strong>
                            <ul style="margin: 0.5rem 0 0; padding-left: 1.5rem;">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('admin.meetings.store') }}" method="POST">
                        @csrf

                        <div class="mb-4">
                            <label for="title" class="form-label" style="font-weight: 500;">Meeting Title *</label>
                            <input 
                                type="text" 
                                class="form-control @error('title') is-invalid @enderror" 
                                id="title" 
                                name="title" 
                                value="{{ old('title') }}"
                                placeholder="e.g., Monthly Board Meeting"
                                required
                                style="border-radius: 8px; padding: 0.75rem; border: 1px solid #ddd;"
                            >
                            @error('title')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="objective" class="form-label" style="font-weight: 500;">Objective *</label>
                            <textarea 
                                class="form-control @error('objective') is-invalid @enderror" 
                                id="objective" 
                                name="objective" 
                                rows="4"
                                placeholder="Describe the purpose and objectives of this meeting..."
                                required
                                style="border-radius: 8px; padding: 0.75rem; border: 1px solid #ddd;"
                            >{{ old('objective') }}</textarea>
                            @error('objective')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label for="meeting_date" class="form-label" style="font-weight: 500;">Date & Time (Philippines) *</label>
                                <input 
                                    type="datetime-local" 
                                    class="form-control @error('meeting_date') is-invalid @enderror" 
                                    id="meeting_date" 
                                    name="meeting_date" 
                                    value="{{ old('meeting_date') }}"
                                    required
                                    style="border-radius: 8px; padding: 0.75rem; border: 1px solid #ddd;"
                                >
                                <small class="form-text text-muted" style="display: block; margin-top: 0.5rem;">
                                    <i class="fas fa-clock"></i> Selected time: <span id="datePreview" style="font-weight: 500; color: #00a86b;">Not selected</span>
                                </small>
                                <small class="form-text text-danger" id="dateError" style="display: none; margin-top: 0.5rem;">
                                    <i class="fas fa-exclamation-circle"></i> <span id="dateErrorText"></span>
                                </small>
                                @error('meeting_date')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-4">
                                <label for="location" class="form-label" style="font-weight: 500;">Location *</label>
                                <input 
                                    type="text" 
                                    class="form-control @error('location') is-invalid @enderror" 
                                    id="location" 
                                    name="location" 
                                    value="{{ old('location') }}"
                                    placeholder="e.g., Conference Room A"
                                    required
                                    style="border-radius: 8px; padding: 0.75rem; border: 1px solid #ddd;"
                                >
                                @error('location')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div style="display: flex; gap: 1rem; margin-top: 2rem;">
                            <button type="submit" class="btn btn-success" style="border-radius: 8px; padding: 0.75rem 2rem; font-weight: 500;">
                                <i class="fas fa-check"></i> Schedule Meeting
                            </button>
                            <a href="{{ route('admin.meetings.index') }}" class="btn btn-secondary" style="border-radius: 8px; padding: 0.75rem 2rem;">
                                <i class="fas fa-times"></i> Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    // Display selected date in natural language format
    const dateInput = document.getElementById('meeting_date');
    const datePreview = document.getElementById('datePreview');
    const dateError = document.getElementById('dateError');
    const dateErrorText = document.getElementById('dateErrorText');
    const submitBtn = document.querySelector('button[type="submit"]');
    
    function setMinDate() {
        // Get the current date and time in Philippines timezone
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
            const date = new Date(dateInput.value + ':00'); // Add seconds for proper parsing
            const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit', timeZone: 'Asia/Manila' };
            const formatted = date.toLocaleDateString('en-US', options);
            
            // Check if the date is in the past
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
    
    // Set minimum date on page load
    window.addEventListener('load', function() {
        setMinDate();
        updateDatePreview();
    });
    
    // Update min date periodically (every minute)
    setInterval(setMinDate, 60000);
</script>@endsection
