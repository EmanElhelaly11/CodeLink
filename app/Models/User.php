<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'track',
        'bio',
        'imageUrl'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'code',
        'role',
        'code_expired_at',
        'email_verified_at',
        'updated_at',
        'created_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function teams()
    {
        return $this->belongsToMany(Team::class, 'team_users')->withTimestamps();
    }

    public function teamRequests()
    {
        return $this->hasMany(TeamRequest::class);
    }

    public function sentTeamRequests()
    {
        return $this->hasMany(TeamRequest::class, 'user_id');
    }

    public function profile()
    {
        return $this->hasOne(UserProfile::class);
    }

    public function ledTeams()
   {
    return $this->hasMany(Team::class, 'leader_id');
   }

    public function followers()
    {
        return $this->belongsToMany(User::class, 'user_followers', 'user_id', 'follower_id');
    }

    public function following()
    {
        return $this->belongsToMany(User::class, 'user_followers', 'follower_id', 'user_id');
    }

    public function administeredTracks()
    {
        return $this->hasMany(Track::class, 'admin_id');
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function shares()
    {
       return $this->hasMany(Share::class);
    }

    public function communities()
    {
        return $this->belongsToMany(Community::class, 'community_users');
    }

    public function courses()
    {
        return $this->hasMany(Course::class);
    }

    public function mentor()
    {
        return $this->hasOne(Mentor::class);
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }
}
