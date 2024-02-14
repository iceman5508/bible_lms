<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Difficulty_level extends Model
{
    use HasFactory;

    protected $table = 'difficulty_levels';
    protected $primaryKey = 'id';
    protected $fillable = [
        'name',
    ];


    public function courses()
    {
        return $this->hasMany(Course::class, 'difficulty_level_id');
    }

    public function activeCourses()
    {
        return $this->hasMany(Course::class, 'difficulty_level_id')->where('status', 1);
    }


    protected static function boot()
    {
        parent::boot();
        self::creating(function($model){
            $model->uuid =  Str::uuid()->toString();
        });
    }

}
