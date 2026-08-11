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
        'board_id',
        'stream_id',
        'semester_id',
        'year',
        'paper_set',
        'exam_type',
        'file_path',
        'thumbnail_path',
        'file_size_bytes',
        'download_count',
        'is_active',
    ];

    protected $appends = ['file_url', 'thumbnail_url'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function getFileUrlAttribute()
    {
        return $this->file_path;
    }

    public function getThumbnailUrlAttribute()
    {
        return $this->thumbnail_path;
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function board()
    {
        return $this->belongsTo(Board::class);
    }

    public function stream()
    {
        return $this->belongsTo(Stream::class);
    }

    public function semester()
    {
        return $this->belongsTo(Semester::class);
    }
}
