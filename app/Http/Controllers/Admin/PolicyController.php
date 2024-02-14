<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Policy;
use App\Traits\General;
use Illuminate\Http\Request;

class PolicyController extends Controller
{
    use General;
    public function privacyPolicy()
    {
        $data['title'] = 'Privacy Policy';
        $data['navPolicyActiveClass'] = 'mm-active';
        $data['subNavPrivacyPolicyActiveClass'] = 'mm-active';
        $data['policy'] = Policy::whereType(1)->first();

        return view('admin.policy.privacy-policy', $data);
    }

    public function privacyPolicyStore(Request $request)
    {
        $policy = Policy::whereType(1)->first();
        if (!$policy)
        {
            $policy = new Policy();
        }

        $policy->type = 1;
        $policy->description = $request->description;
        $policy->save();

        $this->showToastrMessage('success', __('Updated Successfully'));
        return redirect()->back();

    }

    public function cookiePolicy()
    {
        $data['title'] = 'Cookie Policy';
        $data['navPolicyActiveClass'] = 'mm-active';
        $data['subNavCookiePolicyActiveClass'] = 'mm-active';
        $data['policy'] = Policy::whereType(2)->first();

        return view('admin.policy.cookie-policy', $data);
    }

    public function cookiePolicyStore(Request $request)
    {
       $policy = Policy::whereType(2)->first();
       if (!$policy)
       {
           $policy = new Policy();
       }

       $policy->type = 2;
       $policy->description = $request->description;
       $policy->save();

       $this->showToastrMessage('success', __('Updated Successfully'));
       return redirect()->back();
    }
    
    public function refundPolicy()
    {
        $data['title'] = 'Refund Policy';
        $data['navPolicyActiveClass'] = 'mm-active';
        $data['subNavRefundPolicyActiveClass'] = 'mm-active';
        $data['policy'] = Policy::whereType(4)->first();

        return view('admin.policy.refund-policy', $data);
    }

    public function refundPolicyStore(Request $request)
    {
       $policy = Policy::whereType(4)->first();
       if (!$policy)
       {
           $policy = new Policy();
       }

       $policy->type = 4;
       $policy->description = $request->description;
       $policy->save();

       $this->showToastrMessage('success', __('Updated Successfully'));
       return redirect()->back();
    }

    public function termConditions()
    {
        $data['title'] = 'Terms & Conditions';
        $data['navTermConditionsActiveClass'] = 'mm-active';
        $data['subNavTermConditionsActiveClass'] = 'mm-active';
        $data['policy'] = Policy::whereType(3)->first();

        return view('admin.policy.terms-conditions', $data);
    }

    public function termConditionsStore(Request $request)
    {
        $policy = Policy::whereType(3)->first();
        if (!$policy)
        {
            $policy = new Policy();
        }

        $policy->type = 3;
        $policy->description = $request->description;
        $policy->save();

        $this->showToastrMessage('success', __('Updated Successfully'));
        return redirect()->back();

    }

}
