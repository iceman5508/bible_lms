<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Order extends Model
{
    use HasFactory;

    protected $table = 'orders';
    protected $primaryKey = 'id';
    protected $appends = ['deposit_slip_url'];
    protected $fillable = [
        'order_number',
        'sub_total',
        'discount',
        'shipping_cost',
        'tax',
        'platform_charge',
        'grand_total',
        'payment_method',
        'customer_comment',
        'error_msg'
    ];

    public function getDepositSlipUrlAttribute()
    {
        if ($this->deposit_slip) {
            return getVideoFile($this->deposit_slip);
        } else {
            return NULL;
        }
    }
    
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function bank()
    {
        return $this->belongsTo(Bank::class, 'bank_id');
    }

    public function items()
    {
        return $this->hasMany(Order_item::class, 'order_id');
    }

    public function getAdminCommissionAttribute()
    {
        return Order_item::where('order_id', $this->id)->sum('admin_commission');
    }

    public function getTotalRevenueAttribute()
    {
        return $this->admin_commission + $this->platform_charge;
    }

    public function jj()
    {

    }

    public function activity()
    {
        return $this->morphMany('App\Models\UserPackageActivity', 'activityable');
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
