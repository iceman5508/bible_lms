<?php

use App\Http\Controllers\Frontend\AgoraController;
use App\Http\Controllers\Student\SubscriptionController;
use App\Http\Controllers\Frontend\SupportTicketController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Student\CartManagementController;
use App\Http\Controllers\Student\ChatController;
use App\Http\Controllers\Student\DashboardController;
use App\Http\Controllers\Student\MyCourseController;
use App\Http\Controllers\Student\WishlistController;
use Illuminate\Support\Facades\Route;

Route::group(['as' => 'student.'], function () {

    //Start:: My Learning Course
    Route::get('my-learning', [MyCourseController::class, 'myLearningCourseList'])->name('my-learning');
    Route::get('organization-course', [MyCourseController::class, 'organizationCourses'])->name('organization_course');
    Route::get('my-consultation', [MyCourseController::class, 'myConsultationList'])->name('my-consultation');
    Route::get('download-invoice/{item_id}', [MyCourseController::class, 'downloadInvoice'])->name('download-invoice');
    Route::post('my-course-complete-duration/{course_uuid}', [MyCourseController::class, 'myCourseCompleteDuration'])->name('my-course.completed_duration');
    Route::get('my-course/{slug}/{action_type?}/{quiz_uuid?}/{answer_id?}', [MyCourseController::class, 'myCourseShow'])->middleware('course.access')->name('my-course.show');

    Route::get('my-learning-video-completed', [MyCourseController::class, 'videoCompleted'])->name('video.completed');
    Route::post('save-certificate', [MyCourseController::class, 'saveCertificate'])->name('save-certificate');

    Route::post('save-exam-answer/{course_uuid}/{question_uuid}/{take_exam_id}', [MyCourseController::class, 'saveExamAnswer'])->name('save-exam-answer');
    Route::post('review-create', [MyCourseController::class, 'reviewCreate'])->name('review.create');
    Route::post('review-paginate/{courseId}', [MyCourseController::class, 'reviewPaginate'])->name('reviewPaginate');
    Route::post('discussion-create', [MyCourseController::class, 'discussionCreate'])->name('discussion.create');
    Route::post('discussion-reply/{discussionId}', [MyCourseController::class, 'discussionReply'])->name('discussion.reply');

    //Star:: Course Assignment
    Route::get('assignment-list', [MyCourseController::class, 'assignmentList'])->name('assignment-list');
    Route::get('assignment-details', [MyCourseController::class, 'assignmentDetails'])->name('assignment-details');
    Route::get('assignment-result', [MyCourseController::class, 'assignmentResult'])->name('assignment-result');
    Route::get('assignment-submit', [MyCourseController::class, 'assignmentSubmit'])->name('assignment-submit');
    Route::post('assignment-submit/{course_id}/{assignment_id}', [MyCourseController::class, 'assignmentSubmitStore'])->name('assignment-submit.store');
    //End:: Course Assignment

    //Start:: Student Profile & Change Password
    Route::get('dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');
    Route::get('profile', [DashboardController::class, 'profile'])->name('profile');

    Route::get('address', [DashboardController::class, 'address'])->name('address');
    Route::post('address-update/{uuid}', [DashboardController::class, 'address_update'])->name('address.update');

    Route::get('become-an-instructor', [DashboardController::class, 'becomeAnInstructor'])->name('become-an-instructor');
    Route::post('save-instructor-info', [DashboardController::class, 'saveInstructorInfo'])->name('save-instructor-info');
    Route::post('save-profile/{uuid}', [DashboardController::class, 'saveProfile'])->name('save-profile');
    Route::get('get-state-by-country/{country_id}', [DashboardController::class, 'getStateByCountry']);
    Route::get('get-city-by-state/{state_id}', [DashboardController::class, 'getCityByState']);

    Route::get('change-password', [DashboardController::class, 'changePassword'])->name('change-password');
    Route::post('change-password', [DashboardController::class, 'changePasswordUpdate'])->name('changePasswordUpdate');
    //End:: Student profile & Change Password


    // student chat start
    Route::get('chat', [ChatController::class, 'index'])->name('chat.index');
    Route::get('chat-messages', [ChatController::class, 'getChatMessages'])->name('chat.messages');
    Route::post('chat-send', [ChatController::class, 'sendChatMessage'])->name('chat.send');
    // student chat end

    //Start:: Cart & Wishlist
    Route::get('cart-list', [CartManagementController::class, 'cartList'])->name('cartList');
    Route::post('apply-coupon', [CartManagementController::class, 'applyCoupon'])->name('applyCoupon');
    Route::post('add-to-cart', [CartManagementController::class, 'addToCart'])->name('addToCart');
    Route::post('update-cart', [CartManagementController::class, 'updateCartQuantity'])->name('updateCartQuantity');
    Route::post('go-to-checkout', [CartManagementController::class, 'goToCheckout'])->name('goToCheckout');
    Route::delete('cart-delete/{id}', [CartManagementController::class, 'cartDelete'])->name('cartDelete');

    Route::post('add-to-cart-consultation', [CartManagementController::class, 'addToCartConsultation'])->name('addToCartConsultation');
    Route::get('course-gift-checkout/{course_uuid}', [CartManagementController::class, 'courseGift'])->name('course-gift');


    Route::get('checkout', [CartManagementController::class, 'checkout'])->name('checkout');
    Route::post('razorpay_payment', [CartManagementController::class, 'razorpay_payment'])->name('razorpay_payment');
    Route::post('pay', [CartManagementController::class, 'pay'])->name('pay');

    Route::get('fetch-bank', [CartManagementController::class, 'fetchBank'])->name('fetchBank');

    // Paystack
    Route::post('paystack/payment', [CartManagementController::class, 'paystackPayment'])->name('paystack_payment');
    Route::get('/payment/callback', [CartManagementController::class, 'handlePaystackGatewayCallback'])->name('paystack_payment.callback');

    Route::get('wishlist', [WishlistController::class, 'wishlist'])->name('wishlist');
    Route::post('add-to-wishlist', [WishlistController::class, 'addToWishlist'])->name('addToWishlist');
    Route::delete('wishlist-delete/{id}', [WishlistController::class, 'wishlistDelete'])->name('wishlistDelete');
    //End:: Cart & Wishlist

    //subscription checkout
    Route::match(array('GET', 'POST'), 'subscription/checkout/{subscription:uuid}', [SubscriptionController::class, 'checkout'])->name('subscription.checkout');
    Route::post('subscription/pay', [SubscriptionController::class, 'pay'])->name('subscription.pay');
    Route::post('subscription/razor-pay-payment', [SubscriptionController::class, 'razorPayPayment'])->name('subscription_razor_pay_payment');
    // Route::get('subscription-list', [SubscriptionController::class, 'subscriptionList'])->name('subscription_panel');
    Route::get('subscription-plan', [SubscriptionController::class, 'subscriptionPlan'])->name('subscription_plan');
    Route::get('subscription-plan-details/{id}', [SubscriptionController::class, 'subscriptionPlanDetails'])->name('subscription_plan_details');

    Route::group(['prefix' => 'support-tickets', 'as' => 'support-ticket.'], function () {
        Route::get('create-tickets', [SupportTicketController::class, 'create'])->name('create');
        Route::post('store-tickets', [SupportTicketController::class, 'store'])->name('store');
        Route::get('show-details/{uuid}', [SupportTicketController::class, 'show'])->name('show');
        Route::post('ticket-message-store', [SupportTicketController::class, 'messageStore'])->name('message.store');
        Route::get('get-tickets/fetch-data', [SupportTicketController::class, 'paginationFetchData'])->name('fetch-data');
    });

    Route::get('thank-you', [MyCourseController::class, 'thankYou'])->name('thank-you');

    Route::get('login-devices', [HomeController::class, 'allLoginDevice'])->withoutMiddleware('device.control')->name('all_login_device');
    Route::post('logout-devices/{device_id?}', [HomeController::class, 'logoutDevice'])->withoutMiddleware('device.control')->name('logout_device');
    Route::get('agora-open-class/{uuid}/{type}', [AgoraController::class, 'openLiveClass'])->name('agora-open-class');

    Route::post('refund-request', [MyCourseController::class, 'refundRequest'])->name('refund.request');
});
