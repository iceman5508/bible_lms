<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Course_lecture;
use App\Models\Course_lecture_views;
use App\Models\Course_lesson;
use App\Models\CourseInstructor;
use App\Models\CourseUploadRule;
use App\Models\Enrollment;
use App\Models\Order;
use App\Models\Order_item;
use App\Models\Setting;
use App\Models\Student;
use App\Models\User;
use App\Tools\Repositories\Crud;
use App\Traits\ApiStatusTrait;
use App\Traits\ImageSaveTrait;
use App\Traits\SendNotification;
use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Arr;

class CourseController extends Controller
{
    use ApiStatusTrait, ImageSaveTrait, SendNotification;
    protected $model, $lectureModel, $lessonModel;

    public function __construct(Course $course, Course_lesson $course_lesson,  Course_lecture $course_lecture)
    {
        $this->model = new Crud($course);
        $this->lectureModel = new Crud($course_lecture);
        $this->lessonModel = new Crud($course_lesson);
    }

    public function index()
    {
        if (!Auth::user()->hasPermissionTo('all_course', 'web')) {
            return $this->error([], 'Unauthorize access', 403);
        } // end permission checking

        $data['courses'] = $this->model->getOrderById('DESC', 25);
        return $this->success($data);
    }

    public function approved()
    {
        if (!Auth::user()->hasPermissionTo('approved_course', 'web')) {
            return $this->error([], 'Unauthorize access', 403);
        } // end permission checking

        $data['courses'] = Course::where('status', 1)->paginate(25);
        return $this->success($data);
    }

    public function reviewPending()
    {
        if (!Auth::user()->hasPermissionTo('pending_course', 'web')) {
            return $this->error([], 'Unauthorize access', 403);
        } // end permission checking

        $data['courses'] = Course::where('status', 2)->paginate(25);
        return $this->success($data);
    }

    public function hold()
    {
        if (!Auth::user()->hasPermissionTo('pending_course', 'web')) {
            return $this->error([], 'Unauthorize access', 403);
        } // end permission checking

        $data['courses'] = Course::where('status', 3)->paginate(25);
        return $this->success($data);
    }

    public function courseEnroll()
    {
        $data['users'] = User::where('role','!=', 1)->get();
        $data['courses'] = Course::all();

        return $this->success($data);
    }

    public function courseEnrollStore(Request $request)
    {
        $request->validate([
            'user_id' => 'required',
            'course_id' => 'required',
            'expired_after_days' => 'bail|nullable|integer|min:1',
        ]);

        if ($request->course_id) {
            $courseOrderExits = Enrollment::where(['user_id' => $request->user_id, 'course_id' => $request->course_id, 'status' => ACCESS_PERIOD_ACTIVE])->whereDate('end_date', '>=', now())->first();

            if ($courseOrderExits) {
                $order = Order::find($courseOrderExits->order_id);
                if ($order) {
                    if ($order->payment_status == 'due') {
                        Order_item::whereOrderId($courseOrderExits->order_id)->get()->map(function ($q) {
                            $q->delete();
                        });
                        $order->delete();
                    } else {
                        return $this->success([], __("Student has already purchased the course!"));
                    }
                }
            }
        }

        $ownCourseCheck = CourseInstructor::where('course_id', $request->course_id)->where('instructor_id', $request->user_id)->delete();

        if ($ownCourseCheck) {
            return $this->success([], __("He is a owner of the course. Can't purchase this course!"));
        }
        $course = Course::find($request->course_id);
        $order = new Order();
        $order->user_id = $request->user_id;
        $order->order_number = rand(100000, 999999);
        $order->payment_status = 'free';
        $order->created_by_type = 2;
        $order->save();

        $order_item = new Order_item();
        $order_item->order_id = $order->id;
        $order_item->user_id = $request->user_id;
        $order_item->course_id = $request->course_id;
        $order_item->owner_user_id = $course->user_id ?? null;
        $order_item->unit_price = 0;
        $order_item->admin_commission = 0;
        $order_item->owner_balance = 0;
        $order_item->sell_commission = 0;
        $order_item->save();
       
        
        set_instructor_ranking_level($course->user_id);
        
        /** ====== Send notification =========*/
        $text = __("New student enrolled");
        $target_url = route('instructor.all-student');
        foreach ($order->items as $item)
        {
            if ($item->course)
            {
                $this->send($text, 2, $target_url, $item->course->user_id);
            }
            
            $expiredDays = !is_null($request->expired_after_days) && $request->expired_after_days > 0 ? $request->expired_after_days : NULL;
            setEnrollment($item, $expiredDays);
        }

        $text = __("Course has been sold");
        $this->send($text, 1, null, null);

        $text = __("New course enrolled by Admin");
        $target_url = route('student.my-learning');
        $this->send($text, 3, $target_url, $request->user_id);

        /** ====== Send notification =========*/
        return $this->success([], __('Student enroll in course successfully'));
    }
}
