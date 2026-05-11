<?php

namespace App\Models;

use MongoDB\Laravel\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $connection = 'mongodb';
    protected $collection = 'users';

    protected $fillable = [
        'full_name',
        'channel_name',
        'channel_url',
        'email',
        'address',
        'mobile_number',
        'password',
        'role',
        'status',
        'email_verified_at'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    protected $attributes = [
        'role'   => 'user',
        'status' => 'active',
    ];
    public function communityPosts()
    {
        return $this->hasMany(CommunityPost::class, 'user_id');
    }

    public function communityComments()
    {
        return $this->hasMany(CommunityComment::class, 'user_id');
    }
}
