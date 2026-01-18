<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Researcher;
use App\Models\Admin;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Create a Researcher User
        $researcherUser = User::updateOrCreate(
            ['email' => 'researcher@reo.com'], // Check by email
            [
                'first_name' => 'winston',
                'last_name' => 'tabs',
                'password' => Hash::make('123456'), // password
                'role' => 'researcher',
                'is_verified' => true,
                'email_verified_at' => now(),
            ]
        );

        // Create Researcher Profile (if not exists)
        if (!$researcherUser->researcher) {
            Researcher::create([
                'user_id' => $researcherUser->id,
                'college' => 'College of computing studies',
                'department' => 'Computer Science',
                'course' => 'BS Computer Science',
                'external_user' => false,
            ]);
        }

        // 2. Create an Admin User
        $adminUser = User::updateOrCreate(
            ['email' => 'admin@reo.com'],
            [
                'first_name' => 'Admin',
                'last_name' => 'user',
                'password' => Hash::make('adminpassword'),
                'role' => 'admin',
                'is_verified' => true,
                'email_verified_at' => now(),
            ]
        );

        // Create Admin Profile (if not exists)
        // Note: Check what fields are strictly required in your Admin model migration
        if (!$adminUser->admin) {
            Admin::create([
                'user_id' => $adminUser->id,
                'position' => 'Chair',
                'member_type' => 'Scientist',
                'college' => 'College of Science and Mathematics',
                'expertise' => ['Ethics', 'Computer Science'],
                'training_completed' => true,
            ]);
        }
    }
}
