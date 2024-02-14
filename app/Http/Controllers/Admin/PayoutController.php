<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\MonthlyDistributionHistory;
use App\Models\SubscriptionCommissionHistory;
use App\Models\Transaction;
use App\Models\User;
use App\Models\UserPackage;
use App\Models\Withdraw;
use App\Traits\General;
use App\Traits\SendNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class PayoutController extends Controller
{
    use General, SendNotification;

    public function newWithdraw()
    {
        if (!Auth::user()->can('payout')) {
            abort('403');
        } // end permission checking

        $data['title'] = 'New Withdraw Request';
        $data['navFinanceParentActiveClass'] = 'mm-active';
        $data['navFinanceShowClass'] = 'mm-show';
        $data['subNavFinanceNewWithdrawActiveClass'] = 'mm-active';
        $data['withdraws'] = Withdraw::pending()->orderBy('id', 'DESC')->with('beneficiary')->paginate(20);
        return view('admin.payout.new-withdraw', $data);

    }

    public function completeWithdraw()
    {
        if (!Auth::user()->can('payout')) {
            abort('403');
        } // end permission checking

        $data['title'] = 'Complete Withdraw';
        $data['navFinanceParentActiveClass'] = 'mm-active';
        $data['navFinanceShowClass'] = 'mm-show';
        $data['subNavFinanceCompleteWithdrawActiveClass'] = 'mm-active';
        $data['withdraws'] = Withdraw::completed()->orderBy('id', 'DESC')->with('beneficiary')->paginate(20);
        return view('admin.payout.complete-withdraw', $data);
    }

    public function rejectedWithdraw()
    {
        if (!Auth::user()->can('payout')) {
            abort('403');
        } // end permission checking

        $data['title'] = 'Rejected Withdraw';
        $data['navFinanceParentActiveClass'] = 'mm-active';
        $data['navFinanceShowClass'] = 'mm-show';
        $data['subNavFinancerejectedWithdrawActiveClass'] = 'mm-active';
        $data['withdraws'] = Withdraw::rejected()->orderBy('id', 'DESC')->with('beneficiary')->paginate(20);
        return view('admin.payout.rejected-withdraw', $data);
    }

    public function changeWithdrawStatus(Request $request)
    {
        if ($request->status == WITHDRAWAL_STATUS_COMPLETE || $request->status == WITHDRAWAL_STATUS_REJECTED)
        {
            $withdraw = Withdraw::whereUuid($request->uuid)->first();
            if (!is_null($withdraw)){
                DB::beginTransaction();
                try {
                    if($request->status == WITHDRAWAL_STATUS_REJECTED){
                        $user = User::find($withdraw->user_id);
                        $user->increment('balance', decimal_to_int($withdraw->amount));
                        createTransaction($user->id,$withdraw->amount,TRANSACTION_WITHDRAWAL_CANCEL,'Withdrawal Cancel ');
                    }
                    $withdraw->status = $request->status;
                    $withdraw->note = $request->note;
                    $withdraw->save();
                    DB::commit();
                    $this->showToastrMessage('success', __('Status has been changed'));
                    return redirect()->back();
                }catch (\Exception $e){
                    DB::rollBack();
                    $this->showToastrMessage('warning', __('Something Went Wrong'));
                    return redirect()->back();
                }
            } else {
                abort(404);
            }

        } else {
            abort(404);
        }

    }
       
    public function distributeSubscription()
    {
        $data['title'] = __('Distribute Subscription Payment');
        $data['navFinanceParentActiveClass'] = 'mm-active';
        $data['navFinanceShowClass'] = 'mm-show';
        $data['subNavFinanceDistributeSubscriptionActiveClass'] = 'mm-active';
        $data['monthYears'] = MonthlyDistributionHistory::select('id','month_year')->get();
        return view('admin.payout.distribute-subscription', $data);
    }

    public function sendMoneyStore(Request $request)
    {
        $request->validate([
            'month_year' => 'required|unique:monthly_distribution_histories,month_year',
        ], [
            'month_year.unique' => 'Already paid of this month. '
        ]);

        $month_year = date('Y-m', strtotime($request->month_year));
        $now_month_year = date('Y-m', strtotime(now()));

        if ($now_month_year <= $month_year) {
            $this->showToastrMessage('error', __('Month Year should be less than Present Month Year'));
            return redirect()->back();
        }

        $totalEnrolls = Enrollment::whereYear('created_at', date('Y', strtotime($month_year)))->whereMonth('created_at', date('m', strtotime($month_year)))->whereNotNull('user_package_id')->with('course.course_instructors.instructor.user')->get();

        $response = $this->earningManagementCalculation($request);

        if($response['total_income_from_subscription'] == 0){
            $this->showToastrMessage('warning', __('The distribution amount is 0'));
            return back();
        }

        DB::beginTransaction();
        try {
            $monthlyDistributionHistory = new MonthlyDistributionHistory();
            $monthlyDistributionHistory->month_year = $request->month_year;
            $monthlyDistributionHistory->total_enroll_course = $response['total_enroll_course'];
            $monthlyDistributionHistory->total_subscription = $response['current_subscription'];
            $monthlyDistributionHistory->total_amount = $response['total_income_from_subscription'];
            $monthlyDistributionHistory->save();

            $adminCommission = 0;
            if(count($totalEnrolls)){
                $amountPerPerson = $monthlyDistributionHistory->total_amount / count($totalEnrolls);
                foreach ($totalEnrolls as $enroll)
                {
                    $userPackage = UserPackage::where('user_packages.user_id', $enroll->user_id)
                        ->where('package_type', PACKAGE_TYPE_SUBSCRIPTION)
                        ->join('packages', 'packages.id', '=', 'user_packages.package_id')
                        ->whereDate('enroll_date', '<=', now())
                        ->whereDate('expired_date', '>=', now())
                        ->select('user_packages.admin_commission')->first();
                    foreach($enroll->course->course_instructors as $course_instructor){
                        $commission_percentage = get_option('sell_commission');
                        if($userPackage){
                            $commission_percentage = $userPackage->admin_commission;
                            $commission = admin_commission_by_percentage($amountPerPerson,  $commission_percentage);
                            $adminCommission += $commission;
                            $finalAmount = $amountPerPerson - $commission;
                        }
                        else{
                            $commission = admin_commission_by_percentage($amountPerPerson,  $commission_percentage);
                            $adminCommission += $commission;
                            $finalAmount = $amountPerPerson - $commission;
                        }

                        $data = [
                            'monthly_distribution_history_id' => $monthlyDistributionHistory->id,
                            'user_id' => $course_instructor->instructor->user_id,
                            'sub_amount' => $amountPerPerson,
                            'commission_percentage' => $commission_percentage,
                            'admin_commission' => $commission,
                            'amount' => $finalAmount,
                            'paid_at' => now()
                        ];
                        
                        SubscriptionCommissionHistory::create($data);
                        $course_instructor->instructor->user->increment('balance', decimal_to_int($finalAmount));
                        createTransaction($course_instructor->instructor->user_id, $finalAmount, TRANSACTION_SUBSCRIPTION_BUY, 'Earning via subscription sell', 'Year month-'.$month_year);
                        $text = __("Monthly Subscription Payment Deposited");
                        $target_url = route('wallet.transaction-history');
                        $this->send($text, 3, $target_url, $course_instructor->instructor->user_id);

                    }
                }
            }
            else{
                $totalSubscriptionCourse = Course::where('is_subscription_enable', 1)->with('course_instructors.instructor.user')->get();
                $amountPerPerson = $monthlyDistributionHistory->total_amount / count($totalSubscriptionCourse);
                foreach ($totalSubscriptionCourse as $course)
                {
                    $userPackage = UserPackage::where('user_packages.user_id', $course->user_id)
                        ->where('package_type', PACKAGE_TYPE_SUBSCRIPTION)
                        ->join('packages', 'packages.id', '=', 'user_packages.package_id')
                        ->whereDate('enroll_date', '<=', now())
                        ->whereDate('expired_date', '>=', now())
                        ->select('admin_commission')->first();
                        
                    foreach($course->course_instructors->where('status', STATUS_APPROVED) as $course_instructor){

                        $commission_percentage = get_option('sell_commission');
                        if($userPackage){
                            $commission_percentage = $userPackage->admin_commission;
                            $commission = admin_commission_by_percentage($amountPerPerson,  $commission_percentage);
                            $adminCommission += $commission;
                            $finalAmount = $amountPerPerson - $commission;
                        }
                        else{
                            $commission = admin_commission_by_percentage($amountPerPerson,  $commission_percentage);
                            $adminCommission += $commission;
                            $finalAmount = $amountPerPerson - $commission;
                        }

                        $data = [
                            'monthly_distribution_history_id' => $monthlyDistributionHistory->id,
                            'user_id' => $course_instructor->instructor->user_id,
                            'sub_amount' => $amountPerPerson,
                            'commission_percentage' => $commission_percentage,
                            'admin_commission' => $commission,
                            'total_amount' => $finalAmount,
                            'paid_at' => now()
                        ];
                        
                        SubscriptionCommissionHistory::create($data);
                        $course_instructor->instructor->user->increment('balance', decimal_to_int($finalAmount));
                        createTransaction($course_instructor->instructor->user_id, $finalAmount, TRANSACTION_SUBSCRIPTION_BUY, 'Earning via subscription sell', 'Year month-'.$month_year);
                        $text = __("Monthly Subscription Payment Deposited");
                        $target_url = route('wallet.transaction-history');
                        $this->send($text, 3, $target_url, $course_instructor->instructor->user_id);

                    }
                }
            }

            $monthlyDistributionHistory->admin_commission = $adminCommission;
            $monthlyDistributionHistory->save();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            $this->showToastrMessage('warning', __('Something went wrong'));
            return redirect()->back();
        }

        $this->showToastrMessage('success', __('Send Money Successfully'));
        return redirect()->back();
    }

    protected function earningManagementCalculation(Request $request)
    {
        $month_year = date('Y-m', strtotime($request->month_year));
        $month = date('m', strtotime($request->month_year));
        $year = date('Y', strtotime($request->month_year));
        $monthDay = cal_days_in_month(CAL_GREGORIAN,$month,$year);

        $response['total_enroll_course'] = Enrollment::whereYear('created_at', date('Y', strtotime($month_year)))->whereMonth('created_at', date('m', strtotime($month_year)))->whereNotNull('user_package_id')->count();
       
        $userPackages = UserPackage::join('packages', 'packages.id', '=', 'user_packages.package_id')
            ->where('packages.package_type', PACKAGE_TYPE_SUBSCRIPTION)
            ->select("user_packages.*")
            ->where(DB::raw("DATE_FORMAT(enroll_date, '%Y-%m')"), '<=', $month_year)
            ->where(DB::raw("DATE_FORMAT(expired_date, '%Y-%m')"), '>=', $month_year)->get();

        $response['total_income_from_subscription'] = 0;
        $response['current_subscription'] = count($userPackages);

        foreach ($userPackages as $userPackage) {
            $totalAmount = $userPackage->payment->sub_total;
            $totalDay = round((strtotime($userPackage->expired_date) - strtotime($userPackage->enroll_date)) / (60 * 60 * 24));
            $dailyAmount = $totalAmount/$totalDay;
            $startDayDiff = (strtotime($month_year.'-'.$monthDay) - strtotime($userPackage->enroll_date)) / (60 * 60 * 24);
            $endDayDiff = (strtotime($month_year.'-'.$monthDay) - strtotime($userPackage->expired_date)) / (60 * 60 * 24);
    
            $dayCount = $startDayDiff;
    
            if(($monthDay - $startDayDiff) < 0){
                $dayCount = $monthDay;
            }
    
            if(($monthDay-$endDayDiff) < $monthDay){
                $dayCount = $monthDay-$endDayDiff;
            }
    
            $dayCount = $dayCount < 0 ? 0 : $dayCount;
    
            $totalAmount = $dailyAmount * $dayCount;
            $response['total_income_from_subscription'] += $totalAmount;
        }

        if ($response['current_subscription'] < 1) {
            $response['total_income_from_subscription'] = 0;
            $response['total_enroll_course'] = 0;
            $response['current_subscription'] = 0;
        }
                        
        return $response;
    }
}
