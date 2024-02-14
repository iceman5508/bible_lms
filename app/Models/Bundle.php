<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Bundle extends Model
{
    use HasFactory;
    protected $appends = ['image_url'];

    protected $fillable = [
        'uuid',
        'user_id',
        'name',
        'slug',
        'image',
        'overview',
        'price',
        'status',
        'is_subscription_enable',
        'access_period',
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
            return asset('uploads/default/course.jpg');
        }
    }

    public function bundleCourses()
    {
        return $this->hasMany(BundleCourse::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function instructor()
    {
        return $this->hasOneThrough(Instructor::class, User::class, 'id', 'user_id', 'user_id', 'id');
    }

    public function organization()
    {
        return $this->hasOneThrough(Organization::class, User::class, 'id', 'user_id', 'user_id', 'id');
    }

    public function orderItems()
    {
        return $this->hasMany(Order_item::class);
    }
    
    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    protected static function boot()
    {
        parent::boot();
        self::creating(function($model){
            $model->uuid =  Str::uuid()->toString();
            $model->user_id =  auth()->id();
        });
    }
}
