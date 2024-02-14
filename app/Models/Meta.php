<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Meta extends Model
{
    use HasFactory;

    protected $table = 'metas';
    protected $primaryKey = 'id';
    protected $fillable = [
        'page_name',
        'meta_title',
        'meta_description',
        'meta_keyword',
        'slug',
        'og_image',
    ];


    protected static function boot()
    {
        parent::boot();
        self::creating(function($model){
            $model->uuid =  Str::uuid()->toString();
        });
    }


}
