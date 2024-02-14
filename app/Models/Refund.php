<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Refund extends Model
{
    protected $fillable = [
        'enrollment_id',
        'order_item_id',
        'user_id',
        'instructor_user_id',
        'reason',
        'feedback',
        'amount',
        'status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    
    public function enrollment()
    {
        return $this->belongsTo(Enrollment::class, 'enrollment_id');
    }
    
    public function order_item()
    {
        return $this->belongsTo(Order_item::class, 'order_item_id');
    }
   
    public function instructor()
    {
        return $this->belongsTo(User::class, 'instructor_user_id');
    }
}
