<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssignmentSubmitFile extends Model
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
}
