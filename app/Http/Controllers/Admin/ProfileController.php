<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProfileRequest;
use App\Models\User;
use App\Traits\General;
use App\Traits\ImageSaveTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    use General, ImageSaveTrait;

    public function index()
    {
        if (!Auth::user()->can('account_setting')) {
            abort('403');
        } // end permission checking

        $data['title'] = 'Profile';
        return view('admin.profile.index', $data);
    }

    public function changePassword()
    {
        if (!Auth::user()->can('account_setting')) {
            abort('403');
        } // end permission checking

        $data['title'] = 'Change Password';
        return view('admin.profile.change-password', $data);
    }

    public function changePasswordUpdate(Request $request)
    {
        if (!Auth::user()->can('account_setting')) {
            abort('403');
        } // end permission checking

        $request->validate([
            'password' => 'required|confirmed|min:6'
        ]);

        $user = Auth::user();
        $user->password = Hash::make($request->password);
        $user->save();

        $this->showToastrMessage('success', __('Password updated successfully.'));
        return redirect()->back();
    }

    public function update(ProfileRequest $request)
    {
        if (!Auth::user()->can('account_setting')) {
            abort('403');
        } // end permission checking

        if ($request->image)
        {
            $this->deleteFile(Auth::user()->image); // delete file from server

            $image = $this->saveImage('user', $request->image, null, null); // new file upload into server

        } else {
            $image = Auth::user()->image;
        }

        $user = Auth::user();

        if ($user) {
           if ($request->email != $user->email) {
            $exists = User::where('email', $request->email)->where('id', '!=', $user->id)->first();
            if ($exists){
                $this->showToastrMessage('error', __('This email is already associated with another account.'));
                return redirect()->back();
            }
           }
        }

        $user->name = $request->name;
        $user->phone_number = $request->phone_number;
        $user->email = $request->email;
        $user->email_verified_at = now();
        $user->address = $request->address;
        $user->image = $image;
        $user->save();

        $this->showToastrMessage('success', __('Profile has been updated'));
        return redirect()->back();

    }

}
