<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Language extends Model
{
    use HasFactory;

    protected $fillable = ['language', 'iso_code', 'flag', 'default_language'];

    protected $appends = ['flag_url'];

    public function getFlagUrlAttribute()
    {
        if ($this->flag)
        {
            return asset($this->flag);
        } else {
            return asset('uploads/default/instructor-default.png');
        }
    }

}
