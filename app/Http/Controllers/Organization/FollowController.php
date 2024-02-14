<?php

namespace App\Http\Controllers\Organization;

use App\Http\Controllers\Controller;

class FollowController extends Controller
{
    public function followers()
    {
        $data['title'] = 'Followers';
        $data['navFollowersActiveClass'] = 'active';
        $data['followers'] = auth()->user()->followers;
        return view('organization.follow.followers', $data);
    }

    public function followings()
    {
        $data['title'] = 'Followings';
        $data['navFollowingsActiveClass'] = 'active';
        $data['followings'] = auth()->user()->followings;
        return view('organization.follow.followings', $data);
    }
}
