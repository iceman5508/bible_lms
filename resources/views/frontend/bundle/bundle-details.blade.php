@extends('frontend.layouts.app')
@php 
$relation = getUserRoleRelation($bundle->user);
@endphp

@section('meta')
    <meta name="description" content="{{ __($bundle->meta_description) }}">
    <meta name="keywords" content="{{ __($bundle->meta_keywords) }}">

    <!-- Open Graph meta tags for social sharing -->
    <meta property="og:type" content="Learning">
    <meta property="og:title" content="{{ __($bundle->meta_title) }}">
    <meta property="og:description" content="{{ __($bundle->meta_description) }}">
    <meta property="og:image" content="{{ getImageFile($bundle->og_image) }}">
    <meta property="og:url" content="{{ url()->current() }}">

    <meta property="og:site_name" content="{{ __(get_option('app_name')) }}">

    <!-- Twitter Card meta tags for Twitter sharing -->
    <meta name="twitter:card" content="Learning">
    <meta name="twitter:title" content="{{ __($bundle->meta_title) }}">
    <meta name="twitter:description" content="{{ __($bundle->meta_description) }}">
    <meta name="twitter:image" content="{{ getImageFile($bundle->og_image) }}">
@endsection

@section('content')
    <div class="bg-page">
        <!-- Course Single Page Header Start -->
        <header class="course-single-page-header gradient-bg position-relative">
            <div class="section-overlay">
                <div class="container">
                    <div class="row">
                        <div class="col-12 col-sm-12 col-md-12 col-lg-6">
                            <div class="course-single-banner-content">
                                <h3 class="page-banner-heading text-white pb-30">{{ __($bundle->name) }}</h3>
                                <p class="instructor-name-certificate font-medium font-12 text-white">
                                    <span class="text-decoration-underline">{{ @$bundle->user->$relation->name }}</span>
                                    @if(get_instructor_ranking_level(@$bundle->user->badges))
                                        | {{ get_instructor_ranking_level(@$bundle->user->badges) }}
                                    @endif
                                </p>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>
        <!-- Course Single Page Header End -->

        <!-- Course Single Details Area Start -->
        <section class="course-single-details-area before-login-purchase-course-details">
            <div class="container">
                <div class="row">
                    <div class="col-12 col-md-12 col-lg-8">
                        <div class="course-single-details-left-content bg-white">
                            <!-- Tab panel nav list -->
                            <div class="course-tab-nav-wrap course-details-tab-nav-wrap d-flex justify-content-between">
                                <ul class="nav nav-tabs tab-nav-list border-0" id="myTab" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link active" id="Overview-tab" data-bs-toggle="tab" href="#Overview" role="tab" aria-controls="Overview"
                                           aria-selected="true">{{ __('Overview') }}</a>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link" id="Courses-list-tab" data-bs-toggle="tab" href="#Courses-list" role="tab" aria-controls="Courses-list"
                                           aria-selected="false">{{ __('Courses list') }} ({{ @$bundle->bundleCourses->count() }})</a>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link" id="Instructor-tab" data-bs-toggle="tab" href="#Instructor" role="tab" aria-controls="Instructor"
                                           aria-selected="false">{{ __('Instructor') }}</a>
                                    </li>
                                </ul>
                            </div>
                            <!-- Tab panel nav list -->

                            <!-- Tab Content-->
                            <div class="tab-content" id="myTabContent">
                                <div class="tab-pane fade show active" id="Overview" role="tabpanel" aria-labelledby="Overview-tab">
                                    <div class="overview-content">
                                        <p>{!! nl2br($bundle->overview) !!}</p>
                                    </div>
                                </div>
                                @include('frontend.bundle.partial.render-bundle-course-list-tab')
                                @include('frontend.bundle.partial.render-instructor-tab')
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-12 col-lg-4">
                        <div class="course-single-details-right-content">
                            <div class="course-info-box bg-white">

                                <div class="video-area-left position-relative d-flex align-items-center justify-content-center">
                                    <div class="course-info-video-img">
                                            <img src="{{ getImageFile($bundle->image) }}" alt="Bundle Image" class="img-fluid">
                                    </div>
                                </div>
                                <div class="course-price-box d-flex justify-content-between align-items-center mt-30 mb-30">

                                    <div>
                                        <h4 class="d-flex align-items-center mb-1">
                                            @if(get_currency_placement() == 'after')
                                                {{ $bundle->price }} {{ get_currency_symbol() }}
                                            @else
                                                {{ get_currency_symbol() }} {{ $bundle->price }}
                                            @endif
                                        </h4>
                                    </div>
                                </div>

                                <div class="course-includes-box course-includes-box-top">
                                    <ul class="pb-30">
                                        <li class="d-flex justify-content-between">
                                            <div>
                                                <span class="iconify" data-icon="carbon:increase-level"></span>
                                                <span>Total Course</span>
                                            </div>
                                            <div>{{ @$bundle->bundleCourses->count() }}</div>
                                        </li>
                                    </ul>
                                </div>

                                <button class="theme-btn theme-button1 theme-button3 w-100 mb-30 addToCart" data-bundle_id="{{ $bundle->id }}"
                                        data-route="{{ route('student.addToCart') }}">
                                    {{ __('Enroll the bundle') }}<i data-feather="arrow-right"></i>
                                </button>

                                <div class="course-info-box-wishlist-btns d-flex mb-30">
                                    <button class="theme-btn para-color font-medium mx-2 addToWishlist" title="Add to wishlist" data-bundle_id="{{ $bundle->id }}"
                                            data-route="{{ route('student.addToWishlist') }}"><span class="iconify me-2" data-icon="bytesize:heart"></span>{{ __('Add to wishlist') }}
                                    </button>
                                    <button class="theme-btn para-color font-medium mx-2" title="Share this course" data-bs-toggle="modal" data-bs-target="#shareThisCourseModal">
                                        <span class="iconify me-2" data-icon="ci:share-outline"></span>{{ __('Share bundle') }}
                                    </button>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- Course Single Details Area End -->
    </div>

    <!--Share This Course Modal Start-->
    <div class="modal fade" id="shareThisCourseModal" tabindex="-1" aria-labelledby="shareThisCourseModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="shareThisCourseModalLabel">{{ __('Share this bundle') }}</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <ul class="share-course-list">
                        <li><a href="http://www.facebook.com/sharer.php?u={{ route('bundle-details', [$bundle->slug]) }}"><span class="iconify"
                                                                                                                                               data-icon="cib:facebook-f"></span></a>
                        </li>
                        <li><a href="https://twitter.com/share?url={{ route('bundle-details', [$bundle->slug]) }}"><span class="iconify"
                                                                                                                                        data-icon="el:twitter"></span></a></li>
                        <li><a href="https://www.linkedin.com/shareArticle?url={{ route('bundle-details', [$bundle->slug]) }}"><span class="iconify"
                                                                                                                                                    data-icon="cib:linkedin-in"></span></a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <!--Share This Course Modal End-->
@endsection

@push('script')
    <script src="{{ asset('frontend/assets/js/course/addToCart.js') }}"></script>
    <script src="{{ asset('frontend/assets/js/course/addToWishlist.js') }}"></script>
@endpush
