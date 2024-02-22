<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\UserEnailVerificaion;
use App\Models\Student;
use App\Models\User;
use App\Traits\ApiStatusTrait;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{

    use ApiStatusTrait;

    public function register(Request $request)
    {
        $validate = $request->validate([
            'first_name' => 'required|max:255',
            'last_name' => 'required|max:255',
            'email' => 'required|email|max:64|unique:users',
            'area_code' => 'required',
            'mobile_number' => 'bail|required|numeric|unique:users',
            'password' => [
                'required',
                'min:6',
                'max:64',
                // 'regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\x]).*$/',
                'confirmed'
            ],
        ]);
        
        try {
            DB::beginTransaction();
            $this->_registerOrLoginUser($validate);
            DB::commit();
            return $this->success([], __('Successfully Registered'));
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->failed([], $e->getMessage());
        }
    }


    protected function _registerOrLoginUser($data)
    {
        $user = User::where('email', '=', $data['email'])->first();

        if (!$user) {
            $user = new User();
            $user->name = $data['first_name'].' '.$data['last_name'];
            $user->area_code =  str_replace("+","",$data['area_code']);
            $user->mobile_number = $data['mobile_number'];
            $user->email = $data['email'];
            $user->phone_number = $data['mobile_number'];
            $user->remember_token = request()->input('_token');
            $user->email_verified_at = get_option('registration_email_verification') == 1 ? NULL : now();
            $user->password = bcrypt($data['password']);
            $user->role = USER_ROLE_STUDENT;
            $user->save();

            $student_data = [
                'user_id' => $user->id,
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'phone_number' => $user->phone_number,
                'status' => get_option('private_mode') ? STATUS_PENDING : STATUS_ACCEPTED
            ];

            Student::create($student_data);

            if(get_option('registration_system_bonus_mode', 0)){
                $balance = get_option('registration_bonus_amount');
                $user->increment('balance', decimal_to_int($balance));
                createTransaction($user->id, $balance, TRANSACTION_REGISTRATION_BONUS, 'Registration Bonus');
            }
    
            if (get_option('registration_email_verification') == 1){
                Mail::to($user->email)->send(new UserEnailVerificaion($user));
            }
        }
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);


        $field = 'email';
        if (filter_var($request->input('email'), FILTER_VALIDATE_EMAIL)) {
            $field = 'email';
        }
        elseif (is_numeric($request->input('email'))) {
            $field = 'mobile_number';
        }

        $request->merge([$field => $request->input('email')]);

        $credentials = $request->only($field, 'password');

        /*
        role 2 = instructor
        role 3 = student
        -----------------
        status 1 = Approved
        status 2 = Blocked
        status 0 = Pending
        */

        if (Auth::attempt($credentials)) {
            $user = auth()->user();
            if ($user->role == USER_ROLE_STUDENT && $user->student->status == STATUS_REJECTED){
                Auth::logout();
                return $this->failed([], __('Your account has been blocked!'));
            }

            if ($user->role == USER_ROLE_STUDENT && $user->student->status == STATUS_PENDING){
                Auth::logout();
                return $this->failed([], __('Your account has been in pending status. Please wait until approval.'));
            }

            if ($user->role == USER_ROLE_INSTRUCTOR && $user->student->status == STATUS_REJECTED && $user->instructor->status == STATUS_REJECTED){
                Auth::logout();
                return $this->failed([], __('Your account has been blocked!'));
            }
            if (get_option('registration_email_verification') == 1){
                $check = $user->hasVerifiedEmail();
                if (!$check){
                    Auth::logout();
                    return $this->failed([], __('Your email is not verified!'));
                }
            }

            $response['token'] = $user->createToken(Str::random(32))->accessToken;

            return $this->success($response, __('Successfully Logged In'));
        }

        return $this->failed([], __('Ops! You have entered invalid credentials'));
    }
   
    /**
     * Social Login
     */
    public function socialLogin(Request $request)
    {
        try{
            $provider = $request->input('provider_name');
            $token = $request->input('access_token');
    
            $user = Socialite::driver($provider)->userFromToken($token);
            return $this->_registerOrLoginUserSocial($user);
        }catch(Exception $e){
            dd($e->getMessage());
            return $this->failed([], __('Ops! You have entered invalid credentials'));
        }
    }

    protected function _registerOrLoginUserSocial($data)
    {
        $user = User::where('email', '=', $data->email)->first();

        if (!$user) {
            $user = new User();
            $user->name = $data->name;
            $user->email = $data->email;
            $user->provider_id = $data->id;
            $user->avatar = $data->avatar;
            $user->role = 3;
            $user->email_verified_at = now();
            $user->save();

            $full=$data->name;
            $full1=explode(' ', $full);
            $first=$full1[0];
            $rest=ltrim($full, $first.' ');

            $student  = new Student();
            $student->user_id = $user->id;
            $student->first_name = $first;
            $student->last_name = $rest;
            $student->status = get_option('private_mode') ? STATUS_PENDING : STATUS_ACCEPTED;
            $student->save();
        }else{
            $student = $user->student;
        }

        if ($user) {
            if ($user->role == USER_ROLE_STUDENT && $user->student->status == STATUS_REJECTED){
                return $this->failed([], __('Your account has been blocked!'));
            }

            if ($user->role == USER_ROLE_STUDENT && $user->student->status == STATUS_PENDING){
                return $this->failed([], __('Your account has been in pending status. Please wait until approval.'));
            }

            if ($user->role == USER_ROLE_INSTRUCTOR && $user->student->status == STATUS_REJECTED && $user->instructor->status == STATUS_REJECTED){
                return $this->failed([], __('Your account has been blocked!'));
            }
            if (get_option('registration_email_verification') == 1){
                $check = $user->hasVerifiedEmail();
                if (!$check){
                    return $this->failed([], __('Your email is not verified!'));
                }
            }

            $response['token'] = $user->createToken(Str::random(32))->accessToken;

            return $this->success($response, __('Successfully Logged In'));
        }

        return $this->failed([], __('Ops! You have entered invalid credentials'));
    }
}
