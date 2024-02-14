<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Student_certificate extends Model
{
    use HasFactory;

    protected $table = 'student_certificates';
    protected $primaryKey = 'id';


    protected static function boot()
    {
        parent::boot();
        self::creating(function($model){
            $model->uuid =  Str::uuid()->toString();
            $model->user_id =  auth()->id();
        });
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
    
    public function student()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

}
