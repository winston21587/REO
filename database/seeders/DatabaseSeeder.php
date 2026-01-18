<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $this->call([
            UserSeeder::class,
        ]);

        // Seed AI Compliance Data for existing Research Titles
        $titles = \App\Models\Research_title::all();
        foreach ($titles as $title) {
            $title->ai_score = rand(0, 100);
            // 30% chance of being verified
            $title->is_human_verified = (rand(1, 100) <= 30);
            $title->save();
        }
    }
}
