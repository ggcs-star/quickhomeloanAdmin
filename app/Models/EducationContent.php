<?php
namespace App\Models;
use MongoDB\Laravel\Eloquent\Model;

class EducationContent extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'education_contents';

    protected $fillable = [
        'module_id',
        'title',
        'slug',
        'type',
        'file',
        'thumbnail',
        'duration',
        'description',
        'order',
        'status'
    ];

    public function module()
    {
        return $this->belongsTo(EducationModule::class, 'module_id');
    }
}