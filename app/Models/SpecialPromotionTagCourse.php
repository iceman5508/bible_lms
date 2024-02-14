<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpecialPromotionTagCourse extends Model
{
    use HasFactory;

    public function specialPromotionTag()
    {
        return $this->belongsTo(SpecialPromotionTag::class);
    }
}
