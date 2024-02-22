<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Country;
use App\Models\Course;
use App\Models\Instructor;
use App\Models\Order_item;
use App\Models\State;
use App\Models\Student;
use App\Models\User;
use App\Tools\Repositories\Crud;
use App\Traits\ApiStatusTrait;
use App\Traits\ImageSaveTrait;
use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Ellaisys\Cognito\Auth\RegistersUsers;
use Ellaisys\Cognito\Auth\AuthenticatesUsers as CognitoAuthenticatesUsers;
use Ellaisys\Cognito\AwsCognitoClient;

class InstructorController extends Controller
{
    use ApiStatusTrait, ImageSaveTrait, CognitoAuthenticatesUsers, RegistersUsers;

    protected $instructorModel, $studentModel;
    public function __construct(Instructor $instructor, Student $student)
    {
        $this->instructorModel = new Crud($instructor);
        $this->studentModel = new Crud($student);
    }

    public function index()
    {
        if (!Auth::user()->hasPermissionTo('all_instructor', 'web')) {
            return $this->error([], 'Unauthorize access', 403);
        } // end permission checking
        
        $data['instructors'] = $this->instructorModel->getOrderById('DESC', 25);
        return $this->success($data);
    }

    public function view($uuid)
    {
        $data['instructor'] = Instructor::where('uuid', $uuid)->with(['city', 'state', 'country', 'certificates', 'awards'])->first();
        if(is_null($data['instructor'])){
            return $this->error([], __('Not Found'), 404);
        }

        $userCourseIds = Course::whereUserId($data['instructor']->user->id)->pluck('id')->toArray();
        if (count($userCourseIds) > 0){
            $orderItems = Order_item::whereIn('course_id', $userCourseIds)
                ->whereYear("created_at", now()->year)->whereMonth("created_at", now()->month)
                ->whereHas('order', function ($q) {
                    $q->where('payment_status', 'paid');
                });
            $data['total_earning'] = $orderItems->sum('owner_balance');
        }

        $data['publishedCourse'] = $data['instructor']->publishedCourses->count();
        $data['pendingCourse'] = $data['instructor']->pendingCourses->count();

        return $this->success($data);
    }

    public function pending()
    {
        if (!Auth::user()->hasPermissionTo('pending_instructor', 'web')) {
            return $this->error([], 'Unauthorize access', 403);
        } // end permission checking

        $data['instructors'] = Instructor::pending()->orderBy('id', 'desc')->paginate(25);
        return $this->success($data);
    }

    public function approved()
    {
        if (!Auth::user()->hasPermissionTo('approved_instructor', 'web')) {
            return $this->error([], 'Unauthorize access', 403);
        } // end permission checking

        $data['instructors'] = Instructor::approved()->orderBy('id', 'desc')->paginate(25);
        return $this->success($data);
    }

    public function blocked()
    {
        if (!Auth::user()->hasPermissionTo('approved_instructor', 'web')) {
            return $this->error([], 'Unauthorize access', 403);
        } // end permission checking

        $data['instructors'] = Instructor::blocked()->orderBy('id', 'desc')->paginate(25);
        return $this->success($data);
    }

    public function create()
    {
        $data['countries'] = Country::orderBy('country_name', 'asc')->get();
        $data['states'] = State::orderBy('name', 'asc')->get();
        $data['cities'] = City::orderBy('name', 'asc')->get();

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
            'professional_title' => 'required',
            'area_code' => 'required',
            'phone_number' => 'bail|numeric|unique:users,mobile_number',
            'address' => 'required',
            'gender' => 'required',
            'about_me' => 'required',
            'image' => 'mimes:jpeg,png,jpg|dimensions:min_width=300,min_height=300,max_width=300,max_height=300|max:1024'
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
                
                //If successful, create the user in local db
                $user = new User();
                $user->name = $request->first_name . ' '. $request->last_name;
                $user->email = $request->email;
                $user->email_verified_at = now();
                $user->area_code =  str_replace("+","",$request->area_code);
                $user->mobile_number = $request->phone_number;
                $user->phone_number = $request->phone_number;
                $user->password = Hash::make($request->password);
                $user->role = 2;
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

                if (Instructor::where('slug', getSlug($user->name))->count() > 0)
                {
                    $slug = getSlug($user->name) . '-'. rand(100000, 999999);
                } else {
                    $slug = getSlug($user->name);
                }

                $instructor_data = [
                    'user_id' => $user->id,
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                    'address' => $request->address,
                    'professional_title' => $request->professional_title,
                    'phone_number' => $user->phone_number,
                    'slug' => $slug,
                    'status' => 1,
                    'country_id' => $request->country_id,
                    'state_id' => $request->state_id,
                    'city_id' => $request->city_id,
                    'gender' => $request->gender,
                    'about_me' => $request->about_me,
                    'postal_code' => $request->postal_code,
                    'social_link' => json_encode($request->social_link),
                ];

                $this->instructorModel->create($instructor_data);

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

    public function update(Request $request, $uuid)
    {
        $instructor = $this->instructorModel->getRecordByUuid($uuid);
        $request->validate([
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'professional_title' => 'required',
            'area_code' => 'required',
            'phone_number' => 'bail|numeric|unique:users,mobile_number,'.$instructor->user_id,
            'address' => 'required',
            'gender' => 'required',
            'about_me' => 'required',
            'image' => 'mimes:jpeg,png,jpg|file|dimensions:min_width=300,min_height=300,max_width=300,max_height=300|max:1024'
        ]);


        try{
            DB::beginTransaction();
            $user = User::find($instructor->user_id);
            if(is_null(($user))){
                return $this->error([], __("Instructor not found"), 404);
            }
            $user->name = $request->first_name . ' '. $request->last_name;
            $user->area_code =  str_replace("+","",$request->area_code);
            $user->mobile_number = $request->phone_number;
            $user->phone_number = $request->phone_number;
            
            $user->image =  $request->image ? $this->saveImage('user', $request->image, null, null) :   $user->image;
            $user->save();

            if (Instructor::where('slug', getSlug($user->name))->count() > 0)
            {
                $slug = getSlug($user->name) . '-'. rand(100000, 999999);
            } else {
                $slug = getSlug($user->name);
            }

            $instructor_data = [
                'user_id' => $user->id,
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'address' => $request->address,
                'professional_title' => $request->professional_title,
                'phone_number' => $user->phone_number,
                'slug' => $slug,
                'country_id' => $request->country_id,
                'state_id' => $request->state_id,
                'city_id' => $request->city_id,
                'gender' => $request->gender,
                'about_me' => $request->about_me,
                'postal_code' => $request->postal_code,
                'social_link' => json_encode($request->social_link),
            ];

            $this->instructorModel->updateByUuid($instructor_data, $uuid);

            DB::commit();
            return $this->success([] , __('Instructor Updated successfully'));
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->failed([], $e->getMessage());
        }
    }
}
