<?php

namespace App\Http\Controllers;

use App\Models\AssignmentSubmit;
use App\Models\Enrollment;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StudentReport extends Controller
{
    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    function index(){
        $students = Student::paginate(10);

        return view('Reporting.index', ['students' => $students]);
    }

    /**
     * Get student report
     * @param Request $request
     * @param Student $student
     * @return void
     */
    function run($uuid){
        $student =  Student::where('uuid', $uuid)->firstOrfail();

        $enrollments = Enrollment::where('user_id', $student->user_id)->get();

        $report_matrix = [];


        foreach ($enrollments as $enrollment){
            $course = $enrollment->course;

            $assignments = $course->assignments;

            $matrix = [];

            foreach ($assignments as $assignment){
                $matrix[$assignment->id]['course'] = $course->title;
                $matrix[$assignment->id]['name'] = $assignment->name;

                $assignmentSubmit = AssignmentSubmit::whereUserId($student->user_id)->whereCourseId($course->id)->whereAssignmentId($assignment->id)->first();
               if(!empty($assignmentSubmit)){
                   $matrix[$assignment->id]['marks'] = $assignmentSubmit['marks'];
                   $matrix[$assignment->id]['submitted'] = 'Yes';
               }else{
                   $matrix[$assignment->id]['marks'] = 0;
                   $matrix[$assignment->id]['submitted'] = 'No';
               }

            }
            $report_matrix[] = $matrix;
        }

        return view('Reporting.view', ['student' => $student, 'reports' => $report_matrix]);
    }
}
