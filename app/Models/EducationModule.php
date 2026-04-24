<?php
namespace App\Models;
use MongoDB\Laravel\Eloquent\Model;

class EducationModule extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'education_modules';

    protected $fillable = [
        'course_id', 
        'title',
        'slug',
        'description',
        'order',
        'status',
        'image',        
        'color_code',   
    ];

    public function contents()
    {
        return $this->hasMany(EducationContent::class, 'module_id');
    }

    public function course()
{
    return $this->belongsTo(Course::class, 'course_id');
}
}