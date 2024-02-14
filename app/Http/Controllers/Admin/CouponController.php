<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CouponRequest;
use App\Models\Coupon;
use App\Models\CouponCourse;
use App\Models\CouponInstructor;
use App\Models\Course;
use App\Models\User;
use App\Tools\Repositories\Crud;
use App\Traits\General;
use Illuminate\Http\Request;
use Auth;

class CouponController extends Controller
{
    use General;

    protected $model;

    public function __construct(Coupon $coupon)
    {
        $this->couponModel = new Crud($coupon);
    }

    public function index()
    {
        if (!Auth::user()->can('manage_coupon')) {
            abort('403');
        } // end permission checking

        $data['title'] = 'Coupon';
        $data['navCouponActiveClass'] = 'mm-active';
        $data['subNavCouponIndexActiveClass'] = 'mm-active';

        $data['coupons'] = $this->couponModel->getOrderById('DESC', 25);
        return view('admin.coupon.index', $data);

    }

    public function create()
    {
        if (!Auth::user()->can('manage_coupon')) {
            abort('403');
        } // end permission checking

        $data['title'] = 'Create Coupon';
        $data['navCouponActiveClass'] = 'mm-active';
        $data['navCouponAddActiveClass'] = 'mm-active';

        $data['courses'] = Course::all();
        $data['instructors'] = User::whereRole(2)->get();

        return view('admin.coupon.create', $data);
    }

    public function store(CouponRequest $request)
    {
        if (!Auth::user()->can('manage_coupon')) {
            abort('403');
        } // end permission checking

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


        $this->showToastrMessage('success', __('Coupon Created Successfully'));
        return redirect()->route('coupon.index');

    }

    public function edit($uuid)
    {
        if (!Auth::user()->can('manage_coupon')) {
            abort('403');
        } // end permission checking

        $data['title'] = 'Create Coupon';
        $data['navCouponActiveClass'] = 'mm-active';
        $data['navCouponAddActiveClass'] = 'mm-active';

        $data['courses'] = Course::all();
        $data['instructors'] = User::whereRole(2)->get();
        $data['coupon'] = $this->couponModel->getRecordByUuid($uuid);
        $data['selectedCourseIDs'] = $data['coupon']->couponCourses->pluck('course_id')->toArray();
        $data['selectedInstructorIDs'] = $data['coupon']->couponInstructors->pluck('user_id')->toArray();

        return view('admin.coupon.edit', $data);
    }

    public function update(Request $request, $uuid)
    {
        if (!Auth::user()->can('manage_coupon')) {
            abort('403');
        } // end permission checking

        $coupon = $this->couponModel->getRecordByUuid($uuid);

        $request->validate([
            'coupon_code_name' => 'required|min:2|max:255|unique:coupons,coupon_code_name,'.$coupon->id,
            'coupon_type' => ['required'],
            'percentage' => ['required'],
            'minimum_amount' => ['required'],
            'start_date' => ['required'],
            'end_date' => ['required'],
        ]);

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
                CouponInstructor::whereCouponId($coupon->id)->delete();
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
                CouponCourse::whereCouponId($coupon->id)->delete();
                foreach ($request->course_ids as $course_id) {
                    $coupon_course = new CouponCourse();
                    $coupon_course->coupon_id = $coupon->id;
                    $coupon_course->course_id = $course_id;
                    $coupon_course->save();
                }
            }

        }

        $this->showToastrMessage('success', __('Coupon Updated Successfully'));
        return redirect()->route('coupon.index');

    }


    public function delete($uuid)
    {
        if (!Auth::user()->can('manage_coupon')) {
            abort('403');
        } // end permission checking


        $coupon = $this->couponModel->getRecordByUuid($uuid);
        CouponCourse::whereCouponId($coupon->id)->delete();
        CouponInstructor::whereCouponId($coupon->id)->delete();
        $this->couponModel->deleteByUuid($uuid);

        $this->showToastrMessage('success', __('Coupon Delete Successfully'));
        return redirect()->back();
    }

}
