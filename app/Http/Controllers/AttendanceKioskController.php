<?php

namespace App\Http\Controllers;

use App\Models\Meeting;
use App\Models\Member;
use App\Models\Attendance;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class AttendanceKioskController extends Controller
{
    /**
     * Show the kiosk attendance page
     */
    public function index(Meeting $meeting): View
    {
        // Check if meeting attendance is open
        if (!$meeting->isAttendanceOpen()) {
            abort(403, 'Attendance is not currently open for this meeting.');
        }

        return view('attendance.kiosk', [
            'meeting' => $meeting,
        ]);
    }

    /**
     * Submit attendance via kiosk (API endpoint)
     */
    public function submit(Request $request, Meeting $meeting): JsonResponse
    {
        // Check if Meeting attendance is open
        if (!$meeting->isAttendanceOpen()) {
            return response()->json([
                'success' => false,
                'message' => 'Attendance is not currently open for this meeting.',
            ], 403);
        }

        // Validate member ID input
        $validated = $request->validate([
            'member_id' => ['required', 'string'],
        ]);

        // Find the member by member_id
        $member = Member::where('member_id', $validated['member_id'])->first();

        // Check if member exists
        if (!$member) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid Member ID. Please check and try again.',
            ], 404);
        }

        // Check if member is already timed in
        $attendance = Attendance::where('meeting_id', $meeting->id)
            ->where('member_id', $member->id)
            ->first();

        if (!$attendance) {
            return response()->json([
                'success' => false,
                'message' => 'Unable to process attendance for this member.',
            ], 400);
        }

        if ($attendance->status === 'present') {
            return response()->json([
                'success' => false,
                'message' => $member->full_name . ' has already checked in.',
            ], 409);
        }

        // Mark member as present
        $attendance->markPresent();

        return response()->json([
            'success' => true,
            'message' => 'Success: ' . $member->full_name . ' is Present',
            'member_name' => $member->full_name,
            'time_in' => $attendance->time_in->setTimezone('Asia/Manila')->format('h:i A'),
        ], 200);
    }

    /**
     * Get meeting details for the kiosk
     */
    public function getMeetingDetails(Meeting $meeting): JsonResponse
    {
        if (!$meeting->isAttendanceOpen()) {
            return response()->json([
                'success' => false,
                'message' => 'Attendance is not open for this meeting.',
            ], 403);
        }

        return response()->json([
            'success' => true,
            'meeting' => [
                'id' => $meeting->id,
                'title' => $meeting->title,
                'objective' => $meeting->objective,
                'meeting_date' => $meeting->meeting_date->format('F d, Y h:i A'),
                'location' => $meeting->location,
                'status' => $meeting->status,
                'present_count' => $meeting->getPresentCount(),
                'total_members' => $meeting->attendances()->count(),
            ],
        ]);
    }
}
