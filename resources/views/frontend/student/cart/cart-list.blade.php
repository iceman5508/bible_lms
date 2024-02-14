@extends('frontend.layouts.app')

@if(isAddonInstalled('LMSZAIPRODUCT'))
@push('style')
<link rel="stylesheet" href="{{ asset('addon/product/css/ecommerce-product.css') }}">
@endpush
@endif

@section('content')
<div class="bg-page">
    <!-- Page Header Start -->
    <header class="page-banner-header blank-page-banner-header gradient-bg position-relative">
        <div class="section-overlay">
            <div class="blank-page-banner-wrap">
                <div class="container">
                    <div class="row">
                        <div class="col-12 col-sm-12 col-md-12">
                            <div class="page-banner-content text-center">
                                <h3 class="page-banner-heading color-heading pb-15">{{ __('Cart') }}</h3>

                                <!-- Breadcrumb Start-->
                                <nav aria-label="breadcrumb">
                                    <ol class="breadcrumb justify-content-center">
                                        <li class="breadcrumb-item font-14"><a href="{{ url('/') }}">{{__('Home')}}</a>
                                        </li>
                                        <li class="breadcrumb-item font-14 active" aria-current="page">{{ __('Cart') }}
                                        </li>
                                    </ol>
                                </nav>
                                <!-- Breadcrumb End-->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>
    <!-- Page Header End -->

    <!-- Cart Page Area Start -->
    <section class="wishlist-page-area cart-page-area">
        <div class="container">
            <div class="row">

                <div class="col-lg-9">
                    <div class="cart-page-left-part bg-white">
                        <div class="cart-page-title d-flex justify-content-between align-items-center mb-3">
                            <h3 class="font-18">{{ @$carts->count() }} {{__('Items In Card')}}</h3>
                            <a href="{{ route('courses') }}"
                                class="text-decoration-underline font-14 font-medium">{{__('Continue Buying')}}</a>
                        </div>
                        <div class="table-responsive">
                            <table class="table bg-white wishlist-table">
                                <thead>
                                    <tr>
                                        <th scope="col" class="color-gray font-15 font-medium">{{__('Course')}}</th>
                                        <th scope="col" class="color-gray font-15 font-medium">{{ __('Type') }}</th>
                                        <th scope="col" class="color-gray font-15 font-medium">{{__('Price')}}</th>
                                        <th scope="col" class="color-gray font-15 font-medium">{{__('Remove')}}</th>
                                    </tr>
                                </thead>
                                <tbody class="theme-border">
                                    @forelse($carts as $cart)
                                        @if ($cart->consultation_slot_id)
                                            @php
                                                $relation = getUserRoleRelation($cart->consultationSlot->user)
                                            @endphp
                                        @endif
                                    <tr class="removable-item">
                                        <td class="wishlist-course-item">
                                            <div
                                                class="card course-item wishlist-item border-0 d-flex align-items-center">
                                                <div class="course-img-wrap flex-shrink-0 overflow-hidden">
                                                    <?php
                                                            $special = @$cart->course->specialPromotionTagCourse->specialPromotionTag->name;
                                                            ?>
                                                    @if(@$special)
                                                    <span
                                                        class="course-tag badge radius-3 font-12 font-medium position-absolute bg-orange">
                                                        {{ @$special }}
                                                    </span>
                                                    @endif
                                                    @if($cart->course_id)
                                                    <a href="{{ route('course-details', @$cart->course->slug) }}">
                                                        <img src="{{ getImageFile(@$cart->course->image_path) }}"
                                                            alt="course" class="img-fluid"></a>
                                                    @elseif($cart->bundle_id)
                                                    <a
                                                        href="{{ route('bundle-details', [@$cart->bundle->slug]) }}">
                                                        <img src="{{ getImageFile(@$cart->bundle->image) }}"
                                                            alt="bundle course" class="img-fluid"></a>
                                                    @elseif($cart->consultation_slot_id)
                                                    <a
                                                        href="{{ route('userProfile', @$cart->consultationSlot->user_id) }}">
                                                        <img src="{{ getImageFile(@$cart->consultationSlot->user->image) }}"
                                                            alt="Consultator" class="img-fluid"></a>
                                                    @elseif($cart->product_id)
                                                    <a
                                                        href="{{ route('lms_product.frontend.view', @$cart->product->slug) }}">
                                                        <img src="{{ getImageFile(@$cart->product->thumbnail_path) }}"
                                                            alt="Product" class="img-fluid"></a>
                                                    @endif
                                                </div>
                                                <div class="card-body flex-grow-1">
                                                    <h5 class="card-title course-title">
                                                        @if($cart->course_id)
                                                        <a href="{{ route('course-details', @$cart->course->slug) }}">{{
                                                            @$cart->course->title }}</a>
                                                        @elseif($cart->bundle_id)
                                                        <a
                                                            href="{{ route('bundle-details', [@$cart->bundle->slug]) }}">{{
                                                            @$cart->bundle->name }}</a>
                                                        @elseif($cart->consultation_slot_id)
                                                        <a
                                                            href="{{ route('userProfile', @$cart->consultationSlot->user_id) }}">
                                                            {{ @$cart->consultationSlot->user->$relation->full_name }}
                                                        </a>
                                                        @elseif($cart->product_id)
                                                        <a
                                                            href="{{ route('lms_product.frontend.view', @$cart->product->slug) }}">
                                                            {{ @$cart->product->title }}
                                                        </a>
                                                        @endif
                                                    </h5>
                                                    <p class="card-text instructor-name-certificate font-medium">
                                                        @if($cart->course_id)
                                                        {{ @$cart->course->instructor->name }}
                                                        @if(get_instructor_ranking_level(@$cart->course->user->badges))
                                                        | {{
                                                        get_instructor_ranking_level(@$cart->course->user->badges)
                                                        }}
                                                        @endif
                                                        @elseif($cart->bundle_id)
                                                        {{ @$cart->bundle->user->instructor->name }}
                                                        @if(get_instructor_ranking_level(@$cart->bundle->user->badges))
                                                        | {{
                                                        get_instructor_ranking_level(@$cart->bundle->user->badges) }}
                                                        @endif
                                                        @elseif($cart->consultation_slot_id)
                                                        {{
                                                        @$cart->consultationSlot->user->$relation->professional_title
                                                        }}
                                                        @if(get_instructor_ranking_level(@$cart->consultationSlot->user->badges))
                                                        | {{
                                                        get_instructor_ranking_level(@$cart->consultationSlot->user->badges)
                                                        }}
                                                        @endif
                                                        @elseif($cart->product_id)
                                                        {{
                                                        @$cart->product->user->$relation->professional_title
                                                        }}
                                                        @if(get_instructor_ranking_level(@$cart->product->user->badges))
                                                        | {{
                                                        get_instructor_ranking_level(@$cart->product->user->badges)
                                                        }}
                                                        @endif
                                                        @endif
                                                    </p>
                                                    @if($cart->course_id)
                                                    @include('frontend.student.cart.partial.cart-course-rating')
                                                    @elseif($cart->consultation_slot_id)
                                                    @include('frontend.student.cart.partial.cart-consultator-rating')
                                                    @elseif($cart->product_id)
                                                    @include('frontend.student.cart.partial.cart-product-rating')
                                                    @endif
                                                </div>
                                            </div>
                                            @if(!$cart->product_id)
                                            <!-- Apply Voucher Start -->
                                            <div class="apply-voucher mt-3">
                                                <form class="row row-cols-lg-auto g-3 align-items-center">
                                                    <div class="col-12">
                                                        <div class="input-group">
                                                            <input type="text"
                                                                class="form-control font-14 coupon-code-{{$cart->id}}"
                                                                placeholder="{{__('Coupon Code')}}">
                                                        </div>
                                                    </div>
                                                    <div class="col-12">
                                                        <button type="button"
                                                            class="theme-btn btn-violet-transparent form-control font-14 apply-coupon-code"
                                                            data-id="{{$cart->id}}"
                                                            data-route="{{ route('student.applyCoupon') }}">{{__('Apply')}}</button>
                                                    </div>
                                                </form>
                                            </div>
                                            @endif
                                            <!-- Apply Voucher End -->
                                        </td>
                                        <td class="wishlist-price font-15 color-heading">
                                            @if($cart->receiver_info)
                                            <span class="fw-bold text-info">Gift Course To {{ $cart->receiver_info['receiver_name']}}</span>
                                            @elseif($cart->course_id)
                                            Course
                                            @elseif($cart->bundle_id)
                                            Bundle Offer
                                            @elseif($cart->consultation_slot_id)
                                            Consultation
                                            @elseif($cart->product_id)
                                            <span>Product</span>
                                            <div class="mt-1 quantity-part" data-id={{ $cart->id }} data-route="{{ route('student.updateCartQuantity') }}">
                                                <button class="increase" data-type=2> <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
                                                    <path d="M3.33203 8H12.6654" stroke="#040453" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                    </svg></button>
                                                <input type="text" disabled class="text-center quantity" value="{{ $cart->quantity }}">
                                                <button class="deincrease" data-type=1><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
                                                    <path d="M8 3.33203V12.6654" stroke="#040453" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                    <path d="M3.33203 8H12.6654" stroke="#040453" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                    </svg></button>
                                            </div>
                                            @endif
                                        </td>
                                        <td class="wishlist-price font-15 color-heading">
                                            <div class="show-price-number">
                                                @if(get_currency_placement() != 'after')
                                                <span class="price-symbol"> {{get_number_format(@$cart->price,
                                                    2)}}</span><span>{{ get_currency_symbol() }}</span>
                                                @else
                                                <span class="price-symbol">{{ get_currency_symbol()
                                                    }}</span><span>{{get_number_format(@$cart->price, 2)}}</span>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="wishlist-remove font-15">
                                            <button>
                                                <span data-formid="delete_row_form_{{$cart->id}}"
                                                    class="iconify deleteItem"
                                                    data-icon="fluent:delete-48-filled"></span>
                                            </button>

                                            <form action="{{ route('student.cartDelete', $cart->id) }}" method="post"
                                                id="delete_row_form_{{ $cart->id }}">
                                                {{ method_field('DELETE') }}
                                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                            </form>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="4" class="text-center">{{__('No Record Found')}}</td>
                                    </tr>
                                    @endforelse

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="order-summary-box bg-white">
                        <form method="POST" action="{{route('student.goToCheckout')}}">
                            @csrf
                            <h3 class="font-18 mb-2">{{__('Order Summery')}}</h3>
                            <div class="cart-order-summary-item-box d-flex justify-content-between align-items-center">
                                <span>items ({{ $carts->count() }}) :</span>
                                <span>

                                    @if(get_currency_placement() == 'after')
                                    <span class="total">{{ get_number_format($carts->sum('price')) }}</span> {{
                                    get_currency_symbol() }}
                                    @else
                                    {{ get_currency_symbol() }} <span class="total">{{
                                        get_number_format($carts->sum('price')) }}</span>
                                    @endif

                                </span>
                            </div>
                            <div class="cart-order-summary-item-box d-flex justify-content-between align-items-center">
                                <span>{{__('Platform Charge')}} ({{get_option('platform_charge')}}%):</span>
                                <span>
                                    @if(get_currency_placement() == 'after')
                                    <span class="platform-charge">{{get_platform_charge($carts->sum('price')+$carts->sum('shipping_charge'))}}</span>
                                    {{ get_currency_symbol() }}
                                    @else
                                    {{ get_currency_symbol() }} <span
                                        class="platform-charge">{{get_platform_charge($carts->sum('price')+$carts->sum('shipping_charge'))}}</span>
                                    @endif
                                </span>
                            </div>

                            <div class="order-summary-box-note d-flex my-4">
                                <div class="flex-shrink-0">
                                    <svg width="18" height="18" viewBox="0 0 18 18" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M9 0.75C4.44375 0.75 0.75 4.44375 0.75 9C0.75 13.5562 4.44375 17.25 9 17.25C13.5562 17.25 17.25 13.5562 17.25 9C17.25 4.44375 13.5562 0.75 9 0.75ZM8.625 4.5C8.42609 4.5 8.23532 4.57902 8.09467 4.71967C7.95402 4.86032 7.875 5.05109 7.875 5.25C7.875 5.44891 7.95402 5.63968 8.09467 5.78033C8.23532 5.92098 8.42609 6 8.625 6H9C9.19891 6 9.38968 5.92098 9.53033 5.78033C9.67098 5.63968 9.75 5.44891 9.75 5.25C9.75 5.05109 9.67098 4.86032 9.53033 4.71967C9.38968 4.57902 9.19891 4.5 9 4.5H8.625ZM7.5 7.5C7.30109 7.5 7.11032 7.57902 6.96967 7.71967C6.82902 7.86032 6.75 8.05109 6.75 8.25C6.75 8.44891 6.82902 8.63968 6.96967 8.78033C7.11032 8.92098 7.30109 9 7.5 9H8.25V11.25H7.5C7.30109 11.25 7.11032 11.329 6.96967 11.4697C6.82902 11.6103 6.75 11.8011 6.75 12C6.75 12.1989 6.82902 12.3897 6.96967 12.5303C7.11032 12.671 7.30109 12.75 7.5 12.75H10.5C10.6989 12.75 10.8897 12.671 11.0303 12.5303C11.171 12.3897 11.25 12.1989 11.25 12C11.25 11.8011 11.171 11.6103 11.0303 11.4697C10.8897 11.329 10.6989 11.25 10.5 11.25H9.75V8.25C9.75 8.05109 9.67098 7.86032 9.53033 7.71967C9.38968 7.57902 9.19891 7.5 9 7.5H7.5Z"
                                            fill="#858585" />
                                    </svg>
                                </div>
                                <div class="flex-grow-1 ms-2">
                                    {{ __('This is for using the platform and get support lifetime') }}
                                </div>
                            </div>

                            @if($carts->sum('shipping_charge') > 0)
                            <div
                                class="cart-order-summary-item-box d-flex justify-content-between align-items-center font-medium color-heading border-top border-bottom border-color py-3">
                                <span>{{ __('Shipping Charge') }}:</span>
                                <span>
                                    @if(get_currency_placement() == 'after')
                                    <span>{{ get_number_format($carts->sum('shipping_charge')) }}</span> {{ get_currency_symbol() }}
                                    @else
                                    {{ get_currency_symbol() }} <span >{{ get_number_format($carts->sum('shipping_charge')) }}</span>
                                    @endif

                                </span>
                            </div>
                            @endif
                            <div
                                class="cart-order-summary-item-box d-flex justify-content-between align-items-center font-medium color-heading border-top border-bottom border-color py-3">
                                <span>{{ __('Total') }}:</span>
                                <span>
                                    @if(get_currency_placement() == 'after')
                                    <span class="grand-total">{{ get_number_format($carts->sum('price') +
                                        get_platform_charge($carts->sum('price')+$carts->sum('shipping_charge'))+$carts->sum('shipping_charge')) }}</span> {{ get_currency_symbol() }}
                                    @else
                                    {{ get_currency_symbol() }} <span class="grand-total">{{
                                        get_number_format($carts->sum('price') +
                                        get_platform_charge($carts->sum('price')+$carts->sum('shipping_charge'))+$carts->sum('shipping_charge')) }}</span>
                                    @endif

                                </span>
                            </div>

                            <div class="cart-check-terms-condition d-flex my-3">
                                <div class="flex-shrink-0">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" checked required
                                            id="flexCheckDefault">
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-1">
                                    By placing your order, you agree with our company <a
                                        href="{{ route('privacy-policy') }}">privacy policy</a> and <a
                                        href="{{ route('privacy-policy') }}">conditions</a> of use.
                                </div>
                            </div>
                            @if(count($carts) > 0)
                            <div class="order-summary-btns">
                                <input type="submit" class="theme-btn theme-button1 w-100 justify-content-center mt-3"
                                    value="{{ __("Proceed to Checkout") }}" name="proceed_to_checkout">
                                @if(get_option('wallet_checkout_system', 0))
                                <input type="submit" class="theme-btn btn-orange w-100 justify-content-center mt-3"
                                    value="{{ __("Pay from wallet") }}" name="pay_from_lmszai_wallet">
                                @endif
                                @if($subscriptionPurchaseEnable)
                                <input type="submit" class="theme-btn btn-orange w-100 justify-content-center mt-3"
                                    value="{{ __("Pay from Subscription") }}" name="pay_from_subscription">
                                @endif
                                <input type="submit" class="theme-btn load-more-btn w-100 justify-content-center mt-3"
                                    value="{{ __("Cancel order") }}" name="cancel_order">
                            </div>
                            @endif
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </section>
    <!-- Cart Page Area End -->
</div>
@endsection

@push('script')
<script src="{{asset('frontend/assets/js/custom/cart-list.js')}}"></script>
@endpush
