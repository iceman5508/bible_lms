<?php
namespace App\Http\Controllers\Api\Student;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Logger;
use App\Http\Services\Payment\BasePaymentService;
use App\Models\AffiliateHistory;
use App\Models\AffiliateRequest;
use App\Models\Bank;
use App\Models\BookingHistory;
use App\Models\Bundle;
use App\Models\BundleCourse;
use App\Models\CartManagement;
use App\Models\City;
use App\Models\ConsultationSlot;
use App\Models\Country;
use App\Models\Coupon;
use App\Models\CouponCourse;
use App\Models\CouponInstructor;
use App\Models\Course;
use App\Models\CourseInstructor;
use App\Models\Enrollment;
use App\Models\Order;
use App\Models\Order_item;
use App\Models\Addon\Product\Product;
use App\Models\State;
use App\Models\Student;
use App\Models\User;
use App\Models\UserPackage;
use App\Models\Withdraw;
use App\Traits\ApiStatusTrait;
use App\Traits\General;
use App\Traits\ImageSaveTrait;
use App\Traits\SendNotification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Exception;

class CartManagementController extends Controller
{
    use ImageSaveTrait, General, SendNotification, ApiStatusTrait;

    private $_api_context;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    protected $logger;

    public function __construct()
    {
        $this->logger = new Logger();
    }

    public function cartList()
    {
        // Start:: Check course, bundle, consultation exists or not. If not exists, Delete it.
        $carts = CartManagement::whereUserId(@Auth::id())->with('course.promotionCourse.promotion', 'bundle', 'consultationSlot.user.instructor')->get();

        if (get_option('subscription_mode')) {
            $subscriptionPurchaseEnable = true;
        } else {
            $subscriptionPurchaseEnable = false;
        }

        foreach ($carts as $cart) {
            if ($cart->course_id && !$cart->course) {
                $cart->delete();
            } elseif ($cart->bundle_id && !$cart->bundle) {
                $cart->delete();
            } elseif ($cart->consultation_slot_id && !$cart->consultationSlot) {
                $cart->delete();
            } else {
                if (!$cart->is_subscription_enable) {
                    $subscriptionPurchaseEnable = false;
                }

                // Start:: Course & Promotion Course Check or not
                if ($cart->course) {
                    $course = $cart->course;
                    $startDate = date('d-m-Y H:i:s', strtotime(@$course->promotionCourse->promotion->start_date));
                    $endDate = date('d-m-Y H:i:s', strtotime(@$course->promotionCourse->promotion->end_date));
                    $percentage = @$course->promotionCourse->promotion->percentage;
                    $promotion_discount_price = number_format($course->price - (($course->price * $percentage) / 100), 2);

                    if (now()->gt($startDate) && now()->lt($endDate)) {
                        $cart->promotion_id = @$course->promotionCourse->promotion->id;
                        $cart->price = $promotion_discount_price;
                    } else {
                        $cart->main_price = $course->price;
                        $cart->price = $course->price;
                    }
                }
                // End:: Course & Promotion Course Check or not

                //Start:: Consultation Check
                elseif ($cart->consultationSlot) {
                    $consultationSlot = $cart->consultationSlot;
                    // User role check
                    if ($consultationSlot->user->role == USER_ROLE_INSTRUCTOR) {
                        $relation = 'instructor';
                    } elseif ($consultationSlot->user->role == USER_ROLE_ORGANIZATION) {
                        $relation = 'organization';
                    }
                    if ($consultationSlot) {
                        $consultationArray = array();
                        $newConsultationDataArray = [
                            'instructor_user_id' => $consultationSlot->user_id,
                            'student_user_id' => Auth::id(),
                            'consultation_slot_id' => $consultationSlot->id,
                            'date' => $cart->consultation_date,
                            'day' => $consultationSlot->day,
                            'time' => $consultationSlot->time,
                            'duration' => $consultationSlot->duration,
                            'status' => 0,
                        ];

                        $consultationArray[] = $newConsultationDataArray;
                        $cart->consultation_details = $consultationArray;

                        $hour_duration = $consultationSlot->hour_duration;
                        $minute_duration = $consultationSlot->minute_duration;

                        $hourly_rate = @$consultationSlot->user->$relation->hourly_rate;
                        $minuteCost = 0;
                        if ($minute_duration > 0) {
                            $minuteCost = ($hourly_rate / (60 / $minute_duration));
                        }
                        $totalCost = ($hour_duration * $hourly_rate) + $minuteCost;

                        $cart->main_price = $totalCost;
                        $cart->price = $totalCost;
                    }
                }
                //End:: Consultation Check

                //Start:: Bundle Offer Check
                if ($cart->bundle) {
                    $cart->main_price = $cart->bundle->price;
                    $cart->price = $cart->bundle->price;
                }
                //End:: Bundle Offer Check


                $cart->coupon_id = null;
                $cart->discount = 0;
                $cart->save();
            }
        }

        if ($subscriptionPurchaseEnable) {
            $subscriptionPurchaseEnable = hasLimit(PACKAGE_RULE_COURSE, count($carts));
        }
        $data['subscriptionPurchaseEnable'] = $subscriptionPurchaseEnable;
        $data['carts'] = [];
        $cartData = CartManagement::whereUserId(@Auth::id())->with('coupon','course.promotionCourse.promotion', 'bundle.user', 'product.reviews', 'consultationSlot.user.instructor', 'course.user.courses.reviews')->get();
        $filterData = [];
        foreach($cartData as $cart){
            $filterData["id"] = $cart->id;
            $filterData["user_id"] = $cart->user_id;
            $filterData["receiver_info"] = $cart->receiver_info;
            $filterData["course_id"] = $cart->course_id;
            $filterData["product_id"] = $cart->product_id;
            $filterData["quantity"] = $cart->quantity;
            $filterData["shipping_charge"] = $cart->shipping_charge;
            $filterData["consultation_slot_id"] = $cart->consultation_slot_id;
            $filterData["consultation_details"] = $cart->consultation_details;
            $filterData["consultation_date"] = $cart->consultation_date;
            $filterData["consultation_available_type"] = $cart->consultation_available_type;
            $filterData["bundle_id"] = $cart->bundle_id;
            $filterData["bundle_course_ids"] = $cart->bundle_course_ids;
            $filterData["promotion_id"] = $cart->promotion_id;
            $filterData["coupon_id"] = $cart->coupon_id;
            $filterData["coupon"] = $cart->coupon;
            $filterData["is_subscription_enable"] = $cart->is_subscription_enable;
            $filterData["main_price"] = $cart->main_price;
            $filterData["price"] = $cart->price;
            $filterData["discount"] = $cart->discount;
            $filterData["created_at"] = $cart->created_at;
            $filterData["updated_at"] = $cart->updated_at;
            $filterData["reference"] = $cart->reference;
            $filterData["bundle"] = $cart->bundle;
            $filterData["consultation_slot"] = $cart->consultation_slot;
            $filterData["course"] = $cart->course;
            $filterData["product"] = $cart->product;
            $filterData["bundle"] = $cart->bundle;
            $filterData["consultationSlot"] = $cart->consultationSlot;
            if($cart->course_id){
                $filterData['author'] = $cart->course->user->name;
                $filterData['author_level'] = get_instructor_ranking_level($cart->course->user->badges);
                $filterData['average_rating'] = (string) $cart->course->average_rating;
                $filterData['total_review'] = (int) $cart->course->reviews->count();
            }elseif($cart->bundle_id){
                $filterData['author'] = $cart->bundle->user->name;
                $filterData['author_level'] = get_instructor_ranking_level($cart->bundle->user->badges);
                $filterData['average_rating'] = (string) 0;
                $filterData['total_review'] = (int) 0;
            }elseif($cart->consultation_slot_id){
                $filterData['author'] = $cart->consultationSlot->user->name;
                $filterData['author_level'] = get_instructor_ranking_level($cart->consultationSlot->user->badges);
                $filterData['average_rating'] = (string) getUserAverageRating($cart->consultationSlot->user->id);
                $filterData['total_review'] = (int) getInstructorTotalReview($cart->consultationSlot->user->id);
            }elseif($cart->product_id){
                $filterData['author'] = $cart->product->user->name;
                $filterData['author_level'] = get_instructor_ranking_level($cart->product->user->badges);
                $filterData['average_rating'] = (string) @$cart->product->average_review;
                $filterData['total_review'] = (int) @$cart->product->reviews()->count();
            }

            array_push($data['carts'], $filterData);
        }

        $data['amount']= $carts->sum('price');
        $data['discount']= $cartData->sum('discount');
        $data['shipping_charge']= $carts->sum('shipping_charge');
        $data['platform_charge']= get_platform_charge($carts->sum('price')+$carts->sum('shipping_charge'));
        $data['platform_charge_percentage']= get_option('platform_charge');
        $data['grand_total'] = get_number_format($carts->sum('price') + $carts->sum('shipping_charge') + get_platform_charge($carts->sum('shipping_charge')+$carts->sum('price')));

        return $this->success($data);
    }

    public function applyCoupon(Request $request)
    {
        if (!Auth::check()) {
            $msg = __("You need to login first!");
            return $this->error([], $msg);
        }

        if (!$request->coupon_code) {
            $msg = __("Enter coupon code!");
            return $this->error([], $msg);
        }


        if ($request->id) {
            $cart = CartManagement::find($request->id);
            if (!$cart) {
                $msg = __("Cart item not found!");
                return $this->error([], $msg);
            }

            $coupon = Coupon::where('coupon_code_name', $request->coupon_code)->where('start_date', '<=', Carbon::now()->format('Y-m-d'))->where('end_date', '>=', Carbon::now()->format('Y-m-d'))->first();

            if ($coupon) {
                if ($cart->price < $coupon->minimum_amount) {
                    $msg = "Minimum " . get_currency_code() . $coupon->minimum_amount . " need to buy for use this coupon!";
                    return $this->error([], $msg);
                }
            }
            if (!$coupon) {
                $msg = __("Invalid coupon code!");
                return $this->error([], $msg);
            }


            if (CartManagement::whereUserId(@Auth::id())->whereCouponId($coupon->id)->count() > 0) {
                $msg = __("You've already used this coupon!");
                return $this->error([], $msg);
            }

            $discount_price = ($cart->price * $coupon->percentage) / 100;

            if ($coupon->coupon_type == 1) {
                $cart->price = round($cart->price - $discount_price);
                $cart->discount = $discount_price;
                $cart->coupon_id = $coupon->id;
                $cart->save();

                $carts = CartManagement::whereUserId(@Auth::id())->get();
                $msg = __("Coupon Applied");
                $responseData['price'] = $cart->price;
                $responseData['discount'] = $cart->discount;
                $responseData['total'] = get_number_format($carts->sum('price'));
                $responseData['platform_charge'] = get_platform_charge($carts->sum('price'));
                $responseData['grand_total'] = get_number_format($carts->sum('price') + get_platform_charge($carts->sum('price')));
                return $this->success($responseData, $msg);
            } elseif ($coupon->coupon_type == 2) {
                if ($cart->course) {
                    $user_id = $cart->course->user_id;
                } else if($cart->product_id){
                    $user_id = $cart->product->user_id;
                }else{
                    $user_id = NULL;
                }

                $couponInstructor = CouponInstructor::where('coupon_id', $coupon->id)->where('user_id', $user_id)->orderBy('id', 'desc')->first();
                if ($couponInstructor) {

                    $cart->price = round($cart->price - $discount_price);
                    $cart->discount = $discount_price;
                    $cart->coupon_id = $coupon->id;
                    $cart->save();

                    $carts = CartManagement::whereUserId(@Auth::id())->get();
                    $msg = __("Coupon Applied");
                    $responseData['price'] = $cart->price;
                    $responseData['discount'] = $cart->discount;
                    $responseData['total'] = get_number_format($carts->sum('price'));
                    $responseData['platform_charge'] = get_platform_charge($carts->sum('price'));
                    $responseData['grand_total'] = get_number_format($carts->sum('price') + get_platform_charge($carts->sum('price')));
                    return $this->success($responseData, $msg);
                } else {
                    $msg = __("Invalid coupon code!");
                    return $this->error([], $msg);
                }
            } elseif ($coupon->coupon_type == 3) {
                $couponCourse = CouponCourse::where('coupon_id', $coupon->id)->where('course_id', $cart->course_id)->orderBy('id', 'desc')->first();
                if ($couponCourse) {

                    $cart->price = round($cart->price - $discount_price);
                    $cart->discount = $discount_price;
                    $cart->coupon_id = $coupon->id;
                    $cart->save();

                    $carts = CartManagement::whereUserId(@Auth::id())->get();
                    $msg = __("Coupon Applied");
                    $responseData['price'] = $cart->price;
                    $responseData['discount'] = $cart->discount;
                    $responseData['total'] = get_number_format($carts->sum('price'));
                    $responseData['platform_charge'] = get_platform_charge($carts->sum('price'));
                    $responseData['grand_total'] = get_number_format($carts->sum('price') + get_platform_charge($carts->sum('price')));
                    return $this->success($responseData, $msg);
                } else {
                    $msg = __("Invalid coupon code!");
                    return $this->error([], $msg);
                }
            } else {
                $msg = __("Invalid coupon code!");
                    return $this->error([], $msg);
            }
        } else {
            $msg = __("Cart item not found!");
            return $this->error([], $msg);
        }
    }

    public function addToCart(Request $request)
    {
        DB::beginTransaction();
        try {
            if ($request->course_id) {
                $enrollment = Enrollment::where(['course_id' => $request->course_id, 'user_id' => Auth::user()->id, 'status' => ACCESS_PERIOD_ACTIVE])->whereDate('end_date', '>=', now())->first();

                if ($enrollment) {
                    $order = Order::find($enrollment->order_id);
                    if ($order) {
                        if ($order->payment_status == 'due') {
                            Order_item::whereOrderId($enrollment->order_id)->get()->map(function ($q) {
                                $q->delete();
                            });
                            $order->delete();
                        } elseif ($order->payment_status == 'pending') {
                            $msg = __("You've already request the course & status is pending!");
                            DB::rollBack();
                            return $this->success([], $msg);
                        } elseif ($order->payment_status == 'paid' || $order->payment_status == 'free') {
                            $msg = __("You've already purchased the course!");
                            DB::rollBack();
                            return $this->success([], $msg);
                        }
                    } else {
                        $msg = __("Something is wrong! Try again.");
                        DB::rollBack();
                        return $this->failed([], $msg);
                    }
                }

                $ownCourseCheck = CourseInstructor::where('course_id', $request->course_id)->where('instructor_id', $request->user_id)->delete();

                if ($ownCourseCheck) {
                    $msg = __("This is your course. No need to add to cart.");
                    DB::rollBack();
                    return $this->success([], $msg);
                }

                $courseExits = Course::find($request->course_id);
                if (!$courseExits) {
                    $msg = __("Course not found!");
                    DB::rollBack();
                    return $this->error([], $msg, 404);
                }
            }

            if ($request->product_id) {
                $productExits = Product::find($request->product_id);
                if (!$productExits) {
                    $msg = __("Product not found!");
                    DB::rollBack();
                    return $this->error([], $msg, 404);
                }
            }

            if ($request->bundle_id) {
                $bundleExits = Bundle::find($request->bundle_id);
                if (!$bundleExits) {
                    $msg = __("Bundle not found!");
                    DB::rollBack();
                    return $this->error([], $msg, 404);
                }

                $ownBundleCheck = Bundle::whereUserId(Auth::user()->id)->where('id', $request->bundle_id)->first();
                if ($ownBundleCheck) {
                    $msg = __("This is your bundle offer. No need to add to cart.");
                    DB::rollBack();
                    return $this->success([], $msg);
                }

                $enrollment = Enrollment::where(['user_id' => Auth::user()->id, 'bundle_id' => $request->bundle_id, 'status' => ACCESS_PERIOD_ACTIVE])->whereDate('end_date', '>=', now())->first();
                if ($enrollment) {
                    $order = Order::find($enrollment->order_id);
                    if ($order) {
                        if ($order->payment_status == 'due') {
                            Order_item::whereOrderId($enrollment->order_id)->get()->map(function ($q) {
                                $q->delete();
                            });
                            $order->delete();
                        } elseif ($order->payment_status == 'pending') {
                            $msg = __("You've already request this bundle & status is pending!");
                            DB::rollBack();
                            return $this->success([], $msg);
                        } elseif ($order->payment_status == 'paid' || $order->payment_status == 'free') {
                            $msg = __("You've already purchased this bundle!");
                            DB::rollBack();
                            return $this->success([], $msg);
                        }
                    } else {
                        $msg = __("Something is wrong! Try again.");
                        DB::rollBack();
                        return $this->failed([], $msg);
                    }
                }
            }

            //Start:: Cart Management
            if ($request->course_id) {
                $cartExists = CartManagement::whereUserId(Auth::user()->id)->whereCourseId($request->course_id)->first();
            } elseif ($request->product_id) {
                $cartExists = CartManagement::whereUserId(Auth::user()->id)->whereProductId($request->product_id)->first();
            } elseif ($request->bundle_id) {
                $cartExists = CartManagement::whereUserId(Auth::user()->id)->whereBundleId($request->bundle_id)->first();
            }

            if ($cartExists) {
                $msg = __("Already added to cart!");
                DB::rollBack();
                return $this->success([], $msg);
            }

            if ($request->course_id) {
                if ($courseExits->learner_accessibility == 'free') {
                    $order = new Order();
                    $order->user_id = Auth::user()->id;
                    $order->order_number = rand(100000, 999999);
                    $order->payment_status = 'free';
                    $order->save();

                    $order_item = new Order_item();
                    $order_item->order_id = $order->id;
                    $order_item->user_id = Auth::user()->id;
                    $order_item->course_id = $courseExits->id;
                    $order_item->owner_user_id = $courseExits->user_id ?? null;
                    $order_item->unit_price = 0;
                    $order_item->admin_commission = 0;
                    $order_item->owner_balance = 0;
                    $order_item->sell_commission = 0;
                    $order_item->save();
                    $enrollment = new Enrollment();
                    $enrollment->order_id = $order->id;
                    $enrollment->user_id = auth()->id();
                    $enrollment->course_id = $request->course_id;
                    $enrollment->owner_user_id = $courseExits->user_id ?? null;
                    $enrollment->start_date = now();
                    $enrollment->end_date = ($courseExits->access_period) ? Carbon::now()->addDays($courseExits->access_period) : MAX_EXPIRED_DATE;
                    $enrollment->save();

                    $msg = __("Free Course added to your my learning list!");
                    DB::commit();
                    return $this->success([], $msg);
                }
            }

            $cart = new CartManagement();
            $cart->user_id = Auth::user()->id;
            $cart->course_id = $request->course_id;
            $cart->product_id = $request->product_id;
            $cart->bundle_id = $request->bundle_id;
            if ($request->course_id) {
                $course = Course::findOrFail($request->course_id);
                $cart->is_subscription_enable = $course->is_subscription_enable;
                $cart->main_price = $courseExits->price;
                $cart->price = $courseExits->price;
            } else if ($request->bundle_id) {
                $bundle = Bundle::findOrFail($request->bundle_id);
                $cart->is_subscription_enable = $bundle->is_subscription_enable;
                $bundleCourses = BundleCourse::where('bundle_id', $request->bundle_id)->pluck('course_id')->toArray();
                $cart->bundle_course_ids = $bundleCourses;
                $cart->main_price = $bundleExits->price;
                $cart->price = $bundleExits->price;
            } else {
                $message = __("Please select the item to add in wishlist");
                return $this->error([], $message, 404);
            }

            $refData = $request->get('ref', '');
            if ($refData != '') {
                $refList = json_decode($refData);
                foreach ($refList as $refHash) {
                    $refPair = explode('$', $refHash);
                    if ($refPair[0] == $cart->course_id) {
                        $refRequest = AffiliateRequest::where(['affiliate_code' => $refPair[1], 'status' => STATUS_APPROVED])->first();
                        if (!is_null($refRequest)) {
                            $refUser = User::where(['id' => $refRequest->user_id])->first();
                            if ($refUser->is_affiliator == AFFILIATOR) {
                                $cart->reference = $refPair[1];
                                break;
                            }
                        }
                    }
                }
            }

            $cart->save();

            $response['quantity'] = CartManagement::whereUserId(Auth::user()->id)->count();
            $msg = __("Added to cart");
            DB::commit();
            return $this->success([], $msg);
        } catch (\Exception $e) {
            DB::rollBack();
            $msg = __("Something is wrong! Try again.");
            return $this->failed([], $msg);
        }
    }

    public function cartCount(Request $request)
    {
        $totalCart = CartManagement::whereUserId(@Auth::id())->count();

        return $this->success(['total' => $totalCart]);
    }

    public function addToCartConsultation(Request $request)
    {

        if ($request->consultation_slot_id) {
            DB::beginTransaction();
            try {
                $consultationExit = ConsultationSlot::whereId($request->consultation_slot_id)->whereUserId($request->booking_instructor_user_id)->first();

                if (!$consultationExit) {
                    DB::rollBack();
                    $msg = __("Time slot not found!");
                    return $this->error([], $msg, 404);
                }

                $ownConsultationSlotCheck = ConsultationSlot::whereId($request->consultation_slot_id)->whereUserId(Auth::id())->first();
                if ($ownConsultationSlotCheck) {
                    DB::rollBack();
                    $msg = __("This is your consultation slot. No need to add.");
                    return $this->success([], $msg);
                }

                $consultationOrderBooked = Order_item::whereConsultationSlotId($request->consultation_slot_id)->where('consultation_date', $request->bookingDate)->first();
                if ($consultationOrderBooked) {
                    $order = Order::find($consultationOrderBooked->order_id);
                    if ($order) {
                        if ($order->payment_status == 'paid' || $order->payment_status == 'free') {
                            DB::rollBack();
                            $msg = __("This slot already purchased. Please try another slot.");
                            return $this->success([], $msg);
                        }
                    } else {
                        DB::rollBack();
                        $msg = __("Something is wrong! Try again.");
                        return $this->failed([], $msg);
                    }
                }

                $consultationOrderExit = Order_item::whereUserId(Auth::user()->id)->whereConsultationSlotId($request->consultation_slot_id)->where('consultation_date', $request->bookingDate)->first();
                if ($consultationOrderExit) {
                    $order = Order::find($consultationOrderExit->order_id);
                    if ($order) {
                        if ($order->payment_status == 'due') {
                            Order_item::whereOrderId($consultationOrderExit->order_id)->get()->map(function ($q) {
                                $q->delete();
                            });
                            $order->delete();
                        } elseif ($order->payment_status == 'pending') {
                            DB::rollBack();
                            $msg = __("You've already request this slot & status is pending!");
                            return $this->success([], $msg);
                        } elseif ($order->payment_status == 'paid' || $order->payment_status == 'free') {
                            DB::rollBack();
                            $msg = __("You've already purchased this slot!");
                            return $this->success([], $msg);
                        }
                    } else {
                        DB::rollBack();
                        $msg = __("Something is wrong! Try again.");
                        return $this->success([], $msg);
                    }
                }

                $consultationOrderExit = Order_item::whereConsultationSlotId($request->consultation_slot_id)->where('consultation_date', $request->bookingDate)->first();
                if ($consultationOrderExit) {
                    $order = Order::find($consultationOrderExit->order_id);
                    if ($order) {
                        if ($order->payment_status == 'pending') {
                            DB::rollBack();
                            $msg = __("Another User already request this slot!");
                            return $this->failed([], $msg);
                        } elseif ($order->payment_status == 'paid' || $order->payment_status == 'free') {
                            DB::rollBack();
                            $msg = __("Another User already purchased this slot!");
                            return $this->failed([], $msg);
                        }
                    } else {
                        DB::rollBack();
                        $msg = __("Something is wrong! Try again.");
                        return $this->failed([], $msg);
                    }
                }


                $cartExists = CartManagement::whereUserId(Auth::user()->id)->whereConsultationSlotId($request->consultation_slot_id)->where('consultation_date', $request->bookingDate)->first();
                if ($cartExists) {
                    DB::rollBack();
                    $msg = __("Already added to cart!");
                    return $this->failed([], $msg);
                }

                $cart = new CartManagement();
                $cart->user_id = Auth::user()->id;
                $cart->consultation_slot_id = $request->consultation_slot_id;
                $consultationArray = array();
                $newConsultationDataArray = [
                    'instructor_user_id' => $consultationExit->user_id,
                    'student_user_id' => Auth::id(),
                    'consultation_slot_id' => $consultationExit->id,
                    'date' => $request->bookingDate,
                    'day' => $consultationExit->day,
                    'time' => $consultationExit->time,
                    'duration' => $consultationExit->duration,
                    'status' => 0,
                ];

                $consultationArray[] = $newConsultationDataArray;

                $cart->consultation_details = $consultationArray;
                $cart->consultation_date = $request->bookingDate;
                $cart->consultation_available_type = $request->available_type;

                $consultationUser = User::whereId($request->booking_instructor_user_id)->first();
                $relation = getUserRoleRelation($consultationUser);
                $cart->is_subscription_enable = $consultationUser->$relation->is_subscription_enable;

                /*
                * Price Calculation
                */
                $hour_duration = $consultationExit->hour_duration;
                $minute_duration = $consultationExit->minute_duration;
                // User role check
                if ($consultationExit->user->role == USER_ROLE_INSTRUCTOR) {
                    $relation = 'instructor';
                } elseif ($consultationExit->user->role == USER_ROLE_ORGANIZATION) {
                    $relation = 'organization';
                }
                $hourly_rate = @$consultationExit->user->$relation->hourly_rate;
                $minuteCost = 0;
                if ($minute_duration > 0) {
                    $minuteCost = ($hourly_rate / (60 / $minute_duration));
                }
                $totalCost = ($hour_duration * $hourly_rate) + $minuteCost;

                $cart->main_price = $totalCost;
                $cart->price = $totalCost;
                $cart->save();

                DB::commit();
                $msg = __("Consultation added to cart");
                return $this->success([], $msg);
            } catch (\Exception $e) {
                DB::rollBack();
                $msg = __("Something is wrong! Try again.");
                return $this->failed([], $msg);
            }
        } else {
            $msg = __("Time slot not found!");
            return $this->failed([], $msg);
        }
    }

    public function cartDelete($id)
    {
        $cart = CartManagement::find($id);
        if(is_null($cart)){
            return $this->error([], __('Data Not found'));
        }
        $cart->delete();
        return $this->success([], __('Removed from cart list!'));
    }

    public function fetchBank(Request $request)
    {
        $bank_id = Bank::find($request->bank_id);
        if ($bank_id) {
            return response()->json([
                'account_number' => $bank_id->account_number,
            ]);
        }
    }

    public function checkout()
    {
        $data['carts'] = CartManagement::whereUserId(@Auth::id())->with('coupon')->get();
        $data['student'] = auth::user()->student;
        $data['countries'] = Country::orderBy('country_name', 'asc')->get();
        if (old('country_id')) {
            $data['states'] = State::where('country_id', old('country_id'))->orderBy('name', 'asc')->get();
        }

        if (old('state_id')) {
            $data['cities'] = City::where('state_id', old('state_id'))->orderBy('name', 'asc')->get();
        }

        $paypal_grand_total_with_conversion_rate = ($data['carts']->sum('price') + get_platform_charge($data['carts']->sum('price'))) * (get_option('paypal_conversion_rate') ? get_option('paypal_conversion_rate') : 0);
        $data['paypal_grand_total_with_conversion_rate'] = (float)preg_replace("/[^0-9.]+/", "", number_format($paypal_grand_total_with_conversion_rate, 2));

        $mercadopago_grand_total_with_conversion_rate = ($data['carts']->sum('price') + get_platform_charge($data['carts']->sum('price'))) * (get_option('mercado_conversion_rate') ? get_option('mercado_conversion_rate') : 0);
        $data['mercadopago_grand_total_with_conversion_rate'] = (float)preg_replace("/[^0-9.]+/", "", number_format($mercadopago_grand_total_with_conversion_rate, 2));
        $data['get_platform_charge'] = get_platform_charge($data['carts']->sum('price'));

        return $this->success($data);
    }

    public function pay(Request $request)
    {
        if(CartManagement::whereUserId(@Auth::id())->count() == 0){
            return $this->error([], __('Your cart is empty'));
        }

        if (is_null($request->payment_method)) {
            return $this->error([], __('Please Select Payment Method'));
        }
        if ($request->payment_method == 'bank') {
            if (empty($request->deposit_by) || is_null($request->deposit_slip)) {
                return $this->error([], __('Please Select Payment Method'));
            }
        }

        if ($request->payment_method == 'paypal') {
            if (empty(env('PAYPAL_CLIENT_ID')) || empty(env('PAYPAL_SECRET')) || empty(env('PAYPAL_MODE'))) {
                return $this->error([], __('Paypal payment gateway is off!'));
            }
        }

        if ($request->payment_method == 'mollie') {
            if (empty(env('MOLLIE_KEY'))) {
                return $this->error([], __('Mollie payment gateway is off!'));
            }
        }

        if ($request->payment_method == 'instamojo') {
            if (empty(env('IM_API_KEY')) || empty(env('IM_AUTH_TOKEN')) || empty(env('IM_URL'))) {
                return $this->error([], __('Instamojo payment gateway is off!'));
            }
        }
        if ($request->payment_method == 'paystack') {
            if (empty(env('PAYSTACK_PUBLIC_KEY')) || empty(env('PAYSTACK_SECRET_KEY'))) {
                return $this->error([], __('Paystack payment gateway is off!'));
            }
        }
        if ($request->payment_method == 'coinbase') {
            if (empty(get_option('coinbase_key'))) {
                return $this->error([], __('Coinbase payment gateway is off!'));
            }
        }
        if ($request->payment_method == 'zitopay') {
            if (empty(get_option('zitopay_username'))) {
                return $this->error([], __('Zitopay payment gateway is off!'));
            }
        }
        if ($request->payment_method == 'iyzipay') {
            if (empty(get_option('iyzipay_key'))) {
                return $this->error([], __('Iyzipay payment gateway is off!'));
            }
        }
        if ($request->payment_method == 'bitpay') {
            if (empty(get_option('bitpay_key'))) {
                return $this->error([], __('Bitpay payment gateway is off!'));
            }
        }
        if ($request->payment_method == 'braintree') {
            if (empty(get_option('braintree_key'))) {
                return $this->error([], __('Braintree payment gateway is off!'));
            }
        }

        $order_data = $this->placeOrder($request->payment_method);
        if($order_data['status']){
            $order = $order_data['data'];
        }else{
            return $this->error([], __('Something went wrong!'));
        }

        /** order billing address */

        if (auth::user()->student) {
            $student = Student::find(auth::user()->student->id);
            $student->fill($request->all());
            $student->save();
        }

        if ($request->payment_method == PAYPAL) {
            $total = $order->grand_total * (get_option('paypal_conversion_rate') ? get_option('paypal_conversion_rate') : 0);
            $total = number_format($total, 2,'.','');
            $object = [
                'currency' => get_option('paypal_currency')
            ];

        } else if ($request->payment_method == MOLLIE) {
            $object = [
                'currency' => get_option('mollie_currency')
            ];
            $total = $order->grand_total * (get_option('mollie_conversion_rate') ? get_option('mollie_conversion_rate') : 0);
            $total = number_format($total, 2,'.','');
        } else if ($request->payment_method == MERCADOPAGO) {
            $object = [
                'currency' => get_option('mercado_currency')
            ];
            $total = $order->grand_total * (get_option('mercado_conversion_rate') ? get_option('mercado_conversion_rate') : 0);
            $total = number_format($total, 2,'.','');
        }  else if ($request->payment_method == FLUTTERWAVE) {
            $object = [
                'currency' => get_option('flutterwave_currency')
            ];
            $total = $order->grand_total * (get_option('flutterwave_conversion_rate') ? get_option('flutterwave_conversion_rate') : 0);
            $total = number_format($total, 2,'.','');
        } else if ($request->payment_method == INSTAMOJO) {
            $total = $order->grand_total * (get_option('im_conversion_rate') ? get_option('im_conversion_rate') : 0);
            $total = number_format($total, 2,'.','');
            $object = [
                'currency' => get_option('im_currency')
            ];

        } else if ($request->payment_method == PAYSTAC) {
            $total = $order->grand_total * (get_option('paystack_conversion_rate') ? get_option('paystack_conversion_rate') : 0);
            $total = number_format($total, 2,'.','');
            $object = [
                'currency' => get_option('paystack_currency'),
                'reference' => $request->reference
            ];

        } else if ($request->payment_method == COINBASE) {
            $total = $order->grand_total * (get_option('coinbase_conversion_rate') ? get_option('coinbase_conversion_rate') : 0);
            $total = number_format($total, 2,'.','');
            $object = [
                'currency' => get_option('coinbase_currency'),
                'reference' => $request->reference
            ];
        } else if ($request->payment_method == ZITOPAY) {
            $total = $order->grand_total * (get_option('zitopay_conversion_rate') ? get_option('zitopay_conversion_rate') : 0);
            $total = number_format($total, 2,'.','');
            $object = [
                'currency' => get_option('zitopay_currency'),
                'reference' => $request->reference
            ];
        } else if ($request->payment_method == IYZIPAY) {
            $total = $order->grand_total * (get_option('iyzipay_conversion_rate') ? get_option('iyzipay_conversion_rate') : 0);
            $total = number_format($total, 2,'.','');
            $object = [
                'currency' => get_option('iyzipay_currency'),
                'reference' => $request->reference
            ];
        } else if ($request->payment_method == BITPAY) {
            $total = $order->grand_total * (get_option('bitpay_conversion_rate') ? get_option('bitpay_conversion_rate') : 0);
            $total = number_format($total, 2,'.','');
            $object = [
                'currency' => get_option('bitpay_currency'),
                'reference' => $request->reference
            ];
        } else if ($request->payment_method == BRAINTREE) {
            $total = $order->grand_total * (get_option('braintree_conversion_rate') ? get_option('braintree_conversion_rate') : 0);
            $total = number_format($total, 2,'.','');
            $object = [
                'currency' => get_option('braintree_currency'),
                'reference' => $request->reference

            ];

        } else if ($request->payment_method == BANK) {
            $deposit_by = $request->deposit_by;
            $deposit_slip = $this->uploadFileWithDetails('bank', $request->deposit_slip);
            if (!$deposit_slip['is_uploaded']) {
                return $this->error([], __('Something went wrong! Failed to upload file'));
            }

            $order->payment_status = 'pending';
            $order->deposit_by = $deposit_by;
            $order->deposit_slip = $deposit_slip['path'];
            $order->payment_method = 'bank';
            $order->bank_id = $request->bank_id;
            $order->save();

            CartManagement::whereUserId(@Auth::id())->delete();

            /** ====== Send notification =========*/
            $text = __("New course enrolled pending request");
            $target_url = route('report.order-pending');
            $this->send($text, 1, $target_url, null);
            /** ====== Send notification =========*/
            return $this->success([], __('Request has been Placed! Please Wait for Approve'));
        } else if ($request->payment_method == SSLCOMMERZ)  {

            $total = $order->grand_total * (get_option('sslcommerz_conversion_rate') ? get_option('sslcommerz_conversion_rate') : 0);
            $total = number_format($total, 2,'.','');
            # CUSTOMER INFORMATION
            $post_data = array();
            $post_data['tran_id'] = $order->uuid; // tran_id must be unique
            $post_data['product_category'] = "Payment for purchase";
            $phone = '0170000';
            $student = $order->user->student;

            $post_data['cus_name'] = Auth::user()->name;
            $post_data['cus_phone'] = $request->input('phone_number',$student->address);
            $post_data['cus_email'] = $request->input('email',$order->user->email);
            $post_data['cus_add1'] = $request->input('address',$student->address);
            $post_data['cus_add2'] = "";
            $post_data['cus_city'] = "";
            $post_data['cus_state'] = "";
            $post_data['cus_postcode'] = $request->input('postal_code','017');
            $post_data['cus_country'] = @$student->country->country_name ?? 'BD';
            $post_data['cus_phone'] = $phone;
            $post_data['cus_fax'] = "";

            # SHIPMENT INFORMATION
            $post_data['ship_name'] = get_option('app_name') ?? 'LMS Store';
            $post_data['ship_add1'] = $request->input('phone_number',$student->address);
            $post_data['ship_add2'] =  '';
            $post_data['ship_city'] =  '';
            $post_data['ship_state'] =  '';
            $post_data['ship_postcode'] = '';
            $post_data['ship_phone'] = $request->input('phone_number',$student->address);
            $post_data['ship_country'] = @$student->country->country_name ?? 'BD';

            $post_data['shipping_method'] = "NO";
            $post_data['product_name'] = "Course Buy";

            $post_data['product_profile'] = "digital-goods";

            # OPTIONAL PARAMETERS
            $post_data['value_a'] = "ref001";
            $post_data['value_b'] = "ref002";
            $post_data['value_c'] = "ref003";
            $post_data['value_d'] = "ref004";

            $object = [
                'id' => $order->uuid,
                'payment_method' => SSLCOMMERZ,
                'currency' => get_option('sslcommerz_currency')
            ];

            $object['successUrl'] = route('api.payment-order-notify', $order->uuid);
            $object['cancelUrl'] = route('api.payment-order-notify', $order->uuid);

            $getWay = new BasePaymentService($object);
            $responseData = $getWay->makePayment($total,$post_data);
            if($responseData['success']){
                $order->payment_id = $responseData['payment_id'];
                $order->save();
                return $this->success(['url' => $responseData['redirect_url'], 'order_id' => $order->uuid]);
            }else{
                return $this->error([], __('Something went wrong!'));
            }
        }

        $order_data = $this->placeOrder($request->payment_method);
        if ($order_data['status']) {
            $order = $order_data['data'];
        } else {
            return $this->error([], __('Something went wrong!'));
        }

        /** order billing address */
        if (auth::user()->student) {
            $student = Student::find(auth::user()->student->id);
            $student->fill($request->all());
            $student->save();
        }

        $object['id'] = $order->uuid;
        $object['payment_method'] = $request->payment_method;
        $object['successUrl'] = route('api.payment-order-notify', $order->uuid);
        $object['cancelUrl'] = route('api.payment-order-notify', $order->uuid);

        try{
            $getWay = new BasePaymentService($object);
            $responseData = $getWay->makePayment($total);
            if ($responseData['success']) {
                $order->payment_id = $responseData['payment_id'];
                $order->save();
                return $this->success(['url' => $responseData['redirect_url'], 'order_id' => $order->uuid]);
            } else {
                return $this->error([], __('Something went wrong!'));
            }
        }catch(Exception $e){
            return $this->error([], __('Something went wrong!'));
        }
    }

    private function placeOrder($payment_method)
    {
        DB::beginTransaction();
        try {
            $carts = CartManagement::whereUserId(@Auth::id())->get();
            $order = new Order();
            $order->user_id = Auth::user()->id;
            $order->order_number = rand(100000, 999999);
            $order->sub_total = $carts->sum('price');
            $order->discount = $carts->sum('discount');
            $order->platform_charge = get_platform_charge($carts->sum('price'));
            $order->current_currency = get_currency_code();
            $order->grand_total = $order->sub_total + $order->platform_charge;
            $order->payment_method = $payment_method;

            $payment_currency = '';
            $conversion_rate = '';

            if ($payment_method == 'paypal') {
                $payment_currency = get_option('paypal_currency');
                $conversion_rate = get_option('paypal_conversion_rate') ? get_option('paypal_conversion_rate') : 0;
            } elseif ($payment_method == 'mercadopago') {
                $payment_currency = get_option('mercado_currency');
                $conversion_rate = get_option('mercado_conversion_rate') ? get_option('mercado_conversion_rate') : 0;
            }

            $order->payment_currency = $payment_currency;
            $order->conversion_rate = $conversion_rate;
            if ($conversion_rate) {
                $order->grand_total_with_conversation_rate = ($order->sub_total + $order->platform_charge) * $conversion_rate;
            }

            $order->save();

            foreach ($carts as $cart) {
                if ($cart->course_id) {
                    $order_item = new Order_item();
                    $order_item->order_id = $order->id;
                    $order_item->user_id = Auth::id();
                    $order_item->course_id = $cart->course_id;
                    $order_item->owner_user_id = $cart->course ? $cart->course->user_id : null;
                    $order_item->unit_price = $cart->price;
                    $userPackage =  UserPackage::join('packages', 'packages.id', '=', 'user_packages.package_id')->whereIn('packages.package_type', [PACKAGE_TYPE_SAAS_INSTRUCTOR, PACKAGE_TYPE_SAAS_ORGANIZATION])->where('user_packages.user_id', $order_item->owner_user_id)->where('user_packages.status', PACKAGE_STATUS_ACTIVE)->whereDate('enroll_date', '<=', now())->whereDate('expired_date', '>=', now())->first();
                    $adminCommission = ($userPackage && $userPackage->admin_commission) ? $userPackage->admin_commission : get_option('sell_commission');
                    if ($adminCommission) {
                        $order_item->admin_commission = admin_commission_by_percentage($cart->price, $adminCommission);
                        $order_item->owner_balance = $cart->price - admin_commission_by_percentage($cart->price, $adminCommission);
                        $order_item->sell_commission = $adminCommission;
                    } else {
                        $order_item->owner_balance = $cart->price;
                    }

                    $order_item->save();
                    $this->addAffiliateHistory($cart, $order, $order_item);
                } elseif ($cart->bundle_id) {
                    // $bundleIds = Enrollment::where('user_id', auth()->id())->whereNotIn('course_id', $cart->bundle_course_ids)->whereDate('end_date', '<', now())->select('course_id')->get()->toArray();
                    $courses = Course::whereIn('id', $cart->bundle_course_ids)->get();
                    $bundleUserId = $cart->bundle->user_id;
                    $userPackage =  UserPackage::join('packages', 'packages.id', '=', 'user_packages.package_id')->whereIn('packages.package_type', [PACKAGE_TYPE_SAAS_INSTRUCTOR, PACKAGE_TYPE_SAAS_ORGANIZATION])->where('user_packages.user_id', $bundleUserId)->where('user_packages.status', PACKAGE_STATUS_ACTIVE)->whereDate('enroll_date', '<=', now())->whereDate('expired_date', '>=', now())->first();
                    $adminCommission = ($userPackage && $userPackage->admin_commission) ? $userPackage->admin_commission : get_option('sell_commission');
                    if ($adminCommission) {
                        $adminCommissionAmount = admin_commission_by_percentage($cart->price, $adminCommission);
                        $totalDistributeAmount = $cart->price - admin_commission_by_percentage($cart->price, $adminCommission);
                    } else {
                        $adminCommissionAmount = admin_commission_by_percentage($cart->price, $adminCommission);
                        $totalDistributeAmount = $cart->price - admin_commission_by_percentage($cart->price, $adminCommission);
                    }

                    $totalCourseAmount = $courses->sum('price');
                    $finalPriceRatio = $totalDistributeAmount / $totalCourseAmount;
                    $adminCommissionRatio = ($totalCourseAmount) ? $adminCommissionAmount / $totalCourseAmount : 0;
                    $sellCommissionRatio = ($totalCourseAmount) ? $adminCommission / $totalCourseAmount : 0;

                    /*
                     * All bundle course added but not calculate balance, commission etc
                     */
                    foreach ($courses as $course) {
                        /*
                        need to add bundle course in order list
                        Old paid course check with bundle course
                        */

                        $order_item = new Order_item();
                        $order_item->order_id = $order->id;
                        $order_item->user_id = Auth::user()->id;
                        $order_item->bundle_id = $cart->bundle_id;
                        $order_item->owner_user_id = $bundleUserId;
                        $order_item->unit_price = $course->price;
                        $order_item->owner_balance = $finalPriceRatio * $course->price;
                        $order_item->sell_commission = $sellCommissionRatio * $course->price;
                        $order_item->admin_commission = $adminCommissionRatio * $course->price;
                        $order_item->course_id = $course->id;
                        $order_item->type = 3; //bundle course
                        $order_item->save();
                        $this->addAffiliateHistory($cart, $order, $order_item);
                    }
                } elseif ($cart->consultation_slot_id) {
                    $order_item = new Order_item();
                    $order_item->order_id = $order->id;
                    $order_item->user_id = Auth::id();
                    $order_item->owner_user_id = is_array($cart['consultation_details']) ? $cart['consultation_details'][0]->instructor_user_id : null;
                    $order_item->consultation_slot_id = $cart->consultation_slot_id;
                    $order_item->consultation_date = $cart->consultation_date;
                    $order_item->unit_price = $cart->price;

                    $userPackage =  UserPackage::join('packages', 'packages.id', '=', 'user_packages.package_id')->whereIn('packages.package_type', [PACKAGE_TYPE_SAAS_INSTRUCTOR, PACKAGE_TYPE_SAAS_ORGANIZATION])->where('user_packages.user_id', $order_item->owner_user_id)->where('user_packages.status', PACKAGE_STATUS_ACTIVE)->whereDate('enroll_date', '<=', now())->whereDate('expired_date', '>=', now())->first();
                    $adminCommission = ($userPackage && $userPackage->admin_commission) ? $userPackage->admin_commission : get_option('sell_commission');
                    if ($adminCommission) {
                        $order_item->admin_commission = admin_commission_by_percentage($cart->price, $adminCommission);
                        $order_item->owner_balance = $cart->price - admin_commission_by_percentage($cart->price, $adminCommission);
                        $order_item->sell_commission = $adminCommission;
                    } else {
                        $order_item->owner_balance = $cart->price;
                    }

                    $order_item->type = 4;
                    $order_item->save();

                    //Start:: Need to add Booking History
                    $booking = new BookingHistory();
                    $booking->order_id = $order->id;
                    $booking->order_item_id = $order_item->id;
                    $booking->instructor_user_id = is_array($cart['consultation_details']) ? $cart['consultation_details'][0]->instructor_user_id : null;
                    $booking->student_user_id = Auth::id();
                    $booking->consultation_slot_id = $cart->consultation_slot_id;
                    $booking->date = is_array($cart['consultation_details']) ? $cart['consultation_details'][0]->date : null;
                    $booking->day = is_array($cart['consultation_details']) ? $cart['consultation_details'][0]->day : null;
                    $booking->time = is_array($cart['consultation_details']) ? $cart['consultation_details'][0]->time : null;
                    $booking->duration = is_array($cart['consultation_details']) ? $cart['consultation_details'][0]->duration : null;
                    $booking->type = $cart->consultation_available_type;
                    $booking->status = 0; //Pending
                    $booking->save();

                    //End:: Add Booking History
                }
            }

            DB::commit();
            return ['status' => true, 'data' => $order];
        } catch (\Exception $e) {
            DB::rollBack();
            $this->logger->log('Cannot Create Order', $e->getMessage());
            return ['status' => false, 'data' => null];
        }
    }

    private function addAffiliateHistory($cart, $order, $order_item)
    {
        if (get_option('referral_status')) {
            $refRequest = AffiliateRequest::where(['affiliate_code' => $cart->reference, 'status' => STATUS_APPROVED])->first();
            if (!is_null($refRequest)) {
                $refUser = User::where(['id' => $refRequest->user_id])->first();
                $alreadyAffiliated = AffiliateHistory::where(['user_id' => $refUser->id, 'course_id' => $cart->course_id])->first();
                if ($refUser->is_affiliator == AFFILIATOR && is_null($alreadyAffiliated)) {
                    $commission = referral_sell_commission($cart->price - $cart->discount);
                    $affiliate = new AffiliateHistory();
                    $affiliate->hash = Str::uuid()->getHex();
                    $affiliate->user_id = $refUser->id;
                    $affiliate->buyer_id = Auth::id();
                    $affiliate->order_id = $order->id;
                    $affiliate->order_item_id = $order_item->id;
                    if ($cart->course_id) {
                        $affiliate->course_id = $cart->course_id;
                    } elseif ($cart->bundle_id) {
                        $affiliate->bundle_id = $cart->bundle_id;
                    }
                    $affiliate->actual_price = $cart->price;
                    $affiliate->discount = $cart->discount;
                    $affiliate->commission = $commission;
                    $affiliate->commission_percentage = get_option('referral_commission_percentage');
                    $affiliate->save();
                }
            }
        }
    }
}
