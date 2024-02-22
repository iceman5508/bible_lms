<?php

namespace App\Http\Controllers\Api\Instructor;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use App\Models\Certificate_by_instructor;
use App\Models\Course;
use App\Traits\General;
use App\Traits\ImageSaveTrait;
use Illuminate\Http\Request;
use App\Traits\ApiStatusTrait;
use PDF;

class CertificateController extends Controller
{
    use General, ImageSaveTrait, ApiStatusTrait;
    public function index()
    {
        $data['courses'] = Course::where('user_id', auth()->id())->with('certificate')->orderBy('id', 'DESC')->get();
        return $this->success($data);
    }
    
    public function add($course_uuid)
    {
        $data['course'] = Course::whereUuid($course_uuid)->first();
        $data['certificates'] = Certificate::all();
        return $this->success($data);
    }

    public function setForCreate(Request $request, $course_uuid)
    {
        if (is_null($request->certificate_uuid))
        {
            $this->showToastrMessage('warning', __('Please select certificate'));
            return redirect()->back();
        } else {
            return redirect(route('instructor.certificate.create', [$course_uuid, $request->certificate_uuid]));
        }
    }

    public function create($course_uuid, $certificate_uuid)
    {
        $data['navCertificateActiveClass'] = 'active';
        $data['title'] = 'Add Certificate';
        $data['course'] = Course::whereUuid($course_uuid)->first();
        $data['certificate'] = Certificate::whereUuid($certificate_uuid)->first();
        return view('instructor.certificate.create', $data);
    }

    public function store(Request $request, $course_uuid, $certificate_uuid)
    {
        $request->validate([
            'title' => 'required',
            'title_y_position' => 'required',
            'title_x_position' => 'required',
            'title_font_size' => 'required',
            'body' => 'required',
            'body_x_position' => 'required',
            'body_y_position' => 'required',
            'body_font_size' => 'required',
            'role_2_x_position' => 'nullable',
            'role_2_y_position' => 'nullable',
            'signature' => 'mimes:png|file|dimensions:min_width=120,min_height=60,max_width=120,max_height=60',
        ]);

        try{
            $course = Course::whereUuid($course_uuid)->first();
            $certificate = Certificate::whereUuid($certificate_uuid)->first();
    
            $certificate_by_instructor = new Certificate_by_instructor();
            $certificate_by_instructor->fill($request->all());
            $certificate_by_instructor->course_id = $course->id;
            $certificate_by_instructor->certificate_id = $certificate->id;
            $certificate_by_instructor->signature = $request->signature ? $this->saveImage('certificate', $request->signature, '120', '60') :   null;
            $certificate_by_instructor->save();

            return $this->success();
        }
        catch(\Exception $e){
            return $this->failed([], $e->getMessage());
        }

    }

    public function view($uuid)
    {
        $data['certificate_by_instructor'] = Certificate_by_instructor::whereUuid($uuid)->firstOrFail();
        $data['certificate'] = Certificate::whereId($data['certificate_by_instructor']->certificate_id)->first();
        $data['course'] = Course::whereId( $data['certificate_by_instructor']->course_id)->first();
        
        return $this->success($data);
    }
}
