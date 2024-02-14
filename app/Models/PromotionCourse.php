<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PromotionCourse extends Model
{
    use HasFactory;

    public function promotion()
    {
        return $this->belongsTo(Promotion::class)->where('status', 1);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }
}
