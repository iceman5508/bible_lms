<?php

namespace App\Traits;

use App\Models\Notification;

trait SendNotification
{
    private function send($text, $user_type, $target_url = null, $user_id = null)
    {
        $notification = new Notification();
        $notification->user_id = $user_id;
        $notification->text = $text;
        $notification->target_url = $target_url;
        $notification->user_type = $user_type;
        $notification->save();
        return $notification;
    }

    private function sendForApi($text, $user_type, $target_url = null, $user_id = null, $sender_id=null)
    {
        $notification = new Notification();
        $notification->user_id = $user_id;
        $notification->sender_id = $sender_id;
        $notification->text = $text;
        $notification->target_url = $target_url;
        $notification->user_type = $user_type;
        $notification->save();
        return $notification;
    }
}
