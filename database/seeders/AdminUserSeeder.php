<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Member;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Seed the admin user.
     */
    public function run(): void
    {
        // Create admin user with specified credentials
        User::firstOrCreate(
            ['email' => 'admincoop@localhost.com'],
            [
                'name' => 'Admin Coop',
                'password' => Hash::make('AdminCoop@123'),
                'role' => 'admin',
                'is_active' => true,
            ]
        );

        // Create sample members (for registration testing)
        $members = [
            [
                'member_id' => 'MM-001',
                'first_name' => 'John',
                'last_name' => 'Smith',
                'email' => 'john.smith@example.com',
                'phone' => '09171234567',
                'address' => '123 Main Street, Manila',
                'date_of_birth' => '1985-06-15',
                'status' => 'active',
            ],
            [
                'member_id' => 'MM-002',
                'first_name' => 'Maria',
                'last_name' => 'Garcia',
                'email' => 'maria.garcia@example.com',
                'phone' => '09175678901',
                'address' => '456 Oak Avenue, Manila',
                'date_of_birth' => '1990-03-22',
                'status' => 'active',
            ],
            [
                'member_id' => 'MM-003',
                'first_name' => 'Pedro',
                'last_name' => 'Santos',
                'email' => 'pedro.santos@example.com',
                'phone' => '09179876543',
                'address' => '789 Pine Road, Manila',
                'date_of_birth' => '1988-09-10',
                'status' => 'active',
            ],
        ];

        foreach ($members as $memberData) {
            Member::firstOrCreate(
                ['member_id' => $memberData['member_id']],
                array_merge($memberData, [
                    'joined_date' => now()->toDateString(),
                ])
            );
        }

        $this->command->info('Admin user and sample members seeded successfully!');
    }
}
