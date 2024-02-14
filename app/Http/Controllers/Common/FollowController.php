<?php

namespace App\Http\Controllers\Common;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FollowController extends Controller
{
    public function follow(Request $request)
    {
        if (auth()->id() != $request->id) {
            auth()->user()->followings()->syncWithoutDetaching([$request->id => ['created_at' => now()]]);
            $msg = __("Follow successfully");
            $status = 200;
        }
        else{
            $msg = __("You can not follow yourself");
            $status = 403;
        }

        $response['msg'] = $msg;
        $response['status'] = $status;
        return response()->json($response, $status);

    }

    public function unfollow(Request $request)
    {
        if (auth()->id() != $request->id) {
            auth()->user()->followings()->detach($request->id);
            $msg = __("unFollow successfully");
            $status = 200;
        }
        else{
            $msg = __("You can not unfollow yourself");
            $status = 403;
        }

        $response['msg'] = $msg;
        $response['status'] = $status;
        return response()->json($response, $status);
    }
}
