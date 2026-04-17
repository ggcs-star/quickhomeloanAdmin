<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class DsaPartner extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'dsa_partners';

    protected $fillable = [
        'name',
        'email',
        'mobile',
        'commission_rate',
        'status',
    ];
}
