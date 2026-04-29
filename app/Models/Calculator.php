<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Calculator extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'calculators';

    protected $fillable = [
        'name',
        'slug',
        'category',
        'description',
        'is_active',
        'access_type', 
        'user_type',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function media()
{
    return $this->hasMany(CalculatorMedia::class, 'calculator_id');
}
}