<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $driver = DB::connection()->getDriverName();
        
        if ($driver === 'mysql') {
            // For MySQL - simply modify the enum
            DB::statement("ALTER TABLE meetings MODIFY status ENUM('draft', 'scheduled', 'ongoing', 'completed') DEFAULT 'draft'");
        } elseif ($driver === 'sqlite') {
            // For SQLite - need to recreate the table due to CHECK constraint limitation
            Schema::table('meetings', function (Blueprint $table) {
                // SQLite doesn't support ALTER COLUMN with CHECK constraints
                // We need to handle this manually
            });
            
            // Get current data
            $meetings = DB::table('meetings')->get();
            
            // Drop and recreate the table
            DB::statement('PRAGMA foreign_keys = OFF');
            
            DB::statement('CREATE TABLE meetings_new (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                title VARCHAR NOT NULL,
                objective TEXT NOT NULL,
                meeting_date DATETIME NOT NULL,
                location VARCHAR NOT NULL,
                status VARCHAR NOT NULL CHECK (status IN ("draft", "scheduled", "ongoing", "completed")) DEFAULT "draft",
                attendance_opened_at TIMESTAMP,
                attendance_closed_at TIMESTAMP,
                created_at TIMESTAMP,
                updated_at TIMESTAMP
            )');
            
            // Create indexes
            DB::statement('CREATE INDEX meetings_new_status ON meetings_new(status)');
            DB::statement('CREATE INDEX meetings_new_meeting_date ON meetings_new(meeting_date)');
            
            // Copy data
            foreach ($meetings as $meeting) {
                DB::table('meetings_new')->insert([
                    'id' => $meeting->id,
                    'title' => $meeting->title,
                    'objective' => $meeting->objective,
                    'meeting_date' => $meeting->meeting_date,
                    'location' => $meeting->location,
                    'status' => $meeting->status,
                    'attendance_opened_at' => $meeting->attendance_opened_at,
                    'attendance_closed_at' => $meeting->attendance_closed_at,
                    'created_at' => $meeting->created_at,
                    'updated_at' => $meeting->updated_at,
                ]);
            }
            
            // Drop old table and rename new one
            DB::statement('DROP TABLE meetings');
            DB::statement('ALTER TABLE meetings_new RENAME TO meetings');
            
            DB::statement('PRAGMA foreign_keys = ON');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $driver = DB::connection()->getDriverName();
        
        if ($driver === 'mysql') {
            DB::statement("ALTER TABLE meetings MODIFY status ENUM('scheduled', 'ongoing', 'completed') DEFAULT 'scheduled'");
        } elseif ($driver === 'sqlite') {
            // Reverse for SQLite
            DB::statement('PRAGMA foreign_keys = OFF');
            
            DB::statement('CREATE TABLE meetings_new (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                title VARCHAR NOT NULL,
                objective TEXT NOT NULL,
                meeting_date DATETIME NOT NULL,
                location VARCHAR NOT NULL,
                status VARCHAR NOT NULL CHECK (status IN ("scheduled", "ongoing", "completed")) DEFAULT "scheduled",
                attendance_opened_at TIMESTAMP,
                attendance_closed_at TIMESTAMP,
                created_at TIMESTAMP,
                updated_at TIMESTAMP
            )');
            
            // Create indexes
            DB::statement('CREATE INDEX meetings_new_status ON meetings_new(status)');
            DB::statement('CREATE INDEX meetings_new_meeting_date ON meetings_new(meeting_date)');
            
            // Copy data, filtering out draft records
            $meetings = DB::table('meetings')->get();
            foreach ($meetings as $meeting) {
                if ($meeting->status !== 'draft') {
                    DB::table('meetings_new')->insert([
                        'id' => $meeting->id,
                        'title' => $meeting->title,
                        'objective' => $meeting->objective,
                        'meeting_date' => $meeting->meeting_date,
                        'location' => $meeting->location,
                        'status' => $meeting->status,
                        'attendance_opened_at' => $meeting->attendance_opened_at,
                        'attendance_closed_at' => $meeting->attendance_closed_at,
                        'created_at' => $meeting->created_at,
                        'updated_at' => $meeting->updated_at,
                    ]);
                }
            }
            
            DB::statement('DROP TABLE meetings');
            DB::statement('ALTER TABLE meetings_new RENAME TO meetings');
            
            DB::statement('PRAGMA foreign_keys = ON');
        }
    }
};

