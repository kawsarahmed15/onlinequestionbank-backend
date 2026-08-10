<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Request extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'subject_id',
        'year',
        'paper_set',
        'status',
        'requested_by',
    ];

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function requester()
    {
        return $this->belongsTo(User::class, 'requested_by');
    }
}
