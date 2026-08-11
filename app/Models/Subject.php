<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = ['name', 'code'];

    public function relations()
    {
        return $this->hasMany(SubjectRelation::class);
    }

    public function boards()
    {
        return $this->belongsToMany(Board::class, 'subject_relations');
    }

    public function streams()
    {
        return $this->belongsToMany(Stream::class, 'subject_relations');
    }

    public function semesters()
    {
        return $this->belongsToMany(Semester::class, 'subject_relations');
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
