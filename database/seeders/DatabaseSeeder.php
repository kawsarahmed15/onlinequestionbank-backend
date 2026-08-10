<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\State;
use App\Models\Level;
use App\Models\Board;
use App\Models\Stream;
use App\Models\Subject;
use App\Models\Paper;
use App\Models\SystemSetting;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database with a comprehensive production dataset.
     */
    public function run(): void
    {
        // 1. System Settings Config
        SystemSetting::set('require_otp_verification', 'false');
        SystemSetting::set('allow_google_login', 'true');
        SystemSetting::set('free_tier_year_limit', '3');
        SystemSetting::set('tier1_price', '99.00');
        SystemSetting::set('tier2_price', '149.00');
        SystemSetting::set('referral_discount_percent', '20');

        // 2. States Setup
        $states = [
            'Assam' => 'assam',
            'Meghalaya' => 'meghalaya',
            'Delhi' => 'delhi',
            'Nagaland' => 'nagaland',
        ];
        $stateModels = [];
        foreach ($states as $name => $slug) {
            $stateModels[$slug] = State::firstOrCreate(['slug' => $slug], ['name' => $name]);
        }

        // 3. Levels (Classes) Setup
        $levelsData = [
            'Class X' => 1,
            'Class XII' => 2,
            'Degree (UG)' => 3,
        ];
        $levelModels = [];
        foreach ($levelsData as $name => $order) {
            $slug = Str::slug($name);
            $levelModels[$slug] = Level::firstOrCreate(
                ['name' => $name],
                ['sort_order' => $order]
            );
        }

        // 4. Boards Setup
        $boardsData = [
            [
                'name' => 'CBSE',
                'full_name' => 'Central Board of Secondary Education',
                'is_national' => true,
                'state_slug' => 'delhi',
            ],
            [
                'name' => 'AHSEC',
                'full_name' => 'Assam Higher Secondary Education Council',
                'is_national' => false,
                'state_slug' => 'assam',
            ],
            [
                'name' => 'MBOSE',
                'full_name' => 'Meghalaya Board of School Education',
                'is_national' => false,
                'state_slug' => 'meghalaya',
            ],
            [
                'name' => 'NBSE',
                'full_name' => 'Nagaland Board of School Education',
                'is_national' => false,
                'state_slug' => 'nagaland',
            ],
        ];
        $boardModels = [];
        foreach ($boardsData as $board) {
            $stateId = isset($stateModels[$board['state_slug']]) ? $stateModels[$board['state_slug']]->id : null;
            $boardModels[$board['name']] = Board::firstOrCreate(
                ['name' => $board['name']],
                [
                    'full_name' => $board['full_name'],
                    'is_national' => $board['is_national'],
                    'state_id' => $stateId,
                ]
            );
        }

        // 5. Streams Setup (Applies to Class XII and Degree)
        $streamsData = ['Science', 'Commerce', 'Arts'];
        $streamModels = [];
        
        // XII Streams
        foreach ($streamsData as $streamName) {
            $streamModels['XII_' . $streamName] = Stream::firstOrCreate(
                [
                    'level_id' => $levelModels['class-xii']->id,
                    'name' => $streamName
                ]
            );
        }
        
        // Degree Streams (Majors)
        foreach ($streamsData as $streamName) {
            $streamModels['Degree_' . $streamName] = Stream::firstOrCreate(
                [
                    'level_id' => $levelModels['degree-ug']->id,
                    'name' => $streamName
                ]
            );
        }

        // 6. Comprehensive Subjects List by Levels & Streams
        // Setup map: Level_Slug => [ Board_Name => [ Stream_Key / null => [ Subjects Name => Code ] ] ]
        $subjectsMapping = [
            'class-x' => [
                'CBSE' => [
                    null => [
                        'Mathematics Standard' => '041',
                        'Science' => '086',
                        'Social Science' => '087',
                        'English Language & Literature' => '184',
                        'Hindi Course A' => '002',
                        'Computer Applications' => '165',
                    ]
                ],
                'AHSEC' => [ // HSLC level mapping under board parentage
                    null => [
                        'General Mathematics' => 'M10',
                        'General Science' => 'S12',
                        'Social Science' => 'SS15',
                        'English' => 'E02',
                        'Assamese MIL' => 'MIL01',
                        'Advanced Mathematics' => 'AM21',
                    ]
                ],
                'MBOSE' => [
                    null => [
                        'Mathematics' => 'M101',
                        'Science & Technology' => 'S201',
                        'Social Technology' => 'SS301',
                        'English Core' => 'E101',
                        'Health & Physical Education' => 'HP501',
                    ]
                ],
            ],
            'class-xii' => [
                'CBSE' => [
                    'XII_Science' => [
                        'Physics' => '042',
                        'Chemistry' => '043',
                        'Mathematics' => '041',
                        'Biology' => '044',
                        'English Core' => '301',
                        'Computer Science' => '083',
                    ],
                    'XII_Commerce' => [
                        'Accountancy' => '055',
                        'Business Studies' => '054',
                        'Economics' => '030',
                        'Mathematics' => '041',
                        'English Core' => '301',
                    ],
                    'XII_Arts' => [
                        'History' => '027',
                        'Geography' => '029',
                        'Political Science' => '028',
                        'Sociology' => '039',
                        'Economics' => '030',
                        'English Core' => '301',
                    ],
                ],
                'AHSEC' => [
                    'XII_Science' => [
                        'Physics' => 'PHYS',
                        'Chemistry' => 'CHEM',
                        'Mathematics' => 'MATH',
                        'Biology' => 'BIOL',
                        'English Core' => 'ENGL',
                        'Alternative English' => 'ALTE',
                    ],
                    'XII_Commerce' => [
                        'Accountancy' => 'ACCT',
                        'Business Studies' => 'BSTD',
                        'Economics' => 'ECON',
                        'Commercial Mathematics' => 'CMST',
                        'English Core' => 'ENGL',
                    ],
                    'XII_Arts' => [
                        'History' => 'HIST',
                        'Geography' => 'GEOG',
                        'Political Science' => 'POLS',
                        'Education' => 'EDUC',
                        'Economics' => 'ECON',
                        'English Core' => 'ENGL',
                    ],
                ],
                'MBOSE' => [
                    'XII_Science' => [
                        'Physics' => 'PHY12',
                        'Chemistry' => 'CHE12',
                        'Mathematics' => 'MAT12',
                        'Biology' => 'BIO12',
                        'English Core' => 'ENG12',
                    ],
                    'XII_Commerce' => [
                        'Accountancy' => 'ACC12',
                        'Business Studies' => 'BST12',
                        'Economics' => 'ECO12',
                        'English Core' => 'ENG12',
                    ],
                    'XII_Arts' => [
                        'History' => 'HIS12',
                        'Geography' => 'GEO12',
                        'Political Science' => 'PSC12',
                        'English Core' => 'ENG12',
                    ],
                ],
            ],
            'degree-ug' => [
                'CBSE' => [ // Maps standard central university syllabus mockup under national board context
                    'Degree_Science' => [
                        'Physics Major Sem 1' => 'PHYM1',
                        'Chemistry Major Sem 1' => 'CHEM1',
                        'Mathematics Major Sem 1' => 'MATH1',
                    ],
                    'Degree_Commerce' => [
                        'Financial Accounting Sem 1' => 'FAC1',
                        'Business Law Sem 1' => 'BLW1',
                    ],
                    'Degree_Arts' => [
                        'English Literature Sem 1' => 'ELIT1',
                        'Political Science Sem 1' => 'POLI1',
                    ]
                ]
            ]
        ];

        // 7. Seed Subjects & Previous Year Papers (Looping years 2015 to 2025)
        $years = range(2015, 2025);
        $samplePdfUrl = 'https://pdfobject.com/pdf/sample.pdf';

        foreach ($subjectsMapping as $levelSlug => $boards) {
            $levelModel = $levelModels[$levelSlug];

            foreach ($boards as $boardName => $streams) {
                $boardModel = $boardModels[$boardName];

                foreach ($streams as $streamKey => $subjects) {
                    $streamModel = $streamKey ? $streamModels[$streamKey] : null;

                    foreach ($subjects as $subjectName => $subjectCode) {
                        // Create Subject
                        $subjectModel = Subject::firstOrCreate(
                            [
                                'board_id' => $boardModel->id,
                                'name' => $subjectName,
                                'stream_id' => $streamModel?->id,
                            ],
                            [
                                'code' => $subjectCode,
                            ]
                        );

                        // Seed papers for this subject across years (2015-2025)
                        foreach ($years as $year) {
                            // Seed papers for random availability (e.g. 70% chance of availability)
                            // This matches realistic database configurations where some years are missing
                            if (rand(1, 100) <= 75) {
                                Paper::firstOrCreate(
                                    [
                                        'subject_id' => $subjectModel->id,
                                        'year' => $year,
                                        'paper_set' => 'A',
                                    ],
                                    [
                                        'exam_type' => 'annual',
                                        'file_path' => $samplePdfUrl,
                                        'file_size_bytes' => rand(120000, 350000),
                                        'download_count' => rand(5, 500),
                                        'is_active' => true,
                                    ]
                                );
                                
                                // 20% chance of having a supplementary/additional set paper as well
                                if (rand(1, 100) <= 20) {
                                    Paper::firstOrCreate(
                                        [
                                            'subject_id' => $subjectModel->id,
                                            'year' => $year,
                                            'paper_set' => 'B',
                                        ],
                                        [
                                            'exam_type' => 'supplementary',
                                            'file_path' => $samplePdfUrl,
                                            'file_size_bytes' => rand(120000, 300000),
                                            'download_count' => rand(1, 100),
                                            'is_active' => true,
                                        ]
                                    );
                                }
                            }
                        }
                    }
                }
            }
        }

        // 8. Default System Users
        User::firstOrCreate(
            ['email' => 'admin@prashnpatra.com'],
            [
                'name' => 'System Admin',
                'password' => bcrypt('admin123'),
                'role' => 'admin',
            ]
        );

        User::firstOrCreate(
            ['email' => 'teststudent@gmail.com'],
            [
                'name' => 'Kawsar Ahmed',
                'mobile_number' => '+8801700000000',
                'password' => bcrypt('password123'),
                'role' => 'student',
                'onboarded_level_id' => $levelModels['class-xii']->id,
                'onboarded_stream_id' => $streamModels['XII_Science']->id,
                'onboarded_board_id' => $boardModels['AHSEC']->id,
            ]
        );
    }
}
