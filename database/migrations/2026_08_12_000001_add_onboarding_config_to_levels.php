<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

/**
 * Add a JSON config column to levels that drives the onboarding flow dynamically.
 * 
 * The config specifies:
 *  - steps: ordered list of steps in the onboarding flow for this level
 *  - Each step has: type (stream|board|semester), label, search_placeholder, icon
 *  - board_type: "board" | "university" | "council" – changes API/UI language
 *  - stream_type: "stream" | "course" | "department" – changes API/UI language
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('levels', function (Blueprint $table) {
            $table->json('onboarding_config')->nullable()->after('sort_order');
            $table->string('icon_name')->nullable()->after('name'); // heroicon name for admin display
            $table->string('description')->nullable()->after('icon_name');
        });

        // Seed sensible defaults for existing levels based on their names
        $levels = DB::table('levels')->get();
        foreach ($levels as $level) {
            $config = self::defaultConfigFor($level->name);
            DB::table('levels')->where('id', $level->id)->update([
                'onboarding_config' => json_encode($config),
            ]);
        }
    }

    public function down(): void
    {
        Schema::table('levels', function (Blueprint $table) {
            $table->dropColumn(['onboarding_config', 'icon_name', 'description']);
        });
    }

    private static function defaultConfigFor(string $name): array
    {
        $nameUpper = strtoupper($name);

        if (str_contains($nameUpper, 'POST GRAD') || str_contains($nameUpper, 'PG') || str_contains($nameUpper, 'MASTER')) {
            return [
                'requires_stream' => true,
                'requires_board' => true,
                'requires_semester' => false,
                'stream_label' => 'Course / Programme',
                'stream_placeholder' => 'Select your PG course (e.g. MA, MSc, MBA)...',
                'board_label' => 'University',
                'board_placeholder' => 'Search your university (e.g. Gauhati University)...',
                'board_search_hint' => 'Search by university name...',
                'semester_label' => 'Semester',
                'semester_placeholder' => 'Select semester',
                'step_descriptions' => [
                    'stream' => 'Which post-graduate programme are you enrolled in?',
                    'board' => 'Which university are you affiliated with?',
                ],
            ];
        }

        if (str_contains($nameUpper, 'DEGREE') || str_contains($nameUpper, 'BACHELOR') || str_contains($nameUpper, 'UNDERGRAD')) {
            return [
                'requires_stream' => true,
                'requires_board' => true,
                'requires_semester' => true,
                'stream_label' => 'Course / Degree',
                'stream_placeholder' => 'Select your course (e.g. BA, BSc, BCom)...',
                'board_label' => 'University',
                'board_placeholder' => 'Search your university (e.g. Gauhati University)...',
                'board_search_hint' => 'Search by university name...',
                'semester_label' => 'Semester',
                'semester_placeholder' => 'Select semester',
                'step_descriptions' => [
                    'stream' => 'Which undergraduate programme are you enrolled in?',
                    'board' => 'Which university are you affiliated with?',
                    'semester' => 'Which semester are you currently in?',
                ],
            ];
        }

        if (str_contains($nameUpper, 'CLASS XII') || str_contains($nameUpper, '12') || str_contains($nameUpper, 'HSC') || str_contains($nameUpper, 'HIGHER SECONDARY')) {
            return [
                'requires_stream' => true,
                'requires_board' => true,
                'requires_semester' => false,
                'stream_label' => 'Stream',
                'stream_placeholder' => 'Select your stream...',
                'board_label' => 'Exam Board',
                'board_placeholder' => 'Search your board (e.g. CBSE, AHSEC)...',
                'board_search_hint' => 'Search by state or board name...',
                'semester_label' => 'Semester',
                'semester_placeholder' => 'Select semester',
                'step_descriptions' => [
                    'stream' => 'Which stream are you studying?',
                    'board' => 'Which board are you enrolled under?',
                ],
            ];
        }

        // Default for Class X / Secondary / any other
        return [
            'requires_stream' => false,
            'requires_board' => true,
            'requires_semester' => false,
            'stream_label' => 'Stream',
            'stream_placeholder' => 'Select your stream...',
            'board_label' => 'Exam Board',
            'board_placeholder' => 'Search your board (e.g. CBSE, SEBA)...',
            'board_search_hint' => 'Search by state or board name...',
            'semester_label' => 'Semester',
            'semester_placeholder' => 'Select semester',
            'step_descriptions' => [
                'board' => 'Which board are you enrolled under?',
            ],
        ];
    }
};
