<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Models\Bundle;
use App\Models\BundleCourse;
use App\Models\CartManagement;
use App\Models\Course;
use App\Models\Wishlist;
use App\Traits\General;
use App\Traits\ImageSaveTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class BundleCourseController extends Controller
{
    use General, ImageSaveTrait;
    public function index()
    {
        $data['title'] = 'Bundle Courses';
        $data['navBundleCourseActiveClass'] = 'active';
        $data['bundles'] = Bundle::whereUserId(Auth::user()->id)->paginate();

        return view('instructor.bundle-course.index')->with($data);
    }

    public function createStepOne()
    {
        $count = Bundle::where('user_id', auth()->id())->count();
        if(hasLimitSaaS(PACKAGE_RULE_BUNDLE_COURSE, PACKAGE_TYPE_SAAS_INSTRUCTOR, $count)){
            $data['title'] = 'Create Bundle Courses';
            $data['navBundleCourseActiveClass'] = 'active';

            return view('instructor.bundle-course.create-step-1')->with($data);
        }
        else{
            $this->showToastrMessage('error', __('Your Bundle Create limit has been finish.'));
            return redirect()->back();
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'overview' => 'required',
            'price' => 'required',
            'access_period' => 'nullable|min:1',
            'image' => 'required|mimes:jpg,png,jpeg,gif,svg',
        ]);

        $bundle = new Bundle();
        $bundle->name = $request->name;
        $bundle->slug = getSlug($request->name);
        $bundle->overview = $request->overview;
        $bundle->price = $request->price;
        $bundle->access_period = (is_null($request->access_period) || $request->access_period < 1) ? 0 : $request->access_period;
        $bundle->meta_title = $request->meta_title;
        $bundle->meta_description = $request->meta_description;
        $bundle->meta_keywords = $request->meta_keywords;
        if($request->hasFile('og_image')){
            $bundle->og_image = $this->saveImage('meta', $request->og_image, null, null);
        }
        $bundle->status = $request->status;
        
        if(get_option('subscription_mode')){
            $bundle->is_subscription_enable = $request->is_subscription_enable;
        }

        $bundle->image = $request->image ? $this->saveImage('bundle', $request->image, null, null) :   null;
        $bundle->save();

        $this->showToastrMessage('success', __('Bundle created successfully'));
        return redirect()->route('instructor.bundle-course.createStepTwo', $bundle->uuid);
    }

    public function createEditStepTwo($uuid)
    {
        $data['title'] = 'Add/Remove Bundle Course';
        $data['navBundleCourseActiveClass'] = 'active';
        $data['bundle'] = Bundle::where('uuid', $uuid)->firstOrFail();
        $data['courses'] = Course::whereUserId(Auth::user()->id)->active()->paginate();
        $data['bundleCourses'] = BundleCourse::where('bundle_id', $data['bundle']->id)->get();
        $data['alreadyAddedBundleCourseIds'] =  BundleCourse::where('bundle_id', $data['bundle']->id)->pluck('course_id')->toArray();
        $data['totalCourses'] = BundleCourse::where('bundle_id', $data['bundle']->id)->count();

        return view('instructor.bundle-course.create-edit-step-2')->with($data);
    }

    public function editStepOne($uuid)
    {
        $data['title'] = 'Update Bundle Courses';
        $data['navBundleCourseActiveClass'] = 'active';
        $data['bundle'] = Bundle::where('uuid', $uuid)->firstOrFail();

        return view('instructor.bundle-course.edit-step-1')->with($data);
    }

    public function update(Request $request, $uuid)
    {
        $request->validate([
            'name' => 'required|max:255',
            'overview' => 'required',
            'price' => 'required',
            'access_period' => 'nullable|min:1',
            'image' => 'mimes:jpg,png,jpeg,gif,svg'
        ]);

        $bundle = Bundle::where('uuid', $uuid)->firstOrfail();
        $bundle->name = $request->name;
        $bundle->slug = getSlug($request->name);
        $bundle->overview = $request->overview;
        $bundle->price = $request->price;
        $bundle->status = $request->status;
        if(get_option('subscription_mode')){
            $bundle->is_subscription_enable = $request->is_subscription_enable;
        }
        $bundle->access_period = (is_null($request->access_period) || $request->access_period < 0) ? 0 : $request->access_period;
        $bundle->image = $request->image ? $this->updateImage('bundle', $request->image, $bundle->image, null, null) :   $bundle->image;
        $bundle->meta_title = $request->meta_title;
        $bundle->meta_description = $request->meta_description;
        $bundle->meta_keywords = $request->meta_keywords;
        if($request->hasFile('og_image')){
            $bundle->og_image = $this->saveImage('meta', $request->og_image, null, null);
        }
        $bundle->save();

        $this->showToastrMessage('success', __('Bundle updated successfully'));
        return redirect()->route('instructor.bundle-course.createStepTwo', $uuid);
    }


    public function addBundleCourse(Request $request)
    {
        $currentBundleCourse = BundleCourse::where('bundle_id', $request->bundle_id)->where('course_id', $request->course_id)->first();
        if ($currentBundleCourse){
            return response()->json([
                'status' => '409',
                'msg' => __('Already added in bundle list!'),
            ]);
        }

        $bundleCourse = new BundleCourse();
        $bundleCourse->course_id = $request->course_id;
        $bundleCourse->bundle_id = $request->bundle_id;
        $bundleCourse->save();

        $totalCourses = BundleCourse::where('bundle_id', $request->bundle_id)->count();

        return response()->json([
            'status' => '200',
            'msg' => __('Course Added in bundle list.'),
            'totalCourses' => $totalCourses,
            'course_id' => $request->course_id
        ]);
    }

    public function removeBundleCourse(Request $request)
    {
        $bundleCourse = BundleCourse::where('bundle_id', $request->bundle_id)->where('course_id', $request->course_id)->first();
        if ($bundleCourse){
            $bundleCourse->delete();

            $totalCourses = BundleCourse::where('bundle_id', $request->bundle_id)->count();

            return response()->json([
                'status' => '200',
                'msg' => __('Course remove from bundle list.'),
                'totalCourses' => $totalCourses,
                'course_id' => $request->course_id
            ]);
        }

        return response()->json([
            'status' => '404',
            'msg' => __('Course not found in bundle list!'),
        ]);
    }

    public function delete($uuid)
    {
        $bundle = Bundle::where('uuid', $uuid)->firstOrfail();
        $this->deleteFile($bundle->image);
        //Start:: Delete this bundle Wishlist, CartManagement & BundleCourse
        Wishlist::where('bundle_id', $bundle->id)->delete();
        CartManagement::where('bundle_id', $bundle->id)->delete();
        BundleCourse::where('bundle_id', $bundle->id)->delete();
        //End:: Delete this bundle wishList and addToCart
        $bundle->delete();

        $this->showToastrMessage('success', __('Bundle deleted successfully'));
        return redirect()->back();
    }

}
