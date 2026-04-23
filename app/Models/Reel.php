<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Reel extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'reels';

    protected $fillable = [
        'title',
        'description',
        'file',
        'order',
        'is_active',
        'views'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}