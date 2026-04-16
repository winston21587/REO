<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\SuperAdmin;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::updateOrCreate(
            ['email' => 'admin@reo.com'], // using updateOrCreate to avoid duplicates if run multiple times
            [
                'first_name' => 'Super',
                'middle_name' => null,
                'last_name' => 'Admin',
                'password' => Hash::make('adminpassword'),
                'role' => 'super_admin',
                'is_verified' => true,
                'email_verified_at' => Carbon::now(),
            ]
        );

        SuperAdmin::updateOrCreate(
            ['user_id' => $user->id],
            []
        );
    }
}
