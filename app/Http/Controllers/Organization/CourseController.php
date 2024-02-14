<?php

namespace App\Http\Controllers\Organization;

use App\Http\Controllers\Controller;
use App\Http\Requests\Instructor\CourseUpdateCategoryRequest;
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
use App\Models\Instructor;
use App\Models\LearnKeyPoint;
use App\Models\Order_item;
use App\Models\Student;
use App\Models\Subcategory;
use App\Models\Tag;
use App\Models\User;
use App\Models\Wishlist;
use App\Tools\Repositories\Crud;
use App\Traits\General;
use App\Traits\ImageSaveTrait;
use App\Traits\SendNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CourseController extends Controller
{
    use General, ImageSaveTrait, SendNotification;
    protected $model, $lectureModel, $lessonModel;

    public function __construct(Course $course, Course_lesson $course_lesson,  Course_lecture $course_lecture)
    {
        $this->model = new Crud($course);
        $this->lectureModel = new Crud($course_lecture);
        $this->lessonModel = new Crud($course_lesson);
    }

    public function index()
    {
        $data['title'] = 'My Course';
        $data['courses'] = Course::where('organization_id', auth()->user()->organization->id)->orderBy('id', 'DESC')->paginate();
        $data['navCourseActiveClass'] = 'active';
        $data['number_of_course'] = count($data['courses']);
        return view('organization.course.index', $data);
    }

    public function create()
    {
        $count = Course::where('user_id', auth()->id())->count();

        if(hasLimitSaaS(PACKAGE_RULE_COURSE, PACKAGE_TYPE_SAAS_ORGANIZATION, $count)){
            $data['title'] = 'Upload Course';
            $data['navCourseUploadActiveClass'] = 'active';
            $data['rules'] = CourseUploadRule::all();
            return view('organization.course.create', $data);
        }
        else{
            $this->showToastrMessage('error', __('Your Course Create limit has been finish.'));
            return redirect()->back();
        }
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
            'private_mode' => $request->private_mode,
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
            if(!hasLimitSaaS(PACKAGE_RULE_SUBSCRIPTION_COURSE, PACKAGE_TYPE_SAAS_ORGANIZATION, $count)){
                $this->showToastrMessage('error', __('Your Subscription Enable Course Create limit has been finish.'));
                return redirect()->back();
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
        return redirect(route('organization.course.edit', [$course->uuid, 'step=category']));
    }

    public function edit($uuid)
    {
        $data['navCourseUploadActiveClass'] = 'active';
        $data['title'] = 'Upload Course';
        $data['rules'] = CourseUploadRule::all();
        $data['course'] = Course::where('courses.uuid', $uuid)->firstOrFail();
        $user_id = auth()->id();

        if(!$data['course']->user_id == $user_id){
            $this->showToastrMessage('error', __('You don\'t have permission to edit this'));
            return redirect()->back();
        }

        $data['keyPoints'] = LearnKeyPoint::whereCourseId($data['course']->id)->get();
        if (\request('step') == 'category') {
            $data['categories'] = Category::active()->orderBy('name', 'asc')->select('id', 'name')->get();
            $data['tags'] = Tag::orderBy('name', 'asc')->select('id', 'name')->get();
            $data['course_languages'] = Course_language::orderBy('name', 'asc')->select('id', 'name')->get();
            $data['difficulty_levels'] = Difficulty_level::orderBy('name', 'asc')->select('id', 'name')->get();
            if (old('category_id')) {
                $data['subcategories'] = Subcategory::where('category_id', old('category_id'))->select('id', 'name')->orderBy('name', 'asc')->get();
            } elseif ($data['course']->category_id) {
                $data['subcategories'] = Subcategory::where('category_id', $data['course']->category_id)->select('id', 'name')->orderBy('name', 'asc')->get();
            } else {
                $data['subcategories'] = [];
            }

            $selected_tags = [];

            if (old('tag')) {
                $selected_tags = old('tag');
            } elseif ($data['course']->tags->count() > 0) {
                foreach ($data['course']->tags as $tag) {
                    $selected_tags[] = $tag->id;
                }
            } else {
                $selected_tags = [];
            }

            $data['selected_tags'] = $selected_tags;

            return view('organization.course.edit-category', $data);
        } elseif (\request('step') == 'lesson') {
            if ($data['course']->course_type == COURSE_TYPE_GENERAL) {
                return view('organization.course.lesson', $data);
            } else {
                return view('organization.course.scorm_upload', $data);
            }
        } elseif (\request('step') == 'instructors') {
            if ($data['course']->user_id != auth()->id()) {
                return view('organization.course.submit-lesson', $data);
            }

            $organization_id = auth()->user()->organization->id;
            $data['instructors'] = User::where('role', USER_ROLE_INSTRUCTOR)->where('instructors.organization_id', $organization_id)->where('instructors.status', STATUS_APPROVED)->join('instructors', 'instructors.user_id', 'users.id')->where('users.id', '!=', $data['course']->user_id)->where('users.id', '!=', auth()->id())->select('users.id', 'users.name')->get();
            return view('organization.course.instructors', $data);
        } elseif (\request('step') == 'submit') {
            return view('organization.course.submit-lesson', $data);
        } else {
            return view('organization.course.edit', $data);
        }
    }

    public function updateOverview(StoreCourseRequest $request, $uuid)
    {
        $data['navCourseUploadActiveClass'] = 'active';
        $course = Course::where('courses.uuid', $uuid)->firstOrFail();
        $user_id = auth()->id();

        if(!$course->user_id == $user_id){

            $courseInstructor = $course->course_instructors()->where('instructor_id', $user_id)->where('status', STATUS_ACCEPTED)->first();
            if(!$courseInstructor){
                $this->showToastrMessage('error', __('You don\'t have permission to edit this'));
                return redirect()->back();
            }
        }

        if (Course::where('slug', getSlug($request->title))->where('id', '!=', $course->id)->count() > 0)
        {
            $slug = getSlug($request->title) . '-'. rand(100000, 999999);
        } else {
            $slug = getSlug($request->title);
        }

        $data = [
            'title' => $request->title,
            'private_mode' => $request->private_mode,
            'course_type' => $request->course_type,
            'subtitle' => $request->subtitle,
            'slug' => $slug,
            'description' => $request->description
        ];

        $data['is_subscription_enable'] = 0;

        if(get_option('subscription_mode')){
            $data['is_subscription_enable'] = $request->is_subscription_enable;
        }

        if($data['is_subscription_enable']){
            if($course->status == STATUS_APPROVED){
                $count = CourseInstructor::join('courses', 'courses.id', '=', 'course_instructor.course_id')->where('is_subscription_enable', STATUS_ACCEPTED)->where('course_instructor.instructor_id', auth()->id())->groupBy('course_id')->count();
            }
            else{
                $count = Course::where('user_id', auth()->id())->count();
            }
            if(!hasLimitSaaS(PACKAGE_RULE_SUBSCRIPTION_COURSE, PACKAGE_TYPE_SAAS_ORGANIZATION, $count)){
                $this->showToastrMessage('error', __('Your Subscription Enable Course Create limit has been finish.'));
                return redirect()->back();
            }
        }

        $this->model->updateByUuid($data, $uuid); // update category

        $now = now();
        if ($request['key_points']) {
            if (count(@$request['key_points']) > 0) {
                foreach ($request['key_points'] as $item) {
                    if (@$item['name']) {
                        if (@$item['id']) {
                            $key_point = LearnKeyPoint::find($item['id']);
                        } else {
                            $key_point = new LearnKeyPoint();
                        }
                        $key_point->course_id = $course->id;
                        $key_point->name = @$item['name'];
                        $key_point->updated_at = $now;
                        $key_point->save();
                    }
                }
            }
        }

        LearnKeyPoint::where('course_id', $course->id)->where('updated_at', '!=', $now)->get()->map(function ($q) {
            $q->delete();
        });

        if ($course->status != 0) {
            $text = __("Course overview has been updated");
            $target_url = route('admin.course.index');
            $this->send($text, 1, $target_url, null);
        }

        return redirect(route('organization.course.edit', [$course->uuid, 'step=category']));
    }

    public function updateCategory(Request $request, $uuid)
    {
        $course = Course::where('courses.uuid', $uuid)->firstOrFail();
        $user_id = auth()->id();

        if(!$course->user_id == $user_id){

            $courseInstructor = $course->course_instructors()->where('instructor_id', $user_id)->where('status', STATUS_ACCEPTED)->first();
            if(!$courseInstructor){
                $this->showToastrMessage('error', __('You don\'t have permission to edit this'));
                return redirect()->back();
            }
        }

        if ($request->image) {
            $request->validate([
                'image' => 'mimes:jpg,png,jpeg,gif,svg'
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
                $this->showToastrMessage('error', __('Something went wrong! Failed to upload file'));
                return redirect()->back();
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
            'access_period' => (is_null($request->access_period) || $request->access_period < 0) ? NULL : $request->access_period,
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

        if($request->status == STATUS_UPCOMING_REQUEST && $course->status != STATUS_UPCOMING_APPROVED){
            try{
                $requestStatus = $request->status;
                if(auth()->user()->organization->auto_content_approval == 1){
    
                    $requestStatus = STATUS_UPCOMING_APPROVED;

                    try{
                        DB::beginTransaction();
                        /** ====== send notification to student ===== */
                        $students = Student::where('user_id', '!=', $course->user_id)->select('user_id')->get();
                        foreach ($students as $student) {
                            $text = __("New course has been added");
                            $target_url = route('course-details', $course->slug);
                            $this->send($text, 3, $target_url, $student->user_id);
                        }
                        /** ====== send notification to student ===== */

                        $text = __("Upcoming Course has been approved");
                        $target_url = route('course-details', $course->slug);
                        $this->send($text, 2, $target_url, $course->user_id);
                        $text = __("Upcoming course has been auto approved");
                        DB::commit();
                    }
                    catch(\Exception $e){
                        DB::rollBack();
                        $this->showToastrMessage('error', $e->getMessage());
                        return redirect()->back();
                    }
                }
                else{
                    $text = __("Upcoming course request.");
                }

                $target_url = route('admin.course.index');
                $this->send($text, 1, $target_url, null);

                $course->status = $requestStatus;
                $course->save();

            }
            catch(\Exception $e){
                $this->showToastrMessage('error', $e->getMessage());
                return redirect()->back();
            }
        }
        elseif($request->status == STATUS_APPROVED && in_array($course->status, [STATUS_UPCOMING_REQUEST, STATUS_UPCOMING_APPROVED])){
            $course->status = STATUS_SUSPENDED;
            $course->save();
        }


        if ($course->status != 0) {
            $text = __("Course category has been updated");
            $target_url = route('admin.course.index');
            $this->send($text, 1, $target_url, null);
        }


        return redirect(route('organization.course.edit', [$course->uuid, 'step=lesson']));
    }

    public function uploadFinished($uuid)
    {
        $course = Course::where('courses.uuid', $uuid)->firstOrFail();
        $user_id = auth()->id();

        if(!$course->user_id == $user_id){

            $courseInstructor = $course->course_instructors()->where('instructor_id', $user_id)->where('status', STATUS_ACCEPTED)->first();
            if(!$courseInstructor){
                $this->showToastrMessage('error', __('You don\'t have permission to edit this'));
                return redirect()->back();
            }
        }

        if ($course->status == 1) {

            if ($course->user_id != auth()->id()) {
                //TODO: notify from here to multi instructor;
                $text = __("You have selected as co-instructor");
                $target_url = route('instructor.multi_instructor');
                $courseInstructors = $course->course_instructors->where('status', STATUS_PENDING)->where('instructor_id', '!=', $course->user_id);

                foreach ($courseInstructors as $courseInstructor) {
                    $this->send($text, 2, $target_url, $courseInstructor->instructor->user_id);
                }
            }
        }elseif($course->status != STATUS_UPCOMING_REQUEST && $course->status != STATUS_UPCOMING_APPROVED) {
            if(auth()->user()->organization->auto_content_approval == 1){
                $course->status = 1;

                try{
                    //TODO: notify from here to multi instructor;
                    $text = __("You have selected as co-instructor");
                    $target_url = route('instructor.multi_instructor');
                    $courseInstructors = $course->course_instructors->where('status', STATUS_PENDING)->where('instructor_id', '!=', $course->user_id);
    
                    foreach ($courseInstructors as $courseInstructor) {
                        $this->send($text, 2, $target_url, $courseInstructor->instructor->user_id);
                    }
                    
                    setBadge($course->user_id);
                    $text = __("Course has been approved");
                    $target_url = route('course-details', $course->slug);
                    $this->send($text, 2, $target_url, $course->user_id);
        
                    /** ====== send notification to student ===== */
                    $students = Student::where('user_id', '!=', $course->user_id)->select('user_id')->get();
                    foreach ($students as $student) {
                        $text = __("New course has been published");
                        $target_url = route('course-details', $course->slug);
                        $this->send($text, 3, $target_url, $student->user_id);
                    }
                    /** ====== send notification to student ===== */
                }
                catch(\Exception $e){
                    $this->showToastrMessage('error', $e->getMessage());
                    return redirect()->back();
                }
            }
            else{
                $course->status = 2;
            }
            
        }
        $course->save();
        return redirect(route('organization.course.index'));
    }

    public function getSubcategoryByCategory($category_id)
    {
        return Subcategory::where('category_id', $category_id)->select('id', 'name')->get()->toJson();
    }

    public function delete($uuid)
    {
        $course = Course::where('user_id', auth()->id())->whereUuid($uuid)->firstOrFail();
        $order_item = Order_item::whereCourseId($course->id)->first();
        if ($order_item)
        {
            $this->showToastrMessage('error', __('You can not deleted. Because already student purchased this course!'));
            return redirect()->back();
        }

        //start:: Course lesson delete
        $lessons = Course_lesson::where('course_id', $course->id)->get();
        if (count($lessons) > 0) {
            foreach ($lessons as $lesson) {
                //start:: lecture delete
                $lectures = Course_lecture::where('lesson_id', $lesson->id)->get();
                if (count($lectures) > 0) {
                    foreach ($lectures as $lecture) {
                        $lecture = Course_lecture::find($lecture->id);
                        if ($lecture) {
                            $this->deleteFile($lecture->file_path); // delete file from server

                            if ($lecture->type == 'vimeo') {
                                if ($lecture->url_path) {
                                    $this->deleteVimeoVideoFile($lecture->url_path);
                                }
                            }

                            Course_lecture_views::where('course_lecture_id', $lecture->id)->get()->map(function ($q) {
                                $q->delete();
                            });

                            $this->lectureModel->delete($lecture->id); // delete record
                        }
                    }
                }
                //end:: lecture delete
                $this->lessonModel->delete($lesson->id);
            }
        }
        //end: lesson delete

        //Start:: Delete this course Wishlist, CartManagement
        Wishlist::where('course_id', $course->id)->delete();
        CartManagement::where('course_id', $course->id)->delete();
        CourseInstructor::where('course_id', $course->id)->delete();
        //End:: Delete this course wishList and addToCart

        $this->deleteFile($course->image);
        $this->deleteVideoFile($course->video);
        $course->delete();
        $this->showToastrMessage('success', __('Course has been deleted.'));
        return redirect()->back();
    }

    public function storeInstructor(Request $request, $uuid)
    {
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
                    $this->showToastrMessage('error', 'The total percentage should not be grater than 100');
                    return back()->withInput();
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

            return redirect(route('organization.course.edit', [$course->uuid, 'step=submit']));
        }
    }
}
