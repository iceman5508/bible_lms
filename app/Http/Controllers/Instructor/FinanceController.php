<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Models\AffiliateHistory;
use App\Models\BookingHistory;
use App\Models\Bundle;
use App\Models\Course;
use App\Models\Order_item;
use App\Models\Withdraw;
use App\Traits\General;
use App\Traits\SendNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class FinanceController extends Controller
{
    use General, SendNotification;

    public function analysisIndex()
    {
        $data['navFinanceActiveClass'] = 'has-open';
        $data['subNavAnalysisActiveClass'] = 'active';
        $data['title'] = 'Analysis';

        $data['total_courses'] = Course::whereUserId(auth()->user()->id)->count();

        //Start::  Cancel Consultation Money Calculation
        $cancelConsultationOrderItemIds = BookingHistory::whereStatus(BOOKING_HISTORY_STATUS_CANCELLED)->where(['instructor_user_id' => Auth::id(), 'send_back_money_status' => SEND_BACK_MONEY_STATUS_YES])->whereHas('order', function ($q){
            $q->where('payment_status', 'paid');
        })->pluck('order_item_id')->toArray();
        $orderItems = Order_item::whereIn('id', $cancelConsultationOrderItemIds);
        $cancel_consultation_money = $orderItems->sum('admin_commission') + $orderItems->sum('owner_balance');
        //Start::  Cancel Consultation Money Calculation

        $orderBundleItemsCount = Order_item::where('owner_user_id', Auth::id())->whereNotNull('bundle_id')->whereNull('course_id')
            ->whereHas('order', function ($q) {
                $q->where('payment_status', 'paid');
            })->count();

        $orderItems = Order_item::where('owner_user_id', Auth::id())
            ->whereHas('order', function ($q) {
            $q->where('payment_status', 'paid');
        });

        //Start::Affiliate Earning from AffiliateHistory
        $affiliateHistoryTotalCommission = AffiliateHistory::where(['user_id' => Auth::id(), 'status' => AFFILIATE_HISTORY_STATUS_PAID])->sum('commission');
        //End::Affiliate Earning from AffiliateHistory

        $data['total_earning'] = $orderItems->sum('owner_balance') + $affiliateHistoryTotalCommission - $cancel_consultation_money;
        $data['total_enroll'] = $orderItems->count('id') - $orderBundleItemsCount;

        $data['total_withdraw_amount'] = Withdraw::whereUserId(auth()->user()->id)->completed()->sum('amount');
        $data['total_pending_withdraw_amount'] = Withdraw::whereUserId(auth()->user()->id)->pending()->sum('amount');

        //Start:: Sell statistics
        $months = collect([]);
        $totalPrice = collect([]);

        Order_item::whereNotIn('id', $cancelConsultationOrderItemIds)->where('owner_user_id', Auth::id())->whereHas('order', function ($q) {
            $q->where('payment_status', 'paid');
        })->select(DB::raw('SUM(owner_balance) as total'), DB::raw('MONTHNAME(created_at) month'))
            ->groupby('month')
            ->get()
            ->map(function ($q) use ($months, $totalPrice) {
                $months->push($q->month);
                $totalPrice->push($q->total);
            });

        $data['months'] = $months;
        $data['totalPrice'] = $totalPrice;
        //End:: Sell statistics

        return view('instructor.finance.analysis-index')->with($data);
    }

    public function withdrawIndex()
    {
        $data['title'] = 'Withdraw History';
        $data['navFinanceActiveClass'] = 'has-open';
        $data['subNavWithdrawActiveClass'] = 'active';
        $data['withdraws'] = Withdraw::whereUserId(auth()->user()->id)->with('beneficiary')->paginate(20);
        return view('instructor.finance.withdraw-history-index')->with($data);
    }

    public function storeWithdraw(Request $request)
    {
        if ($request->amount > int_to_decimal(Auth::user()->balance))
        {
            $this->showToastrMessage('warning', __('Insufficient balance'));
            return redirect()->back();
        } else {
            DB::beginTransaction();
            try {
                $withdrow = new Withdraw();
                $withdrow->transection_id = Str::uuid()->getHex();
                $withdrow->amount = $request->amount;
                $withdrow->payment_method = $request->payment_method;
                $withdrow->save();
                Auth::user()->decrement('balance', decimal_to_int($request->amount));
                createTransaction(Auth::id(),$request->amount,TRANSACTION_WITHDRAWAL,'Withdrawal via '.$request->payment_method);

                $text = __("New Withdraw Request Received");
                $target_url = route('payout.new-withdraw');
                $this->send($text, 1, $target_url, null);

                $this->showToastrMessage('warming', __('Withdraw request has been saved'));
                DB::commit();
                return redirect()->back();
            }catch (\Exception $e){
                DB::rollBack();
                $this->showToastrMessage('warning', __('Something Went Wrong'));
                return redirect()->back();
            }
        }

    }

    public function downloadReceipt($uuid)
    {
        $withdraw = Withdraw::whereUuid($uuid)->first();

//        $invoice_name = 'receipt-' . $withdraw->transection_id. '.pdf';
        // make sure email invoice is checked.
//        $customPaper = array(0, 0, 612, 792);
//        $pdf = PDF::loadView('instructor.finance.receipt-pdf', ['withdraw' => $withdraw])->setPaper($customPaper, 'portrait');
//        $pdf->save(public_path() . '/uploads/receipt/' . $invoice_name);
       // return $pdf->stream($invoice_name);
//        return $pdf->download($invoice_name);
        return view('instructor.finance.receipt-pdf', ['withdraw' => $withdraw]);

    }
}
