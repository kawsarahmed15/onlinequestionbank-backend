<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        // Get all levels
        $levels = DB::table('levels')->get();
        
        foreach ($levels as $level) {
            $config = json_decode($level->onboarding_config, true) ?? [];
            $nameUpper = strtoupper($level->name);
            
            // Check if it's PG
            if (str_contains($nameUpper, 'POST GRAD') || str_contains($nameUpper, 'PG') || str_contains($nameUpper, 'MASTER')) {
                $config['requires_semester'] = true;
                $config['total_semesters'] = 4;
                $config['board_filter_type'] = 'university';
                $config['step_descriptions']['semester'] = 'Which semester are you currently in?';
            }
            // Check if it's UG
            elseif (str_contains($nameUpper, 'DEGREE') || str_contains($nameUpper, 'BACHELOR') || str_contains($nameUpper, 'UNDERGRAD') || str_contains($nameUpper, 'UG')) {
                $config['requires_semester'] = true;
                $config['total_semesters'] = 8;
                $config['board_filter_type'] = 'university';
                $config['step_descriptions']['semester'] = 'Which semester are you currently in?';
            }
            // Class XII / Board
            elseif (str_contains($nameUpper, 'CLASS XII') || str_contains($nameUpper, '12') || str_contains($nameUpper, 'HSC') || str_contains($nameUpper, 'HIGHER SECONDARY')) {
                $config['board_filter_type'] = 'board';
            }
            // Default
            else {
                $config['board_filter_type'] = 'board';
            }
            
            // Update level
            DB::table('levels')->where('id', $level->id)->update([
                'onboarding_config' => json_encode($config),
            ]);
            
            // Generate Semesters if required
            if (!empty($config['requires_semester']) && !empty($config['total_semesters'])) {
                $total = intval($config['total_semesters']);
                for ($i = 1; $i <= $total; $i++) {
                    // Check if already exists
                    $exists = DB::table('semesters')
                        ->where('level_id', $level->id)
                        ->where('number', $i)
                        ->exists();
                        
                    if (!$exists) {
                        DB::table('semesters')->insert([
                            'id' => (string) Str::uuid(),
                            'level_id' => $level->id,
                            'number' => $i,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }
                }
            }
        }
    }

    public function down(): void
    {
        //
    }
};
