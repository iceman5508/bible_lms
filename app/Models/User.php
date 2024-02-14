<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use IvanoMatteo\LaravelDeviceTracking\Traits\UseDevices;
use Laravel\Passport\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, UseDevices, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'balance',
        'area_code',
        'mobile_number',
        'phone_number',
        'role',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'og_image',
    ];

    
    protected $appends = ['image_url'];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    public function courses()
    {
        return $this->hasMany(Course::class, 'user_id');
    }

    public function students()
    {
        return $this->hasMany(Order_item::class, 'owner_user_id', 'id');
    }

    public function orderItems()
    {
        return $this->hasMany(Order_item::class, 'owner_user_id', 'id');
    }

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class, 'user_id');
    }

    public function ownerEnrollments()
    {
        return $this->hasMany(Enrollment::class, 'owner_user_id');
    }

    public function consultations()
    {
        return $this->hasMany(Enrollment::class, 'consultation_slot_id');
    }


    /**
     * @return bool
     */

    public function is_admin()
    {
        if ($this->role == 1) {
            return true;
        }
        return false;
    }

    /**
     * @return bool
     */

    public function is_instructor()
    {
        if ($this->role == 2) {
            return true;
        }
        return false;
    }

    /**
     * @return bool
     */
    public function is_organization()
    {
        if ($this->role == 4) {
            return true;
        }
        return false;
    }

    /**
     * @return bool
     */

    public function is_student()
    {
        if ($this->role == 3) {
            return true;
        }
        return false;
    }


    public function instructor()
    {
        return $this->hasOne(Instructor::class);
    }

    public function organization()
    {
        return $this->hasOne(Organization::class);
    }

    public function student()
    {
        return $this->hasOne(Student::class);
    }

    public function getImageUrlAttribute()
    {
        if ($this->image)
        {
            return asset($this->image);
        } else {
            return asset('uploads/default/instructor-default.png');
        }
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */

    public function blogs()
    {
        return $this->hasMany(Blog::class);
    }

    public function publishedBlogs()
    {
        return $this->hasMany(Blog::class)->where('status', 1);
    }

    public function unpublishedBlogs()
    {
        return $this->hasMany(Blog::class)->where('status', 0);
    }

    public function getImagePathAttribute()
    {
        if ($this->image)
        {
            return $this->image;
        } else {
            return 'uploads/default/instructor-default.png';
        }
    }

    public function card()
    {
        return $this->hasOne(User_card::class, 'user_id');
    }

    public function paypal()
    {
        return $this->hasOne(User_paypal::class, 'user_id');
    }

    public function emailNotification()
    {
        return $this->hasOne(Email_notification_setting::class, 'user_id');
    }

    public function exams()
    {
        return $this->hasMany(Exam::class, 'user_id');
    }

    public function notifications()
    {
        return $this->hasMany(Notifiable::class, 'user_id');
    }

    public function unseenNotifications()
    {
        return $this->hasMany(Notification::class, 'user_id')->where('is_seen', 'no');
    }

    public function forumPostComments()
    {
        return $this->hasMany(ForumPostComment::class, 'user_id', 'id');
    }

    public function followings()
    {
        return $this->belongsToMany(User::class,'user_follower','user_id','follower_id');
    }

    public function followers()
    {
        return $this->belongsToMany(User::class,'user_follower','follower_id','user_id');
    }
   
    public function badges()
    {
        return $this->belongsToMany(RankingLevel::class, 'user_badges', 'user_id' , 'ranking_level_id');
    }
   
    public function zoom_settings()
    {
        return $this->hasOne(ZoomSetting::class);
    }

    public static function clientID()
    {
        return auth()->user()->zoom_settings?->api_key;
    }

    public static function clientSecret()
    {
        return auth()->user()->zoom_settings?->api_secret;
    }

    public static function accountID()
    {
        return auth()->user()->zoom_settings?->account_id;
    }
}
