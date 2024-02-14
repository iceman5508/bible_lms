<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use App\Traits\General;
use App\Traits\ImageGenerateFromHTML;
use App\Traits\ImageSaveTrait;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Auth;


class CertificateController extends Controller
{
    use General, ImageSaveTrait, ImageGenerateFromHTML;

    public function index()
    {
        if (!Auth::user()->can('manage_certificate')) {
            abort('403');
        } // end permission checking

        $data['title'] = ' Certificates';
        $data['navCertificateActiveClass'] = "mm-active";
        $data['subNavAllCertificateActiveClass'] = "mm-active";
        $data['certificates'] = Certificate::paginate(25);
        return view('admin.certificate.index')->with($data);
    }


    public function create()
    {
        if (!Auth::user()->can('manage_certificate')) {
            abort('403');
        } // end permission checking

        $data['title'] = 'Add Certificate';
        $data['navCertificateActiveClass'] = "mm-active";
        $data['subNavAddCertificateActiveClass'] = "mm-active";
        return view('admin.certificate.create')->with($data);
    }


    public function store(Request $request)
    {
        if (!Auth::user()->can('manage_certificate')) {
            abort('403');
        } // end permission checking

        // $request->validate([
        //     'show_number' => 'required',
        //     'number_y_position' => 'required',
        //     'number_font_size' => 'required',
        //     'title' => 'required',
        //     'title_y_position' => 'required',
        //     'title_font_size' => 'required',
        //     'show_date' => 'required',
        //     'date_y_position' => 'required',
        //     'date_font_size' => 'required',
        //     'show_student_name' => 'required',
        //     'student_name_y_position' => 'required',
        //     'student_name_font_size' => 'required',
        //     'body' => 'required',
        //     'body_y_position' => 'required',
        //     'body_font_size' => 'required',
        //     'role_1_title' => 'required',
        //     'role_1_y_position' => 'required',
        //     'role_1_font_size' => 'required',
        //     'role_2_title' => 'required',
        //     'role_2_y_position' => 'required',
        //     'role_2_font_size' => 'required',
        //     'background_image' => 'required|mimes:jpg,png|file|dimensions:min_width=1030,min_height=734,max_width=1030,max_height=734',
        //     'role_1_signature' => 'required|mimes:png|file|dimensions:min_width=120,min_height=60,max_width=120,max_height=60',
        // ]);

        $request->validate([
            'background_image' => 'nullable|mimes:jpg,png|file|dimensions:min_width=1030,min_height=734,max_width=1030,max_height=734',
            'role_1_signature' => 'nullable|mimes:png|file|dimensions:min_width=120,min_height=60,max_width=120,max_height=60',
        ]);


        $certificate = Certificate::where('status', CERTIFICATE_DRAFT)->first();
        if (is_null($certificate)) {
            $certificate = new Certificate();
            $certificate->uuid = Str::uuid()->toString();
            $certificate->certificate_number = rand(1000000, 9999999);
        }

        $certificate->fill($request->all());

        if ($request->hasFile('background_image')) {
            $certificate->image = $request->background_image ? $this->saveImage('certificate', $request->background_image, null, null) :   null;
        }
        
        if ($request->hasFile('role_1_signature')) {
            $certificate->role_1_signature = $request->role_1_signature ? $this->saveImage('certificate', $request->role_1_signature, null, null) :   null;
        }

        if (!$request->final_submit) {
            $certificate->status = CERTIFICATE_DRAFT;
        } else {
            $certificate->status = CERTIFICATE_VALID;
        }

        $certificate->save();

        if (!$request->final_submit) {
            $certificate = view('admin.certificate.view')->with(['certificate' => $certificate])->render();
            return response()->json(array('success' => true, 'certificate' => $certificate));
        }
        else{
            return response()->json(array('success' => true, 'view' => route('certificate.edit', $certificate->uuid)));
        }
    }

    public function edit($uuid)
    {
        if (!Auth::user()->can('manage_certificate')) {
            abort('403');
        } // end permission checking

        $data['title'] = 'View Certificate';
        $data['navCertificateActiveClass'] = "mm-active";
        $data['subNavAddCertificateActiveClass'] = "mm-active";
        $data['certificate'] = Certificate::whereUuid($uuid)->first();
        return view('admin.certificate.edit')->with($data);
    }

    public function update(Request $request, $uuid)
    {
        if (!Auth::user()->can('manage_certificate')) {
            abort('403');
        } // end permission checking

        // $request->validate([
        //     'show_number' => 'required',
        //     'number_y_position' => 'required',
        //     'number_font_size' => 'required',
        //     'title' => 'required',
        //     'title_y_position' => 'required',
        //     'title_font_size' => 'required',
        //     'show_date' => 'required',
        //     'date_y_position' => 'required',
        //     'date_font_size' => 'required',
        //     'show_student_name' => 'required',
        //     'student_name_y_position' => 'required',
        //     'student_name_font_size' => 'required',
        //     'body' => 'required',
        //     'body_y_position' => 'required',
        //     'body_font_size' => 'required',
        //     'role_1_title' => 'required',
        //     'role_1_y_position' => 'required',
        //     'role_1_font_size' => 'required',
        //     'role_2_title' => 'required',
        //     'role_2_y_position' => 'required',
        //     'role_2_font_size' => 'required',
        //     'background_image' => 'mimes:jpg,png|file|dimensions:min_width=1030,min_height=734,max_width=1030,max_height=734',
        //     'role_1_signature' => 'mimes:png|file|dimensions:min_width=120,min_height=60,max_width=120,max_height=60',
        // ]);

        $request->validate([
            'background_image' => 'nullable|mimes:jpg,png|file|dimensions:min_width=1030,min_height=734,max_width=1030,max_height=734',
            'role_1_signature' => 'nullable|mimes:png|file|dimensions:min_width=120,min_height=60,max_width=120,max_height=60',
        ]);

        $certificate = Certificate::whereUuid($uuid)->first();
        $certificate->fill($request->all());
        if ($request->background_image) {
            $this->deleteFile($certificate->image);
            $certificate->image = $this->saveImage('certificate', $request->background_image, '1030', '730');
        }

        if ($request->role_1_signature) {
            $this->deleteFile($certificate->role_1_signature);
            $certificate->role_1_signature = $this->saveImage('certificate', $request->role_1_signature, '120', '60');
        }

        $certificate->save();

        if (!$request->final_submit) {
            $certificate = view('admin.certificate.view')->with(['certificate' => $certificate])->render();
            return response()->json(array('success' => true, 'certificate' => $certificate));
        }
        else{
            return response()->json(array('success' => true, 'view' => route('certificate.edit', $certificate->uuid)));
        }
    }

    public function delete($uuid)
    {
        if (!Auth::user()->can('manage_certificate')) {
            abort('403');
        } // end permission checking

        $certificate = Certificate::whereUuid($uuid)->first();
        $this->deleteFile($certificate->image);
        $this->deleteFile($certificate->role_1_signature);
        $this->deleteFile($certificate->path);
        $certificate->delete();

        $this->showToastrMessage('error', __('Certificate has been deleted'));
        return redirect()->back();
    }
}
