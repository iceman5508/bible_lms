<?php

namespace App\Http\Controllers\Organization;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\GmeetSetting;
use App\Models\LiveClass;
use App\Models\ZoomSetting;
use App\Tools\Repositories\Crud;
use App\Traits\General;
use App\Traits\SendNotification;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use JoisarJignesh\Bigbluebutton\Facades\Bigbluebutton;

class LiveClassController extends Controller
{
    use General, SendNotification;

    const MEETING_TYPE_INSTANT = 1;
    const MEETING_TYPE_SCHEDULE = 2;
    const MEETING_TYPE_RECURRING = 3;
    const MEETING_TYPE_FIXED_RECURRING_FIXED = 8;
    protected $model, $courseModel;

    public function __construct(LiveClass $liveClass, Course $course)
    {
        $this->model = new CRUD($liveClass);
        $this->courseModel = new CRUD($course);
    }

    public function courseLiveClassIndex()
    {
        $data['title'] = 'Live Class';
        $data['navLiveClassActiveClass'] = 'active';

        $now = now();

        $data['courses'] = Course::whereUserId(Auth::user()->id);
       $data['courses'] = $data['courses']->withCount([
            'liveClasses as total_upcoming' => function ($q) {
                $q->select(DB::raw("COUNT(id) as total_upcoming"));
                $q->where(function($q){
                    $q->whereDate('date', now());
                    $q->whereTime('time', '>=', now());
                });
                $q->orWhere(function($q){
                    $q->whereDate('date', '>', now());
                });
            },
            'liveClasses as total_past' => function ($q) {
                $q->select(DB::raw("COUNT(id) as total_past"));
                $q->where(function($q){
                    $q->whereDate('date', now());
                    $q->whereTime('time', '<', now());
                });
                $q->orWhere(function($q){
                    $q->whereDate('date', '<', now());
                });
            },

        ])->paginate();


        return view('organization.live_class.live-class-course-list', $data);
    }

    public function liveClassIndex(Request $request, $course_uuid)
    {
        $data['title'] = 'Live Class List';
        $data['navLiveClassActiveClass'] = 'active';
        if ($request->past) {
            $data['navPastActive'] = 'active';
            $data['tabPastActive'] = 'show active';
        } else {
            $data['navUpcomingActive'] = 'active';
            $data['tabUpcomingActive'] = 'show active';
        }

        $data['course'] = $this->courseModel->getRecordByUuid($course_uuid);
        $data['upcoming_live_classes'] = LiveClass::whereCourseId($data['course']->id)->whereUserId(Auth::user()->id)
            ->where(function($q){
                $q->whereDate('date', now());
                $q->whereTime('time', '>', now());
            })
            ->orWhere(function($q){
                $q->whereDate('date', '>', now());
            })
            ->latest()->paginate(15, '*', 'upcoming');

        $data['current_live_classes'] = LiveClass::whereCourseId($data['course']->id)->whereUserId(Auth::user()->id)
            ->where(function($q){
                $q->whereDate('date', now());
                $q->whereTime('time', '<=', now());
                $q->whereTime(DB::raw('SEC_TO_TIME((duration*60) + TIME_TO_SEC(time))'), '>=', now());
            })
            ->latest()->paginate(15, '*', 'past');
    
        $data['past_live_classes'] = LiveClass::whereCourseId($data['course']->id)->whereUserId(Auth::user()->id)
            ->where(function($q){
                $q->whereDate('date', now());
                $q->whereTime(DB::raw('SEC_TO_TIME((duration*60) + TIME_TO_SEC(time))'), '<', now());
            })
            ->orWhere(function($q){
                $q->whereDate('date', '<', now());
            })
            ->latest()->paginate(15, '*', 'past');




        return view('organization.live_class.live-class-list', $data);
    }

    public function createLiveCLass($course_uuid)
    {
        $data['title'] = 'Live Class Create';
        $data['navLiveClassActiveClass'] = 'active';
        $data['course'] = $this->courseModel->getRecordByUuid($course_uuid);
        $data['gmeet'] = GmeetSetting::whereUserId(Auth::id())->where('status', GMEET_AUTHORIZE)->first();
        return view('organization.live_class.create', $data);
    }

    public function store(Request $request, $course_uuid)
    {
        $request->validate([
            'class_topic' => 'required|max:255',
            'date' => 'required',
            'duration' => 'required',
            'moderator_pw' => 'nullable|min:6',
            'attendee_pw' => 'nullable|min:6',
        ]);

        try {
            $course = $this->courseModel->getRecordByUuid($course_uuid);
            $class = new LiveClass();
            $class->course_id = $course->id;
            $class->class_topic = $request->class_topic;
            $dateTime = strtotime($request->date);
            $date = date('Y-m-d', $dateTime);
            $time = date('H:i:s', $dateTime);
            $class->date = $date;
            $class->time = $time;
            $class->duration = $request->duration;
            $class->start_url = $request->start_url;
            $class->join_url = $request->join_url;
            $class->meeting_host_name = $request->meeting_host_name;
            $class->meeting_id = $request->meeting_host_name == 'jitsi' ? $request->jitsi_meeting_id : $class->id . rand();
            $class->moderator_pw = $request->moderator_pw;
            $class->attendee_pw = $request->attendee_pw;
            $class->save();

            /** ====== Start:: BigBlueButton create meeting ===== */
            if ($class->meeting_host_name == 'bbb') {
                Bigbluebutton::create([
                    'meetingID' => $class->meeting_id,
                    'meetingName' => $class->class_topic,
                    'attendeePW' => $request->moderator_pw,
                    'moderatorPW' => $request->attendee_pw
                ]);
            }
            /** ====== End:: BigBlueButton create meeting ===== */

            /** ====== Start:: Gmeet create meeting ===== */
            if ($class->meeting_host_name == 'gmeet') {
                $endDate = \Carbon\Carbon::parse($class->date)->addMinutes($class->duration);
                $link = GmeetSetting::createMeeting($class->class_topic, $class->date, $endDate);
                $class->join_url = $link;
                $class->save();
            }
            /** ====== End:: Gmeet create meeting ===== */
            else if ($class->meeting_host_name == 'jitsi') {
                $link =  get_option('jitsi_server_base_url').$request->jitsi_meeting_id;
                $class->join_url = $link;
                $class->save();
            }
            else if ($class->meeting_host_name == 'agora') {
                $class->join_url = route('student.agora-open-class', ['uuid' => $class->uuid, 'type' => 'live_class']);
                $class->save();
            }


            /** ====== send notification to student ===== */
            $students = Enrollment::where('course_id', $course->id)->select('user_id')->get();
            foreach ($students as $student) {
                $text = __("New Live Class Added");
                $target_url = route('student.my-course.show', $course->slug);
                $this->send($text, 3, $target_url, $student->user_id);
            }
            /** ====== send notification to student ===== */

            DB::commit();
            $this->showToastrMessage('success', 'Live Class Created Successfully');
            return redirect()->route('organization.live-class.index', $course_uuid);
        } catch (Exception $e) {
            DB::rollBack();
            $this->showToastrMessage('error', 'Please check your credentials');
            return redirect()->back()->withInput();
        }
    }

    public function view($course_uuid, $uuid)
    {
        $data['title'] = 'Live Class Details';
        $data['navLiveClassActiveClass'] = 'active';
        $data['course'] = $this->courseModel->getRecordByUuid($course_uuid);
        $data['liveClasses'] = $this->model->getRecordByUuid($uuid);
        return view('organization.live_class.view', $data);
    }

    public function delete($uuid)
    {
        $this->model->deleteByUuid($uuid);
        $this->showToastrMessage('error', __('Deleted Successfully'));
        return redirect()->back();
    }

    public function getZoomMeetingLink(Request $request)
    {
        $data = $request->all();

        $zoom = ZoomSetting::find(auth()->id());

        $meeting = \Jubaer\Zoom\Facades\Zoom::createMeeting([
            "agenda" => (!empty($data['agenda'])) ? $data['agenda'] : null,
            "topic" => $data['topic'],
            "type" => 2, // 1 => instant, 2 => scheduled, 3 => recurring with no fixed time, 8 => recurring with fixed time
            "duration" => $data['duration'], // in minutes
            "timezone" => @$zoom->timezone ?? 'Asia/Dhaka', // set your timezone
            "start_time" => $this->toZoomTimeFormat($data['start_date']), // set your start time
            "settings" => [
                'host_video' =>  @$zoom->host_video ? true : false,
                'participant_video' =>  @$zoom->participant_video ? true : false,
                'waiting_room' =>  @$zoom->waiting_room ? true : false,
                'join_before_host' => false, // if you want to join before host set true otherwise set false
                'mute_upon_entry' => false, // if you want to mute participants when they join the meeting set true otherwise set false
                'audio' => 'both', // values are 'both', 'telephony', 'voip'. default is both.
                'auto_recording' => 'none', // values are 'none', 'local', 'cloud'. default is none.
                'approval_type' => 0, // 0 => Automatically Approve, 1 => Manually Approve, 2 => No Registration Required
            ],

        ]);

        return response()->json([
            'start_url' => $meeting['data']['start_url'],
            'join_url' => $meeting['data']['join_url']
        ]);
    }

    public function toZoomTimeFormat(string $dateTime)
    {
        try {
            $date = new \DateTime($dateTime);

            return $date->format('Y-m-d\TH:i:s');
        } catch (\Exception $e) {
            Log::error('ZoomJWT->toZoomTimeFormat : ' . $e->getMessage());

            return '';
        }
    }


    public function bigBlueButtonJoinMeeting($liveClassId)
    {
        $liveClass = LiveClass::find($liveClassId);
        if ($liveClass) {
            return redirect()->to(
                Bigbluebutton::join([
                    'meetingID' => $liveClass->meeting_id,
                    'userName' => auth()->user()->instructor()->name ?? auth()->user()->student()->name ?? auth()->user()->name,
                    'password' => $liveClass->attendee_pw //which user role want to join set password here
                ])
            );
        } else {
            $this->showToastrMessage('error', __('Live Class is not found'));
            return redirect()->back();
        }
    }

    public function jitsiJoinMeeting($liveClassUUId)
    {
        $data['title'] = 'Jitsi Meet';
        $data['liveClass'] = LiveClass::where('uuid', $liveClassUUId)->firstOrFail();
        return view('organization.live_class.jitsi-live-class')->with($data);
    }
}
