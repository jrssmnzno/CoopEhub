@extends('layouts.app')

@section('title', 'Edit Meeting')
@section('subtitle', 'Modify meeting details')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card" style="border: none; border-radius: 10px; box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
                <div style="background: linear-gradient(135deg, #00a86b 0%, #008b5e 100%); color: white; padding: 2rem; border-radius: 10px 10px 0 0;">
                    <h3 style="margin: 0; font-weight: 600;">Edit Meeting</h3>
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

                    <form action="{{ route('admin.meetings.update', $meeting) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <label for="title" class="form-label" style="font-weight: 500;">Meeting Title *</label>
                            <input 
                                type="text" 
                                class="form-control @error('title') is-invalid @enderror" 
                                id="title" 
                                name="title" 
                                value="{{ old('title', $meeting->title) }}"
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
                            >{{ old('objective', $meeting->objective) }}</textarea>
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
                                    value="{{ old('meeting_date', $meeting->meeting_date->format('Y-m-d\TH:i')) }}"
                                    required
                                    style="border-radius: 8px; padding: 0.75rem; border: 1px solid #ddd;"
                                >
                                <small class="form-text text-muted" style="display: block; margin-top: 0.5rem;">
                                    <i class="fas fa-clock"></i> Selected time: <span id="datePreview" style="font-weight: 500; color: #00a86b;">{{ $meeting->meeting_date->setTimezone('Asia/Manila')->format('l, F j, Y \\a\\t g:i A') }}</span>
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
                                    value="{{ old('location', $meeting->location) }}"
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
                                <i class="fas fa-save"></i> Save Changes
                            </button>
                            <a href="{{ route('admin.meetings.show', $meeting) }}" class="btn btn-secondary" style="border-radius: 8px; padding: 0.75rem 2rem;">
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
    
    function updateDatePreview() {
        if (dateInput.value) {
            const date = new Date(dateInput.value + ':00'); // Add seconds for proper parsing
            const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit', timeZone: 'Asia/Manila' };
            const formatted = date.toLocaleDateString('en-US', options);
            datePreview.textContent = formatted;
        }
    }
    
    dateInput.addEventListener('change', updateDatePreview);
    dateInput.addEventListener('input', updateDatePreview);
</script>@endsection
