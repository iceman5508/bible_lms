<?php

namespace App\Http\Middleware;

use App\Models\UserPackage;
use App\Traits\General;
use Closure;
use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Facades\Cookie;
use IvanoMatteo\LaravelDeviceTracking\Models\Device;

class DeviceControlMiddleware
{
    use General;
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {   
        $authUser = auth()->user();
        if(get_option('device_control') && $authUser->role == USER_ROLE_STUDENT){
            $device_count = $authUser->device->count();
            $userPackage = UserPackage::join('packages', 'packages.id', '=', 'user_packages.package_id')->where('package_type', PACKAGE_TYPE_SUBSCRIPTION)->where('user_packages.user_id', auth()->id())->where('user_packages.status', PACKAGE_STATUS_ACTIVE)->whereDate('enroll_date', '<=', now())->whereDate('expired_date', '>=', now())->with('enrollments')->select('user_packages.device')->first();
            if(!is_null($userPackage)){
                $limit =  $userPackage->device;
            }
            else{
                $limit =  get_option('device_limit');
            }

            $device_uuid = $request->cookie('_uuid_d');
            
            if(Device::join('device_user', 'devices.id', '=', 'device_user.device_id')->where('devices.device_uuid', $device_uuid)->first()){
                return $next($request);
            }
            elseif(Device::join('device_user', 'devices.id', '=', 'device_user.device_id')->where('devices.device_uuid', $device_uuid)->withTrashed()->first()){
                Auth::logout();
                Cookie::queue(Cookie::forget('_uuid_d'));
                return redirect(route('login'));
            }
            elseif($device_count < $limit){
                \DeviceTracker::detectFindAndUpdate();
            }
            else{
                $this->showToastrMessage('warning', 'Device Limit Reached. Please logout from the list');
                return redirect()->to('student/login-devices');
            }
        }

        return $next($request);
    }
}
