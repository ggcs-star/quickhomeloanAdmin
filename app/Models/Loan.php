<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Loan extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'loans';
    protected $guarded = [];
}


