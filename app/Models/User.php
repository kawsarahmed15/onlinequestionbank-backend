<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasUuids;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'mobile_number',
        'email',
        'password',
        'school_college_name',
        'referral_code',
        'onboarded_level_id',
        'onboarded_stream_id',
        'onboarded_board_id',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Relationships
    public function devices()
    {
        return $this->hasMany(UserDevice::class);
    }

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    public function yearAccessLogs()
    {
        return $this->hasMany(YearAccessLog::class);
    }

    public function subjects()
    {
        return $this->belongsToMany(Subject::class, 'user_subjects');
    }

    public function savedPapers()
    {
        return $this->belongsToMany(Paper::class, 'saved_papers');
    }

    public function requests()
    {
        return $this->hasMany(Request::class, 'requested_by');
    }

    public function submissions()
    {
        return $this->hasMany(Submission::class, 'submitted_by');
    }

    public function level()
    {
        return $this->belongsTo(Level::class, 'onboarded_level_id');
    }

    public function stream()
    {
        return $this->belongsTo(Stream::class, 'onboarded_stream_id');
    }

    public function board()
    {
        return $this->belongsTo(Board::class, 'onboarded_board_id');
    }
}
