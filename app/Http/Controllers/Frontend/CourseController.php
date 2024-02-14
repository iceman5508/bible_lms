<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\AffiliateRequest;
use App\Models\CartManagement;
use App\Models\Category;
use App\Models\Course;
use App\Models\Difficulty_level;
use App\Models\Discussion;
use App\Models\Enrollment;
use App\Models\Review;
use App\Models\Subcategory;
use App\Traits\General;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;

class CourseController extends Controller
{
    use General;
    private $coursePaginateValue = 9;

    public function allCourses(Request $request)
    {
        $data['pageTitle'] = "Courses";
        $data['metaData'] = staticMeta(2);
        $data['categories'] = Category::with('subcategories')->active()->get();

        if($request->ajax()){
            $lastPage = false;
            $data['courses'] = $this->filterCourseData($request);
            $data['topCourse'] = Enrollment::query()
            ->whereMonth('created_at', now()->month)
            ->select('course_id', DB::raw('count(*) as total'))
            ->groupBy('course_id')
            ->limit(10)
            ->orderBy('total','desc')
            ->get()
            ->pluck('course_id')
            ->toArray();
            $data['courses'] = $data['courses']->paginate($this->coursePaginateValue);

            if( $data['courses']->lastPage() == $request->page){
                $lastPage = true;
            }
    
            $data['lastPage'] = $lastPage;
            $data['html'] = View::make('frontend.course.render-course-list-only', $data)->render();
            $data['status'] = true;
            return response()->json($data);
        }
        else{
            $data['courses'] = Course::where('private_mode', '!=', 1)->with('course_instructors.user.instructor.instructor_courses')->active()->paginate($this->coursePaginateValue);
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
        $data['total_courses'] = Course::where('private_mode', '!=', 1)->active()->count();
        $data['difficulty_levels'] = Difficulty_level::all();
        $data['highest_price'] = Course::where('private_mode', '!=', 1)->max('price');

        $data['random_four_categories'] = Category::active()->inRandomOrder()->limit(4)->get();
        return view('frontend.course.course-list', $data);
    }

    public function searchCourseList(Request $request)
    {
        if (!get_option('private_mode') || Auth::user()) {
            $data['courses'] = Course::where('private_mode', '!=', 1)->active()->where('title', 'like', '%' . $request->title . '%')->get();
        } else {
            $data['courses'] = [];
        }

        return view('frontend.render-search-course-list', $data);
    }

    public function courseDetails($slug)
    {
        $data['pageTitle'] = "Course Details";
        $data['metaData'] = staticMeta(3);
        $data['course'] = Course::whereSlug($slug)->first();
        $affilate_request = AffiliateRequest::where(['user_id' => Auth::id(), 'status' => STATUS_APPROVED])->first();
        if (!is_null($affilate_request)) {
            $ref_cod = $data['course']->id . '$' . $affilate_request->affiliate_code;
            $data['ref_link'] = route('course-details', $slug) . '?code=' . $ref_cod;
        } else {
            $data['ref_link'] = null;
        }

        if ($data['course']->status != STATUS_APPROVED && $data['course']->status != STATUS_UPCOMING_APPROVED){
            $this->showToastrMessage('error', __('This course is not active'));
            return redirect()->back();
        }

        $authUser = auth()->user();
        $relation = ($authUser) ? getUserRoleRelation($authUser) : NULL;
        
        if($data['course']->private_mode == 1 && (is_null($authUser) || ($authUser->$relation->organization_id != $data['course']->organization_id && $authUser->id != $data['course']->user_id))){
            abort(403);
        }

        //Start:: Course Review
        $data['reviews'] = Review::whereCourseId($data['course']->id)->latest()->paginate(3);
        $data['loadMoreShowButtonReviews'] = Review::whereCourseId($data['course']->id)->paginate(4);
        $data['five_star_count'] = Review::whereCourseId($data['course']->id)->whereRating(5)->count();
        $data['four_star_count'] = Review::whereCourseId($data['course']->id)->whereRating(4)->count();
        $data['three_star_count'] = Review::whereCourseId($data['course']->id)->whereRating(3)->count();
        $data['two_star_count'] = Review::whereCourseId($data['course']->id)->whereRating(2)->count();
        $data['first_star_count'] = Review::whereCourseId($data['course']->id)->whereRating(1)->count();

        $data['total_reviews'] = (5 * $data['five_star_count']) + (4 * $data['four_star_count']) + (3 * $data['three_star_count']) +
            (2 * $data['two_star_count']) + (1 * $data['first_star_count']);
        $data['total_user_review'] = $data['five_star_count'] + $data['four_star_count'] + $data['three_star_count'] + $data['two_star_count'] + $data['first_star_count'];
        if ($data['total_user_review'] > 0) {
            $data['average_rating'] = $data['total_reviews']  / $data['total_user_review'];
        } else {
            $data['average_rating'] = 0;
        }

        $total_reviews = Review::whereCourseId($data['course']->id)->count();
        if ($data['total_reviews'] > 0) {
            $data['five_star_percentage'] =  100 * ($data['five_star_count'] / $total_reviews);
            $data['four_star_percentage'] =  100 * ($data['four_star_count'] / $total_reviews);
            $data['three_star_percentage'] = 100 * ($data['three_star_count'] / $total_reviews);
            $data['two_star_percentage'] = 100 * ($data['two_star_count'] / $total_reviews);
            $data['first_star_percentage'] = 100 * ($data['first_star_count'] / $total_reviews);
        } else {
            $data['five_star_percentage'] =  0;
            $data['four_star_percentage'] =  0;
            $data['three_star_percentage'] = 0;
            $data['two_star_percentage'] = 0;
            $data['first_star_percentage'] = 0;
        }
        //End:: Course Review

        $data['discussions'] = Discussion::whereCourseId($data['course']->id)->whereNull('parent_id')->active()->get();

        // Start:: Check student enrolled or cart list or nothing
        $user = Auth::user();
        $data['course_exits'] = 0;
        if ($user) {

            $courseIds = Enrollment::where(['user_id' => $user->id, 'course_id' => $data['course']->id, 'status' => ACCESS_PERIOD_ACTIVE])->whereDate('end_date', '>=', now())->pluck('course_id')->count();
            if ($courseIds) {
                $data['course_exits'] = 'enrolled';
            }
            // End:: Checking enrolled or not

            // Start:: Checking Cart list or not
            $cartExists = CartManagement::whereUserId($user->id)->whereCourseId($data['course']->id)->first();
            if ($cartExists) {
                $data['course_exits'] = 'cartList';
            }
            // End:: Checking Cart list or not
        }
        // End:: Check student enrolled or cart list or nothing

        //Start:: Instructor rating
        $data['instructor_average_rating'] = getUserAverageRating($data['course']->user_id);
        //End:: Instructor rating

        //Start:: Instructor students
        $instructorCourseIds = Course::where('private_mode', '!=', 1)->where('user_id', $data['course']->user_id)->pluck('id')->toArray();

        $data['total_instructor_students'] = Enrollment::whereIn('course_id', $instructorCourseIds)->count();
        //End:: Instructor students

        //Start:: Course students

        $data['total_course_students'] = Enrollment::where('course_id', $data['course']->id)->count();
        //End:: Course students

        return view('frontend.course.course-details', $data);
    }

    public function categoryCourses($slug)
    {
        $data['pageTitle'] = "Courses";
        $data['metaData'] = staticMeta(4);
        $data['categories'] = Category::with('subcategories')->active()->get();
        $data['category'] = Category::whereSlug($slug)->firstOrFail();
        $data['courses'] = Course::where('private_mode', '!=', 1)->whereCategoryId($data['category']->id)->active()->paginate($this->coursePaginateValue);
        $data['topCourse'] = Enrollment::query()
                ->whereMonth('created_at', now()->month)
                ->select('course_id', DB::raw('count(*) as total'))
                ->groupBy('course_id')
                ->limit(10)
                ->orderBy('total','desc')
                ->get()
                ->pluck('course_id')
                ->toArray();
        $data['total_courses'] = Course::where('private_mode', '!=', 1)->whereCategoryId($data['category']->id)->active()->count();
        $data['difficulty_levels'] = Difficulty_level::all();
        $data['highest_price'] = Course::where('private_mode', '!=', 1)->max('price');
        $data['random_four_categories'] = Category::active()->inRandomOrder()->limit(4)->get();
        return view('frontend.course.category.category-course-list', $data);
    }

    public function subCategoryCourses($slug)
    {
        $data['pageTitle'] = "Subcategory Courses";
        $data['metaData'] = staticMeta(4);
        $data['categories'] = Category::with('subcategories')->active()->get();
        $data['subcategory'] = Subcategory::whereSlug($slug)->firstOrFail();
        $data['courses'] = Course::where('private_mode', '!=', 1)->whereSubcategoryId($data['subcategory']->id)->active()->paginate($this->coursePaginateValue);
        $data['topCourse'] = Enrollment::query()
                ->whereMonth('created_at', now()->month)
                ->select('course_id', DB::raw('count(*) as total'))
                ->groupBy('course_id')
                ->limit(10)
                ->orderBy('total','desc')
                ->get()
                ->pluck('course_id')
                ->toArray();
        $data['total_courses'] = Course::where('private_mode', '!=', 1)->whereSubcategoryId($data['subcategory']->id)->active()->count();
        $data['difficulty_levels'] = Difficulty_level::all();
        $data['highest_price'] = Course::where('private_mode', '!=', 1)->max('price');
        $data['random_four_categories'] = Category::active()->inRandomOrder()->limit(4)->get();
        return view('frontend.course.category.subcategory-course-list', $data);
    }

    public function getFilterCourse(Request $request)
    {
        $data['courses'] = $this->filterCourseData($request);
        $data['topCourse'] = Enrollment::query()
        ->whereMonth('created_at', now()->month)
        ->select('course_id', DB::raw('count(*) as total'))
        ->groupBy('course_id')
        ->limit(10)
        ->orderBy('total','desc')
        ->get()
        ->pluck('course_id')
        ->toArray();
        $data['courses'] = $data['courses']->paginate($this->coursePaginateValue);
        return view('frontend.course.render-course-list')->with($data);
    }

    public function paginationFetchData(Request $request)
    {
        $data['courses'] = $this->filterCourseData($request);
        if ($request->ajax()) {
            $data['courses'] = $data['courses']->paginate($this->coursePaginateValue);
            return view('frontend.course.render-course-list')->with($data);
        }
    }

    public function filterCourseData($request)
    {
        if ($request->category_id) {
            $data['courses'] = Course::where('private_mode', '!=', 1)->whereCategoryId($request->category_id)->active();
        } elseif ($request->sub_category_id) {
            $data['courses'] = Course::where('private_mode', '!=', 1)->whereSubcategoryId($request->sub_category_id)->active();
        } else {
            $data['courses'] = Course::where('private_mode', '!=', 1)->active();
        }

        $subCategoryIds = $request->subCategoryIds ?? [];
        $difficultyLevelIds = $request->difficultyLevelIds ?? [];
        $ratingIds = $request->ratingIds ?? [];
        $min_price = (int)$request->min_price;
        $max_price = (int)$request->max_price;
        $learnerAccessibilityTypes = $request->learnerAccessibilityTypes  ?? [];
        $durationIds = $request->durationIds ?? [];
        $sortBy_id = $request->sortBy_id;

        $data['courses'] = $data['courses']->where(function ($q) use ($subCategoryIds, $difficultyLevelIds, $ratingIds, $min_price, $max_price, $learnerAccessibilityTypes) {

            if (!empty($subCategoryIds)) {
                $q->whereIn('subcategory_id', $subCategoryIds);
            }

            if (!empty($difficultyLevelIds)) {
                $q->whereIn('difficulty_level_id', $difficultyLevelIds);
            }
            if ($ratingIds) {
                foreach ($ratingIds as $rating) {
                    $q->where('average_rating', '>=', $rating);
                }
            }

            if ($min_price && $max_price) {
                $q->whereBetween('price', [$min_price, $max_price]);
            } else if ($max_price) {
                $q->whereBetween('price', [0, $max_price]);
            }

            if (!empty($learnerAccessibilityTypes)) {
                $q->whereIn('learner_accessibility', $learnerAccessibilityTypes);
            }
        });

        /*
         * duration_id = 1 // less than 24 hours
         * duration_id = 2 // 24 to 36 hours
         * duration_id = 3 // 36 to 72 hours
         * duration_id = 4 // above 72 hours
         */

        if ($durationIds) {
            $courses = $data['courses']->get();
            $courseIds = collect([]);
            foreach ($courses as $course) {
                $duration = $course->filter_video_duration;
                foreach ($durationIds as $durationId) {
                    if ($durationId == 1) {
                        if ($duration <= 24) {
                            $courseIds->push($course->id);
                        }
                    } elseif ($durationId == 2) {
                        if ($duration >= 24 && $duration <= 36) {
                            $courseIds->push($course->id);
                        }
                    } elseif ($durationId == 3) {
                        if ($duration >= 36 && $duration <= 72) {
                            $courseIds->push($course->id);
                        }
                    } elseif ($durationId == 4) {
                        if ($duration >= 72) {
                            $courseIds->push($course->id);
                        }
                    }
                }
            }

            $data['courses'] = $data['courses']->whereIn('id', $courseIds);
        }

        if ($sortBy_id) {
            if ($sortBy_id == 2) {
                $data['courses'] = $data['courses']->orderBy('id', 'DESC');
            }
            if ($sortBy_id == 3) {
                $data['courses'] = $data['courses']->orderBy('id', 'ASC');
            }
        }

        return $data['courses'];
    }

    public function reviewPaginate(Request $request, $courseId)
    {
        $data['reviews'] = Review::whereCourseId($courseId)->latest()->paginate(3);
        $response['appendReviews'] = View::make('frontend.course.partial.render-partial-review-list', $data)->render();
        $response['reviews'] = Review::whereCourseId($courseId)->latest()->paginate(3);
        return response()->json($response);
    }
}
