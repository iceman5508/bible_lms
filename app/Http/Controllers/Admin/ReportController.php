<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Addon\Product\Product;
use App\Models\BookingHistory;
use App\Models\Course;
use App\Models\Order;
use App\Models\Order_item;
use App\Models\User;
use App\Traits\SendNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    use SendNotification;
    public function revenueReportCoursesIndex()
    {
        if (!Auth::user()->can('finance')) {
            abort('403');
        } // end permission checking

        $data['title'] = 'Courses Report List';
        $courseIds = Order_item::whereHas('order', function ($q) {
            $q->where('payment_status', 'paid')->orWhere('payment_status', 'free');
        })->pluck('course_id')->toArray();

        $data['courses'] = Course::whereIn('id', $courseIds)->withCount([
            'orderItems as total_admin_commission' => function ($q) {
                $q->select(DB::raw("SUM(admin_commission)"));
            },

            'orderItems as total_owner_balance' => function ($q) {
                $q->select(DB::raw("SUM(owner_balance)"));
            },

            'orderItems as total_purchase_course' => function ($q) {
                $q->select(DB::raw("COUNT(id)"));
            }
        ])->paginate(25);


        $orderItems = Order_item::whereNotNull('course_id')->whereHas('order', function ($q) {
            $q->where('payment_status', 'paid')->orWhere('payment_status', 'free');
        });
        $data['grand_admin_commission'] = $orderItems->sum('admin_commission');
        $data['grand_instructor_commission'] = $orderItems->sum('owner_balance');

        $data['total_enrolment_in_course'] = Order_item::whereNotNull('course_id')->whereHas('order', function ($q) {
            $q->where('payment_status', 'paid')->orWhere('payment_status', 'free');
        })->count();

        $data['total_courses'] = Course::where('status', '!=', 4)->count();

        return view('admin.report.course-list', $data);
    }

    public function revenueReportBundlesIndex()
    {
        if (!Auth::user()->can('finance')) {
            abort('403');
        } // end permission checking

        $data['title'] = 'Consultation Report List';

        $bundleIds = Order_item::whereHas('order', function ($q) {
            $q->where('payment_status', 'paid')->orWhere('payment_status', 'free');
        })->pluck('bundle_id')->toArray();

        $data['bundleOrderItems'] = Order_item::whereNotNUll('bundle_id')->whereHas('order', function ($q) {
            $q->where('payment_status', 'paid');
        })->select(['owner_user_id','bundle_id',DB::raw("SUM(admin_commission) as total_admin_commission"), DB::raw("SUM(owner_balance) as total_owner_balance"), DB::raw("COUNT(id) as total_enroll")])
            ->groupBy('bundle_id')->latest()->paginate();

        $orderItems = Order_item::whereNotNUll('bundle_id')->whereHas('order', function ($q) {
            $q->where('payment_status', 'paid');
        });
        $data['grand_admin_commission'] = $orderItems->sum('admin_commission');
        $data['grand_instructor_commission'] = $orderItems->sum('owner_balance');

        $data['total_enrolment_in_bundle'] = Order_item::whereNotNUll('bundle_id')->whereHas('order', function ($q) {
            $q->where('payment_status', 'paid');
        })->count();

        $data['total_courses'] = Course::where('status', '!=', 4)->count();

        return view('admin.report.bundle-list', $data);
    }

    public function revenueReportConsultationIndex()
    {
        if (!Auth::user()->can('finance')) {
            abort('403');
        } // end permission checking

        $data['title'] = 'Consultation Report List';

        $data['consultationOrderItems'] = Order_item::whereNotNUll('consultation_slot_id')->whereHas('order', function ($q) {
            $q->where('payment_status', 'paid');
        })->select(['owner_user_id','consultation_slot_id', 'consultation_date',DB::raw("SUM(admin_commission) as total_admin_commission"), DB::raw("SUM(owner_balance) as total_owner_balance"), DB::raw("COUNT(id) as total_enroll")])
            ->groupBy('consultation_slot_id')->latest()->paginate();

        // Start:: Consultation Calculation
        $data['total_enrolment_in_consultation'] = Order_item::whereNotNull('consultation_slot_id')->whereHas('order', function ($q) {
            $q->where('payment_status', 'paid');
        })->count();

        $orderItems = Order_item::whereNotNull('consultation_slot_id')->whereHas('order', function ($q) {
            $q->where('payment_status', 'paid');
        });
        $data['grand_money_from_consultation'] = $orderItems->sum('admin_commission') + $orderItems->sum('owner_balance');

        $cancelConsultationOrderItemIds = BookingHistory::whereStatus(2)->where('send_back_money_status', 1)->whereHas('order', function ($q){
            $q->where('payment_status', 'paid');
        })->pluck('order_item_id')->toArray();

        $orderItems = Order_item::whereIn('id', $cancelConsultationOrderItemIds);
        $data['cancel_consultation_money'] = $orderItems->sum('admin_commission') + $orderItems->sum('owner_balance');
        $data['cancel_admin_commission_consultation_money'] = $orderItems->sum('admin_commission');
        $data['cancel_instructor_commission_consultation_money'] = $orderItems->sum('owner_balance');
        $data['total_cancel_consultation'] = count($cancelConsultationOrderItemIds);

        // End:: Consultation Calculation
        return view('admin.report.consultation-list', $data);
    }

    public function orderReportIndex()
    {
        if (!Auth::user()->can('finance')) {
            abort('403');
        } // end permission checking

        $data['title'] = 'Order Report List';

        $data['orders'] = Order::where('payment_status', 'paid')->orWhere('payment_status', 'free')->withCount([
            'items as total_admin_commission' => function ($q) {
                $q->select(DB::raw("SUM(admin_commission)"));
            },

            'items as total_owner_balance' => function ($q) {
                $q->select(DB::raw("SUM(owner_balance)"));
            },
        ])->paginate(25);


        $data['grand_total'] = Order::where('payment_status', 'paid')->sum('grand_total');

        $data['total_platform_charge'] = Order::where('payment_status', 'paid')->sum('platform_charge');
        $total_order_admin_commission = Order::where('payment_status', 'paid')->withCount([
            'items as total_sell_commission' => function ($q) {
                $q->select(DB::raw('SUM(admin_commission)'));
            }
        ])->get();
        $data['total_admin_commission'] = $total_order_admin_commission->sum('total_admin_commission');

        $total_admin_sell = Order::where('payment_status', 'paid')->withCount([
            'items as total_admin_commission' => function ($q) {
                $q->select(DB::raw("SUM(admin_commission)"));
            },
        ])->get();
        $data['total_revenue'] = $total_admin_sell->sum(function ($q) {
            return $q->platform_charge + $q->total_admin_commission;
        });

        $orderItems = Order_item::whereHas('order', function ($q) {
            $q->where('payment_status', 'paid')->orWhere('payment_status', 'free');
        });
        $data['grand_admin_commission'] = $orderItems->sum('admin_commission');
        $data['grand_instructor_commission'] = $orderItems->sum('owner_balance');

        $data['total_enrolment_in_course'] = Order_item::whereNotNull('course_id')->whereHas('order', function ($q) {
            $q->where('payment_status', 'paid')->orWhere('payment_status', 'free');
        })->count();

        // Start:: Consultation Calculation
        $data['total_enrolment_in_consultation'] = Order_item::whereNotNull('consultation_slot_id')->whereHas('order', function ($q) {
            $q->where('payment_status', 'paid');
        })->count();

        $orderItems = Order_item::whereNotNull('consultation_slot_id')->whereHas('order', function ($q) {
            $q->where('payment_status', 'paid');
        });
        $data['grand_money_from_consultation'] = $orderItems->sum('admin_commission') + $orderItems->sum('owner_balance');

        $cancelConsultationOrderItemIds = BookingHistory::whereStatus(2)->where('send_back_money_status', 1)->whereHas('order', function ($q){
            $q->where('payment_status', 'paid');
        })->pluck('order_item_id')->toArray();

        $orderItems = Order_item::whereIn('id', $cancelConsultationOrderItemIds);
        $data['cancel_consultation_money'] = $orderItems->sum('admin_commission') + $orderItems->sum('owner_balance');
        $data['cancel_admin_commission_consultation_money'] = $orderItems->sum('admin_commission');
        $data['cancel_instructor_commission_consultation_money'] = $orderItems->sum('owner_balance');
        $data['total_cancel_consultation'] = count($cancelConsultationOrderItemIds);

        // End:: Consultation Calculation

        //Start:: Bundle Course
        $orderItems = Order_item::whereNotNull('bundle_id')->whereNull('course_id')->whereHas('order', function ($q) {
            $q->where('payment_status', 'paid');
        });
        $data['grand_money_from_bundle'] = $orderItems->sum('admin_commission') + $orderItems->sum('owner_balance');
        $data['total_bundle_Course_enrolled'] = $orderItems->count();
        //End:: Bundle Course


        return view('admin.report.order-report-list', $data);
    }

    public function orderReportPending()
    {
        if (!Auth::user()->can('finance')) {
            abort('403');
        } // end permission checking
        $data['title'] = 'Pending Order Report List';

        $data['orders'] = Order::where('payment_status', 'pending')->withCount([
            'items as total_admin_commission' => function ($q) {
                $q->select(DB::raw("SUM(admin_commission)"));
            },

            'items as total_owner_balance' => function ($q) {
                $q->select(DB::raw("SUM(owner_balance)"));
            },
        ])->paginate(25);


        $data['grand_total'] = Order::where('payment_status', 'pending')->sum('grand_total');

        $data['total_platform_charge'] = Order::where('payment_status', 'pending')->sum('platform_charge');
        $total_order_admin_commission = Order::where('payment_status', 'pending')->withCount([
            'items as total_sell_commission' => function ($q) {
                $q->select(DB::raw('SUM(admin_commission)'));
            }
        ])->get();
        $data['total_admin_commission'] = $total_order_admin_commission->sum('total_admin_commission');

        $total_admin_sell = Order::where('payment_status', 'pending')->withCount([
            'items as total_admin_commission' => function ($q) {
                $q->select(DB::raw("SUM(admin_commission)"));
            },
        ])->get();
        $data['total_revenue'] = $total_admin_sell->sum(function ($q) {
            return $q->platform_charge + $q->total_admin_commission;
        });

        return view('admin.report.order-report-pending', $data);
    }

    public function orderReportCancelled()
    {
        if (!Auth::user()->can('finance')) {
            abort('403');
        } // end permission checking
        $data['title'] = 'Cancelled Order Report List';
        $data['orders'] = Order::where('payment_status', 'cancelled')->withCount([
            'items as total_admin_commission' => function ($q) {
                $q->select(DB::raw("SUM(admin_commission)"));
            },

            'items as total_owner_balance' => function ($q) {
                $q->select(DB::raw("SUM(owner_balance)"));
            },
        ])->paginate(25);


        $data['grand_total'] = Order::where('payment_status', 'cancelled')->sum('grand_total');

        $data['total_platform_charge'] = Order::where('payment_status', 'cancelled')->sum('platform_charge');
        $total_order_admin_commission = Order::where('payment_status', 'cancelled')->withCount([
            'items as total_sell_commission' => function ($q) {
                $q->select(DB::raw('SUM(admin_commission)'));
            }
        ])->get();
        $data['total_admin_commission'] = $total_order_admin_commission->sum('total_admin_commission');

        $total_admin_sell = Order::where('payment_status', 'cancelled')->withCount([
            'items as total_admin_commission' => function ($q) {
                $q->select(DB::raw("SUM(admin_commission)"));
            },
        ])->get();
        $data['total_revenue'] = $total_admin_sell->sum(function ($q) {
            return $q->platform_charge + $q->total_admin_commission;
        });

        return view('admin.report.order-report-cancelled', $data);
    }

    public function orderReportPaidStatus($uuid, $status)
    {

        DB::beginTransaction();
        try {
            $order = Order::where('uuid', $uuid)->first();
            $order->payment_status = $status;
            $order->save();
            if($status == 'paid'){
                distributeCommission($order);
            }
            DB::commit();
        }catch (\Exception $e){
            DB::rollBack();
        }

        /** ====== send notification to student ===== */
        $orderItems = Order_item::where('order_id', $order->id)->get();
        foreach ($orderItems as $orderItem)
        {
            if(!is_null($orderItem->product_id)){
                if ($status == 'paid') {
                    
                    Product::where('id', $orderItem->product_id)->decrement('quantity', $orderItem->unit);

                    $text = __("Your purchase product been approved.");
                    $target_url = route('lms_product.student.purchase_list');
    
                    /** ====== Send notification to instructor =========*/
                    $text2 = "New product sold";
                    $target_url2 = route('lms_product.instructor.product.my-product');
                    $this->send($text2, 2, $target_url2, @$orderItem->product->user_id);
                    /** ====== Send notification to instructor =========*/
    
                } else {
                    $text = __("Your bank payment has been cancelled.");
                    $target_url = route('lms_product.student.purchase_list');
                    $this->send($text, 3, $target_url, $order->user_id);
                }
    
            }else{
                if ($status == 'paid') {
                    $text = __("Your new course has been approved and added.");
                    $target_url = route('student.my-course.show', @$orderItem->course->slug);
    
                    /** ====== Send notification to instructor =========*/
                    $text2 = "New student enrolled";
                    $target_url2 = route('instructor.all-student');
                    $this->send($text2, 2, $target_url2, @$orderItem->course->user_id);
                    /** ====== Send notification to instructor =========*/
    
                } else {
                    $text = __("Your bank payment has been cancelled.");
                    $target_url = route('student.my-learning');
                    $this->send($text, 3, $target_url, $order->user_id);
                }
    
            }
        }
        /** ====== send notification to student ===== */
    }

    public function cancelConsultationList()
    {
        if (!Auth::user()->can('finance')) {
            abort('403');
        } // end permission checking
        $data['title'] = 'Cancel Consultation Report List';
        $data['consultationOrderItems'] = Order_item::whereNotNUll('consultation_slot_id')->whereHas('order', function ($q) {
            $q->where('payment_status', 'paid');
        })->select(['owner_user_id','consultation_slot_id', 'consultation_date',DB::raw("SUM(admin_commission) as total_admin_commission"), DB::raw("SUM(owner_balance) as total_owner_balance"), DB::raw("COUNT(id) as total_enroll")])
            ->groupBy('consultation_slot_id')->latest()->paginate();

        $data['bookingHistories'] = BookingHistory::whereHas('order', function ($q) {
            $q->where('payment_status', 'paid');
        })->cancelled()->latest()->paginate();

        return view('admin.report.cancel-consultation-list', $data);
    }

    public function changeConsultationStatus(Request $request)
    {
        DB::beginTransaction();
        try {
            $booking = BookingHistory::find($request->id);
            if ($booking) {
                if ($booking->send_back_money_status == SEND_BACK_MONEY_STATUS_YES) {
                    return response()->json([
                        'status' => 403,
                    ]);
                } else {
                    $booking->send_back_money_status = SEND_BACK_MONEY_STATUS_YES;
                    $booking->back_admin_commission = @$booking->order_item->admin_commission ?? '';
                    $booking->back_owner_balance = @$booking->order_item->owner_balance ?? '';
                    $booking->save();

                    $user = User::find($booking->instructor_user_id);
                    if($user && $booking->send_back_money_status == SEND_BACK_MONEY_STATUS_YES) {
                        $refundMoney = $booking->order_item->admin_commission + $booking->order_item->owner_balance;
                        createTransaction($booking->instructor_user_id, $refundMoney, TRANSACTION_REFUND, 'Refund Consultation');
                        $user->decrement('balance', decimal_to_int($refundMoney));
                    }

                    /** ====== send notification to student ===== */
                    $text = __("Your consultation booking cancelled money back done");
                    $target_url = route('student.my-consultation');
                    $this->send($text, 3, $target_url, $booking->student_user_id);

                    /** ====== send notification to instructor ===== */
                    $target_url = route('instructor.bookingHistory');
                    $this->send($text, 2, $target_url, $booking->instructor_user_id);

                    DB::commit();
                    return response()->json([
                        'status' => 200,
                    ]);
                }
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 400,
            ]);
        }

    }
}
