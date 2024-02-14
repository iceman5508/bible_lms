<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Traits\General;
use App\Models\Package;
use App\Models\UserPackage;
use App\Models\WalletRecharge;
use App\Traits\ImageSaveTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class WalletRechargeController extends Controller
{
    use General, ImageSaveTrait;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (!auth()->user()->can('manage_wallet_recharge')) {
                abort('403');
            } // end permission checking
            
            return $next($request);
        });
    }

    public function list()
    {
        $data['title'] = __('Wallet Recharge List');
        $data['navWalletParentActiveClass'] = 'mm-active';
        $data['navWalletParentShowClass'] = 'mm-show';
        $data['subNavWalletActiveClass'] = 'mm-active';
        $data['walletRecharges'] = WalletRecharge::with('payment:id,payment_id,grand_total')->paginate();
        return view('admin.wallet_recharge.list', $data);
    }
    
    public function pendingList()
    {
        $data['title'] = __('Wallet Recharge Pending List');
        $data['navWalletParentActiveClass'] = 'mm-active';
        $data['navWalletParentShowClass'] = 'mm-show';
        $data['subNavWalletActiveClass'] = 'mm-active';
        $data['walletRecharges'] = WalletRecharge::where('status', STATUS_PENDING)->with('payment:id,payment_id,grand_total')->with('payment.bank')->paginate();
        return view('admin.wallet_recharge.pending_list', $data);
    }

    public function changeStatus(Request $request)
    {
        $walletRecharge = WalletRecharge::whereId($request->id)->first();
        if(($request->status == STATUS_ACCEPTED || $request->status == STATUS_REJECTED) && $walletRecharge->status == STATUS_PENDING){
            if($request->status == STATUS_ACCEPTED){
                $walletRecharge->payment->update(['payment_status' => 'paid']);
                createTransaction(auth()->id(), $walletRecharge->payment->sub_total, TRANSACTION_WALLET_RECHARGE, 'Wallet Recharge', 'Recharge (' . $walletRecharge->id . ')', $walletRecharge->id);
                $walletRecharge->payment->user->increment('balance', decimal_to_int($walletRecharge->payment->sub_total));
            }
            else{
                $walletRecharge->payment->update(['payment_status' => 'cancel']);
            }
            
            $walletRecharge->update(['status' => $request->status]);
        }
        else{
            return response()->json([
                'data' => 'success',
            ]);
        }


        return response()->json([
            'data' => 'success',
        ]);
    }

}
