<?php

namespace App\Http\Controllers\Admin;

use App\Models\Meeting;
use App\Models\Member;
use App\Models\Attendance;
use App\Http\Controllers\Controller;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;

class MeetingManagementController extends Controller
{
    /**
     * Display all meetings
     */
    public function index(): View
    {
        $meetings = Meeting::orderBy('meeting_date', 'desc')->paginate(15);

        return view('admin.meetings.index', [
            'meetings' => $meetings,
        ]);
    }

    /**
     * Show the form for creating a new meeting
     */
    public function create(): View
    {
        return view('admin.meetings.create');
    }

    /**
     * Store a newly created meeting
     */
    public function store(Request $request): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'objective' => ['required', 'string'],
            'meeting_date' => ['required', 'date_format:Y-m-d\\TH:i', 'after_or_equal:now'],
            'location' => ['required', 'string', 'max:255'],
        ]);

        $validated['meeting_date'] = \Carbon\Carbon::createFromFormat('Y-m-d\\TH:i', $validated['meeting_date']);
        $validated['status'] = 'draft';

        $meeting = Meeting::create($validated);

        // Return JSON for AJAX requests
        if ($request->ajax() || $request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Meeting created successfully.',
                'meeting' => $meeting,
                'redirect_url' => route('admin.meetings.show', $meeting),
            ]);
        }

        return redirect()->route('admin.meetings.show', $meeting)->with('success', 'Meeting created successfully.');
    }

    /**
     * Display the specified meeting
     */
    public function show(Meeting $meeting): View
    {
        $meeting->load('attendances.member');
        $attendanceStats = [
            'present' => $meeting->getPresentCount(),
            'absent' => $meeting->getAbsentCount(),
            'excused' => $meeting->getExcusedCount(),
        ];

        return view('admin.meetings.show', [
            'meeting' => $meeting,
            'attendanceStats' => $attendanceStats,
        ]);
    }

    /**
     * Show the form for editing a meeting
     */
    public function edit(Meeting $meeting): View
    {
        return view('admin.meetings.edit', [
            'meeting' => $meeting,
        ]);
    }

    /**
     * Update the specified meeting
     */
    public function update(Request $request, Meeting $meeting): RedirectResponse
    {
        // Cannot edit if attendance is open or meeting is completed
        if ($meeting->isAttendanceOpen() || $meeting->status === 'completed') {
            return back()->withErrors('Cannot edit meeting while attendance is in progress or meeting is completed.');
        }

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'objective' => ['required', 'string'],
            'meeting_date' => ['required', 'date_format:Y-m-d\\TH:i', 'after_or_equal:now'],
            'location' => ['required', 'string', 'max:255'],
        ]);

        $validated['meeting_date'] = \Carbon\Carbon::createFromFormat('Y-m-d\\TH:i', $validated['meeting_date']);

        $meeting->update($validated);

        return redirect()->route('admin.meetings.show', $meeting)->with('success', 'Meeting updated successfully.');
    }

    /**
     * Delete the specified meeting
     */
    public function destroy(Meeting $meeting): RedirectResponse
    {
        if ($meeting->isAttendanceOpen() || $meeting->status === 'completed') {
            return back()->withErrors('Cannot delete meeting while attendance is in progress or meeting is completed.');
        }

        $meeting->delete();

        return redirect()->route('admin.meetings.index')->with('success', 'Meeting deleted successfully.');
    }

    /**
     * Publish a draft meeting (change status to scheduled)
     */
    public function publishDraft(Meeting $meeting): RedirectResponse
    {
        if ($meeting->status !== 'draft') {
            return back()->withErrors('Only draft meetings can be published.');
        }

        $meeting->update(['status' => 'scheduled']);

        return back()->with('success', 'Meeting published successfully and is now scheduled.');
    }

    /**
     * Open attendance for the meeting
     */
    public function openAttendance(Meeting $meeting): RedirectResponse
    {
        if ($meeting->status !== 'scheduled') {
            return back()->withErrors('Can only open attendance for scheduled meetings.');
        }

        $meeting->openAttendance();

        return back()->with('success', 'Attendance is now open. Members can check in.');
    }

    /**
     * Close attendance for the meeting
     */
    public function closeAttendance(Meeting $meeting): RedirectResponse
    {
        if (!$meeting->isAttendanceOpen()) {
            return back()->withErrors('Attendance is not currently open.');
        }

        $meeting->closeAttendance();

        return back()->with('success', 'Attendance has been closed and meeting marked as completed.');
    }

    /**
     * Mark member as excused
     */
    public function markExcused(Request $request, Meeting $meeting): JsonResponse
    {
        $validated = $request->validate([
            'member_id' => ['required', 'exists:members,id'],
            'remarks' => ['nullable', 'string', 'max:500'],
        ]);

        $attendance = Attendance::where('meeting_id', $meeting->id)
            ->where('member_id', $validated['member_id'])
            ->first();

        if (!$attendance) {
            return response()->json(['success' => false, 'message' => 'Attendance record not found.'], 404);
        }

        $attendance->markExcused($validated['remarks'] ?? null);

        return response()->json(['success' => true, 'message' => 'Member marked as excused.']);
    }

    /**
     * Get meeting statistics
     */
    public function getStats(Meeting $meeting): JsonResponse
    {
        return response()->json([
            'present' => $meeting->getPresentCount(),
            'absent' => $meeting->getAbsentCount(),
            'excused' => $meeting->getExcusedCount(),
            'total' => $meeting->attendances()->count(),
        ]);
    }
}
