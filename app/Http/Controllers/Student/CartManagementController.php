<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Logger;
use App\Http\Services\Payment\BasePaymentService;
use App\Models\Addon\Product\Product;
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
use App\Models\State;
use App\Models\Student;
use App\Models\User;
use App\Models\UserPackage;
use App\Models\Withdraw;
use App\Traits\General;
use App\Traits\ImageSaveTrait;
use App\Traits\SendNotification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Rest\ApiContext;
use Session;
use Redirect;
use Razorpay\Api\Api;
use Exception;
use Mollie\Laravel\Facades\Mollie;

class CartManagementController extends Controller
{
    use ImageSaveTrait, General, SendNotification;

    private $_api_context;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    protected $logger;

    public function __construct()
    {
        /** Mollie api key **/
        if (env('MOLLIE_KEY')) {
            Mollie::api()->setApiKey(env('MOLLIE_KEY'));
        }

        /** PayPal api context **/
        $paypal_conf = \Config::get('paypal');
        $this->_api_context = new ApiContext(
            new OAuthTokenCredential(
                $paypal_conf['client_id'],
                $paypal_conf['secret']
            )
        );
        $this->_api_context->setConfig($paypal_conf['settings']);

        $this->logger = new Logger();
    }

    public function cartList()
    {
        $data['pageTitle'] = 'Cart';

        // Start:: Check course, bundle, consultation exists or not. If not exists, Delete it.
        $carts = CartManagement::whereUserId(@Auth::id())->with('course.promotionCourse.promotion', 'bundle', 'consultationSlot.user.instructor')->get();

        if(get_option('subscription_mode')){
            $subscriptionPurchaseEnable = true;
        }
        else{
            $subscriptionPurchaseEnable = false;
        }

        foreach ($carts as $cart) {
            if ($cart->course_id && !$cart->course) {
                $cart->delete();
            } elseif ($cart->bundle_id && !$cart->bundle) {
                $cart->delete();
            } elseif ($cart->consultation_slot_id && !$cart->consultationSlot) {
                $cart->delete();
            } elseif ($cart->product_id && !$cart->product) {
                $cart->delete();
            }
            else{
                if(!$cart->is_subscription_enable){
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
                    if($consultationSlot->user->role == USER_ROLE_INSTRUCTOR){
                        $relation = 'instructor';
                    }elseif($consultationSlot->user->role == USER_ROLE_ORGANIZATION){
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
               
                if ($cart->product_id) {
                    $cart->main_price = $cart->product->current_price * $cart->quantity;
                    $cart->price = $cart->product->current_price * $cart->quantity;
                }


                $cart->coupon_id = null;
                $cart->discount = 0;
                $cart->save();
            }
        }

        if($subscriptionPurchaseEnable){
            $subscriptionPurchaseEnable = hasLimit(PACKAGE_RULE_COURSE, count($carts));
        }
        $data['subscriptionPurchaseEnable'] = $subscriptionPurchaseEnable;
        $data['carts'] = CartManagement::whereUserId(@Auth::id())->get();

        return view('frontend.student.cart.cart-list', $data);
    }

    public function applyCoupon(Request $request)
    {
        if (!Auth::check()) {
            $response['msg'] = __("You need to login first!");
            $response['status'] = 401;
            return response()->json($response);
        }

        if (!$request->coupon_code) {
            $response['msg'] = __("Enter coupon code!");
            $response['status'] = 404;
            return response()->json($response);
        }


        if ($request->id) {
            $cart = CartManagement::find($request->id);
            if (!$cart) {
                $response['msg'] = __("Cart item not found!");
                $response['status'] = 404;
                return response()->json($response);
            }

            $coupon = Coupon::where('coupon_code_name', $request->coupon_code)->where('start_date', '<=', Carbon::now()->format('Y-m-d'))->where('end_date', '>=', Carbon::now()->format('Y-m-d'))->first();

            if ($coupon) {
                if ($cart->price < $coupon->minimum_amount) {
                    $response['msg'] = "Minimum " . get_currency_code() . $coupon->minimum_amount . " need to buy for use this coupon!";
                    $response['status'] = 402;
                    return response()->json($response);
                }
            }
            if (!$coupon) {
                $response['msg'] = __("Invalid coupon code!");
                $response['status'] = 404;
                return response()->json($response);
            }


            if (CartManagement::whereUserId(@Auth::id())->whereCouponId($coupon->id)->count() > 0) {
                $response['msg'] = __("You've already used this coupon!");
                $response['status'] = 402;
                return response()->json($response);
            }

            $discount_price = ($cart->price * $coupon->percentage) / 100;

            if ($coupon->coupon_type == 1) {
                $cart->price = round($cart->price - $discount_price);
                $cart->discount = $discount_price;
                $cart->coupon_id = $coupon->id;
                $cart->save();

                $carts = CartManagement::whereUserId(@Auth::id())->get();
                $response['msg'] = __("Coupon Applied");
                $response['price'] = $cart->price;
                $response['discount'] = $cart->discount;
                $response['total'] = get_number_format($carts->sum('price'));
                $response['platform_charge'] = get_platform_charge($carts->sum('price'));
                $response['grand_total'] = get_number_format($carts->sum('price') + $carts->sum('shipping_charge') + get_platform_charge($carts->sum('price')));
                $response['status'] = 200;
                return response()->json($response);
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
                    $response['msg'] = __("Coupon Applied");
                    $response['price'] = $cart->price;
                    $response['discount'] = $cart->discount;
                    $response['total'] = get_number_format($carts->sum('price'));
                    $response['platform_charge'] = get_platform_charge($carts->sum('price'));
                    $response['grand_total'] = get_number_format($carts->sum('price') + $carts->sum('shipping_charge') + get_platform_charge($carts->sum('price')));
                    $response['status'] = 200;
                    return response()->json($response);
                } else {
                    $response['msg'] = __("Invalid coupon code!");
                    $response['status'] = 404;
                    return response()->json($response);
                }
            } elseif ($coupon->coupon_type == 3) {
                $couponCourse = CouponCourse::where('coupon_id', $coupon->id)->where('course_id', $cart->course_id)->orderBy('id', 'desc')->first();
                if ($couponCourse) {

                    $cart->price = round($cart->price - $discount_price);
                    $cart->discount = $discount_price;
                    $cart->coupon_id = $coupon->id;
                    $cart->save();

                    $carts = CartManagement::whereUserId(@Auth::id())->get();
                    $response['msg'] = __("Coupon Applied");
                    $response['price'] = $cart->price;
                    $response['discount'] = $cart->discount;
                    $response['total'] = get_number_format($carts->sum('price'));
                    $response['platform_charge'] = get_platform_charge($carts->sum('price'));
                    $response['grand_total'] = get_number_format($carts->sum('price') + $carts->sum('shipping_charge') + get_platform_charge($carts->sum('price')));
                    $response['status'] = 200;
                    return response()->json($response);
                } else {
                    $response['msg'] = __("Invalid coupon code!");
                    $response['status'] = 404;
                    return response()->json($response);
                }
            } else {
                $response['msg'] = __("Invalid coupon code!");
                $response['status'] = 404;
                return response()->json($response);
            }
        } else {
            $response['msg'] = __("Cart item not found!");
            $response['status'] = 404;
            return response()->json($response);
        }
    }

    public function addToCart(Request $request)
    {
        if (!Auth::check()) {
            $response['msg'] = __("You need to login first!");
            $response['status'] = 401;
            return response()->json($response);
        }

        if ($request->course_id) {
            if($request->is_gift){
                $rules = [
                    'receiver_info.receiver_name' => ['required', 'string','min:2', 'max:100'],
                    'receiver_info.receiver_email' => ['required', 'string', 'email', 'max:255'],
                ];

                $request->validate($rules,[
                    'receiver_info.receiver_name.required' => __('Name field is required'),
                    'receiver_info.receiver_name.min:2' => __('Name field should be at least 2'),
                    'receiver_info.receiver_name.max:100' => __('Name field should be max 100'),
                    'receiver_info.receiver_email.required' =>  __('Email field is required'),
                    'receiver_info.receiver_email.email' =>  __('Email must be valid'),
                ]);
            }
        }
        DB::beginTransaction();
        try {
            if ($request->course_id) {
                $enrollment = Enrollment::where(['course_id' => $request->course_id , 'user_id' => Auth::user()->id, 'status' => ACCESS_PERIOD_ACTIVE])->whereDate('end_date', '>=', now())->first();
                if($request->is_gift){
                    $giftUser = User::where('email', $request->receiver_info['receiver_email'])->first();
                    
                    if(!is_null($giftUser)){
                        $giftEnrollment = Enrollment::where(['course_id' => $request->course_id , 'user_id' => $giftUser->id, 'status' => ACCESS_PERIOD_ACTIVE])->whereDate('end_date', '>=', now())->first();
                        if(!is_null($giftEnrollment) && $giftEnrollment->user_id == $giftUser->id){
                            $response['msg'] = __("This user already have this course!");
                            $response['status'] = 402;
                            DB::rollBack();
                            return response()->json($response);
                        }
                    }
                    else{
                        $response['msg'] = __("This user doesn't exist. Please try another");
                        $response['status'] = 404;
                        DB::rollBack();
                        return response()->json($response);
                    }
                }
                
                if ($enrollment && !$request->is_gift) {
                    $order = Order::find($enrollment->order_id);
                    if ($order) {
                        if ($order->payment_status == 'due') {
                            Order_item::whereOrderId($enrollment->order_id)->get()->map(function ($q) {
                                $q->delete();
                            });
                            $order->delete();
                        } elseif ($order->payment_status == 'pending') {
                            $response['msg'] = __("You've already request the course & status is pending!");
                            $response['status'] = 402;
                            DB::rollBack();
                            return response()->json($response);
                        } elseif ($order->payment_status == 'paid' || $order->payment_status == 'free') {
                            $response['msg'] = __("You've already purchased the course!");
                            $response['status'] = 402;
                            DB::rollBack();
                            return response()->json($response);
                        }
                    } else {
                        $response['msg'] = __("Something is wrong! Try again.");
                        $response['status'] = 402;
                        DB::rollBack();
                        return response()->json($response);
                    }
                }

                $ownCourseCheck = CourseInstructor::where('course_id', $request->course_id)->where('instructor_id', auth()->id())->first();

                if ($ownCourseCheck && $request->is_gift) {
                    $courseExits = Course::find($request->course_id);
                    $order = new Order();
                    $order->user_id = Auth::user()->id;
                    $order->order_number = rand(100000, 999999);
                    $order->payment_status = 'free';
                    $order->save();

                    $order_item = new Order_item();
                    $order_item->order_id = $order->id;
                    $order_item->user_id = Auth::user()->id;
                    $order_item->receiver_info = $request->receiver_info;
                    $order_item->course_id = $courseExits->id;
                    $order_item->owner_user_id = $courseExits->user_id ?? null;
                    $order_item->unit_price = 0;
                    $order_item->admin_commission = 0;
                    $order_item->owner_balance = 0;
                    $order_item->sell_commission = 0;
                    $order_item->save();
                   
                    setEnrollment($order_item);

                    $response['msg'] = __("Your course has been gifted for free");
                    $response['status'] = 200;
                    DB::commit();
                    return response()->json($response);
                }
                elseif ($ownCourseCheck) {
                    $response['msg'] = __("This is your course. No need to add to cart.");
                    $response['status'] = 402;
                    DB::rollBack();
                    return response()->json($response);
                }

                $courseExits = Course::find($request->course_id);
                if (!$courseExits) {
                    $response['msg'] = __("Course not found!");
                    $response['status'] = 404;
                    DB::rollBack();
                    return response()->json($response);
                }
            }

            if ($request->product_id) {
                $productExits = Product::find($request->product_id);
                if (!$productExits) {
                    $response['msg'] = __("Product not found!");
                    $response['status'] = 404;
                    DB::rollBack();
                    return response()->json($response);
                }
            }

            if ($request->bundle_id) {
                $bundleExits = Bundle::find($request->bundle_id);
                if (!$bundleExits) {
                    $response['msg'] = __("Bundle not found!");
                    $response['status'] = 404;
                    DB::rollBack();
                    return response()->json($response);
                }

                $ownBundleCheck = Bundle::whereUserId(Auth::user()->id)->where('id', $request->bundle_id)->first();
                if ($ownBundleCheck) {
                    $response['msg'] = __("This is your bundle offer. No need to add to cart.");
                    $response['status'] = 402;
                    DB::rollBack();
                    return response()->json($response);
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
                            $response['msg'] = __("You've already request this bundle & status is pending!");
                            $response['status'] = 402;
                            DB::rollBack();
                            return response()->json($response);
                        } elseif ($order->payment_status == 'paid' || $order->payment_status == 'free') {
                            $response['msg'] = __("You've already purchased this bundle!");
                            $response['status'] = 402;
                            DB::rollBack();
                            return response()->json($response);
                        }
                    } else {
                        $response['msg'] = __("Something is wrong! Try again.");
                        $response['status'] = 402;
                        DB::rollBack();
                        return response()->json($response);
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

            if ($cartExists && is_null($request->product_id)) {
                $response['msg'] = __("Already added to cart!");
                $response['status'] = 409;
                DB::rollBack();
                return response()->json($response);
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

                    $response['msg'] = __("Free Course added to your my learning list!");
                    $response['status'] = 200;
                    DB::commit();
                    return response()->json($response);
                }
            }

            $quantity = $request->quantity;
            $cart = NULL;
            if($request->product_id){
                $cart = CartManagement::where('product_id', $request->product_id)->where('user_id', auth()->id())->first();
            }

            if(is_null($cart)){
                $cart = new CartManagement();
            }else{
                $quantity = ($request->quantity ?? 0) + $cart->quantity;
            }

            if ($request->product_id) {
                if($productExits && $productExits->quantity < 1){
                    CartManagement::where('product_id', $request->product_id)->delete();
                    $response['msg'] = __("Product is out of stock");
                    $response['status'] = 422;
                    return response()->json($response);
                }
                elseif($productExits && $productExits->quantity < $quantity){
                    $response['msg'] = __("Quantity is out of stock");
                    $response['status'] = 422;
                    return response()->json($response);
                }
            }

            $cart->user_id = Auth::user()->id;
            $cart->receiver_info = $request->receiver_info ?? [];
            $cart->course_id = $request->course_id;
            $cart->product_id = $request->product_id;
            $cart->quantity = $quantity ?? 0;
            $cart->bundle_id = $request->bundle_id;

            if($request->course_id){
                $course = Course::findOrFail($request->course_id);
                $cart->is_subscription_enable = $course->is_subscription_enable;
            }else if($request->bundle_id){
                $bundle = Bundle::findOrFail($request->bundle_id);
                $cart->is_subscription_enable = $bundle->is_subscription_enable;
            }

            if ($request->course_id) {
                $cart->main_price = $courseExits->price;
                $cart->price = $courseExits->price;
            }

            if ($request->bundle_id) {
                $bundleCourses = BundleCourse::where('bundle_id', $request->bundle_id)->pluck('course_id')->toArray();
                $cart->bundle_course_ids = $bundleCourses;
                $cart->main_price = $bundleExits->price;
                $cart->price = $bundleExits->price;
            }
            if ($request->product_id) {
                $cart->main_price = $productExits->current_price * $quantity;
                $cart->price = $productExits->current_price * $quantity;
                if($productExits->type == PHYSICAL_PRODUCT){
                    $cart->shipping_charge = $productExits->shipping_charge;
                }
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
            $response['msg'] = __("Added to cart");
            $response['msgInfoChange'] = __("Added to cart");
            $response['status'] = 200;
            //End:: Cart Management
            DB::commit();
            return response()->json($response);
        } catch (\Exception $e) {
            DB::rollBack();
            $response['msg'] = __("Something is wrong! Try again.");
            $response['status'] = 402;
            return response()->json($response);
        }
    }
    
    public function updateCartQuantity(Request $request)
    {
        if (!Auth::check()) {
            $response['msg'] = __("You need to login first!");
            $response['status'] = 401;
            return response()->json($response);
        }
        
        if($request->quantity < 2 && $request->type == 2){
            $response['msg'] = __("Minimum Quantity is 1");
            $response['status'] = 422;
            return response()->json($response);
        }

        $cart = CartManagement::find($request->id);
        
        
        if($request->type == 1){
            $product = $cart->product;
    
            if($product && $product->quantity < 1){
                $cart->delete();
                $response['msg'] = __("Product is out of stock");
                $response['status'] = 422;
                return response()->json($response);
            }
            elseif($product && $product->quantity < ($cart->quantity + 1)){
                $response['msg'] = __("Quantity is out of stock");
                $response['status'] = 422;
                return response()->json($response);
            }
            
            $cart->increment('quantity', 1);
        }else{
            $cart->decrement('quantity', 1);
        }

        $response['data'] = [
            'quantity' => $cart->quantity
        ];

        $response['msg'] = __("Cart Update Successfully");
        $response['status'] = 200;
        return response()->json($response);
    }
    
    public function courseGift($uuid)
    {
        if (!Auth::check()) {
            $response['msg'] = __("You need to login first!");
            $response['status'] = 401;
            return response()->json($response);
        }
        $data['pageTitle'] = __("Send this course to your friend");
        $data['course'] = Course::where('uuid', $uuid)->first();
        $data['countries'] = Country::all();

        return view('frontend.student.cart.course-gift-checkout', $data);
    }

    public function addToCartConsultation(Request $request)
    {

        if (!Auth::check()) {
            $response['msg'] = __("You need to login first!");
            $response['status'] = 401;
            return response()->json($response);
        }

        if ($request->consultation_slot_id) {
            DB::beginTransaction();
            try {
                $consultationExit = ConsultationSlot::whereId($request->consultation_slot_id)->whereUserId($request->booking_instructor_user_id)->first();

                if (!$consultationExit) {
                    $response['msg'] = __("Time slot not found!");
                    $response['status'] = 404;
                    DB::rollBack();
                    return response()->json($response);
                }

                $ownConsultationSlotCheck = ConsultationSlot::whereId($request->consultation_slot_id)->whereUserId(Auth::id())->first();
                if ($ownConsultationSlotCheck) {
                    $response['msg'] = __("This is your consultation slot. No need to add.");
                    $response['status'] = 402;
                    DB::rollBack();
                    return response()->json($response);
                }

                $consultationOrderBooked = Order_item::whereConsultationSlotId($request->consultation_slot_id)->where('consultation_date', $request->bookingDate)->first();
                if ($consultationOrderBooked) {
                    $order = Order::find($consultationOrderBooked->order_id);
                    if ($order) {
                        if ($order->payment_status == 'paid' || $order->payment_status == 'free') {
                            $response['msg'] = __("This slot already purchased. Please try another slot.");
                            $response['status'] = 402;
                            DB::rollBack();
                            return response()->json($response);
                        }
                    } else {
                        $response['msg'] = __("Something is wrong! Try again.");
                        $response['status'] = 402;
                        DB::rollBack();
                        return response()->json($response);
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
                            $response['msg'] = __("You've already request this slot & status is pending!");
                            $response['status'] = 402;
                            DB::rollBack();
                            return response()->json($response);
                        } elseif ($order->payment_status == 'paid' || $order->payment_status == 'free') {
                            $response['msg'] = __("You've already purchased this slot!");
                            $response['status'] = 402;
                            DB::rollBack();
                            return response()->json($response);
                        }
                    } else {
                        $response['msg'] = __("Something is wrong! Try again.");
                        $response['status'] = 402;
                        DB::rollBack();
                        return response()->json($response);
                    }
                }

                $consultationOrderExit = Order_item::whereConsultationSlotId($request->consultation_slot_id)->where('consultation_date', $request->bookingDate)->first();
                if ($consultationOrderExit) {
                    $order = Order::find($consultationOrderExit->order_id);
                    if ($order) {
                        if ($order->payment_status == 'pending') {
                            $response['msg'] = __("Another User already request this slot!");
                            $response['status'] = 402;
                            DB::rollBack();
                            return response()->json($response);
                        } elseif ($order->payment_status == 'paid' || $order->payment_status == 'free') {
                            $response['msg'] = __("Another User already purchased this slot!");
                            $response['status'] = 402;
                            DB::rollBack();
                            return response()->json($response);
                        }
                    } else {
                        $response['msg'] = __("Something is wrong! Try again.");
                        $response['status'] = 402;
                        DB::rollBack();
                        return response()->json($response);
                    }
                }


                $cartExists = CartManagement::whereUserId(Auth::user()->id)->whereConsultationSlotId($request->consultation_slot_id)->where('consultation_date', $request->bookingDate)->first();
                if ($cartExists) {
                    $response['msg'] = __("Already added to cart!");
                    $response['status'] = 409;
                    DB::rollBack();
                    return response()->json($response);
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
                if($consultationExit->user->role == USER_ROLE_INSTRUCTOR){
                    $relation = 'instructor';
                }elseif($consultationExit->user->role == USER_ROLE_ORGANIZATION){
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

                $response['status'] = 200;
                $response['msg'] = __("Consultation added to cart");
                $response['redirect_route'] = route('student.cartList');
                DB::commit();
                return response()->json($response);
            } catch (\Exception $e) {
                DB::rollBack();
                $response['msg'] = __("Something is wrong! Try again.");
                $response['status'] = 402;
                return response()->json($response);
            }
        } else {
            $response['msg'] = __("Time slot not found!");
            $response['status'] = 404;
            return response()->json($response);
        }
    }

    public function goToCheckout(Request $request)
    {
        if ($request->has('proceed_to_checkout')) {
            return redirect(route('student.checkout'));
        } elseif ($request->has('pay_from_lmszai_wallet')) {
            $carts = CartManagement::whereUserId(@Auth::id())->get();
            if (!count($carts)){
                $this->showToastrMessage('error', __('Your cart is empty!'));
                return redirect()->back();
            }
            if ($carts->sum('price') > int_to_decimal(Auth::user()->balance)) {
                $this->showToastrMessage('warning', __('Insufficient balance'));
                return redirect()->back();
            } else {
                DB::beginTransaction();
                try {
                    $order_data = $this->placeOrder('buy');
                    if($order_data['status']){
                        $order = $order_data['data'];
                    }else{
                        $this->showToastrMessage('error', __('Something went wrong!'));
                        return redirect()->back();
                    }
                    
                    $order->payment_status = 'paid';
                    $order->save();

                    CartManagement::whereUserId(@Auth::id())->delete();

                    distributeCommission($order);

                    /** ====== Send notification =========*/
                    $text = __("New student enrolled");
                    $target_url = route('instructor.all-student');

                    foreach ($order->items as $item) {
                        if ($item->course) {
                            $this->send($text, 2, $target_url, $item->course->user_id);
                        }
                    }

                    $text = __("Course has been sold");
                    $this->send($text, 1, null, null);

                    /** ====== Send notification =========*/

                    $withdrow = new Withdraw();
                    $withdrow->transection_id = Str::uuid()->getHex();
                    $withdrow->amount = $carts->sum('price');
                    $withdrow->payment_method = 'buy';
                    $withdrow->status = WITHDRAWAL_STATUS_COMPLETE;
                    $withdrow->save();
                    Auth::user()->decrement('balance', decimal_to_int($carts->sum('price')));
                    createTransaction(Auth::id(), $carts->sum('price'), TRANSACTION_BUY, 'Transaction for Purchase');
                    DB::commit();
                } catch (\Exception $e) {
                    DB::rollBack();
                    $this->showToastrMessage('warning', __('Something Went Wrong'));
                    return redirect()->back();
                }



                $this->showToastrMessage('success', __('Payment has been completed'));
                return redirect()->route('student.thank-you');
            }
        } elseif ($request->has('cancel_order')) {
            CartManagement::whereUserId(@Auth::id())->delete();
            $this->showToastrMessage('warning', __('Order has been cancel'));
            return redirect(url('/'));
        } elseif ($request->has('pay_from_subscription')) {
            $carts = CartManagement::whereUserId(@Auth::id())->get();
            if (!count($carts)) {
                $this->showToastrMessage('error', 'Your cart is empty!');
                return redirect()->back();
            }

            if(get_option('subscription_mode')){
                $subscriptionPurchaseEnable = true;
            }
            else{
                $subscriptionPurchaseEnable = false;
            }

            foreach ($carts as $cart) {
                if(!$cart->is_subscription_enable){
                    $subscriptionPurchaseEnable = false;
                }
            }

            if (!$subscriptionPurchaseEnable || !hasLimit(PACKAGE_RULE_COURSE, count($carts))) {
                $this->showToastrMessage('warning', 'Subscription Package Limit over');
                return redirect()->back();
            } else {
                DB::beginTransaction();
                try {
                    $order_data = $this->placeOrder('subscription');
                    if($order_data['status']){
                        $order = $order_data['data'];
                    }else{
                        $this->showToastrMessage('error', __('Something went wrong!'));
                        return redirect()->back();
                    }

                    $order->payment_status = 'paid';
                    $order->save();

                    CartManagement::whereUserId(@Auth::id())->delete();

                    $userPackage = UserPackage::join('packages', 'packages.id', '=', 'user_packages.package_id')->where('packages.package_type', PACKAGE_TYPE_SUBSCRIPTION)->where('user_packages.user_id', auth()->id())->where('user_packages.status', PACKAGE_STATUS_ACTIVE)->whereDate('enroll_date', '<=', now())->whereDate('expired_date', '>=', now())->select('user_packages.*')->with('enrollments')->first();

                    foreach ($order->items as $item) {
                        $enrollment = setEnrollment($item);
                        $enrollment->user_package_id = $userPackage->id;
                        $enrollment->save();
                    }

                    /** ====== Send notification =========*/
                    $text = __("New student enrolled");
                    $target_url = route('instructor.all-student');
                    foreach ($order->items as $item) {
                        if ($item->course) {
                            $this->send($text, 2, $target_url, $item->course->user_id);
                        }
                    }

                    $text = __("Course has been sold");
                    $this->send($text, 1, null, null);

                    /** ====== Send notification =========*/

                    DB::commit();
                } catch (\Exception $e) {
                    DB::rollBack();
                    $this->showToastrMessage('warning', 'Something Went Wrong');
                    return redirect()->back();
                }

                $this->showToastrMessage('success', 'Payment has been completed');
                return redirect()->route('student.thank-you');
            }
        } else {
            abort(404);
        }
    }

    public function cartDelete($id)
    {
        $cart = CartManagement::findOrFail($id);
        $cart->delete();
        $this->showToastrMessage('success', __('Removed from cart list!'));
        return redirect()->back();
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
        $data['pageTitle'] = "Checkout";
        $data['carts'] = CartManagement::whereUserId(@Auth::id())->get();
        $data['student'] = auth::user()->student;
        $data['countries'] = Country::orderBy('country_name', 'asc')->get();
        $data['banks'] = Bank::orderBy('name', 'asc')->where('status', 1)->get();
        if (old('country_id')) {
            $data['states'] = State::where('country_id', old('country_id'))->orderBy('name', 'asc')->get();
        }

        if (old('state_id')) {
            $data['cities'] = City::where('state_id', old('state_id'))->orderBy('name', 'asc')->get();
        }

        $razorpay_grand_total_with_conversion_rate = ($data['carts']->sum('price') + $data['carts']->sum('shipping_charge')  + get_platform_charge($data['carts']->sum('price'))) * (get_option('razorpay_conversion_rate') ? get_option('razorpay_conversion_rate') : 0);
        $data['razorpay_grand_total_with_conversion_rate'] = (float)preg_replace("/[^0-9.]+/", "", number_format($razorpay_grand_total_with_conversion_rate, 2));

        $paystack_grand_total_with_conversion_rate = ($data['carts']->sum('price') + $data['carts']->sum('shipping_charge')  + get_platform_charge($data['carts']->sum('price'))) * (get_option('paystack_conversion_rate') ? get_option('paystack_conversion_rate') : 0);
        $data['paystack_grand_total_with_conversion_rate'] = (float)preg_replace("/[^0-9.]+/", "", number_format($paystack_grand_total_with_conversion_rate, 2));

        $sslcommerz_grand_total_with_conversion_rate = ($data['carts']->sum('price') + $data['carts']->sum('shipping_charge')  + get_platform_charge($data['carts']->sum('price'))) * (get_option('sslcommerz_conversion_rate') ? get_option('sslcommerz_conversion_rate') : 0);
        $data['sslcommerz_grand_total_with_conversion_rate'] = (float)preg_replace("/[^0-9.]+/", "", number_format($sslcommerz_grand_total_with_conversion_rate, 2));

        return view('frontend.student.cart.checkout', $data);
    }

    public function razorpay_payment(Request $request)
    {
        $input = $request->all();
        $api = new Api(env('RAZORPAY_KEY'), env('RAZORPAY_SECRET'));

        if (empty(env('RAZORPAY_KEY')) && empty(env('RAZORPAY_SECRET'))) {
            $this->showToastrMessage('error', __('Razorpay payment gateway off!'));
            return redirect()->back();
        }
        DB::beginTransaction();
        try {
            $payment = $api->payment->fetch($input['razorpay_payment_id']);
            $this->logger->log('transaction razorpay ', json_encode($payment));

            if (count($input) && !empty($input['razorpay_payment_id'])) {
                try {
                    $response = $api->payment->fetch($input['razorpay_payment_id'])->capture(array('amount' => $payment['amount']));
                } catch (Exception $e) {
                    DB::rollBack();
                    Session::put('error', $e->getMessage());
                    return redirect()->back();
                }
            }

            $order_data = $this->placeOrder($request->payment_method);
            if($order_data['status']){
                $order = $order_data['data'];
            }else{
                $this->showToastrMessage('error', __('Something went wrong!'));
                return redirect()->back();
            }

            $order->payment_status = 'paid';
            $order->payment_method = 'razorpay';

            CartManagement::whereUserId(@Auth::id())->delete();

            distributeCommission($order);

            $payment_currency = get_option('razorpay_currency');
            $conversion_rate = get_option('razorpay_conversion_rate') ? get_option('razorpay_conversion_rate') : 0;

            $order->payment_currency = $payment_currency;
            $order->conversion_rate = $conversion_rate;
            $order->grand_total_with_conversation_rate = ($order->sub_total + $order->platform_charge) * $conversion_rate;
            $order->save();
            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();
            $this->logger->log('transaction failed razorpay', $exception->getMessage());
            $this->showToastrMessage('error', __('Something went wrong!'));
            return redirect()->back();
        }


        /** ====== Send notification =========*/
        $text = __("New student enrolled");
        $target_url = route('instructor.all-student');
        foreach ($order->items as $item) {
            if ($item->course) {
                $this->send($text, 2, $target_url, $item->course->user_id);
            }
        }

        $text = __("Course has been sold");
        $this->send($text, 1, null, null);

        /** ====== Send notification =========*/

        $this->showToastrMessage('success', __('Payment has been completed'));
        return redirect()->route('student.thank-you');
    }

    public function pay(Request $request)
    {
        if (is_null($request->payment_method)) {
            $this->showToastrMessage('warning', __('Please Select Payment Method'));
            return redirect()->back();
        }
        if ($request->payment_method == 'bank') {
            if (empty($request->deposit_by) || is_null($request->deposit_slip)) {
                $this->showToastrMessage('error', __('Bank Information Not Valid!'));
                return redirect()->back();
            }
        }

        if ($request->payment_method == 'paypal') {
            if (empty(env('PAYPAL_CLIENT_ID')) || empty(env('PAYPAL_SECRET')) || empty(env('PAYPAL_MODE'))) {
                $this->showToastrMessage('error', __('Paypal payment gateway is off!'));
                return redirect()->back();
            }
        }

        if ($request->payment_method == 'mollie') {
            if (empty(env('MOLLIE_KEY'))) {
                $this->showToastrMessage('error', __('Mollie payment gateway is off!'));
                return redirect()->back();
            }
        }

        if ($request->payment_method == 'instamojo') {
            if (empty(env('IM_API_KEY')) || empty(env('IM_AUTH_TOKEN')) || empty(env('IM_URL'))) {
                $this->showToastrMessage('error', __('Instamojo payment gateway is off!'));
                return redirect()->back();
            }
        }
        if ($request->payment_method == 'paystack') {
            if (empty(env('PAYSTACK_PUBLIC_KEY')) || empty(env('PAYSTACK_SECRET_KEY'))) {
                $this->showToastrMessage('error', __('Paystack payment gateway is off!'));
                return redirect()->back();
            }
        }
        if ($request->payment_method == 'coinbase') {
            if (empty(get_option('coinbase_key'))) {
                $this->showToastrMessage('error', __('Coinbase payment gateway is off!'));
                return redirect()->back();
            }
        }
        if ($request->payment_method == 'zitopay') {
            if (empty(get_option('zitopay_username'))) {
                $this->showToastrMessage('error', __('Zitopay payment gateway is off!'));
                return redirect()->back();
            }
        }
        if ($request->payment_method == 'iyzipay') {
            if (empty(get_option('iyzipay_key'))) {
                $this->showToastrMessage('error', __('Iyzipay payment gateway is off!'));
                return redirect()->back();
            }
        }
        if ($request->payment_method == 'bitpay') {
            if (empty(get_option('bitpay_key'))) {
                $this->showToastrMessage('error', __('Bitpay payment gateway is off!'));
                return redirect()->back();
            }
        }
        if ($request->payment_method == 'braintree') {
            if (empty(get_option('braintree_key'))) {
                $this->showToastrMessage('error', __('Braintree payment gateway is off!'));
                return redirect()->back();
            }
        }
        $order_data = $this->placeOrder($request->payment_method);
        if($order_data['status']){
            $order = $order_data['data'];
        }else{
            $this->showToastrMessage('error', __('Something went wrong!'));
            return redirect()->back();
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
                'id' => $order->uuid,
                'payment_method' => PAYPAL,
                'currency' => get_option('paypal_currency')
            ];
            $getWay = new BasePaymentService($object);
            $responseData = $getWay->makePayment($total);
            if($responseData['success']){
                $order->payment_id = $responseData['payment_id'];
                $order->save();
                return Redirect::away($responseData['redirect_url']);
            }else{
                $this->showToastrMessage('error', __('Something went wrong!'));
                return redirect()->back();
            }

        } else if ($request->payment_method == MOLLIE) {
            $object = [
                'id' => $order->uuid,
                'payment_method' => MOLLIE,
                'currency' => get_option('mollie_currency')
            ];
            $total = $order->grand_total * (get_option('mollie_conversion_rate') ? get_option('mollie_conversion_rate') : 0);
            $total = number_format($total, 2,'.','');
            $getWay = new BasePaymentService($object);
            $responseData = $getWay->makePayment($total);

            if($responseData['success']){
                $order->payment_id = $responseData['payment_id'];
                $order->save();
                return Redirect::away($responseData['redirect_url']);
            }else{
                $this->showToastrMessage('error', $responseData['message']);
                return redirect()->back();
            }
        } else if ($request->payment_method == MERCADOPAGO) {
            $object = [
                'id' => $order->uuid,
                'payment_method' => MERCADOPAGO,
                'currency' => get_option('mercado_currency')
            ];
            $total = $order->grand_total * (get_option('mercado_conversion_rate') ? get_option('mercado_conversion_rate') : 0);
            $total = number_format($total, 2,'.','');
            $getWay = new BasePaymentService($object);
            $responseData = $getWay->makePayment($total);

            if($responseData['success']){
                $order->payment_id = $responseData['payment_id'];
                $order->save();
                return Redirect::away($responseData['redirect_url']);
            }else{
                $this->showToastrMessage('error', $responseData['message']);
                return redirect()->back();
            }
        }  else if ($request->payment_method == FLUTTERWAVE) {
            $object = [
                'id' => $order->uuid,
                'payment_method' => FLUTTERWAVE,
                'currency' => get_option('flutterwave_currency')
            ];
            $total = $order->grand_total * (get_option('flutterwave_conversion_rate') ? get_option('flutterwave_conversion_rate') : 0);
            $total = number_format($total, 2,'.','');
            $getWay = new BasePaymentService($object);
            $responseData = $getWay->makePayment($total);

            if($responseData['success']){
                $order->payment_id = $responseData['payment_id'];
                $order->save();
                return Redirect::away($responseData['redirect_url']);
            }else{
                $this->showToastrMessage('error', $responseData['message']);
                return redirect()->back();
            }
        } else if ($request->payment_method == INSTAMOJO) {
            $total = $order->grand_total * (get_option('im_conversion_rate') ? get_option('im_conversion_rate') : 0);
            $total = number_format($total, 2,'.','');
            $object = [
                'id' => $order->uuid,
                'payment_method' => INSTAMOJO,
                'currency' => get_option('im_currency')
            ];
            $getWay = new BasePaymentService($object);
            $responseData = $getWay->makePayment($total);

            if($responseData['success']){
                $order->payment_id = $responseData['payment_id'];
                $order->save();
                return Redirect::away($responseData['redirect_url']);
            }else{
                $this->showToastrMessage('error', __('Something went wrong!'));
                return redirect()->back();
            }

        } else if ($request->payment_method == PAYSTAC) {
            $total = $order->grand_total * (get_option('paystack_conversion_rate') ? get_option('paystack_conversion_rate') : 0);
            $total = number_format($total, 2,'.','');
            $object = [
                'id' => $order->uuid,
                'payment_method' => PAYSTAC,
                'currency' => get_option('paystack_currency'),
                'reference' => $request->reference
            ];
            $getWay = new BasePaymentService($object);
            $responseData = $getWay->makePayment($total);

            if($responseData['success']){
                $order->payment_id = $responseData['payment_id'];
                $order->save();
                return Redirect::away($responseData['redirect_url']);
            }else{
                $this->showToastrMessage('error', __('Something went wrong!'));
                return redirect()->back();
            }

        } else if ($request->payment_method == COINBASE) {
            $total = $order->grand_total * (get_option('coinbase_conversion_rate') ? get_option('coinbase_conversion_rate') : 0);
            $total = number_format($total, 2,'.','');
            $object = [
                'id' => $order->uuid,
                'payment_method' => COINBASE,
                'currency' => get_option('coinbase_currency'),
                'reference' => $request->reference
            ];
            $getWay = new BasePaymentService($object);
            $responseData = $getWay->makePayment($total);

            if($responseData['success']){
                $order->payment_id = $responseData['payment_id'];
                $order->save();
                return Redirect::away($responseData['redirect_url']);
            }else{
                $this->showToastrMessage('error', __('Something went wrong!'));
                return redirect()->back();
            }
        } else if ($request->payment_method == ZITOPAY) {
            $total = $order->grand_total * (get_option('zitopay_conversion_rate') ? get_option('zitopay_conversion_rate') : 0);
            $total = number_format($total, 2,'.','');
            $object = [
                'id' => $order->uuid,
                'payment_method' => ZITOPAY,
                'currency' => get_option('zitopay_currency'),
                'reference' => $request->reference
            ];
            $getWay = new BasePaymentService($object);
            $responseData = $getWay->makePayment($total);

            if($responseData['success']){
                $order->payment_id = $responseData['payment_id'];
                $order->save();
                return Redirect::away($responseData['redirect_url']);
            }else{
                $this->showToastrMessage('error', __('Something went wrong!'));
                return redirect()->back();
            }
        } else if ($request->payment_method == IYZIPAY) {
            $total = $order->grand_total * (get_option('iyzipay_conversion_rate') ? get_option('iyzipay_conversion_rate') : 0);
            $total = number_format($total, 2,'.','');
            $object = [
                'id' => $order->uuid,
                'payment_method' => IYZIPAY,
                'currency' => get_option('iyzipay_currency'),
                'reference' => $request->reference
            ];
            $getWay = new BasePaymentService($object);
            $responseData = $getWay->makePayment($total);

            if($responseData['success']){
                $order->payment_id = $responseData['payment_id'];
                $order->save();
                return Redirect::away($responseData['redirect_url']);
            }else{
                $this->showToastrMessage('error', __('Something went wrong!'));
                return redirect()->back();
            }
        } else if ($request->payment_method == BITPAY) {
            $total = $order->grand_total * (get_option('bitpay_conversion_rate') ? get_option('bitpay_conversion_rate') : 0);
            $total = number_format($total, 2,'.','');
            $object = [
                'id' => $order->uuid,
                'payment_method' => BITPAY,
                'currency' => get_option('bitpay_currency'),
                'reference' => $request->reference
            ];
            $getWay = new BasePaymentService($object);
            $responseData = $getWay->makePayment($total);

            if($responseData['success']){
                $order->payment_id = $responseData['payment_id'];
                $order->save();
                return Redirect::away($responseData['redirect_url']);
            }else{
                $this->showToastrMessage('error', __('Something went wrong!'));
                return redirect()->back();
            }
        } else if ($request->payment_method == BRAINTREE) {
            $total = $order->grand_total * (get_option('braintree_conversion_rate') ? get_option('braintree_conversion_rate') : 0);
            $total = number_format($total, 2,'.','');
            $object = [
                'id' => $order->uuid,
                'payment_method' => BRAINTREE,
                'currency' => get_option('braintree_currency'),
                'reference' => $request->reference
            ];
            $getWay = new BasePaymentService($object);
            $responseData = $getWay->makePayment($total);

            if($responseData['success']){
                $order->payment_id = $responseData['payment_id'];
                $order->save();
                return Redirect::away($responseData['redirect_url']);
            }else{
                $this->showToastrMessage('error', __('Something went wrong!'));
                return redirect()->back();
            }

        } else if ($request->payment_method == BANK) {
            $deposit_by = $request->deposit_by;
            $deposit_slip = $this->uploadFileWithDetails('bank', $request->deposit_slip);
            if (!$deposit_slip['is_uploaded']) {
                $this->showToastrMessage('error', __('Something went wrong! Failed to upload file'));
                return redirect()->back();
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
            $this->showToastrMessage('success', __('Request has been Placed! Please Wait for Approve'));
            return redirect()->route('student.thank-you');
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

            $getWay = new BasePaymentService($object);
            $responseData = $getWay->makePayment($total,$post_data);
            if($responseData['success']){
                $order->payment_id = $responseData['payment_id'];
                $order->save();
                return Redirect::away($responseData['redirect_url']);
            }else{
                $this->showToastrMessage('error', __('Something went wrong!'));
                return redirect()->back();
            }
        } else if ($request->payment_method == STRIPE)  {

            $total = $order->grand_total * (get_option('stripe_conversion_rate') ? get_option('stripe_conversion_rate') : 0);
            $total = number_format($total, 2,'.','');

            $object = [
                'id' => $order->uuid,
                'payment_method' => STRIPE,
                'currency' => get_option('stripe_currency'),
                'token' => $request->stripeToken
            ];
            $getWay = new BasePaymentService($object);
            $responseData = $getWay->makePayment($total);

            if($responseData['success']){
                if($responseData['data']['payment_status'] == 'success') {
                    $order->payment_id = $responseData['payment_id'];
                    $order->payment_status = 'paid';
                    $order->save();

                    CartManagement::whereUserId(@Auth::id())->delete();

                    distributeCommission($order);
                    /** ====== Send notification =========*/
                    $text = __("New student enrolled");
                    $target_url = route('instructor.all-student');
                    foreach ($order->items as $item) {
                        if ($item->course) {
                            $this->send($text, 2, $target_url, $item->course->user_id);
                        }
                    }
                    $text = __("Course has been sold");
                    $this->send($text, 1, null, null);
                    /** ====== Send notification =========*/
                    $this->showToastrMessage('success', __('Payment has been completed'));
                    return redirect()->route('student.thank-you');
                }
            }
            $this->showToastrMessage('error', __('Something went wrong!'));
            return redirect()->back();
        }
    }

    private function placeOrder($payment_method)
    {
        DB::beginTransaction();
        try{
            $carts = CartManagement::whereUserId(@Auth::id())->get();
            $order = new Order();
            $order->user_id = Auth::user()->id;
            $order->order_number = rand(100000, 999999);
            $order->sub_total = $carts->sum('price');
            $order->discount = $carts->sum('discount');
            $order->platform_charge = get_platform_charge($carts->sum('price'));
            $order->shipping_cost = $carts->sum('shipping_charge');
            $order->current_currency = get_currency_code();
            $order->grand_total = $order->sub_total + $order->shipping_cost + $order->platform_charge;
            $order->payment_method = $payment_method;

            $payment_currency = '';
            $conversion_rate = '';

            if ($payment_method == 'paypal') {
                $payment_currency = get_option('paypal_currency');
                $conversion_rate = get_option('paypal_conversion_rate') ? get_option('paypal_conversion_rate') : 0;
            } elseif ($payment_method == 'stripe') {
                $payment_currency = get_option('stripe_currency');
                $conversion_rate = get_option('stripe_conversion_rate') ? get_option('stripe_conversion_rate') : 0;
            } elseif ($payment_method == 'bank') {
                $payment_currency = get_option('bank_currency');
                $conversion_rate = get_option('bank_conversion_rate') ? get_option('bank_conversion_rate') : 0;
            } elseif ($payment_method == 'mollie') {
                $payment_currency = get_option('mollie_currency');
                $conversion_rate = get_option('mollie_conversion_rate') ? get_option('mollie_conversion_rate') : 0;
            } elseif ($payment_method == 'instamojo') {
                $payment_currency = get_option('im_currency');
                $conversion_rate = get_option('im_conversion_rate') ? get_option('im_conversion_rate') : 0;
            } elseif ($payment_method == 'paystack') {
                $payment_currency = get_option('paystack_currency');
                $conversion_rate = get_option('paystack_conversion_rate') ? get_option('paystack_conversion_rate') : 0;
            } elseif ($payment_method == 'sslcommerz') {
                $payment_currency = get_option('sslcommerz_currency');
                $conversion_rate = get_option('sslcommerz_conversion_rate') ? get_option('sslcommerz_conversion_rate') : 0;
            } elseif ($payment_method == 'mercadopago') {
                $payment_currency = get_option('mercado_currency');
                $conversion_rate = get_option('mercado_conversion_rate') ? get_option('mercado_conversion_rate') : 0;
            } elseif ($payment_method == 'flutterwave') {
                $payment_currency = get_option('flutterwave_currency');
                $conversion_rate = get_option('flutterwave_conversion_rate') ? get_option('flutterwave_conversion_rate') : 0;
            } elseif ($payment_method == 'coinbase') {
                $payment_currency = get_option('coinbase_currency');
                $conversion_rate = get_option('coinbase_conversion_rate') ? get_option('coinbase_conversion_rate') : 0;
            } elseif ($payment_method == 'zitopay') {
                $payment_currency = get_option('zitopay_currency');
                $conversion_rate = get_option('zitopay_conversion_rate') ? get_option('zitopay_conversion_rate') : 0;
            } elseif ($payment_method == IYZIPAY) {
                $payment_currency = get_option('iyzipay_currency');
                $conversion_rate = get_option('iyzipay_conversion_rate') ? get_option('iyzipay_conversion_rate') : 0;
            } elseif ($payment_method == BITPAY) {
                $payment_currency = get_option('bitpay_currency');
                $conversion_rate = get_option('bitpay_conversion_rate') ? get_option('bitpay_conversion_rate') : 0;
            } elseif ($payment_method == BRAINTREE) {
                $payment_currency = get_option('braintree_currency');
                $conversion_rate = get_option('braintree_conversion_rate') ? get_option('braintree_conversion_rate') : 0;
            }

            $order->payment_currency = $payment_currency;
            $order->conversion_rate = $conversion_rate;
            if ($conversion_rate) {
                $order->grand_total_with_conversation_rate = ($order->sub_total + $order->platform_charge + $order->shipping_cost) * $conversion_rate;
            }

            $order->save();

            foreach ($carts as $cart) {
                if ($cart->course_id) {
                    $order_item = new Order_item();
                    $order_item->order_id = $order->id;
                    $order_item->user_id = Auth::id();
                    $order_item->product_id = $cart->product_id;
                    $order_item->course_id = $cart->course_id;
                    $order_item->receiver_info = $cart->receiver_info;
                    $order_item->owner_user_id = $cart->course ? $cart->course->user_id : null;
                    $order_item->unit_price = $cart->price;
                    $userPackage =  UserPackage::join('packages', 'packages.id', '=', 'user_packages.package_id')->whereIn('packages.package_type', [PACKAGE_TYPE_SAAS_INSTRUCTOR, PACKAGE_TYPE_SAAS_ORGANIZATION])->where('user_packages.user_id', $order_item->owner_user_id)->where('user_packages.status', PACKAGE_STATUS_ACTIVE)->whereDate('enroll_date', '<=', now())->whereDate('expired_date', '>=', now())->select('user_packages.*')->first();
                    $adminCommission = ($userPackage && $userPackage->admin_commission && get_option('saas_mode')) ? $userPackage->admin_commission : get_option('sell_commission');
                    if ($adminCommission) {
                        $order_item->admin_commission = admin_commission_by_percentage($cart->price, $adminCommission);
                        $order_item->owner_balance = $cart->price - admin_commission_by_percentage($cart->price, $adminCommission);
                        $order_item->sell_commission = $adminCommission;
                    } else {
                        $order_item->owner_balance = $cart->price;
                    }

                    $order_item->save();
                    $this->addAffiliateHistory($cart,$order,$order_item);

                } elseif ($cart->bundle_id) {
                    // $bundleIds = Enrollment::where('user_id', auth()->id())->whereNotIn('course_id', $cart->bundle_course_ids)->whereDate('end_date', '<', now())->select('course_id')->get()->toArray();
                    $courses = Course::whereIn('id', $cart->bundle_course_ids)->get();
                    $bundleUserId = $cart->bundle->user_id;
                    $userPackage =  UserPackage::join('packages', 'packages.id', '=', 'user_packages.package_id')->whereIn('packages.package_type', [PACKAGE_TYPE_SAAS_INSTRUCTOR, PACKAGE_TYPE_SAAS_ORGANIZATION])->where('user_packages.user_id', $bundleUserId)->where('user_packages.status', PACKAGE_STATUS_ACTIVE)->whereDate('enroll_date', '<=', now())->whereDate('expired_date', '>=', now())->select('user_packages.*')->first();
                    $adminCommission = ($userPackage && $userPackage->admin_commission && get_option('saas_mode')) ? $userPackage->admin_commission : get_option('sell_commission');
                    if($adminCommission){
                        $adminCommissionAmount = admin_commission_by_percentage($cart->price, $adminCommission);
                        $totalDistributeAmount = $cart->price - admin_commission_by_percentage($cart->price, $adminCommission);
                    }
                    else{
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
                        $this->addAffiliateHistory($cart,$order,$order_item);
                    }

                } elseif ($cart->consultation_slot_id) {
                    $order_item = new Order_item();
                    $order_item->order_id = $order->id;
                    $order_item->user_id = Auth::id();
                    $order_item->owner_user_id = is_array($cart['consultation_details']) ? $cart['consultation_details'][0]->instructor_user_id : null;
                    $order_item->consultation_slot_id = $cart->consultation_slot_id;
                    $order_item->consultation_date = $cart->consultation_date;
                    $order_item->unit_price = $cart->price;

                    $userPackage =  UserPackage::join('packages', 'packages.id', '=', 'user_packages.package_id')->whereIn('packages.package_type', [PACKAGE_TYPE_SAAS_INSTRUCTOR, PACKAGE_TYPE_SAAS_ORGANIZATION])->where('user_packages.user_id', $order_item->owner_user_id)->where('user_packages.status', PACKAGE_STATUS_ACTIVE)->whereDate('enroll_date', '<=', now())->whereDate('expired_date', '>=', now())->select('user_packages.*')->first();
                    $adminCommission = ($userPackage && $userPackage->admin_commission && get_option('saas_mode')) ? $userPackage->admin_commission : get_option('sell_commission');
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
                } elseif ($cart->product_id) {
                    $order_item = new Order_item();
                    $order_item->order_id = $order->id;
                    $order_item->user_id = Auth::id();
                    $order_item->product_id = $cart->product_id;
                    $order_item->receiver_info = $cart->receiver_info;
                    $order_item->owner_user_id = $cart->product ? $cart->product->user_id : null;
                    $order_item->unit_price = $cart->price;
                    $order_item->unit = $cart->quantity;
                    $adminCommission = get_option('sell_commission');
                    if ($adminCommission) {
                        $order_item->admin_commission = admin_commission_by_percentage($cart->price, $adminCommission);
                        $order_item->owner_balance = $cart->price - admin_commission_by_percentage($cart->price, $adminCommission);
                        $order_item->sell_commission = $adminCommission;
                    } else {
                        $order_item->owner_balance = $cart->price;
                    }

                    $order_item->save();

                }

            }

            DB::commit();
            return ['status' => true,'data' => $order];
        }catch (\Exception $e){
            DB::rollBack();
            $this->logger->log('Cannot Create Order', $e->getMessage());
            return ['status' => false,'data' => null];
        }

    }

    private function addAffiliateHistory($cart, $order, $order_item){
        if(get_option('referral_status')){
            $refRequest = AffiliateRequest::where(['affiliate_code' => $cart->reference,'status' => STATUS_APPROVED])->first();
            if(!is_null($refRequest)) {
                $refUser = User::where(['id' => $refRequest->user_id])->first();
                $alreadyAffiliated = AffiliateHistory::where(['user_id'=>$refUser->id,'course_id' =>$cart->course_id])->first();
                if($refUser->is_affiliator == AFFILIATOR && is_null($alreadyAffiliated)) {
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
