<?php

namespace App\Http\Controllers\Organization;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Country;
use App\Models\Instructor;
use App\Models\Package;
use App\Models\State;
use App\Models\Student;
use App\Models\User;
use App\Models\UserPackage;
use App\Tools\Repositories\Crud;
use App\Traits\General;
use App\Traits\ImageSaveTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class InstructorController extends Controller
{
    use General, ImageSaveTrait;

    protected $instructorModel, $studentModel;
    public function __construct(Instructor $instructor, Student $student)
    {
        $this->instructorModel = new Crud($instructor);
        $this->studentModel = new Crud($student);
    }

    public function index()
    {
        $data['title'] = 'All Instructors';
        $data['navInstructorActiveClass'] = 'has-open';
        $data['subNavInstructorIndexActiveClass'] = 'active';
        $data['instructors'] = Instructor::query()
            ->with('user')
            ->where('organization_id', auth()->user()->organization->id)
            ->withCount('courses as total_course')
            ->withCount('enrollments as total_sale')
            ->withSum('orders as sub_total', 'sub_total')
            ->paginate(10);
        return view('organization.instructor.index', $data);
    }

    public function create()
    {
        $data['title'] = 'Add Instructor';
        $data['navInstructorActiveClass'] = 'has-open';
        $data['subNavInstructorAddActiveClass'] = 'active';
        $data['countries'] = Country::orderBy('country_name', 'asc')->get();
        if (old('country_id')) {
            $data['states'] = State::where('country_id', old('country_id'))->orderBy('name', 'asc')->get();
        }
        if (old('state_id')) {
            $data['cities'] = City::where('state_id', old('state_id'))->orderBy('name', 'asc')->get();
        }
        return view('organization.instructor.create', $data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:6'],
            'professional_title' => 'required',
            'area_code' => 'required',
            'phone_number' => 'bail|numeric|unique:users,mobile_number',
            'address' => 'required',
            'gender' => 'required',
            'about_me' => 'required',
            'image' => 'mimes:jpeg,png,jpg|file|dimensions:min_width=300,min_height=300,max_width=300,max_height=300|max:1024'
        ]);

        $user = new User();
        $user->name = $request->first_name . ' ' . $request->last_name;
        $user->email = $request->email;
        $user->email_verified_at = now();
        $user->area_code =  str_replace("+", "", $request->area_code);
        $user->mobile_number = $request->phone_number;
        $user->phone_number = $request->phone_number;
        $user->password = Hash::make($request->password);
        $user->role = USER_ROLE_INSTRUCTOR;
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

        if (Instructor::where('slug', getSlug($user->name))->count() > 0) {
            $slug = getSlug($user->name) . '-' . rand(100000, 999999);
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
            'status' => STATUS_APPROVED,
            'country_id' => $request->country_id,
            'state_id' => $request->state_id,
            'city_id' => $request->city_id,
            'gender' => $request->gender,
            'about_me' => $request->about_me,
            'postal_code' => $request->postal_code,
            'social_link' => json_encode($request->social_link),
            'organization_id' => auth()->user()->organization->id
        ];

        $this->instructorModel->create($instructor_data);

        if(!UserPackage::where('user_id', $user->id)->first()){
            //set default package
            $package = Package::where('is_default', 1)->where('package_type', PACKAGE_TYPE_SAAS_INSTRUCTOR)->firstOrFail();
            $userPackageData['user_id'] = $user->id;
            $userPackageData['is_default'] = 1;
            $userPackageData['package_id'] = $package->id;
            $userPackageData['subscription_type'] =  get_option('saas_ins_default_package_type', 'monthly') == 'yearly' ?  SUBSCRIPTION_TYPE_YEARLY : SUBSCRIPTION_TYPE_MONTHLY;
            $userPackageData['student'] = $package->student;
            $userPackageData['instructor'] = $package->instructor;
            $userPackageData['course'] = $package->course;
            $userPackageData['consultancy'] = $package->consultancy;
            $userPackageData['subscription_course'] = $package->subscription_course;
            $userPackageData['bundle_course'] = $package->bundle_course;
            $userPackageData['product'] = $package->product;
            $userPackageData['admin_commission'] = $package->admin_commission;
            $userPackageData['payment_id'] = NULL;
            $userPackageData['enroll_date'] = now();
            $userPackageData['expired_date'] = get_option('saas_ins_default_package_type', 'monthly') == 'yearly' ? Carbon::now()->addYear() : Carbon::now()->addMonth();
            UserPackage::create($userPackageData);
        }

        setBadge($user->id);

        $this->showToastrMessage('success', __('Instructor created successfully'));
        return redirect()->route('organization.instructor.index');
    }

    public function edit($uuid)
    {
        $data['title'] = 'Edit Instructor';
        $data['navInstructorActiveClass'] = 'has-open';
        $data['subNavInstructorIndexActiveClass'] = 'active';
        $data['instructor'] = Instructor::where('organization_id', auth()->user()->organization->id)->where('uuid', $uuid)->firstOrFail();
        if ($data['instructor'] == null) {
            $this->showToastrMessage('error', __('Instructor Not Found!'));
            return redirect()->route('organization.instructor.index');
        }
        $data['user'] = User::findOrfail($data['instructor']->user_id);

        $data['countries'] = Country::orderBy('country_name', 'asc')->get();

        if (old('country_id')) {
            $data['states'] = State::where('country_id', old('country_id'))->orderBy('name', 'asc')->get();
        }

        if (old('state_id')) {
            $data['cities'] = City::where('state_id', old('state_id'))->orderBy('name', 'asc')->get();
        }

        return view('organization.instructor.edit', $data);
    }

    public function update(Request $request, $uuid)
    {
        $instructor = $this->instructorModel->getRecordByUuid($uuid);

        $request->validate([
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $instructor->user_id],
            'professional_title' => 'required',
            'area_code' => 'required',
            'phone_number' => 'bail|numeric|unique:users,mobile_number,' . $instructor->user_id,
            'address' => 'required',
            'gender' => 'required',
            'about_me' => 'required',
            'image' => 'mimes:jpeg,png,jpg|file|dimensions:min_width=300,min_height=300,max_width=300,max_height=300|max:1024'
        ]);


        $user = User::findOrfail($instructor->user_id);
        if (User::where('id', '!=', $instructor->user_id)->where('email', $request->email)->count() > 0) {
            $this->showToastrMessage('warning', __('Email already exist'));
            return redirect()->back();
        }

        $user->name = $request->first_name . ' ' . $request->last_name;
        $user->email = $request->email;
        $user->area_code =  str_replace("+", "", $request->area_code);
        $user->mobile_number = $request->phone_number;
        $user->phone_number = $request->phone_number;
        if ($request->password) {
            $request->validate([
                'password' => 'required|string|min:6'
            ]);
            $user->password = Hash::make($request->password);
        }
        $user->image =  $request->image ? $this->saveImage('user', $request->image, null, null) :   $user->image;
        $user->save();

        if (Instructor::where('slug', getSlug($user->name))->count() > 0) {
            $slug = getSlug($user->name) . '-' . rand(100000, 999999);
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

        $this->showToastrMessage('success', __('Updated Successfully'));
        return redirect()->route('organization.instructor.index');
    }

    public function status(Request $request)
    {
        $request->validate([
            'id' => 'required',
            'status' => 'required|in:' .  STATUS_APPROVED . ',' . STATUS_REJECTED,
        ]);
        $instructor = Instructor::where('organization_id', auth()->user()->organization->id)->findOrFail($request->id);
        if (is_null($instructor)) {
            return response()->json(['message' => __('Instructor Not Found!'), 'status' => false]);
        }
        $instructor->status = $request->status;
        $instructor->save();
        return response()->json(['message' => __('Instructor status has been updated'), 'status' => true]);
    }


}
