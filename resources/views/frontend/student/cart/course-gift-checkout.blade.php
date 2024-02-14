@extends('frontend.layouts.app')

@section('content')
<div class="bg-page">
    <!-- Page Header Start -->
    <header class="page-banner-header gradient-bg position-relative">
        <div class="section-overlay">
            <div class="container">
                <div class="row">
                    <div class="col-12 col-sm-12 col-md-12">
                        <div class="page-banner-content text-center">
                            <h3 class="page-banner-heading text-white pb-15">{{ $pageTitle }}</h3>

                            <!-- Breadcrumb Start-->
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb justify-content-center">
                                    <li class="breadcrumb-item font-14"><a href="{{ url('/') }}">{{ __('Home') }}</a>
                                    </li>
                                    <li class="breadcrumb-item font-14 active" aria-current="page">{{ $pageTitle }}</li>
                                </ol>
                            </nav>
                            <!-- Breadcrumb End-->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>
    <!-- Page Header End -->

    <section class="contact-page-area section-t-space">
        <div class="container">
            <div class="row">
                <!-- Contact page left side start-->
                <div class="col-md-6 col-lg-7 bg-white  contact-page-left-side">
                    <div class="contact-page-left-side-wrap contact-form-area">
                        <h5 class="contact-form-title font-24 font-semi-bold">{{ __("Receiver Information") }}</h5>
                        <form id="contact-form">
                            <div class="row">
                                <div class="col-md-12 mb-30">
                                    <label class="label-text-title color-heading font-medium font-16">{{ __('Receipent
                                        Name') }} <span class="text-danger">*</span></label>
                                    <input type="text" name="receiver_name" class="form-control" placeholder="Recepent name">
                                </div>
                                <div class="col-md-12 mb-30">
                                    <label class="label-text-title color-heading font-medium font-16">{{ __('Receipent
                                        Email') }} <span class="text-danger">*</span></label>
                                    <input type="email" name="receiver_email" class="form-control" placeholder="Recepent Email">
                                </div>
                            </div>
                            <div class="col-12">
                                <button type="button"
                                    class="theme-btn theme-button1 theme-button3 w-100 font-15 fw-bold"  data-course_id="{{ $course->id }}"
                                    data-route="{{ route('student.addToCart') }}" 
                                    id="addToCartGift">{{ __("Send") }}</button>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- Contact page left side End-->

                <!-- Contact page right side start-->
                <div class="col-md-6 col-lg-5 bg-white contact-page-right">
                    @php
                    $userRelation = getUserRoleRelation($course->user);
                    @endphp
                    <div class="contact-page-left-side-wrap ">
                        <h5 class="contact-form-title font-24 font-semi-bold mb-2">{{ __("Course Information") }}</h5>
                        <div class="card course-item {{ $course->status != STATUS_UPCOMING_APPROVED ? "" : "
                            course-item-upcoming" }} border-0 radius-3 bg-white">
                            <div class="course-img-wrap overflow-hidden">
                                <a href="{{ route('course-details', $course->slug) }}"><img
                                        src="{{getImageFile($course->image_path)}}" alt="course" class="img-fluid"></a>
                            </div>
                            <div class="card-body">
                                <h5 class="card-title course-title"><a
                                        href="{{ route('course-details', $course->slug) }}">{{
                                        Str::limit($course->title, 40)}}</a></h5>
                                <p class="card-text instructor-name-certificate font-medium font-12">
                                    <a href="{{ route('userProfile',$course->user->id) }}">{{
                                        $course->$userRelation->name }}</a>
                                    @foreach($course->$userRelation->awards as $award) | {{ $award->name }} @endforeach
                                </p>
                                <div class="course-item-bottom">
                                    <div class="course-rating d-flex align-items-center">
                                        <span class="font-medium font-14 me-2">{{ @$course->average_rating }}</span>
                                        <ul class="rating-list d-flex align-items-center me-2">
                                            @include('frontend.course.render-course-rating')
                                        </ul>
                                        <span class="rating-count font-14">({{ @$course->reviews->count() }})</span>
                                    </div>
                                    <div class="instructor-bottom-item font-14 font-semi-bold">

                                        @if($course->learner_accessibility == 'paid')
                                        <?php
                                                $startDate = date('d-m-Y H:i:s', strtotime(@$course->promotionCourse->promotion->start_date));
                                                $endDate = date('d-m-Y H:i:s', strtotime(@$course->promotionCourse->promotion->end_date));
                                                $percentage = @$course->promotionCourse->promotion->percentage;
                                                $discount_price = number_format($course->price - (($course->price * $percentage) / 100), 2);
                                                ?>

                                        @if(now()->gt($startDate) && now()->lt($endDate))
                                        <div class="instructor-bottom-item font-14 font-semi-bold">
                                            {{ __('Price') }}:
                                            <span class="color-hover">
                                                @if($currencyPlacement ?? get_currency_placement() == 'after')
                                                {{ $discount_price }} {{ $currencySymbol ?? get_currency_symbol() }}
                                                @else
                                                {{ $currencySymbol ?? get_currency_symbol() }} {{ $discount_price }}
                                                @endif
                                            </span>
                                            <span
                                                class="text-decoration-line-through fw-normal font-14 color-gray ps-3">
                                                @if($currencyPlacement ?? get_currency_placement() == 'after')
                                                {{ $course->price }} {{ $currencySymbol ?? get_currency_symbol() }}
                                                @else
                                                {{ $currencySymbol ?? get_currency_symbol() }} {{ $course->price }}
                                                @endif
                                            </span>
                                        </div>
                                        @elseif ($course->price <= $course->old_price)
                                            <div class="instructor-bottom-item font-14 font-semi-bold">
                                                {{ __('Price') }}:
                                                <span class="color-hover">
                                                    @if($currencyPlacement ?? get_currency_placement() == 'after')
                                                    {{ $course->price }} {{ $currencySymbol ?? get_currency_symbol() }}
                                                    @else
                                                    {{ $currencySymbol ?? get_currency_symbol() }} {{ $course->price }}
                                                    @endif
                                                </span>
                                                <span
                                                    class="text-decoration-line-through fw-normal font-14 color-gray ps-3">
                                                    @if($currencyPlacement ?? get_currency_placement() == 'after')
                                                    {{ $course->old_price }} {{ $currencySymbol ?? get_currency_symbol()
                                                    }}
                                                    @else
                                                    {{ $currencySymbol ?? get_currency_symbol() }} {{ $course->old_price
                                                    }}
                                                    @endif
                                                </span>
                                            </div>
                                            @else
                                            <div class="instructor-bottom-item font-14 font-semi-bold">{{ __('Price')
                                                }}:
                                                <span class="color-hover">
                                                    @if($currencyPlacement ?? get_currency_placement() == 'after')
                                                    {{ $course->price }} {{ $currencySymbol ?? get_currency_symbol() }}
                                                    @else
                                                    {{ $currencySymbol ?? get_currency_symbol() }} {{ $course->price }}
                                                    @endif
                                                </span>
                                            </div>
                                            @endif
                                            @elseif($course->learner_accessibility == 'free')
                                            <div class="instructor-bottom-item font-14 font-semi-bold">
                                                {{ __('Free') }}
                                            </div>
                                            @endif
                                            @if($course->learner_accessibility != 'free' &&
                                            get_option('cashback_system_mode', 0))
                                            <div
                                                class="bg-light-purple d-flex font-12 justify-content-between mt-2 p-1 rounded">
                                                <span class="color-para">
                                                    {{ __('Cashback') }}:
                                                </span>
                                                <span class="color-orange">
                                                    @if($currencyPlacement ?? get_currency_placement() == 'after')
                                                    {{calculateCashback($course->price) }} {{ $currencySymbol ??
                                                    get_currency_symbol() }}
                                                    @else
                                                    {{ $currencySymbol ?? get_currency_symbol() }}
                                                    {{calculateCashback($course->price) }}
                                                    @endif
                                                </span>
                                            </div>
                                            @endif
                                            </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <!-- Contact page right side End-->
            </div>
        </div>
    </section>
</div>

@endsection

@push('script')
<script>
    $(document).on("click", '#addToCartGift', function(){
        var course_id = $(this).data('course_id');
        var product_id = $(this).data('product_id');
        var bundle_id = $(this).data('bundle_id');
        var route = $(this).data('route');
        var ref = localStorage.getItem('ref')
        var receiver_name = $(":input[name=receiver_name]").val();
        var receiver_email = $(":input[name=rreceiver_email]").val();

        var receiverInfo = {
            'receiver_name' : $(":input[name=receiver_name]").val(),
            'receiver_email' : $(":input[name=receiver_email]").val(),
        };

        $.ajax({
            type: "POST",
            url: route,
            data: {'course_id': course_id, 'product_id': product_id, 'bundle_id': bundle_id, 'receiver_info': receiverInfo, 'is_gift': 1, 'ref':ref, '_token': $('meta[name="csrf-token"]').attr('content')},
            datatype: "json",
            success: function (response) {
                toastr.options.positionClass = 'toast-bottom-right';
                if (response.status == 402) {
                    toastr.error(response.msg)
                }
                if (response.status == 401 || response.status == 404 || response.status == 409){
                    toastr.error(response.msg)
                } else if(response.status == 200) {
                    $('.cartQuantity').text(response.quantity)
                    toastr.success(response.msg)
                    $('.msgInfoChange').html(response.msgInfoChange)
                }
            },
            error: function (error) {
                toastr.options.positionClass = 'toast-bottom-right';
                if (error.status == 401){
                    toastr.error("You need to login first!")
                }
                else if (error.status == 403){
                    toastr.error("You don't have permission to add course or product!")
                }
                else if (error.status == 422){
                    $.each(error.responseJSON.errors, function(ind, val){
                        toastr.error(val[0]);
                    });
                }

            },
        });
       });
</script>
@endpush