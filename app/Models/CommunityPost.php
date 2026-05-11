<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class CommunityPost extends Model
{
    protected $connection = 'mongodb';

    protected $collection = 'community_posts';

    protected $fillable = [
        'user_id',
        'user_name',
        'user_photo',
        'content',
        'likes',
        'likes_count',
        'shares',
        'shares_count',
        'saved_by',
        'saved_count',
        'comments_count'
    ];

    protected $casts = [
        'likes' => 'array',
        'shares' => 'array',
        'saved_by' => 'array'
    ];

    public function comments()
    {
        return $this->hasMany(
            CommunityComment::class,
            'post_id'
        );
    }
}
