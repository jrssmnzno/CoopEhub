# Meeting & Attendance System Documentation

## Overview

The Meeting & Attendance system is a comprehensive solution for managing meetings and tracking member attendance. It includes:

1. **Admin Meeting Scheduler** - Create and manage meetings
2. **Kiosk-Style Check-in Interface** - Fast member ID-based attendance checking
3. **Attendance Tracking** - Automatic marking of absent members
4. **Member Dashboard Integration** - Upcoming meeting announcements

---

## Database Schema

### Meetings Table
```
- id (primary key)
- title (string)
- objective (text)
- meeting_date (datetime)
- location (string)
- status (enum: scheduled, ongoing, completed) - default: scheduled
- attendance_opened_at (timestamp, nullable)
- attendance_closed_at (timestamp, nullable)
- created_at, updated_at
```

### Attendance Table
```
- id (primary key)
- meeting_id (foreign key → meetings.id)
- member_id (foreign key → members.id)
- time_in (timestamp, nullable)
- remarks (text, nullable)
- status (enum: present, absent, excused) - default: absent
- created_at, updated_at
- unique constraint on (meeting_id, member_id)
```

---

## Admin Features

### 1. Create Meeting
**Route:** `GET /admin/meetings/create`
**POST:** `POST /admin/meetings`

**Fields:**
- Title (required)
- Objective (required)
- Date & Time (required)
- Location (required)

### 2. View All Meetings
**Route:** `GET /admin/meetings`

Shows:
- Meeting status (Scheduled/Ongoing/Completed)
- Date and location
- Attendance statistics (Present/Absent/Excused counts)
- Quick actions

### 3. Manage Meeting Details
**Route:** `GET /admin/meetings/{meeting}`

Shows:
- Complete meeting information
- Attendance summary statistics
- Attendance rate (bar chart visualization)
- Member attendance records table
- Actions to manage attendance

### 4. Open Attendance
**Route:** `POST /admin/meetings/{meeting}/open-attendance`

**Actions:**
- Changes meeting status from "Scheduled" to "Ongoing"
- Creates attendance records for all active members (marked as absent)
- Opens kiosk for check-ins
- Members can now check in via kiosk interface

### 5. Close Attendance
**Route:** `POST /admin/meetings/{meeting}/close-attendance`

**Actions:**
- Marks meeting as "Completed"
- Closes kiosk interface
- Finalizes attendance records
- Any unchecked members remain marked as absent

### 6. Mark Member as Excused
**Route:** `POST /admin/meetings/{meeting}/mark-excused`

Allows admin to mark absent members as excused with optional remarks.

---

## Kiosk Check-in Interface

### Purpose
A fast, simple interface for members to check in using their Member ID. Designed to be used at a kiosk (one laptop at the meeting entrance).

### Route
`GET /attendance/kiosk/{meeting}`
`POST /attendance/kiosk/{meeting}/submit`

### Features
1. **Large Input Field** - Member ID input (auto-focused)
2. **Real-time Validation** - Validates member ID as user types
3. **Success Animation** - Green checkmark with member name on successful check-in
4. **Error Messages** - Clear error messages for invalid IDs or duplicates
5. **Live Statistics** - Shows current attendance count in real-time
6. **Fast UX** - Optimized for quick member processing

### Validation Logic
- ✅ If member ID exists and hasn't checked in → "Success: [Name] is Present"
- ❌ If member ID is invalid → "Invalid Member ID. Please check and try again."
- ❌ If member already checked in → "[Name] has already checked in."
- ❌ If meeting attendance is not open → "Attendance is not currently open for this meeting."

### API Endpoint
**POST:** `/attendance/kiosk/{meeting}/submit`

**Request:**
```json
{
  "member_id": "MM-001"
}
```

**Response (Success):**
```json
{
  "success": true,
  "message": "Success: John Doe is Present",
  "member_name": "John Doe",
  "time_in": "02:30 PM"
}
```

**Response (Error):**
```json
{
  "success": false,
  "message": "Invalid Member ID. Please check and try again."
}
```

---

## Member Dashboard Integration

### Upcoming Meeting Announcement
- **Location:** Top of Member Dashboard
- **Shows:** Next upcoming meeting (if any)
- **Details Displayed:**
  - Meeting title
  - Date and time
  - Location
  - Objective/Purpose
- **Action Button:**
  - "Check In Now" (visible only when meeting is ongoing)

---

## Workflow Examples

### Scenario 1: Schedule a New Meeting
1. Admin navigates to `/admin/meetings/create`
2. Enters meeting details (title, objective, date/time, location)
3. Clicks "Schedule Meeting"
4. Meeting is created with status "Scheduled"

### Scenario 2: Run a Meeting with Attendance
1. Meeting is scheduled for 2:00 PM
2. At 1:55 PM, admin navigates to meeting details page
3. Clicks "Open Attendance" button
4. System creates attendance records for all active members (marked absent)
5. Admin opens kiosk on a laptop at the meeting entrance
6. Members go to kiosk and enter their Member ID
7. System marks them as present with timestamp
8. After meeting ends, admin clicks "Close Meeting"
9. Any member not checked in remains marked as absent

### Scenario 3: Member Checks In via Kiosk
1. Member arrives at meeting location
2. Goes to kiosk and sees meeting details
3. Enters Member ID (e.g., "MM-001")
4. System validates member ID
5. Shows success message: "Success: Jane Smith is Present"
6. Member's attendance is recorded with timestamp
7. Live counter updates to show new attendance count

### Scenario 4: Admin Marks Member as Excused
1. Meeting is completed
2. Admin reviews attendance records
3. Finds a member marked as absent
4. Clicks "Excuse" button next to member
5. Enters remarks (optional, e.g., "Medical leave")
6. System updates member status to "Excused"

---

## Models & Relationships

### Meeting Model
**Relationships:**
- `hasMany('attendances')` - Attendance records for this meeting

**Methods:**
- `getPresentCount()` - Count of present members
- `getAbsentCount()` - Count of absent members
- `getExcusedCount()` - Count of excused members
- `isAttendanceOpen()` - Check if attendance is currently open
- `openAttendance()` - Open attendance and create records
- `closeAttendance()` - Close attendance and mark completed

### Attendance Model
**Relationships:**
- `belongsTo('Meeting')` - The meeting this attendance belongs to
- `belongsTo('Member')` - The member for this attendance record

**Methods:**
- `markPresent()` - Mark member as present with timestamp
- `markAbsent()` - Mark member as absent
- `markExcused(?string $remarks)` - Mark member as excused with optional remarks

### Member Model
**Relationships:**
- `hasMany('attendances')` - All attendance records for this member

---

## Routes Summary

### Admin Routes
```
GET  /admin/meetings              - List all meetings
GET  /admin/meetings/create       - Create meeting form
POST /admin/meetings              - Store new meeting
GET  /admin/meetings/{id}         - View meeting details
GET  /admin/meetings/{id}/edit    - Edit meeting form
PUT  /admin/meetings/{id}         - Update meeting
DELETE /admin/meetings/{id}       - Delete meeting
POST /admin/meetings/{id}/open-attendance    - Open attendance
POST /admin/meetings/{id}/close-attendance   - Close meeting
POST /admin/meetings/{id}/mark-excused       - Mark member excused
```

### Member/Public Routes
```
GET  /attendance/kiosk/{meeting}           - Kiosk check-in page
POST /attendance/kiosk/{meeting}/submit    - Submit attendance
```

### API Routes
```
GET  /api/attendance/meeting/{meeting}/details  - Get meeting stats for kiosk
```

---

## Business Logic

### Attendance Status Rules
1. **Present** - Member checked in during attendance window
2. **Absent** - Meeting ended but member didn't check in (default)
3. **Excused** - Admin manually marked as excused (with optional remarks)

### Meeting Status Flow
```
Scheduled → Ongoing → Completed
```

- **Scheduled:** Editing allowed, attendance not available
- **Ongoing:** No editing, attendance checking active
- **Completed:** No changes allowed, attendance finalized

### Automatic Member Records
When `openAttendance()` is called:
- System queries all active members
- Creates attendance record for each (if not exists)
- Initial status: "Absent"
- Members can check in to change status to "Present"

---

## UI Components

### Meeting Cards (Admin List View)
Displays:
- Meeting title and status badge
- Date/time and location
- Objective preview
- Present/Absent/Excused counts
- View and Edit buttons

### Kiosk Interface
Displays:
- Meeting details header (gradient background)
- Large member ID input field
- Real-time meeting statistics
- Message display area (success/error feedback)
- Success animation with bounce effect

### Member Dashboard Announcement
Displays:
- Upcoming meeting card with details
- Check-in button (when ongoing)
- Status badge (when not yet opened)

---

## Security & Validations

### Member ID Validation
- Must exist in members table
- Case-sensitive (as per system design)
- Already checked in members are rejected

### Meeting Access
- Attendance can only be opened/closed when meeting status allows
- Meeting can't be edited while attendance is open or meeting is completed
- Meeting can't be deleted if attendance has been opened

### Data Integrity
- Unique constraint on (meeting_id, member_id) prevents duplicate attendance records
- Foreign keys ensure referential integrity
- Cascading deletes on meeting deletion

---

## Tips for Use

1. **Kiosk Setup:** Set up a dedicated laptop/tablet at meeting entrance with login credentials
2. **Member IDs:** Members should know their ID (format: MM-XXX)
3. **Timing:** Open attendance 5-10 minutes before actual meeting start
4. **Excused Members:** Use remarks field to note reasons (medical, work, etc.)
5. **Reports:** Review attendance after meeting to identify no-shows and follow up

---

## Future Enhancements

Possible additions:
- QR code scanning for faster check-in
- SMS/Email notifications about meetings
- Attendance reports and analytics
- Meeting attendance history per member
- Multiple check-in times (check-out)
- Meeting minutes/notes recording
- Attendance calendars
