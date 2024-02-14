<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AffiliateHistory;
use App\Models\AffiliateRequest;
use App\Models\User;
use App\Tools\Repositories\Crud;
use App\Traits\General;
use App\Traits\ImageSaveTrait;
use App\Traits\SendNotification;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AffiliateController extends Controller
{
    use General, ImageSaveTrait,SendNotification;

    protected $model;
    public function __construct(AffiliateRequest $blog)
    {
        $this->model = new Crud($blog);
    }


    public function affiliateRequestStatusChange(Request $request)
    {
        if (!Auth::user()->can('manage_affiliate')) {
            abort('403');
        } // end permission checking

        DB::beginTransaction();
        try {
            $req = AffiliateRequest::findOrFail($request->id);
            $req->status = $request->status;


            if ($req->status == STATUS_APPROVED) {
                $user = User::where(['id' => $req->user_id])->first();
                $user->is_affiliator = AFFILIATOR;
                $user->save();
            } else if($req->status == STATUS_REJECTED) {
                $req->comments = $request->note;
                $user = User::where(['id' => $req->user_id])->first();
                $user->is_affiliator = AFFILIATE_REQUEST_REJECTED;
                $user->save();
                $this->send($request->note, 2,'', $req->user_id);

            }else{
                $user = User::where(['id' => $req->user_id])->first();
                $user->is_affiliator = NOT_AFFILIATOR;
                $user->save();
            }
            $req->save();
            DB::commit();
        }catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'data' => 'error',
                'message' => $e->getMessage(),
            ]);

        }
        return response()->json([
            'data' => 'success',
            'message' => 'success',
        ]);
    }


    public function affiliateRequestList()
    {
        if (!Auth::user()->can('manage_affiliate')) {
            abort('403');
        } // end permission checking

        $data['title'] = ' Affiliate Manage';
        $data['navAffiliateManageParentActiveClass'] = 'mm-active';
        $data['subNavAffiliateManageListActiveClass'] = 'mm-active';

        $data['requestsAll'] = AffiliateRequest::select('affiliate_request.*')->join('users',['users.id' => 'affiliate_request.user_id'])->get();
        $data['requestsApproved'] = AffiliateRequest::select('affiliate_request.*')->join('users',['users.id' => 'affiliate_request.user_id'])->where(['affiliate_request.status'=>STATUS_APPROVED])->get();
        $data['requestsSuspend'] = AffiliateRequest::select('affiliate_request.*')->join('users',['users.id' => 'affiliate_request.user_id'])->where(['affiliate_request.status'=>STATUS_REJECTED])->get();
        $data['requestsPending'] = AffiliateRequest::select('affiliate_request.*')->join('users',['users.id' => 'affiliate_request.user_id'])->where(['affiliate_request.status'=>STATUS_PENDING])->get();
        return view('admin.affiliate.affiliate-list', $data);

    }

    public function affiliateHistory()
    {
        if (!Auth::user()->can('manage_affiliate')) {
            abort('403');
        } // end permission checking

        $data['title'] = ' Affiliate History';
        $data['navAffiliateManageParentActiveClass'] = 'mm-active';
        $data['subNavAffiliateHistoryActiveClass'] = 'mm-active';


        return view('admin.affiliate.affiliator-history', $data);

    }

    public function allAffiliates(Request $request){
        if($request->ajax()) {
            $aff = AffiliateHistory::join('users','users.id','=','affiliate_history.user_id')
            ->join('courses','courses.id','=','affiliate_history.course_id')->where('affiliate_history.status', AFFILIATE_HISTORY_STATUS_PAID);
            return datatables($aff)
               ->addColumn('actual_price', function ($item) {
                    if(get_currency_placement() == 'after') {
                        return $item->actual_price . ' ' . get_currency_symbol();
                    } else {
                        return get_currency_symbol() . ' ' . $item->actual_price;
                    }
                })
               ->addColumn('type', function ($item) {
                    return getUserType($item->role);
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



}
