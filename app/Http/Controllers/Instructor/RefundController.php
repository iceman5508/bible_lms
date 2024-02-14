<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Models\Refund;
use App\Models\Transaction;
use App\Traits\General;
use App\Traits\SendNotification;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RefundController extends Controller
{
    use General, SendNotification;
    public function index()
    {
        $data['title'] = 'Refund List';
        $data['navRefundActiveClass'] = 'active';
        $data['refunds'] = Refund::where('instructor_user_id', auth()->id())->with('order_item.course')->get();
        return view('instructor.refund.list', $data);
    }

    public function processRefund(Request $request)
    {
        try{
            DB::beginTransaction();
            $refund = Refund::where('id', $request->id)->first();
            if(is_null($refund)){
                return response()->json(['status' => false, 'message' => 'Request not found'], 404);
            }
            
            if($request->type == 2){
                $refund->update([
                    'status' => STATUS_REJECTED,
                    'feedback' => $request->feedback,
                ]);
    
                $this->send('Refund Request Rejected', 3, null, $refund->user_id);
                DB::commit();
                return response()->json(['status' => true, 'message' => 'Rejected successfully'], 200);
            }
            else{
                $refund->update([
                    'status' => STATUS_SUCCESS
                ]);

                $refund->enrollment()->update(['status' => STATUS_PENDING]);
    
                //refund process
                createTransaction($refund->user_id, $refund->amount, TRANSACTION_REFUND, 'Refund', 'Order_item (' . $refund->order_item_id . ')');
                if ($refund->user) {
                    $refund->user->increment('balance', decimal_to_int($refund->amount));
                }
                
                $allTransaction = Transaction::where('order_item_id', $refund->order_item_id)->get();
                foreach($allTransaction as $transaction){
                    createTransaction($transaction->user_id, $transaction->amount, TRANSACTION_SELL_REFUND, 'Refund Reversed', 'Order_item (' . $transaction->order_item_id . ')');
                    if ($transaction->user) {
                        $transaction->user->decrement('balance', decimal_to_int($transaction->amount));
                    }
                }

                $this->send('Refund Request Accepted', 3, null, $refund->user_id);
                DB::commit();
                return response()->json(['status' => true, 'message' => 'Approved Successfully'], 200);
            }
            
        }catch(Exception $e){
            DB::rollback();
            return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
