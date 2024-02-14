<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Country;
use App\Models\Course;
use App\Models\Order_item;
use App\Models\Organization;
use App\Models\Package;
use App\Models\State;
use App\Models\Student as ModelsStudent;
use App\Models\User;
use App\Models\UserPackage;
use App\Traits\General;
use App\Traits\ImageSaveTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class OrganizationController extends Controller
{
    use General, ImageSaveTrait;

    public function index()
    {
        if (!auth()->user()->can('all_organization')) {
            abort('403');
        } // end permission checking

        $data['title'] = __('All Organization');
        $data['organizations'] = Organization::orderBy('id', 'DESC')->paginate(25);
        return view('admin.organizations.index', $data);
    }

    public function view($uuid)
    {
        $data['title'] = __('Organization Profile');
        $data['organization'] = Organization::where('uuid', $uuid)->first();
        $userCourseIds = Course::whereUserId($data['organization']->user->id)->pluck('id')->toArray();
        if (count($userCourseIds) > 0) {
            $orderItems = Order_item::whereIn('course_id', $userCourseIds)
                ->whereYear("created_at", now()->year)->whereMonth("created_at", now()->month)
                ->whereHas('order', function ($q) {
                    $q->where('payment_status', 'paid');
                });
            $data['total_earning'] = $orderItems->sum('owner_balance');
        }

        return view('admin.organizations.view', $data);
    }

    public function pending()
    {
        if (!auth()->user()->can('pending_organization')) {
            abort('403');
        } // end permission checking

        $data['title'] = __('Pending for Review');
        $data['organizations'] = Organization::pending()->orderBy('id', 'desc')->paginate(25);
        return view('admin.organizations.pending', $data);
    }

    public function approved()
    {
        if (!auth()->user()->can('approved_organization')) {
            abort('403');
        } // end permission checking

        $data['title'] = __('Approved Organizations');
        $data['organizations'] = Organization::approved()->orderBy('id', 'desc')->paginate(25);
        return view('admin.organizations.approved', $data);
    }

    public function blocked()
    {
        if (!auth()->user()->can('approved_organization')) {
            abort('403');
        } // end permission checking

        $data['title'] = __('Blocked Organizations');
        $data['organizations'] = Organization::blocked()->orderBy('id', 'desc')->paginate(25);
        return view('admin.organizations.blocked', $data);
    }

    public function create()
    {
        $data['title'] = __('Add Organization');
        $data['countries'] = Country::orderBy('country_name', 'asc')->get();

        if (old('country_id')) {
            $data['states'] = State::where('country_id', old('country_id'))->orderBy('name', 'asc')->get();
        }

        if (old('state_id')) {
            $data['cities'] = City::where('state_id', old('state_id'))->orderBy('name', 'asc')->get();
        }

        return view('admin.organizations.add', $data);
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

        try {
            DB::beginTransaction();
            $user = new User();
            $user->name = $request->first_name . ' ' . $request->last_name;
            $user->email = $request->email;
            $user->email_verified_at = now();
            $user->area_code =  str_replace("+", "", $request->area_code);
            $user->mobile_number = $request->phone_number;
            $user->phone_number = $request->phone_number;
            $user->password = Hash::make($request->password);
            $user->role = USER_ROLE_ORGANIZATION;
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

            ModelsStudent::create($student_data);

            if (Organization::where('slug', getSlug($user->name))->count() > 0) {
                $slug = getSlug($user->name) . '-' . rand(100000, 999999);
            } else {
                $slug = getSlug($user->name);
            }

            $organization_data = [
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

            Organization::create($organization_data);

            if(!UserPackage::join('packages', 'packages.id', '=', 'user_packages.package_id')->where('package_type', PACKAGE_TYPE_SAAS_ORGANIZATION)->where('user_packages.user_id', $user->id)->select('user_packages.*')->first()){
                //set default package
                $package = Package::where('id',get_option('default_saas_for_org'))->first();
                // dd($package);
                if(is_null($package) && get_option('saas_mode')){
                    DB::rollBack();
                    $this->showToastrMessage('error', __("You Don't have default SAAS Package For Organization"));
                    return redirect()->back();
                }
                elseif(!is_null($package)){
                    $userPackageData['user_id'] = $user->id;
                    $userPackageData['is_default'] = 1;
                    $userPackageData['package_id'] = $package->id;
                    $userPackageData['subscription_type'] =  get_option('saas_org_default_package_type', 'monthly') == 'yearly' ?  SUBSCRIPTION_TYPE_YEARLY : SUBSCRIPTION_TYPE_MONTHLY;
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
                    $userPackageData['expired_date'] = get_option('saas_org_default_package_type', 'monthly') == 'yearly' ? Carbon::now()->addYear() : Carbon::now()->addMonth();
                    UserPackage::create($userPackageData);
                }
            }

            DB::commit();
            $this->showToastrMessage('success', __('Organization created successfully'));
            return redirect()->route('organizations.index');
        } catch (\Exception $e) {
            DB::rollBack();
            $this->showToastrMessage('error', $e->getMessage());
            return redirect()->route('organizations.index');
        }
    }

    public function edit($uuid)
    {
        $data['title'] = __('Edit Organization');
        $data['organization'] = Organization::where('uuid', $uuid)->first();
        $data['user'] = User::findOrfail($data['organization']->user_id);

        $data['countries'] = Country::orderBy('country_name', 'asc')->get();

        if (old('country_id')) {
            $data['states'] = State::where('country_id', old('country_id'))->orderBy('name', 'asc')->get();
        }

        if (old('state_id')) {
            $data['cities'] = City::where('state_id', old('state_id'))->orderBy('name', 'asc')->get();
        }

        return view('admin.organizations.edit', $data);
    }

    public function update(Request $request, $uuid)
    {
        $organization = Organization::where('uuid', $uuid)->first();
        $request->validate([
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $organization->user_id],
            'professional_title' => 'required',
            'area_code' => 'required',
            'phone_number' => 'bail|numeric|unique:users,mobile_number,' . $organization->user_id,
            'address' => 'required',
            'gender' => 'required',
            'about_me' => 'required',
            'image' => 'mimes:jpeg,png,jpg|file|dimensions:min_width=300,min_height=300,max_width=300,max_height=300|max:1024'
        ]);


        $user = User::findOrfail($organization->user_id);
        if (User::where('id', '!=', $organization->user_id)->where('email', $request->email)->count() > 0) {
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

        if (Organization::where('slug', getSlug($user->name))->count() > 0) {
            $slug = getSlug($user->name) . '-' . rand(100000, 999999);
        } else {
            $slug = getSlug($user->name);
        }

        $organizationData = [
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

        Organization::where('uuid', $uuid)->update($organizationData);

        $this->showToastrMessage('success', __('Updated Successfully'));
        return redirect()->route('organizations.index');
    }

    public function delete($uuid)
    {
        if (!auth()->user()->can('manage_organization')) {
            abort('403');
        } // end permission checking

        $organization = Organization::where('uuid', $uuid)->first();
        $user = User::findOrfail($organization->user_id);

        if ($organization && $user) {
            //Start:: Course Delete
            $courses = Course::whereUserId($user->id)->get();
            if(count($courses)){
                return response()->json(['message' =>  __('This user have courses. Please delete those course before delete the user.'), 'status' => false], 200);
            }
            // foreach ($courses as $course) {
            //     //start:: Course lesson delete
            //     $lessons = Course_lesson::where('course_id', $course->id)->get();
            //     if (count($lessons) > 0) {
            //         foreach ($lessons as $lesson) {
            //             //start:: lecture delete
            //             $lectures = Course_lecture::where('lesson_id', $lesson->id)->get();
            //             if (count($lectures) > 0) {
            //                 foreach ($lectures as $lecture) {
            //                     $lecture = Course_lecture::find($lecture->id);
            //                     if ($lecture) {
            //                         $this->deleteFile($lecture->file_path); // delete file from server

            //                         if ($lecture->type == 'vimeo') {
            //                             if ($lecture->url_path) {
            //                                 $this->deleteVimeoVideoFile($lecture->url_path);
            //                             }
            //                         }

            //                         Course_lecture_views::where('course_lecture_id', $lecture->id)->get()->map(function ($q) {
            //                             $q->delete();
            //                         });

            //                         Course_lecture::find($lecture->id)->delete(); // delete lecture record
            //                     }
            //                 }
            //             }
            //             //end:: delete lesson record
            //             Course_lesson::find($lesson->id)->delete();
            //         }
            //     }
            //     //end

            //     $this->deleteFile($course->image);
            //     $this->deleteVideoFile($course->video);
            //     $course->delete();
            // }
            //End:: Course Delete
        }

        Organization::where('uuid', $uuid)->delete($uuid);

        $user->role = USER_ROLE_ORGANIZATION;
        $user->save();

        return response()->json(['message' =>  __('Organization Deleted Successfully'), 'status' => true], 200);
    }

    public function getStateByCountry($country_id)
    {
        return State::where('country_id', $country_id)->orderBy('name', 'asc')->get()->toJson();
    }

    public function getCityByState($state_id)
    {
        return City::where('state_id', $state_id)->orderBy('name', 'asc')->get()->toJson();
    }

    public function changeOrganizationStatus(Request $request)
    {
        $request->validate([
            'id' => 'required',
            'status' => 'required|in:' . STATUS_PENDING . ', ' . STATUS_APPROVED . ',' . STATUS_REJECTED,
        ]);

        $organization = Organization::findOrFail($request->id);
        if (is_null($organization)) {
            return response()->json(['message' => __('Organization Not Found!'), 'status' => false]);
        }
        try {
            DB::beginTransaction();
            if ($request->status == STATUS_APPROVED)
            {
                $user = $organization->user;
                if(!UserPackage::join('packages', 'packages.id', '=', 'user_packages.package_id')->where('package_type', PACKAGE_TYPE_SAAS_ORGANIZATION)->where('user_packages.user_id', $user->id)->select('user_packages.*')->first()){
                    //set default package
                    $package = Package::where('id',get_option('default_saas_for_org'))->first();
                    // dd($package);
                    if(is_null($package) && get_option('saas_mode')){
                        DB::rollBack();
                        $this->showToastrMessage('error', __("You Don't have default SAAS Package For Organization"));
                        return redirect()->back();
                    }
                    elseif(!is_null($package)){
                        $userPackageData['user_id'] = $user->id;
                        $userPackageData['is_default'] = 1;
                        $userPackageData['package_id'] = $package->id;
                        $userPackageData['subscription_type'] =  get_option('saas_org_default_package_type', 'monthly') == 'yearly' ?  SUBSCRIPTION_TYPE_YEARLY : SUBSCRIPTION_TYPE_MONTHLY;
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
                        $userPackageData['expired_date'] = get_option('saas_org_default_package_type', 'monthly') == 'yearly' ? Carbon::now()->addYear() : Carbon::now()->addMonth();
                        UserPackage::create($userPackageData);
                    }
                }


                $user->role = USER_ROLE_ORGANIZATION;
                $user->save();

                setBadge($user->id);
            }
            $organization->status = $request->status;
            $organization->save();
            DB::commit();
            return response()->json(['message' => __('Organization status has been updated'), 'status' => true]);
        }catch (\Exception $e){
            DB::rollBack();
            $this->showToastrMessage('error', $e->getMessage());
            return redirect()->back();
        }
    }

    public function changeAutoContentStatus(Request $request)
    {
        $instructor = Organization::findOrFail($request->id);
        $instructor->auto_content_approval = $request->auto_content_approval;
        $instructor->save();

        return response()->json([
            'data' => 'success',
        ]);
    }
}
