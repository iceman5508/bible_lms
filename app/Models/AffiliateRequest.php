<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AffiliateRequest extends Model
{
    use HasFactory;

    protected $table = 'affiliate_request';
    protected $fillable = [
        'user_id',
        'comments',
        'address',
        'letter',
        'affiliate_code',
    ];
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }


}
