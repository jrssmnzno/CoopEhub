@if($upcomingMeeting)
    <div class="card" style="border: none; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-bottom: 2rem; border-left: 5px solid #4a90e2;">
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h5 style="margin: 0; color: #4a90e2; font-weight: 600;">
                        <i class="fas fa-calendar-check"></i> Upcoming Meeting
                    </h5>
                    <h3 style="margin: 0.5rem 0; font-weight: 700;">{{ $upcomingMeeting->title }}</h3>
                    <p style="margin: 0.25rem 0; color: #666; font-size: 0.95rem;">
                        <i class="fas fa-clock"></i> {{ $upcomingMeeting->meeting_date->format('F d, Y • h:i A') }}
                    </p>
                    <p style="margin: 0.25rem 0; color: #666; font-size: 0.95rem;">
                        <i class="fas fa-map-marker-alt"></i> {{ $upcomingMeeting->location }}
                    </p>
                    <p style="margin: 0.75rem 0 0; color: #333; font-size: 0.9rem;">
                        <strong>Objective:</strong> {{ $upcomingMeeting->objective }}
                    </p>
                </div>
                <div class="col-md-4 text-end">
                    @if($upcomingMeeting->status === 'ongoing' && Auth::user()->isAdmin())
                        <a href="{{ route('attendance.kiosk.show', $upcomingMeeting) }}" class="btn btn-success" style="border-radius: 8px; padding: 0.75rem 1.5rem;">
                            <i class="fas fa-sign-in-alt"></i> Check In Now
                        </a>
                    @elseif($upcomingMeeting->status !== 'ongoing')
                        <span style="background: #ffc107; color: white; padding: 0.5rem 1rem; border-radius: 20px; font-size: 0.85rem; font-weight: 500;">
                            {{ ucfirst($upcomingMeeting->status) }}
                        </span>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endif
