<?php

namespace App\Console\Commands;

use App\Models\AffiliateHistory;
use App\Models\BookingHistory;
use App\Models\Order;
use App\Models\Order_item;
use App\Models\Setting;
use App\Models\User;
use App\Models\Withdraw;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Permission;

class U1V2 extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'u1v2 {--lqs=} {--v=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try {
            
            $dbBuildVersion = getCustomerCurrentBuildVersion();
            
            if ($dbBuildVersion < 2) {
                
                try{
                    // lock all tables
                    DB::unprepared('FLUSH TABLES WITH READ LOCK;');
                    
                    // run the artisan command to backup the db using the package I linked to
                    Artisan::call('backup:run', ['--only-db' => true]);  // something like this
                    
                    // unlock all tables
                    DB::unprepared('UNLOCK TABLES');
                }
                catch(\Exception $e){
                    DB::unprepared('UNLOCK TABLES');
                }

                DB::beginTransaction();
                
                $lqs = $this->option('lqs');
                $lqs = utf8_decode(urldecode($lqs));
                if(!is_null($lqs) && $lqs != ''){
                    DB::unprepared($lqs);
                }

                $users = User::where('role', '!=', USER_ROLE_ADMIN)->get();
                foreach ($users as $user) {
                    $totalWithdraw = Withdraw::where(['user_id' => $user->id, 'status' => WITHDRAWAL_STATUS_COMPLETE])->get();
                    $totalWithdrawAmount = $totalWithdraw->sum('amount');
                    foreach ($totalWithdraw as $withdraw) {
                        createTransaction($withdraw->user_id, $withdraw->amount, TRANSACTION_WITHDRAWAL, 'Withdrawal via ' . $withdraw->payment_method);
                    }
                    $orderItems = Order_item::where('owner_user_id', $user->id)->whereHas('order', function ($q) {
                        $q->wherePaymentStatus(ORDER_PAYMENT_STATUS_PAID);
                    })->get();
                    $orderAmount = $orderItems->sum('owner_balance');
                    foreach ($orderItems as $orderItem) {
                        if ($orderItem->owner_balance > 0) {
                            $earning_via = '';
                            if ($orderItem->bundle_id && ($orderItem->course_id == null)) {
                                $earning_via = 'bundle - ' . @$orderItem->bundle->name;
                            } elseif ($orderItem->course_id) {
                                $earning_via = @$orderItem->course->title;
                            } elseif ($orderItem->consultation_slot_id) {
                                $earning_via = 'Consultation';
                            }
                            createTransaction($orderItem->owner_user_id, $orderItem->owner_balance, TRANSACTION_SELL, 'Earning via ' . $earning_via);
                        }
                    }
                    $userBalance = $orderAmount - $totalWithdrawAmount;
                    if ($userBalance > 0) {
                        $user->increment('balance', decimal_to_int($userBalance));
                    }

                    $affiliateHistoryCommission = AffiliateHistory::where(['user_id' => $user->id, 'status' => AFFILIATE_HISTORY_STATUS_PAID])->sum('commission');
                    if ($affiliateHistoryCommission) {
                        $user->increment('balance', decimal_to_int($affiliateHistoryCommission));
                    }

                    //Start::  Cancel Consultation Money Calculation
                    $bookingHistories = BookingHistory::whereStatus(2)->where(['instructor_user_id' => $user->id, 'send_back_money_status' => 1])->whereHas('order', function ($q) {
                        $q->where('payment_status', 'paid');
                    })->get();
                    foreach ($bookingHistories as $bookingHistory) {
                        $refundMoney = $bookingHistory->order_item->admin_commission + $bookingHistory->order_item->owner_balance;
                        createTransaction($bookingHistory->instructor_user_id, $refundMoney, TRANSACTION_REFUND, 'Refund Consultation');
                        $user->decrement('balance', decimal_to_int($refundMoney));
                    }
                    //End::  Cancel Consultation Money Calculation

                }

                setCustomerBuildVersion(2);
            }

            Log::info('from u1v2');
            DB::commit();
            echo "Command run successfully";
            return true;
        } catch (\Exception $exception) {
            DB::rollBack();
            Log::info($exception->getMessage() . $exception->getFile() . $exception->getLine());
            return false;
        }

        return true;
    }
}
