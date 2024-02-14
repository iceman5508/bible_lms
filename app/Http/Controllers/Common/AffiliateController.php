<?php

namespace App\Http\Controllers\Common;

use App\Http\Controllers\Controller;
use App\Models\AffiliateHistory;
use App\Models\AffiliateRequest;
use App\Traits\General;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AffiliateController extends Controller
{
    use General;
    public function dashboard()
    {
        $data['pageTitle'] = 'Affiliate';
        $affiliate = AffiliateHistory::where(['user_id'=> auth()->user()->id, 'status' => AFFILIATE_HISTORY_STATUS_PAID])->sum('actual_price');
        $earnings = AffiliateHistory::where(['user_id'=> auth()->user()->id, 'status' => AFFILIATE_HISTORY_STATUS_PAID])->sum('commission');
        $count = AffiliateHistory::where(['user_id'=> auth()->user()->id, 'status' => AFFILIATE_HISTORY_STATUS_PAID])->count();
        $data['totalAffiliate'] = get_number_format($affiliate);
        $data['totalCommission'] = get_number_format($earnings);
        $data['totalAffiliateCount'] = $count;
        return view('frontend.affiliator.affiliate-dashboard', $data);
    }
    public function myAffiliations(Request $request){
        if($request->ajax()) {
            $aff = AffiliateHistory::where(['user_id'=>Auth::id(), 'status' => AFFILIATE_HISTORY_STATUS_PAID]);
            return datatables($aff)
                ->addColumn('course_name', function ($item) {
                    return $item->course->title;
                })
                ->addColumn('actual_price', function ($item) {
                    if(get_currency_placement() == 'after') {
                        return $item->actual_price . ' ' . get_currency_symbol();
                    } else {
                        return get_currency_symbol() . ' ' . $item->actual_price;
                    }

                })
                ->addColumn('commission', function ($item) {
                    if(get_currency_placement() == 'after') {
                        return $item->commission . ' ' . get_currency_symbol();
                    } else {
                        return get_currency_symbol() . ' ' . $item->commission;
                    }

                })
                ->addColumn('date', function ($item) {
                    return $item->created_at->format('Y-m-d H:i:s');
                })
                ->make(true);
        }
        return view('admin.coin.list');
    }

    public function becomeAffiliate()
    {
        $data['title'] = 'Become an affiliator';
        $data['pageTitle'] = 'Affiliate';
        return view('frontend.affiliator.become-an-affiliator', $data);
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
        DB::beginTransaction();
        try {
            $obj->user_id = Auth::id();
            $obj->status = STATUS_PENDING;
            $obj->address = $request->address;
            $obj->letter = $request->letter;
            $obj->affiliate_code = Str::uuid()->getHex();
            $obj->save();

            $user = Auth::user();
            $user->is_affiliator = AFFILIATE_REQUEST_PENDING;
            $user->save();
            DB::commit();
        }catch (\Exception $e) {
            DB::rollBack();
        }
        $this->showToastrMessage('success', __('Request Created Successfully'));
        return redirect()->back();
    }
}
