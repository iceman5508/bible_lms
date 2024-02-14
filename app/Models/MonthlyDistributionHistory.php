<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MonthlyDistributionHistory extends Model
{
    protected $fillable = [
        'month_year',
        'total_subscription',
        'total_enroll_course',
        'total_amount',
        'admin_commission',
        'user_id',
    ];

    protected static function boot()
    {
        parent::boot();
        self::creating(function($model){
            $model->user_id =  auth()->id();
        });
    }
}
