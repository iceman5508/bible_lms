<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Enrollment;
use App\Models\Student;
use App\Models\User;
use App\Tools\Repositories\Crud;
use App\Traits\ApiStatusTrait;
use App\Traits\ImageSaveTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Ellaisys\Cognito\Auth\RegistersUsers;
use Ellaisys\Cognito\Auth\AuthenticatesUsers as CognitoAuthenticatesUsers;
use Ellaisys\Cognito\AwsCognitoClient;

class StudentController extends Controller
{
    use ApiStatusTrait, ImageSaveTrait, CognitoAuthenticatesUsers, RegistersUsers;

    protected $studentModel;
    public function __construct( Student $student)
    {
        $this->studentModel = new Crud($student);
    }
    public function index()
    {
        $data['students'] = Student::with(['city', 'state', 'country', 'user'])->orderBy('id', 'DESC')->paginate(25);
        return $this->success($data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => [
                'required',
                'min:6',
                'max:64',
                'regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\x]).*$/'
            ],
            'area_code' => 'required',
            'phone_number' => 'bail|numeric|unique:users,mobile_number',
            'address' => 'required',
            'gender' => 'required',
            'about_me' => 'required',
            'image' => 'mimes:jpeg,png,jpg|file|dimensions:min_width=300,min_height=300,max_width=300,max_height=300|max:1024'
        ]);


        try{
            DB::beginTransaction();
            //Create credentials object
            $data = $request->only('email', 'password');

            $data['name'] = $request->first_name.' '.$request->last_name;
            // $data['phone_number'] = $request->area_code.$request->phone_number;
            //Register User in cognito
            if ($cognitoRegistered = $this->createCognitoUser(collect($data), null, config('cognito.default_user_group'))) {

                $confirmPassword = app()->make(AwsCognitoClient::class)->setUserPassword($request->email, $request->password, true);
                $user = new User();
                $user->name = $request->first_name . ' '. $request->last_name;
                $user->email = $request->email;
                $user->area_code =  str_replace("+","",$request->area_code);
                $user->mobile_number = $request->phone_number;
                $user->phone_number = $request->phone_number;
                $user->email_verified_at = now();
                $user->password = Hash::make($request->password);
                $user->role = 3;
                $user->image =  $request->image ? $this->saveImage('user', $request->image, null, null) :   null;
                $user->save();

                $student_data = [
                    'user_id' => $user->id,
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                    'address' => $request->address,
                    'phone_number' => $user->phone_number,
                    'country_id' => $request->country_id,
                    'state_id' => $request->state_id,
                    'city_id' => $request->city_id,
                    'gender' => $request->gender,
                    'about_me' => $request->about_me,
                    'postal_code' => $request->postal_code,
                ];

                $this->studentModel->create($student_data);

                DB::commit();
                return $this->success([] , __('Instructor created successfully'));
            } else {
                DB::rollBack();
                return $this->failed();
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->failed([], $e->getMessage());
        }
    }

    public function view($uuid)
    {
        $data['student'] = Student::with(['city', 'state', 'country', 'user'])->orderBy('id', 'DESC')->where('uuid', $uuid)->first();
        
        if(is_null(($data['student']))){
            return $this->error([], __("Student not found"), 404);
        }

        $data['enrollments'] = Enrollment::where('user_id', $data['student']->user_id)->whereNotNull('course_id')->latest()->paginate(15);

        return $this->success($data);
    }

    public function update(Request $request, $uuid)
    {
        $student = $this->studentModel->getRecordByUuid($uuid);

        $request->validate([
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'area_code' => 'required',
            'phone_number' => 'bail|numeric|unique:users,mobile_number,'.$student->user_id,
            'address' => 'required',
            'gender' => 'required',
            'about_me' => 'required',
            'image' => 'mimes:jpeg,png,jpg|file|dimensions:min_width=300,min_height=300,max_width=300,max_height=300|max:1024'
        ]);

        try{
            DB::beginTransaction();

            $user = User::find($student->user_id);

            if(is_null(($user))){
                return $this->error([], __("Student not found"), 404);
            }
    
            $user->name = $request->first_name . ' '. $request->last_name;
            $user->email = $request->email;
            $user->area_code =  str_replace("+","",$request->area_code);
            $user->mobile_number = $request->phone_number;
            $user->phone_number = $request->phone_number;
            $user->image =  $request->image ? $this->saveImage('user', $request->image, null, null) :   $user->image;
            $user->save();
    
            $student_data = [
                'user_id' => $user->id,
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'address' => $request->address,
                'phone_number' => $user->phone_number,
                'country_id' => $request->country_id,
                'state_id' => $request->state_id,
                'city_id' => $request->city_id,
                'gender' => $request->gender,
                'about_me' => $request->about_me,
                'postal_code' => $request->postal_code,
            ];
    
            $this->studentModel->updateByUuid($student_data, $uuid);
    
            DB::commit();
            return $this->success([] , __('Student Updated successfully'));
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->failed([], $e->getMessage());
        }
    }
}
