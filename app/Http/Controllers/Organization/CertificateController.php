<?php

namespace App\Http\Controllers\Organization;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use App\Models\Certificate_by_instructor;
use App\Models\Course;
use App\Traits\General;
use App\Traits\ImageSaveTrait;
use Illuminate\Http\Request;
use PDF;

class CertificateController extends Controller
{
    use General, ImageSaveTrait;
    public function index()
    {
        $data['navCertificateActiveClass'] = 'active';
        $data['title'] = 'Manage Certificate';
        $data['courses'] = Course::where('user_id', auth()->id())->orderBy('id', 'DESC')->get();
        return view('organization.certificate.index', $data);
    }

    public function add($course_uuid)
    {
        $data['navCertificateActiveClass'] = 'active';
        $data['title'] = 'Add Certificate';
        $data['course'] = Course::whereUuid($course_uuid)->first();
        $data['certificates'] = Certificate::all();
        return view('organization.certificate.add', $data);
    }

    public function setForCreate(Request $request, $course_uuid)
    {
        if (is_null($request->certificate_uuid))
        {
            $this->showToastrMessage('warning', __('Please select certificate'));
            return redirect()->back();
        } else {
            return redirect(route('organization.certificate.create', [$course_uuid, $request->certificate_uuid]));
        }
    }

    public function create($course_uuid, $certificate_uuid)
    {
        $data['navCertificateActiveClass'] = 'active';
        $data['title'] = 'Add Certificate';
        $data['course'] = Course::whereUuid($course_uuid)->first();
        $data['certificate'] = Certificate::whereUuid($certificate_uuid)->first();
        return view('organization.certificate.create', $data);
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

        $course = Course::whereUuid($course_uuid)->first();
        $certificate = Certificate::whereUuid($certificate_uuid)->first();

        $certificate_by_instructor = new Certificate_by_instructor();
        $certificate_by_instructor->fill($request->all());
        $certificate_by_instructor->course_id = $course->id;
        $certificate_by_instructor->certificate_id = $certificate->id;
        $certificate_by_instructor->signature = $request->signature ? $this->saveImage('certificate', $request->signature, '120', '60') :   null;
        $certificate_by_instructor->save();

        $this->showToastrMessage('success', 'Certificate has been created');
        return redirect(route('organization.certificate.edit', [$certificate_by_instructor->uuid]));

    }

    public function edit($uuid)
    {
        $data['navCertificateActiveClass'] = 'active';
        $data['title'] = 'Edit Certificate';
        $data['certificate_by_instructor'] = Certificate_by_instructor::whereUuid($uuid)->firstOrFail();
        $data['certificate'] = Certificate::whereId($data['certificate_by_instructor']->certificate_id)->first();
        $data['course'] = Course::whereId( $data['certificate_by_instructor']->course_id)->first();
        return view('organization.certificate.edit', $data);
    }

    public function update(Request $request, $uuid)
    {
        $certificate_by_instructor = Certificate_by_instructor::whereUuid($uuid)->firstOrFail();
        $certificate = Certificate::find($certificate_by_instructor->certificate_id);

        $certificate_by_instructor->fill($request->all());
        if ($request->signature)
        {
            $this->deleteFile($certificate_by_instructor->signature);
            $certificate_by_instructor->signature =$this->saveImage('certificate', $request->signature, '120', '60') ;
        }
        $certificate_by_instructor->save();


        // $this->deleteFile( $certificate_by_instructor->path);

        // /** === make pdf certificate ===== */
        // $certificate_name = 'certificate-' . $certificate_by_instructor->uuid. '.pdf';
        // // make sure email invoice is checked.
        // $customPaper = array(0, 0, 612, 500);
        // $pdf = PDF::loadView('organization.certificate.pdf', ['certificate' => $certificate, 'certificate_by_instructor' => $certificate_by_instructor])->setPaper($customPaper, 'portrait');
        // //return $pdf->stream($certificate_name);
        // $pdf->save(public_path() . '/uploads/certificate/' . $certificate_name);
        // /** === make pdf certificate ===== */
        // $certificate_by_instructor->path =  "/uploads/certificate/$certificate_name";
        // $certificate_by_instructor->save();

        $this->showToastrMessage('success', __('Certificate has been saved'));
        return redirect()->back();
    }

    public function view($uuid)
    {
        $data['navCertificateActiveClass'] = 'active';
        $data['title'] = 'View Certificate';
        $data['certificate_by_instructor'] = Certificate_by_instructor::whereUuid($uuid)->firstOrFail();
        $data['certificate'] = Certificate::whereId($data['certificate_by_instructor']->certificate_id)->first();
        $data['course'] = Course::whereId( $data['certificate_by_instructor']->course_id)->first();
        return view('organization.certificate.view', $data);
    }
}
