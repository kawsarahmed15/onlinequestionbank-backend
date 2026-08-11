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
            'class-x' => [
                'id' => '22222222-2222-2222-2222-222222222222',
                'name' => 'Class X',
                'sort_order' => 1,
            ],
            'class-xii' => [
                'id' => '33333333-3333-3333-3333-333333333333',
                'name' => 'Class XII',
                'sort_order' => 2,
            ],
            'degree-ug' => [
                'id' => '44444444-4444-4444-4444-444444444444',
                'name' => 'Degree (UG)',
                'sort_order' => 3,
            ],
        ];
        $levelModels = [];
        foreach ($levelsData as $slug => $data) {
            $levelModels[$slug] = Level::firstOrCreate(
                ['id' => $data['id']],
                [
                    'name' => $data['name'],
                    'sort_order' => $data['sort_order'],
                ]
            );
        }

        // 4. Boards & Universities Setup
        $boardsData = [
            [
                'id' => '55555555-5555-5555-5555-555555555555',
                'name' => 'CBSE',
                'full_name' => 'Central Board of Secondary Education',
                'is_national' => true,
                'state_slug' => 'delhi',
            ],
            [
                'id' => '66666666-6666-6666-6666-666666666666',
                'name' => 'AHSEC',
                'full_name' => 'Assam Higher Secondary Education Council',
                'is_national' => false,
                'state_slug' => 'assam',
            ],
            [
                'id' => '66666666-2222-2222-2222-222222222222',
                'name' => 'MBOSE',
                'full_name' => 'Meghalaya Board of School Education',
                'is_national' => false,
                'state_slug' => 'meghalaya',
            ],
            [
                'id' => '66666666-3333-3333-3333-333333333333',
                'name' => 'NBSE',
                'full_name' => 'Nagaland Board of School Education',
                'is_national' => false,
                'state_slug' => 'nagaland',
            ],
            // Universities for Degree
            [
                'id' => '55555555-1111-1111-1111-111111111111',
                'name' => 'Gauhati University',
                'full_name' => 'Gauhati University (GU)',
                'is_national' => false,
                'state_slug' => 'assam',
            ],
            [
                'id' => '55555555-2222-2222-2222-222222222222',
                'name' => 'Cotton University',
                'full_name' => 'Cotton University (CU)',
                'is_national' => false,
                'state_slug' => 'assam',
            ],
            [
                'id' => '55555555-3333-3333-3333-333333333333',
                'name' => 'Dibrugarh University',
                'full_name' => 'Dibrugarh University (DU)',
                'is_national' => false,
                'state_slug' => 'assam',
            ],
            [
                'id' => '55555555-4444-4444-4444-444444444444',
                'name' => 'NEHU',
                'full_name' => 'North-Eastern Hill University',
                'is_national' => false,
                'state_slug' => 'meghalaya',
            ],
        ];
        $boardModels = [];
        foreach ($boardsData as $board) {
            $stateId = isset($stateModels[$board['state_slug']]) ? $stateModels[$board['state_slug']]->id : null;
            $boardModels[$board['name']] = Board::firstOrCreate(
                ['id' => $board['id']],
                [
                    'name' => $board['name'],
                    'full_name' => $board['full_name'],
                    'is_national' => $board['is_national'],
                    'state_id' => $stateId,
                ]
            );
        }

        // 5. Streams Setup (Class XII Streams & Degree Courses)
        $streamModels = [];
        
        // XII Streams
        $streamModels['XII_Science'] = Stream::firstOrCreate(
            ['id' => '77777777-7777-7777-7777-777777777777'],
            ['level_id' => $levelModels['class-xii']->id, 'name' => 'Science']
        );
        $streamModels['XII_Commerce'] = Stream::firstOrCreate(
            ['id' => '88888888-8888-8888-8888-888888888888'],
            ['level_id' => $levelModels['class-xii']->id, 'name' => 'Commerce']
        );
        $streamModels['XII_Arts'] = Stream::firstOrCreate(
            ['id' => '99999999-9999-9999-9999-999999999999'],
            ['level_id' => $levelModels['class-xii']->id, 'name' => 'Arts']
        );
        
        // Degree Courses/Streams
        $streamModels['Degree_BA'] = Stream::firstOrCreate(
            ['id' => '77777777-1111-1111-1111-222222222222'],
            ['level_id' => $levelModels['degree-ug']->id, 'name' => 'BA']
        );
        $streamModels['Degree_BSc'] = Stream::firstOrCreate(
            ['id' => '77777777-2222-2222-2222-222222222222'],
            ['level_id' => $levelModels['degree-ug']->id, 'name' => 'BSc']
        );
        $streamModels['Degree_BCom'] = Stream::firstOrCreate(
            ['id' => '77777777-3333-3333-3333-222222222222'],
            ['level_id' => $levelModels['degree-ug']->id, 'name' => 'BCom']
        );
        $streamModels['Degree_BCA'] = Stream::firstOrCreate(
            ['id' => '77777777-4444-4444-4444-222222222222'],
            ['level_id' => $levelModels['degree-ug']->id, 'name' => 'BCA']
        );
        $streamModels['Degree_BBA'] = Stream::firstOrCreate(
            ['id' => '77777777-5555-5555-5555-222222222222'],
            ['level_id' => $levelModels['degree-ug']->id, 'name' => 'BBA']
        );

        // 5.5 Semesters Setup (Degree level only)
        $semesterModels = [];
        for ($i = 1; $i <= 8; $i++) {
            $semUuid = "55555555-5555-5555-5555-00000000000" . $i;
            $semesterModels[$i] = \App\Models\Semester::firstOrCreate(
                ['id' => $semUuid],
                [
                    'level_id' => $levelModels['degree-ug']->id,
                    'number' => $i
                ]
            );
        }

        // 6. Comprehensive Subjects List by Levels & Streams
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
                'Gauhati University' => [
                    'Degree_BA_Sem1' => [
                        'BA English Literature Major Sem 1' => 'BAENG01',
                        'BA History Major Sem 1' => 'BAHIS01',
                    ],
                    'Degree_BA_Sem2' => [
                        'BA English Literature Major Sem 2' => 'BAENG02',
                        'BA Political Science Sem 2' => 'BAPOL02',
                    ],
                    'Degree_BSc_Sem1' => [
                        'BSc Physics Major Sem 1' => 'BSCPHY01',
                        'BSc Chemistry Major Sem 1' => 'BSCCHE01',
                    ],
                    'Degree_BSc_Sem2' => [
                        'BSc Physics Major Sem 2' => 'BSCPHY02',
                        'BSc Mathematics Sem 2' => 'BSCMAT02',
                    ],
                    'Degree_BCom_Sem1' => [
                        'BCom Financial Accounting Sem 1' => 'BCOMACC01',
                        'BCom Business Law Sem 1' => 'BCOMLAW01',
                    ],
                    'Degree_BCom_Sem2' => [
                        'BCom Corporate Accounting Sem 2' => 'BCOMCOR02',
                    ],
                    'Degree_BCA_Sem1' => [
                        'BCA Programming in C Sem 1' => 'BCAPROG01',
                        'BCA Database Management Sem 1' => 'BCADBM01',
                    ],
                    'Degree_BCA_Sem2' => [
                        'BCA Data Structures Sem 2' => 'BCADS02',
                        'BCA Computer Architecture Sem 2' => 'BCACAR02',
                    ],
                    'Degree_BCA_Sem5' => [
                        'BCA Java Programming Sem 5' => 'BCAJAV05',
                        'BCA Software Engineering Sem 5' => 'BCASE05',
                    ],
                    'Degree_BBA_Sem1' => [
                        'BBA Principles of Management Sem 1' => 'BBAMGT01',
                        'BBA Business Communication Sem 1' => 'BBACM01',
                    ],
                ],
                'Cotton University' => [
                    'Degree_BA_Sem1' => [
                        'CU BA English Major Sem 1' => 'CUBAENG1',
                    ],
                    'Degree_BSc_Sem2' => [
                        'CU BSc Computer Science Sem 2' => 'CUBSCCS2',
                    ],
                    'Degree_BCA_Sem1' => [
                        'CU BCA Programming in C++ Sem 1' => 'CUBCACPP1',
                    ],
                ],
            ],
        ];

        // 7. Seed Subjects & Previous Year Papers (Looping years 2015 to 2025)
        $years = range(2015, 2025);
        $samplePdfUrl = 'https://pdfobject.com/pdf/sample.pdf';

        foreach ($subjectsMapping as $levelSlug => $boards) {
            $levelModel = $levelModels[$levelSlug];

            foreach ($boards as $boardName => $streams) {
                $boardModel = $boardModels[$boardName];

                foreach ($streams as $streamKey => $subjects) {
                    $streamModel = null;
                    $semesterModel = null;

                    if ($streamKey) {
                        if (str_contains($streamKey, '_Sem')) {
                            $parts = explode('_Sem', $streamKey);
                            $baseStreamKey = $parts[0];
                            $semNumber = (int)$parts[1];

                            $streamModel = $streamModels[$baseStreamKey] ?? null;
                            $semesterModel = $semesterModels[$semNumber] ?? null;
                        } else {
                            $streamModel = $streamModels[$streamKey] ?? null;
                        }
                    }

                    foreach ($subjects as $subjectName => $subjectCode) {
                        // Create Subject
                        $subjectModel = Subject::firstOrCreate(
                            [
                                'board_id' => $boardModel->id,
                                'name' => $subjectName,
                                'stream_id' => $streamModel?->id,
                                'semester_id' => $semesterModel?->id,
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
