<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Payment extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'uuid',
        'user_id',
        'type',
        'order_number',
        'payment_id',
        'sub_total',
        'tax',
        'payment_currency',
        'platform_charge',
        'conversion_rate',
        'grand_total_with_conversation_rate',
        'deposit_by',
        'deposit_slip',
        'bank_id',
        'grand_total',
        'payment_method',
        'payment_details',
        'payment_status',
        'created_by_type',
        'created_by'
    ];


    public function user(){
        return $this->belongsTo(User::class);
    }
 
    public function bank(){
        return $this->belongsTo(Bank::class);
    }

    protected static function boot()
    {
        parent::boot();
        self::creating(function($model){
            $model->uuid =  Str::uuid()->toString();
            $model->created_by =  auth()->id();
        });
    }
}
