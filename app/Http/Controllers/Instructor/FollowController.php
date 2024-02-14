<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;

class FollowController extends Controller
{
    public function followers()
    {
        $data['title'] = 'Followers';
        $data['navFollowersActiveClass'] = 'active';
        $data['followers'] = auth()->user()->followers;
        return view('instructor.follow.followers', $data);
    }

    public function followings()
    {
        $data['title'] = 'Followings';
        $data['navFollowingsActiveClass'] = 'active';
        $data['followings'] = auth()->user()->followings;
        return view('instructor.follow.followings', $data);
    }
}
