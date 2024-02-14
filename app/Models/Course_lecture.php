<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Course_lecture extends Model
{
    use HasFactory;

    protected $table = 'course_lectures';
    protected $primaryKey = 'id';
    protected $appends = ['file_url', 'image_url', 'pdf_url', 'audio_url'];
    protected $fillable = [
        'course_id',
        'lesson_id',
        'title',
        'short_description',
        'lecture_type',
        'file_path',
        'file_size',
        'file_duration',
        'file_duration_second',
        'type',
        'after_day',
        'unlock_date',
        'pre_ids',
    ];

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    public function lesson()
    {
        return $this->belongsTo(Course_lesson::class, 'lesson_id');
    }

    public function getImagePathAttribute()
    {
        if ($this->image)
        {
            return $this->image;
        } else {
            return 'uploads/default/lecture.jpg';
        }
    }
   
    public function getFileUrlAttribute()
    {
        return getImageFile($this->file_path);
    }
    public function getPdfUrlAttribute()
    {
        return getImageFile($this->pdf);
    }
    public function getImageUrlAttribute()
    {
        return getImageFile($this->image);
    }
    public function getAudioUrlAttribute()
    {
        return getVideoFile($this->audio);
    }

    protected static function boot()
    {
        parent::boot();
        self::creating(function($model){
            $model->uuid =  Str::uuid()->toString();
        });
    }


}
