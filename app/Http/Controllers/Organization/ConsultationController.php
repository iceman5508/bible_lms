<?php

namespace App\Http\Controllers\Organization;

use App\Http\Controllers\Controller;
use App\Models\BookingHistory;
use App\Models\ConsultationSlot;
use App\Models\GmeetSetting;
use App\Models\InstructorConsultationDayStatus;
use App\Models\Organization;
use App\Traits\General;
use App\Traits\SendNotification;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use JoisarJignesh\Bigbluebutton\Facades\Bigbluebutton;

class ConsultationController extends Controller
{
    use General , SendNotification;
    public function dashboard()
    {
        $data['title'] = 'Consultation Dashboard';
        $data['navConsultationActiveClass'] = 'has-open';
        $data['subNavConsultationDashboardActiveClass'] = 'active';
        $data['organization'] = Organization::whereUserId(Auth::id())->first();
        return view('organization.consultation.dashboard')->with($data);
    }

    public function availabilityUpdate(Request $request)
    {
        $request->validate([
            'consultation_available' => 'required',
            'available_type' => 'required',
            'hourly_rate' => 'required_if:consultation_available,==,1|min:0'
        ],[
            'hourly_rate.required_if' => 'Hourly rate field is required.'
        ]);
        $organization = Organization::whereUserId(Auth::id())->first();
        $organization->consultation_available = $request->consultation_available;
        $organization->available_type = $request->available_type;
        $organization->hourly_rate = $request->hourly_rate;
        $organization->hourly_old_rate = $request->hourly_old_rate;
        $organization->consultancy_area = $request->consultancy_area ?? 3;
        $organization->is_offline = $request->is_offline ?? 0;
        $organization->offline_message = $request->offline_message;
        $organization->is_subscription_enable = $request->is_subscription_enable ?? 0;
        $organization->save();

        $this->showToastrMessage('success', __('Updated Successfully'));
        return redirect()->route('organization.consultation.dashboard');
    }

    public function slotStore(Request $request)
    {
        $count = ConsultationSlot::where('user_id', auth()->id())->count();
        if(hasLimitSaaS(PACKAGE_RULE_CONSULTANCY, PACKAGE_TYPE_SAAS_ORGANIZATION, $count)){

            $request->validate([
                "starTimes"  => "required|min:1",
                "endTimes"  => "required|min:1",
            ]);

            $timeAddCheck = false;
            $startTimes = $request->starTimes;
            $endTimes = $request->endTimes;

            if (count($startTimes) == count($endTimes)) {
                /*
                * You can run any array of $startTime and $endTimes
                */

                foreach ($startTimes as $key => $value) {
                    $datetime1 = new DateTime(date('h:i:s A', strtotime($startTimes[$key])));
                    $datetime2 = new DateTime(date('h:i A', strtotime($endTimes[$key])));
                    $interval = $datetime1->diff($datetime2);
                    $hours = $interval->format('%h');
                    $minutes = $interval->format('%i');

                    $startTime =  date('h:i A', strtotime($startTimes[$key]));
                    $endTime =  date('h:i A', strtotime($endTimes[$key]));
                    $timeAddCheck = true;

                    $slot = new ConsultationSlot();
                    $slot->user_id = Auth::id();
                    $slot->time = $startTime . ' - ' . $endTime;
                    $slot->day = $request->day;
                    $slot->duration = $hours . ($hours > 1 ? " Hours " : " Hour ") . $minutes . ($minutes > 1 ? " Minutes" : " Minute") ;
                    $slot->hour_duration = $hours;
                    $slot->minute_duration = $minutes;
                    $slot->save();
                }
            }

        if ($timeAddCheck){
            $this->showToastrMessage('success', __('Slot Added successfully'));
        } else {
            $this->showToastrMessage('error', __('Something is wrong! Try again.'));
        }

            return redirect()->back();
        }
        else{
            $this->showToastrMessage('error', __('Your Consultancy Slot Create limit has been finish.'));
            return redirect()->back();
        }
    }

    public function slotView($day)
    {
        $data['slots'] = ConsultationSlot::whereUserId(Auth::id())->where('day', $day)->get();
        return view('organization.consultation.partial.render-slot-list', $data);
    }

    public function slotDelete($id)
    {
        $slot = ConsultationSlot::whereUserId(Auth::id())->where('id', $id)->first();
        if (!$slot){
            return response()->json([
                'status' => 404,
                'msg' => __('Slot Not Found!')
            ]);
        }

        $slot->delete();
        return response()->json([
            'msg' => __('Deleted Successfully')
        ]);
    }

    public function dayAvailableStatusChange($day)
    {
        $item = InstructorConsultationDayStatus::whereUserId(Auth::id())->where('day', $day)->first();
        if (!$item){
            $item = new InstructorConsultationDayStatus();
            $item->user_id = Auth::id();
            $item->day = $day;
            $item->save();
        } else {
            $item->delete();
        }

        $this->showToastrMessage('success', __('Status Change Successfully'));
        return redirect()->back();
    }

    public function bookingRequest()
    {
        $data['title'] = 'Booking Request';
        $data['navConsultationActiveClass'] = 'has-open';
        $data['subNavBookingRequestActiveClass'] = 'active';
        $data['bookingHistories'] = BookingHistory::where('instructor_user_id', Auth::id())->whereHas('order', function ($q) {
            $q->where('payment_status', 'paid');
        })->pending()->paginate();

        return view('organization.consultation.booking-request', $data);
    }

    public function cancelReason(Request $request, $uuid)
    {
        $request->validate([
            'cancel_reason' => 'required'
        ]);

        $booking = BookingHistory::where(['instructor_user_id' => Auth::id(), 'uuid'=> $uuid])->firstOrFail();
        $booking->cancel_reason =  $request->cancel_reason;
        $booking->status = 2;
        $booking->save();

        $text = __("Your consultation booking request cancelled");
        $target_url = route('student.my-consultation');
        $this->send($text, 3, $target_url, $booking->student_user_id);

        $this->showToastrMessage('success', __('Status Change Successfully'));
        return redirect()->back();
    }

    public function bookingHistory(Request $request)
    {
        $data['title'] = 'Booking History';
        $data['navConsultationActiveClass'] = 'has-open';
        $data['subNavBookingHistoryActiveClass'] = 'active';

        if($request->completed) {
            $data['completedActive'] = 'active';
            $data['completedShowActive'] = 'show active';
        } elseif ($request->cancelled) {
            $data['cancelledActive'] = 'active';
            $data['cancelledShowActive'] = 'show active';
        } else {
            $data['upcomingActive'] = 'active';
            $data['upcomingShowActive'] = 'show active';
        }
        $data['bookingHistoryUpcoming'] = BookingHistory::where('instructor_user_id', Auth::id())->whereHas('order', function ($q) {
            $q->where('payment_status', 'paid');
        })->approved()->paginate(15, ['*'], 'upcoming');

        $data['bookingHistoryCompleted'] = BookingHistory::where('instructor_user_id', Auth::id())->whereHas('order', function ($q) {
            $q->where('payment_status', 'paid');
        })->completed()->paginate(15, ['*'], 'completed');

        $data['bookingHistoryCancelled'] = BookingHistory::where('instructor_user_id', Auth::id())->whereHas('order', function ($q) {
            $q->where('payment_status', 'paid');
        })->cancelled()->paginate(15, ['*'], 'cancelled');

        $data['gmeet'] = GmeetSetting::whereUserId(Auth::id())->where('status', GMEET_AUTHORIZE)->first();
        return view('organization.consultation.booking-history', $data);
    }

    public function bookingStatus($uuid, $status)
    {
        /*
         * 0=pending
         * 1=Approve
         * 2=Cancel
         * 3=Completed
         */

        $booking = BookingHistory::where('uuid', $uuid)->firstOrFail();
        $booking->status = $status;
        $booking->save();

        $this->showToastrMessage('success', __('Status Change Successfully'));
        return response()->json([
            'msg' => 'success'
        ]);
    }

    public function bookingMeetingStore(Request $request, $uuid)
    {
        $request->validate([
            'moderator_pw' => 'nullable|min:6',
            'attendee_pw' => 'nullable|min:6',
        ]);

        $booking = BookingHistory::where('uuid', $uuid)->firstOrFail();
        $join_url = $request->join_url;
        $startTime = Carbon::parse($booking->date.' '.explode('-', $booking->time)[0]);
        $endTime = Carbon::parse($booking->date.' '.explode('-', $booking->time)[1]);
        /** ====== Start:: Gmeet create meeting ===== */
        if ($request->meeting_host_name == 'gmeet') {
            $link = GmeetSetting::createMeeting('Consultation', $startTime, $endTime);
            $join_url = $link;
        }
        else if ($request->meeting_host_name == 'agora') {
            $join_url = route('student.agora-open-class', ['uuid' => $request->uuid, 'type' => 'consultation']);
        }

        $booking->start_url = $request->start_url;
        $booking->join_url = $join_url;
        $booking->meeting_id = $request->meeting_host_name == 'jitsi' ? $request->jitsi_meeting_id : $booking->id . rand();
        $booking->meeting_password = $request->meeting_password;
        $booking->meeting_host_name = $request->meeting_host_name;
        $booking->moderator_pw = $request->moderator_pw;
        $booking->attendee_pw = $request->attendee_pw;
        $booking->save();

        $this->showToastrMessage('success', __('Meeting Create  Successfully'));
        return redirect()->back();
    }

    public function jitsiJoinMeeting($uuid)
    {
        $data['title'] = 'Jitsi Meet';
        $data['bookingHistory'] = BookingHistory::where('uuid', $uuid)->firstOrFail();
        return view('organization.consultation.jitsi-consultation')->with($data);
    }

    public function studentBigBlueButtonJoinMeeting($id)
    {
        $bookingHistory = BookingHistory::findOrFail($id);
        if ($bookingHistory) {
            return redirect()->to(
                Bigbluebutton::join([
                    'meetingID' => $bookingHistory->meeting_id,
                    'userName' => auth()->user()->student()->name ?? auth()->user()->name,
                    'password' => $bookingHistory->attendee_pw //which user role want to join set password here
                ])
            );
        } else {
            $this->showToastrMessage('error', __('Meet Link is not found'));
            return redirect()->back();
        }
    }

    public function instructorBigBlueButtonJoinMeeting($id)
    {
        $bookingHistory = BookingHistory::findOrFail($id);
        if ($bookingHistory){
            return redirect()->to(
                Bigbluebutton::join([
                    'meetingID' => $bookingHistory->meeting_id,
                    'userName' => auth()->user()->instructor()->name ?? auth()->user()->student()->name ?? auth()->user()->name,
                    'password' => $bookingHistory->attendee_pw //which user role want to join set password here
                ])
            );
        } else {
            $this->showToastrMessage('error', __('Meet Link is not found'));
            return redirect()->back();
        }
    }
}
