<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Category extends Model
{
    use HasFactory;

    protected $table = 'categories';
    protected $appends = ['image_url'];
    protected $fillable = [
        'name',
        'image',
        'is_feature',
        'slug',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'og_image',
    ];

    public function getImageUrlAttribute()
    {
        if ($this->image) {
            return asset($this->image);
        } else {
            return asset('uploads/default/no-image-found.png');
        }
    }

    public function courses()
    {
        return $this->hasMany(Course::class, 'category_id');
    }

    public function activeCourses()
    {
        return $this->hasMany(Course::class, 'category_id')->where('status', 1);
    }

    public function subcategories()
    {
        return $this->hasMany(Subcategory::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    public function scopeFeature($query)
    {
        return $query->where('is_feature', 'yes');
    }

    public function getImagePathAttribute()
    {
        if ($this->image)
        {
            return $this->image;
        } else {
            return 'uploads/default/no-image-found.png';
        }
    }


    protected static function boot()
    {
        parent::boot();
        self::creating(function($model){
            $model->uuid =  Str::uuid()->toString();
        });
    }

}
