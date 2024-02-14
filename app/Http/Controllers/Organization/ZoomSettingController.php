<?php

namespace App\Http\Controllers\Organization;

use App\Http\Controllers\Controller;
use App\Models\ZoomSetting;
use App\Traits\General;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ZoomSettingController extends Controller
{
    use General;
    public function zoomSetting()
    {
        $data['title'] = __('Zoom Setting');
        $data['navZoomSettingActiveClass'] = 'active';
        $data['zoom'] = ZoomSetting::whereUserId(Auth::id())->first();
        return view('organization.settings.zoom', $data);
    }

    public function zoomSettingUpdate(Request $request)
    {
        if ($request->status == 1){
            $request->validate([
               'account_id' => 'required',
               'api_key' => 'required',
               'api_secret' => 'required',
            ]);
        }

        $zoom = ZoomSetting::whereUserId(Auth::id())->first();
        if (!$zoom) {
            $zoom = new ZoomSetting();
        }

        $zoom->user_id = Auth::id();
        $zoom->account_id = $request->account_id;
        $zoom->api_key = $request->api_key;
        $zoom->api_secret = $request->api_secret;
        $zoom->timezone = $request->timezone;
        $zoom->host_video = $request->host_video ?? 0;
        $zoom->participant_video = $request->participant_video ?? 0;
        $zoom->waiting_room = $request->waiting_room ?? 0;
        $zoom->status = $request->status;
        $zoom->save();

        $this->showToastrMessage('success', __('Updated Successfully'));
        return redirect()->back();
    }
}
