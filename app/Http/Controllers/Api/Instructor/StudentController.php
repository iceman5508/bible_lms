<?php

namespace App\Http\Controllers\Api\Instructor;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\User;
use App\Traits\ApiStatusTrait;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    use ApiStatusTrait;
    
    public function allStudentIndex(Request $request)
    {
        $students = User::join('enrollments', 'enrollments.user_id', 'users.id');
        //Start:: Course search
        if ($request->course_id){
            $students = $students->where('enrollments.course_id', $request->course_id);
        }

        $data['students'] = $students->whereNotNull('enrollments.course_id')->where('enrollments.owner_user_id', auth()->id())->select('users.*')->with(['enrollments' => function($q){
            $q->where('owner_user_id', auth()->id());
        }])->paginate();

        return $this->success($data);
    }

}
