<?php

namespace App\Http\Controllers;

use App\Models\Student_certificate;
use Illuminate\Http\Request;

class CertificateVerifyController extends Controller
{

    /**
     * Show the validation page.
     *
     */
    public function verifyCertificate(Request $request)
    {
        $data['pageTitle'] = __('Verify Certificate');
        if ($request->wantsJson()) {
            $certificate = Student_certificate::where('certificate_number', $request->certificate_number)->with(['student', 'course'])->first();
            if ($request->certificate_number == NULL || is_null($certificate)) {
                $data['certificate'] = $certificate;
                $html = view('frontend.partials.certificate_view', $data)->render();
                return response()->json(array('success' => true, 'data' => $html));
            }
            else{

                $data['certificate'] = $certificate;
                $html = view('frontend.partials.certificate_view', $data)->render();

                return response()->json(array('success' => true, 'data' => $html));
            }
        } else {
            return view('frontend.certificate_verify', $data);
        }
    }
}
