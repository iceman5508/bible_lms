<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Bundle;
use App\Models\BundleCourse;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Order;
use App\Models\Order_item;
use App\Models\Review;
use Illuminate\Http\Request;

class BundleCourseController extends Controller
{
    private $bundlePaginateValue = 9;

    public function allBundles()
    {
        $data['pageTitle'] = 'All Bundles';
        $data['bundles'] = Bundle::latest()->paginate($this->bundlePaginateValue);
        $data['highest_price'] = Bundle::max('price');

        return view('frontend.bundle.bundle-list')->with($data);
    }
    public function bundleDetails($slug)
    {
        $data['pageTitle'] = 'Bundle Details';
        $data['bundle'] = Bundle::where('slug', $slug)->firstOrFail();
        $user_id = $data['bundle']->user_id;

        //Start:: Instructor rating
        $instructorCourseIds = Course::where('user_id', $user_id)->pluck('id')->toArray();
        $data['instructor_average_rating'] = getUserAverageRating($user_id);
        //End:: Instructor rating

        $data['total_students'] = Enrollment::whereIn('course_id', $instructorCourseIds)->count();
        //End:: Instructor students

        return view('frontend.bundle.bundle-details')->with($data);
    }

    public function paginationFetchData(Request $request)
    {
        $data['bundles'] = $this->filterBundleCourseData($request);
        if($request->ajax()) {
            $data['bundles'] = $data['bundles']->paginate($this->bundlePaginateValue);
            return view('frontend.bundle.render-bundle-course-list')->with($data);
        }
    }

    public function getFilterBundleCourse(Request $request)
    {
        $data['bundles'] = $this->filterBundleCourseData($request);
        $data['bundles'] = $data['bundles']->paginate($this->bundlePaginateValue);
        return view('frontend.bundle.render-bundle-course-list')->with($data);
    }

    public function filterBundleCourseData($request)
    {
        $min_price = (int)$request->min_price;
        $max_price = (int)$request->max_price;
        $sortBy_id = $request->sortBy_id;
        $data['bundles'] = Bundle::query()->active();

        $data['bundles'] = $data['bundles']->where(function ($q) use ($min_price, $max_price) {
            if ($min_price && $max_price) {
                $q->whereBetween('price', [$min_price, $max_price]);
            } else if ($max_price) {
                $q->whereBetween('price', [0, $max_price]);
            }
        });

        if ($sortBy_id == 2 || $sortBy_id == 1){
            $data['bundles'] = $data['bundles']->orderBy('id', 'DESC');
        } elseif ($sortBy_id == 3){
            $data['bundles'] = $data['bundles']->orderBy('id', 'ASC');
        }

        return $data['bundles'] ;
    }
}
