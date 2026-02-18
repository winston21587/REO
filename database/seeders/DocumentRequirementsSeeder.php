<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\DocumentRequirement;

class DocumentRequirementsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $requirements = [
            [
                'name' => 'Application Form',
                'description' => 'Standard application form for research submission.',
                'file_type' => 'PDF',
                'is_required' => true,
                'is_multiple' => false,
            ],
            [
                'name' => 'Research Protocol',
                'description' => 'Detailed research protocol document.',
                'file_type' => 'PDF',
                'is_required' => true,
                'is_multiple' => false,
            ],
            [
                'name' => 'Technical Clearance',
                'description' => 'Clearance document for technical review.',
                'file_type' => 'PDF',
                'is_required' => true,
                'is_multiple' => false,
            ],
            [
                'name' => 'Data Collection Instruments',
                'description' => 'Tools and instruments used for data collection.',
                'file_type' => 'PDF',
                'is_required' => true,
                'is_multiple' => false,
            ],
            [
                'name' => 'Informed Consent',
                'description' => 'Consent form for participants.',
                'file_type' => 'PDF',
                'is_required' => true,
                'is_multiple' => false,
            ],
            [
                'name' => 'Curriculum Vitae',
                'description' => 'CV of the principal investigator/researcher.',
                'file_type' => 'PDF',
                'is_required' => true,
                'is_multiple' => false,
            ],
            [
                'name' => 'Study Protocol Form',
                'description' => 'Editable study protocol form.',
                'file_type' => 'Word',
                'is_required' => true,
                'is_multiple' => false,
            ],
            [
                'name' => 'Informed Consent Form',
                'description' => 'Editable informed consent form.',
                'file_type' => 'Word',
                'is_required' => true,
                'is_multiple' => false,
            ],
            [
                'name' => 'Exempt Review Form',
                'description' => 'Form for exempt review application.',
                'file_type' => 'Word',
                'is_required' => true,
                'is_multiple' => false,
            ],
            [
                'name' => 'Supplementary Documents',
                'description' => 'Additional supporting documents if applicable.',
                'file_type' => 'Others',
                'is_required' => false,
                'is_multiple' => true,
            ],
        ];

        foreach ($requirements as $requirement) {
            DocumentRequirement::firstOrCreate(
                ['name' => $requirement['name']], // Check by name to avoid duplicates
                $requirement
            );
        }
    }
}
