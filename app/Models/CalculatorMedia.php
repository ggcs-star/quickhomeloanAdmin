<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class CalculatorMedia extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'calculator_media';

    protected $fillable = [
        'calculator_id',
        'title',
        'slug',
        'type',        // audio | video
        'file',        // file path or URL
        'thumbnail',   // for video preview
        'duration',    // in seconds
        'description',
        'order',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    // Relation with Calculator
    public function calculator()
    {
        return $this->belongsTo(Calculator::class, 'calculator_id');
    }
}