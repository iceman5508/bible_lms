<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Assignment extends Model
{
    use HasFactory;

    protected $appends = ['file_url'];

    public function getFileUrlAttribute()
    {
        if ($this->file) {
            return getVideoFile($this->file);
        } else {
            return asset('uploads/default/course.jpg');
        }
    }


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function assignmentFiles()
    {
        return $this->hasMany(AssignmentFile::class);
    }

    protected static function boot()
    {
        parent::boot();
        self::creating(function($model){
            $model->uuid =  Str::uuid()->toString();
            $model->user_id =  auth()->id();
        });
    }
}
