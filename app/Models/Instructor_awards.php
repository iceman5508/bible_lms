<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Instructor_awards extends Model
{
    use HasFactory;

    protected $table = 'instructor_awards';
    protected $primaryKey = 'id';
    protected $fillable = [
        'instructor_id',
        'organization_id',
        'name',
        'winning_year'
    ];

    protected static function boot()
    {
        parent::boot();
        self::creating(function($model){
            $model->uuid =  Str::uuid()->toString();
        });
    }
}
