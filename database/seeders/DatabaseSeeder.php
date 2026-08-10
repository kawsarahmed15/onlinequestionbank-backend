<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\State;
use App\Models\Level;
use App\Models\Board;
use App\Models\Stream;
use App\Models\Semester;
use App\Models\Subject;
use App\Models\Paper;
use App\Models\SystemSetting;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. System Settings
        SystemSetting::set('require_otp_verification', 'false');
        SystemSetting::set('allow_google_login', 'true');
        SystemSetting::set('free_tier_year_limit', '3');
        SystemSetting::set('tier1_price', '99.00');
        SystemSetting::set('tier2_price', '149.00');
        SystemSetting::set('referral_discount_percent', '20');

        // 2. States
        $assam = State::create([
            'id' => '11111111-1111-1111-1111-111111111111',
            'name' => 'Assam',
            'slug' => 'assam',
        ]);

        $meghalaya = State::create([
            'id' => '11111111-2222-2222-2222-222222222222',
            'name' => 'Meghalaya',
            'slug' => 'meghalaya',
        ]);

        // 3. Levels
        $classX = Level::create([
            'id' => '22222222-2222-2222-2222-222222222222',
            'name' => 'Class X',
            'sort_order' => 1,
        ]);

        $classXII = Level::create([
            'id' => '33333333-3333-3333-3333-333333333333',
            'name' => 'Class XII',
            'sort_order' => 2,
        ]);

        $degree = Level::create([
            'id' => '44444444-4444-4444-4444-444444444444',
            'name' => 'Degree',
            'sort_order' => 3,
        ]);

        // 4. Boards
        $cbse = Board::create([
            'id' => '55555555-5555-5555-5555-555555555555',
            'name' => 'CBSE',
            'full_name' => 'Central Board of Secondary Education',
            'is_national' => true,
        ]);

        $ahsec = Board::create([
            'id' => '66666666-6666-6666-6666-666666666666',
            'name' => 'AHSEC',
            'full_name' => 'Assam Higher Secondary Education Council',
            'state_id' => $assam->id,
            'is_national' => false,
        ]);

        $mbose = Board::create([
            'id' => '66666666-2222-2222-2222-222222222222',
            'name' => 'MBOSE',
            'full_name' => 'Meghalaya Board of School Education',
            'state_id' => $meghalaya->id,
            'is_national' => false,
        ]);

        // 5. Streams (Class XII)
        $science = Stream::create([
            'id' => '77777777-7777-7777-7777-777777777777',
            'level_id' => $classXII->id,
            'name' => 'Science',
        ]);

        $commerce = Stream::create([
            'id' => '88888888-8888-8888-8888-888888888888',
            'level_id' => $classXII->id,
            'name' => 'Commerce',
        ]);

        $arts = Stream::create([
            'id' => '99999999-9999-9999-9999-999999999999',
            'level_id' => $classXII->id,
            'name' => 'Arts',
        ]);

        // 6. Subjects (AHSEC Science Class XII)
        $physics = Subject::create([
            'id' => 'aaaaaaaa-aaaa-aaaa-aaaa-aaaaaaaaaaaa',
            'board_id' => $ahsec->id,
            'stream_id' => $science->id,
            'name' => 'Physics',
            'code' => '042',
        ]);

        $chemistry = Subject::create([
            'id' => 'bbbbbbbb-bbbb-bbbb-bbbb-bbbbbbbbbbbb',
            'board_id' => $ahsec->id,
            'stream_id' => $science->id,
            'name' => 'Chemistry',
            'code' => '043',
        ]);

        $maths = Subject::create([
            'id' => 'cccccccc-cccc-cccc-cccc-cccccccccccc',
            'board_id' => $ahsec->id,
            'stream_id' => $science->id,
            'name' => 'Mathematics',
            'code' => '041',
        ]);

        $english = Subject::create([
            'id' => 'dddddddd-dddd-dddd-dddd-dddddddddddd',
            'board_id' => $ahsec->id,
            'stream_id' => $science->id,
            'name' => 'English Core',
            'code' => '301',
        ]);

        // Subjects (AHSEC Commerce Class XII)
        $accountancy = Subject::create([
            'id' => 'eeeeeeee-eeee-eeee-eeee-eeeeeeeeeeee',
            'board_id' => $ahsec->id,
            'stream_id' => $commerce->id,
            'name' => 'Accountancy',
            'code' => '055',
        ]);

        $businessStudies = Subject::create([
            'id' => 'ffffffff-ffff-ffff-ffff-ffffffffffff',
            'board_id' => $ahsec->id,
            'stream_id' => $commerce->id,
            'name' => 'Business Studies',
            'code' => '054',
        ]);

        // 7. Seed Sample Papers for Physics
        $fileUrl = 'https://pdfobject.com/pdf/sample.pdf';

        Paper::create([
            'id' => 'paper-phy-2024-a',
            'subject_id' => $physics->id,
            'year' => 2024,
            'paper_set' => 'A',
            'exam_type' => 'annual',
            'file_path' => $fileUrl,
            'file_size_bytes' => 150000,
            'download_count' => 124,
        ]);

        Paper::create([
            'id' => 'paper-phy-2023-a',
            'subject_id' => $physics->id,
            'year' => 2023,
            'paper_set' => 'A',
            'exam_type' => 'annual',
            'file_path' => $fileUrl,
            'file_size_bytes' => 145000,
            'download_count' => 310,
        ]);

        Paper::create([
            'id' => 'paper-phy-2022-a',
            'subject_id' => $physics->id,
            'year' => 2022,
            'paper_set' => 'A',
            'exam_type' => 'annual',
            'file_path' => $fileUrl,
            'file_size_bytes' => 140000,
            'download_count' => 420,
        ]);

        // Default Admin User
        User::create([
            'id' => '00000000-0000-0000-0000-000000000000',
            'name' => 'System Admin',
            'email' => 'admin@prashnpatra.com',
            'password' => bcrypt('admin123'),
            'role' => 'admin',
        ]);
    }
}
