<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = ['board_id', 'stream_id', 'semester_id', 'name', 'code'];

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

    public function papers()
    {
        return $this->hasMany(Paper::class);
    }

    public function requests()
    {
        return $this->hasMany(Request::class);
    }

    public function submissions()
    {
        return $this->hasMany(Submission::class);
    }
}
