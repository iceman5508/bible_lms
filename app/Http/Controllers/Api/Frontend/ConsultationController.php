<?php

namespace App\Http\Controllers\Api\Frontend;

use App\Http\Controllers\Controller;
use App\Models\BookingHistory;
use App\Models\ConsultationSlot;
use App\Models\Instructor;
use App\Models\InstructorConsultationDayStatus;
use App\Models\Order;
use App\Models\User;
use App\Traits\ApiStatusTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ConsultationController extends Controller
{
    use ApiStatusTrait;

    private $consultationPaginateValue = 10;

    public function consultationInstructorList(Request $request)
    {
        $lastPage = false;
        
        $consultationInstructors = User::query()
            ->leftJoin('instructors as ins', 'ins.user_id', '=', 'users.id')
            ->leftJoin('organizations as org', 'org.user_id', '=', 'users.id')
            ->whereIn('users.role', [USER_ROLE_INSTRUCTOR, USER_ROLE_ORGANIZATION])
            ->where(function ($q) {
                $q->where('ins.status', STATUS_APPROVED)
                    ->orWhere('org.status', STATUS_APPROVED);
            })
            ->where(function ($q) {
                $q->where('ins.consultation_available', STATUS_APPROVED)
                    ->orWhere('org.consultation_available', STATUS_APPROVED);
            })
            ->with('badges:name,type,from,to,description')
            ->select('users.*', 'ins.organization_id', DB::raw(selectStatement()))
            ->paginate(10);

        $data = [];
        foreach($consultationInstructors as $consultationInstructor){
            $data['instructors'][] = [
                "id" => $consultationInstructor->id,
                "name" => $consultationInstructor->name,
                "image_url" => $consultationInstructor->image_url,
                "email" => $consultationInstructor->email,
                "area_code" => $consultationInstructor->area_code,
                "mobile_number" => $consultationInstructor->mobile_number,
                "email_verified_at" => $consultationInstructor->email_verified_at,
                "role" => $consultationInstructor->role,
                "phone_number" => $consultationInstructor->phone_number,
                "is_affiliator" => $consultationInstructor->is_affiliator,
                "organization_id" => $consultationInstructor->organization_id,
                "uuid" => $consultationInstructor->uuid,
                "professional_title" => $consultationInstructor->professional_title,
                "postal_code" => $consultationInstructor->postal_code,
                "about_me" => $consultationInstructor->about_me,
                "gender" => $consultationInstructor->gender,
                "social_link" => $consultationInstructor->social_link,
                "slug" => $consultationInstructor->slug,
                "remove_from_web_search" => $consultationInstructor->remove_from_web_search,
                "is_offline" => $consultationInstructor->is_offline,
                "offline_message" => $consultationInstructor->offline_message,
                "consultation_available" => $consultationInstructor->consultation_available,
                "hourly_rate" => $consultationInstructor->hourly_rate,
                "hourly_old_rate" => $consultationInstructor->hourly_old_rate,
                "available_type" => $consultationInstructor->available_type,
                "approval_date" => $consultationInstructor->approval_date,
                'average_rating' => $consultationInstructor->courses->where('average_rating', '>', 0)->avg('average_rating') ?? 0,
                'total_rating' => count($consultationInstructor->courses->where('average_rating', '>', 0)),
                'badges' => $consultationInstructor->badges,
            ];
        }

        if(!count($consultationInstructors)){
            $data['instructors'] = [];
        }

        if($consultationInstructors->lastPage() == $request->page){
            $lastPage = true;
        }

        $data['per_page'] = 10;
        $data['current_page'] = $request->page ?? 1;
        $data['lastPage'] = $lastPage;
        $data['status'] = true;

        return $this->success($data);
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

    public function consultationDetails($user_id)
    {
        $data['days'] = InstructorConsultationDayStatus::whereUserId($user_id)->pluck('day')->toArray();
        return $this->success($data);
    }

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
        $returnData['slots'] = $data['slots']->where('user_id', $request->user_id)->where('day', $day)->get();

        return $this->success($returnData);
    }
}
