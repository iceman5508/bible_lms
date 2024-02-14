<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseResource;
use App\Tools\Repositories\Crud;
use App\Traits\General;
use App\Traits\ImageSaveTrait;
use Illuminate\Http\Request;

class ResourceController extends Controller
{
    use General, ImageSaveTrait;

    protected $resourceModel, $courseModel;

    public function __construct(CourseResource $resources, Course $course)
    {
        $this->resourceModel = new CRUD($resources);
        $this->courseModel = new CRUD($course);
    }

    public function index($course_uuid)
    {
        $data['title'] = 'Resources';
        $data['navCourseActiveClass'] = 'active';
        $data['course'] = $this->courseModel->getRecordByUuid($course_uuid);
        $data['resources'] = CourseResource::where('course_id', $data['course']->id)->paginate();

        return view('instructor.course.resources.index', $data);
    }

    public function create($course_uuid)
    {
        $data['title'] = 'Resource Add';
        $data['navCourseActiveClass'] = 'active';
        $data['course'] = $this->courseModel->getRecordByUuid($course_uuid);
        return view('instructor.course.resources.create', $data);
    }

    public function store(Request $request, $course_uuid)
    {
        $request->validate([
            "file" => "required|mimes:zip"
        ]);

        $course = $this->courseModel->getRecordByUuid($course_uuid);

        $resource = new CourseResource();
        $resource->course_id = $course->id;

        if ($request->hasFile('file')) {
            $image_details = $this->uploadFileWithDetails('course_resource', $request->file);
            if (!$image_details['is_uploaded']) {
                $this->showToastrMessage('error', __('Something went wrong! Failed to upload file'));
                return redirect()->route('resource.index', $course_uuid);
            }
            $resource->file = $image_details['path'];
            $resource->original_filename = $image_details['original_filename'];
        }

        $resource->save();

        $this->showToastrMessage('success', __('Resource Created Successfully'));
        return redirect()->route('resource.index', $course_uuid);
    }

    public function delete($uuid)
    {
        $resource = $this->resourceModel->getRecordByUuid($uuid);
        $this->deleteVideoFile($resource->file);
        $this->resourceModel->deleteByUuid($uuid);
        $this->showToastrMessage('success', __('Resource Deleted Successfully'));
        return redirect()->back();
    }
}
