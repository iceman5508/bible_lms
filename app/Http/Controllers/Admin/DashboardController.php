<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\Course;
use App\Models\Course_lecture;
use App\Models\Course_lesson;
use App\Models\Order;
use App\Models\Order_item;
use App\Models\User;
use App\Models\Withdraw;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;

class DashboardController extends Controller
{
    public function dashboard()
    {
        $data['title'] = 'Dashboard';
        $data['total_admins'] = User::whereRole(1)->count();
        $data['total_instructors'] = User::whereRole(2)->count();
        $data['total_students'] = User::whereNotIn('role', [1])->count();
        $data['total_enrolments'] = Order_item::where('course_id', '!=', null)->whereHas('order', function ($q) {
            $q->where('payment_status', 'paid')->orWhere('payment_status', 'free');
        })->count();
        $data['total_courses'] = Course::count();
        $data['total_pending_courses'] = Course::where('status', 0)->count();
        $data['total_active_courses'] = Course::where('status', 1)->count();
        $data['total_review_pending_courses'] = Course::where('status', 2)->count();
        $data['total_hold_courses'] = Course::where('status', 3)->count();
        $data['total_draft_courses'] = Course::where('status', 4)->count();
        $data['total_free_courses'] = Course::where('learner_accessibility', 'free')->count();
        $data['total_paid_courses'] = Course::where('learner_accessibility', 'paid')->count();
        $data['total_lessons'] = Course_lesson::count();
        $data['total_lectures'] = Course_lecture::count();
        $data['total_blogs'] = Blog::count();
        $data['total_paid_sales'] = Order::where('payment_status', 'paid')->sum('grand_total');
        $data['total_free_sales'] = Order::where('payment_status', 'free')->count();

        $data['total_platform_charge'] = Order::where('payment_status', 'paid')->sum('platform_charge');
        $data['total_platform_charge_this_month'] = Order::where('payment_status', 'paid')->whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'))->sum('platform_charge');

        $total_order_admin_commission = Order::where('payment_status', 'paid')->withCount([
            'items as total_admin_commission' => function ($q) {
                $q->select(DB::raw('SUM(admin_commission)'));
            }
        ])->get();
        $data['total_admin_commission'] = $total_order_admin_commission->sum('total_admin_commission');

        $total_order_admin_commission_this_month = Order::where('payment_status', 'paid')->whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'))->withCount([
            'items as total_admin_commission' => function ($q) {
                $q->select(DB::raw('SUM(admin_commission)'));
            }
        ])->get();
        $data['total_admin_commission_this_month'] = $total_order_admin_commission_this_month->sum('total_admin_commission');

        //Start:: Revenue & Withdraw
        $total_admin_commission = Order::where('payment_status', 'paid')->withCount([
            'items as total_admin_commission' => function ($q) {
                $q->select(DB::raw("SUM(admin_commission)"));
            },
        ])->get();

        $data['total_revenue'] = $total_admin_commission->sum(function ($q) {
            return $q->platform_charge + $q->total_admin_commission;
        });

        $data['total_new_withdraws'] = Withdraw::whereStatus(0)->sum('amount');
        $data['total_complete_withdraws'] = Withdraw::whereStatus(1)->sum('amount');
        //End:: Revenue & Withdraw

        //Start:: Chart
        $totalMonths = collect([]);
        $totalMonthlyEnroll = collect([]);
        Order_item::where('course_id', '!=', null)->whereHas('order', function ($q) {
            $q->where('payment_status', 'paid')->orWhere('payment_status', 'free');
        })->select(DB::raw('COUNT(id) as total'), DB::raw('MONTHNAME(created_at) month'))
            ->groupby('month')
            ->get()
            ->map(function ($q) use ($totalMonths, $totalMonthlyEnroll) {
                $totalMonths->push($q->month);
                $totalMonthlyEnroll->push($q->total);
        });
        $data['totalMonths'] = $totalMonths;
        $data['totalMonthlyEnroll'] = $totalMonthlyEnroll;

        $totalYears = collect([]);
        $totalYearlyEnroll = collect([]);

        Order_item::where('course_id', '!=', null)->whereHas('order', function ($q) {
            $q->where('payment_status', 'paid')->orWhere('payment_status', 'free');
        })->select(DB::raw('COUNT(id) as total'), DB::raw('YEAR(created_at) year'))
        ->groupby('year')
        ->get()
        ->map(function ($q) use ($totalYears, $totalYearlyEnroll) {
            $totalYears->push($q->year);
            $totalYearlyEnroll->push($q->total);
        });

        $data['totalYears'] = $totalYears;
        $data['totalYearlyEnroll'] = $totalYearlyEnroll;
        // End:: Chart

        //Start:: Top Seller Chart
        $allName = collect([]);
        $allPercentage = collect([]);

        $total_order_item = Order_item::where('course_id', '!=', null)->whereHas('order', function ($q) {
            $q->where('payment_status', 'paid')->orWhere('payment_status', 'free');
        })->count();

        $data['TopSellersDetails'] = User::withCount([
            'orderItems as totalItems' => function ($q) {
                $q->select(DB::raw("COUNT(owner_user_id)"));
            }
        ])->orderBy('totalItems', 'desc')->take(5)->get();

        foreach($data['TopSellersDetails'] as $topSellersDetail)
        {
            if($total_order_item) {
                $percentage = (float)number_format((($topSellersDetail->totalItems / $total_order_item) * 100), 1);
                if ($percentage){
                    $allName->push(@$topSellersDetail->instructor->full_name ?? $topSellersDetail->name);
                    $allPercentage->push($percentage);
                }
            }
        }

        $data['allName'] = $allName;
        $data['allPercentage'] = $allPercentage;

        //End:: Top Seller Chart

        // Start:: Top courses & Requested Withdrawal
        $courseIds = Order_item::where('course_id', '!=', null)->whereHas('order', function ($q) {
            $q->where('payment_status', 'paid')->orWhere('payment_status', 'free');
        })->pluck('course_id')->toArray();
        $data['total_ten_courses'] = Course::whereIn('id', $courseIds)->withCount([
            'orderItems as totalOrder' => function ($q) {
                $q->select(DB::raw("COUNT(course_id)"));
            }
        ])->orderBy('totalOrder', 'desc')->take(10)->get();

        $data['withdraws'] = Withdraw::whereStatus(0)->orderBy('id', 'DESC')->paginate(20);
        // End:: Top courses & Requested Withdrawal

        return view('admin.dashboard', $data);
    }

}
