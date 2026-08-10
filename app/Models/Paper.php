<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Paper extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'subject_id',
        'year',
        'paper_set',
        'exam_type',
        'file_path',
        'thumbnail_path',
        'file_size_bytes',
        'download_count',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }
}
