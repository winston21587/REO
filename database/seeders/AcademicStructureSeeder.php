<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\College;
use App\Models\Department;
use App\Models\Program;
use Illuminate\Support\Facades\DB;

class AcademicStructureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Disable foreign key checks to allow truncation
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Program::truncate();
        Department::truncate();
        College::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $structure = [
            [
                'name' => 'College of Agriculture',
                'code' => 'CA',
                'color' => '#4CAF50', // Green
                'departments' => [
                    [
                        'name' => 'Department of Agriculture',
                        'code' => 'DA-AGRI',
                        'programs' => [
                            ['name' => 'Bachelor of Science in Agriculture', 'code' => 'BSA'],
                            ['name' => 'Bachelor of Science in Agribusiness', 'code' => 'BSAB'],
                            ['name' => 'Bachelor of Agricultural Technology', 'code' => 'BAT'],
                        ]
                    ],
                    [
                        'name' => 'Department of Agricultural Engineering',
                        'code' => 'DA-AGE',
                        'programs' => [
                            ['name' => 'Bachelor of Science in Agricultural and Biosystems Engineering', 'code' => 'BSABE'],
                        ]
                    ]
                ]
            ],
            [
                'name' => 'College of Architecture',
                'code' => 'CArch',
                'color' => '#795548', // Brown
                'departments' => [
                    [
                        'name' => 'Department of Architecture',
                        'code' => 'DA-ARCH',
                        'programs' => [
                            ['name' => 'Bachelor of Science in Architecture', 'code' => 'BSArch'],
                        ]
                    ]
                ]
            ],
            [
                'name' => 'College of Asian and Islamic Studies',
                'code' => 'CAIS',
                'color' => '#009688', // Teal
                'departments' => [
                    [
                        'name' => 'Department of Asian and Islamic Studies',
                        'code' => 'DA-IS',
                        'programs' => [
                            ['name' => 'Bachelor of Arts in Asian Studies', 'code' => 'BAAS'],
                            ['name' => 'Bachelor of Arts in Islamic Studies', 'code' => 'BAIS'],
                        ]
                    ]
                ]
            ],
            [
                'name' => 'College of Computing Studies',
                'code' => 'CCS',
                'color' => '#FF5722', // Deep Orange
                'departments' => [
                    [
                        'name' => 'Department of Computer Science',
                        'code' => 'DCS',
                        'programs' => [
                            ['name' => 'Bachelor of Science in Computer Science', 'code' => 'BSCS'],
                        ]
                    ],
                    [
                        'name' => 'Department of Information Technology',
                        'code' => 'DIT',
                        'programs' => [
                            ['name' => 'Bachelor of Science in Information Technology', 'code' => 'BSIT'],
                        ]
                    ]
                ]
            ],
            [
                'name' => 'College of Criminal Justice Education',
                'code' => 'CCJE',
                'color' => '#607D8B', // Blue Grey
                'departments' => [
                    [
                        'name' => 'Department of Criminology',
                        'code' => 'D-CRIM',
                        'programs' => [
                            ['name' => 'Bachelor of Science in Criminology', 'code' => 'BSCrim'],
                        ]
                    ]
                ]
            ],
            [
                'name' => 'College of Engineering',
                'code' => 'COE',
                'color' => '#FF9800', // Orange
                'departments' => [
                    [
                        'name' => 'Department of Civil Engineering',
                        'code' => 'DCE',
                        'programs' => [
                            ['name' => 'Bachelor of Science in Civil Engineering', 'code' => 'BSCE'],
                        ]
                    ],
                    [
                        'name' => 'Department of Electrical Engineering',
                        'code' => 'DEE',
                        'programs' => [
                            ['name' => 'Bachelor of Science in Electrical Engineering', 'code' => 'BSEE'],
                        ]
                    ],
                    [
                        'name' => 'Department of Mechanical Engineering',
                        'code' => 'DME',
                        'programs' => [
                            ['name' => 'Bachelor of Science in Mechanical Engineering', 'code' => 'BSME'],
                        ]
                    ],
                    [
                        'name' => 'Department of Computer Engineering',
                        'code' => 'DCpE',
                        'programs' => [
                            ['name' => 'Bachelor of Science in Computer Engineering', 'code' => 'BSCbD'], // Adjusted code if needed
                        ]
                    ],
                    [
                        'name' => 'Department of Sanitary Engineering',
                        'code' => 'DSE',
                        'programs' => [
                            ['name' => 'Bachelor of Science in Sanitary Engineering', 'code' => 'BSSE'],
                        ]
                    ],
                    [
                        'name' => 'Department of Geodetic Engineering',
                        'code' => 'DGE',
                        'programs' => [
                            ['name' => 'Bachelor of Science in Geodetic Engineering', 'code' => 'BSGE'],
                        ]
                    ]
                ]
            ],
            [
                'name' => 'College of Forestry and Environmental Studies',
                'code' => 'CFES',
                'color' => '#2E7D32', // Dark Green
                'departments' => [
                    [
                        'name' => 'Department of Forestry',
                        'code' => 'D-FOR',
                        'programs' => [
                            ['name' => 'Bachelor of Science in Forestry', 'code' => 'BSF'],
                            ['name' => 'Bachelor of Science in Agroforestry', 'code' => 'BSAF'],
                            ['name' => 'Bachelor of Science in Environmental Science', 'code' => 'BSES'],
                        ]
                    ]
                ]
            ],
            [
                'name' => 'College of Home Economics',
                'code' => 'CHE',
                'color' => '#E91E63', // Pink
                'departments' => [
                    [
                        'name' => 'Department of Home Economics',
                        'code' => 'D-HE',
                        'programs' => [
                            ['name' => 'Bachelor of Science in Home Economics', 'code' => 'BSHE'],
                            ['name' => 'Bachelor of Science in Nutrition and Dietetics', 'code' => 'BSND'],
                            ['name' => 'Bachelor of Science in Food Technology', 'code' => 'BSFT'],
                            ['name' => 'Bachelor of Science in Hospitality Management', 'code' => 'BSHM'],
                        ]
                    ]
                ]
            ],
            [
                'name' => 'College of Law',
                'code' => 'COL', // Changed from 'Gwaporium' to 'COL'
                'color' => '#3F51B5', // Indigo
                'departments' => [
                    [
                        'name' => 'College of Law',
                        'code' => 'D-LAW',
                        'programs' => [
                            ['name' => 'Juris Doctor', 'code' => 'JD'],
                        ]
                    ]
                ]
            ],
            [
                'name' => 'College of Liberal Arts',
                'code' => 'CLA',
                'color' => '#9C27B0', // Purple
                'departments' => [
                    [
                        'name' => 'Department of English',
                        'code' => 'D-ENG',
                        'programs' => [
                            ['name' => 'Bachelor of Arts in English Language Studies', 'code' => 'AB-ELS'],
                        ]
                    ],
                    [
                        'name' => 'Department of Political Science',
                        'code' => 'D-POLSCI',
                        'programs' => [
                            ['name' => 'Bachelor of Arts in Political Science', 'code' => 'AB-PolSci'],
                        ]
                    ],
                    [
                        'name' => 'Department of Psychology',
                        'code' => 'D-PSYCH',
                        'programs' => [
                            ['name' => 'Bachelor of Science in Psychology', 'code' => 'BSPsych'],
                        ]
                    ],
                    [
                        'name' => 'Department of Mass Communication',
                        'code' => 'D-MASSCOMM',
                        'programs' => [
                            ['name' => 'Bachelor of Arts in Broadcasting', 'code' => 'AB-Broad'],
                            ['name' => 'Bachelor of Arts in Journalism', 'code' => 'AB-Journ'],
                        ]
                    ]
                ]
            ],
            [
                'name' => 'College of Nursing',
                'code' => 'CN',
                'color' => '#F44336', // Red
                'departments' => [
                    [
                        'name' => 'Department of Nursing',
                        'code' => 'D-NURS',
                        'programs' => [
                            ['name' => 'Bachelor of Science in Nursing', 'code' => 'BSN'],
                        ]
                    ]
                ]
            ],
            [
                'name' => 'College of Public Administration and Development Studies',
                'code' => 'CPADS',
                'color' => '#673AB7', // Deep Purple
                'departments' => [
                    [
                        'name' => 'Department of Public Administration',
                        'code' => 'D-PADS',
                        'programs' => [
                            ['name' => 'Bachelor of Public Administration', 'code' => 'BPA'],
                            ['name' => 'Bachelor of Science in Development Communication', 'code' => 'BSDevComm'], // Often here or in Agriculture/Liberal Arts
                        ]
                    ]
                ]
            ],
            [
                'name' => 'College of Science and Mathematics',
                'code' => 'CSM',
                'color' => '#00BCD4', // Cyan
                'departments' => [
                    [
                        'name' => 'Department of Biology',
                        'code' => 'D-BIO',
                        'programs' => [
                            ['name' => 'Bachelor of Science in Biology', 'code' => 'BSBio'],
                        ]
                    ],
                    [
                        'name' => 'Department of Chemistry',
                        'code' => 'D-CHEM',
                        'programs' => [
                            ['name' => 'Bachelor of Science in Chemistry', 'code' => 'BSChem'],
                        ]
                    ],
                    [
                        'name' => 'Department of Mathematics',
                        'code' => 'D-MATH',
                        'programs' => [
                            ['name' => 'Bachelor of Science in Mathematics', 'code' => 'BSMath'],
                            ['name' => 'Bachelor of Science in Statistics', 'code' => 'BSStat'],
                        ]
                    ],
                    [
                        'name' => 'Department of Physics',
                        'code' => 'D-PHYS',
                        'programs' => [
                            ['name' => 'Bachelor of Science in Physics', 'code' => 'BSPhys'],
                        ]
                    ]
                ]
            ],
            [
                'name' => 'College of Social Work and Community Development',
                'code' => 'CSWCD',
                'color' => '#8BC34A', // Light Green
                'departments' => [
                    [
                        'name' => 'Department of Social Work',
                        'code' => 'D-SW',
                        'programs' => [
                            ['name' => 'Bachelor of Science in Social Work', 'code' => 'BSSW'],
                            ['name' => 'Bachelor of Science in Community Development', 'code' => 'BSCD'],
                        ]
                    ]
                ]
            ],
            [
                'name' => 'College of Sports Science and Physical Education',
                'code' => 'CSSPE',
                'color' => '#FFC107', // Amber
                'departments' => [
                    [
                        'name' => 'Department of Physical Education',
                        'code' => 'D-PE',
                        'programs' => [
                            ['name' => 'Bachelor of Physical Education', 'code' => 'BPE'],
                            ['name' => 'Bachelor of Exercise and Sports Sciences', 'code' => 'BESS'],
                        ]
                    ]
                ]
            ],
            [
                'name' => 'College of Teacher Education',
                'code' => 'CTE',
                'color' => '#2196F3', // Blue
                'departments' => [
                    [
                        'name' => 'Department of Elementary Education',
                        'code' => 'D-ELEM',
                        'programs' => [
                            ['name' => 'Bachelor of Elementary Education', 'code' => 'BEEd'],
                            ['name' => 'Bachelor of Early Childhood Education', 'code' => 'BECEd'],
                            ['name' => 'Bachelor of Special Needs Education', 'code' => 'BSNEd'],
                        ]
                    ],
                    [
                        'name' => 'Department of Secondary Education',
                        'code' => 'D-SEC',
                        'programs' => [
                            ['name' => 'Bachelor of Secondary Education', 'code' => 'BSEd'],
                            // Note: Majors are usually programs or tracks, simplified here
                        ]
                    ]
                ]
            ],
        ];

        DB::transaction(function () use ($structure) {
            foreach ($structure as $collegeData) {
                $college = College::create([
                    'name' => $collegeData['name'],
                    'code' => $collegeData['code'],
                    'color_assign' => $collegeData['color'],
                ]);

                foreach ($collegeData['departments'] as $deptData) {
                    $department = Department::create([
                        'college_id' => $college->id,
                        'name' => $deptData['name'],
                        'code' => $deptData['code'],
                    ]);

                    foreach ($deptData['programs'] as $progData) {
                        Program::create([
                            'department_id' => $department->id,
                            'name' => $progData['name'],
                            'code' => $progData['code'],
                        ]);
                    }
                }
            }
        });
    }
}
