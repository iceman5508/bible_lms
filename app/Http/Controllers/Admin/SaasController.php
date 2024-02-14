<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Traits\General;
use App\Models\Package;
use App\Models\UserPackage;
use App\Traits\ImageSaveTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SaasController extends Controller
{
    use General, ImageSaveTrait;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (!auth()->user()->can('manage_saas')) {
                abort('403');
            } // end permission checking
            
            return $next($request);
        });

        $this->middleware('isDemo', ['only' => ['store', 'update', 'delete', 'changeStatus']]);
    }

    public function index()
    {
        $data['title'] = __('Manage SaaS Packages');
        $data['navSaasParentActiveClass'] = 'mm-active';
        $data['navSaasParentShowClass'] = 'mm-show';
        $data['subNavSaasActiveClass'] = 'mm-active';
        $data['saases'] = Package::whereIn('package_type', [PACKAGE_TYPE_SAAS_INSTRUCTOR, PACKAGE_TYPE_SAAS_ORGANIZATION])->orderBy('order', 'ASC')->paginate(10);
        return view('admin.saas.index', $data);
    }
    
    public function purchaseList()
    {
        $data['title'] = __('Manage SaaS Packages');
        $data['navSaasParentActiveClass'] = 'mm-active';
        $data['navSaasParentShowClass'] = 'mm-show';
        $data['subNavSaasActiveClass'] = 'mm-active';
        $data['userSaases'] = UserPackage::join('packages', 'packages.id', '=', 'user_packages.package_id')->where('user_packages.status', PACKAGE_STATUS_ACTIVE)->whereIn('packages.package_type', [PACKAGE_TYPE_SAAS_INSTRUCTOR, PACKAGE_TYPE_SAAS_ORGANIZATION])->select('user_packages.*', 'packages.icon',  'packages.title', 'packages.uuid as package_uuid')->paginate(10);
        return view('admin.saas.purchase_list', $data);
    }
  
    public function pendingPurchaseList()
    {
        $data['title'] = __('Manage SaaS Packages');
        $data['navSaasParentActiveClass'] = 'mm-active';
        $data['navSaasParentShowClass'] = 'mm-show';
        $data['subNavSaasActiveClass'] = 'mm-active';
        $data['userSaases'] = UserPackage::join('packages', 'packages.id', '=', 'user_packages.package_id')->where('user_packages.status', PACKAGE_STATUS_PENDING)->whereIn('packages.package_type', [PACKAGE_TYPE_SAAS_INSTRUCTOR, PACKAGE_TYPE_SAAS_ORGANIZATION])->select('user_packages.*', 'packages.icon',  'packages.title', 'packages.uuid as package_uuid')->paginate(10);
        return view('admin.saas.pending_purchase_list', $data);
    }

    public function create()
    {
        $data['title'] = __('Add SaaS Packages');
        $data['navSaasParentActiveClass'] = 'mm-active';
        $data['navSaasParentShowClass'] = 'mm-show';
        return view('admin.saas.create', $data);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'package_type' => 'bail|required',
            'title' => 'bail|required|max:191|min:2',
            'monthly_price' => 'bail|required|required|min:0',
            'discounted_monthly_price' => 'bail|required|required|min:0',
            'yearly_price' => 'bail|required|required|min:0',
            'discounted_yearly_price' => 'bail|required|required|min:0',
            'student' => 'bail|required|min:0',
            'instructor' => 'bail|required|min:0',
            'course' => 'bail|required|min:0',
            'bundle_course' => 'bail|required|min:0',
            'subscription_course' => 'bail|required|min:0',
            'consultancy' => 'bail|required|min:0',
            'admin_commission' => 'bail|required|integer|min:0|max:100',
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
        $data['slug'] = $slug;

        Package::create($data);

        $this->showToastrMessage('success', __('SaaS package has been added'));
        return redirect()->route('admin.saas.index');
    }

    public function edit(Package $saa)
    {
        $data['title'] = __('Edit SaaS Packages');
        $data['saas'] = $saa;
        $data['navSaasParentActiveClass'] = 'mm-active';
        $data['navSaasParentShowClass'] = 'mm-show';
        $data['subNavSaasActiveClass'] = 'mm-active';
        return view('admin.saas.edit', $data);

    }

    public function update(Request $request, Package $saa)
    {
        $data = $request->validate([
            'package_type' => 'bail|required',
            'title' => 'bail|required|max:191|min:2',
            'monthly_price' => 'bail|required|required|min:0',
            'discounted_monthly_price' => 'bail|required|required|min:0',
            'yearly_price' => 'bail|required|required|min:0',
            'discounted_yearly_price' => 'bail|required|required|min:0',
            'student' => 'bail|required|min:0',
            'instructor' => 'bail|required|min:0',
            'course' => 'bail|required|min:0',
            'bundle_course' => 'bail|required|min:0',
            'subscription_course' => 'bail|required|min:0',
            'consultancy' => 'bail|required|min:0',
            'admin_commission' => 'bail|required|integer|min:0|max:100',
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
        
        $data['icon'] = $request->icon ? $this->saveImage('packages', $request->icon, null, null) :   $saa->icon;
        $data['slug'] = $slug;

        if($saa->is_default == 1){
            unset($data['monthly_price']);
            unset($data['discounted_monthly_price']);
            unset($data['yearly_price']);
            unset($data['discounted_yearly_price']);
        }
        $saa->update($data);

        $this->showToastrMessage('success', __('SaaS package has been updated'));
        return redirect()->route('admin.saas.index');
    }

    public function show(Package $saa)
    {
        $data['title'] = __('View SaaS Packages');
        $data['saas'] = $saa;
        $data['totalSaases'] = UserPackage::where('package_id', $data['saas']->id)->count();
        $data['userSaases'] = UserPackage::where('package_id', $data['saas']->id)->paginate(30);
        $data['navSaasParentActiveClass'] = 'mm-active';
        $data['navSaasParentShowClass'] = 'mm-show';
        $data['subNavSaasActiveClass'] = 'mm-active';
        return view('admin.saas.view', $data);

    }

    public function destroy(Package $saa)
    {
        if(!$saa->is_default){
            if($saa->user_package){
                $this->showToastrMessage('error', __('SaaS package has already used. Please deactivate it instead.'));
            }
            else{
                $saa->delete();
                $this->showToastrMessage('error', __('SaaS package has been deleted'));
            }
        }

        return redirect()->back();
    }

    public function changeStatus(Request $request)
    {
        $saa = Package::whereId($request->id)->first();
        if(!$saa->is_default){
            $saa->update(['status' => $request->status]);

            return response()->json([
                'data' => 'success',
            ]);
        }
    }
    
    public function changePurchaseStatus(Request $request)
    {
        $saa = UserPackage::whereId($request->id)->firstOrFail();
        if($request->status == PACKAGE_STATUS_ACTIVE){
            UserPackage::join('packages', 'packages.id', '=', 'user_packages.package_id')->where('package_type', $saa->package->package_type)->where('user_packages.user_id', $saa->user_id)->where('user_packages.status', PACKAGE_STATUS_ACTIVE)->whereDate('enroll_date', '<=', now())->whereDate('expired_date', '>=', now())->update(['user_packages.status' => PACKAGE_STATUS_CANCELED]);
            $saa->payment->update(['payment_status' => 'paid']);
        }

        $saa->update(['status' => $request->status]);

        return response()->json([
            'data' => 'success',
        ]);
    }

}
