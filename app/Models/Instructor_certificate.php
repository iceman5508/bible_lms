<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Instructor_certificate extends Model
{
    use HasFactory;

    protected $table = 'instructor_certificates';
    protected $primaryKey = 'id';
    protected $fillable = [
        'instructor_id',
        'organization_id',
        'name',
        'passing_year'
    ];


    protected static function boot()
    {
        parent::boot();
        self::creating(function($model){
            $model->uuid =  Str::uuid()->toString();
        });
    }

}
