<?php

namespace App\Http\Controllers\Api\Frontend;

use App\Http\Controllers\Controller;
use App\Models\AboutUsGeneral;
use App\Models\Assignment;
use App\Models\Bundle;
use App\Models\Category;
use App\Models\City;
use App\Models\ClientLogo;
use App\Models\Country;
use App\Models\Course;
use App\Models\Course_lecture;
use App\Models\Enrollment;
use App\Models\Exam;
use App\Models\FaqQuestion;
use App\Models\Home;
use App\Models\InstructorSupport;
use App\Models\Package;
use App\Models\Review;
use App\Models\State;
use App\Models\User;
use App\Models\UserPackage;
use App\Traits\ApiStatusTrait;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    use ApiStatusTrait;

    public function index()
    {
        $data['metaData'] = staticMeta(1);
        $data['aboutUsGeneral'] = AboutUsGeneral::first();
        $data['instructorSupports'] = InstructorSupport::all();
        $data['home'] = Home::first();
        $data['currencyPlacement'] = get_currency_placement();
        $data['currencySymbol'] = get_currency_symbol();
        return $this->success($data);
    }

    public function settings()
    {
        $setting = config('settings');
        $settings['app_logo'] =  asset($setting['app_logo'] ?? '');
        $settings['app_fav_icon'] = asset($setting['app_fav_icon'] ?? '');
        $settings['bundle_course_logo'] = asset($setting['bundle_course_logo'] ?? '');
        $settings['app_preloader'] = asset($setting['app_preloader'] ?? '');
        $settings['achievement_four_logo'] = asset($setting['achievement_four_logo'] ?? '');
        $settings['achievement_third_logo'] = asset($setting['achievement_third_logo'] ?? '');
        $settings['achievement_second_logo'] = asset($setting['achievement_second_logo'] ?? '');
        $settings['achievement_first_logo'] = asset($setting['achievement_first_logo'] ?? '');
        $settings['customer_say_logo'] = asset($setting['customer_say_logo'] ?? '');
        $settings['customer_say_logo'] = asset($setting['customer_say_logo'] ?? '');
        $settings['sign_up_left_image'] = asset($setting['sign_up_left_image'] ?? '');
        $settings['faq_image'] = asset($setting['faq_image'] ?? '');
        $settings['course_logo'] = asset($setting['course_logo'] ?? '');
        $settings['top_category_logo'] = asset($setting['top_category_logo'] ?? '');
        $settings['top_instructor_logo'] = asset($setting['top_instructor_logo'] ?? '');
        $settings['become_instructor_video'] = getVideoFile($setting['become_instructor_video'] ?? '');
        $settings['become_instructor_video_preview_image'] = asset($setting['become_instructor_video_preview_image'] ?? '');
        $settings['become_instructor_video_logo'] = asset($setting['become_instructor_video_logo'] ?? '');
        $settings['app_name'] = ($setting['app_name'] ?? '');
        $settings['app_email'] = ($setting['app_email'] ?? '');
        $settings['app_contact_number'] = ($setting['app_contact_number'] ?? '');
        $settings['app_location'] = ($setting['app_location'] ?? '');
        $settings['app_date_format'] = ($setting['app_date_format'] ?? '');
        $settings['app_copyright'] = ($setting['app_copyright'] ?? '');
        $settings['app_developed'] = ($setting['app_developed'] ?? '');
        $settings['footer_quote'] = ($setting['footer_quote'] ?? '');
        $settings['sign_up_left_text'] = ($setting['sign_up_left_text'] ?? '');
        $settings['sign_up_left_image'] = ($setting['sign_up_left_image'] ?? '');
        $settings['forgot_title'] = ($setting['forgot_title'] ?? '');
        $settings['forgot_subtitle'] = ($setting['forgot_subtitle'] ?? '');
        $settings['forgot_btn_name'] = ($setting['forgot_btn_name'] ?? '');
        $settings['facebook_url'] = ($setting['facebook_url'] ?? '');
        $settings['twitter_url'] = ($setting['twitter_url'] ?? '');
        $settings['linkedin_url'] = ($setting['linkedin_url'] ?? '');
        $settings['pinterest_url'] = ($setting['pinterest_url'] ?? '');
        $settings['app_instructor_footer_title'] = ($setting['app_instructor_footer_title'] ?? '');
        $settings['app_instructor_footer_subtitle'] = ($setting['app_instructor_footer_subtitle'] ?? '');
        $settings['get_in_touch_title'] = ($setting['get_in_touch_title'] ?? '');
        $settings['send_us_msg_title'] = ($setting['send_us_msg_title'] ?? '');
        $settings['contact_us_location'] = ($setting['contact_us_location'] ?? '');
        $settings['contact_us_email_one'] = ($setting['contact_us_email_one'] ?? '');
        $settings['contact_us_email_two'] = ($setting['contact_us_email_two'] ?? '');
        $settings['contact_us_phone_one'] = ($setting['contact_us_phone_one'] ?? '');
        $settings['contact_us_phone_two'] = ($setting['contact_us_phone_two'] ?? '');
        $settings['contact_us_map_link'] = ($setting['contact_us_map_link'] ?? '');
        $settings['contact_us_description'] = ($setting['contact_us_description'] ?? '');
        $settings['home_special_feature_first_logo'] = asset($setting['home_special_feature_first_logo'] ?? '');
        $settings['home_special_feature_first_title'] = ($setting['home_special_feature_first_title'] ?? '');
        $settings['home_special_feature_first_subtitle'] = ($setting['home_special_feature_first_subtitle'] ?? '');
        $settings['home_special_feature_second_logo'] = asset($setting['home_special_feature_second_logo'] ?? '');
        $settings['home_special_feature_second_title'] = ($setting['home_special_feature_second_title'] ?? '');
        $settings['home_special_feature_second_subtitle'] = ($setting['home_special_feature_second_subtitle'] ?? '');
        $settings['home_special_feature_third_logo'] = asset($setting['home_special_feature_third_logo'] ?? '');
        $settings['home_special_feature_third_title'] = ($setting['home_special_feature_third_title'] ?? '');
        $settings['home_special_feature_third_subtitle'] = ($setting['home_special_feature_third_subtitle'] ?? '');
        $settings['course_title'] = ($setting['course_title'] ?? '');
        $settings['course_subtitle'] = ($setting['course_subtitle'] ?? '');
        $settings['top_category_title'] = ($setting['top_category_title'] ?? '');
        $settings['top_category_subtitle'] = ($setting['top_category_subtitle'] ?? '');
        $settings['top_instructor_title'] = ($setting['top_instructor_title'] ?? '');
        $settings['top_instructor_subtitle'] = ($setting['top_instructor_subtitle'] ?? '');
        $settings['become_instructor_video_title'] = ($setting['become_instructor_video_title'] ?? '');
        $settings['become_instructor_video_subtitle'] = ($setting['become_instructor_video_subtitle'] ?? '');
        $settings['customer_say_logo'] = ($setting['customer_say_logo'] ?? '');
        $settings['customer_say_title'] = ($setting['customer_say_title'] ?? '');
        $settings['customer_say_first_name'] = ($setting['customer_say_first_name'] ?? '');
        $settings['customer_say_first_position'] = ($setting['customer_say_first_position'] ?? '');
        $settings['customer_say_first_comment_title'] = ($setting['customer_say_first_comment_title'] ?? '');
        $settings['customer_say_first_comment_description'] = ($setting['customer_say_first_comment_description'] ?? '');
        $settings['customer_say_first_comment_rating_star'] = ($setting['customer_say_first_comment_rating_star'] ?? '');
        $settings['customer_say_second_name'] = ($setting['customer_say_second_name'] ?? '');
        $settings['customer_say_second_position'] = ($setting['customer_say_second_position'] ?? '');
        $settings['customer_say_second_comment_title'] = ($setting['customer_say_second_comment_title'] ?? '');
        $settings['customer_say_second_comment_description'] = ($setting['customer_say_second_comment_description'] ?? '');
        $settings['customer_say_second_comment_rating_star'] = ($setting['customer_say_second_comment_rating_star'] ?? '');
        $settings['customer_say_third_name'] = ($setting['customer_say_third_name'] ?? '');
        $settings['customer_say_third_position'] = ($setting['customer_say_third_position'] ?? '');
        $settings['customer_say_third_comment_title'] = ($setting['customer_say_third_comment_title'] ?? '');
        $settings['customer_say_third_comment_description'] = ($setting['customer_say_third_comment_description'] ?? '');
        $settings['customer_say_third_comment_rating_star'] = ($setting['customer_say_third_comment_rating_star'] ?? '');
        $settings['achievement_first_title'] = ($setting['achievement_first_title'] ?? '');
        $settings['achievement_first_subtitle'] = ($setting['achievement_first_subtitle'] ?? '');
        $settings['achievement_second_title'] = ($setting['achievement_second_title'] ?? '');
        $settings['achievement_second_subtitle'] = ($setting['achievement_second_subtitle'] ?? '');
        $settings['achievement_third_title'] = ($setting['achievement_third_title'] ?? '');
        $settings['achievement_third_subtitle'] = ($setting['achievement_third_subtitle'] ?? '');
        $settings['achievement_four_title'] = ($setting['achievement_four_title'] ?? '');
        $settings['achievement_four_subtitle'] = ($setting['achievement_four_subtitle'] ?? '');
        $settings['support_faq_title'] = ($setting['support_faq_title'] ?? '');
        $settings['support_faq_subtitle'] = ($setting['support_faq_subtitle'] ?? '');
        $settings['ticket_title'] = ($setting['ticket_title'] ?? '');
        $settings['ticket_subtitle'] = ($setting['ticket_subtitle'] ?? '');
        $settings['allow_preloader'] = ($setting['allow_preloader'] ?? '');
        $settings['registration_email_verification'] = ($setting['registration_email_verification'] ?? '');
        $settings['courseUploadRuleTitle'] = ($setting['courseUploadRuleTitle'] ?? '');
        $settings['course_upload_rules'] = ($setting['course_upload_rules'] ?? '');
        $settings['bundle_course_title'] = ($setting['bundle_course_title'] ?? '');
        $settings['bundle_course_subtitle'] = ($setting['bundle_course_subtitle'] ?? '');
        $settings['bundle_course_logo'] = asset($setting['bundle_course_logo'] ?? '');
        $settings['app_color_design_type'] = ($setting['app_color_design_type'] ?? '');
        $settings['app_theme_color'] = ($setting['app_theme_color'] ?? '');
        $settings['app_navbar_background_color'] = ($setting['app_navbar_background_color'] ?? '');
        $settings['app_body_font_color'] = ($setting['app_body_font_color'] ?? '');
        $settings['app_heading_color'] = ($setting['app_heading_color'] ?? '');
        $settings['app_gradiant_banner_color1'] = ($setting['app_gradiant_banner_color1'] ?? '');
        $settings['app_gradiant_banner_color2'] = ($setting['app_gradiant_banner_color2'] ?? '');
        $settings['app_gradiant_banner_color'] = ($setting['app_gradiant_banner_color'] ?? '');
        $settings['app_gradiant_footer_color1'] = ($setting['app_gradiant_footer_color1'] ?? '');
        $settings['app_gradiant_footer_color2'] = ($setting['app_gradiant_footer_color2'] ?? '');
        $settings['app_gradiant_footer_color'] = ($setting['app_gradiant_footer_color'] ?? '');
        $settings['app_gradiant_overlay_background_color_opacity'] = ($setting['app_gradiant_overlay_background_color_opacity'] ?? '');
        $settings['og_title'] = ($setting['og_title'] ?? '');
        $settings['og_description'] = ($setting['og_description'] ?? '');
        $settings['app_version'] = ($setting['app_version'] ?? '');
        $settings['referral_status'] = ($setting['referral_status'] ?? '');
        $settings['referral_commission_percentage'] = ($setting['referral_commission_percentage'] ?? '');
        $settings['current_version'] = ($setting['current_version'] ?? '');
        $settings['saas_mode'] = ($setting['saas_mode'] ?? '');
        $settings['subscription_mode'] = ($setting['subscription_mode'] ?? '');
        $settings['registration_system_bonus_mode'] = ($setting['registration_system_bonus_mode'] ?? '');
        $settings['registration_bonus_amount'] = ($setting['registration_bonus_amount'] ?? '');
        $settings['refund_system_mode'] = ($setting['refund_system_mode'] ?? '');
        $settings['cashback_system_mode'] = ($setting['cashback_system_mode'] ?? '');
        $settings['cashback_type'] = ($setting['cashback_type'] ?? '');
        $settings['cashback_amount'] = ($setting['cashback_amount'] ?? '');
        $settings['upcoming_course_title'] = ($setting['upcoming_course_title'] ?? '');
        $settings['upcoming_course_subtitle'] = ($setting['upcoming_course_subtitle'] ?? '');
        $settings['upcoming_course_logo'] = asset($setting['upcoming_course_logo'] ?? '');
        $settings['instagram_url'] = ($setting['instagram_url'] ?? '');
        $settings['tiktok_url'] = ($setting['tiktok_url'] ?? '');
        $settings['course_gift_mode'] = ($setting['course_gift_mode'] ?? '');
        $settings['wallet_recharge_system'] = ($setting['wallet_recharge_system'] ?? '');
        $settings['wallet_checkout_system'] = ($setting['wallet_checkout_system'] ?? '');
        $settings['LMSZAIAI_current_version'] = ($setting['LMSZAIAI_current_version'] ?? '');
        $settings['GOOGLE_LOGIN_STATUS'] = env('GOOGLE_LOGIN_STATUS', 0);
        $settings['FACEBOOK_LOGIN_STATUS'] = env('FACEBOOK_LOGIN_STATUS', 0);
        $settings['TWITTER_LOGIN_STATUS'] = env('TWITTER_LOGIN_STATUS', 0);
        return $this->success($settings);
    }

    public function courses()
    {
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

        $courses = Course::active()->featured()->take(12)->select(['title', 'image', 'user_id', 'instructor_id', 'organization_id', 'slug', 'price', 'old_price', 'id', 'average_rating', 'created_at', 'learner_accessibility'])->with('promotionCourse.promotion')->withCount('reviews')->with(['user', 'instructor', 'organization'])->get();
        foreach($courses as $course){
            $startDate = date('d-m-Y H:i:s', strtotime(@$course->promotionCourse->promotion->start_date));
            $endDate = date('d-m-Y H:i:s', strtotime(@$course->promotionCourse->promotion->end_date));
            $percentage = @$course->promotionCourse->promotion->percentage;
            $discount_price = number_format($course->price - (($course->price * $percentage) / 100), 2);

            if(now()->gt($startDate) && now()->lt($endDate)){
                $filterData['discount_price'] = $discount_price;
                $filterData['price'] = $course->price;
            }elseif($course->price <= $course->old_price){
                $filterData['price'] = $course->old_price;
                $filterData['discount_price'] = $course->price;
            }else{
                $filterData['price'] = $course->price;
                $filterData['discount_price'] = $course->price;
            }

            if($course->learner_accessibility != 'free' && get_option('cashback_system_mode', 0)){
                $filterData['cashback'] = calculateCashback($course->price) ;
            }

            $userRelation = getUserRoleRelation($course->user);

            $filterData['title'] = $course->title;
            $filterData['average_rating'] = $course->average_rating;
            $filterData['slug'] = $course->slug;
            $filterData['id'] = $course->id;
            $filterData['created_at'] = $course->created_at;
            $filterData['image_url'] = $course->image_url;
            $filterData['learner_accessibility'] = $course->learner_accessibility;
            $filterData['total_review'] = $course->reviews_count;
            $filterData['author'] = $course->$userRelation->name;
            $filterData['author_user_id'] = $course->user_id;
            $awards = '';
            foreach($course->$userRelation->awards as $award){
                $awards .= ' | '. $award->name;
            }

            $filterData['author_awards'] = $awards;

            $data['courses'][] = $filterData;
        }

        return $this->success($data);
    }

    public function upcomingCourses()
    {
        $courses = Course::with('reviews')->with('user')->with('promotionCourse.promotion')->with('instructor.ranking_level')->with('specialPromotionTagCourse.specialPromotionTag')->upcoming()->take(12)->get();
        foreach($courses as $course){
            $startDate = date('d-m-Y H:i:s', strtotime(@$course->promotionCourse->promotion->start_date));
            $endDate = date('d-m-Y H:i:s', strtotime(@$course->promotionCourse->promotion->end_date));
            $percentage = @$course->promotionCourse->promotion->percentage;
            $discount_price = number_format($course->price - (($course->price * $percentage) / 100), 2);

            if(now()->gt($startDate) && now()->lt($endDate)){
                $filterData['discount_price'] = $discount_price;
                $filterData['price'] = $course->price;
            }elseif($course->price <= $course->old_price){
                $filterData['price'] = $course->old_price;
                $filterData['discount_price'] = $course->price;
            }else{
                $filterData['price'] = $course->price;
                $filterData['discount_price'] = $course->price;
            }

            if($course->learner_accessibility != 'free' && get_option('cashback_system_mode', 0)){
                $filterData['cashback'] = calculateCashback($course->price) ;
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
            foreach($course->$userRelation->awards as $award){
                $awards .= ' | '. $award->name;
            }

            $filterData['author_awards'] = $awards;

            $data['courses'][] = $filterData;
        }

        return $this->success($data);
    }

    public function categoryList()
    {
        $data = Category::active()->feature()->get();
        return $this->success($data);
    }

    public function categoryCourse($slug)
    {
        $featureCategory = Category::where('slug', $slug)->with('activeCourses')->with('courses.reviews')->with('courses.user')->with('courses.promotionCourse.promotion')->with('courses.instructor.ranking_level')->with('courses.specialPromotionTagCourse.specialPromotionTag')->first();

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

        if(count($featureCategory->activeCourses)){
            foreach($featureCategory->activeCourses->take(12) as $course){
                $filterData = [];
                $startDate = date('d-m-Y H:i:s', strtotime(@$course->promotionCourse->promotion->start_date));
                $endDate = date('d-m-Y H:i:s', strtotime(@$course->promotionCourse->promotion->end_date));
                $percentage = @$course->promotionCourse->promotion->percentage;
                $discount_price = number_format($course->price - (($course->price * $percentage) / 100), 2);

                if(now()->gt($startDate) && now()->lt($endDate)){
                    $filterData['discount_price'] = $discount_price;
                    $filterData['price'] = $course->price;
                }elseif($course->price <= $course->old_price){
                    $filterData['price'] = $course->old_price;
                    $filterData['discount_price'] = $course->price;
                }else{
                    $filterData['price'] = $course->price;
                    $filterData['discount_price'] = $course->price;
                }

                if($course->learner_accessibility != 'free' && get_option('cashback_system_mode', 0)){
                    $filterData['cashback'] = calculateCashback($course->price) ;
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

                foreach($course->$userRelation->awards as $award){
                    $awards .= ' | '. $award->name;
                }

                $filterData['author_awards'] = $awards;

                $data['courses'][] = $filterData;
            }
        }
        else{
            $data['courses'] = [];
        }

        return $this->success($data);
    }

    public function faqQuestions()
    {
        $faqQuestions = FaqQuestion::get();
        return $this->success($faqQuestions);
    }

    public function clients()
    {
        $clients = ClientLogo::all();
        return $this->success($clients);
    }

    public function bundleCourses()
    {
        $bundles = Bundle::withCount('bundleCourses')->with('instructor:uuid,first_name,last_name,professional_title,hourly_rate,consultation_available')->with('organization:uuid,first_name,last_name,professional_title,hourly_rate,consultation_available')->active()->latest()->take(12)->get();
        return $this->success($bundles);
    }

    public function instructors()
    {
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
            ->paginate(8);

        $data = [];
        foreach($instructors as $instructor){
            $data[] = [
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
                "address" => $instructor->address,
                'average_rating' => $instructor->courses->where('average_rating', '>', 0)->avg('average_rating') ?? 0,
                'total_rating' => count($instructor->courses->where('average_rating', '>', 0)),
                'badges' => $instructor->badges,
            ];
        }

        return $this->success($data);
    }

    public function consultationInstructors()
    {
        $consultationInstructors = User::query()
            ->leftJoin('instructors as ins', 'ins.user_id', '=', 'users.id')
            ->leftJoin('organizations as org', 'org.user_id', '=', 'users.id')
            ->whereIn('users.role', [USER_ROLE_INSTRUCTOR, USER_ROLE_ORGANIZATION])
            ->where(function ($q) {
                $q->where('ins.status', STATUS_APPROVED)
                    ->orWhere('org.status', STATUS_APPROVED);
            })
            ->where(function ($q) {
                $q->where('ins.consultation_available', STATUS_APPROVED)
                    ->orWhere('org.consultation_available', STATUS_APPROVED);
            })
            ->with('badges:name,type,from,to,description')
            ->select('users.*', 'ins.organization_id', DB::raw(selectStatement()))
            ->limit(8)
            ->get();

        $data = [];
        foreach($consultationInstructors as $consultationInstructor){
            $data[] = [
                "id" => $consultationInstructor->id,
                "name" => $consultationInstructor->name,
                "email" => $consultationInstructor->email,
                "image_url" => $consultationInstructor->image_url,
                "area_code" => $consultationInstructor->area_code,
                "mobile_number" => $consultationInstructor->mobile_number,
                "email_verified_at" => $consultationInstructor->email_verified_at,
                "role" => $consultationInstructor->role,
                "phone_number" => $consultationInstructor->phone_number,
                "is_affiliator" => $consultationInstructor->is_affiliator,
                "organization_id" => $consultationInstructor->organization_id,
                "uuid" => $consultationInstructor->uuid,
                "professional_title" => $consultationInstructor->professional_title,
                "postal_code" => $consultationInstructor->postal_code,
                "about_me" => $consultationInstructor->about_me,
                "gender" => $consultationInstructor->gender,
                "social_link" => $consultationInstructor->social_link,
                "slug" => $consultationInstructor->slug,
                "remove_from_web_search" => $consultationInstructor->remove_from_web_search,
                "is_offline" => $consultationInstructor->is_offline,
                "offline_message" => $consultationInstructor->offline_message,
                "consultation_available" => $consultationInstructor->consultation_available,
                "hourly_rate" => $consultationInstructor->hourly_rate,
                "hourly_old_rate" => $consultationInstructor->hourly_old_rate,
                "available_type" => $consultationInstructor->available_type,
                "approval_date" => $consultationInstructor->approval_date,
                'average_rating' => $consultationInstructor->courses->where('average_rating', '>', 0)->avg('average_rating') ?? 0,
                'total_rating' => count($consultationInstructor->courses->where('average_rating', '>', 0)),
                'badges' => $consultationInstructor->badges,
            ];
        }

        return $this->success($data);
    }

    public function subscriptions()
    {
        $data['subscriptions'] = Package::where('status', PACKAGE_STATUS_ACTIVE)->where('package_type', PACKAGE_TYPE_SUBSCRIPTION)->where('in_home', PACKAGE_STATUS_ACTIVE)->orderBy('order', 'ASC')->get();
        $data['mySubscriptionPackage'] = UserPackage::where('user_packages.user_id', auth()->id())->where('user_packages.status', PACKAGE_STATUS_ACTIVE)->whereDate('enroll_date', '<=', now())->whereDate('expired_date', '>=', now())->where('package_type', PACKAGE_TYPE_SUBSCRIPTION)->join('packages', 'packages.id', '=', 'user_packages.package_id')->select('package_id', 'package_type', 'subscription_type')->first();
        return $this->success($data);
    }

    public function saas()
    {
        $packages = Package::where('status', PACKAGE_STATUS_ACTIVE)->where('in_home', PACKAGE_STATUS_ACTIVE)->whereIn('package_type', [PACKAGE_TYPE_SAAS_INSTRUCTOR, PACKAGE_TYPE_SAAS_ORGANIZATION])->orderBy('order', 'ASC')->get();
        $data['instructorSaas'] = $packages->where('package_type', PACKAGE_TYPE_SAAS_INSTRUCTOR);
        $data['organizationSaas'] = $packages->where('package_type', PACKAGE_TYPE_SAAS_ORGANIZATION);
        $data['mySaasPackage'] = UserPackage::where('user_packages.user_id', auth()->id())->where('user_packages.status', PACKAGE_STATUS_ACTIVE)->whereDate('enroll_date', '<=', now())->whereDate('expired_date', '>=', now())->whereIn('package_type', [PACKAGE_TYPE_SAAS_INSTRUCTOR, PACKAGE_TYPE_SAAS_ORGANIZATION])->join('packages', 'packages.id', '=', 'user_packages.package_id')->select('package_id', 'package_type', 'subscription_type')->first();
        return $this->success($data);
    }

    public function getCurrentCurrency(){
        $data = get_current_currency();
        return $this->success($data);
    }

    public function getLanguage(){
        $data = appLanguages();
        return $this->success($data);
    }

    public function getLanguageJson($code){
        $data = json_decode(file_get_contents(resource_path('lang/'.$code.'.json')));
        return $this->success($data);
    }

    public function getCountry(){
        $data = Country::all();
        return $this->success($data);
    }

    public function getState($country_id){
        $data = State::where('country_id', $country_id)->get();
        return $this->success($data);
    }

    public function getCity($state_id){
        $data = City::where('state_id', $state_id)->get();
        return $this->success($data);
    }

    public function instructorDetails($userId){
        $instructor = User::query()
            ->leftJoin('instructors as ins', 'ins.user_id', '=', 'users.id')
            ->leftJoin('organizations as org', 'org.user_id', '=', 'users.id')
            ->where('users.id', $userId)
            ->where(function ($q) {
                $q->where('ins.status', STATUS_APPROVED)
                    ->orWhere('org.status', STATUS_APPROVED);
            })
            ->with('badges:name,type,from,to,description')
            ->with('courses')
            ->select('users.*', 'ins.organization_id', DB::raw(selectStatement()))
            ->first();

        if(is_null($instructor)){
            return $this->error([], __('Data Not Found'));
        }


        $courseIds = Course::where('private_mode', '!=', 1)->where('user_id', $instructor->id)->where('status', 1)->select('id')->pluck('id')->toArray();

        $data = [
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
            "address" => $instructor->address,
            'average_rating' => $instructor->courses->where('average_rating', '>', 0)->avg('average_rating') ?? 0,
            'total_rating' => count($instructor->courses->where('average_rating', '>', 0)),
            'badges' => $instructor->badges,
            'totalCourse' => count($instructor->courses),
            'totalStudent' => Enrollment::where('owner_user_id', $instructor->id)->groupBy('user_id')->count(),
            'totalMeeting' => Enrollment::where('owner_user_id', $instructor->id)->whereNull('consultation_slot_id')->count(),
            'total_assignments' => Assignment::whereIn('course_id', $courseIds)->count(),
            'total_lectures' => Course_lecture::whereIn('course_id', $courseIds)->count(),
            'total_quizzes' => Exam::whereIn('course_id', $courseIds)->count(),
        ];

        foreach($instructor->courses as $course){
            $filterData = [];
            $startDate = date('d-m-Y H:i:s', strtotime(@$course->promotionCourse->promotion->start_date));
            $endDate = date('d-m-Y H:i:s', strtotime(@$course->promotionCourse->promotion->end_date));
            $percentage = @$course->promotionCourse->promotion->percentage;
            $discount_price = number_format($course->price - (($course->price * $percentage) / 100), 2);

            if(now()->gt($startDate) && now()->lt($endDate)){
                $filterData['discount_price'] = $discount_price;
                $filterData['price'] = $course->price;
            }elseif($course->price <= $course->old_price){
                $filterData['price'] = $course->old_price;
                $filterData['discount_price'] = $course->price;
            }else{
                $filterData['price'] = $course->price;
                $filterData['discount_price'] = $course->price;
            }

            if($course->learner_accessibility != 'free' && get_option('cashback_system_mode', 0)){
                $filterData['cashback'] = calculateCashback($course->price) ;
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

            foreach($course->$userRelation->awards as $award){
                $awards .= ' | '. $award->name;
            }

            $filterData['author_awards'] = $awards;

            $data['courses'][] = $filterData;
        }

        return $this->success($data);
    }
}
