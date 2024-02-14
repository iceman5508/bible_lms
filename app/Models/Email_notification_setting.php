<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class Email_notification_setting extends Model
{
    use HasFactory;

    protected $table = 'email_notification_settings';
    protected $primaryKey = 'id';
    protected $fillable = [
        'user_id',
        'updates_from_classes',
        'updates_from_teacher_discussion',
        'activity_on_your_project',
        'activity_on_your_discussion_comment',
        'reply_comment',
        'new_follower',
        'new_class_by_someone_you_follow',
        'new_live_session',
    ];

    protected static function boot()
    {
        parent::boot();
        self::creating(function($model){
            $model->user_id =  Auth::id();
            $model->uuid =  Str::uuid()->toString();
        });
    }

}
