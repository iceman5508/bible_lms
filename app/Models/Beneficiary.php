<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Beneficiary extends Model
{
    protected $fillable = [
        'uuid',
        'user_id',
        'beneficiary_name',
        'type',
        'card_number',
        'card_holder_name',
        'expire_month',
        'expire_year',
        'bank_name',
        'bank_account_number',
        'bank_account_name',
        'bank_routing_number',
        'paypal_email',
        'status',
    ];

    protected static function booted()
    {
        self::creating(function ($model){
            $authUser = auth()->user();
            $model->uuid = Str::uuid()->toString();
            $model->user_id = $authUser->id;
        });
    }
}
