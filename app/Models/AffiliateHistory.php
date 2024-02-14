<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class AffiliateHistory extends Model
{
    use HasFactory;

    protected $table = 'affiliate_history';
    protected $fillable = [
        'hash',
        'user_id',
        'buyer_id',
        'order_id',
        'course_id',
        'bundle_id',
        'consultation_slot_id',
        'actual_price',
        'discount',
        'commission',
        'commission_percentage',
        'status'
    ];
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    public function scopePaid($query)
    {
        return $query->whereStatus(AFFILIATE_HISTORY_STATUS_PAID);
    }


    public function scopeDue($query)
    {
        return $query->whereStatus(AFFILIATE_HISTORY_STATUS_DUE);
    }


}
