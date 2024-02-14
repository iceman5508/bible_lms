<?php

namespace App\Http\Controllers;

use App\Models\UserPackage;
use App\Traits\General;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use IvanoMatteo\LaravelDeviceTracking\Models\Device;

class HomeController extends Controller
{
    use General;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        if (Auth::user()->is_admin())
        {
            return redirect(route('admin.dashboard'));

        } else {
            return redirect(route('main.index'));
        }
    }
    
    /**
     * Show all the login device of current user.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function allLoginDevice()
    {
        $data['devices'] = auth()->user()->device;
        $userPackage = UserPackage::join('packages', 'packages.id', '=', 'user_packages.package_id')->where('package_type', PACKAGE_TYPE_SUBSCRIPTION)->where('user_packages.user_id', auth()->id())->where('user_packages.status', PACKAGE_STATUS_ACTIVE)->whereDate('enroll_date', '<=', now())->whereDate('expired_date', '>=', now())->with('enrollments')->select('user_packages.device')->first();
        if(!is_null($userPackage)){
            $limit =  $userPackage->device;
        }
        else{
            $limit =  get_option('device_limit');
        }
        
        $data['limit'] = $limit;
        return view('frontend.logout_devices', $data);
    }
   
    /**
     * Show all the login device of current user.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function logoutDevice($device_id = NULL)
    {
        $userPackage = UserPackage::join('packages', 'packages.id', '=', 'user_packages.package_id')->where('package_type', PACKAGE_TYPE_SUBSCRIPTION)->where('user_packages.user_id', auth()->id())->where('user_packages.status', PACKAGE_STATUS_ACTIVE)->whereDate('enroll_date', '<=', now())->whereDate('expired_date', '>=', now())->with('enrollments')->select('user_packages.device')->first();
        if(!is_null($userPackage)){
            $limit =  $userPackage->device;
        }
        else{
            $limit =  get_option('device_limit');
        }

        if($device_id){
            Device::join('device_user', 'devices.id', '=', 'device_user.device_id')->where('devices.id', $device_id)->update(['deleted_at' => now()]);
            Cookie::queue(Cookie::forget('_uuid_d'));
            $this->showToastrMessage('success', 'Logout device successfully.');
            $device_count = auth()->user()->device->count();
            if($device_count < $limit){
                \DeviceTracker::detectFindAndUpdate();
            }
        }
        else{
            Device::join('device_user', 'devices.id', '=', 'device_user.device_id')->where('user_id', auth()->id())->update(['deleted_at' => now()]);            
            $this->showToastrMessage('success', 'Logout from all device successfully. Please login to continue.');
            Cookie::queue(Cookie::forget('_uuid_d'));
            Auth::logout();
        }

        return redirect('/');
    }


}
