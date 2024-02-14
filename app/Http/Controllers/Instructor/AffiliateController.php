<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Models\AffiliateRequest;
use App\Traits\General;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AffiliateController extends Controller
{
    use General;
    public function becomeAffiliate()
    {
        $data['title'] = 'Become Affiliate';
        $data['navAffiliateActiveClass'] = 'has-open';
        $data['subNavBecomeAffiliateActiveClass'] = 'active';
        return view('instructor.affiliate.create', $data);
    }
    public function requestList()
    {
        $data['title'] = 'Request List';
        $data['navAffiliateActiveClass'] = 'has-open';
        $data['subNavAffiliateListActiveClass'] = 'active';
        $data['requests'] = AffiliateRequest::where(['user_id'=>Auth::id()])->paginate();
        return view('instructor.affiliate.request-list', $data);
    }

    public function becomeAffiliateApply(Request $request)
    {

        $obj = AffiliateRequest::whereUserId(Auth::id())->where(['status' => STATUS_PENDING])->first();
        if(!is_null($obj)){
            $this->showToastrMessage('error', __('You already have a pending request'));
            return redirect()->back();
        }
        $obj = AffiliateRequest::whereUserId(Auth::id())->where(['status' => STATUS_APPROVED])->first();
        if(!is_null($obj)){
            $this->showToastrMessage('error', __('You already have a Approved request'));
            return redirect()->back();
        }
        if (is_null($obj)) {
            $obj = new AffiliateRequest();
        }

        $obj->user_id = Auth::id();
        $obj->status = STATUS_PENDING;
        $obj->address = $request->address;
        $obj->save();

        $this->showToastrMessage('success', __('Request Created Successfully'));
        return redirect()->back();
    }
}
