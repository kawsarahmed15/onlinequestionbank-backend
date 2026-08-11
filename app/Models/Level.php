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

    public function streams()
    {
        return $this->hasMany(Stream::class);
    }

    public function semesters()
    {
        return $this->hasMany(Semester::class);
    }

    /**
     * Returns the resolved onboarding_config, falling back to sensible defaults
     * based on the level name if none is configured in the database.
     */
    public function getResolvedConfigAttribute(): array
    {
        if (!empty($this->onboarding_config)) {
            return $this->onboarding_config;
        }

        $name = strtoupper($this->name ?? '');

        if (str_contains($name, 'POST GRAD') || str_contains($name, 'MASTER') || str_contains($name, 'PG')) {
            return [
                'requires_stream'      => true,
                'requires_board'       => true,
                'requires_semester'    => false,
                'board_filter_type'    => 'university',
                'stream_label'         => 'Course / Programme',
                'stream_placeholder'   => 'Select your PG course (e.g. MA, MSc, MBA)...',
                'board_label'          => 'University',
                'board_placeholder'    => 'Search your university...',
                'board_search_hint'    => 'Search by university name...',
                'semester_label'       => 'Semester',
                'semester_placeholder' => 'Select semester',
                'step_descriptions'    => [
                    'stream' => 'Which post-graduate programme are you enrolled in?',
                    'board'  => 'Which university are you affiliated with?',
                ],
            ];
        }

        if (str_contains($name, 'DEGREE') || str_contains($name, 'BACHELOR') || str_contains($name, 'UG')) {
            return [
                'requires_stream'      => true,
                'requires_board'       => true,
                'requires_semester'    => true,
                'board_filter_type'    => 'university',
                'stream_label'         => 'Course / Degree',
                'stream_placeholder'   => 'Select your course (e.g. BA, BSc, BCom)...',
                'board_label'          => 'University',
                'board_placeholder'    => 'Search your university...',
                'board_search_hint'    => 'Search by university name...',
                'semester_label'       => 'Semester',
                'semester_placeholder' => 'Select semester',
                'step_descriptions'    => [
                    'stream' => 'Which undergraduate programme are you enrolled in?',
                    'board'  => 'Which university are you affiliated with?',
                    'semester' => 'Which semester are you currently in?',
                ],
            ];
        }

        if (str_contains($name, 'XII') || str_contains($name, 'HSC') || str_contains($name, 'HIGHER SECONDARY')) {
            return [
                'requires_stream'      => true,
                'requires_board'       => true,
                'requires_semester'    => false,
                'board_filter_type'    => 'board',
                'stream_label'         => 'Stream',
                'stream_placeholder'   => 'Select your stream...',
                'board_label'          => 'Exam Board',
                'board_placeholder'    => 'Search your board (e.g. CBSE, AHSEC)...',
                'board_search_hint'    => 'Search by state or board name...',
                'semester_label'       => 'Semester',
                'semester_placeholder' => 'Select semester',
                'step_descriptions'    => [
                    'stream' => 'Which stream are you studying?',
                    'board'  => 'Which board are you enrolled under?',
                ],
            ];
        }

        // Default fallback (Class X, Matriculation, etc.)
        return [
            'requires_stream'      => false,
            'requires_board'       => true,
            'requires_semester'    => false,
            'board_filter_type'    => 'board',
            'stream_label'         => 'Stream',
            'stream_placeholder'   => 'Select your stream...',
            'board_label'          => 'Exam Board',
            'board_placeholder'    => 'Search your board...',
            'board_search_hint'    => 'Search by state or board name...',
            'semester_label'       => 'Semester',
            'semester_placeholder' => 'Select semester',
            'step_descriptions'    => [
                'board' => 'Which board are you enrolled under?',
            ],
        ];
    }
}
