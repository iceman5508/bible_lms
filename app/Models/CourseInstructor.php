<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CourseInstructor extends Model
{
    use SoftDeletes;
    protected $table = 'course_instructor';

    protected $fillable = [
        'course_id',
        'instructor_id',
        'share',
        'status',
    ];

    public function user(){
        return $this->belongsTo(User::class, 'instructor_id');
    }
    
    public function course(){
        return $this->belongsTo(Course::class);
    }
}
