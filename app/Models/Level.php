<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Level extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = ['name', 'sort_order'];

    public function streams()
    {
        return $this->hasMany(Stream::class);
    }

    public function semesters()
    {
        return $this->hasMany(Semester::class);
    }
}
