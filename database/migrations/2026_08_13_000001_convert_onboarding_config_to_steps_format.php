<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $levels = DB::table('levels')->get();
        foreach ($levels as $level) {
            $config = json_decode($level->onboarding_config, true) ?: [];
            if (isset($config['steps'])) {
                continue; // already converted
            }
            $steps = [];
            
            if (!empty($config['requires_stream'])) {
                $steps[] = [
                    'type' => 'stream',
                    'label' => $config['stream_label'] ?? 'Stream',
                    'description' => $config['step_descriptions']['stream'] ?? '',
                    'placeholder' => $config['stream_placeholder'] ?? 'Select stream...',
                    'icon' => 'menu_book',
                ];
            }
            if (!empty($config['requires_board'])) {
                $steps[] = [
                    'type' => 'board',
                    'label' => $config['board_label'] ?? 'Exam Board',
                    'description' => $config['step_descriptions']['board'] ?? '',
                    'placeholder' => $config['board_placeholder'] ?? 'Search board...',
                    'search_hint' => $config['board_search_hint'] ?? 'Search by name...',
                    'filter_type' => $config['board_filter_type'] ?? 'board',
                    'icon' => 'account_balance',
                ];
            }
            if (!empty($config['requires_semester'])) {
                $steps[] = [
                    'type' => 'semester',
                    'label' => $config['semester_label'] ?? 'Semester',
                    'description' => $config['step_descriptions']['semester'] ?? '',
                    'total' => intval($config['total_semesters'] ?? 6),
                    'icon' => 'calendar_month',
                ];
            }
            
            DB::table('levels')->where('id', $level->id)->update([
                'onboarding_config' => json_encode(['steps' => $steps])
            ]);
        }
    }

    public function down(): void
    {
        $levels = DB::table('levels')->get();
        foreach ($levels as $level) {
            $config = json_decode($level->onboarding_config, true) ?: [];
            if (!isset($config['steps'])) {
                continue; // already old format
            }
            
            $oldFormat = [
                'requires_stream' => false,
                'requires_board' => false,
                'requires_semester' => false,
            ];
            
            foreach ($config['steps'] as $step) {
                if ($step['type'] === 'stream') {
                    $oldFormat['requires_stream'] = true;
                    $oldFormat['stream_label'] = $step['label'] ?? 'Stream';
                    $oldFormat['stream_placeholder'] = $step['placeholder'] ?? 'Select stream...';
                    $oldFormat['step_descriptions']['stream'] = $step['description'] ?? '';
                } elseif ($step['type'] === 'board') {
                    $oldFormat['requires_board'] = true;
                    $oldFormat['board_label'] = $step['label'] ?? 'Exam Board';
                    $oldFormat['board_placeholder'] = $step['placeholder'] ?? 'Search board...';
                    $oldFormat['board_search_hint'] = $step['search_hint'] ?? 'Search by name...';
                    $oldFormat['board_filter_type'] = $step['filter_type'] ?? 'board';
                    $oldFormat['step_descriptions']['board'] = $step['description'] ?? '';
                } elseif ($step['type'] === 'semester') {
                    $oldFormat['requires_semester'] = true;
                    $oldFormat['semester_label'] = $step['label'] ?? 'Semester';
                    $oldFormat['total_semesters'] = $step['total'] ?? 6;
                    $oldFormat['step_descriptions']['semester'] = $step['description'] ?? '';
                }
            }
            
            DB::table('levels')->where('id', $level->id)->update([
                'onboarding_config' => json_encode($oldFormat)
            ]);
        }
    }
};
