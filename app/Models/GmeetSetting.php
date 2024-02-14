<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class GmeetSetting extends Model
{
    protected $table = 'gmeet_settings';

    static function createMeeting($title, $startDate, $endDate)
    {
        $gmeet = GmeetSetting::whereUserId(auth()->id())->first();
        $meetingId = \Str::random(10);
        $calendarId = $gmeet->calender_id;
        $client = new \Google_Client();
        $accessToken = json_decode($gmeet->token,true);
        $client->setAccessToken($accessToken);
        
        if ($client->isAccessTokenExpired()) {
            // Refresh the token if possible, else fetch a new one.
            if ($client->getRefreshToken()) {
                $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
            } else {
                // Request authorization from the user.
                $authUrl = $client->createAuthUrl();
                return redirect($authUrl);
            }
        }
        
        $service = new \Google_Service_Calendar($client);
        
        $event = new \Google_Service_Calendar_Event(array(
            'summary' => $title,
            'start' => array(
                'dateTime' => Carbon::parse($startDate)->format(\DateTime::RFC3339),
                'timeZone' => $gmeet->timezone,
            ),
            'end' => array(
                'dateTime' => Carbon::parse($endDate)->format(\DateTime::RFC3339),
                'timeZone' => $gmeet->timezone,
            ),
        ));

        $event = $service->events->insert($calendarId, $event);
    
        $conference = new \Google_Service_Calendar_ConferenceData();
        $conferenceRequest = new \Google_Service_Calendar_CreateConferenceRequest();
        $conferenceRequest->setRequestId($meetingId);
        $conference->setCreateRequest($conferenceRequest);
        $event->setConferenceData($conference);
    
        $event = $service->events->patch($calendarId, $event->id, $event, ['conferenceDataVersion' => 1]);
    
        return $event->hangoutLink;
    }
}
