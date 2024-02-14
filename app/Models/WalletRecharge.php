<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\SoftDeletes;

class WalletRecharge extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'uuid',
        'user_id',
        'payment_id',
        'amount',
        'payment_method',
        'status'
    ];

    public function payment(){
        return $this->belongsTo(Payment::class);
    }

    protected static function boot()
    {
        parent::boot();
        self::creating(function($model){
            $model->uuid = Str::uuid()->toString();
            $model->user_id = auth()->id();
        });
    }
}
