<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Services\Agora\RtcTokenBuilder;
use App\Http\Services\Agora\RtmTokenBuilder;
use App\Http\Controllers\Controller;
use App\Models\BookingHistory;
use App\Models\LiveClass;
use Carbon\Carbon;

class AgoraController extends Controller
{
    public $appId;
    private $appCertificate;

    public function __construct()
    {
        $this->appId = get_option('agora_app_id');
        $this->appCertificate = get_option('agora_app_certificate');
    }

    public function getRTCToken(string $channelName, bool $isHost): string
    {
        $role = $isHost ? RtcTokenBuilder::RolePublisher : RtcTokenBuilder::RoleAttendee;

        $expireTimeInSeconds = 3600;
        $currentTimestamp = now()->getTimestamp();
        $privilegeExpiredTs = $currentTimestamp + $expireTimeInSeconds;

        return RtcTokenBuilder::buildTokenWithUserAccount($this->appId, $this->appCertificate, $channelName, null, $role, $privilegeExpiredTs);
    }

    public function getRTMToken($channelName): string
    {
        $expireTimeInSeconds = 3600;
        $currentTimestamp = now()->getTimestamp();
        $privilegeExpiredTs = $currentTimestamp + $expireTimeInSeconds;

        return RtmTokenBuilder::buildToken($this->appId, $this->appCertificate, $channelName, null, $privilegeExpiredTs);
    }
  
    public function openLiveClass($uuid, $type)
    {
        $streamRole = 'audience';
        if($type == 'live_class'){
            $session = LiveClass::where('uuid', $uuid)->first();
            if($session->user_id == auth()->id()){
                $streamRole = 'host';
            }
            $channelName = $session->class_topic;
            $startTime = Carbon::parse($session->date.' '.$session->time);
            $hostUserId = $session->user_id;
        }
        else{
            $session = BookingHistory::where('uuid', $uuid)->first();
            if($session->instructor_user_id == auth()->id()){
                $streamRole = 'host';
            }
            $channelName = "Consultation with ".$session->user->name;
            $startTime = Carbon::parse($session->date.' '.explode('-', $session->time)[0]);
            $hostUserId = $session->instructor_user_id;
        }

        if(is_null($session)){
            $this->showToastrMessage('success', __('This session was not found.')) ;
            return redirect()->back();
        }

        $accountName = "user ".auth()->id();
        $agoraController = new AgoraController();
        $isHost = ($streamRole === 'host');
        $rtcToken = $agoraController->getRTCToken($channelName, $isHost);
        $rtmToken = $agoraController->getRTMToken($accountName);
        $data = [
            'pageTitle' => $channelName,
            'session' => $session,
            'isHost' => $isHost,
            'appId' =>  $this->appId,
            'accountName' => $accountName,
            'channelName' => $channelName,
            'rtcToken' => $rtcToken,
            'rtmToken' => $rtmToken,
            'streamRole' => $streamRole,
            'notStarted' => !$isHost,
            'streamStartAt' => $startTime,
            'authUserId' => auth()->id(),
            'hostUserId' => $hostUserId,
            'sessionStreamType' => ($type == 'live_class') ? 'multiple' : 'single'
        ];
    
        return view('frontend.student.agora.index', $data);
    }
    
}
