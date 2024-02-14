<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Package extends Model
{
    use SoftDeletes; 

    protected $primaryKey = 'uuid';
    protected $keyType = 'string';

    protected $fillable = [
        'uuid',
        'package_type',
        'title',
        'slug',
        'description',
        'discounted_monthly_price',
        'monthly_price',
        'discounted_yearly_price',
        'yearly_price',
        'icon',
        'student',
        'instructor',
        'course',
        'consultancy',
        'subscription_course',
        'bundle_course',
        'product',
        'device',
        'admin_commission',
        'in_home',
        'recommended',
        'status',
        'is_default',
        'order',
        'user_id',
    ];

    protected static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->uuid =  Str::uuid()->toString();
            $model->user_id =  auth()->id();
        });
    }
}
