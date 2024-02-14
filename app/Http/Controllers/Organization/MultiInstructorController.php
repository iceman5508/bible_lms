<?php

namespace App\Http\Controllers\Organization;

use App\Http\Controllers\Controller;
use App\Models\CourseInstructor;
use App\Traits\General;
use App\Traits\SendNotification;
use Illuminate\Http\Request;

class MultiInstructorController extends Controller
{
    use SendNotification, General;
    public function index(Request $request)
    {
        $data['navInstructorRequestActiveClass'] = "active";
        $data['instructorRequests'] = CourseInstructor::where('instructor_id', auth()->id())->whereHas('course', function ($query){
            $query->where('user_id', '!=', auth()->id());
        })->paginate(10);

        return view('organization.course_organization.index', $data);
    }

    public function changeStatus(Request $request, $id)
    {
        if($request->status == STATUS_ACCEPTED){
            $count = CourseInstructor::join('courses', 'courses.id', '=', 'course_organization.course_id')->where('course_instructor.instructor_id', auth()->id())->groupBy('course_id')->count();
            if(!hasLimitSaaS(PACKAGE_RULE_COURSE, PACKAGE_TYPE_SAAS_INSTRUCTOR, $count)){
                $this->showToastrMessage('error', __('Your Course limit has been finish.'));
                return redirect()->back();
            }
        }

        $courseInstructor = CourseInstructor::where('instructor_id', auth()->id())->where('id', $id)->firstOrFail();
        $courseInstructor->status = $request->status;
        $courseInstructor->save();

        if ($request->status == STATUS_ACCEPTED) {
            $text = __("Co instructor request has been approved");
        }

        if ($request->status == STATUS_REJECTED) {
            $text = __("Co instructor request has been rejected");
        }

        $target_url = route('course-details', $courseInstructor->course->slug);
        $this->send($text, 2, $target_url, $courseInstructor->course->user_id);

        $this->showToastrMessage('success', 'Status has been changed');
        return redirect()->back();
    }
}
