<?php

namespace App\Http\Controllers\Api\Student;

use App\Http\Controllers\Controller;
use App\Models\Bundle;
use App\Models\Course;
use App\Models\CourseInstructor;
use App\Models\Enrollment;
use App\Models\Order;
use App\Models\Order_item;
use App\Models\Wishlist;
use App\Traits\ApiStatusTrait;
use App\Traits\General;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    use General, ApiStatusTrait;

    public function wishlist()
    {
        $wishlists = Wishlist::whereUserId(Auth::user()->id)->get();
        foreach ($wishlists as $wishlist) {
            if ($wishlist->course_id) {
                $course = Course::find($wishlist->course_id);
                if (!$course) {
                    Wishlist::where('course_id', $wishlist->course_id)->delete();
                }
            } elseif ($wishlist->bundle_id) {
                $bundle = Bundle::find($wishlist->bundle_id);
                if (!$bundle) {
                    Wishlist::where('bundle_id', $wishlist->bundle_id)->delete();
                }
            }
        }
        // End:: Check course, bundle exists or not. If not exists, Delete it.
        $data['wishlists'] = Wishlist::whereUserId(Auth::user()->id)->with(['course', 'bundle'])->paginate();

        return $this->success($data);
    }


    public function addToWishlist(Request $request)
    {
        if ($request->course_id) {
            $courseOrderExits = Enrollment::where(['course_id' => $request->course_id, 'user_id' => Auth::user()->id, 'status' => ACCESS_PERIOD_ACTIVE])->whereDate('end_date', '>=', now())->first();

            if ($courseOrderExits) {
                $order = Order::find($courseOrderExits->order_id);
                if ($order) {
                    if ($order->payment_status == 'due') {
                        Order_item::whereOrderId($courseOrderExits->order_id)->get()->map(function ($q) {
                            $q->delete();
                        });
                        $order->delete();
                    } else {
                        $message = __("You've already purchased the course!");
                        return $this->success([], $message);
                    }
                }
            }

            $ownCourseCheck = CourseInstructor::where('course_id', $request->course_id)->where('instructor_id', $request->user_id)->delete();

            if ($ownCourseCheck) {
                $message = __("This is your course. No need to add to wishlist.");
                return $this->success([], $message);
            }

            $courseExits = Course::find($request->course_id);
            if (!$courseExits) {
                $message = __("Course not found!");
                return $this->error([], $message, 404);
            }
            $wishListExists = WishList::whereUserId(Auth::user()->id)->where('course_id', $request->course_id)->first();
        } elseif ($request->bundle_id) {
            $bundleExits = Bundle::find($request->bundle_id);
            if (!$bundleExits) {
                $message = __("Bundle not found!");
                return $this->error([], $message, 404);
            }
            $wishListExists = WishList::whereUserId(Auth::user()->id)->where('bundle_id', $request->bundle_id)->first();
        }
        else{
            $message = __("Please select the item to add in wishlist");
            return $this->error([], $message, 404);
        }

        if ($wishListExists) {
            $message = __("Already added to wishlist!");
            return $this->error([], $message, 404);
        }

        $wishlist = new Wishlist();
        $wishlist->user_id = Auth::user()->id;
        $wishlist->course_id = $request->course_id;
        $wishlist->product_id = $request->product_id;
        $wishlist->bundle_id = $request->bundle_id;
        $wishlist->save();

        $response['quantity'] = Wishlist::whereUserId(Auth::user()->id)->count();
        $message = __("Added to wishlist");
        return $this->success([], $message);
    }

    public function wishlistDelete($id)
    {
        $wishlist = Wishlist::findOrFail($id);
        $wishlist->delete();
        $message = __('Removed from wishlist!');
        return $this->success([], $message);
    }
}
