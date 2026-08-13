<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Level extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = ['name', 'sort_order', 'icon_name', 'description', 'onboarding_config'];

    protected $casts = [
        'onboarding_config' => 'array',
        'sort_order' => 'integer',
    ];

    protected static function booted()
    {
        static::saved(function ($level) {
            $config = $level->onboarding_config ?? [];
            $steps = $config['steps'] ?? [];
            $semesterStep = collect($steps)->firstWhere('type', 'semester');
            
            if ($semesterStep) {
                $total = intval($semesterStep['total'] ?? 0);
                if ($total > 0) {
                    for ($i = 1; $i <= $total; $i++) {
                        \App\Models\Semester::firstOrCreate([
                            'level_id' => $level->id,
                            'number' => $i,
                        ]);
                    }
                    
                    $extraSemesters = \App\Models\Semester::where('level_id', $level->id)
                        ->where('number', '>', $total)
                        ->get();
                    foreach ($extraSemesters as $extraSem) {
                        try {
                            $extraSem->delete();
                        } catch (\Exception $e) {
                            // ignore if has foreign key constraint
                        }
                    }
                }
            }
        });
    }

    public function streams()
    {
        return $this->hasMany(Stream::class);
    }

    public function semesters()
    {
        return $this->hasMany(Semester::class);
    }

    public function requiresStream(): bool
    {
        return collect($this->resolved_config['steps'] ?? [])->contains('type', 'stream');
    }

    public function requiresBoard(): bool
    {
        return collect($this->resolved_config['steps'] ?? [])->contains('type', 'board');
    }

    public function requiresSemester(): bool
    {
        return collect($this->resolved_config['steps'] ?? [])->contains('type', 'semester');
    }

    /**
     * Returns the resolved onboarding_config, falling back to sensible defaults
     * based on the level name if none is configured in the database.
     */
    public function getResolvedConfigAttribute(): array
    {
        $config = $this->onboarding_config ?? [];
        if (!empty($config)) {
            if (isset($config['steps'])) {
                return $config;
            }
            
            // Convert old format on the fly
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
            return ['steps' => $steps];
        }

        $name = strtoupper($this->name ?? '');

        if (str_contains($name, 'POST GRAD') || str_contains($name, 'MASTER') || str_contains($name, 'PG')) {
            return ['steps' => [
                ['type' => 'stream', 'label' => 'Course / Programme', 'description' => 'Which post-graduate programme are you enrolled in?', 'placeholder' => 'Select your PG course (e.g. MA, MSc, MBA)...', 'icon' => 'menu_book'],
                ['type' => 'board', 'label' => 'University', 'description' => 'Which university are you affiliated with?', 'placeholder' => 'Search your university...', 'search_hint' => 'Search by university name...', 'filter_type' => 'university', 'icon' => 'account_balance'],
                ['type' => 'semester', 'label' => 'Semester', 'description' => 'Which semester are you currently in?', 'total' => 4, 'icon' => 'calendar_month']
            ]];
        }

        if (str_contains($name, 'DEGREE') || str_contains($name, 'BACHELOR') || str_contains($name, 'UG')) {
            return ['steps' => [
                ['type' => 'stream', 'label' => 'Course / Degree', 'description' => 'Which undergraduate programme are you enrolled in?', 'placeholder' => 'Select your course (e.g. BA, BSc, BCom)...', 'icon' => 'menu_book'],
                ['type' => 'board', 'label' => 'University', 'description' => 'Which university are you affiliated with?', 'placeholder' => 'Search your university...', 'search_hint' => 'Search by university name...', 'filter_type' => 'university', 'icon' => 'account_balance'],
                ['type' => 'semester', 'label' => 'Semester', 'description' => 'Which semester are you currently in?', 'total' => 8, 'icon' => 'calendar_month']
            ]];
        }

        if (str_contains($name, 'XII') || str_contains($name, 'HSC') || str_contains($name, 'HIGHER SECONDARY')) {
            return ['steps' => [
                ['type' => 'stream', 'label' => 'Stream', 'description' => 'Which stream are you studying?', 'placeholder' => 'Select your stream...', 'icon' => 'menu_book'],
                ['type' => 'board', 'label' => 'Exam Board', 'description' => 'Which board are you enrolled under?', 'placeholder' => 'Search your board (e.g. CBSE, AHSEC)...', 'search_hint' => 'Search by state or board name...', 'filter_type' => 'board', 'icon' => 'account_balance']
            ]];
        }

        // Default fallback (Class X, Matriculation, etc.)
        return ['steps' => [
            ['type' => 'board', 'label' => 'Exam Board', 'description' => 'Which board are you enrolled under?', 'placeholder' => 'Search your board...', 'search_hint' => 'Search by state or board name...', 'filter_type' => 'board', 'icon' => 'account_balance']
        ]];
    }
}
