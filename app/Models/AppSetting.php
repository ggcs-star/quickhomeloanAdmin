<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class AppSetting extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'app_settings';

    protected $fillable = [
        'app_name',
        'app_logo',
        'splash_logo',
        'header_logo',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function getAppLogoUrlAttribute()
    {
        return $this->app_logo ? asset('storage/' . $this->app_logo) : null;
    }

    public function getSplashLogoUrlAttribute()
    {
        return $this->splash_logo ? asset('storage/' . $this->splash_logo) : null;
    }

    public function getHeaderLogoUrlAttribute()
    {
        return $this->header_logo ? asset('storage/' . $this->header_logo) : null;
    }
}