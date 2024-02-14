<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\AboutUsGeneral;
use App\Models\Addon\Product\Product;
use App\Models\Assignment;
use App\Models\Bundle;
use App\Models\Category;
use App\Models\City;
use App\Models\ClientLogo;
use App\Models\ContactUs;
use App\Models\ContactUsIssue;
use App\Models\Country;
use App\Models\Course;
use App\Models\Course_lecture;
use App\Models\Enrollment;
use App\Models\Exam;
use App\Models\FaqQuestion;
use App\Models\Home;
use App\Models\Instructor;
use App\Models\InstructorSupport;
use App\Models\Organization;
use App\Models\OurHistory;
use App\Models\Package;
use App\Models\Policy;
use App\Models\RankingLevel;
use App\Models\Review;
use App\Models\State;
use App\Models\TeamMember;
use App\Models\User;
use App\Models\UserPackage;
use App\Rules\ReCaptcha;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;

class MainIndexController extends Controller
{
    public function index()
    {
        if (file_exists(storage_path('installed'))) {
            $data['pageTitle'] = "Home";
            $data['metaData'] = staticMeta(1);
            $data['home'] = Home::first();
            $data['topCourse'] = Enrollment::query()
                ->whereMonth('created_at', now()->month)
                ->select('course_id', DB::raw('count(*) as total'))
                ->groupBy('course_id')
                ->limit(10)
                ->orderBy('total','desc')
                ->get()
                ->pluck('course_id')
                ->toArray();

            if($data['home']->category_courses_area == 1){
                $data['featureCategories'] = Category::with('activeCourses')->with('courses.reviews')->with('courses.user')->with('courses.promotionCourse.promotion')->with('courses.instructor.ranking_level')->with('courses.specialPromotionTagCourse.specialPromotionTag')->active()->feature()->get()->map(function ($q) {
                    $q->setRelation('courses', $q->courses->where('status', 1)->take(12));
                    return $q;
                });
            }
            
            if($data['home']->top_category_area == 1){
                $data['firstFourCategories'] = Category::with('courses')->feature()->active()->take(4)->get();
            }
            
            
            if($data['home']->instructor_support_area == 1){
                $data['aboutUsGeneral'] = AboutUsGeneral::first();
                $data['instructorSupports'] = InstructorSupport::all();
                $data['clients'] = ClientLogo::all();
            }

            if($data['home']->faq_area == 1){
                $data['faqQuestions'] = FaqQuestion::take(6)->get();
            }
            
            
            if($data['home']->instructor_area == 1){
                $data['instructors'] = User::query()
                ->leftJoin('instructors as ins', 'ins.user_id', '=', 'users.id')
                ->leftJoin('organizations as org', 'org.user_id', '=', 'users.id')
                ->whereIn('users.role', [USER_ROLE_INSTRUCTOR,USER_ROLE_ORGANIZATION])
                ->where(function($q){
                    $q->where('ins.status', STATUS_APPROVED)
                    ->orWhere('org.status', STATUS_APPROVED);
                })
                ->with('badges')
                ->select('users.*', 'ins.organization_id', DB::raw(selectStatement()))
                ->paginate(5);
            }
            
            if($data['home']->bundle_area == 1){
                $data['bundles'] = Bundle::with('bundleCourses')->with('user.instructor.ranking_level')->active()->latest()->take(12)->get();
            }

            if($data['home']->consultation_area == 1){
                $data['consultationInstructors'] = User::query()
                ->leftJoin('instructors as ins', 'ins.user_id', '=', 'users.id')
                ->leftJoin('organizations as org', 'org.user_id', '=', 'users.id')
                ->whereIn('users.role', [USER_ROLE_INSTRUCTOR,USER_ROLE_ORGANIZATION])
                ->where(function($q){
                    $q->where('ins.status', STATUS_APPROVED)
                    ->orWhere('org.status', STATUS_APPROVED);
                })
                ->where(function($q){
                    $q->where('ins.consultation_available', STATUS_APPROVED)
                    ->orWhere('org.consultation_available', STATUS_APPROVED);
                })
                ->with('badges')
                ->select('users.*', 'ins.organization_id', DB::raw(selectStatement()))
                ->limit(8)
                ->get();
            }

            if($data['home']->courses_area == 1){
                $data['featuredCourses'] = Course::with('reviews')->with('user')->with('promotionCourse.promotion')->with('instructor.ranking_level')->with('specialPromotionTagCourse.specialPromotionTag')->featured()->active()->take(12)->get();
            }
            if($data['home']->upcoming_courses_area == 1){
                $data['upcomingCourses'] = Course::with('reviews')->with('user')->with('promotionCourse.promotion')->with('instructor.ranking_level')->with('specialPromotionTagCourse.specialPromotionTag')->upcoming()->take(12)->get();
            }
           
            if(isAddonInstalled('LMSZAIPRODUCT')){
                if($data['home']->product_area == 1){
                    $data['products'] = Product::with('reviews')->where('status', STATUS_SUCCESS)->where('is_feature', STATUS_SUCCESS)->take(12)->get();
                }
            }
            
            $data['currencyPlacement'] = get_currency_placement();
            $data['currencySymbol'] = get_currency_symbol();
            $packages = Package::where('status', PACKAGE_STATUS_ACTIVE)->where('in_home', PACKAGE_STATUS_ACTIVE)->orderBy('order', 'ASC')->get();
            $data['subscriptions'] = $packages->where('package_type', PACKAGE_TYPE_SUBSCRIPTION);
            $data['instructorSaas'] = $packages->where('package_type', PACKAGE_TYPE_SAAS_INSTRUCTOR);
            $data['organizationSaas'] = $packages->where('package_type', PACKAGE_TYPE_SAAS_ORGANIZATION);
            
            if($data['home']->subscription_show == 1){
                $data['mySubscriptionPackage'] = UserPackage::where('user_packages.user_id', auth()->id())->where('user_packages.status', PACKAGE_STATUS_ACTIVE)->whereDate('enroll_date', '<=', now())->whereDate('expired_date', '>=', now())->where('package_type', PACKAGE_TYPE_SUBSCRIPTION)->join('packages', 'packages.id', '=', 'user_packages.package_id')->select('package_id', 'package_type', 'subscription_type')->first();
            }
            if($data['home']->saas_show == 1){
                $data['mySaasPackage'] = UserPackage::where('user_packages.user_id', auth()->id())->where('user_packages.status', PACKAGE_STATUS_ACTIVE)->whereDate('enroll_date', '<=', now())->whereDate('expired_date', '>=', now())->whereIn('package_type', [PACKAGE_TYPE_SAAS_INSTRUCTOR, PACKAGE_TYPE_SAAS_ORGANIZATION])->join('packages', 'packages.id', '=', 'user_packages.package_id')->select('package_id', 'package_type', 'subscription_type')->first();
            }
            return view(getThemePath().'.home.home', $data);
        } else {
            return redirect()->to('/install');
        }
    }

    public function aboutUs()
    {
        $data['pageTitle'] = 'About Us';
        $data['metaData'] = staticMeta(7);
        $data['aboutUsGeneral'] = AboutUsGeneral::first();
        $data['ourHistories'] = OurHistory::get();
        $data['teamMembers'] = TeamMember::all();
        $data['instructorSupports'] = InstructorSupport::all();
        $data['clients'] = ClientLogo::all();

        return view('frontend.about', $data);
    }

    public function contactUs()
    {
        $data['pageTitle'] = 'Contact Us';
        $data['metaData'] = staticMeta(8);
        $data['contactUsIssues'] = ContactUsIssue::all();
        return view('frontend.contact', $data);
    }

    public function contactUsStore(Request $request)
    {
        $contact = new ContactUs();
        $contact->name = $request->name;
        $contact->email = $request->email;
        $contact->contact_us_issue_id = $request->contact_us_issue_id;
        $contact->message = $request->message;
        $contact->save();

        $response['msg'] = __('Message sent successfully.');
        return response()->json($response);
    }

    public function faq()
    {
        $data['pageTitle'] = 'FAQ';
        $data['metaData'] = staticMeta(12);
        $data['faqs'] = FaqQuestion::all();
        return view('frontend.faq', $data);
    }

    public function instructorDetails($id, $slug)
    {
        $data['pageTitle'] = 'Instructor Details';
        $data['userInstructor'] = User::query()
            ->with(['followings', 'followers', 'instructor'])
            ->where('role', USER_ROLE_INSTRUCTOR)
            ->findOrFail($id);
        $data['courses'] = Course::where('private_mode', '!=', 1)->active()->whereUserId($id)->paginate(6);
        $data['topCourse'] = Enrollment::query()
                ->whereMonth('created_at', now()->month)
                ->select('course_id', DB::raw('count(*) as total'))
                ->groupBy('course_id')
                ->limit(10)
                ->orderBy('total','desc')
                ->get()
                ->pluck('course_id')
                ->toArray();
        $data['loadMoreButtonShowCourses'] = Course::where('private_mode', '!=', 1)->active()->whereUserId($id)->paginate(7);
        $data['average_rating'] = getUserAverageRating($id);
        $courseIds = Course::where('private_mode', '!=', 1)->where('user_id', $id)->where('status', 1)->pluck('id')->toArray();
        $data['total_rating'] = Review::whereIn('course_id', $courseIds)->count();

        $data['total_assignments'] = Assignment::whereIn('course_id', $courseIds)->count();
        $data['total_lectures'] = Course_lecture::whereIn('course_id', $courseIds)->count();
        $data['total_quizzes'] = Exam::whereIn('course_id', $courseIds)->count();
        $data['badge'] = RankingLevel::select('from', 'to', 'type', 'name', 'badge_image')->get();
        return view('frontend.instructor.instructor-details', $data);
    }

    public function userProfile(User $user)
    {
        if($user->role != USER_ROLE_INSTRUCTOR && $user->role != USER_ROLE_ORGANIZATION){
            abort(404);
        }

        $userRelation = getUserRoleRelation($user);

        $user->with('followings', 'followers', $userRelation, 'badges');
        $data['courses'] = Course::where('private_mode', '!=', 1)->active()->whereUserId($user->id)->paginate(3);
        $data['topCourse'] = Enrollment::query()
                ->whereMonth('created_at', now()->month)
                ->select('course_id', DB::raw('count(*) as total'))
                ->groupBy('course_id')
                ->limit(10)
                ->orderBy('total','desc')
                ->get()
                ->pluck('course_id')
                ->toArray();
        $data['average_rating'] = getUserAverageRating($user->id);
        $courseIds = Course::where('private_mode', '!=', 1)->where('user_id', $user->id)->where('status', 1)->select('id')->pluck('id')->toArray();
        $data['total_rating'] = Review::whereIn('course_id', $courseIds)->count();
        $data['totalStudent'] = Enrollment::where('owner_user_id', $user->id)->groupBy('user_id')->count();
        $data['totalMeeting'] = Enrollment::where('owner_user_id', $user->id)->whereNull('consultation_slot_id')->count();
        $data['total_assignments'] = Assignment::whereIn('course_id', $courseIds)->count();
        $data['total_lectures'] = Course_lecture::whereIn('course_id', $courseIds)->count();
        $data['total_quizzes'] = Exam::whereIn('course_id', $courseIds)->count();
        $data['user'] = $user;
        if($user->role == USER_ROLE_INSTRUCTOR){
            $data['pageTitle'] = __('About Instructor');
        }
        elseif($user->role == USER_ROLE_ORGANIZATION){
            $data['pageTitle'] = __('About Organization');
        }
        elseif($user->role == USER_ROLE_STUDENT){
            $data['pageTitle'] = __('About Student');
        }
        if($data['user']->role == USER_ROLE_ORGANIZATION){
            $data['instructors'] = User::join('instructors as ins', 'ins.user_id', '=', 'users.id')->where('ins.status', STATUS_APPROVED)->where('organization_id', $data['user']->organization->id)->select('*', 'users.id')->paginate(3);
            $data['consultationInstructors'] = User::join('instructors as ins', 'ins.user_id', '=', 'users.id')->where('ins.status', STATUS_APPROVED)->where('ins.consultation_available', STATUS_APPROVED)->where('organization_id', $data['user']->organization->id)->select('*', 'users.id')->paginate(3);
        }

        return view('frontend.user-details', $data);
    }

    public function moreCourse(Request $request,  $user_id)
    {
        $course = Course::where('private_mode', '!=', 1)->active()->where('user_id', $user_id)->paginate(3);

    }

    public function organizationInstructorPaginate(Request $request, User $user)
    {
        $data['user'] = $user;
        if($data['user']->role == USER_ROLE_ORGANIZATION){
            $lastPage = false;
            $data['instructors'] = Instructor::where('organization_id', $data['user']->organization->id)->approved()->paginate(3);
            if($data['instructors']->lastPage() == $request->page){
                $lastPage = true;
            }
            $response['appendOrganizationInstructors'] = View::make('frontend.instructor.render-organization-instructors', $data)->render();
            return response()->json(['status' => true,'data' => $response, 'lastPage' => $lastPage]);
        }
    }

    public function instructorCoursePaginate(Request $request, $id)
    {
        $lastPage = false;
        $data['courses'] = Course::where('private_mode', '!=', 1)->active()->whereUserId($id)->paginate(3);
        if($data['courses']->lastPage() == $request->page){
            $lastPage = true;
        }
        $data['topCourse'] = Enrollment::query()
            ->whereMonth('created_at', now()->month)
            ->select('course_id', DB::raw('count(*) as total'))
            ->groupBy('course_id')
            ->limit(10)
            ->orderBy('total','desc')
            ->get()
            ->pluck('course_id')
            ->toArray();
        $response['appendInstructorCourses'] = View::make('frontend.instructor.render-instructor-courses', $data)->render();
        return response()->json(['status' => true,'data' => $response,'lastPage' => $lastPage]);
    }

    public function allInstructor()
    {
        $data['pageTitle'] = "All Instructor";
        $data['instructors'] = User::query()
        ->leftJoin('instructors as ins', 'ins.user_id', '=', 'users.id')
        ->leftJoin('organizations as org', 'org.user_id', '=', 'users.id')
        ->whereIn('users.role', [USER_ROLE_INSTRUCTOR,USER_ROLE_ORGANIZATION])
        ->where(function($q){
            $q->where('ins.status', STATUS_APPROVED)
            ->orWhere('org.status', STATUS_APPROVED);
        })
        ->select('users.*', 'ins.organization_id', DB::raw(selectStatement()))
        ->paginate(12);
        return view('frontend.instructor.all-instructor', $data);
    }

    public function instructor(Request $request)
    {
        $data['pageTitle']= "Instructor";
        $data['categories'] = Category::active()->orderBy('name', 'asc')->select('id', 'name')->get();

        $users = User::query()
                ->leftJoin('instructors as ins', 'ins.user_id', '=', 'users.id')
                ->leftJoin('organizations as org', 'org.user_id', '=', 'users.id')
                ->whereIn('users.role', [USER_ROLE_INSTRUCTOR,USER_ROLE_ORGANIZATION])
                ->where(function($q){
                    $q->where('ins.status', STATUS_APPROVED)
                    ->orWhere('org.status', STATUS_APPROVED);
                })
                ->select('users.*', 'ins.organization_id', DB::raw(selectStatement()))
                ->paginate(12);

        $mapArray = array();
        foreach($users as $user)
        {
            if($user->lat && $user->long){
                array_push($mapArray, [
                    'coordinates' => ['long' => $user->long, 'lat' => $user->lat],
                    "properties" => [
                        'image' => getImageFile($user->image_path),
                        'name' => $user->name,
                        'popup' => view('components.frontend.instructor', ['user' => $user, 'type' => INSTRUCTOR_CARD_TYPE_THREE])->render()
                    ]
                ]);
            }
        }

        $data['countries'] = Country::all();
        $data['states'] = State::all();
        $data['cities'] = City::all();
        $data['users'] = $users;
        $data['mapData'] = $mapArray;


        $priceMax = User::query()
        ->leftJoin('instructors as ins', 'ins.user_id', '=', 'users.id')
        ->leftJoin('organizations as org', 'org.user_id', '=', 'users.id')
        ->whereIn('users.role', [USER_ROLE_INSTRUCTOR,USER_ROLE_ORGANIZATION])
        ->where(function($q){
            $q->where('ins.status', STATUS_APPROVED)
            ->orWhere('org.status', STATUS_APPROVED);
        })
        ->selectRaw('MAX(case when org.id is null then ins.hourly_rate else org.hourly_rate end) AS hourly_rate')
        ->first();

        $data['priceMax'] = $priceMax ? $priceMax->hourly_rate : 0;

        return view('frontend.instructor.instructor', $data);
    }

    public function filterInstructor(Request $request)
    {
        $data = $this->filterQuery($request);
        $data['html'] = view('frontend.instructor.render_instructor', $data)->render();
        return $data;
    }

    public function filterQuery($request){
        $users = User::query()
                ->leftJoin('instructors as ins', 'ins.user_id', '=', 'users.id')
                ->leftJoin('organizations as org', 'org.user_id', '=', 'users.id')
                ->whereIn('users.role', [USER_ROLE_INSTRUCTOR,USER_ROLE_ORGANIZATION])
                ->where(function($q){
                    $q->where('ins.status', STATUS_APPROVED)
                    ->orWhere('org.status', STATUS_APPROVED);
                });

        if($request->available_for_meeting){
            $users->where(function($q) use($request){
                $q->where('ins.consultation_available', 1);
                $q->orWhere('org.consultation_available', 1);
            });
        }

        if($request->free_meeting){
            $users->where(function($q) use($request){
                if(!$request->available_for_meeting){
                    $q->where('ins.consultation_available', 1);
                    $q->orWhere('org.consultation_available', 1);
                }
            });
            $users->where(function($q){
                $q->where('ins.hourly_rate', 0);
                $q->orWhere('org.hourly_rate', 0);
            });
        }

        if($request->discount_meeting){
            $users->where(function($q) use($request){
                if(!$request->available_for_meeting){
                    $q->where('ins.consultation_available', 1);
                    $q->orWhere('org.consultation_available', 1);
                }

            });
            $users->where(function($q){
               $q->whereColumn('ins.hourly_rate', '<', 'ins.hourly_old_rate');
               $q->orWhereColumn('org.hourly_rate', '<', 'org.hourly_old_rate');
            });
        }

        if($request->country_id){
            $users->where(function($q) use($request){
                $q->where('ins.country_id', $request->country_id)
                ->orWhere('org.country_id', $request->country_id);
            });
        }

        if($request->state_id){
            $users->where(function($q) use($request){
                $q->where('ins.state_id', $request->state_id)
                ->orWhere('org.state_id', $request->state_id);
            });
        }

        if($request->city_id){
            $users->where(function($q) use($request){
                $q->where('ins.city_id', $request->city_id)
                ->orWhere('org.city_id', $request->city_id);
            });
        }

        if($request->available_type){
            $users->where(function($q) use($request){
                $q->where('ins.available_type', $request->available_type)
                ->orWhere('org.available_type', $request->available_type);
            });
        }

        if(is_array($request->consultation_day)){
            $users->leftJoin('instructor_consultation_day_statuses as icds', 'icds.user_id', '=', 'users.id');
            $users->whereIn('icds.day', $request->consultation_day);
        }

        if(is_array($request->category_ids)){
            $users->leftJoin('courses', 'courses.user_id', '=', 'users.id');
            $users->whereIn('courses.category_id', $request->category_ids);
        }

        if(is_array($request->rating)){
            $min = min($request->rating);
            $max = max($request->rating);
            $users->leftJoin(DB::raw('(select users.id, avg(c.average_rating) as avg_rating
            from
                users
            join instructors i on
                i.user_id = users.id
            join courses c on
                c.user_id = users.id and c.average_rating > 0
                group by users.id) as average_table'), 'average_table.id', '=', 'users.id')
            ->whereBetween('average_table.avg_rating', [$min, $max]);
        }

        $users->where(function($q) use($request){
            $q->whereBetween('ins.hourly_rate', [$request->price_min, $request->price_max])
            ->orWhereBetween('org.hourly_rate', [$request->price_min, $request->price_max]);
        });

        if($request->sort_by){
            $users->orderBy('users.created_at', $request->sort_by);
        }
        else{
            $users->orderBy('users.created_at', 'ASC');
        }

        $users->groupBy('users.id');
        $users = $users->select('users.id', 'users.name', 'users.email', 'users.area_code', 'users.mobile_number', 'users.role', 'users.phone_number', 'users.lat', 'users.long', 'users.image', 'users.avatar', 'users.created_at', 'users.is_affiliator', 'users.balance', 'ins.organization_id', DB::raw(selectStatement()))->paginate(12);

        $mapArray = array();
        foreach($users as $user)
        {
            if($user->lat && $user->long){
                array_push($mapArray, [
                    'coordinates' => ['long' => $user->long, 'lat' => $user->lat],
                    "properties" => [
                        'image' => getImageFile($user->image_path),
                        'name' => $user->name,
                        'popup' => view('components.frontend.instructor', ['user' => $user, 'type' => INSTRUCTOR_CARD_TYPE_THREE])->render()
                    ]
                ]);
            }
        }

        $data['users'] = $users;
        $data['mapData'] = $mapArray;

        return  $data;
    }

    public function instructorMore(Request $request){
        $lastPage = false;
        $data = $this->filterQuery($request);

        if($data['users']->lastPage() == $request->page){
            $lastPage = true;
        }

        $data['lastPage'] = $lastPage;
        $data['html'] = View::make('frontend.instructor.render-organization-instructors', ['users' => $data['users']])->render();
        $data['status'] = true;
        return response()->json($data);
    }

    public function organizationDetails($id, $slug)
    {
        $data['pageTitle'] = 'Organization Details';
        $data['userOrganization'] = User::findOrFail($id);
        $data['courses'] = Course::where('private_mode', '!=', 1)->active()->whereUserId($id)->paginate(6);
        $data['loadMoreButtonShowCourses'] = Course::where('private_mode', '!=', 1)->active()->whereUserId($id)->paginate(7);
        $data['average_rating'] = getUserAverageRating($id);
        $courseIds = Course::where('private_mode', '!=', 1)->where('user_id', $id)->where('status', 1)->pluck('id')->toArray();
        $data['total_rating'] = Review::whereIn('course_id', $courseIds)->count();
        $data['instructors'] = Instructor::approved()->paginate(3);
        $data['consultationInstructors'] = Instructor::approved()->consultationAvailable()->take(8)->get();
        $data['total_assignments'] = Assignment::whereIn('course_id', $courseIds)->count();
        $data['total_lectures'] = Course_lecture::whereIn('course_id', $courseIds)->count();
        $data['total_quizzes'] = Exam::whereIn('course_id', $courseIds)->count();
        $data['topCourse'] = Enrollment::query()
        ->whereMonth('created_at', now()->month)
        ->select('course_id', DB::raw('count(*) as total'))
        ->groupBy('course_id')
        ->limit(10)
        ->orderBy('total','desc')
        ->get()
        ->pluck('course_id')
        ->toArray();
        return view('frontend.organizations.view', $data);
    }

    public function termConditions()
    {
        $data['pageTitle'] = "Terms & Conditions";
        $data['policy'] = Policy::whereType(3)->first();

        return view('frontend.terms-conditions', $data);
    }

    public function privacyPolicy()
    {
        $data['pageTitle'] = "Privacy Policy";
        $data['metaData'] = staticMeta(10);
        $data['policy'] = Policy::whereType(1)->first();

        return view('frontend.privacy-policy', $data);
    }

    public function cookiePolicy()
    {
        $data['pageTitle'] = "Cookie Policy";
        $data['metaData'] = staticMeta(11);
        $data['policy'] = Policy::whereType(2)->first();

        return view('frontend.cookie-policy', $data);
    }
    
    public function refundPolicy()
    {
        $data['pageTitle'] = "Refund Policy";
        $data['metaData'] = staticMeta(14);
        $data['policy'] = Policy::whereType(4)->first();

        return view('frontend.refund-policy', $data);
    }
   
    public function comingSoon()
    {
        $data['pageTitle'] = get_option('coming_soon_title');
        $data['pageDescription'] = get_option('coming_soon_description');
        $data['comingSoonDate'] = get_option('coming_soon_date');
        return view('zainiklab.coming-soon', $data);
    }
    
    public function getUserProfile(Request $request)
    {
        $user = User::find($request->id)->only(['id', 'name', 'image']);
        return response()->json(['user' => $user], 200);
    }
}
