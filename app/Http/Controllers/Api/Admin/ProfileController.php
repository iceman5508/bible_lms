<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProfileRequest;
use App\Models\User;
use App\Traits\ApiStatusTrait;
use App\Traits\General;
use App\Traits\ImageSaveTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    use General, ImageSaveTrait, ApiStatusTrait;

    public function changePasswordUpdate(Request $request)
    {
        if (!Auth::user()->hasPermissionTo('account_setting', 'web')) {
            return $this->error([], 'Unauthorize access', 403);
        } // end permission checking

        $request->validate([
            'email' => 'required',
            'password' => 'required',
            'new_password' => [
                'required',
                'min:6',
                'max:64',
                'confirmed'
            ],
        ]);

        try{
            if(auth()->user()->email != $request->email){
                return $this->error([], __('Invalid email'));
            }

            $user = User::find(Auth::id());

            if (Hash::check($request->password, $user->password)) {
                $user->password = Hash::make($request->new_password);
                $user->save();
                return $this->success([], __('Password changed successfully'));
            } else {
                return $this->success([], __('Your old password does not match.'));
            }

        }catch(\Exception $e){
            return $this->error([], $e->getMessage());
        }

    }

    public function update(ProfileRequest $request)
    {
        if (!Auth::user()->hasPermissionTo('account_setting', 'web')) {
            return $this->error([], 'Unauthorize access', 403);
        } // end permission checking

        if ($request->image)
        {
            $image = $this->saveImage('user', $request->image, null, null); // new file upload into server
        } else {
            $image = Auth::user()->image;
        }

        $user = Auth::user();
        $user->name = $request->name;
        $user->phone_number = $request->phone_number;
        $user->address = $request->address;
        $user->image = $image;
        $user->save();

        return $this->success([], __('Profile has been updated'));
    }
}
