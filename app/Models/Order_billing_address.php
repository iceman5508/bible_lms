<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order_billing_address extends Model
{
    use HasFactory;
    protected $table = 'order_billing_addresses';
    protected $primaryKey = 'id';
    protected $fillable = [
        'order_id',
        'country_id',
        'state_id',
        'city_id',
        'first_name',
        'last_name',
        'email',
        'street_address',
        'zip_code',
        'phone_number',
        'set_as_shipping_address',
    ];
}
