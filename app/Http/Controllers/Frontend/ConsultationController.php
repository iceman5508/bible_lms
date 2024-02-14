<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\BookingHistory;
use App\Models\ConsultationSlot;
use App\Models\Course;
use App\Models\Instructor;
use App\Models\InstructorConsultationDayStatus;
use App\Models\Order;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ConsultationController extends Controller
{
    private $consultationPaginateValue = 9;

    public function getInstructorBookingTime(Request $request)
    {
        $date = $request->bookingDate;
        $data['bookingDay'] = date('l', strtotime($date));
        $day = 7;
        if ($data['bookingDay'] == 'Sunday') {
            $day = 0;
        } elseif ($data['bookingDay'] == 'Monday') {
            $day = 1;
        } elseif ($data['bookingDay'] == 'Tuesday') {
            $day = 2;
        } elseif ($data['bookingDay'] == 'Wednesday') {
            $day = 3;
        } elseif ($data['bookingDay'] == 'Thursday') {
            $day = 4;
        } elseif ($data['bookingDay'] == 'Friday') {
            $day = 5;
        } elseif ($data['bookingDay'] == 'Saturday') {
            $day = 6;
        }

        $orderIds =  Order::where('payment_status', 'paid')->orWhere('payment_status', 'free')->pluck('id')->toArray();

        $consultationSlotIds = BookingHistory::whereIn('order_id', $orderIds)->where('instructor_user_id', $request->user_id)->where('date', $date)->pluck('consultation_slot_id')->toArray();
        $data['slots'] = ConsultationSlot::whereNotIn('id', $consultationSlotIds);
        $data['slots'] = $data['slots']->where('user_id', $request->user_id)->where('day', $day)->get();

        return view('frontend.home.partial.consultation-booking-day-time', $data);
    }

    public function consultationInstructorList()
    {
        $data['consultationInstructors'] = User::query()
                ->leftJoin('instructors as ins', 'ins.user_id', '=', 'users.id')
                ->leftJoin('organizations as org', 'org.user_id', '=', 'users.id')
                ->whereIn('users.role', [USER_ROLE_INSTRUCTOR,USER_ROLE_ORGANIZATION])
                ->where(function($q){
                    $q->where('ins.status', STATUS_APPROVED)
                    ->orWhere('org.status', STATUS_APPROVED);
                })
                ->where(function($q){
                    $q->where('ins.consultation_available', STATUS_APPROVED)
                    ->orWhere('org.consultation_available', STATUS_APPROVED);
                })
                ->with('badges')
                ->select('users.*', 'ins.organization_id', DB::raw(selectStatement()))
                ->paginate($this->consultationPaginateValue);
        $data['highest_price'] = Instructor::max('hourly_rate');
        return view('frontend.consultation.instructor-consultation-list', $data);
    }

    public function paginationFetchData(Request $request)
    {
        $data['consultationInstructors'] = $this->filterConsultationInstructorData($request);
        if ($request->ajax()) {
            return view('frontend.consultation.render-consultation-instructor-list')->with($data);
        }
    }

    public function getFilterConsultationInstructor(Request $request)
    {
        $data['consultationInstructors'] = $this->filterConsultationInstructorData($request);
        return view('frontend.consultation.render-consultation-instructor-list')->with($data);
    }

    public function filterConsultationInstructorData($request)
    {
        $min_hourly_rate = $request->min_hourly_rate;
        $max_hourly_rate = $request->max_hourly_rate;
        $sortBy_id = $request->sortBy_id;
        $ratingIds = $request->ratingIds ?? [];
        $search_name = $request->search_name;
        $typeIds = $request->typeIds ?? [];

        $users = User::query()
                ->leftJoin('instructors as ins', 'ins.user_id', '=', 'users.id')
                ->leftJoin('organizations as org', 'org.user_id', '=', 'users.id')
                ->whereIn('users.role', [USER_ROLE_INSTRUCTOR,USER_ROLE_ORGANIZATION])
                ->where(function($q){
                    $q->where('ins.status', STATUS_APPROVED)
                    ->orWhere('org.status', STATUS_APPROVED);
                })->where(function($q){
                    $q->where('ins.consultation_available', 1);
                    $q->orWhere('org.consultation_available', 1);
                });

        if ($search_name){
            $users->where('users.name', 'LIKE', '%' . $search_name. '%');
        }
        
        if($typeIds && count($typeIds) != 2){
            $users->where(function($q) use($typeIds){
                $q->where('ins.available_type', $typeIds)
                ->orWhere('org.available_type', $typeIds);
            });
        }

        if(is_array($ratingIds) && count($ratingIds)){
            $min = min($ratingIds);
            $max = max($ratingIds);
            $users->leftJoin(DB::raw('(select users.id, avg(c.average_rating) as avg_rating
            from
                users
            join instructors i on
                i.user_id = users.id
            join courses c on
                c.user_id = users.id and c.average_rating > 0
                group by users.id) as average_table'), 'average_table.id', '=', 'users.id')
            ->whereBetween('average_table.avg_rating', [$min, $max]);
        }

        if ($min_hourly_rate && $max_hourly_rate) {
            $users->where(function($q) use($min_hourly_rate, $max_hourly_rate){
                $q->whereBetween('ins.hourly_rate', [$min_hourly_rate, $max_hourly_rate])
                ->orWhereBetween('org.hourly_rate', [$min_hourly_rate, $max_hourly_rate]);
            });
        } else if ($min_hourly_rate) {
            $users->where(function($q) use($min_hourly_rate){
                $q->where('ins.hourly_rate', '>=', $min_hourly_rate)
                ->orWhere('org.hourly_rate', '>=', $min_hourly_rate);
            });
        } else if ($max_hourly_rate) {
            $users->where(function($q) use($max_hourly_rate){
                $q->where('ins.hourly_rate', '<=', $max_hourly_rate)
                ->orWhere('org.hourly_rate', '<=', $max_hourly_rate);
            });
        }

        if ($sortBy_id == 2 || $sortBy_id == 1) {
            $users->orderBy('users.id', 'DESC');
        } elseif ($sortBy_id == 3) {
            $users->orderBy('users.id', 'ASC');
        }

        $users->groupBy('users.id');
        $users = $users->select('users.id', 'users.name', 'users.email', 'users.area_code', 'users.mobile_number', 'users.role', 'users.phone_number', 'users.lat', 'users.long', 'users.image', 'users.avatar', 'users.created_at', 'users.is_affiliator', 'users.balance', 'ins.organization_id', DB::raw(selectStatement()))->paginate($this->consultationPaginateValue);

        return $users;
    }

    public function getOffDays($user_id)
    {
        $data['days'] = InstructorConsultationDayStatus::whereUserId($user_id)->pluck('day')->toArray();
        return response()->json($data);
    }
}
