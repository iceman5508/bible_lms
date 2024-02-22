<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\EditUserRequest;
use App\Http\Requests\Admin\UserRequest;
use App\Models\User;
use App\Traits\ApiStatusTrait;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Auth;
use Illuminate\Support\Facades\DB;
use Ellaisys\Cognito\Auth\RegistersUsers;
use Ellaisys\Cognito\Auth\AuthenticatesUsers as CognitoAuthenticatesUsers;
use Ellaisys\Cognito\AwsCognitoClient;

class UserController extends Controller
{
    use ApiStatusTrait, CognitoAuthenticatesUsers, RegistersUsers;

    public function index()
    {
        if (!Auth::user()->hasPermissionTo('user_management', 'web')) {
            return $this->error([], 'Unauthorize access', 403);
        } // end permission checking

        $data['users'] = User::whereRole(1)->withTrashed()->paginate(25);
        return $this->success($data);
    }

    public function store(UserRequest $request)
    {
        if (!Auth::user()->hasPermissionTo('user_management', 'web')) {
            return $this->error([], 'Unauthorize access', 403);
        } // end permission checking
        try{
            DB::beginTransaction();
            //Create credentials object
            $data = $request->only('email', 'password');

            $data['name'] = $request->name;
            // $data['phone_number'] = $request->area_code.$request->phone_number;
            //Register User in cognito
            if ($cognitoRegistered = $this->createCognitoUser(collect($data), null, config('cognito.default_user_group'))) {

                $confirmPassword = app()->make(AwsCognitoClient::class)->setUserPassword($request->email, $request->password, true);
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

                DB::commit();
                return $this->success([] , __('User created successfully'));
            } else {
                DB::rollBack();
                return $this->failed();
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->failed([], $e->getMessage());
        }

    }

    public function update(EditUserRequest $request, $id)
    {
        if (!Auth::user()->hasPermissionTo('user_management', 'web')) {
            return $this->error([], 'Unauthorize access', 403);
        } // end permission checking
        try{
            DB::beginTransaction();

            $user = User::find($id);

            if(is_null(($user))){
                return $this->error([], __("User not found"), 404);
            }

            $user->name = $request->name;
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
            
            DB::commit();
            return $this->success([] , __('User Updated successfully'));
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->failed([], $e->getMessage());
        }
    }
}
