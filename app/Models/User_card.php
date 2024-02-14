<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class User_card extends Model
{
    use HasFactory;

    protected $table = 'user_cards';
    protected $primaryKey = 'id';
    protected $fillable = [
        'user_id',
        'card_number',
        'card_holder_name',
        'month',
        'year',
    ];

    protected static function boot()
    {
        parent::boot();
        self::creating(function($model){
            $model->uuid =  Str::uuid()->toString();
        });
    }

}
