<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Submission extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'subject_id',
        'year',
        'paper_set',
        'file_path',
        'status',
        'rejection_reason',
        'submitted_by',
    ];

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function submitter()
    {
        return $this->belongsTo(User::class, 'submitted_by');
    }
}
