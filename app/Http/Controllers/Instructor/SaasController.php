<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Models\Bundle;
use App\Models\ConsultationSlot;
use App\Models\Course;
use App\Models\CourseInstructor;
use App\Models\Instructor;
use App\Models\Package;
use App\Models\Student;
use App\Models\UserPackage;

class SaasController extends Controller
{

    public function saasList()
    {
        $data['pageTitle'] = __('SaaS panel');
        $data['title'] = __('SaaS panel');
        $data['mySaasPackage'] = UserPackage::where('user_packages.user_id', auth()->id())->whereIn('package_type', [PACKAGE_TYPE_SAAS_INSTRUCTOR, PACKAGE_TYPE_SAAS_ORGANIZATION])->where('user_packages.status', PACKAGE_STATUS_ACTIVE)->whereDate('enroll_date', '<=', now())->whereDate('expired_date', '>=', now())->join('packages', 'packages.id', '=', 'user_packages.package_id')->select('package_id', 'package_type', 'subscription_type')->first();
        $sasses = Package::whereIn('package_type', [PACKAGE_TYPE_SAAS_INSTRUCTOR, PACKAGE_TYPE_SAAS_ORGANIZATION])->where('status', PACKAGE_STATUS_ACTIVE)->orderBy('order', 'ASC')->get();
        $data['instructorSaas'] = $sasses->where('package_type', PACKAGE_TYPE_SAAS_INSTRUCTOR);
        $data['organizationSaas'] = $sasses->where('package_type', PACKAGE_TYPE_SAAS_ORGANIZATION);
        return view('frontend.saas.list', $data);
    }

    public function saasPlan()
    {
        $data['pageTitle'] = __('SaaS Plan');
        $data['title'] = __('SaaS Plan');
        $data['userPackages'] = UserPackage::query()
            ->where('user_packages.user_id', auth()->id())
            ->whereIn('package_type', [PACKAGE_TYPE_SAAS_INSTRUCTOR, PACKAGE_TYPE_SAAS_ORGANIZATION])
            ->join('packages', 'packages.id', '=', 'user_packages.package_id')->select('user_packages.*')
            ->orderBy('user_packages.id', 'desc')
            ->get();
        return view('frontend.saas.plan', $data);
    }

    public function saasPlanDetails($id)
    {
        $data['pageTitle'] = __('SaaS Plan Details');
        $data['title'] = __('SaaS Plan Details');
        $data['userPackage'] = UserPackage::query()
            ->where('user_packages.user_id', auth()->id())
            ->whereIn('package_type', [PACKAGE_TYPE_SAAS_INSTRUCTOR, PACKAGE_TYPE_SAAS_ORGANIZATION])
            ->join('packages', 'packages.id', '=', 'user_packages.package_id')
            ->select('user_packages.*', 'packages.package_type')
            ->findOrFail($id);
        if ($data['userPackage']->package_type == PACKAGE_TYPE_SAAS_ORGANIZATION) {
            $data['studentCount'] = Student::where('organization_id', auth()->user()->organization->id)->count();
            $data['instructorCount'] = Instructor::where('organization_id', auth()->user()->organization->id)->count();
        }

        $data['courseCount'] = CourseInstructor::join('courses', 'courses.id', '=', 'course_instructor.course_id')->where('course_instructor.instructor_id', auth()->id())->groupBy('course_id')->count();
        $data['consultancyCount'] = ConsultationSlot::where('user_id', auth()->id())->count();
        $data['subscriptionCourseCount'] = Course::where('user_id', auth()->id())->where('is_subscription_enable', 1)->count();
        $data['bundleCourseCount'] = Bundle::where('user_id', auth()->id())->count();
        return view('frontend.saas.plan_details', $data);
    }
}
