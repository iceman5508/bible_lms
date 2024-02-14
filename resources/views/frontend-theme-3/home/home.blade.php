@extends('frontend.layouts.app')
@section('meta')
    @php
        $metaData = getMeta('home');
    @endphp

    <meta name="description" content="{{ __($metaData['meta_description']) }}">
    <meta name="keywords" content="{{ __($metaData['meta_keyword']) }}">

    <!-- Open Graph meta tags for social sharing -->
    <meta property="og:type" content="Learning">
    <meta property="og:title" content="{{ __($metaData['meta_title']) }}">
    <meta property="og:description" content="{{ __($metaData['meta_description']) }}">
    <meta property="og:image" content="{{ __($metaData['og_image']) }}">
    <meta property="og:url" content="{{ url()->current() }}">

    <meta property="og:site_name" content="{{ __(get_option('app_name')) }}">

    <!-- Twitter Card meta tags for Twitter sharing -->
    <meta name="twitter:card" content="Learning">
    <meta name="twitter:title" content="{{ __($metaData['meta_title']) }}">
    <meta name="twitter:description" content="{{ __($metaData['meta_description']) }}">
    <meta name="twitter:image" content="{{ __($metaData['og_image']) }}">
    @if (isAddonInstalled('LMSZAIPRODUCT'))
        <link rel="stylesheet" href="{{ asset('addon/product/css/ecommerce-product.css') }}">
    @endif
@endsection

@push('theme-style')
    <!-- swiper slider page css -->
    <link rel="stylesheet" href="{{ asset('frontend-theme-3/assets/css/swiper.min.css') }}">

    <!-- page css -->
    <link rel="stylesheet" href="{{ asset('frontend-theme-3/assets/css/style.css') }}">

    <!-- landing_common page css -->
    <link rel="stylesheet" href="{{ asset('frontend-theme-2/assets/css/common.css') }}">
@endpush

@section('content')
    <!-- Main Content Start-->

    <div class="section-landing-hero sf-section-landing-hero">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    @php
                        $bannerImage = @$home->banner_image;
                        if (env('IS_LOCAL', 0)) {
                            $bannerImage = get_option('banner_image_' . get_option('theme', THEME_DEFAULT));
                        }
                    @endphp
                    <div class="landing-hero-area sf-landing-hero-area" data-background="{{ getImageFile($bannerImage) }}">
                        <div class="row">
                            <div class="col-lg-6 offset-lg-3">
                                <div class="landing-banner-text sf-landing-banner-text text-center pb-114">
                                    <h6 class="come-for-learn-text landing-come-area">
                                        @foreach (@$home->banner_mini_words_title ?? [] as $banner_mini_word)
                                            <span>{{ __($banner_mini_word) }}</span>
                                        @endforeach
                                    </h6>
                                    <h1 class=" landing-banner-title">
                                        {{ __(@$home->banner_first_line_title) }}
                                        <span>{{ __(@$home->banner_second_line_title) }}</span>
                                        {{ __(@$home->banner_third_line_title) }}
                                    </h1>
                                    <p class="section-sub-heading landing-sub-heading">{{ __(@$home->banner_subtitle) }}
                                    </p>
                                    <div class="d-flex justify-content-center align-items-center flex-wrap sf-g-10">
                                        <a href="{{ $home->banner_first_button_link }}"
                                            class="tour-btu theme-btn heading-2-bg white-color">
                                            {{ __($home->banner_first_button_name) }}
                                            <i class="fas fa-arrow-right mx-2"></i></a>
                                        <a href="{{ $home->banner_second_button_link }}"
                                            class="theme-btn theme-button1 mx-2">{{ __($home->banner_second_button_name) }}
                                            <i class="fas fa-arrow-right mx-2 "></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="landing-feature feature-1">
                            <div class="align-items-center d-flex flex-row sf-g-10 single-get-item">
                                <div class="flex-shrink-0 feature-img-wrap">
                                    <img src="{{ getImageFile(get_option('banner_left_card_icon')) }}" alt="feature">
                                </div>
                                <div class="feature-content flex-grow-1 text-center text-lg-start rtl-text">
                                    <h6>{{ __(get_option('banner_left_card_title')) }}</h6>
                                    <p>{{ __(get_option('banner_left_card_description')) }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="landing-feature feature-2">
                            <div class="align-items-center d-flex flex-row sf-g-10 single-get-item">
                                <div class="flex-shrink-0 feature-img-wrap">
                                    <img src="{{ getImageFile(get_option('banner_right_card_icon')) }}" alt="feature">
                                </div>
                                <div class="feature-content flex-grow-1  text-center text-lg-start rtl-text">
                                    <h6>{{ __(get_option('banner_right_card_title')) }}</h6>
                                    <p>{{ __(get_option('banner_right_card_description')) }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- instructor and support area start --}}
    @if ($home->instructor_support_area == 1)
        <div class="instructor-open-part position-relative">
            <div class="will-get-area instructor-support-area">
                <div class="section-overlay landing-overlay instructor-support">

                    <div class="container">
                        <div class="row">
                            <div class="col-lg-6 offset-lg-3">
                                <div class="section-title-part">

                                    <h3 class="section-heading section-heading-light mx-auto w-100 text-center">
                                        {{ __(@$aboutUsGeneral->instructor_support_title) }}</h3>
                                    <p class="section-sub-heading text-center mb-la-24 mb-0">
                                        {{ __(@$aboutUsGeneral->instructor_support_subtitle) }}</p>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <div class="support-instructor-box">
                <div class="container">
                    <div class="row">
                        @foreach ($instructorSupports as $instructorSupport)
                            <div class="col-lg-4 col-md-4 col-sm-6">
                                <div class="instructor-support-item bg-white radius-3 text-center">
                                    <div class="instructor-support-img-wrap">
                                        <img src="{{ getImageFile($instructorSupport->image_path) }}"
                                            alt="{{ __('support') }}">
                                    </div>
                                    <h6>{{ __($instructorSupport->title) }}</h6>
                                    <p>{{ __($instructorSupport->subtitle) }} </p>
                                    <a href="{{ $instructorSupport->button_link ?? '#' }}"
                                        class="theme-btn theme-button1 theme-button3">{{ __($instructorSupport->button_name) }}
                                        <i class="fas fa-arrow-right mx-2"></i>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

        </div>
    @endif

    {{-- instructor and support area end --}}

    @if (!get_option('private_mode') || !auth()->guest())
        {{-- Latest Bundle Courses area start --}}
        @if ($home->courses_area == 1)
            <div class="latest-courses-area courses-area-landing latest-courses-landing-2">
                <div class="container border-bottom">
                    <div class="row">
                        <div class="col-lg-12">
                            <div
                                class="section-left-title-with-btn d-flex justify-content-between align-items-end align-items-center">
                                <div class="section-title section-title-left d-flex align-items-start">
                                    <div class="section-heading-img me-3">
                                        <img src="{{ asset('frontend-theme-2/assets/img/education.png') }}" alt="course">
                                    </div>
                                    <div>
                                        <h3 class="section-heading heading-2">{{ __(get_option('course_title')) }}</h3>
                                        <p class="section-sub-heading mb-0">{{ __(get_option('course_subtitle')) }}</p>
                                    </div>
                                </div>
                                <a href="{{ route('courses') }}"
                                    class=" theme-button2 theme-button3 sf-bg-transparent">{{ __('View All') }} <i
                                        class="fas fa-arrow-right mx-2"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    @if (count($featuredCourses))
                        <div class="latest-courses-slider owl-carousel">
                            @foreach ($featuredCourses as $course)
                                @php
                                    $userRelation = getUserRoleRelation($course->user);
                                @endphp
                                <div class="col-12 col-lg-6 w-100">
                                    @include('frontend-theme-3.partials.course')
                                </div>
                            @endforeach
                        </div>
                    @else
                        {{ __('No Course Found') }}
                    @endif
                </div>
            </div>
        @endif
        {{-- Latest Bundle Courses area end --}}

        {{-- Course By Category section area start --}}
        @if ($home->category_courses_area == 1)
            <div class="course-category-area angel-elsie-bg">
                <div class="container position-relative border-bottom">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="text-start landing-2-tab">

                                <div class="mb-2">
                                    <img src="{{ asset('frontend-theme-2/assets/img/education.png') }}" alt="course">
                                </div>
                                <div>
                                    <h3 class="section-heading w-100">{{ __(get_option('category_course_title')) }}</h3>
                                    <p class="section-sub-heading mb-la-24">
                                        {{ __(get_option('category_course_subtitle')) }}</p>
                                </div>
                                <div class="category-tab-area">

                                    <ul class="nav nav-pills mb-category-50  " id="pills-tab" role="tablist">
                                        @foreach ($featureCategories as $key => $category)
                                            <li class="nav-item mb-2" role="presentation">
                                                <button class="nav-link {{ $key == 0 ? 'active' : '' }}"
                                                    id="{{ $category->slug }}-tab" data-bs-toggle="pill"
                                                    data-bs-target="#{{ $category->slug }}" type="button"
                                                    role="tab" aria-controls="{{ $category->slug }}"
                                                    aria-selected="{{ $key == 0 ? 'true' : 'false' }}">{{ __($category->name) }}</button>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">

                        </div>
                        <div class="col-lg-12">
                            <div class="tab-content">
                                @foreach ($featureCategories as $key => $category)
                                    <div class="tab-pane fade {{ $key == 0 ? 'show active' : '' }}"
                                        id="{{ $category->slug }}" role="tabpanel"
                                        aria-labelledby="{{ $category->slug }}-tab">
                                        @foreach ($category->courses->take(1) as $course)
                                            @php
                                                $userRelation = getUserRoleRelation($course->user);
                                            @endphp
                                            <div class="common-product ">

                                                <div
                                                    class="card course-item course-item-upcoming border-0 radius-3 bg-white flex-row min-course-h align-items-center course-landing-card">
                                                    <div class="course-img-wrap overflow-hidden min-h-auto w-auto">
                                                        @if ($course->status != STATUS_UPCOMING_APPROVED)
                                                            @if (date('Y-m-d H:i:s', strtotime('-7 days')) >= $course->created_at)
                                                                @if (in_array($course->id, $topCourse))
                                                                    <span
                                                                        class="course-tag badge radius-3 font-12 font-medium position-absolute bg-orange">{{ __('Best Seller') }}</span>
                                                                @endif
                                                            @else
                                                                <span
                                                                    class="course-tag badge radius-3 font-12 font-medium position-absolute bg-green">{{ __('New course') }}</span>
                                                            @endif
                                                            <?php
                                                            $special = @$course->specialPromotionTagCourse->specialPromotionTag->name;
                                                            ?>
                                                            @if ($special)
                                                                <span
                                                                    class="course-tag badge radius-3 font-12 font-medium position-absolute bg-orange">
                                                                    {{ __(@$special) }}
                                                                </span>
                                                            @endif
                                                        @else
                                                            <span
                                                                class="course-tag badge radius-3 font-12 font-medium position-absolute bg-warning">{{ __('Upcoming') }}</span>
                                                        @endif
                                                        <a href="{{ route('course-details', $course->slug) }}">
                                                            <img src="{{ getImageFile($course->image_path) }}"
                                                                alt="{{ __('course') }}">
                                                        </a>

                                                    </div>
                                                    <div class="card-body">
                                                        <h5 class="card-title course-title"><a
                                                                href="{{ route('course-details', $course->slug) }}">{{ Str::limit($course->title, 40) }}</a>
                                                        </h5>
                                                        <p
                                                            class="card-text instructor-name-certificate font-medium font-12">
                                                            <a
                                                                href="{{ route('userProfile', $course->user->id) }}">{{ $course->$userRelation->name }}</a>
                                                            @foreach ($course->$userRelation->awards as $award)
                                                                | {{ $award->name }}
                                                            @endforeach
                                                        </p>
                                                        <div class="course-item-bottom">
                                                            <div class="course-rating d-flex align-items-center">
                                                                <span
                                                                    class="font-medium font-14 me-2">{{ @$course->average_rating }}</span>
                                                                <div class="rating-list d-flex align-items-center me-2">
                                                                    <div
                                                                        class="search-instructor-rating w-100 d-inline-flex align-items-center justify-content-center">
                                                                        <div class="star-ratings">
                                                                            <div class="fill-ratings"
                                                                                style="width: {{ @$course->average_rating * 20 }}%">
                                                                                <span>★★★★★</span>
                                                                            </div>
                                                                            <div class="empty-ratings">
                                                                                <span>★★★★★</span>
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                </div>
                                                                <span
                                                                    class="rating-count font-14">({{ @$course->reviews->count() }})</span>
                                                            </div>
                                                            <div class="instructor-bottom-item font-14 font-semi-bold">
                                                                @if ($course->learner_accessibility == 'paid')
                                                                    <?php
                                                                    $startDate = date('d-m-Y H:i:s', strtotime(@$course->promotionCourse->promotion->start_date));
                                                                    $endDate = date('d-m-Y H:i:s', strtotime(@$course->promotionCourse->promotion->end_date));
                                                                    $percentage = @$course->promotionCourse->promotion->percentage;
                                                                    $discount_price = number_format($course->price - ($course->price * $percentage) / 100, 2);
                                                                    ?>

                                                                    @if (now()->gt($startDate) && now()->lt($endDate))
                                                                        <div
                                                                            class="instructor-bottom-item font-14 font-semi-bold">
                                                                            {{ __('Price') }}:
                                                                            <span class="color-hover">
                                                                                @if ($currencyPlacement ?? get_currency_placement() == 'after')
                                                                                    {{ $discount_price }}
                                                                                    {{ $currencySymbol ?? get_currency_symbol() }}
                                                                                @else
                                                                                    {{ $currencySymbol ?? get_currency_symbol() }}
                                                                                    {{ $discount_price }}
                                                                                @endif
                                                                            </span>
                                                                            <span
                                                                                class="text-decoration-line-through fw-normal font-14 color-gray ps-3">
                                                                                @if ($currencyPlacement ?? get_currency_placement() == 'after')
                                                                                    {{ $course->price }}
                                                                                    {{ $currencySymbol ?? get_currency_symbol() }}
                                                                                @else
                                                                                    {{ $currencySymbol ?? get_currency_symbol() }}
                                                                                    {{ $course->price }}
                                                                                @endif
                                                                            </span>
                                                                        </div>
                                                                    @elseif ($course->price <= $course->old_price)
                                                                        <div
                                                                            class="instructor-bottom-item font-14 font-semi-bold">
                                                                            {{ __('Price') }}:
                                                                            <span class="color-hover">
                                                                                @if ($currencyPlacement ?? get_currency_placement() == 'after')
                                                                                    {{ $course->price }}
                                                                                    {{ $currencySymbol ?? get_currency_symbol() }}
                                                                                @else
                                                                                    {{ $currencySymbol ?? get_currency_symbol() }}
                                                                                    {{ $course->price }}
                                                                                @endif
                                                                            </span>
                                                                            <span
                                                                                class="text-decoration-line-through fw-normal font-14 color-gray ps-3">
                                                                                @if ($currencyPlacement ?? get_currency_placement() == 'after')
                                                                                    {{ $course->old_price }}
                                                                                    {{ $currencySymbol ?? get_currency_symbol() }}
                                                                                @else
                                                                                    {{ $currencySymbol ?? get_currency_symbol() }}
                                                                                    {{ $course->old_price }}
                                                                                @endif
                                                                            </span>
                                                                        </div>
                                                                    @else
                                                                        <div
                                                                            class="instructor-bottom-item font-14 font-semi-bold">
                                                                            {{ __('Price') }}:
                                                                            <span class="color-hover">
                                                                                @if ($currencyPlacement ?? get_currency_placement() == 'after')
                                                                                    {{ $course->price }}
                                                                                    {{ $currencySymbol ?? get_currency_symbol() }}
                                                                                @else
                                                                                    {{ $currencySymbol ?? get_currency_symbol() }}
                                                                                    {{ $course->price }}
                                                                                @endif
                                                                            </span>
                                                                        </div>
                                                                    @endif
                                                                @elseif($course->learner_accessibility == 'free')
                                                                    <div
                                                                        class="instructor-bottom-item font-14 font-semi-bold">
                                                                        {{ __('Free') }}
                                                                    </div>
                                                                @endif
                                                                @if ($course->learner_accessibility != 'free' && get_option('cashback_system_mode', 0))
                                                                    <div
                                                                        class="bg-light-purple d-flex font-12 justify-content-between mt-2 p-1 rounded">
                                                                        <span class="color-para">
                                                                            {{ __('Cashback') }}:
                                                                        </span>
                                                                        <span class="color-orange">
                                                                            @if ($currencyPlacement ?? get_currency_placement() == 'after')
                                                                                {{ calculateCashback($course->price) }}
                                                                                {{ $currencySymbol ?? get_currency_symbol() }}
                                                                            @else
                                                                                {{ $currencySymbol ?? get_currency_symbol() }}
                                                                                {{ calculateCashback($course->price) }}
                                                                            @endif
                                                                        </span>
                                                                    </div>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                        @endforeach
                                        <div class="tab-slider-landing owl-carousel">
                                            @foreach ($category->courses->skip(1)->take(12) as $course)
                                                @php
                                                    $userRelation = getUserRoleRelation($course->user);
                                                @endphp
                                                <div class="col-12 col-lg-6 w-100">
                                                    <div
                                                        class="card course-item course-item-upcoming border-0 radius-3 bg-white">
                                                        <div class="course-img-wrap overflow-hidden">
                                                            @if ($course->status != STATUS_UPCOMING_APPROVED)
                                                                @if (date('Y-m-d H:i:s', strtotime('-7 days')) >= $course->created_at)
                                                                    @if (in_array($course->id, $topCourse))
                                                                        <span
                                                                            class="course-tag badge radius-3 font-12 font-medium position-absolute bg-orange">{{ __('Best Seller') }}</span>
                                                                    @endif
                                                                @else
                                                                    <span
                                                                        class="course-tag badge radius-3 font-12 font-medium position-absolute bg-green">{{ __('New course') }}</span>
                                                                @endif
                                                                <?php
                                                                $special = @$course->specialPromotionTagCourse->specialPromotionTag->name;
                                                                ?>
                                                                @if ($special)
                                                                    <span
                                                                        class="course-tag badge radius-3 font-12 font-medium position-absolute bg-orange">
                                                                        {{ __(@$special) }}
                                                                    </span>
                                                                @endif
                                                            @else
                                                                <span
                                                                    class="course-tag badge radius-3 font-12 font-medium position-absolute bg-warning">{{ __('Upcoming') }}</span>
                                                            @endif
                                                            <a href="{{ route('course-details', $course->slug) }}">
                                                                <img src="{{ getImageFile($course->image_path) }}"
                                                                    alt="{{ __('course') }}">
                                                            </a>
                                                        </div>
                                                        <div class="card-body">
                                                            <h5 class="card-title course-title"><a
                                                                    href="{{ route('course-details', $course->slug) }}">{{ Str::limit($course->title, 40) }}</a>
                                                            </h5>
                                                            <p
                                                                class="card-text instructor-name-certificate font-medium font-12">
                                                                <a
                                                                    href="{{ route('userProfile', $course->user->id) }}">{{ $course->$userRelation->name }}</a>
                                                                @foreach ($course->$userRelation->awards as $award)
                                                                    | {{ $award->name }}
                                                                @endforeach
                                                            </p>
                                                            <div class="course-item-bottom">
                                                                <div class="course-rating d-flex align-items-center">
                                                                    <span
                                                                        class="font-medium font-14 me-2">{{ @$course->average_rating }}</span>
                                                                    <div
                                                                        class="rating-list d-flex align-items-center me-2">
                                                                        <div
                                                                            class="search-instructor-rating w-100 d-inline-flex align-items-center justify-content-center">
                                                                            <div class="star-ratings">
                                                                                <div class="fill-ratings"
                                                                                    style="width: {{ @$course->average_rating * 20 }}%">
                                                                                    <span>★★★★★</span>
                                                                                </div>
                                                                                <div class="empty-ratings">
                                                                                    <span>★★★★★</span>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <span
                                                                        class="rating-count font-14">({{ @$course->reviews->count() }})</span>
                                                                </div>
                                                                <div class="instructor-bottom-item font-14 font-semi-bold">
                                                                    @if ($course->learner_accessibility == 'paid')
                                                                        <?php
                                                                        $startDate = date('d-m-Y H:i:s', strtotime(@$course->promotionCourse->promotion->start_date));
                                                                        $endDate = date('d-m-Y H:i:s', strtotime(@$course->promotionCourse->promotion->end_date));
                                                                        $percentage = @$course->promotionCourse->promotion->percentage;
                                                                        $discount_price = number_format($course->price - ($course->price * $percentage) / 100, 2);
                                                                        ?>

                                                                        @if (now()->gt($startDate) && now()->lt($endDate))
                                                                            <div
                                                                                class="instructor-bottom-item font-14 font-semi-bold">
                                                                                {{ __('Price') }}:
                                                                                <span class="color-hover">
                                                                                    @if ($currencyPlacement ?? get_currency_placement() == 'after')
                                                                                        {{ $discount_price }}
                                                                                        {{ $currencySymbol ?? get_currency_symbol() }}
                                                                                    @else
                                                                                        {{ $currencySymbol ?? get_currency_symbol() }}
                                                                                        {{ $discount_price }}
                                                                                    @endif
                                                                                </span>
                                                                                <span
                                                                                    class="text-decoration-line-through fw-normal font-14 color-gray ps-3">
                                                                                    @if ($currencyPlacement ?? get_currency_placement() == 'after')
                                                                                        {{ $course->price }}
                                                                                        {{ $currencySymbol ?? get_currency_symbol() }}
                                                                                    @else
                                                                                        {{ $currencySymbol ?? get_currency_symbol() }}
                                                                                        {{ $course->price }}
                                                                                    @endif
                                                                                </span>
                                                                            </div>
                                                                        @elseif ($course->price <= $course->old_price)
                                                                            <div
                                                                                class="instructor-bottom-item font-14 font-semi-bold">
                                                                                {{ __('Price') }}:
                                                                                <span class="color-hover">
                                                                                    @if ($currencyPlacement ?? get_currency_placement() == 'after')
                                                                                        {{ $course->price }}
                                                                                        {{ $currencySymbol ?? get_currency_symbol() }}
                                                                                    @else
                                                                                        {{ $currencySymbol ?? get_currency_symbol() }}
                                                                                        {{ $course->price }}
                                                                                    @endif
                                                                                </span>
                                                                                <span
                                                                                    class="text-decoration-line-through fw-normal font-14 color-gray ps-3">
                                                                                    @if ($currencyPlacement ?? get_currency_placement() == 'after')
                                                                                        {{ $course->old_price }}
                                                                                        {{ $currencySymbol ?? get_currency_symbol() }}
                                                                                    @else
                                                                                        {{ $currencySymbol ?? get_currency_symbol() }}
                                                                                        {{ $course->old_price }}
                                                                                    @endif
                                                                                </span>
                                                                            </div>
                                                                        @else
                                                                            <div
                                                                                class="instructor-bottom-item font-14 font-semi-bold">
                                                                                {{ __('Price') }}:
                                                                                <span class="color-hover">
                                                                                    @if ($currencyPlacement ?? get_currency_placement() == 'after')
                                                                                        {{ $course->price }}
                                                                                        {{ $currencySymbol ?? get_currency_symbol() }}
                                                                                    @else
                                                                                        {{ $currencySymbol ?? get_currency_symbol() }}
                                                                                        {{ $course->price }}
                                                                                    @endif
                                                                                </span>
                                                                            </div>
                                                                        @endif
                                                                    @elseif($course->learner_accessibility == 'free')
                                                                        <div
                                                                            class="instructor-bottom-item font-14 font-semi-bold">
                                                                            {{ __('Free') }}
                                                                        </div>
                                                                    @endif
                                                                    @if ($course->learner_accessibility != 'free' && get_option('cashback_system_mode', 0))
                                                                        <div
                                                                            class="bg-light-purple d-flex font-12 justify-content-between mt-2 p-1 rounded">
                                                                            <span class="color-para">
                                                                                {{ __('Cashback') }}:
                                                                            </span>
                                                                            <span class="color-orange">
                                                                                @if ($currencyPlacement ?? get_currency_placement() == 'after')
                                                                                    {{ calculateCashback($course->price) }}
                                                                                    {{ $currencySymbol ?? get_currency_symbol() }}
                                                                                @else
                                                                                    {{ $currencySymbol ?? get_currency_symbol() }}
                                                                                    {{ calculateCashback($course->price) }}
                                                                                @endif
                                                                            </span>
                                                                        </div>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
        {{-- Course By Category section area end --}}

        {{-- Extensive Product  area start --}}
        @if (isAddonInstalled('LMSZAIPRODUCT'))
            @if ($home->product_area == 1)
                <div class="extensive-area-2">
                    <div class="container">
                        <div class="row">
                            <div class="col-lg-10 offset-lg-1">
                                <div class="gradient-bg-lan text-center">

                                    <div class="extensive-banner-area">
                                        <img src="{{ getImageFile(get_option('product_section_logo')) }}"
                                            alt="{{ __('Product') }}">
                                        <h3 class="section-heading mx-auto w-100 text-center">
                                            {{ __(get_option('product_section_title')) }} @if (env('LOGIN_HELP') == 'active')
                                                <span class="color-deep-orange font-18">(Addon)</span>
                                            @endif
                                        </h3>
                                        <p>{{ __(get_option('product_section_subtitle')) }}</p>

                                        <a href="{{ route('lms_product.frontend.list') }}"
                                            class=" theme-button2 theme-button3 sf-bg-transparent">{{ __('View All') }}
                                            <i class="fas fa-arrow-right mx-2"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-10 offset-lg-1">
                                <div class="extensive-product-all owl-carousel mt-50">
                                    @foreach ($products as $product)
                                        <div class="extensive-single-product ">
                                            <div class="course-img-wrap overflow-hidden min-h-auto w-auto flex-shrink-0">

                                                <a href="{{ route('lms_product.frontend.view', $product->slug) }}">
                                                    <img src="{{ getImageFile($product->thumbnail_path) }}"
                                                        alt="{{ $product->title }}">
                                                </a>

                                            </div>
                                            <div class="extensive-product-title">
                                                <h5 class="section-heading w-100"><a
                                                        href="{{ route('lms_product.frontend.view', $product->slug) }}">{{ $product->title }}</a>
                                                </h5>
                                                @php
                                                    $reviewCount = $product->reviews()->count();
                                                    $averate_percent = $product->average_review * 20;
                                                @endphp
                                                <div class="course-item-bottom">
                                                    <div class="course-rating d-flex align-items-center">
                                                        <span
                                                            class="font-medium font-14 me-2">{{ number_format(@$product->average_review, 1) }}</span>
                                                        <div class="rating-list d-flex align-items-center me-2">
                                                            <div
                                                                class="search-instructor-rating w-100 d-inline-flex align-items-center justify-content-center">
                                                                <div class="star-ratings">
                                                                    <div class="fill-ratings"
                                                                        style="width: {{ $averate_percent }}%">
                                                                        <span>★★★★★</span>
                                                                    </div>
                                                                    <div class="empty-ratings">
                                                                        <span>★★★★★</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <span class="rating-count font-14">({{ $reviewCount }})</span>
                                                    </div>
                                                    <div class="instructor-bottom-item font-14 font-semi-bold">
                                                        <div class="instructor-bottom-item font-14 font-semi-bold">
                                                            {{ __('Price') }}:
                                                            @if ($product->old_price > $product->current_price)
                                                                <span class="text-decoration-line-through">
                                                                    @if ($currencyPlacement ?? get_currency_placement() == 'after')
                                                                        {{ $product->old_price }}
                                                                        {{ $currencySymbol ?? get_currency_symbol() }}
                                                                    @else
                                                                        {{ $currencySymbol ?? get_currency_symbol() }}
                                                                        {{ $product->old_price }}
                                                                    @endif
                                                                </span>
                                                                <span class="font-14 color-hover  ps-3">
                                                                    @if ($currencyPlacement ?? get_currency_placement() == 'after')
                                                                        {{ $product->current_price }}
                                                                        {{ $currencySymbol ?? get_currency_symbol() }}
                                                                    @else
                                                                        {{ $currencySymbol ?? get_currency_symbol() }}
                                                                        {{ $product->current_price }}
                                                                    @endif
                                                                </span>
                                                            @else
                                                                <span class="text-decoration-line-through">
                                                                    @if ($currencyPlacement ?? get_currency_placement() == 'after')
                                                                        {{ $product->current_price }}
                                                                        {{ $currencySymbol ?? get_currency_symbol() }}
                                                                    @else
                                                                        {{ $currencySymbol ?? get_currency_symbol() }}
                                                                        {{ $product->current_price }}
                                                                    @endif
                                                                </span>
                                                            @endif
                                                        </div>
                                                        @if ($product->quantity > 0)
                                                            <button type="button"
                                                                class="theme-btn theme-button1 theme-button3 mt-25 addToCart"
                                                                data-product_id="{{ $product->id }}" data-quantity=1
                                                                data-route="{{ route('student.addToCart') }}">{{ __('Add To Cart') }}<i
                                                                    class="fas fa-arrow-right mx-2"></i></button>
                                                        @else
                                                            <button type="button"
                                                                class="bg-warning p-2 rounded text-white w-75 mt-25">{{ __('Out of stock') }}</button>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        @endif
        {{-- Extensive Product  area end --}}

        {{-- bundle-courses-area Bundle Courses area start --}}
        @if ($home->bundle_area == 1)
            @if (count($bundles) > 0)
                <div class="bundle-courses-area angel-elsie-bg">
                    <div class="container">
                        <div class="row  position-relative sf-rg-16">

                            <div class="col-lg-6">
                                <div class="section-title bundle-pt">
                                    <div class="section-heading-img flex-shrink-0">
                                        <img src="{{ getImageFile(get_option('bundle_course_logo')) }}"
                                            alt="{{ 'Bundle' }}">
                                    </div>
                                    <div>
                                        <h3 class="section-heading w-100">{{ __(get_option('bundle_course_title')) }}
                                        </h3>
                                        <p class="section-sub-heading mb-la-24 font-la-14">
                                            {{ __(get_option('bundle_course_subtitle')) }}</p>
                                        <a href="{{ route('bundles') }}"
                                            class=" theme-button2 theme-button3 sf-bg-transparent">{{ __('View All') }}
                                            <i class="fas fa-arrow-right mx-2"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="bundle-all-slider swiper">
                                    <div class="bundle-slider swiper-wrapper">
                                        @while (count($bundles))
                                            <div class="row single-bundle-slider swiper-slide sf-rg-md-24">
                                                @foreach ($bundles->take(2) as $bundle)
                                                    @php
                                                        $relation = getUserRoleRelation($bundle->user);
                                                    @endphp
                                                    <div
                                                        class="col-lg-6 col-md-6 align-self-start {{ $loop->first ? 'bundle-1' : '' }}">
                                                        <div class="card border-0 radius-3 bg-white sf-h-md-100">
                                                            <div
                                                                class="course-img-wrap single-bundle-img overflow-hidden ">
                                                                <a href="{{ route('bundle-details', [$bundle->slug]) }}">
                                                                    <img src="{{ getImageFile($bundle->image) }}"
                                                                        alt="course" class="img-fluid">
                                                                </a>
                                                            </div>
                                                            <div class="card-body">
                                                                <h5 class="card-title course-title"><a
                                                                        href="{{ route('bundle-details', [$bundle->slug]) }}">{{ Str::limit($bundle->name, 40) }}</a>
                                                                </h5>
                                                                <p
                                                                    class="card-text instructor-name-certificate font-medium font-14">
                                                                    <a
                                                                        href="{{ route('userProfile', $bundle->user->id) }}">{{ @$bundle->user->$relation->name }}</a>
                                                                    @if (@$bundle->user->$relation->level_id != null)
                                                                        |
                                                                        {{ @$bundle->user->$relation->ranking_level->name }}
                                                                    @endif
                                                                </p>
                                                                <div class="course-item-bottom">
                                                                    <div
                                                                        class="instructor-bottom-item font-14 font-semi-bold mb-15">
                                                                        {{ __('Courses') }}: <span
                                                                            class="heading-2">{{ @$bundle->bundleCourses->count() }}</span>
                                                                    </div>
                                                                    <div
                                                                        class="instructor-bottom-item font-14 font-semi-bold">
                                                                        <div
                                                                            class="instructor-bottom-item font-14 font-semi-bold">
                                                                            {{ __('Price') }}:
                                                                            <span class="color-hover">
                                                                                @if ($currencyPlacement == 'after')
                                                                                    {{ $bundle->price }}
                                                                                    {{ $currencySymbol }}
                                                                                @else
                                                                                    {{ $currencySymbol }}
                                                                                    {{ $bundle->price }}
                                                                                @endif
                                                                            </span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                                @php
                                                    $bundles = $bundles->skip(2);
                                                @endphp
                                            </div>
                                        @endwhile
                                    </div>
                                    <div class="slider-btu">

                                        <div class="swiper-button-next bundle-next"></div>
                                        <div class="swiper-button-prev bundle-prev"></div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            @endif
        @endif
    @endif
    {{-- bundle-courses-area Bundle Courses area end --}}

    {{-- Upcoming Course Section start --}}
    @if ($home->upcoming_courses_area == 1)
        <div class="upcoming-section-area courses-area-landing">
            <div class="container border-bottom">
                <div class="row">
                    <div class="col-lg-12">
                        <div
                            class="section-left-title-with-btn d-flex justify-content-between align-items-end align-items-center">
                            <div class="section-title section-title-left d-flex align-items-start">
                                <div class="section-heading-img me-3">
                                    <img src="{{ getImageFile(get_option('upcoming_course_logo')) }}" alt="course">
                                </div>
                                <div>
                                    <h3 class="section-heading heading-2">{{ __(get_option('upcoming_course_title')) }}
                                    </h3>
                                    <p class="section-sub-heading mb-0">{{ __(get_option('upcoming_course_subtitle')) }}
                                    </p>
                                </div>
                            </div>
                            <a href="{{ route('courses') }}"
                                class=" theme-button2 theme-button3 sf-bg-transparent">{{ __('View All') }} <i
                                    class="fas fa-arrow-right mx-2"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="upcoming-all-slider owl-carousel">
                    @forelse($upcomingCourses as $course)
                        @php
                            $userRelation = getUserRoleRelation($course->user);
                        @endphp
                        <div class="col-lg-12">
                            <div
                                class="card  border-0 radius-3 bg-white flex-lg-row flex-column min-course-h align-items-center course-landing-card p-3 mb-30">
                                <div
                                    class="course-img-wrap overflow-hidden min-h-auto sf-max-w-md-327 align-self-stretch sf-max-md-h-228">
                                    <span
                                        class="course-tag badge radius-3 font-12 font-medium position-absolute color-yellow-bg heading-2">{{ __('Upcoming') }}</span>
                                    <a href="{{ route('course-details', $course->slug) }}">
                                        <img src="{{ getImageFile($course->image_path) }}" alt="course"
                                            class="img-fluid">
                                    </a>
                                </div>
                                <div class="card-body">
                                    <h5 class="card-title course-title"><a
                                            href="{{ route('course-details', $course->slug) }}">{{ Str::limit($course->title, 40) }}</a>
                                    </h5>
                                    <p class="card-text instructor-name-certificate font-medium font-14">
                                        <a
                                            href="{{ route('userProfile', $course->user->id) }}">{{ $course->$userRelation->name }}</a>
                                        @foreach ($course->$userRelation->awards as $award)
                                            | {{ $award->name }}
                                        @endforeach
                                    </p>
                                    <div class="course-item-bottom">
                                        <div class="course-rating d-flex align-items-center">
                                            <span class="font-medium font-14 me-2">{{ @$course->average_rating }}</span>
                                            <div class="rating-list d-flex align-items-center me-2">
                                                <div
                                                    class="search-instructor-rating w-100 d-inline-flex align-items-center justify-content-center">
                                                    <div class="star-ratings">
                                                        <div class="fill-ratings"
                                                            style="width: {{ @$course->average_rating * 20 }}%">
                                                            <span>★★★★★</span>
                                                        </div>
                                                        <div class="empty-ratings">
                                                            <span>★★★★★</span>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                            <span class="rating-count font-14">({{ @$course->reviews->count() }})</span>
                                        </div>
                                        <div class="instructor-bottom-item font-14 font-semi-bold">
                                            @if ($course->learner_accessibility == 'paid')
                                                <?php
                                                $startDate = date('d-m-Y H:i:s', strtotime(@$course->promotionCourse->promotion->start_date));
                                                $endDate = date('d-m-Y H:i:s', strtotime(@$course->promotionCourse->promotion->end_date));
                                                $percentage = @$course->promotionCourse->promotion->percentage;
                                                $discount_price = number_format($course->price - ($course->price * $percentage) / 100, 2);
                                                ?>
                                                @if (now()->gt($startDate) && now()->lt($endDate))
                                                    <div class="instructor-bottom-item font-14 font-semi-bold">
                                                        {{ __('Price') }}:
                                                        <span class="color-hover">
                                                            @if ($currencyPlacement ?? get_currency_placement() == 'after')
                                                                {{ $discount_price }}
                                                                {{ $currencySymbol ?? get_currency_symbol() }}
                                                            @else
                                                                {{ $currencySymbol ?? get_currency_symbol() }}
                                                                {{ $discount_price }}
                                                            @endif
                                                        </span>
                                                        <span
                                                            class="text-decoration-line-through fw-normal font-14 color-gray ps-3">
                                                            @if ($currencyPlacement ?? get_currency_placement() == 'after')
                                                                {{ $course->price }}
                                                                {{ $currencySymbol ?? get_currency_symbol() }}
                                                            @else
                                                                {{ $currencySymbol ?? get_currency_symbol() }}
                                                                {{ $course->price }}
                                                            @endif
                                                        </span>
                                                    </div>
                                                @elseif ($course->price <= $course->old_price)
                                                    <div class="instructor-bottom-item font-14 font-semi-bold">
                                                        {{ __('Price') }}:
                                                        <span class="color-hover">
                                                            @if ($currencyPlacement ?? get_currency_placement() == 'after')
                                                                {{ $course->price }}
                                                                {{ $currencySymbol ?? get_currency_symbol() }}
                                                            @else
                                                                {{ $currencySymbol ?? get_currency_symbol() }}
                                                                {{ $course->price }}
                                                            @endif
                                                        </span>
                                                        <span
                                                            class="text-decoration-line-through fw-normal font-14 color-gray ps-3">
                                                            @if ($currencyPlacement ?? get_currency_placement() == 'after')
                                                                {{ $course->old_price }}
                                                                {{ $currencySymbol ?? get_currency_symbol() }}
                                                            @else
                                                                {{ $currencySymbol ?? get_currency_symbol() }}
                                                                {{ $course->old_price }}
                                                            @endif
                                                        </span>
                                                    </div>
                                                @else
                                                    <div class="instructor-bottom-item font-14 font-semi-bold">
                                                        {{ __('Price') }}:
                                                        <span class="color-hover">
                                                            @if ($currencyPlacement ?? get_currency_placement() == 'after')
                                                                {{ $course->price }}
                                                                {{ $currencySymbol ?? get_currency_symbol() }}
                                                            @else
                                                                {{ $currencySymbol ?? get_currency_symbol() }}
                                                                {{ $course->price }}
                                                            @endif
                                                        </span>
                                                    </div>
                                                @endif
                                            @elseif($course->learner_accessibility == 'free')
                                                <div class="instructor-bottom-item font-14 font-semi-bold">
                                                    {{ __('Free') }}
                                                </div>
                                            @endif
                                            @if ($course->learner_accessibility != 'free' && get_option('cashback_system_mode', 0))
                                                <div
                                                    class="bg-light-purple d-flex font-12 justify-content-between mt-2 p-1 rounded">
                                                    <span class="color-para">
                                                        {{ __('Cashback') }}:
                                                    </span>
                                                    <span class="color-orange">
                                                        @if ($currencyPlacement ?? get_currency_placement() == 'after')
                                                            {{ calculateCashback($course->price) }}
                                                            {{ $currencySymbol ?? get_currency_symbol() }}
                                                        @else
                                                            {{ $currencySymbol ?? get_currency_symbol() }}
                                                            {{ calculateCashback($course->price) }}
                                                        @endif
                                                    </span>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        {{ __('No Course Found') }}
                    @endforelse
                </div>
            </div>
        </div>
    @endif
    {{-- Upcoming Course Section end --}}

    {{-- Personalized Training section start --}}
    @if (!get_option('private_mode') || !auth()->guest())
        @if ($home->consultation_area == 1)
            @if (count($consultationInstructors) > 0)
                <div class="training-section-area angel-elsie-bg overflow-hidden">
                    <div class="row">
                        <div class="col-xxl-5 col-lg-4 p-0">
                            <div class="training-banner will-get-area h-100">
                                <div class="training-overlay section-overlay h-100">
                                    <div class="section-title">
                                        <div class="section-heading-img flex-shrink-0">
                                            <img src="{{ asset('uploads_demo/about_us_general/team-members-heading-img.png') }}"
                                                alt="course">
                                        </div>
                                        <div>
                                            <h3 class="section-heading section-heading-light w-100">
                                                {{ __('Personalized Training') }}</h3>
                                            <p class="section-sub-heading mb-la-24 font-la-14">
                                                {{ __('Consult with your favorite consultant!') }}</p>
                                            <a href="{{ route('consultationInstructorList') }}"
                                                class=" theme-button2 theme-button3 white-color sf-bg-transparent">{{ __('View All') }}<i
                                                    class="fas fa-arrow-right mx-2"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xxl-7 col-lg-8 p-0">
                            <div class="training-slider-all owl-carousel">
                                @foreach ($consultationInstructors as $user)
                                    <!-- Course item start -->
                                    <div class="col-sm-6 col-md-6 col-lg-6 col-xl-3 mt-0 mb-25 w-100">
                                        <div class="card instructor-item position-relative text-center border-0 p-30 px-3">
                                            @php
                                                $percent = $user->hourly_rate && $user->hourly_old_rate ? (($user->hourly_old_rate - $user->hourly_rate) * 100) / $user->hourly_old_rate : 0;
                                            @endphp
                                            @if ($percent && $user->consultation_available == 1)
                                                <span
                                                    class="instructor-price-cutoff badge radius-3 font-12 font-medium position-absolute bg-orange">{{ round(@$percent) }}%
                                                    {{ __('off') }}</span>
                                            @endif
                                            <div class="search-instructor-img-wrap mb-15">
                                                <a href="{{ route('userProfile', $user->id) }}">
                                                    <img src="{{ getImageFile(@$user->image_path) }}" alt="instructor"
                                                        class="fit-image rounded-circle mx-auto"></a>
                                            </div>
                                            <div class="card-body p-0">
                                                <h6 class="card-title"><a
                                                        href="{{ route('userProfile', $user->id) }}">{{ $user->name }}</a>
                                                </h6>
                                                <p class="card-text instructor-designation font-medium mb-15">
                                                    {{ @$user->professional_title }}
                                                    @if (get_instructor_ranking_level($user->badges))
                                                        <span
                                                            class="mx-2">||</span>{{ get_instructor_ranking_level($user->badges) }}
                                                </p>
                                @endif

                                <?php
                                $average_rating = $user->courses->where('average_rating', '>', 0)->avg('average_rating');
                                ?>
                                <div
                                    class="course-rating search-instructor-rating w-100 mb-15 d-inline-flex align-items-center justify-content-center">
                                    <span
                                        class="font-medium font-14 me-2">{{ number_format(@$average_rating, 1) }}</span>
                                    <div class="star-ratings">
                                        <div class="fill-ratings" style="width: {{ $average_rating * 20 }}%">
                                            <span>★★★★★</span>
                                        </div>
                                        <div class="empty-ratings">
                                            <span>★★★★★</span>
                                        </div>
                                    </div>
                                    <span
                                        class="rating-count font-14 ms-2">({{ count(@$user->courses->where('average_rating', '>', 0)) }})</span>
                                </div>
                                <div class="search-instructor-bottom-item font-14 font-medium">
                                    <div
                                        class="search-instructor-award-img d-inline-flex flex-wrap justify-content-center">
                                        @foreach ($user->badges as $badge)
                                            <img src="{{ asset($badge->badge_image) }}" title="{{ $badge->name }}"
                                                alt="{{ $badge->name }}" class="fit-image rounded-circle">
                                        @endforeach
                                    </div>
                                </div>

                                <div class="search-instructor-price d-inline-flex align-items-center mb-15">
                                    @if ($user->consultation_available == 1)
                                        @if ($user->hourly_rate < $user->hourly_old_rate)
                                            <div class="search-instructor-new-price font-medium mx-1">
                                                {{ $user->hourly_rate }}/{{ __('Hour') }}
                                            </div>
                                            <div
                                                class="search-instructor-old-price text-decoration-line-through color-gray font-13 font-medium mx-1">
                                                {{ $user->hourly_old_rate }}</div>
                                        @else
                                            <div class="search-instructor-new-price font-medium mx-1">
                                                {{ $user->hourly_rate }}/{{ __('Hour') }}
                                            </div>
                                            <div
                                                class="search-instructor-old-price text-decoration-line-through color-gray font-13 font-medium mx-1">
                                            </div>
                                        @endif
                                    @else
                                        <div class="search-instructor-new-price font-medium mx-1"></div>
                                        <div
                                            class="search-instructor-old-price text-decoration-line-through color-gray font-13 font-medium mx-1">
                                        </div>
                                    @endif
                                </div>
                                <div class="w-100">
                                    @php $hourly_fee = 0; @endphp
                                    @if ($currencyPlacement ?? get_currency_placement() == 'after')
                                        @php $hourly_fee = $user->hourly_rate . ' ' . $currencySymbol ?? get_currency_symbol() . '/h'; @endphp
                                    @else
                                        @php $hourly_fee = $currencySymbol ?? get_currency_symbol() . ' ' . $user->hourly_rate . '/h'; @endphp
                                    @endif
                                    <button type="button" data-type="{{ $user->available_type }}"
                                        data-booking_instructor_user_id="{{ $user->id }}"
                                        data-hourly_fee="{{ $hourly_fee }}"
                                        data-hourly_rate="{{ $user->hourly_rate }}"
                                        data-get_off_days_route="{{ route('getOffDays', $user->id) }}"
                                        class="theme-btn theme-button1 theme-button3 w-100 bookSchedule"
                                        data-bs-toggle="modal"
                                        data-bs-target="#consultationBookingModal">{{ __('Book Schedule') }}
                                    </button>
                                </div>
                            </div>
                        </div>

                    </div>
                    <!-- Course item end -->
            @endforeach
            </div>
            </div>
            </div>
            </div>
        @endif
    @endif
    @endif
    {{-- Personalized Training section end --}}

    {{-- price section area start --}}
    @if (@$home->subscription_show == 1 && get_option('subscription_mode'))
        <div class="price-area-landing">
            <div class="container">
                <div class="row">
                    <div class="row">
                        <div class="col-lg-6 offset-lg-3">
                            <div class="section-title text-center">
                                <h3 class="section-heading w-50">{{ __('Subscribe Now!') }}</h3>
                                <p class="section-sub-heading mb-19">
                                    {{ __('#Choose a subscription plan and save money!') }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="row">

                        <div class="nav nav-pills justify-content-center align-items-center" id="pills-tab"
                            role="tablist">
                            <span class="plan-switch-month-year-text mx-3"> {{ __('Monthly') }}</span>
                            <div class="price-tab-lang">

                                <span class="nav-item" role="presentation">

                                    <button class="nav-link active" id="pills-monthly-tab" data-bs-toggle="pill"
                                        data-bs-target="#pills-monthly" type="button" role="tab"
                                        aria-controls="pills-monthly" aria-selected="true"></button>
                                </span>
                                <span class="nav-item" role="presentation">

                                    <button class="nav-link" id="pills-yearly-tab" data-bs-toggle="pill"
                                        data-bs-target="#pills-yearly" type="button" role="tab"
                                        aria-controls="pills-yearly" aria-selected="false"></button>
                                </span>
                            </div>
                            <span class="plan-switch-month-year-text mx-3">
                                {{ __('Yearly') }}
                            </span>

                        </div>
                        <div class="tab-content mt-56" id="">
                            @include('frontend-theme-3.home.partial.subscription-home-list')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
    {{-- price section area end --}}

    {{-- Personalized Training section start --}}
    @if ($home->instructor_area == 1)
        <div class="training-section-area angel-elsie-bg overflow-hidden">
            <div class="row">
                <div class="col-xxl-5 col-lg-4 p-0">
                    <div class="training-banner will-get-area h-100">
                        <div class="training-overlay section-overlay h-100">
                            <div class="section-title">
                                <div class="section-heading-img flex-shrink-0">
                                    <img src="{{ getImageFile(get_option('top_instructor_logo')) }}"
                                        alt="{{ __('Instructor') }}">
                                </div>
                                <div>
                                    <h3 class="section-heading section-heading-light w-100">
                                        {{ __(get_option('top_instructor_title')) }}</h3>
                                    <p class="section-sub-heading mb-la-24 font-la-14">
                                        {{ __(get_option('top_instructor_subtitle')) }}</p>
                                    <a href="{{ route('instructor') }}"
                                        class=" theme-button2 theme-button3 sf-bg-transparent white-color">{{ __('View All Instructor') }}<i
                                            class="fas fa-arrow-right mx-2"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xxl-7 col-lg-8 p-0">
                    <div class="training-slider-all owl-carousel">
                        @foreach ($instructors as $user)
                            <div class="col-sm-6 col-md-6 col-lg-6 col-xl-3 mt-0 mb-25 w-100">
                                <div class="card instructor-item position-relative text-center border-0 p-30 px-3">
                                    @php
                                        $percent = $user->hourly_rate && $user->hourly_old_rate ? (($user->hourly_old_rate - $user->hourly_rate) * 100) / $user->hourly_old_rate : 0;
                                    @endphp
                                    @if ($percent && $user->consultation_available == 1)
                                        <span
                                            class="instructor-price-cutoff badge radius-3 font-12 font-medium position-absolute bg-orange">{{ round(@$percent) }}%
                                            {{ __('off') }}</span>
                                    @endif
                                    <div class="search-instructor-img-wrap mb-15">
                                        <a href="{{ route('userProfile', $user->id) }}">
                                            <img src="{{ getImageFile(@$user->image_path) }}" alt="instructor"
                                                class="fit-image rounded-circle mx-auto"></a>
                                    </div>
                                    <div class="card-body p-0">
                                        <h6 class="card-title"><a
                                                href="{{ route('userProfile', $user->id) }}">{{ $user->name }}</a>
                                        </h6>
                                        <p class="card-text instructor-designation font-medium mb-15">
                                            {{ @$user->professional_title }}
                                            @if (get_instructor_ranking_level($user->badges))
                                                <span
                                                    class="mx-2">||</span>{{ get_instructor_ranking_level($user->badges) }}
                                        </p>
                        @endif

                        <?php
                        $average_rating = $user->courses->where('average_rating', '>', 0)->avg('average_rating');
                        ?>
                        <div
                            class="course-rating search-instructor-rating w-100 mb-15 d-inline-flex align-items-center justify-content-center">
                            <span class="font-medium font-14 me-2">{{ number_format(@$average_rating, 1) }}</span>
                            <div class="star-ratings">
                                <div class="fill-ratings" style="width: {{ $average_rating * 20 }}%">
                                    <span>★★★★★</span>
                                </div>
                                <div class="empty-ratings">
                                    <span>★★★★★</span>
                                </div>
                            </div>
                            <span
                                class="rating-count font-14 ms-2">({{ count(@$user->courses->where('average_rating', '>', 0)) }})</span>
                        </div>
                        <div class="search-instructor-bottom-item font-14 font-medium">
                            <div class="search-instructor-award-img d-inline-flex flex-wrap justify-content-center">
                                @foreach ($user->badges as $badge)
                                    <img src="{{ asset($badge->badge_image) }}" title="{{ $badge->name }}"
                                        alt="{{ $badge->name }}" class="fit-image rounded-circle">
                                @endforeach
                            </div>
                        </div>

                        <div class="search-instructor-price d-inline-flex align-items-center mb-15">
                            @if ($user->consultation_available == 1)
                                @if ($user->hourly_rate < $user->hourly_old_rate)
                                    <div class="search-instructor-new-price font-medium mx-1">
                                        {{ $user->hourly_rate }}/{{ __('Hour') }}
                                    </div>
                                    <div
                                        class="search-instructor-old-price text-decoration-line-through color-gray font-13 font-medium mx-1">
                                        {{ $user->hourly_old_rate }}</div>
                                @else
                                    <div class="search-instructor-new-price font-medium mx-1">
                                        {{ $user->hourly_rate }}/{{ __('Hour') }}
                                    </div>
                                    <div
                                        class="search-instructor-old-price text-decoration-line-through color-gray font-13 font-medium mx-1">
                                    </div>
                                @endif
                            @else
                                <div class="search-instructor-new-price font-medium mx-1"></div>
                                <div
                                    class="search-instructor-old-price text-decoration-line-through color-gray font-13 font-medium mx-1">
                                </div>
                            @endif
                        </div>
                        <div class="w-100">
                            <a href="{{ route('userProfile', $user->id) }}"
                                class="green-theme-btn theme-button1 w-100">{{ __('View Profile') }}</a>
                        </div>
                    </div>
                </div>

            </div>
    @endforeach
    </div>
    </div>
    </div>
    </div>
    @endif
    {{-- Personalized Training section end --}}

    {{-- sass price section area start --}}
    @if (@$home->saas_show == 1 && get_option('saas_mode'))
        <div class="price-area-landing">
            <div class="container">
                <div class="row">
                    <div class="row">
                        <div class="col-lg-6 offset-lg-3">
                            <div class="section-title text-center">
                                <h3 class="section-heading w-50">{{ __('Saas Plan') }}</h3>
                                <p class="section-sub-heading mb-19">{{ __('#Choose a saas plan and save money!') }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="row">

                        <ul class="nav nav-pills saas-plan-instructor-organization-nav radius-8 mb-4" id="home2SassTab"
                            role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="instructor-tab" data-bs-toggle="tab"
                                    data-bs-target="#instructor-tab-pane" type="button" role="tab"
                                    aria-controls="instructor-tab-pane"
                                    aria-selected="true">{{ __('Instructor') }}</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="organization-tab" data-bs-toggle="tab"
                                    data-bs-target="#organization-tab-pane" type="button" role="tab"
                                    aria-controls="organization-tab-pane"
                                    aria-selected="false">{{ __('Organization') }}</button>
                            </li>
                        </ul>
                        <div class="tab-content" id="home2SassTabContent">
                            <!-- Instructor -->
                            @include('frontend-theme-3.home.partial.instructor-saas-home-list')

                            <!-- Organization -->
                            @include('frontend-theme-3.home.partial.organization-saas-home-list')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
    {{-- sass price section area end --}}

    {{-- Customers Say section start --}}
    @if ($home->customer_says_area == 1)
        <div class=" gradient-bg ">
            <div class="section-overlay customers-says-landing">
                <div class="container">
                    <div class="row">
                        <div class="col-xl-4 offset-xl-4 col-lg-6 offset-lg-3">
                            <div class="section-title text-center mb-42">
                                <div class="section-heading-img">
                                    <img src="{{ getImageFile(get_option('customer_say_logo')) }}"
                                        alt="{{ __('achievement') }}">
                                </div>
                                <h3 class="section-heading section-heading-light mx-auto w-100">
                                    {{ __(get_option('customer_say_title')) }}
                                </h3>
                            </div>
                        </div>
                    </div>
                    <div class="row testimonial-content-wrap">
                        <div class="col-lg-6 mb-4">
                            <div class="testimonial-item testimonial-landing-2">
                                <div class="d-flex flex-column flex-md-row gap-4">
                                    <div class="d-flex flex-column align-items-center align-items-md-start">
                                        <img class="rectangle-2-img fit-image object-fit-cover"
                                            src="{{ getImageFile(get_option('customer_say_first_image')) }}"
                                            alt="{{ __(get_option('customer_say_first_name')) }}">
                                        <div
                                            class="flex-grow-1 testimonial-content landing-2 d-flex flex-column align-items-center align-items-md-start">
                                            <h6 class="font-16">{{ __(get_option('customer_say_first_name')) }}</h6>
                                            <p class="font-13 font-medium">
                                                {{ __(get_option('customer_say_first_position')) }}</p>
                                        </div>
                                    </div>
                                    <div>
                                        <div
                                            class="testimonial-top-content d-flex align-items-center justify-content-md-start justify-content-center">
                                            <div class="flex-shrink-0 quote-img-wrap">
                                                <img class="wh-30"
                                                    src="{{ asset('frontend/assets/img/icons-svg/quote.svg') }}"
                                                    alt="quote">
                                            </div>
                                        </div>
                                        <div
                                            class="testimonial-bottom-content d-flex flex-column align-items-center align-items-md-start">
                                            <h6 class="text-white">
                                                {{ __(get_option('customer_say_first_comment_title')) }}</h6>
                                            <p class="font-17">
                                                {{ __(get_option('customer_say_first_comment_description')) }}</p>
                                            <div class=" d-flex align-items-center">
                                                <ul class="rating-list d-flex align-items-center me-2">

                                                    <div
                                                        class="search-instructor-rating w-100 d-inline-flex align-items-center justify-content-center">
                                                        <div class="star-ratings">
                                                            <div class="fill-ratings"
                                                                style="width: {{ (float) get_option('customer_say_first_comment_rating_star') * 20 }}%">
                                                                <span>★★★★★</span>
                                                            </div>
                                                            <div class="empty-ratings">
                                                                <span>★★★★★</span>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 mb-4">
                            <div class="testimonial-item testimonial-landing-2">
                                <div class="d-flex flex-column flex-md-row gap-4">
                                    <div class="d-flex flex-column align-items-center align-items-md-start">
                                        <img class="rectangle-2-img fit-image object-fit-cover"
                                            src="{{ getImageFile(get_option('customer_say_second_image')) }}"
                                            alt="{{ __(get_option('customer_say_second_name')) }}">
                                        <div
                                            class="flex-grow-1 testimonial-content landing-2 d-flex flex-column align-items-center align-items-md-start">
                                            <h6 class="font-16">{{ __(get_option('customer_say_second_name')) }}</h6>
                                            <p class="font-13 font-medium">
                                                {{ __(get_option('customer_say_second_position')) }}</p>
                                        </div>
                                    </div>
                                    <div>
                                        <div
                                            class="testimonial-top-content d-flex align-items-center justify-content-md-start justify-content-center">
                                            <div class="flex-shrink-0 quote-img-wrap">
                                                <img class="wh-30"
                                                    src="{{ asset('frontend/assets/img/icons-svg/quote.svg') }}"
                                                    alt="quote">
                                            </div>
                                        </div>
                                        <div
                                            class="testimonial-bottom-content d-flex flex-column align-items-center align-items-md-start">
                                            <h6 class="text-white">
                                                {{ __(get_option('customer_say_second_comment_title')) }}</h6>
                                            <p class="font-17">
                                                {{ __(get_option('customer_say_second_comment_description')) }}</p>
                                            <div class=" d-flex align-items-center">
                                                <ul class="rating-list d-flex align-items-center me-2">

                                                    <div
                                                        class="search-instructor-rating w-100 d-inline-flex align-items-center justify-content-center">
                                                        <div class="star-ratings">
                                                            <div class="fill-ratings"
                                                                style="width: {{ (float) get_option('customer_say_second_comment_rating_star') * 20 }}%">
                                                                <span>★★★★★</span>
                                                            </div>
                                                            <div class="empty-ratings">
                                                                <span>★★★★★</span>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 mb-4">
                            <div class="testimonial-item testimonial-landing-2">
                                <div class="d-flex flex-column flex-md-row gap-4">
                                    <div class="d-flex flex-column align-items-center align-items-md-start">
                                        <img class="rectangle-2-img fit-image object-fit-cover"
                                            src="{{ getImageFile(get_option('customer_say_third_image')) }}"
                                            alt="{{ __(get_option('customer_say_third_name')) }}">
                                        <div
                                            class="flex-grow-1 testimonial-content landing-2 d-flex flex-column align-items-center align-items-md-start">
                                            <h6 class="font-16">{{ __(get_option('customer_say_third_name')) }}</h6>
                                            <p class="font-13 font-medium">
                                                {{ __(get_option('customer_say_third_position')) }}</p>
                                        </div>
                                    </div>
                                    <div>
                                        <div
                                            class="testimonial-top-content d-flex align-items-center justify-content-md-start justify-content-center">
                                            <div class="flex-shrink-0 quote-img-wrap">
                                                <img class="wh-30"
                                                    src="{{ asset('frontend/assets/img/icons-svg/quote.svg') }}"
                                                    alt="quote">
                                            </div>
                                        </div>
                                        <div
                                            class="testimonial-bottom-content d-flex flex-column align-items-center align-items-md-start">
                                            <h6 class="text-white">
                                                {{ __(get_option('customer_say_third_comment_title')) }}</h6>
                                            <p class="font-17">
                                                {{ __(get_option('customer_say_third_comment_description')) }}</p>
                                            <div class=" d-flex align-items-center">
                                                <ul class="rating-list d-flex align-items-center me-2">

                                                    <div
                                                        class="search-instructor-rating w-100 d-inline-flex align-items-center justify-content-center">
                                                        <div class="star-ratings">
                                                            <div class="fill-ratings"
                                                                style="width: {{ (float) get_option('customer_say_third_comment_rating_star') * 20 }}%">
                                                                <span>★★★★★</span>
                                                            </div>
                                                            <div class="empty-ratings">
                                                                <span>★★★★★</span>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 mb-4">
                            <div class="testimonial-item testimonial-landing-2">
                                <div class="d-flex flex-column flex-md-row gap-4">
                                    <div class="d-flex flex-column align-items-center align-items-md-start">
                                        <img class="rectangle-2-img fit-image object-fit-cover"
                                            src="{{ getImageFile(get_option('customer_say_fourth_image')) }}"
                                            alt="{{ __(get_option('customer_say_fourth_name')) }}">
                                        <div
                                            class="flex-grow-1 testimonial-content landing-2 d-flex flex-column align-items-center align-items-md-start">
                                            <h6 class="font-16">{{ __(get_option('customer_say_fourth_name')) }}</h6>
                                            <p class="font-13 font-medium">
                                                {{ __(get_option('customer_say_fourth_position')) }}</p>
                                        </div>
                                    </div>
                                    <div>
                                        <div
                                            class="testimonial-top-content d-flex align-items-center justify-content-md-start justify-content-center">
                                            <div class="flex-shrink-0 quote-img-wrap">
                                                <img class="wh-30"
                                                    src="{{ asset('frontend/assets/img/icons-svg/quote.svg') }}"
                                                    alt="quote">
                                            </div>
                                        </div>
                                        <div
                                            class="testimonial-bottom-content d-flex flex-column align-items-center align-items-md-start">
                                            <h6 class="text-white">
                                                {{ __(get_option('customer_say_fourth_comment_title')) }}</h6>
                                            <p class="font-17">
                                                {{ __(get_option('customer_say_fourth_comment_description')) }}</p>
                                            <div class=" d-flex align-items-center">
                                                <ul class="rating-list d-flex align-items-center me-2">

                                                    <div
                                                        class="search-instructor-rating w-100 d-inline-flex align-items-center justify-content-center">
                                                        <div class="star-ratings">
                                                            <div class="fill-ratings"
                                                                style="width: {{ (float) get_option('customer_say_fourth_comment_rating_star') * 20 }}%">
                                                                <span>★★★★★</span>
                                                            </div>
                                                            <div class="empty-ratings">
                                                                <span>★★★★★</span>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
    {{-- Customers Say section end --}}

    {{-- Frequentyl ask questions section start --}}
    @if ($home->faq_area == 1)
        <section class="faq-area home-page-faq-area sf-home-page-faq-area section-t-space pb-0">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6 offset-lg-3 faq-landing">
                        <div class="section-title text-center">
                            <h3 class="section-heading w-50 w-lg">{{ __(get_option('faq_title')) }}</h3>
                            <p class="section-sub-heading">{{ __(get_option('faq_subtitle')) }}</p>
                        </div>
                    </div>
                </div>
                @php
                    $totalFaqs = count($faqQuestions);
                    $half = ceil($totalFaqs / 2);
                @endphp
                <div class="row align-items-start">
                    <div class="col-md-6 col-lg-6 col-xl-5 offset-xl-1 faq-landing">

                        <div class="accordion" id="accordionFaq">
                            @foreach ($faqQuestions->take($half) as $key => $faqQuestion)
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="heading_{{ $key }}">
                                        <button
                                            class="accordion-button font-medium font-18 {{ $key == 0 ? '' : 'collapsed' }}"
                                            type="button" data-bs-toggle="collapse"
                                            data-bs-target="#collapse_{{ $key }}"
                                            aria-expanded="{{ $key == 0 ? 'true' : 'false' }}"
                                            aria-controls="collapse_{{ $key }}">
                                            {{ $key + 1 }}. {{ __($faqQuestion->question) }}
                                        </button>
                                    </h2>
                                    <div id="collapse_{{ $key }}"
                                        class="accordion-collapse collapse {{ $key == 0 ? 'show' : '' }}"
                                        aria-labelledby="heading_{{ $key }}" data-bs-parent="#accordionFaq">
                                        <div class="accordion-body">
                                            {{ __($faqQuestion->answer) }}
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-6 col-xl-5">

                        <div class="accordion" id="accordionFaq-2">
                            @foreach ($faqQuestions->skip($half) as $key => $faqQuestion)
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="heading_{{ $key + $half }}">
                                        <button
                                            class="accordion-button font-medium font-18 {{ $key + $half == 0 ? '' : 'collapsed' }}"
                                            type="button" data-bs-toggle="collapse"
                                            data-bs-target="#collapse_{{ $key + $half }}"
                                            aria-expanded="{{ $key + $half == 0 ? 'true' : 'false' }}"
                                            aria-controls="collapse_{{ $key + $half }}">
                                            {{ $key + 1 }}. {{ __($faqQuestion->question) }}
                                        </button>
                                    </h2>
                                    <div id="collapse_{{ $key + $half }}"
                                        class="accordion-collapse collapse {{ $key + $half == 0 ? 'show' : '' }}"
                                        aria-labelledby="heading_{{ $key + $half }}" data-bs-parent="#accordionFaq-2">
                                        <div class="accordion-body">
                                            {{ __($faqQuestion->answer) }}
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="pb-5 sf-sponser-section">
            <div class="container">
                <div class="row client-logo-area">
                    @foreach ($clients as $client)
                        <div class="col">
                            <div class="client-logo-item text-center">
                                <img src="{{ getImageFile($client->image_path) }}" alt="{{ $client->name }}">
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif
    {{-- Frequentyl ask questions section end --}}

    @include('frontend.home.partial.consultation-booking-schedule-modal')

    <!-- New Video Player Modal Start-->
    <div class="modal fade VideoTypeModal" id="newVideoPlayerModal" tabindex="-1" aria-labelledby="newVideoPlayerModal"
        aria-hidden="true">

        <div class="modal-header border-bottom-0">
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><span class="iconify"
                    data-icon="akar-icons:cross"></span></button>
        </div>
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="video-player-area">
                        <!-- HTML 5 Video -->
                        <video id="player" playsinline controls
                            data-poster="{{ getImageFile(get_option('become_instructor_video_preview_image')) }}"
                            controlsList="nodownload">
                            <source src="{{ getVideoFile(get_option('become_instructor_video')) }}" type="video/mp4">
                        </video>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- New Video Player Modal End-->

@endsection

@push('style')
    <!-- Video Player css -->
    <link rel="stylesheet" href="{{ asset('frontend/assets/vendor/video-player/plyr.css') }}">
@endpush

@push('script')
    <!--Hero text effect-->
    <script src="{{ asset('frontend/assets/js/course/addToCart.js') }}"></script>
    <script src="{{ asset('frontend/assets/js/course/addToWishlist.js') }}"></script>
    <script src="{{ asset('frontend/assets/js/custom/booking.js') }}"></script>

    <!-- Video Player js -->
    <script src="{{ asset('frontend/assets/vendor/video-player/plyr.js') }}"></script>

    <!-- frontend-theme-2 Custom scripts for this template -->
    <script src="{{ asset('frontend-theme-3/assets/js/swiper.js') }}"></script>
    <!--  -->
    <script src="{{ asset('frontend-theme-3/assets/js/main.js') }}"></script>

    <!-- frontend-theme-2 Custom scripts for this template -->
    <script src="{{ asset('frontend-theme-2/assets/js/main.js') }}"></script>

    <script>
        const zai_player = new Plyr('#player');
    </script>
    <!-- Video Player js -->
@endpush

</body>

</html>
