<?php

namespace App\Http\Controllers\Api\Frontend;

use App\Http\Controllers\Controller;
use App\Models\AffiliateRequest;
use App\Models\Bundle;
use App\Models\CartManagement;
use App\Models\Category;
use App\Models\Course;
use App\Models\Difficulty_level;
use App\Models\Discussion;
use App\Models\Enrollment;
use App\Models\Review;
use App\Models\Subcategory;
use App\Models\User;
use App\Traits\ApiStatusTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;

class CourseController extends Controller
{
    use ApiStatusTrait;

    public $coursePaginateValue = 9;

    public function allCourses(Request $request)
    {
        $data['metaData'] = staticMeta(2);
        $data['categories'] = Category::with('subcategories')->active()->get();

        $data['topCourse'] = Enrollment::query()
            ->whereMonth('created_at', now()->month)
            ->whereNotNull('course_id')
            ->select('course_id', DB::raw('count(*) as total'))
            ->groupBy('course_id')
            ->limit(10)
            ->orderBy('total', 'desc')
            ->get()
            ->pluck('course_id')
            ->toArray();
        $data['total_courses'] = Course::where('private_mode', '!=', 1)->active()->count();
        $data['difficulty_levels'] = Difficulty_level::all();
        $data['highest_price'] = Course::where('private_mode', '!=', 1)->max('price');

        $data['random_four_categories'] = Category::active()->inRandomOrder()->limit(4)->get();
        return $this->success($data);
    }

    public function getCourse(Request $request)
    {

        $lastPage = false;
        $courses = $this->filterCourseData($request);

        $data['topCourse'] = Enrollment::query()
            ->whereMonth('created_at', now()->month)
            ->whereNotNull('course_id')
            ->select('course_id', DB::raw('count(*) as total'))
            ->groupBy('course_id')
            ->limit(10)
            ->orderBy('total', 'desc')
            ->get()
            ->pluck('course_id')
            ->toArray();

        $courses = $courses->with('user:id,name,email,role,image');
        $courses = $courses->with('instructor:id,uuid,first_name,last_name,professional_title,hourly_rate,consultation_available');
        $courses = $courses->paginate(10);

        foreach ($courses as $course) {
            $startDate = date('d-m-Y H:i:s', strtotime(@$course->promotionCourse->promotion->start_date));
            $endDate = date('d-m-Y H:i:s', strtotime(@$course->promotionCourse->promotion->end_date));
            $percentage = @$course->promotionCourse->promotion->percentage;
            $discount_price = number_format($course->price - (($course->price * $percentage) / 100), 2);

            if (now()->gt($startDate) && now()->lt($endDate)) {
                $filterData['discount_price'] = $discount_price;
                $filterData['price'] = $course->price;
            } elseif ($course->price <= $course->old_price) {
                $filterData['price'] = $course->old_price;
                $filterData['discount_price'] = $course->price;
            } else {
                $filterData['price'] = $course->price;
                $filterData['discount_price'] = $course->price;
            }

            if ($course->learner_accessibility != 'free' && get_option('cashback_system_mode', 0)) {
                $filterData['cashback'] = calculateCashback($course->price);
            }

            $userRelation = getUserRoleRelation($course->user);

            $filterData['title'] = $course->title;
            $filterData['average_rating'] = $course->average_rating;
            $filterData['slug'] = $course->slug;
            $filterData['id'] = $course->id;
            $filterData['created_at'] = $course->created_at;
            $filterData['image_url'] = $course->image_url;
            $filterData['learner_accessibility'] = $course->learner_accessibility;
            $filterData['total_review'] = $course->reviews->count();
            $filterData['author'] = $course->$userRelation->name;
            $filterData['author_user_id'] = $course->user_id;
            $awards = '';
            foreach ($course->$userRelation->awards as $award) {
                $awards .= ' | ' . $award->name;
            }

            $filterData['author_awards'] = $awards;

            $data['courses'][] = $filterData;
        }

        if (!count($courses)) {
            $data['courses'] = [];
        }


        if ($courses->lastPage() == $request->page) {
            $lastPage = true;
        }

        $data['per_page'] = 10;
        $data['current_page'] = $request->page ?? 1;
        $data['lastPage'] = $lastPage;
        $data['status'] = true;

        return $this->success($data);
    }

    public function getBundleCourseList(Request $request)
    {

        $lastPage = false;
        $bundles = Bundle::withCount('bundleCourses')->with('instructor:uuid,first_name,last_name,professional_title,hourly_rate,consultation_available')->with('organization:uuid,first_name,last_name,professional_title,hourly_rate,consultation_available')->active()->latest()->paginate(10);

        if ($bundles->lastPage() == $request->page) {
            $lastPage = true;
        }

        $data['bundles'] = $bundles->items();
        $data['per_page'] = 10;
        $data['current_page'] = $request->page ?? 1;
        $data['lastPage'] = $lastPage;
        $data['status'] = true;

        return $this->success($data);
    }

    public function getUpcomingCourseList(Request $request)
    {

        $lastPage = false;

        $data['topCourse'] = Enrollment::query()
            ->whereMonth('created_at', now()->month)
            ->whereNotNull('course_id')
            ->select('course_id', DB::raw('count(*) as total'))
            ->groupBy('course_id')
            ->limit(10)
            ->orderBy('total', 'desc')
            ->get()
            ->pluck('course_id')
            ->toArray();

        $courses = Course::with('reviews')->with('user')->with('promotionCourse.promotion')->with('instructor.ranking_level')->with('specialPromotionTagCourse.specialPromotionTag')->upcoming()->paginate(10);
        foreach ($courses as $course) {
            $startDate = date('d-m-Y H:i:s', strtotime(@$course->promotionCourse->promotion->start_date));
            $endDate = date('d-m-Y H:i:s', strtotime(@$course->promotionCourse->promotion->end_date));
            $percentage = @$course->promotionCourse->promotion->percentage;
            $discount_price = number_format($course->price - (($course->price * $percentage) / 100), 2);

            if (now()->gt($startDate) && now()->lt($endDate)) {
                $filterData['discount_price'] = $discount_price;
                $filterData['price'] = $course->price;
            } elseif ($course->price <= $course->old_price) {
                $filterData['price'] = $course->old_price;
                $filterData['discount_price'] = $course->price;
            } else {
                $filterData['price'] = $course->price;
                $filterData['discount_price'] = $course->price;
            }

            if ($course->learner_accessibility != 'free' && get_option('cashback_system_mode', 0)) {
                $filterData['cashback'] = calculateCashback($course->price);
            }

            $userRelation = getUserRoleRelation($course->user);

            $filterData['title'] = $course->title;
            $filterData['slug'] = $course->slug;
            $filterData['id'] = $course->id;
            $filterData['created_at'] = $course->created_at;
            $filterData['image_url'] = $course->image_url;
            $filterData['learner_accessibility'] = $course->learner_accessibility;
            $filterData['author'] = $course->$userRelation->name;
            $filterData['author_user_id'] = $course->user_id;
            $awards = '';
            foreach ($course->$userRelation->awards as $award) {
                $awards .= ' | ' . $award->name;
            }

            $filterData['author_awards'] = $awards;

            $data['courses'][] = $filterData;
        }

        if (!count($courses)) {
            $data['courses'] = [];
        }

        if ($courses->lastPage() == $request->page) {
            $lastPage = true;
        }

        $data['per_page'] = 10;
        $data['current_page'] = $request->page ?? 1;
        $data['lastPage'] = $lastPage;
        $data['status'] = true;

        return $this->success($data);
    }

    public function getInstructorList(Request $request)
    {

        $lastPage = false;

        $instructors = User::query()
            ->leftJoin('instructors as ins', 'ins.user_id', '=', 'users.id')
            ->leftJoin('organizations as org', 'org.user_id', '=', 'users.id')
            ->whereIn('users.role', [USER_ROLE_INSTRUCTOR, USER_ROLE_ORGANIZATION])
            ->where(function ($q) {
                $q->where('ins.status', STATUS_APPROVED)
                    ->orWhere('org.status', STATUS_APPROVED);
            })
            ->with('badges:name,type,from,to,description')
            ->select('users.*', 'ins.organization_id', DB::raw(selectStatement()))
            ->paginate(10);

        $data = [];
        foreach ($instructors as $instructor) {
            $data['instructors'][] = [
                "id" => $instructor->id,
                "name" => $instructor->name,
                "email" => $instructor->email,
                "image_url" => $instructor->image_url,
                "area_code" => $instructor->area_code,
                "mobile_number" => $instructor->mobile_number,
                "email_verified_at" => $instructor->email_verified_at,
                "role" => $instructor->role,
                "phone_number" => $instructor->phone_number,
                "is_affiliator" => $instructor->is_affiliator,
                "organization_id" => $instructor->organization_id,
                "uuid" => $instructor->uuid,
                "professional_title" => $instructor->professional_title,
                "postal_code" => $instructor->postal_code,
                "about_me" => $instructor->about_me,
                "gender" => $instructor->gender,
                "social_link" => $instructor->social_link,
                "slug" => $instructor->slug,
                "remove_from_web_search" => $instructor->remove_from_web_search,
                "is_offline" => $instructor->is_offline,
                "offline_message" => $instructor->offline_message,
                "consultation_available" => $instructor->consultation_available,
                "hourly_rate" => $instructor->hourly_rate,
                "hourly_old_rate" => $instructor->hourly_old_rate,
                "available_type" => $instructor->available_type,
                "approval_date" => $instructor->approval_date,
                'average_rating' => $instructor->courses->where('average_rating', '>', 0)->avg('average_rating') ?? 0,
                'total_rating' => count($instructor->courses->where('average_rating', '>', 0)),
                'badges' => $instructor->badges,
            ];
        }

        if ($instructors->lastPage() == $request->page) {
            $lastPage = true;
        }

        if (!count($instructors)) {
            $data['instructors'] = [];
        }

        $data['per_page'] = 10;
        $data['current_page'] = $request->page ?? 1;
        $data['lastPage'] = $lastPage;
        $data['status'] = true;

        return $this->success($data);
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
        $learnerAccessibilityTypes = $request->learnerAccessibilityTypes ?? [];
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


    public function searchCourseList(Request $request)
    {
        if (!get_option('private_mode') || Auth::user()) {
            $data = Course::where('private_mode', '!=', 1)->active()->where('title', 'like', '%' . $request->title . '%')->select('title', 'slug', 'id', 'image')->get();
        } else {
            $data = [];
        }

        return $this->success($data);
    }

    public function courseDetails($slug, $type = NULL)
    {
        if ($type == NULL) {
            $course = Course::whereSlug($slug)->first();

            $startDate = date('d-m-Y H:i:s', strtotime(@$course->promotionCourse->promotion->start_date));
            $endDate = date('d-m-Y H:i:s', strtotime(@$course->promotionCourse->promotion->end_date));
            $percentage = @$course->promotionCourse->promotion->percentage;
            $discount_price = number_format($course->price - (($course->price * $percentage) / 100), 2);

            if (now()->gt($startDate) && now()->lt($endDate)) {
                $filterData['discount_price'] = $discount_price;
                $filterData['price'] = $course->price;
            } elseif ($course->price <= $course->old_price) {
                $filterData['price'] = $course->old_price;
                $filterData['discount_price'] = $course->price;
            } else {
                $filterData['price'] = $course->price;
                $filterData['discount_price'] = $course->price;
            }

            if ($course->learner_accessibility != 'free' && get_option('cashback_system_mode', 0)) {
                $filterData['cashback'] = calculateCashback($course->price);
            }

            $userRelation = getUserRoleRelation($course->user);

            $filterData['uuid'] = $course->uuid;
            $filterData['user_id'] = $course->user_id;
            $filterData['course_type'] = $course->course_type;
            $filterData['instructor_id'] = $course->instructor_id;
            $filterData['category_id'] = $course->category_id;
            $filterData['subcategory_id'] = $course->subcategory_id;
            $filterData['course_language_id'] = $course->course_language_id;
            $filterData['difficulty_level_id'] = $course->difficulty_level_id;
            $filterData['subtitle'] = $course->subtitle;
            $filterData['description'] = $course->description;
            $filterData['feature_details'] = $course->feature_details;
            $filterData['learner_accessibility'] = $course->learner_accessibility;
            $filterData['image'] = $course->image;
            $filterData['intro_video_check'] = $course->intro_video_check;
            $filterData['video'] = $course->video;
            $filterData['youtube_video_id'] = $course->youtube_video_id;
            $filterData['is_subscription_enable'] = $course->is_subscription_enable;
            $filterData['private_mode'] = $course->private_mode;
            $filterData['slug'] = $course->slug;
            $filterData['is_featured'] = $course->is_featured;
            $filterData['status'] = $course->status;
            $filterData['drip_content'] = $course->drip_content;
            $filterData['access_period'] = $course->access_period;
            $filterData['meta_title'] = $course->meta_title;
            $filterData['meta_description'] = $course->meta_description;
            $filterData['meta_keywords'] = $course->meta_keywords;
            $filterData['og_image'] = $course->og_image;
            $filterData['created_at'] = $course->created_at;
            $filterData['updated_at'] = $course->updated_at;
            $filterData['organization_id'] = $course->organization_id;
            $filterData['key_points'] = $course->key_points;
            $filterData['title'] = $course->title;
            $filterData['average_rating'] = $course->average_rating;
            $filterData['id'] = $course->id;
            $filterData['image_url'] = $course->image_url;
            $filterData['video_url'] = $course->video_url;
            $filterData['learner_accessibility'] = $course->learner_accessibility;
            $filterData['total_review'] = $course->reviews->count();
            $filterData['author'] = $course->$userRelation->name;
            $filterData['author_user_id'] = $course->user_id;
            $awards = '';
            foreach ($course->$userRelation->awards as $award) {
                $awards .= ' | ' . $award->name;
            }

            $filterData['author_awards'] = $awards;

            $data = $filterData;


            if ($course->status != 1) {
                return $this->error([], __('This course is not active'));
            }

            $data['key_points'] = $course->keyPoints;

            return $this->success($data);
        } elseif ($type == 'curriculum') {
            $course = Course::whereSlug($slug)->with('lessons.lectures')->first();

            if ($course->status != 1) {
                return $this->error([], __('This course is not active'));
            }

            $data = $course->lessons;

            return $this->success($data);
        } elseif ($type == 'discussion') {
            $course = Course::whereSlug($slug)->first();
            $data = Discussion::whereCourseId($course->id)->whereNull('parent_id')->with('user')->with('replies.user')->active()->get();

            if ($course->status != 1) {
                return $this->error([], __('This course is not active'));
            }

            return $this->success($data);
        } elseif ($type == 'review') {
            $course = Course::whereSlug($slug)->first();
            $data['reviews'] = Review::whereCourseId($course->id)->latest()->paginate(10);
            $data['five_star_count'] = Review::whereCourseId($course->id)->whereRating(5)->count();
            $data['four_star_count'] = Review::whereCourseId($course->id)->whereRating(4)->count();
            $data['three_star_count'] = Review::whereCourseId($course->id)->whereRating(3)->count();
            $data['two_star_count'] = Review::whereCourseId($course->id)->whereRating(2)->count();
            $data['first_star_count'] = Review::whereCourseId($course->id)->whereRating(1)->count();

            $data['total_reviews'] = (5 * $data['five_star_count']) + (4 * $data['four_star_count']) + (3 * $data['three_star_count']) +
                (2 * $data['two_star_count']) + (1 * $data['first_star_count']);
            $data['total_user_review'] = $data['five_star_count'] + $data['four_star_count'] + $data['three_star_count'] + $data['two_star_count'] + $data['first_star_count'];
            if ($data['total_user_review'] > 0) {
                $data['average_rating'] = $data['total_reviews'] / $data['total_user_review'];
            } else {
                $data['average_rating'] = 0;
            }

            $total_reviews = Review::whereCourseId($course->id)->count();
            if ($data['total_reviews'] > 0) {
                $data['five_star_percentage'] = 100 * ($data['five_star_count'] / $total_reviews);
                $data['four_star_percentage'] = 100 * ($data['four_star_count'] / $total_reviews);
                $data['three_star_percentage'] = 100 * ($data['three_star_count'] / $total_reviews);
                $data['two_star_percentage'] = 100 * ($data['two_star_count'] / $total_reviews);
                $data['first_star_percentage'] = 100 * ($data['first_star_count'] / $total_reviews);
            } else {
                $data['five_star_percentage'] = 0;
                $data['four_star_percentage'] = 0;
                $data['three_star_percentage'] = 0;
                $data['two_star_percentage'] = 0;
                $data['first_star_percentage'] = 0;
            }
            if ($course->status != 1) {
                return $this->error([], __('This course is not active'));
            }

            return $this->success($data);
        } elseif ($type == 'instructor') {
            $course = Course::whereSlug($slug)->first();
            $instructorCourseIds = Course::where('private_mode', '!=', 1)->where('user_id', $course->user_id)->pluck('id')->toArray();

            $instructor = User::query()
                ->leftJoin('instructors as ins', 'ins.user_id', '=', 'users.id')
                ->leftJoin('organizations as org', 'org.user_id', '=', 'users.id')
                ->whereIn('users.role', [USER_ROLE_INSTRUCTOR, USER_ROLE_ORGANIZATION])
                ->where(function ($q) {
                    $q->where('ins.status', STATUS_APPROVED)
                        ->orWhere('org.status', STATUS_APPROVED);
                })
                ->with('badges:name,type,from,to,description')
                ->select('users.*', 'ins.organization_id', DB::raw(selectStatement()))
                ->where('users.id', $course->user_id)
                ->first();


            $data = [
                "id" => $instructor->id,
                "name" => $instructor->name,
                'image_url' => $instructor->image_url,
                "email" => $instructor->email,
                "area_code" => $instructor->area_code,
                "mobile_number" => $instructor->mobile_number,
                "email_verified_at" => $instructor->email_verified_at,
                "role" => $instructor->role,
                "phone_number" => $instructor->phone_number,
                "is_affiliator" => $instructor->is_affiliator,
                "organization_id" => $instructor->organization_id,
                "uuid" => $instructor->uuid,
                "professional_title" => $instructor->professional_title,
                "postal_code" => $instructor->postal_code,
                "about_me" => $instructor->about_me,
                "gender" => $instructor->gender,
                "social_link" => $instructor->social_link,
                "slug" => $instructor->slug,
                "remove_from_web_search" => $instructor->remove_from_web_search,
                "is_offline" => $instructor->is_offline,
                "offline_message" => $instructor->offline_message,
                "consultation_available" => $instructor->consultation_available,
                "hourly_rate" => $instructor->hourly_rate,
                "hourly_old_rate" => $instructor->hourly_old_rate,
                "available_type" => $instructor->available_type,
                "approval_date" => $instructor->approval_date,
                'average_rating' => $instructor->courses->where('average_rating', '>', 0)->avg('average_rating') ?? 0,
                'total_rating' => count($instructor->courses->where('average_rating', '>', 0)),
                'total_course_students' => Enrollment::where('course_id', $course->id)->count(),
                'total_instructor_students' => Enrollment::whereIn('course_id', $instructorCourseIds)->count(),
                'total_instructor_course' => $instructor->courses->count(),
                'badges' => $instructor->badges,
            ];

            if ($course->status != 1) {
                return $this->error([], __('This course is not active'));
            }

            return $this->success($data);
        } else {
            return $this->error([], __('No Data Found'));
        }
    }

    public function bundleDetails($slug)
    {
        $bundle = Bundle::query()
            ->leftJoin('users', 'bundles.user_id', '=', 'users.id')
            ->leftJoin('organizations as org', 'org.user_id', '=', 'users.id')
            ->leftJoin('instructors as ins', 'ins.user_id', '=', 'users.id')
            ->with('bundleCourses.course.user:id,name,email,role,image')
            ->with('bundleCourses.course.reviews')
            ->with('bundleCourses.course.instructor:id,uuid,first_name,last_name,professional_title,hourly_rate,consultation_available')
            ->where('bundles.slug', $slug)
            ->select('bundles.*', 'ins.organization_id', DB::raw(selectStatement()))
            ->first();

        if (is_null($bundle)) {
            return $this->error([], __('No Data Found'));
        }

        if ($bundle->status != 1) {
            return $this->error([], __('This bundle is not active'));
        }

        $instructorCourseIds = Course::where('private_mode', '!=', 1)->where('user_id', $bundle->user_id)->pluck('id')->toArray();

        $instructor = User::query()
            ->leftJoin('instructors as ins', 'ins.user_id', '=', 'users.id')
            ->leftJoin('organizations as org', 'org.user_id', '=', 'users.id')
            ->whereIn('users.role', [USER_ROLE_INSTRUCTOR, USER_ROLE_ORGANIZATION])
            ->where(function ($q) {
                $q->where('ins.status', STATUS_APPROVED)
                    ->orWhere('org.status', STATUS_APPROVED);
            })
            ->with('badges:name,type,from,to,description')
            ->select('users.*', 'ins.organization_id', DB::raw(selectStatement()))
            ->where('users.id', $bundle->user_id)
            ->first();


        $instructorData = [
            "id" => $instructor->id,
            "name" => $instructor->name,
            'image_url' => $instructor->image_url,
            "email" => $instructor->email,
            "area_code" => $instructor->area_code,
            "mobile_number" => $instructor->mobile_number,
            "email_verified_at" => $instructor->email_verified_at,
            "role" => $instructor->role,
            "phone_number" => $instructor->phone_number,
            "is_affiliator" => $instructor->is_affiliator,
            "organization_id" => $instructor->organization_id,
            "uuid" => $instructor->uuid,
            "professional_title" => $instructor->professional_title,
            "postal_code" => $instructor->postal_code,
            "about_me" => $instructor->about_me,
            "gender" => $instructor->gender,
            "social_link" => $instructor->social_link,
            "slug" => $instructor->slug,
            "remove_from_web_search" => $instructor->remove_from_web_search,
            "is_offline" => $instructor->is_offline,
            "offline_message" => $instructor->offline_message,
            "consultation_available" => $instructor->consultation_available,
            "hourly_rate" => $instructor->hourly_rate,
            "hourly_old_rate" => $instructor->hourly_old_rate,
            "available_type" => $instructor->available_type,
            "approval_date" => $instructor->approval_date,
            'average_rating' => $instructor->courses->where('average_rating', '>', 0)->avg('average_rating') ?? 0,
            'total_rating' => count($instructor->courses->where('average_rating', '>', 0)),
            'total_instructor_students' => Enrollment::whereIn('course_id', $instructorCourseIds)->count(),
            'total_instructor_course' => $instructor->courses->count(),
            'badges' => $instructor->badges,
        ];

        $data = [
            'uuid' => $bundle->uuid,
            'user_id' => $bundle->user_id,
            'name' => $bundle->name,
            'slug' => $bundle->slug,
            'image_url' => $bundle->image_url,
            'overview' => $bundle->overview,
            'price' => $bundle->price,
            'status' => $bundle->status,
            'instructor' => $instructorData,
        ];

        foreach ($bundle->bundleCourses as $bundleCourse) {
            $course = $bundleCourse->course;
            $startDate = date('d-m-Y H:i:s', strtotime(@$course->promotionCourse->promotion->start_date));
            $endDate = date('d-m-Y H:i:s', strtotime(@$course->promotionCourse->promotion->end_date));
            $percentage = @$course->promotionCourse->promotion->percentage;
            $discount_price = number_format($course->price - (($course->price * $percentage) / 100), 2);

            if (now()->gt($startDate) && now()->lt($endDate)) {
                $filterData['discount_price'] = $discount_price;
                $filterData['price'] = $course->price;
            } elseif ($course->price <= $course->old_price) {
                $filterData['price'] = $course->old_price;
                $filterData['discount_price'] = $course->price;
            } else {
                $filterData['price'] = $course->price;
                $filterData['discount_price'] = $course->price;
            }

            if ($course->learner_accessibility != 'free' && get_option('cashback_system_mode', 0)) {
                $filterData['cashback'] = calculateCashback($course->price);
            }

            $userRelation = getUserRoleRelation($course->user);

            $filterData['title'] = $course->title;
            $filterData['slug'] = $course->slug;
            $filterData['id'] = $course->id;
            $filterData['created_at'] = $course->created_at;
            $filterData['image_url'] = $course->image_url;
            $filterData['learner_accessibility'] = $course->learner_accessibility;
            $filterData['author'] = $course->$userRelation->name;
            $filterData['author_user_id'] = $course->user_id;
            $filterData['average_rating'] = $course->average_rating;
            $filterData['total_review'] = $course->reviews->count();
            $awards = '';
            foreach ($course->$userRelation->awards as $award) {
                $awards .= ' | ' . $award->name;
            }

            $filterData['author_awards'] = $awards;

            $data['courses'][] = $filterData;
        }

        if (!count($bundle->bundleCourses)) {
            $data['courses'] = [];
        }

        return $this->success($data);
    }


    public function categoryCourses($slug)
    {
        $data['metaData'] = staticMeta(4);
        $data['categories'] = Category::with('subcategories')->active()->get();
        $data['category'] = Category::whereSlug($slug)->firstOrFail();
        $data['courses'] = Course::where('private_mode', '!=', 1)->whereCategoryId($data['category']->id)->active()->paginate($this->coursePaginateValue);
        $data['topCourse'] = Enrollment::query()
            ->whereMonth('created_at', now()->month)
            ->whereNotNull('course_id')
            ->select('course_id', DB::raw('count(*) as total'))
            ->groupBy('course_id')
            ->limit(10)
            ->orderBy('total', 'desc')
            ->get()
            ->pluck('course_id')
            ->toArray();
        $data['total_courses'] = Course::where('private_mode', '!=', 1)->whereCategoryId($data['category']->id)->active()->count();
        $data['difficulty_levels'] = Difficulty_level::all();
        $data['highest_price'] = Course::where('private_mode', '!=', 1)->max('price');
        $data['random_four_categories'] = Category::active()->inRandomOrder()->limit(4)->get();

        return $this->success($data);
    }

    public function subCategoryCourses($slug)
    {
        $data['metaData'] = staticMeta(4);
        $data['categories'] = Category::with('subcategories')->active()->get();
        $data['subcategory'] = Subcategory::whereSlug($slug)->firstOrFail();
        $data['courses'] = Course::where('private_mode', '!=', 1)->whereSubcategoryId($data['subcategory']->id)->active()->paginate($this->coursePaginateValue);
        $data['topCourse'] = Enrollment::query()
            ->whereMonth('created_at', now()->month)
            ->whereNotNull('course_id')
            ->select('course_id', DB::raw('count(*) as total'))
            ->groupBy('course_id')
            ->limit(10)
            ->orderBy('total', 'desc')
            ->get()
            ->pluck('course_id')
            ->toArray();
        $data['total_courses'] = Course::where('private_mode', '!=', 1)->whereSubcategoryId($data['subcategory']->id)->active()->count();
        $data['difficulty_levels'] = Difficulty_level::all();
        $data['highest_price'] = Course::where('private_mode', '!=', 1)->max('price');
        $data['random_four_categories'] = Category::active()->inRandomOrder()->limit(4)->get();

        return $this->success($data);
    }

    public function getFilterCourse(Request $request)
    {
        $data['courses'] = $this->filterCourseData($request);
        $data['topCourse'] = Enrollment::query()
            ->whereMonth('created_at', now()->month)
            ->whereNotNull('course_id')
            ->select('course_id', DB::raw('count(*) as total'))
            ->groupBy('course_id')
            ->limit(10)
            ->orderBy('total', 'desc')
            ->get()
            ->pluck('course_id')
            ->toArray();
        $data['courses'] = $data['courses']->paginate($this->coursePaginateValue);
        return $this->success($data);
    }

    public function paginationFetchData(Request $request)
    {
        $data['courses'] = $this->filterCourseData($request)->paginate($this->coursePaginateValue);
        return $this->success($data);
    }
}
