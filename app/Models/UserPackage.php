<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserPackage extends Model
{
    use SoftDeletes; 

    protected $fillable = [
        'user_id',
        'package_id',
        'payment_id',
        'subscription_type',
        'enroll_date',
        'expired_date',
        'student',
        'instructor',
        'course',
        'consultancy',
        'subscription_course',
        'bundle_course',
        'product',
        'device',
        'admin_commission',
        'status',
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }
   
    public function package(){
        return $this->belongsTo(Package::class, 'package_id', 'id', 'uuid');
    }
    
    public function payment(){
        return $this->belongsTo(Payment::class);
    }
    
    public function enrollments(){
        return $this->hasMany(Enrollment::class);
    }
}
