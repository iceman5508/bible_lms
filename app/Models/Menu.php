<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;

    public function page()
    {
        return $this->belongsTo(Page::class, 'url', 'id');
    }

    public function scopeActive()
    {
        return $this->where('status', 1);
    }

}
