<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use App\Traits\ApiStatusTrait;
use App\Traits\ImageGenerateFromHTML;
use App\Traits\ImageSaveTrait;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Auth;


class CertificateController extends Controller
{
    use ApiStatusTrait, ImageSaveTrait, ImageGenerateFromHTML;

    public function index()
    {
        if (!Auth::user()->hasPermissionTo('manage_certificate', 'web')) {
            return $this->error([], 'Unauthorize access', 403);
        } // end permission checking
        
        $data['certificates'] = Certificate::paginate(25);
        return $this->success($data);
    }


    public function store(Request $request)
    {
        if (!Auth::user()->hasPermissionTo('manage_certificate', 'web')) {
            return $this->error([], 'Unauthorize access', 403);
        } // end permission checking

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

        $certificate = view('admin.certificate.view')->with(['certificate' => $certificate])->render();
        return $this->success(['certificate' => $certificate]);
    }

    public function update(Request $request, $uuid)
    {
        if (!Auth::user()->hasPermissionTo('manage_certificate', 'web')) {
            return $this->error([], 'Unauthorize access', 403);
        } // end permission checking

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

        $certificate = view('admin.certificate.view')->with(['certificate' => $certificate])->render();
        return $this->success(['certificate' => $certificate]);
    }
}
