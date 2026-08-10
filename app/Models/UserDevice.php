<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserDevice extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'user_id',
        'device_uuid',
        'device_model',
        'last_ip',
        'last_active_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
