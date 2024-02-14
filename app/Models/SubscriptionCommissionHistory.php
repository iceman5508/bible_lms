<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubscriptionCommissionHistory extends Model
{
    protected $fillable = [
        'monthly_distribution_history_id',
        'user_id',
        'sub_amount',
        'commission_percentage',
        'admin_commission',
        'total_amount',
        'paid_at',
    ];
}
