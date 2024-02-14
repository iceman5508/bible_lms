<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\EditUserRequest;
use App\Http\Requests\Admin\UserRequest;
use App\Models\Country;
use App\Models\User;
use App\Traits\General;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use DB;
use Auth;

class UserController extends Controller
{
    use General;

    public function index()
    {
        if (!Auth::user()->can('user_management')) {
            abort('403');
        } // end permission checking

        $data['title'] = __('All Users');
        $data['users'] = User::whereRole(1)->withTrashed()->paginate(25);
        $data['navUserParentActiveClass'] = 'mm-active';
        $data['navUserParentShowClass'] = 'mm-show';
        $data['subNavUserActiveClass'] = 'mm-active';
        return view('admin.user.index', $data);
    }

    public function create()
    {
        if (!Auth::user()->can('user_management')) {
            abort('403');
        } // end permission checking

        $data['title'] = 'Add User';
        $data['navUserParentActiveClass'] = 'mm-active';
        $data['navUserParentShowClass'] = 'mm-show';
        $data['subNavUserCreateActiveClass'] = 'mm-active';
        $data['roles'] = Role::all();
        $data['countries'] = Country::all();
        return view('admin.user.create', $data);
    }


    public function store(UserRequest $request)
    {
        if (!Auth::user()->can('user_management')) {
            abort('403');
        } // end permission checking

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->area_code =  str_replace("+","",$request->area_code);
        $user->mobile_number = $request->phone_number;
        $user->phone_number = $request->phone_number;
        $user->address = $request->address;
        $user->password = Hash::make($request->password);
        $user->role = 1;
        $user->assignRole($request->role_name);
        $user->email_verified_at = Carbon::now()->format("Y-m-d H:i:s");
        $user->save();
        return $this->controlRedirection($request, 'user', 'User');

    }

    public function edit($id)
    {
        if (!Auth::user()->can('user_management')) {
            abort('403');
        } // end permission checking

        $data['title'] = 'Edit User';
        $data['navUserParentActiveClass'] = 'mm-active';
        $data['navUserParentShowClass'] = 'mm-show';
        $data['subNavUserActiveClass'] = 'mm-active';
        $data['roles'] = Role::all();
        $data['user'] = User::find($id);
        $data['countries'] = Country::all();
        return view('admin.user.edit', $data);
    }

    public function update(EditUserRequest $request, $id)
    {
        if (!Auth::user()->can('user_management')) {
            abort('403');
        } // end permission checking

        if (User::whereEmail($request->email)->where('id', '!=', $id)->count() > 0)
        {
            $this->showToastrMessage('warning', __('Email already exist'));
            return redirect()->back();
        }

        $user = User::find($id);
        $user->name = $request->name;
        $user->email = $request->email;
        $user->area_code =  str_replace("+","",$request->area_code);
        $user->mobile_number = $request->phone_number;
        $user->phone_number = $request->phone_number;
        $user->address = $request->address;
        if ($request->role_name)
        {
            DB::table('model_has_roles')->where('role_id', $user->roles->first()->id)->where('model_id', $id)->delete();
        }
        $user->assignRole($request->role_name);
        $user->save();
        return $this->controlRedirection($request, 'user', 'User');

    }

    public function delete($id)
    {
        if (!Auth::user()->can('user_management')) {
            abort('403');
        } // end permission checking

        User::whereId($id)->delete();

        $this->showToastrMessage('error', __('User has been deleted'));
        return redirect()->back();
    }

}
