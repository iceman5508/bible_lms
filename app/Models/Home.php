<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Home extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $appends = ['banner_image_url'];
    protected $casts = [
        'banner_mini_words_title' => 'object',
        'banner_second_line_changeable_words' => 'object',
    ];

    public function getBannerImagePathAttribute()
    {
        if ($this->banner_image)
        {
            return $this->banner_image;
        } else {
            return 'uploads/default/no-image-found.png';
        }
    }
    
    public function getBannerImageUrlAttribute()
    {
        if ($this->banner_image)
        {
            return asset($this->banner_image);
        } else {
            return asset('uploads/default/no-image-found.png');
        }
    }
}
