@extends('frontend.layouts.app')
@php
$userRelation = getUserRoleRelation($user);
@endphp

@section('meta')
    <meta name="description" content="{{ __($user->meta_description) }}">
    <meta name="keywords" content="{{ __($user->meta_keywords) }}">

    <!-- Open Graph meta tags for social sharing -->
    <meta property="og:type" content="Learning">
    <meta property="og:title" content="{{ __($user->meta_title) }}">
    <meta property="og:description" content="{{ __($user->meta_description) }}">
    <meta property="og:image" content="{{ getImageFile($user->og_image) }}">
    <meta property="og:url" content="{{ url()->current() }}">

    <meta property="og:site_name" content="{{ __(get_option('app_name')) }}">

    <!-- Twitter Card meta tags for Twitter sharing -->
    <meta name="twitter:card" content="Learning">
    <meta name="twitter:title" content="{{ __($user->meta_title) }}">
    <meta name="twitter:description" content="{{ __($user->meta_description) }}">
    <meta name="twitter:image" content="{{ getImageFile($user->og_image) }}">
@endsection

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

    <!-- Instructor Details Area Start -->
    <section class="instructor-details-area section-t-space">
        <div class="container">
            <div class="row instructor-details-main-row">
                <div class="col-12 col-md-12 col-lg-8 col-xl-9">
                    <div class="instructor-details-left-content">
                        @if ($user->$userRelation->is_offline == INSTRUCTOR_IS_OFFLINE)
                        <div
                            class="instructor-details-left-inner-box instructor-temporary-unavailable px-4 py-3 radius-4">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1 me-3">
                                    <h6 class="text-white font-17">{{ __('Instructor is temporarily unavailable') }}.
                                    </h6>
                                    <p class="text-white font-15 mt-1">{{ __(@$user->$userRelation->offline_message) }}
                                    </p>
                                </div>
                                <div class="flex-shrink-0">
                                    <div class="temporary-unavailable-img-wrap bg-white px-4 py-3 radius-3">
                                        <img src="{{ asset('frontend/assets/img/instructor-img/unavailable-calendar.png') }}"
                                            alt="">
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                        <!-- about instructor box -->
                        <div class="instructor-details-left-inner-box about-skills-box bg-white radius-3">
                            <div class="about-instructor-box">
                                <h5 class="instructor-details-inner-title">{{ __('About') }} {{ @$user->name }}</h5>
                                <p>{{ @$user->$userRelation->about_me }}</p>
                            </div>

                            <div class="instructor-skills-box mt-25">
                                <h5 class="instructor-details-inner-title">{{ __('Skills') }}</h5>
                                <ul class="instructor-skills-tag d-inline-flex align-items-center flex-wrap">
                                    @forelse ($user->$userRelation->skills as $skill)
                                    <li class="bg-page instructor-skills-tag-item">{{ $skill->title }}</li>
                                    @empty
                                    <li class="font-15"><span class="color-gray2">{{ __('No skills to show') }}</li>
                                    @endforelse
                                </ul>
                            </div>
                        </div>

                        <!-- Certificate and awards -->
                        <div class="instructor-details-left-inner-box certificate-awards-box bg-white radius-3">
                            <div class="row">
                                <div class="col-md-6 certificate-awards-inner">
                                    <h5 class="instructor-details-inner-title">{{ __('Certifications') }}</h5>
                                    <ul>
                                        @forelse(@$user->$userRelation->certificates as $certificate)
                                        <li class="font-15"><span class="color-heading">{{ $certificate->passing_year
                                                }}</span>{{ $certificate->name }}</li>
                                        @empty
                                        <li class="font-15"><span class="color-gray2">{{ __('No certificate to show') }}</li>
                                        @endforelse
                                    </ul>
                                </div>
                                <div class="col-md-6 certificate-awards-inner">
                                    <h5 class="instructor-details-inner-title">{{ __('Awards') }}</h5>
                                    <ul>
                                        @forelse(@$user->$userRelation->awards as $award)
                                        <li class="font-15"><span class="color-heading">{{ $award->winning_year
                                                }}</span>{{ $award->name }}</li>
                                        @empty
                                        <li class="font-15"><span class="color-gray2">{{ __('No award to show') }}</li>
                                        @endforelse
                                    </ul>
                                </div>
                            </div>
                        </div>

                        @if(!get_option('private_mode') || !auth()->guest())

                        @if($user->role == USER_ROLE_INSTRUCTOR)
                        <!-- My others courses -->
                        <div class="instructor-details-left-inner-box my-others-courses bg-white radius-3">
                            <h5 class="instructor-details-inner-title">{{ __('My courses') }} ({{
                                @$user->courses->count() }})</h5>
                            <div class="row" id="appendInstructorCourses">
                                @include('frontend.instructor.render-instructor-courses')

                            </div>
                            @if($courses->hasPages())
                            <!-- Load More Button-->
                            <div class="d-block" id="loadMoreBtn"><button type="button"
                                    class="theme-btn theme-button2 load-more-btn loadMore">{{ __('Load More') }} <span
                                        class="iconify" data-icon="icon-park-outline:loading-one"></span></button></div>
                            @endif
                        </div>
                        @elseif($user->role == USER_ROLE_ORGANIZATION)
                        <!-- Organization Single Tab Area Start -->
                        <div class="instructor-details-left-inner-box organization-single-tab-area bg-white radius-3">
                            <!-- Tab panel nav list -->
                            <div class="course-tab-nav-wrap course-details-tab-nav-wrap d-flex justify-content-between">
                                <ul class="nav nav-tabs tab-nav-list border-0" id="myTab" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link active" id="Courses-tab" data-bs-toggle="tab" href="#Courses"
                                            role="tab" aria-controls="Courses" aria-selected="true">{{ __("Courses") }}</a>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link" id="Instructors-tab" data-bs-toggle="tab"
                                            href="#Instructors" role="tab" aria-controls="Instructors"
                                            aria-selected="false">{{ __("Instructors") }}</a>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link" id="ReserveMeeting-tab" data-bs-toggle="tab"
                                            href="#ReserveMeeting" role="tab" aria-controls="ReserveMeeting"
                                            aria-selected="false">{{ __("Reserve a meeting") }}</a>
                                    </li>
                                </ul>
                            </div>
                            <!-- Tab Content-->
                            <div class="tab-content" id="myTabContent">
                                <div class="tab-pane fade show active" id="Courses" role="tabpanel"
                                    aria-labelledby="Courses-tab">
                                    <!-- organization courses tab-pane start -->
                                    <div class="organization-courses-tab">
                                        <div class="row" id="appendInstructorCourses">
                                            @include('frontend.instructor.render-instructor-courses')

                                        </div>
                                        @if($courses->hasPages())
                                            <!-- Load More Button-->
                                            <div class="d-block" id="loadMoreBtn"><button type="button"
                                                class="theme-btn theme-button2 load-more-btn loadMore">{{ __('Load More') }} <span
                                                    class="iconify" data-icon="icon-park-outline:loading-one"></span></button></div>
                                        @endif
                                    </div>
                                    <!-- organization courses tab-pane end -->
                                </div>
                                <div class="tab-pane fade" id="Instructors" role="tabpanel"
                                    aria-labelledby="Instructors-tab">
                                    <!-- organization instructors tab-pane start -->
                                    <div class="organization-instructors-tab">
                                        <div class="row top-instructor-content-wrap" id="appendOrganizationInstructors">
                                            @foreach ($instructors as $instructorUser)
                                            <div class="col-sm-6 col-md-6 col-lg-6 col-xl-4 mt-0 mb-25">
                                                <x-frontend.instructor :user="$instructorUser"
                                                    :type=INSTRUCTOR_CARD_TYPE_ONE />
                                            </div>
                                            @endforeach
                                        </div>
                                        @if($instructors->hasPages())
                                            <!-- Load More Button-->
                                            <div class="d-block" id="organizationLoadMoreBtn"><button type="button"
                                                    class="theme-btn theme-button2 load-more-btn instructorLoadMore">{{ __('Load More') }} <span
                                                        class="iconify" data-icon="icon-park-outline:loading-one"></span></button></div>
                                        @endif
                                    </div>
                                    <!-- organization instructors tab-pane start -->
                                </div>
                                <div class="tab-pane fade" id="ReserveMeeting" role="tabpanel"
                                    aria-labelledby="ReserveMeeting-tab">
                                    <!-- organization reserve-meeting tab-pane start -->
                                    <div class="organization-instructors-reserve-meeting-tab">
                                        <div class="row">
                                            @foreach($consultationInstructors as $instructorUser)
                                            <!-- Course item start -->
                                            <div class="col-12 col-md-6 col-lg-6 col-xl-4 mt-0 mb-25">
                                                <x-frontend.instructor :user="$instructorUser"
                                                    :type=INSTRUCTOR_CARD_TYPE_TWO />
                                            </div>
                                            <!-- Course item end -->
                                            @endforeach
                                        </div>
                                    </div>
                                    <!-- organization reserve-meeting tab-pane end -->
                                </div>
                            </div>
                        </div>
                        @endif
                        @endif
                    </div>
                </div>

                <div class="col-12 col-md-12 col-lg-4 col-xl-3">
                    <div class="instructor-details-right-content radius-3">
                        <div class="course-info-box instructor-info-box bg-white p-0">

                            <div class="instructor-details-right-img-box text-center mb-20">
                                <div class="instructor-details-avatar-wrap radius-50 overflow-hidden mx-auto">
                                    <img src="{{ getImageFile($user->image_path) }}" alt="img" class="radius-50">
                                </div>
                                <h6 class="instructor-details-name">{{ $user->$userRelation->name }}</h6>
                                <p class="instructor-details-designation font-12 font-semi-bold mb-15">{{
                                    $user->$userRelation->professional_title }}</p>
                                <div class="search-instructor-award-img d-inline-flex flex-wrap justify-content-center">
                                    @foreach ($user->badges as $badge)
                                    <img src="{{ asset($badge->badge_image) }}" title="{{ $badge->name }}" alt="{{ $badge->name }}"
                                        class="fit-image rounded-circle">
                                    @endforeach
                                </div>
                                <div>
                                    @auth
                                    @if (auth()->id() != $user->id)
                                    @if ($user->followers->where('id', auth()->id())->count())
                                    <button type="button" class="green-theme-btn theme-button1 follow-btn"
                                        id="unFollowingId">{{ __('Unfollow') }}</button>
                                    @else
                                    <button type="button" class="green-theme-btn theme-button1 follow-btn"
                                        id="followingId">{{ __('Follow') }}</button>
                                    @endif
                                    @endif
                                    @else
                                    <button type="button" class="green-theme-btn theme-button1 follow-btn"
                                        id="unAuthBtnId">{{ __('Follow') }}</button>
                                    @endauth
                                </div>
                            </div>

                            <div class="follower-following-box mb-20">
                                <div class="font-15 follower-item only-follower-item border-start-0">
                                    <h6 class="d-block color-heading font-15" id="followers">{{ count($user->followers)
                                        }}</h6>
                                    <span>{{ __('Followers') }}</span>
                                </div>
                                <div class="font-15 follower-item border-start-0 border-end-0">
                                    <h6 class="d-block color-heading font-15" id="following">{{ count($user->followings)
                                        }}</h6>
                                    <span>{{ __('Following') }}</span>
                                </div>
                            </div>

                            <div class="course-includes-box p-0 mb-20">
                                <ul>
                                    @if(get_instructor_ranking_level($user->badges))
                                    <li>
                                        <span class="iconify" data-icon="icon-park-outline:ranking"></span>
                                        <span>{{ get_instructor_ranking_level($user->badges) }}
                                            {{ __('(Ranking)') }}</span>
                                    </li>
                                    @endif
                                    <li>
                                        <span class="iconify" data-icon="akar-icons:book-close"></span>
                                        <span>{{ @$user->courses->count() }} {{ __('Courses') }}</span>
                                    </li>
                                    <li>
                                        <span class="iconify" data-icon="bi:camera-video"></span>
                                        <span>{{ @$total_lectures }} {{ __('Video Lectures') }}</span>
                                    </li>
                                    <li>
                                        <span class="iconify" data-icon="la:book-reader"></span>
                                        <span>{{ @$totalStudent }} {{ __('Students') }}</span>
                                    </li>
                                    <li>
                                        <span class="iconify"
                                            data-icon="healthicons:i-exam-multiple-choice-outline"></span>
                                        <span>{{ @$total_quizzes }} {{ __('Quizzes') }}</span>
                                    </li>
                                    <li>
                                        <span class="iconify" data-icon="bi:book"></span>
                                        <span>{{ @$total_assignments }} {{ __('Assignments') }}</span>
                                    </li>
                                    <li>
                                        <span class="iconify"
                                            data-icon="fluent:device-meeting-room-remote-24-regular"></span>
                                        <span>{{ $totalMeeting }} {{ __(' Meetings') }}</span>
                                    </li>
                                    <li>
                                        <span class="iconify" data-icon="bi:star"></span>
                                        <span>{{ $total_rating }} Reviews ({{ number_format(@$average_rating, 1) }}
                                            average)</span>
                                    </li>
                                    <li>
                                        <span class="iconify" data-icon="codicon:globe"></span>
                                        <span>{{ @$user->$userRelation->address }}</span>
                                    </li>
                                </ul>
                            </div>
                            @php
                            $social_link = json_decode(@$user->$userRelation->social_link);
                            @endphp

                            <div class="instructor-social mt-20">
                                <ul class="d-flex align-items-center">
                                    <li>
                                        <a href="{{@$user->$userRelation->social_link ? $social_link->facebook : ''}}">
                                            <span class="iconify" data-icon="ant-design:facebook-filled"></span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{@$user->$userRelation->social_link ? $social_link->twitter : ''}}">
                                            <span class="iconify" data-icon="ant-design:twitter-square-filled"></span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{@$user->$userRelation->social_link ? $social_link->linkedin : ''}}">
                                            <span class="iconify" data-icon="ant-design:linkedin-filled"></span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{@$user->$userRelation->social_link ? $social_link->pinterest : ''}}">
                                            <span class="iconify" data-icon="fa-brands:pinterest-square"
                                                data-width="1em" data-height="1em"></span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            @if(!get_option('private_mode') || !auth()->guest())
                            @if(@$user->$userRelation->consultation_available == 1)
                            @php $hourly_fee = 0; @endphp
                            @if(get_currency_placement() == 'after')
                            @php $hourly_fee = @$user->$userRelation->hourly_rate . ' ' . get_currency_symbol() . '/h';
                            @endphp
                            @else
                            @php $hourly_fee = get_currency_symbol() . ' ' . @$user->$userRelation->hourly_rate . '/h';
                            @endphp
                            @endif
                            <div class="instructor-bottom-item mt-20">
                                <button type="button" data-type="{{ @$user->$userRelation->available_type }}"
                                    data-booking_instructor_user_id="{{ @$user->$userRelation->user_id }}"
                                    data-hourly_fee="{{ $hourly_fee }}"
                                    data-hourly_rate="{{ @$user->$userRelation->hourly_rate }}"
                                    data-get_off_days_route="{{ route('getOffDays', @$user->$userRelation->user_id) }}"
                                    class="theme-btn theme-button1 theme-button3 w-100 bookSchedule"
                                    data-bs-toggle="modal" data-bs-target="#consultationBookingModal">{{ __('Book
                                    Schedule') }}
                                </button>
                            </div>
                            @endif
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Instructor Details Area End -->

</div>
<input type="hidden" value="3" class="course_paginate_number">
<input type="hidden" class="instructorCoursePaginateRoute" value="{{ route('instructorCoursePaginate', $user->id) }}">
<input type="hidden" class="organizationInstructorsPaginateRoute" value="{{ route('organizationInstructorPaginate', $user->id) }}">

@include('frontend.home.partial.consultation-booking-schedule-modal')
@endsection

@push('script')
<script>
    const followRoute = "{{ route('follow') }}";
    const unfollowRoute = "{{ route('unfollow') }}";
    const userInstructorId = "{{ $user->id }}";
</script>
<script src="{{ asset('frontend/assets/js/course/addToCart.js') }}"></script>
<script src="{{ asset('frontend/assets/js/course/addToWishlist.js') }}"></script>
<script src="{{ asset('frontend/assets/js/custom/instructor-course-paginate.js') }}"></script>
<script src="{{ asset('frontend/assets/js/custom/booking.js') }}"></script>
<script src="{{ asset('frontend/assets/js/custom/follow.js') }}"></script>
@endpush
