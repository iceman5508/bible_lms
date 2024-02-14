<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course_lecture_views extends Model
{
    use HasFactory;

    protected $table = 'course_lecture_views';
    protected $primaryKey = 'id';
    protected $fillable = [
        'course_id',
        'course_lecture_id',
        'enrollment_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    public function lecture()
    {
        return $this->belongsTo(Course_lecture::class, 'course_lecture_id');
    }

    protected static function boot()
    {
        parent::boot();
        self::creating(function($model){
            $model->user_id =  auth()->id();
        });
    }

}
