<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Traits\General;
use Illuminate\Http\Request;

ini_set('max_execution_time', 3600);

class InstallerController extends Controller
{
    use General;

    public function notificationUrl($uuid)
    {
        $notification = \App\Models\Notification::whereUuid($uuid)->first();
        $notification->is_seen = 'yes';
        $notification->save();

        if (is_null($notification->target_url))
        {
            return redirect(url()->previous());

        } else {
            return redirect($notification->target_url);
        }
    }
   
    public function markAllAsReadNotification(Request $request)
    {
        if(auth()->user()->role == USER_ROLE_ADMIN){
            Notification::where('user_type', 1)->where('is_seen', 'no')->orderBy('created_at', 'DESC')->update(['is_seen' => 'yes']);
        }
        elseif((auth()->user()->role == USER_ROLE_INSTRUCTOR || auth()->user()->role == USER_ROLE_ORGANIZATION) && $request->type == 2){
            Notification::where('user_id', auth()->user()->id)->where('user_type', 2)->where('is_seen', 'no')->update(['is_seen' => 'yes']);
        }
        else{
            Notification::where('user_id', auth()->user()->id)->where('user_type', 3)->where('is_seen', 'no')->update(['is_seen' => 'yes']);
        }
        return back();
    }

}
