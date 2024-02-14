<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Email_template extends Model
{
    use HasFactory;

    protected $table = 'email_templates';
    protected $primaryKey = 'id';
    protected $fillable = [
        'name',
        'subject',
        'body'
    ];


    protected static function boot()
    {
        parent::boot();
        self::creating(function($model){
            $model->uuid =  Str::uuid()->toString();
        });
    }
}
