<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Enrollment;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function allStudentIndex(Request $request)
    {
        $data['title'] = 'All Student';
        $data['navAllStudentActiveClass'] = 'active';

        $data['courses'] = Course::whereUserId(auth()->user()->id)->get();
        $userCourseIds = Course::whereUserId(auth()->user()->id)->pluck('id')->toArray();

        $enrollments = Enrollment::whereIn('course_id', $userCourseIds);

        //Start:: Course search
        if ($request->course_id){
            $enrollments = $enrollments->whereCourseId($request->course_id);
        }
        //End:: Course search

        $data['enrollments'] = $enrollments->with('user')->with('course')->paginate();

        return view('instructor.all-student')->with($data);
    }

}
