<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CouponRequest;
use App\Models\Coupon;
use App\Models\CouponCourse;
use App\Models\CouponInstructor;
use App\Tools\Repositories\Crud;
use App\Traits\ApiStatusTrait;
use App\Traits\General;
use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Facades\DB;

class CouponController extends Controller
{
    use General, ApiStatusTrait;

    protected $couponModel;

    public function __construct(Coupon $coupon)
    {
        $this->couponModel = new Crud($coupon);
    }

    public function index()
    {
        if (!Auth::user()->hasPermissionTo('manage_coupon', 'web')) {
            return $this->error([], 'Unauthorize access', 403);
        } // end permission checking

        $data['coupons'] = Coupon::with('creator')->orderBy('id', 'DESC')->paginate(25);
        return $this->success($data);
    }

    public function store(CouponRequest $request)
    {
        if (!Auth::user()->hasPermissionTo('manage_coupon', 'web')) {
            return $this->error([], 'Unauthorize access', 403);
        } // end permission checking

        try{
            DB::beginTransaction();
            $coupon = new Coupon();
            $coupon->coupon_code_name = $request->coupon_code_name;
            $coupon->coupon_type = $request->coupon_type;
            $coupon->status = $request->status ?? 1;
            $coupon->percentage = $request->percentage;
            $coupon->minimum_amount = $request->minimum_amount;
            $coupon->maximum_use_limit = $request->maximum_use_limit;
            $coupon->start_date = $request->start_date;
            $coupon->end_date = $request->end_date;
            $coupon->save();
    
            if ($request->coupon_type == 2) {
                if (count($request->instructor_ids) > 0) {
                    foreach ($request->instructor_ids as $instructor_id) {
                        $coupon_instructor = new CouponInstructor();
                        $coupon_instructor->coupon_id = $coupon->id;
                        $coupon_instructor->user_id = $instructor_id;
                        $coupon_instructor->save();
                    }
                }
    
            }
    
            if ($request->coupon_type == 3) {
                if (count($request->course_ids) > 0) {
                    foreach ($request->course_ids as $course_id) {
                        $coupon_course = new CouponCourse();
                        $coupon_course->coupon_id = $coupon->id;
                        $coupon_course->course_id = $course_id;
                        $coupon_course->save();
                    }
                }
    
            }

            DB::commit();
            return $this->success([], __("Coupon Add Successfully"));
        }catch(\Exception $e){
            DB::rollback();
            return $this->failed([], $e->getMessage());
        }

    }

    public function update(Request $request, $uuid)
    {
        if (!Auth::user()->hasPermissionTo('manage_coupon', 'web')) {
            return $this->error([], 'Unauthorize access', 403);
        } // end permission checking

        try{
            $coupon = $this->couponModel->getRecordByUuid($uuid);

            $request->validate([
                'coupon_code_name' => 'required|min:2|max:255|unique:coupons,coupon_code_name,'.$coupon->id,
                'percentage' => ['required'],
                'minimum_amount' => ['required'],
                'start_date' => ['required'],
                'end_date' => ['required'],
            ]);

            $coupon->coupon_code_name = $request->coupon_code_name;
            $coupon->status = $request->status ?? 1;
            $coupon->percentage = $request->percentage;
            $coupon->minimum_amount = $request->minimum_amount;
            $coupon->maximum_use_limit = $request->maximum_use_limit;
            $coupon->start_date = $request->start_date;
            $coupon->end_date = $request->end_date;
            $coupon->save();

            DB::commit();
            return $this->success([], __("Coupon Updated Successfully"));
        }catch(\Exception $e){
            DB::rollback();
            return $this->failed([], $e->getMessage());
        }
    }
}
