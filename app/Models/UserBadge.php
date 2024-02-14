<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserBadge extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'user_id',
        'ranking_level_id',
    ];
}
