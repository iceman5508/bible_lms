<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Course_lesson extends Model
{
    use HasFactory;

    protected $table = 'course_lessons';
    protected $primaryKey = 'id';
    protected $fillable = [
        'course_id',
        'name',
        'short_description'
    ];

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    public function lectures()
    {
        return $this->hasMany(Course_lecture::class, 'lesson_id');
    }

    public function getVideoDurationAttribute()
    {
        $video_duration = 0;
        if ($this->lectures->count() > 0)
        {
            foreach ($this->lectures as $lecture)
            {
                if ($lecture->type == 'video' && !is_null($lecture->file_duration))
                {
                    $video_duration+=  ($lecture->file_duration_second / 120);
                }
            }

        }

        return number_format($video_duration, 2).'min';
    }

    protected static function boot()
    {
        parent::boot();
        self::creating(function($model){
            $model->uuid =  Str::uuid()->toString();
        });
    }

}
