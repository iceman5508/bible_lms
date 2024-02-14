<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Http\Requests\Instructor\ProfileRequest;
use App\Models\City;
use App\Models\Country;
use App\Models\Instructor;
use App\Models\Instructor_awards;
use App\Models\Instructor_certificate;
use App\Models\Skill;
use App\Models\State;
use App\Models\User;
use App\Tools\Repositories\Crud;
use App\Traits\General;
use App\Traits\ImageSaveTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    use  ImageSaveTrait, General;

    protected $model;
    protected $userModel;

    public function __construct(Instructor $instructor, User $user)
    {
        $this->model = new Crud($instructor);
        $this->userModel = new Crud($user);
    }

    public function profile()
    {
        $data['title'] = 'Instructor Profile';
        $data['navProfileActiveClass'] = 'has-open';
        $data['subNavProfileBasicActiveClass'] = 'active';
        $data['user'] = Auth::user();
        $data['instructor'] = Auth::user()->instructor;
        $data['skills'] = Skill::where('status',1)->get();
        return view('instructor.profile', $data);
    }

    public function saveProfile(ProfileRequest $request, $uuid)
    {
        $instructor = $this->model->getRecordByUuid($uuid);

        $user = User::find($instructor->user_id);
        if (User::where('id', '!=', $user->id)->where('email', $request->email)->count() > 0) {
            $this->showToastrMessage('warning', __('Email already exist'));
            return redirect()->back();
        } else {
            $user->email = $request->email;
        }

        if ($request->image) {
            $this->deleteFile($user->image); // delete file from server

            $image = $this->saveImage('user', $request->image, null, null); // new file upload into server

        } else {
            $image = $user->image;
        }

        $user->name = $request->first_name . ' ' . $request->last_name;
        $user->image = $image;
        $user->save();

        $instructor_date = [
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'professional_title' => $request->professional_title,
            'phone_number' => $request->phone_number,
            'about_me' => $request->about_me,
            'social_link' => json_encode($request->social_link),
            'gender' => $request->gender,
        ];

        $instructor = $this->model->updateByUuid($instructor_date, $uuid); // update category

        $instructor->skills()->sync($request->skills); // Skills

        /**
         * manage instructor certificate
         */

        $certificate_title = $request->certificate_title;
        $certificate_date = $request->certificate_date;
        if ($certificate_title && $certificate_date) {
            Instructor_certificate::where('instructor_id', $instructor->id)->delete();
            for ($c = 0; $c < count($certificate_title); $c++) {
                if ($certificate_title[$c] != '' && $certificate_date[$c] != '') {
                    $certificate = [
                        'instructor_id' => $instructor->id,
                        'name' => $certificate_title[$c],
                        'passing_year' => $certificate_date[$c]
                    ];
                    Instructor_certificate::create($certificate);
                }
            }
        }

        /**
         * end manage instructor certificate
         */

        /**
         * manage instructor award
         */

        $award_title = $request->award_title;
        $award_year = $request->award_year;
        if ($award_title && $award_year) {
            Instructor_awards::where('instructor_id', $instructor->id)->delete();
            for ($a = 0; $a < count($award_title); $a++) {
                if ($award_title[$a] != '' && $award_year[$a] != '') {
                    $award = [
                        'instructor_id' => $instructor->id,
                        'name' => $award_title[$a],
                        'winning_year' => $award_year[$a]
                    ];
                    Instructor_awards::create($award);
                }
            }
        }

        /**
         * end instructor award
         */


        $this->showToastrMessage('success', __('Successfully save'));
        return redirect()->back();
    }

    public function address()
    {
        $data['title'] = 'Instructor Address';
        $data['navProfileActiveClass'] = 'has-open';
        $data['subNavProfileAddressActiveClass'] = 'active';
        $data['instructor'] = Auth::user()->instructor;
        $data['user'] = Auth::user();
        $data['countries'] = Country::orderBy('country_name', 'asc')->get();
        if (old('country_id'))
        {
            $data['states'] = State::where('country_id', old('country_id'))->orderBy('name', 'asc')->get();
        }
        if (old('state_id'))
        {
            $data['cities'] = City::where('state_id', old('state_id'))->orderBy('name', 'asc')->get();
        }
        return view('instructor.address', $data);
    }

    public function address_update(Request $request, $uuid)
    {
        $request->validate([
            'country_id' => 'required',
            'state_id' => 'required',
            'city_id' => 'required',
            'postal_code' => 'required',
            'address' => 'required',
            'lat' => 'required',
            'long' => 'required',
        ]);
        $instructor = $this->model->getRecordByUuid($uuid);
        $user = User::find($instructor->user_id);
        $user->lat = $request->lat;
        $user->long = $request->long;
        $user->save();
        $data = [
            'country_id' => $request->country_id,
            'state_id' => $request->state_id,
            'city_id' => $request->city_id,
            'postal_code' => $request->postal_code,
            'address' => $request->address,
        ];

        $this->model->updateByUuid($data, $uuid);
        $this->showToastrMessage('success', __('Successfully Updated!'));
        return redirect()->back();
    }

    public function getStateByCountry($country_id)
    {
        return State::where('country_id', $country_id)->orderBy('name', 'asc')->get()->toJson();
    }

    public function getCityByState($state_id)
    {
        return City::where('state_id', $state_id)->orderBy('name', 'asc')->get()->toJson();
    }
}
