<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\BookingHistory;
use App\Models\Course;
use App\Models\Order;
use App\Models\Order_item;
use App\Models\User;
use App\Traits\ApiStatusTrait;
use App\Traits\SendNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    use SendNotification, ApiStatusTrait;
    public function revenueReportCoursesIndex()
    {
        if (!Auth::user()->hasPermissionTo('finance', 'web')) {
            return $this->error([], 'Unauthorize access', 403);
        } // end permission checking

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

        return $this->success($data);
    }

    public function orderReportIndex()
    {
        if (!Auth::user()->hasPermissionTo('finance', 'web')) {
            return $this->error([], 'Unauthorize access', 403);
        } // end permission checking

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

        $cancelConsultationOrderItemIds = BookingHistory::whereStatus(2)->where('send_back_money_status', 1)->whereHas('order', function ($q) {
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

        return $this->success($data);
    }

    public function orderReportPending()
    {
        if (!Auth::user()->hasPermissionTo('finance', 'web')) {
            return $this->error([], 'Unauthorize access', 403);
        } // end permission checking

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

        return $this->success($data);
    }

    public function orderReportCancelled()
    {
        if (!Auth::user()->hasPermissionTo('finance', 'web')) {
            return $this->error([], 'Unauthorize access', 403);
        } // end permission checking
        
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

        return $this->success($data);
    }

    public function revenueReportConsultationIndex()
    {
        if (!Auth::user()->hasPermissionTo('finance', 'web')) {
            return $this->error([], 'Unauthorize access', 403);
        } // end permission checking

        $data['consultationOrderItems'] = Order_item::whereNotNUll('consultation_slot_id')->whereHas('order', function ($q) {
            $q->where('payment_status', 'paid');
        })->select(['owner_user_id', 'consultation_slot_id', 'consultation_date', DB::raw("SUM(admin_commission) as total_admin_commission"), DB::raw("SUM(owner_balance) as total_owner_balance"), DB::raw("COUNT(id) as total_enroll")])
            ->groupBy('consultation_slot_id')->latest()->paginate();

        // Start:: Consultation Calculation
        $data['total_enrolment_in_consultation'] = Order_item::whereNotNull('consultation_slot_id')->whereHas('order', function ($q) {
            $q->where('payment_status', 'paid');
        })->count();

        $orderItems = Order_item::whereNotNull('consultation_slot_id')->whereHas('order', function ($q) {
            $q->where('payment_status', 'paid');
        });
        $data['grand_money_from_consultation'] = $orderItems->sum('admin_commission') + $orderItems->sum('owner_balance');

        $cancelConsultationOrderItemIds = BookingHistory::whereStatus(2)->where('send_back_money_status', 1)->whereHas('order', function ($q) {
            $q->where('payment_status', 'paid');
        })->pluck('order_item_id')->toArray();

        $orderItems = Order_item::whereIn('id', $cancelConsultationOrderItemIds);
        $data['cancel_consultation_money'] = $orderItems->sum('admin_commission') + $orderItems->sum('owner_balance');
        $data['cancel_admin_commission_consultation_money'] = $orderItems->sum('admin_commission');
        $data['cancel_instructor_commission_consultation_money'] = $orderItems->sum('owner_balance');
        $data['total_cancel_consultation'] = count($cancelConsultationOrderItemIds);

        return $this->success($data);
    }

    public function cancelConsultationList()
    {
        if (!Auth::user()->hasPermissionTo('finance', 'web')) {
            return $this->error([], 'Unauthorize access', 403);
        } // end permission checking

        $data['consultationOrderItems'] = Order_item::whereNotNUll('consultation_slot_id')->whereHas('order', function ($q) {
            $q->where('payment_status', 'paid');
        })->select(['owner_user_id', 'consultation_slot_id', 'consultation_date', DB::raw("SUM(admin_commission) as total_admin_commission"), DB::raw("SUM(owner_balance) as total_owner_balance"), DB::raw("COUNT(id) as total_enroll")])
            ->groupBy('consultation_slot_id')->latest()->paginate();

        $data['bookingHistories'] = BookingHistory::whereHas('order', function ($q) {
            $q->where('payment_status', 'paid');
        })->cancelled()->latest()->paginate();

        return $this->success($data);
    }
}
