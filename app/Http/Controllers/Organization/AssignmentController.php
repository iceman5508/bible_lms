<?php

namespace App\Http\Controllers\Organization;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\AssignmentSubmit;
use App\Models\Course;
use App\Tools\Repositories\Crud;
use App\Traits\General;
use App\Traits\ImageSaveTrait;
use Dflydev\DotAccessData\Data;
use Illuminate\Http\Request;

class AssignmentController extends Controller
{
    use General, ImageSaveTrait;
    protected $assignmentModel, $courseModel, $assignmentSubmitModel;

    public function __construct(Assignment $assignment, Course $course, AssignmentSubmit $assignmentSubmit)
    {
        $this->assignmentModel = new CRUD($assignment);
        $this->courseModel = new CRUD($course);
        $this->assignmentSubmitModel = new CRUD($assignmentSubmit);
    }

    public function index($course_uuid)
    {
        $data['title'] = 'Assignment';
        $data['navCourseActiveClass'] = 'active';
        $data['course'] = $this->courseModel->getRecordByUuid($course_uuid);
        $data['assignments'] = Assignment::where('course_id', $data['course']->id)->paginate();
        return view('organization.course.assignment.index', $data);
    }

    public function create($course_uuid)
    {
        $data['title'] = 'Assignment Add';
        $data['navCourseActiveClass'] = 'active';
        $data['course'] = $this->courseModel->getRecordByUuid($course_uuid);
        return view('organization.course.assignment.create', $data);
    }

    public function store(Request $request, $course_uuid)
    {
        $request->validate([
            'name' => 'required|max:255',
            'marks' => 'required|integer',
            "file" => "mimes:pdf,zip"
        ]);

        $course = $this->courseModel->getRecordByUuid($course_uuid);

        $assignment = new Assignment();
        $assignment->course_id = $course->id;
        $assignment->name = $request->name;
        $assignment->marks = $request->marks;
        $assignment->description = $request->description;
        $assignment->status = 1;

        if ($request->hasFile('file')){
            $file_details = $this->uploadFileWithDetails('assignment', $request->file);
            if (!$file_details['is_uploaded']) {
                $this->showToastrMessage('error', __('Something went wrong! Failed to upload file'));
                return redirect()->route('organization.assignment.index', $course_uuid);
            }
            $assignment->file = $file_details['path'];
            $assignment->original_filename = $file_details['original_filename'];
        }

        $assignment->save();

        $this->showToastrMessage('success', __('Assignment Created Successfully'));
        return redirect()->route('organization.assignment.index', $course_uuid);
    }

    public function edit($course_uuid, $uuid)
    {
        $data['title'] = 'Assignment Edit';
        $data['navCourseActiveClass'] = 'active';
        $data['course'] = $this->courseModel->getRecordByUuid($course_uuid);
        $data['assignment'] = $this->assignmentModel->getRecordByUuid($uuid);

        return view('organization.course.assignment.edit', $data);
    }

    public function update(Request $request, $course_uuid, $uuid)
    {
        $request->validate([
            'name' => 'required|max:255',
            'marks' => 'required|integer',
            "file" => "mimes:pdf,zip"
        ]);

        $course = $this->courseModel->getRecordByUuid($course_uuid);

        $assignment = $this->assignmentModel->getRecordByUuid($uuid);
        $assignment->course_id = $course->id;
        $assignment->name = $request->name;
        $assignment->marks = $request->marks;
        $assignment->description = $request->description;
        $assignment->status = 1;

        if ($request->hasFile('file')){
            $this->deleteVideoFile($assignment->file);
            $file_details = $this->uploadFileWithDetails('assignment', $request->file);
            if (!$file_details['is_uploaded']) {
                $this->showToastrMessage('error', __('Something went wrong! Failed to upload file'));
                return redirect()->route('organization.assignment.index', $course_uuid);
            }
            $assignment->file = $file_details['path'];
            $assignment->original_filename = $file_details['original_filename'];
        }

        $assignment->save();

        $this->showToastrMessage('success', __('Assignment Updated Successfully'));
        return redirect()->route('organization.assignment.index', $course_uuid);
    }

    public function delete($uuid)
    {
        /*
         * Need to check any student this assignment attended or not.
         * if attended this assignment, need to discuss this assignment will be deleted or not.
         */

        $assignment = $this->assignmentModel->getRecordByUuid($uuid);
        $this->deleteVideoFile($assignment->file);
        $this->assignmentModel->deleteByUuid($uuid);
        $this->showToastrMessage('success', __('Assignment Deleted Successfully'));
        return redirect()->back();
    }

    public function assessmentIndex(Request $request, $course_uuid, $assignment_uuid)
    {
        $data['title'] = 'Assignment';
        $data['navCourseActiveClass'] = 'active';
        $data['course'] = $this->courseModel->getRecordByUuid($course_uuid);
        $data['assignment'] = $this->assignmentModel->getRecordByUuid($assignment_uuid);
        if ($request->done)
        {
            $data['navDoneActive'] = 'active';
            $data['tabDoneActive'] = 'show active';
        } else {
            $data['navPendingActive'] = 'active';
            $data['tabPendingActive'] = 'show active';
        }

        $data['assignmentSubmitsPending'] = AssignmentSubmit::where('assignment_id', $data['assignment']->id)->whereNull('marks')->paginate(15, ['*'], 'pending');
        $data['assignmentSubmitsDone'] = AssignmentSubmit::where('assignment_id', $data['assignment']->id)->whereNotNull('marks')->paginate(15, ['*'], 'done');

        return view('organization.course.assignment.assessment.index', $data);
    }

    public function assessmentSubmitMarkUpdate(Request $request, $assignment_submit_uuid)
    {
        $assignmentSubmit = $this->assignmentSubmitModel->getRecordByUuid($assignment_submit_uuid);
        $assignmentSubmit->marks = $request->marks;
        $assignmentSubmit->notes = $request->notes;
        $assignmentSubmit->save();

        $this->showToastrMessage('success', __('Marks Updated Successfully'));
        return redirect()->back();
    }

    public function studentAssignmentDownload(Request $request)
    {

        $assignmentSubmit = $this->assignmentSubmitModel->getRecordById($request->id);
        if ($assignmentSubmit->file){

            $filepath = getVideoFile($assignmentSubmit->file);
            return response()->download($filepath);
        } else {
            $data['msg'] = __('No File Found!');
            $data['status'] = 404;
            return response()->json([
                'data' => $data
            ]);
        }

    }
}
