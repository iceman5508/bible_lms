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
    <!-- landing page css -->
    <link rel="stylesheet" href="{{ asset('frontend-theme-2/assets/css/style.css') }}">
    <!-- landing_common page css -->
    <link rel="stylesheet" href="{{ asset('frontend-theme-2/assets/css/common.css') }}">
@endpush

@section('content')
    <!-- Header Start -->
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
                        <div class="landing-banner-text text-center p-0">
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
                            <p class="section-sub-heading landing-sub-heading">{{ __(@$home->banner_subtitle) }}</p>
                            <div class="d-flex justify-content-center align-items-center flex-wrap sf-g-10">
                                <a href="{{ $home->banner_first_button_link }}"
                                    class="tour-btu theme-btn heading-2-bg white-color">
                                    {{ __($home->banner_first_button_name) }} <i class="fas fa-arrow-right mx-2"></i></a>
                                <a href="{{ $home->banner_second_button_link }}"
                                    class="theme-btn theme-button1">{{ __($home->banner_second_button_name) }} <i
                                        class="fas fa-arrow-right mx-2"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Header End -->

    <!-- Special Feature Area Start -->
    <div class="will-get-area sf-mt-n-1 {{ @$home->special_feature_area == 1 ? '' : 'd-none' }}">
        <div class="section-overlay landing-overlay">

            <div class="container">
                <div class="row">
                    <div class="col-lg-6 offset-lg-3">
                        <div class="section-title-part section-title-w">
                            <h3 class="section-heading section-heading-light mx-auto w-100 text-center sf-max-w-350">
                                {{ __(get_option('home_special_feature_title')) }}</h3>
                        </div>
                    </div>
                </div>
                <div class="row justify-content-center sf-rg-10">
                    <div class="col-md-4 col-sm-6">
                        <div class="align-items-center d-flex flex-column flex-lg-row single-get-item sf-max-w-440 m-auto">
                            <div class="flex-shrink-0 feature-img-wrap">
                                <img src="{{ getImageFile(get_option('home_special_feature_first_logo')) }}"
                                    alt="feature">
                            </div>
                            <div class="feature-content flex-grow-1 mx-3 text-center text-lg-start rtl-text">
                                <h6>{{ __(get_option('home_special_feature_first_title')) }}</h6>
                                <p>{{ __(get_option('home_special_feature_first_subtitle')) }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-6">
                        <div class="align-items-center d-flex flex-column flex-lg-row single-get-item sf-max-w-440 m-auto">
                            <div class="flex-shrink-0 feature-img-wrap">
                                <img src="{{ getImageFile(get_option('home_special_feature_second_logo')) }}"
                                    alt="feature">
                            </div>
                            <div class="feature-content flex-grow-1 mx-3 text-center text-lg-start rtl-text">
                                <h6>{{ __(get_option('home_special_feature_second_title')) }}</h6>
                                <p>{{ __(get_option('home_special_feature_second_subtitle')) }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-6">
                        <div class="align-items-center d-flex flex-column flex-lg-row single-get-item sf-max-w-440 m-auto">
                            <div class="flex-shrink-0 feature-img-wrap">
                                <img src="{{ getImageFile(get_option('home_special_feature_third_logo')) }}"
                                    alt="feature">
                            </div>
                            <div class="feature-content flex-grow-1 mx-3 text-center text-lg-start rtl-text">
                                <h6>{{ __(get_option('home_special_feature_third_title')) }}</h6>
                                <p>{{ __(get_option('home_special_feature_third_subtitle')) }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Special Feature Area End -->

    @if (!get_option('private_mode') || !auth()->guest())
        @if ($home->courses_area == 1)
            <!-- All Courses Area Start -->
            <div class="courses-area-section">
                <div class="container border-bottom">
                    <div class="row sf-rg-10">
                        <div class="col-lg-5">
                            <div class="section-left-title-with-btn">
                                <div class="section-title">
                                    <div class="section-heading-img">
                                        <img src="{{ getImageFile(get_option('course_logo')) }}" alt="course" />
                                    </div>
                                    <div>
                                        <h3 class="sf-fs-41 sf-fw-500 sf-lh-44 sf-title-color-1 sf-pb-12">
                                            {{ __(get_option('course_title')) }}</h3>
                                        <p class="sf-fs-14 sf-fw-400 sf-lh-22 sf-body-font-color sf-pb-24">
                                            {{ __(get_option('course_subtitle')) }}</p>
                                    </div>
                                </div>
                                <a href="{{ route('courses') }}"
                                    class="sf-bd-c-theme-color sf-bd-two sf-hover-bg-theme-color sf-hover-color-white sf-px-15 sf-py-11 theme-btn sf-text-theme-color">{{ __('View All') }}
                                    <i class="fas fa-arrow-right mx-2"></i> </a>
                            </div>
                        </div>
                        <div class="col-lg-7">
                            @if (count($featuredCourses))
                                <div class="selection-course-slider owl-carousel">
                                    @foreach ($featuredCourses as $course)
                                        @php
                                            $userRelation = getUserRoleRelation($course->user);
                                        @endphp
                                        <div class="col-12 col-lg-6 w-100">
                                            @include('frontend-theme-2.partials.course')
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                {{ __('No Course Found') }}
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <!-- All Courses Area END -->
        @endif

        @if ($home->category_courses_area == 1)
            <!-- Board Selection of Courses Area Start -->
            <div class="course-category-area ">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="category-title-part ">
                                <div class="text-center">

                                    <div class="mb-2">
                                        <img src="{{ getImageFile(get_option('category_course_logo')) }}"
                                            alt="Category Course">
                                    </div>
                                    <div>
                                        <h3 class="section-heading w-100">{{ __(get_option('category_course_title')) }}
                                        </h3>
                                        <p class="section-sub-heading mb-la-24">
                                            {{ __(get_option('category_course_subtitle')) }}</p>
                                    </div>
                                </div>
                                <div class="category-tab-area">
                                    <ul class="nav nav-pills mb-category-50 justify-content-center sf-rg-10"
                                        id="pills-tab" role="tablist">
                                        @foreach ($featureCategories as $key => $category)
                                            <li class="nav-item" role="presentation">
                                                <button class="nav-link  {{ $key == 0 ? 'active' : '' }}"
                                                    id="{{ $category->slug }}-tab" data-bs-toggle="pill"
                                                    data-bs-target="#{{ $category->slug }}" type="button"
                                                    role="tab" aria-controls="{{ $category->slug }}"
                                                    aria-selected="{{ $key == 0 ? 'true' : 'false' }}">{{ __($category->name) }}</button>
                                            </li>
                                        @endforeach
                                    </ul>
                                    <div class="tab-content" id="pills-tabContent">
                                        @foreach ($featureCategories as $key => $category)
                                            <div class="tab-pane fade {{ $key == 0 ? 'show active' : '' }}"
                                                id="{{ $category->slug }}" role="tabpanel"
                                                aria-labelledby="{{ $category->slug }}-tab">
                                                <div class="row">
                                                    <!-- Course item start -->
                                                    @foreach ($category->courses->take(4) as $course)
                                                        @php
                                                            $userRelation = getUserRoleRelation($course->user);
                                                        @endphp
                                                        <div class="col-lg-6">
                                                            @include('frontend-theme-2.partials.course-card-horizontal')
                                                        </div>
                                                    @endforeach
                                                    <!-- Course item end -->
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="d-flex justify-content-center mt-25">
                                    <a href="{{ route('courses') }}"
                                        class="sf-bd-c-theme-color sf-bd-two sf-hover-bg-theme-color sf-hover-color-white sf-px-15 sf-py-11 theme-btn sf-text-theme-color">{{ __('View All') }}
                                        <i class="fas fa-arrow-right mx-2"></i> </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Board Selection of Courses Area End -->
        @endif

        @if (isAddonInstalled('LMSZAIPRODUCT'))
            @if ($home->product_area == 1)
                <div class="extensive-area position-relative">
                    <div class="container">
                        <div class="row">
                            <div class="col-lg-10 offset-lg-1">
                                <div class="gradient-bg-lan">

                                    <div class="extensive-banner-area">
                                        <img class="w-60px" src="{{ getImageFile(get_option('product_section_logo')) }}"
                                            alt="{{ __('Product') }}">
                                        <h3 class="section-heading section-heading-light mx-auto w-100 text-center">
                                            {{ __(get_option('product_section_title')) }} @if (env('LOGIN_HELP') == 'active')
                                                <span class="color-deep-orange font-18">(Addon)</span>
                                            @endif
                                        </h3>
                                        <p>{{ __(get_option('product_section_subtitle')) }}</p>
                                        <a href="{{ route('lms_product.frontend.list') }}"
                                            class=" theme-button2 theme-button3 heading-2-bg white-color">{{ __('View All') }}
                                            <i class="fas fa-arrow-right mx-2"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- Extensive Product slider start --}}

                    <div class="exten-slider-area border-bottom">
                        <div class="single-exten-slider owl-carousel">
                            @foreach ($products as $product)
                                <div class="col-lg-6 w-100">
                                    <div
                                        class="align-items-center bg-white border-0 card course-item-upcoming exten-card-slider flex-column flex-xl-row flex-row min-course-h p-3 radius-3">
                                        <div
                                            class="overflow-hidden min-h-auto sf-max-w-md-270 w-100 align-self-stretch flex-grow-1 snow-white-bg radius-5 single-exten-img">
                                            <img src="{{ getImageFile($product->thumbnail_path) }}"
                                                alt="{{ $product->title }}">
                                        </div>
                                        <div class="card-body">
                                            <h5 class="card-title course-title"><a
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
                                                            data-route="{{ route('student.addToCart') }}">{{ __('Add To Cart') }}</button>
                                                    @else
                                                        <button type="button"
                                                            class="bg-warning p-2 rounded text-white w-75 mt-25">{{ __('Out of stock') }}</button>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Extensive Product slider end --}}
                </div>
            @endif
        @endif

        @if ($home->bundle_area == 1)
            @if (count($bundles) > 0)
                <!-- Latest Courses bundles Area Start -->
                <div class="latest-courses-area courses-area-landing">
                    <div class="container border-bottom ">
                        <div class="row">
                            <div class="col-lg-12">
                                <div
                                    class="section-left-title-with-btn d-flex justify-content-between align-items-end align-items-center">
                                    <div class="section-title section-title-left d-flex align-items-start">
                                        <div class="section-heading-img me-3">
                                            <img src="{{ getImageFile(get_option('bundle_course_logo')) }}"
                                                alt="{{ 'Bundle' }}">
                                        </div>
                                        <div>
                                            <h3 class="section-heading heading-2">
                                                {{ __(get_option('bundle_course_title')) }}</h3>
                                            <p class="section-sub-heading mb-0">
                                                {{ __(get_option('bundle_course_subtitle')) }}</p>
                                        </div>
                                    </div>
                                    <a href="{{ route('bundles') }}"
                                        class=" theme-button2 theme-button3">{{ __('View All') }} <i
                                            class="fas fa-arrow-right mx-2"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="latest-courses-slider owl-carousel">
                            @foreach ($bundles as $bundle)
                                @php
                                    $relation = getUserRoleRelation($bundle->user);
                                @endphp
                                <div class="col-12 col-lg-6 w-100">
                                    <div class="card course-item course-item-upcoming border-0 radius-3 bg-white">
                                        <div class="course-img-wrap overflow-hidden">
                                            <a href="{{ route('bundle-details', [$bundle->slug]) }}">
                                                <img src="{{ getImageFile($bundle->image) }}" alt="course"
                                                    class="img-fluid">
                                            </a>
                                        </div>
                                        <div class="card-body">
                                            <h5 class="card-title course-title"><a
                                                    href="{{ route('bundle-details', [$bundle->slug]) }}">{{ Str::limit($bundle->name, 40) }}</a>
                                            </h5>
                                            <p class="card-text instructor-name-certificate font-medium font-14">
                                                <a
                                                    href="{{ route('userProfile', $bundle->user->id) }}">{{ @$bundle->user->$relation->name }}</a>
                                                @if (@$bundle->user->$relation->level_id != null)
                                                    | {{ @$bundle->user->$relation->ranking_level->name }}
                                                @endif
                                            </p>
                                            <div class="course-item-bottom">
                                                <div class="instructor-bottom-item font-14 font-semi-bold mb-15">
                                                    {{ __('Courses') }}: <span
                                                        class="heading-2">{{ @$bundle->bundleCourses->count() }}</span>
                                                </div>
                                                <div class="instructor-bottom-item font-14 font-semi-bold">
                                                    <div class="instructor-bottom-item font-14 font-semi-bold">
                                                        {{ __('Price') }}:
                                                        <span class="color-hover">
                                                            @if ($currencyPlacement == 'after')
                                                                {{ $bundle->price }} {{ $currencySymbol }}
                                                            @else
                                                                {{ $currencySymbol }} {{ $bundle->price }}
                                                            @endif
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <!-- Latest Courses bundles Area End -->
            @endif
        @endif
    @endif

    @if ($home->upcoming_courses_area == 1)
        <!-- All Courses Area Start -->
        <div class="upcoming-section-area angel-elsie-bg courses-area-landing">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div
                            class="section-left-title-with-btn d-flex justify-content-between align-items-end align-items-center">
                            <div class="section-title section-title-left d-flex align-items-start">
                                <div class="section-heading-img me-3">
                                    <img src="{{ getImageFile(get_option('upcoming_course_logo')) }}" alt="Course">
                                </div>
                                <div>
                                    <h3 class="section-heading heading-2">{{ __(get_option('upcoming_course_title')) }}
                                    </h3>
                                    <p class="section-sub-heading mb-0">{{ __(get_option('upcoming_course_subtitle')) }}
                                    </p>
                                </div>
                            </div>
                            <a href="{{ route('courses') }}" class=" theme-button2 theme-button3">{{ __('View All') }}
                                <i class="fas fa-arrow-right mx-2"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="row mt-45 sf-rg-16">
                    @if (count($upcomingCourses))
                        @php
                            $course = $upcomingCourses->first();
                            $userRelation = getUserRoleRelation($course->user);
                        @endphp
                        <div class="col-12 col-lg-6">
                            @include('frontend-theme-2.partials.upcoming-course')
                        </div>
                        <div class="col-12 col-lg-6">
                            <div class="row sf-rg-16">
                                @foreach ($upcomingCourses->skip(1)->take(2) as $course)
                                    @php
                                        $userRelation = getUserRoleRelation($course->user);
                                    @endphp
                                    <div class="col-lg-12">
                                        <div
                                            class="card course-item course-item-upcoming border-0 radius-3 mb-0 bg-white flex-row min-course-h align-items-center course-landing-card">
                                            <div
                                                class="course-img-wrap overflow-hidden min-h-auto sf-max-w-md-327 align-self-stretch sf-max-md-h-228">
                                                <span
                                                    class="course-tag badge radius-3 font-12 font-medium position-absolute color-yellow-bg heading-2">{{ __('Upcoming') }}</span>
                                                <a href="{{ route('course-details', $course->slug) }}">
                                                    <img src="{{ getImageFile($course->image_path) }}" alt="course"
                                                        class="h-100 object-fit-cover w-100">
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
                                                        @elseif(get_option('cashback_system_mode', 0))
                                                            <div
                                                                class="bg-light-purple d-flex font-12 justify-content-between mt-2 p-1 rounded">
                                                                <span class="color-para">
                                                                    {{ __('Cashback') }}:
                                                                </span>
                                                                <span class="color-orange">
                                                                    0
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
                    @else
                        {{ __('No Course Found') }}
                    @endif
                </div>
            </div>
        </div>
        <!-- All Courses Area END -->
    @endif


    @if (!get_option('private_mode') || !auth()->guest())
        @if ($home->consultation_area == 1)
            @if (count($consultationInstructors) > 0)
                <!-- One to One Consultation Area Start -->
                <div class="personalized-area upcoming-section-area">
                    <div class="container">
                        <div class="row sf-rg-24">

                            <div class="align-items-center col-lg-4 col-md-6 d-flex">
                                <div class="section-left-title-with-btn ">
                                    <div class="section-title d-flex flex-wrap gap-4 ">
                                        <div class="section-heading-img flex-shrink-0">
                                            <img src="{{ asset('uploads_demo/about_us_general/team-members-heading-img.png') }}"
                                                alt="Consultant">
                                        </div>
                                        <div>
                                            <h3 class="section-heading w-100">{{ __('Personalized Training') }}</h3>
                                            <p class="section-sub-heading mb-la-24 font-la-14">
                                                {{ __('Consult with your favorite consultant!') }}</p>
                                            <a href="{{ route('consultationInstructorList') }}"
                                                class="theme-btn theme-button1 theme-button3">{{ __('View All') }}
                                                <i class="fas fa-arrow-right mx-2"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @foreach ($consultationInstructors as $user)
                                <div class="col-lg-4 col-sm-6">
                                    <div class="training-card position-relative ">
                                        <img class="trainer-img fit-image" src="{{ getImageFile(@$user->image_path) }}"
                                            alt="{{ __('instructor') }}">
                                        <div class="hover-trainer-info">
                                            <h5 class="trainer-name"><a
                                                    href="{{ route('userProfile', $user->id) }}">{{ $user->name }}</a>
                                            </h5>
                                            <p class="trainer-position"> {{ @$user->professional_title }}
                                                @if (get_instructor_ranking_level($user->badges))
                                                    <span
                                                        class="mx-2">||</span>{{ get_instructor_ranking_level($user->badges) }}
                                                @endif
                                            </p>

                                            <?php
                                            $average_rating = $user->courses->where('average_rating', '>', 0)->avg('average_rating');
                                            ?>
                                            <div class="course-rating d-flex align-items-center">
                                                <span
                                                    class="font-medium font-14 me-2 white-color">{{ number_format(@$average_rating, 1) }}</span>
                                                <ul class="rating-list d-flex align-items-center me-2">
                                                    <li class="">

                                                    </li>
                                                    <div
                                                        class="search-instructor-rating w-100 d-inline-flex align-items-center justify-content-center">
                                                        <div class="star-ratings">
                                                            <div class="fill-ratings"
                                                                style="width: {{ $average_rating * 20 }}%">
                                                                <span>★★★★★</span>
                                                            </div>
                                                            <div class="empty-ratings">
                                                                <span>★★★★★</span>
                                                            </div>
                                                        </div>
                                                    </div>

                                </ul>
                                <span
                                    class="rating-count font-14 white-color">({{ count(@$user->courses->where('average_rating', '>', 0)) }})</span>
                            </div>
                            <div class="trainer-base">
                                @foreach ($user->badges as $badge)
                                    <img src="{{ asset($badge->badge_image) }}" title="{{ $badge->name }}"
                                        alt="{{ $badge->name }}">
                                @endforeach
                            </div>
                            @if ($user->consultation_available == 1)
                                <p class="trainer-position">{{ $user->hourly_rate }}/{{ __('Hour') }}</p>
                            @else
                                <p class="trainer-position"></p>
                            @endif

                                            @php $hourly_fee = 0; @endphp
                                            @if ($currencyPlacement ?? get_currency_placement() == 'after')
                                                @php $hourly_fee = $user->hourly_rate . ' ' . $currencySymbol ?? get_currency_symbol() . '/h'; @endphp
                                            @else
                                                @php $hourly_fee = $currencySymbol ?? get_currency_symbol() . ' ' . $user->hourly_rate . '/h'; @endphp
                                            @endif

                                            <button data-type="{{ $user->available_type }}"
                                                data-booking_instructor_user_id="{{ $user->id }}"
                                                data-hourly_fee="{{ $hourly_fee }}"
                                                data-hourly_rate="{{ $user->hourly_rate }}"
                                                data-get_off_days_route="{{ route('getOffDays', $user->id) }}"
                                                data-bs-toggle="modal" data-bs-target="#consultationBookingModal"
                                                type="button"
                                                class="bookSchedule theme-btn theme-button1 mt-20">{{ __('Book Schedule') }}
                                                <i class="fas fa-arrow-right mx-2"></i>
                                            </button>

                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <!-- One to One Consultation Area End -->
            @endif
        @endif
    @endif

    <!-- Subscription Start -->
    @if (@$home->subscription_show == 1 && get_option('subscription_mode'))
        <div class="price-area">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6 offset-lg-3">
                        <div class="section-title text-center">
                            <h3 class="section-heading w-50">{{ __('Subscribe Now!') }}</h3>
                            <p class="section-sub-heading mb-19">{{ __('#Choose a subscription plan and save money!') }}
                            </p>
                        </div>
                    </div>
                </div>
                <div class="row">

                    <div class="nav nav-pills justify-content-center align-items-center" id="pills-tab" role="tablist">
                        <span class="plan-switch-month-year-text mx-3">{{ __('Monthly') }}</span>
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
                    <div class="tab-content" id="">
                        @include('frontend-theme-2.home.partial.subscription-home-list')
                    </div>
                </div>
            </div>
        </div>
    @endif
    <!-- Subscription End -->

    @if ($home->instructor_area == 1)
        <!-- Our Top Instructor Area Start -->
        <div class="personalized-area upcoming-section-area angel-elsie-bg instructors-area">
            <div class="container">
                <div class="row sf-rg-24">
                    <div class="align-items-center col-lg-4 col-md-6 d-flex">
                        <div class="section-left-title-with-btn ">
                            <div class="section-title ">
                                <div class="section-heading-img flex-shrink-0">
                                    <img src="{{ getImageFile(get_option('top_instructor_logo')) }}"
                                        alt="{{ __('Instructor') }}">
                                </div>
                                <div>
                                    <h3 class="section-heading w-100">{{ __(get_option('top_instructor_title')) }}</h3>
                                    <p class="section-sub-heading mb-la-24 font-la-14">
                                        {{ __(get_option('top_instructor_subtitle')) }}</p>
                                    <a href="{{ route('instructor') }}"
                                        class="theme-btn theme-button1 theme-button3">{{ __('View All Instructor') }}
                                        <i class="fas fa-arrow-right mx-2"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    @foreach ($instructors as $user)
                        <div class="col-lg-4 col-sm-6">
                            <div class="training-card position-relative ">
                                <img class="trainer-img fit-image" src="{{ getImageFile(@$user->image_path) }}"
                                    alt="{{ __('instructor') }}">
                                <div class="hover-trainer-info">
                                    <h5 class="trainer-name"><a
                                            href="{{ route('userProfile', $user->id) }}">{{ $user->name }}</a></h5>
                                    <p class="trainer-position"> {{ @$user->professional_title }}
                                        @if (get_instructor_ranking_level($user->badges))
                                            <span
                                                class="mx-2">||</span>{{ get_instructor_ranking_level($user->badges) }}
                                        @endif
                                    </p>

                                    <?php
                                    $average_rating = $user->courses->where('average_rating', '>', 0)->avg('average_rating');
                                    ?>
                                    <div class="course-rating d-flex align-items-center">
                                        <span
                                            class="font-medium font-14 me-2 white-color">{{ number_format(@$average_rating, 1) }}</span>
                                        <ul class="rating-list d-flex align-items-center me-2">
                                            <li class="">

                                            </li>
                                            <div
                                                class="search-instructor-rating w-100 d-inline-flex align-items-center justify-content-center">
                                                <div class="star-ratings">
                                                    <div class="fill-ratings"
                                                        style="width: {{ $average_rating * 20 }}%">
                                                        <span>★★★★★</span>
                                                    </div>
                                                    <div class="empty-ratings">
                                                        <span>★★★★★</span>
                                                    </div>
                                                </div>
                                            </div>

                        </ul>
                        <span
                            class="rating-count font-14 white-color">({{ count(@$user->courses->where('average_rating', '>', 0)) }})</span>
                    </div>
                    <div class="trainer-base">
                        @foreach ($user->badges as $badge)
                            <img src="{{ asset($badge->badge_image) }}" title="{{ $badge->name }}"
                                alt="{{ $badge->name }}">
                        @endforeach
                    </div>
                    @if ($user->consultation_available == 1)
                        <p class="trainer-position">{{ $user->hourly_rate }}/{{ __('Hour') }}</p>
                    @else
                        <p class="trainer-position"></p>
                    @endif

                                    @php $hourly_fee = 0; @endphp
                                    @if ($currencyPlacement ?? get_currency_placement() == 'after')
                                        @php $hourly_fee = $user->hourly_rate . ' ' . $currencySymbol ?? get_currency_symbol() . '/h'; @endphp
                                    @else
                                        @php $hourly_fee = $currencySymbol ?? get_currency_symbol() . ' ' . $user->hourly_rate . '/h'; @endphp
                                    @endif

                                    <a href="{{ route('userProfile', $user->id) }}"
                                        class=" package-btn green-theme-btn theme-button1 mt-20">{{ __('View Profile') }}
                                        <i class="fas fa-arrow-right mx-2"></i>
                                    </a>

                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        <!-- Our Top Instructor Area End -->
    @endif

    <!-- Saas Plan Start -->
    @if (@$home->saas_show == 1 && get_option('saas_mode'))
        <div class="price-area">
            <div class="container">
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
                                aria-controls="instructor-tab-pane" aria-selected="true">{{ __('Instructor') }}</button>
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
                        @include('frontend-theme-2.home.partial.instructor-saas-home-list')

                        <!-- Organization -->
                        @include('frontend-theme-2.home.partial.organization-saas-home-list')
                    </div>
                </div>
            </div>
        </div>
    @endif
    <!-- Saas Plan End -->

    @if ($home->customer_says_area == 1)
        <!-- Customers Says/ testimonial Area Start -->
        <div class="customers-says-area gradient-bg ">
            <div class="section-overlay customers-landing">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-4 offset-lg-4">
                            <div class="section-title text-center">
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

                        <div class="col-lg-6">
                            <div class="testimonial-item">
                                <div class="align-items-start customers-d-column d-flex gap-4">
                                    <div class="d-flex justify-content-center justify-content-sm-start">
                                        <img src="{{ getImageFile(get_option('customer_say_first_image')) }}"
                                            alt="quote">
                                    </div>
                                    <div>
                                        <div
                                            class="testimonial-top-content d-flex justify-content-center justify-content-sm-start align-items-center">
                                            <div class="flex-shrink-0 quote-img-wrap">
                                                <img src="{{ asset('frontend/assets/img/icons-svg/quote.svg') }}"
                                                    alt="quote">
                                            </div>
                                            <div class="ms-3 testimonial-content">
                                                <h6 class="font-16">{{ __(get_option('customer_say_first_name')) }}</h6>
                                                <p class="font-13 font-medium">
                                                    {{ __(get_option('customer_say_first_position')) }}</p>
                                            </div>
                                        </div>
                                        <div class="testimonial-bottom-content">
                                            <h6 class="text-white">
                                                {{ __(get_option('customer_say_first_comment_title')) }}</h6>
                                            <p class="font-17">
                                                {{ __(get_option('customer_say_first_comment_description')) }}</p>
                                            <div class="course-rating d-flex align-items-center">
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

                        <div class="col-lg-6">
                            <div class="testimonial-item">
                                <div class="align-items-start customers-d-column d-flex gap-4">
                                    <div class="d-flex justify-content-center justify-content-sm-start">
                                        <img src="{{ getImageFile(get_option('customer_say_second_image')) }}"
                                            alt="quote">
                                    </div>
                                    <div>
                                        <div
                                            class="testimonial-top-content d-flex justify-content-center justify-content-sm-start align-items-center">
                                            <div class="flex-shrink-0 quote-img-wrap">
                                                <img src="{{ asset('frontend-theme-2/assets/img/quet(7).png') }}"
                                                    alt="course">
                                            </div>
                                            <div class="ms-3 testimonial-content">
                                                <h6 class="font-16">{{ __(get_option('customer_say_second_name')) }}</h6>
                                                <p class="font-13 font-medium">
                                                    {{ __(get_option('customer_say_second_position')) }}</p>
                                            </div>
                                        </div>
                                        <div class="testimonial-bottom-content">
                                            <h6 class="text-white">
                                                {{ __(get_option('customer_say_second_comment_title')) }}</h6>
                                            <p class="font-17">
                                                {{ __(get_option('customer_say_second_comment_description')) }}</p>
                                            <div class="course-rating d-flex align-items-center">
                                                <ul class="rating-list d-flex align-items-center me-2">

                                                    <div
                                                        class="search-instructor-rating w-100 d-inline-flex align-items-center justify-content-center">
                                                        <div class="star-ratings">
                                                            <div class="fill-ratings"
                                                                style="width:{{ (float) get_option('customer_say_second_comment_rating_star') * 20 }}%">
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

                        <div class="col-lg-6">
                            <div class="testimonial-item">
                                <div class="align-items-start customers-d-column d-flex gap-4">
                                    <div class="d-flex justify-content-center justify-content-sm-start">
                                        <img src="{{ getImageFile(get_option('customer_say_third_image')) }}"
                                            alt="quote">
                                    </div>
                                    <div>
                                        <div
                                            class="testimonial-top-content d-flex justify-content-center justify-content-sm-start align-items-center">
                                            <div class="flex-shrink-0 quote-img-wrap">
                                                <img src="{{ asset('frontend/assets/img/icons-svg/quote.svg') }}"
                                                    alt="quote">
                                            </div>
                                            <div class="ms-3 testimonial-content">
                                                <h6 class="font-16">{{ __(get_option('customer_say_second_name')) }}</h6>
                                                <p class="font-13 font-medium">
                                                    {{ __(get_option('customer_say_second_position')) }}</p>
                                            </div>
                                        </div>
                                        <div class="testimonial-bottom-content">
                                            <h6 class="text-white">
                                                {{ __(get_option('customer_say_second_comment_title')) }}</h6>
                                            <p class="font-17">
                                                {{ __(get_option('customer_say_second_comment_description')) }}</p>
                                            <div class="course-rating d-flex align-items-center">
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

                        <div class="col-lg-6">
                            <div class="testimonial-item">
                                <div class="align-items-start customers-d-column d-flex gap-4">
                                    <div class="d-flex justify-content-center justify-content-sm-start">
                                        <img src="{{ getImageFile(get_option('customer_say_fourth_image')) }}"
                                            alt="quote">
                                    </div>
                                    <div>
                                        <div
                                            class="testimonial-top-content d-flex justify-content-center justify-content-sm-start align-items-center">
                                            <div class="flex-shrink-0 quote-img-wrap">
                                                <img src="{{ asset('frontend/assets/img/icons-svg/quote.svg') }}"
                                                    alt="quote">
                                            </div>
                                            <div class="ms-3 testimonial-content">
                                                <h6 class="font-16">{{ __(get_option('customer_say_fourth_name')) }}</h6>
                                                <p class="font-13 font-medium">
                                                    {{ __(get_option('customer_say_fourth_position')) }}</p>
                                            </div>
                                        </div>
                                        <div class="testimonial-bottom-content">
                                            <h6 class="text-white">
                                                {{ __(get_option('customer_say_fourth_comment_title')) }}</h6>
                                            <p class="font-17">
                                                {{ __(get_option('customer_say_fourth_comment_description')) }}</p>
                                            <div class="course-rating d-flex align-items-center">
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
        <!-- Customers Says/ testimonial Area End -->
    @endif

    @if ($home->faq_area == 1)
        <!-- FAQ Area Start -->
        <section class="faq-area home-page-faq-area sf-home-page-faq-area section-t-space">
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
        <!-- FAQ Area End -->
    @endif


    @if ($home->instructor_support_area == 1)
        <!-- Course Instructor and Support Area Start -->
        <div class="instructor-section-area">
            <div class="container">
                <div class="row flex-column-reverse flex-lg-row align-items-center">
                    <div class="col-lg-5 offset-lg-1">
                        <div class="row">
                            @foreach ($instructorSupports->take(2) as $instructorSupport)
                                <div class="col-lg-12 col-sm-6 mb-4">
                                    <div
                                        class="instructor-support-item sf-instructor-support-item bg-white radius-3 text-center">
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
                    <div class="col-lg-5">
                        <div class="row">
                            <div class="col-lg-12 col-sm-6 mb-4">
                                <div class="section-title">
                                    <h3 class="section-heading w-100">
                                        {{ __(@$aboutUsGeneral->instructor_support_title) }}</h3>
                                    <p class="section-sub-heading">
                                        {{ __(@$aboutUsGeneral->instructor_support_subtitle) }}</p>
                                </div>
                            </div>
                            @php
                                $third = $instructorSupports->skip(2)->first();
                            @endphp
                            @if (!is_null($third))
                                <div class="col-lg-12 col-sm-6 mb-4">
                                    <div
                                        class="instructor-support-item sf-instructor-support-item bg-white radius-3 text-center">
                                        <div class="instructor-support-img-wrap">
                                            <img src="{{ getImageFile($third->image_path) }}" alt="support">
                                        </div>
                                        <h6>{{ __($third->title) }}</h6>
                                        <p>{{ __($third->subtitle) }} </p>
                                        <a href="{{ $instructorSupport->button_link ?? '#' }}"
                                            class="theme-btn theme-button1 theme-button3">{{ __($third->button_name) }}
                                            <i class="fas fa-arrow-right mx-2"></i>
                                        </a>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Course Instructor and Support Area End -->

        {{-- sponser section area start --}}
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

    <script src="{{ asset('frontend-theme-2/assets/js/main.js') }}"></script>

    <script>
        const zai_player = new Plyr('#player');
    </script>
    <!-- Video Player js -->
@endpush
