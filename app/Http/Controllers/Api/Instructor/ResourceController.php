<?php

namespace App\Http\Controllers\Api\Instructor;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseResource;
use App\Tools\Repositories\Crud;
use App\Traits\ApiStatusTrait;
use App\Traits\General;
use App\Traits\ImageSaveTrait;
use Illuminate\Http\Request;

class ResourceController extends Controller
{
    use General, ImageSaveTrait, ApiStatusTrait;

    protected $resourceModel, $courseModel;

    public function __construct(CourseResource $resources, Course $course)
    {
        $this->resourceModel = new CRUD($resources);
        $this->courseModel = new CRUD($course);
    }

    public function index($course_uuid)
    {
        $data['course'] = $this->courseModel->getRecordByUuid($course_uuid);
        $data['resources'] = CourseResource::where('course_id', $data['course']->id)->paginate();
        return $this->success($data);
    }

    public function store(Request $request, $course_uuid)
    {
        try{
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
                    return $this->failed([], __('Something went wrong! Failed to upload file'));
                }
                $resource->file = $image_details['path'];
                $resource->original_filename = $image_details['original_filename'];
            }
    
            $resource->save();
            return $this->success([], __("Successfully Added"));
        }
        catch(\Exception $e){
            return $this->failed([], $e->getMessage());
        }
    }
}
