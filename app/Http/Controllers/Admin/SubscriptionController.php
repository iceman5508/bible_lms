<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Traits\General;
use App\Models\Package;
use App\Models\UserPackage;
use App\Traits\ImageSaveTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SubscriptionController extends Controller
{
    use General, ImageSaveTrait;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (!auth()->user()->can('manage_subscriptions')) {
                abort('403');
            } // end permission checking
            
            return $next($request);
        });

        $this->middleware('isDemo', ['only' => ['store', 'update', 'delete', 'changeStatus']]);
    }

    public function index()
    {
        $data['title'] = __('Manage Subscription Packages');
        $data['navSubscriptionParentActiveClass'] = 'mm-active';
        $data['navSubscriptionParentShowClass'] = 'mm-show';
        $data['subNavSubscriptionActiveClass'] = 'mm-active';
        $data['subscriptions'] = Package::where('package_type', PACKAGE_TYPE_SUBSCRIPTION)->orderBy('order', 'ASC')->paginate(10);
        return view('admin.subscriptions.index', $data);
    }
    
    public function purchaseList()
    {
        $data['title'] = __('Subscription Package Purchase List');
        $data['navSubscriptionParentActiveClass'] = 'mm-active';
        $data['navSubscriptionParentShowClass'] = 'mm-show';
        $data['subNavSubscriptionActiveClass'] = 'mm-active';
        $data['userSubscriptions'] = UserPackage::join('packages', 'packages.id', '=', 'user_packages.package_id')->where('user_packages.status', PACKAGE_STATUS_ACTIVE)->where('packages.package_type', PACKAGE_TYPE_SUBSCRIPTION)->select('user_packages.*', 'packages.icon', 'packages.title', 'packages.uuid as package_uuid')->paginate(10);
        return view('admin.subscriptions.purchase_list', $data);
    }
   
    public function pendingPurchaseList()
    {
        $data['title'] = __('Subscription Package Purchase Pending List');
        $data['navSubscriptionParentActiveClass'] = 'mm-active';
        $data['navSubscriptionParentShowClass'] = 'mm-show';
        $data['subNavSubscriptionActiveClass'] = 'mm-active';
        $data['userSubscriptions'] = UserPackage::join('packages', 'packages.id', '=', 'user_packages.package_id')->where('user_packages.status', PACKAGE_STATUS_PENDING)->where('packages.package_type', PACKAGE_TYPE_SUBSCRIPTION)->select('user_packages.*', 'packages.icon', 'packages.title', 'packages.uuid as package_uuid')->paginate(10);
        return view('admin.subscriptions.pending_purchase_list', $data);
    }

    public function create()
    {
        $data['title'] = __('Add Subscription Packages');
        $data['navSubscriptionParentActiveClass'] = 'mm-active';
        $data['navSubscriptionParentShowClass'] = 'mm-show';
        return view('admin.subscriptions.create', $data);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'bail|required|max:191|min:2',
            'monthly_price' => 'bail|required|required|min:0',
            'discounted_monthly_price' => 'bail|required|required|min:0',
            'yearly_price' => 'bail|required|required|min:0',
            'discounted_yearly_price' => 'bail|required|required|min:0',
            'course' => 'bail|required|min:0',
            'bundle_course' => 'bail|required|min:0',
            'consultancy' => 'bail|required|min:0',
            'device' => 'bail|required|min:1',
            'description' => 'bail|nullable',
            'recommended' => 'nullable',
            'in_home' => 'nullable',
            'order' => 'required|min:1',
            'icon' => 'bail|required|mimes:jpeg,jpg,png|max:300|dimensions:width=80,height=80'
        ]);

        $slug = Str::slug($request->title);
        
        if (Package::where('slug', $slug)->withTrashed()->count() > 0)
        {
            $slug = Str::slug($request->title) . '-'. rand(100000, 999999);
        }
        
        $data['icon'] = $request->icon ? $this->saveImage('packages', $request->icon, null, null) :   null;
        $data['package_type'] = PACKAGE_TYPE_SUBSCRIPTION;
        $data['slug'] = $slug;

        Package::create($data);

        $this->showToastrMessage('success', __('Subscription package has been added'));
        return redirect()->route('admin.subscriptions.index');
    }

    public function edit(Package $subscription)
    {
        $data['title'] = __('Edit Subscription Packages');
        $data['subscription'] = $subscription;
        $data['navSubscriptionParentActiveClass'] = 'mm-active';
        $data['navSubscriptionParentShowClass'] = 'mm-show';
        $data['subNavSubscriptionActiveClass'] = 'mm-active';
        return view('admin.subscriptions.edit', $data);

    }

    public function update(Request $request, Package $subscription)
    {
        $data = $request->validate([
            'title' => 'bail|required|max:191|min:2',
            'monthly_price' => 'bail|required|required|min:0',
            'discounted_monthly_price' => 'bail|required|required|min:0',
            'yearly_price' => 'bail|required|required|min:0',
            'discounted_yearly_price' => 'bail|required|required|min:0',
            'course' => 'bail|required|min:0',
            'bundle_course' => 'bail|required|min:0',
            'consultancy' => 'bail|required|min:0',
            'device' => 'bail|required|min:1',
            'description' => 'bail|nullable',
            'recommended' => 'nullable',
            'in_home' => 'nullable',
            'order' => 'required|min:1',
            'icon' => 'bail|nullable|mimes:jpeg,jpg,png|max:300|dimensions:width=80,height=80'
        ]);

        $slug = Str::slug($request->title);
        
        if (Package::where('slug', $slug)->withTrashed()->count() > 0)
        {
            $slug = Str::slug($request->title) . '-'. rand(100000, 999999);
        }
        
        $data['icon'] = $request->icon ? $this->saveImage('packages', $request->icon, null, null) :   $subscription->icon;
        $data['slug'] = $slug;

        if($subscription->is_default == 1){
            unset($data['monthly_price']);
            unset($data['discounted_monthly_price']);
            unset($data['yearly_price']);
            unset($data['discounted_yearly_price']);
        }

        $subscription->update($data);

        $this->showToastrMessage('success', __('Subscription package has been updated'));
        return redirect()->route('admin.subscriptions.index');
    }

    public function show(Package $subscription)
    {
        $data['title'] = __('View Subscription Packages');
        $data['subscription'] = $subscription;
        $data['totalSubscription'] = UserPackage::where('package_id', $data['subscription']->id)->count();
        $data['userSubscriptions'] = UserPackage::where('package_id', $data['subscription']->id)->paginate(30);
        $data['navSubscriptionParentActiveClass'] = 'mm-active';
        $data['navSubscriptionParentShowClass'] = 'mm-show';
        $data['subNavSubscriptionActiveClass'] = 'mm-active';
        return view('admin.subscriptions.view', $data);

    }

    public function destroy(Package $subscription)
    {
        if(!$subscription->is_default){
            if($subscription->user_package){
                $this->showToastrMessage('error', __('Subscription package has already used. Please deactivate it instead.'));
            }
            else{
                $subscription->delete();
                $this->showToastrMessage('error', __('Subscription package has been deleted'));
            }
        }

        return redirect()->back();
    }

    public function changeStatus(Request $request)
    {
        $subscription = Package::whereId($request->id)->first();
        if(!$subscription->is_default){
            $subscription->update(['status' => $request->status]);

            return response()->json([
                'data' => 'success',
            ]);
        }
    }

    public function changePurchaseStatus(Request $request)
    {
        $subscription = UserPackage::whereId($request->id)->first();
        if($request->status == PACKAGE_STATUS_ACTIVE){
            UserPackage::join('packages', 'packages.id', '=', 'user_packages.package_id')->where('package_type', $subscription->package->package_type)->where('user_packages.user_id', auth()->id())->where('user_packages.status', PACKAGE_STATUS_ACTIVE)->whereDate('enroll_date', '<=', now())->whereDate('expired_date', '>=', now())->update(['user_packages.status' => PACKAGE_STATUS_CANCELED]);
            $subscription->payment->update(['payment_status' => 'paid']);
        }

        $subscription->update(['status' => $request->status]);

        return response()->json([
            'data' => 'success',
        ]);
    }

}
