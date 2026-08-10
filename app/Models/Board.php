<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Board extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = ['name', 'full_name', 'state_id', 'is_national'];

    public function state()
    {
        return $this->belongsTo(State::class);
    }

    public function subjects()
    {
        return $this->hasMany(Subject::class);
    }
}
