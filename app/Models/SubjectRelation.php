<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubjectRelation extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = ['subject_id', 'board_id', 'stream_id', 'semester_id'];

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
