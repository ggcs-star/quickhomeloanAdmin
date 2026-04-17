<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Task extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'tasks';

    protected $fillable = [
        'title',
        'description',
        'priority',
        'status',
        'due_date',
        'lead_id',
        'lead_name',
        'assigned_to',
    ];
}
