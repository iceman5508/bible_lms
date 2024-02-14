<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class BookingHistory extends Model
{
    use HasFactory;

    public function scopePending($query)
    {
        return $query->whereStatus(0);
    }

    public function scopeApproved($query)
    {
        return $query->whereStatus(1);
    }

    public function scopeCancelled($query)
    {
        return $query->whereStatus(2);
    }

    public function scopeCompleted($query)
    {
        return $query->whereStatus(3);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'student_user_id', 'id');
    }

    public function instructorUser()
    {
        return $this->belongsTo(User::class, 'instructor_user_id', 'id');
    }

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'id');
    }

    public function order_item()
    {
        return $this->belongsTo(Order_item::class, 'order_item_id', 'id');
    }

    protected static function boot()
    {
        parent::boot();
        self::creating(function($model){
            $model->uuid =  Str::uuid()->toString();
        });
    }
}
