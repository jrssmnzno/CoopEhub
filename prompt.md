I need to add a Meeting Scheduling and Attendance Monitoring feature to the KOOPko System. This involves an Admin-side scheduler and a Member-side check-in interface.

1. Database Schema (Laravel)
Meetings Table: Columns for id, title, objective, meeting_date (datetime), location, and status (Scheduled, Ongoing, Completed).

Attendance Table: Columns for id, meeting_id (foreign key), member_id (foreign key), time_in (timestamp), and remarks.

Logic: Attendance is unique per member per meeting. If a member_id isn't recorded by the time the meeting status changes to "Completed," they are marked as Absent.

2. Admin Logic & UI (The Scheduler)
Functionality: Admin creates a meeting with a specific objective. Once saved, it triggers a "New Meeting" notification or status update on the member's end.

UI: A "Meeting Management" page with a calendar or list view.

Status Control: A toggle or button for the Admin to "Open Attendance" and "Close Meeting."

3. Member Dashboard Integration
Announcement UI: A prominent "Upcoming Meeting" card on the Member Dashboard showing the Date, Title, and Objective.

4. The Attendance Procedure (UX)
Check-in Interface: A dedicated, simplified page (accessible via the Member Dashboard) displaying the full Meeting Details.

The Input: A single, large input field for Member ID.

Validation Logic: * If the Member ID exists and is not yet recorded, show a "Success: [Name] is Present" message with a green checkmark.

If the ID is invalid or already timed-in, show a clear error message.

UX Goal: This should be fast enough to be used as a "Kiosk" (one laptop at a door where members type their IDs as they enter).