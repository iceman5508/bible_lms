<?php

namespace App\Http\Controllers\Api\Student;

use App\Http\Controllers\Controller;
use App\Http\Requests\Student\ProfileApiRequest;
use App\Models\City;
use App\Models\Enrollment;
use App\Models\Instructor;
use App\Models\InstructorFeature;
use App\Models\InstructorProcedure;
use App\Models\Organization;
use App\Models\State;
use App\Models\Student;
use App\Models\User;
use App\Tools\Repositories\Crud;
use App\Traits\ApiStatusTrait;
use App\Traits\General;
use App\Traits\ImageSaveTrait;
use App\Traits\SendNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{

    use  ImageSaveTrait, General, SendNotification, ApiStatusTrait;

    protected $studentModel;
    protected $organizationModel;
    protected $instructorModel;

    public function __construct(Student $student, Instructor $instructor, Organization $organization)
    {
        $this->studentModel = new Crud($student);
        $this->instructorModel = new Crud($instructor);
        $this->organizationModel = new Crud($organization);
    }

    public function profile()
    {
        $data = auth::user();
        $data->student;
        return $this->success($data);
    }

    public function saveProfile(ProfileApiRequest $request, $uuid)
    {
        try {
            DB::beginTransaction();
            $student = $this->studentModel->getRecordByUuid($uuid);

            $user = User::find($student->user_id);

            if ($request->image) {
                $this->deleteFile($user->image); // delete file from server

                $image = $this->saveImage('user', $request->image, null, 'null'); // new file upload into server

            } else {
                $image = $user->image;
            }

            $user->name = $request->first_name . ' ' . $request->last_name;
            $user->image = $image;
            $user->mobile_number = $request->mobile_number;
            $user->phone_number = $request->mobile_number;
            $user->address = $request->address;
            $user->save();

            $data = [
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'mobile_number' => $request->mobile_number,
                'phone_number' => $request->mobile_number,
                'about_me' => $request->about_me,
                'gender' => $request->gender,
                'country_id' => $request->country_id,
                'state_id' => $request->state_id,
                'city_id' => $request->city_id,
                'postal_code' => $request->postal_code,
                'address' => $request->address,
            ];

            $this->studentModel->updateByUuid($data, $uuid);

            DB::commit();

            return $this->success([], __('Updated Successful'));
        } catch (\Exception $e) {
            DB::rollback();
            return $this->failed([], $e->getMessage());
        }
    }

    public function changePasswordUpdate(Request $request)
    {
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

    public function becomeAnInstructor()
    {
        if (auth()->user()->role == USER_ROLE_INSTRUCTOR) {
            $message =  __('You are already an instructor!');
            return $this->failed([], $message);
        } elseif (auth()->user()->role == USER_ROLE_ORGANIZATION) {
            $message =  __('You are already an organization!');
            return $this->failed([], $message);
        }

        $data['instructorFeatures'] = InstructorFeature::take(3)->get();
        $data['instructorProcedures'] = InstructorProcedure::all();
        $data['total_students'] = Student::count();
        $data['total_enrollments'] = Enrollment::count();
        $data['total_instructors'] = Instructor::count();
        return $this->success($data);
    }

    public function saveInstructorInfo(Request $request)
    {
        $request->validate(
            [
                'first_name' => 'required',
                'last_name' => 'required',
                'professional_title' => 'required',
                'about_me' => 'required',
                'cv_file' => 'required|max:5000|mimes:pdf',
            ],
            [
                'required'  => 'The :attribute field is required.',
            ]
        );

        $authUser = Auth::user();

        $object = Instructor::where('user_id', $authUser->id)->get();

        if ($object->count() > 0) {
            $message =  __('Request already send');
            return $this->success([], $message);
        } else {

            $slugCount = Instructor::where('slug', getSlug($authUser->name))->count();

            if ($slugCount) {
                $slug = getSlug($authUser->name) . '-' . rand(100000, 999999);
            } else {
                $slug = getSlug($authUser->name);
            }

            $cv_file_data = $this->uploadFileWithDetails('user', $request->cv_file);
            if (!$cv_file_data['is_uploaded']) {
                $message = __('Something went wrong! Failed to upload file');
                return $this->success([], $message);
            }
            $data = [
                'user_id' => Auth::user()->id,
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'professional_title' => $request->professional_title,
                'phone_number' => $request->phone_number,
                'address' => $request->address,
                'about_me' => $request->about_me,
                'slug' => $slug,
                'cv_file' => $cv_file_data['path'],
                'cv_filename' => $cv_file_data['original_filename'],
            ];

            $this->instructorModel->create($data);
            $text = __("New Instructor request");

            $this->send($text, 1);

            $message = __('Request successfully send');
            return $this->success([], $message);
        }
    }

    public function getStateByCountry($country_id)
    {
        $data['states'] = State::where('country_id', $country_id)->orderBy('name', 'asc')->get();
        return $this->success($data);
    }

    public function getCityByState($state_id)
    {
        $data['cities'] = City::where('state_id', $state_id)->orderBy('name', 'asc')->get();
        return $this->success($data);
    }
}
