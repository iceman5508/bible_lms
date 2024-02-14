<?php

namespace App\Http\Controllers\Organization;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Country;
use App\Models\Enrollment;
use App\Models\State;
use App\Models\Student;
use App\Models\User;
use App\Models\UserPackage;
use App\Tools\Repositories\Crud;
use App\Traits\General;
use App\Traits\ImageSaveTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class StudentController extends Controller
{
    use General, ImageSaveTrait;

    protected $studentModel;
    public function __construct(Student $student)
    {
        $this->studentModel = new Crud($student);
    }
    public function index()
    {
        $data['title'] = 'All Student';
        $data['navStudentActiveClass'] = 'has-open';
        $data['subNavStudentIndexActiveClass'] = 'active';
        $data['students'] = Student::query()
            ->where('organization_id', auth()->user()->organization->id)
            ->paginate(10);
        return view('organization.student.index', $data);
    }

    public function create()
    {
        $data['title'] = 'Add Student';
        $data['navStudentActiveClass'] = 'has-open';
        $data['subNavStudentAddActiveClass'] = 'active';
        $data['countries'] = Country::orderBy('country_name', 'asc')->get();

        if (old('country_id')) {
            $data['states'] = State::where('country_id', old('country_id'))->orderBy('name', 'asc')->get();
        }

        if (old('state_id')) {
            $data['cities'] = City::where('state_id', old('state_id'))->orderBy('name', 'asc')->get();
        }
        return view('organization.student.create', $data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:2'],
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
        $user->area_code =  str_replace("+", "", $request->area_code);
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
            'organization_id' => auth()->user()->organization->id
        ];

        $this->studentModel->create($student_data);

        $this->showToastrMessage('success', __('Student created successfully'));
        return redirect()->route('organization.student.index');
    }

    public function view($uuid)
    {
        $data['title'] = 'Student Profile';
        $data['navStudentActiveClass'] = 'has-open';
        $data['subNavStudentIndexActiveClass'] = 'active';
        $data['student'] = Student::where('organization_id', auth()->user()->organization->id)->where('uuid', $uuid)->firstOrfail();
        if ($data['student'] == null) {
            $this->showToastrMessage('error', __('Student Not Found!'));
            return redirect()->route('organization.student.index');
        }
        $data['enrollments'] = Enrollment::with('course')->where('user_id', $data['student']->user_id)->whereNotNull('course_id')->latest()->paginate(10);
        $data['userPackageCount'] = UserPackage::query()
            ->where('user_packages.user_id', $data['student']->user_id)
            ->where('package_type', PACKAGE_TYPE_SUBSCRIPTION)
            ->join('packages', 'packages.id', '=', 'user_packages.package_id')
            ->select('user_packages.*')
            ->count();

        return view('organization.student.view', $data);
    }

    public function edit($uuid)
    {
        $data['title'] = 'Edit Student';
        $data['navStudentActiveClass'] = 'has-open';
        $data['subNavStudentIndexActiveClass'] = 'active';
        $data['student'] = Student::where('organization_id', auth()->user()->organization->id)->where('uuid', $uuid)->firstOrfail();
        if ($data['student'] == null) {
            $this->showToastrMessage('error', __('Student Not Found!'));
            return redirect()->route('organization.student.index');
        }
        $data['user'] = User::findOrfail($data['student']->user_id);

        $data['countries'] = Country::orderBy('country_name', 'asc')->get();

        if (old('country_id')) {
            $data['states'] = State::where('country_id', old('country_id'))->orderBy('name', 'asc')->get();
        }

        if (old('state_id')) {
            $data['cities'] = City::where('state_id', old('state_id'))->orderBy('name', 'asc')->get();
        }

        return view('organization.student.edit', $data);
    }

    public function update(Request $request, $uuid)
    {
        $student = $this->studentModel->getRecordByUuid($uuid);

        $request->validate([
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $student->user_id],
            'area_code' => 'required',
            'phone_number' => 'bail|numeric|unique:users,mobile_number,' . $student->user_id,
            'address' => 'required',
            'gender' => 'required',
            'about_me' => 'required',
            'image' => 'mimes:jpeg,png,jpg|file|dimensions:min_width=300,min_height=300,max_width=300,max_height=300|max:1024'
        ]);


        $user = User::findOrfail($student->user_id);
        if (User::where('id', '!=', $student->user_id)->where('email', $request->email)->count() > 0) {
            $this->showToastrMessage('warning', __('Email already exist'));
            return redirect()->back();
        }

        $user->name = $request->first_name . ' ' . $request->last_name;
        $user->email = $request->email;
        if ($request->password) {
            $request->validate([
                'password' => 'required|string|min:6'
            ]);
            $user->password = Hash::make($request->password);
        }
        $user->area_code =  str_replace("+", "", $request->area_code);
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

        $this->showToastrMessage('success', __('Updated Successfully'));
        return redirect()->route('organization.student.index');
    }

    public function status(Request $request)
    {
        $request->validate([
            'id' => 'required',
            'status' => 'required|in:' .  STATUS_APPROVED . ',' . STATUS_REJECTED,
        ]);
        $student = Student::where('organization_id', auth()->user()->organization->id)->findOrFail($request->id);
        if (is_null($student)) {
            return response()->json(['message' => __('Student Not Found!'), 'status' => false]);
        }
        $student->status = $request->status;
        $student->save();
        return response()->json(['message' => __('Student status has been updated'), 'status' => true]);
    }


}
