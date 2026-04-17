<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Campaign extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'campaigns';

    protected $fillable = [
        'name',
        'channel',
        'message',
        'short_link',
        'recipient_type',
    'selected_leads',

        'recipients',
        'sent_count',
        'failed_count',
        'status',
        'sent_at',
        'scheduled_at',
        'clicks',
    ];

    protected $casts = [
        'recipients'   => 'integer',
        'sent_count'   => 'integer',
        'failed_count' => 'integer',
        'clicks'       => 'integer',
        'sent_at'      => 'datetime',
         // 👇 ADD
    'selected_leads' => 'array',
        'scheduled_at' => 'datetime',
    ];

    protected $attributes = [
        'sent_count'   => 0,
        'failed_count' => 0,
        'clicks'       => 0,
        'status'       => 'Draft',
    ];
}
