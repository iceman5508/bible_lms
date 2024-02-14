<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User_paypal extends Model
{
    use HasFactory;

    protected $table = 'user_paypals';
    protected $fillable = [
        'user_id',
        'email',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

}
