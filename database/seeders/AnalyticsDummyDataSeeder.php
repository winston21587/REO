<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Research_title;
use App\Models\Researcher;
use Carbon\Carbon;

class AnalyticsDummyDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get existing researchers or create sample ones
        $researchers = Researcher::limit(5)->get();
        
        if ($researchers->isEmpty()) {
            // If no researchers exist, create some dummy researchers
            $researchers = collect([
                (object)['id' => 1],
                (object)['id' => 2],
                (object)['id' => 3],
                (object)['id' => 4],
                (object)['id' => 5],
            ]);
        }

        // Review Types
        $reviewTypes = ['Expedited', 'Exempt', 'Full Board'];
        
        // Thesis Types
        $thesisTypes = ['Undergraduate thesis', 'Masters thesis', 'Dissertation'];
        
        // Funding Types
        $fundingTypes = ['Institutionally Funded', 'Externally Funded'];
        
        // Research Categories
        $categories = ['Medical Research', 'Social Science', 'Engineering', 'Behavioral Science', 'Other'];
        
        // Statuses
        $statuses = ['Approved', 'Pending', 'Revision Required', 'Rejected'];
        
        // Generate 50 dummy research titles with various combinations
        $counter = 0;
        foreach ($reviewTypes as $reviewType) {
            foreach ($thesisTypes as $thesisType) {
                foreach ($fundingTypes as $fundingType) {
                    for ($i = 0; $i < 2; $i++) {
                        $researcher = $researchers->random();
                        $createdDate = Carbon::now()->subDays(rand(0, 90));
                        
                        Research_title::create([
                            'Study_Protocol_title' => "Research Study - $reviewType - $thesisType - $fundingType - " . uniqid(),
                            'Research_Category' => $categories[array_rand($categories)],
                            'Review_Type' => $reviewType,
                            'thesis_type' => $thesisType,
                            'funding_type' => $fundingType,
                            'Created_by' => 'Admin',
                            'Status' => $statuses[array_rand($statuses)],
                            'ai_score' => rand(50, 100),
                            'is_human_verified' => (bool)rand(0, 1),
                            'Adviser' => 'Dr. Advisor ' . rand(1, 5),
                            'researcher_id' => $researcher->id ?? 1,
                            'Official_Receipt_Number' => rand(100000, 999999),
                            'created_at' => $createdDate,
                            'updated_at' => $createdDate,
                        ]);
                        
                        $counter++;
                    }
                }
            }
        }

        // Add some additional mixed entries
        $mixedCount = 0;
        while ($mixedCount < 10) {
            $researcher = $researchers->random();
            $createdDate = Carbon::now()->subDays(rand(0, 90));
            
            Research_title::create([
                'Study_Protocol_title' => "Mixed Research Study - " . uniqid(),
                'Research_Category' => $categories[array_rand($categories)],
                'Review_Type' => $reviewTypes[array_rand($reviewTypes)],
                'thesis_type' => $thesisTypes[array_rand($thesisTypes)],
                'funding_type' => $fundingTypes[array_rand($fundingTypes)],
                'Created_by' => 'Admin',
                'Status' => $statuses[array_rand($statuses)],
                'ai_score' => rand(50, 100),
                'is_human_verified' => (bool)rand(0, 1),
                'Adviser' => 'Dr. Advisor ' . rand(1, 5),
                'researcher_id' => $researcher->id ?? 1,
                'Official_Receipt_Number' => rand(100000, 999999),
                'created_at' => $createdDate,
                'updated_at' => $createdDate,
            ]);
            
            $mixedCount++;
        }

        $this->command->info("Created " . ($counter + $mixedCount) . " dummy research records for analytics testing");
    }
}
