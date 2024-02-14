<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class Withdraw extends Model
{
    use HasFactory;

    protected $table = 'withdraws';
    protected $primaryKey = 'id';

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function scopePending($query)
    {
        return $query->where('status', WITHDRAWAL_STATUS_PENDING);
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', WITHDRAWAL_STATUS_COMPLETE);
    }

    public function scopeRejected($query)
    {
        return $query->where('status', WITHDRAWAL_STATUS_REJECTED);
    }

    public function beneficiary()
    {
        return $this->belongsTo(Beneficiary::class);
    }
    
    protected static function boot()
    {
        parent::boot();
        self::creating(function($model){
            $model->uuid =  Str::uuid()->getHex();
            $model->user_id =  auth()->id();
        });
    }

}
