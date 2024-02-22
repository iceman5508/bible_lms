<?php

namespace App\Http\Controllers\Api\Instructor;

use App\Http\Controllers\Controller;
use App\Http\Requests\Instructor\StoreCourseRequest;
use App\Models\CartManagement;
use App\Models\Category;
use App\Models\Course;
use App\Models\Course_language;
use App\Models\Course_lecture;
use App\Models\Course_lecture_views;
use App\Models\Course_lesson;
use App\Models\CourseInstructor;
use App\Models\CourseUploadRule;
use App\Models\Difficulty_level;
use App\Models\LearnKeyPoint;
use App\Models\Order_item;
use App\Models\Subcategory;
use App\Models\Tag;
use App\Models\User;
use App\Models\Wishlist;
use App\Tools\Repositories\Crud;
use App\Traits\ApiStatusTrait;
use App\Traits\ImageSaveTrait;
use App\Traits\SendNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CourseController extends Controller
{
    use ImageSaveTrait, SendNotification, ApiStatusTrait;
    protected $model, $lectureModel, $lessonModel;

    public function __construct(Course $course, Course_lesson $course_lesson,  Course_lecture $course_lecture)
    {
        $this->model = new Crud($course);
        $this->lectureModel = new Crud($course_lecture);
        $this->lessonModel = new Crud($course_lesson);
    }

    public function index()
    {
        $courseInstructorIds = CourseInstructor::where('course_instructor.instructor_id', auth()->id())->join('courses', 'courses.id', '=', 'course_instructor.course_id')->select('user_packages.*')->whereNull('courses.organization_id')->where('course_instructor.status',STATUS_APPROVED)->select('course_id')->get()->pluck('course_id')->toArray();
        $mainCourseIds = Course::where('user_id', auth()->id())->select('id')->pluck('id')->toArray();

        $courseInstructorIds = array_unique(array_merge($mainCourseIds, $mainCourseIds));
        $data['courses'] = Course::whereIn('id', $courseInstructorIds)->whereNull('organization_id')->orderBy('id', 'DESC')->paginate(10);
        return $this->success($data);
    }

    public function store(StoreCourseRequest $request)
    {
        if (Course::where('slug', Str::slug($request->title))->count() > 0)
        {
            $slug = Str::slug($request->title) . '-'. rand(100000, 999999);
        } else {
            $slug = Str::slug($request->title);
        }

        $data = [
            'title' => $request->title,
            'course_type' => $request->course_type,
            'subtitle' => $request->subtitle,
            'slug' => $slug,
            'status' => 4,
            'description' => $request->description
        ];

        $data['is_subscription_enable']= 0;

        if(get_option('subscription_mode')){
            $data['is_subscription_enable'] = $request->is_subscription_enable;
        }

        if($data['is_subscription_enable']){
            $count = Course::where('user_id', auth()->id())->count();
            if(!hasLimitSaaS(PACKAGE_RULE_SUBSCRIPTION_COURSE, PACKAGE_TYPE_SAAS_INSTRUCTOR, $count)){
                return $this->failed([], __('Your Subscription Enable Course Create limit has been finish.'));
            }
        }

        $course = $this->model->create($data);

        if ($request['key_points']) {
            if (count(@$request['key_points']) > 0) {
                foreach ($request['key_points'] as $item) {
                    if (@$item['name']) {
                        $key_point = new LearnKeyPoint();
                        $key_point->course_id = $course->id;
                        $key_point->name = @$item['name'];
                        $key_point->save();
                    }
                }
            }
        }
        
        return $this->success(['course_uuid' => $course->uuid, 'step' => 'category']);
    }

    public function updateCategory(Request $request, $uuid)
    {
        $course = Course::where('courses.uuid', $uuid)->first();
        $user_id = auth()->id();

        if(!$course->user_id == $user_id){

            $courseInstructor = $course->course_instructors()->where('instructor_id', $user_id)->where('status', STATUS_ACCEPTED)->first();
            if(!$courseInstructor){
                return $this->failed([], __('You don\'t have permission to edit this'));
            }
        }

        if ($request->image) {
            $request->validate([
                'image' => 'dimensions:min_width=575,min_height=450,max_width=575,max_height=450'
            ]);
            $this->deleteFile($course->image); // delete file from server
            $image = $this->saveImage('course', $request->image, null, null); // new file upload into server
        } else {
            $image = $course->image;
        }

        if ($request->video) {
            $this->deleteVideoFile($course->video); // delete file from server
            $file_details = $this->uploadFileWithDetails('course', $request->video);
            if (!$file_details['is_uploaded']) {
                return $this->failed([], __('Something went wrong! Failed to upload file'));
            }
            $video = $file_details['path'];
        } else {
            $video = $course->video;
        }

        $data = [
            'category_id' => $request->category_id,
            'subcategory_id' => $request->subcategory_id,
            'price' => $request->price,
            'old_price' => $request->old_price,
            'drip_content' => $request->drip_content,
            'access_period' => ($request->access_period || $request->access_period < 0) ? NULL : $request->access_period,
            'course_language_id' => $request->course_language_id,
            'difficulty_level_id' => $request->difficulty_level_id,
            'learner_accessibility' => $request->learner_accessibility,
            'image' => $image ?? null,
            'video' => $video ?? null,
            'intro_video_check' => $request->intro_video_check,
            'youtube_video_id' => $request->youtube_video_id ?? null,
        ];

        $this->model->updateByUuid($data, $uuid); // update category

        if ($request->tag) {
            $course->tags()->sync($request->tag);
        }

        if ($course->status != 0) {
            $text = __("Course category has been updated");
            $target_url = route('admin.course.index');
            $this->send($text, 1, $target_url, null);
        }

        return $this->success(['course_uuid' => $course->uuid, 'step' => 'lesson']);
    }

    public function uploadFinished($uuid)
    {
        $course = Course::where('courses.uuid', $uuid)->first();
        $user_id = auth()->id();

        if($course->user_id != $user_id){

            $courseInstructor = $course->course_instructors()->where('instructor_id', $user_id)->where('status', STATUS_ACCEPTED)->first();
            if(!$courseInstructor){
                return $this->failed([], __('You don\'t have permission to edit this'));
            }
        }

        if ($course->status == 1) {

            if ($course->user_id != auth()->id()) {
                //TODO: notify from here to multi instructor;
                $text = __("You have selected as co-instructor");
                $target_url = route('instructor.multi_instructor');
                $courseInstructors = $course->course_instructors->where('status', STATUS_PENDING)->where('instructor_id', '!=', $course->user_id);

                foreach ($courseInstructors as $courseInstructor) {
                    $this->send($text, 2, $target_url, $courseInstructor->instructor_id);
                }
            }
        } else {
            $course->status = 2;
        }
        $course->save();
        return $this->success();
    }

    public function getSubcategoryByCategory($category_id)
    {
        return Subcategory::where('category_id', $category_id)->select('id', 'name')->get()->toJson();
    }


    public function storeInstructor(Request $request, $uuid)
    {
        try{

            $course = Course::where('user_id', auth()->id())->whereUuid($uuid)->firstOrFail();
    
            if ($course->user_id == auth()->id()) {
                $request->validate([
                    'share.*' => 'bail|required|min:0|max:100'
                ]);
    
                $data = $request->all();
                $courseInstructorIds = [];
                if($request->instructor_id){
    
                    $totalShare = array_sum($request->share);
                    if ($totalShare > 100) {
                         return $this->failed([], 'The total percentage should not be grater than 100');
                    }
    
                    foreach ($data['instructor_id'] as $id => $instructor) {
                        $courseInstructor = CourseInstructor::updateOrCreate([
                            'instructor_id' => $id,
                            'course_id' => $course->id,
                        ], [
                            'instructor_id' => $id,
                            'course_id' => $course->id,
                            'share' => $data['share'][$id],
                        ]);
    
                        array_push($courseInstructorIds, $courseInstructor->id);
                    }
                }
                else{
                    $totalShare = 0;
                }
    
                $courseInstructor = CourseInstructor::updateOrCreate([
                    'instructor_id' => $course->user_id,
                    'course_id' => $course->id,
                ], [
                    'instructor_id' => $course->user_id,
                    'course_id' => $course->id,
                    'share' => (100 - $totalShare),
                    'status' => STATUS_ACCEPTED
                ]);
    
    
                array_push($courseInstructorIds, $courseInstructor->id);
    
                CourseInstructor::whereNotIn('id', $courseInstructorIds)->where('course_id', $course->id)->delete();
    
                return $this->success(['course_uuid' => $course->uuid, 'step' => 'submit']);
            }
        }
        catch(\Exception $e){
            return $this->failed([], $e->getMessage());
        }
    }
}
