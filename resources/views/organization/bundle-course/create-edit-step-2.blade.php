@extends('layouts.organization')

@section('breadcrumb')
    <div class="page-banner-content text-center">
        <h3 class="page-banner-heading text-white pb-15"> {{ __('Bundles Courses') }} </h3>

        <!-- Breadcrumb Start-->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb justify-content-center">
                <li class="breadcrumb-item font-14"><a
                        href="{{ route('organization.dashboard') }}">{{ __('Dashboard') }}</a></li>
                <li class="breadcrumb-item font-14 active" aria-current="page">{{ __('Bundles Courses') }}</li>
            </ol>
        </nav>
    </div>
@endsection

@section('content')
    <div class="instructor-profile-right-part">

        <div class="instructor-create-new-quiz-page instructor-create-assignment-page bg-white">
            <div class="instructor-my-courses-title d-flex justify-content-between align-items-center">
                <h6>{{ __('Create Bundles Courses') }}</h6>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="instructor-my-courses-box instructor-panel-bundles-courses-page create-bundles-courses-page bg-white">
                        <label class="label-text-title color-heading font-medium font-16 mb-3">{{ __('Select your course') }} (<span class="totalCourses">{{ @$totalCourses }}</span>)</label>
                        <div class="row create-bundles-courses-item-wrap">
                            @forelse($courses as $course)
                                <!-- Course item start -->
                                <div class="col-12 col-sm-6 col-lg-12">
                                    <div class="card course-item instructor-my-course-item create-bundles-course-item bg-white">
                                        <div class="course-img-wrap flex-shrink-0 overflow-hidden">
                                        <span class="course-tag badge radius-3 font-14 font-medium position-absolute bg-white color-hover">
                                            @if(get_currency_placement() == 'after')
                                                {{$course->price}} {{ get_currency_symbol() }}
                                            @else
                                                {{ get_currency_symbol() }} {{$course->price}}
                                            @endif</span>
                                            <a href="#"><img src="{{getImageFile($course->image_path)}}" alt="course" class="img-fluid"></a>
                                        </div>
                                        <div class="card-body">

                                            <div class="instructor-courses-info-duration-wrap">
                                                <ul class="d-flex align-items-center justify-content-between">
                                                    <li class="font-medium font-12">
                                                        <span class="iconify" data-icon="octicon:device-desktop-24"></span>{{ __('Video') }}<span
                                                            class="instructor-courses-info-duration-wrap-text font-medium color-heading">({{ @$course->lectures->count() }})</span>
                                                    </li>
                                                    <li class="font-medium font-12">
                                                        <span class="iconify" data-icon="ant-design:clock-circle-outlined"></span>{{ __('Duration') }}<span
                                                            class="instructor-courses-info-duration-wrap-text font-medium color-heading">({{ @$course->VideoDuration }})</span></li>
                                                    <li class="font-medium font-12">
                                                        <span class="iconify" data-icon="carbon:user-multiple"></span>{{ __('Enrolled') }}<span
                                                            class="instructor-courses-info-duration-wrap-text font-medium color-heading">({{ @$course->orderItems->count() }})</span>
                                                    </li>
                                                </ul>
                                            </div>

                                            <div class="instructor-my-course-item-left">
                                                <h5 class="card-title course-title"><a href="{{ route('course-details', $course->slug) }}">{{ Str::limit($course->title, 40) }}</a>
                                                </h5>
                                                <div class="course-item-bottom">
                                                    <div class="course-rating d-flex align-items-center">
                                                        <span class="font-medium font-14">{{ number_format($course->average_rating, 1) }}</span>
                                                        <ul class="rating-list d-flex align-items-center">
                                                            @include('frontend.course.render-course-rating')
                                                        </ul>
                                                        <span class="rating-count font-14">({{ @$course->reviews->count() }})</span>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="instructor-my-course-btns create-bundles-courses-check-btn">
                                            <div class="form-check appendAddRemoveBundleCourse{{ $course->id }}">
                                                <input class="form-check-input {{ in_array($course->id, $alreadyAddedBundleCourseIds) ? 'removeBundle' : 'addBundle' }} "
                                                       type="checkbox" @if(in_array($course->id, $alreadyAddedBundleCourseIds)) checked @endif data-course_id="{{$course->id}}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Course item end -->
                            @empty
                                <div class="col-12">
                                    <!-- If there is no data Show Empty Design Start -->
                                    <div class="empty-data">
                                        <img src="{{ asset('frontend/assets/img/empty-data-img.png') }}" alt="img" class="img-fluid">
                                        <h5 class="my-3">{{ __('Empty courses') }}</h5>
                                    </div>
                                    <!-- If there is no data Show Empty Design End -->
                                </div>
                            @endforelse

                            <!-- Pagination Start -->
                            @if(@$courses->hasPages())
                                {{ @$courses->links('frontend.paginate.paginate') }}
                            @endif
                            <!-- Pagination End -->

                        </div>
                    </div>
                    <div>
                        <a href="{{ route('organization.bundle-course.editStepOne', $bundle->uuid) }}" class="theme-btn theme-button3 quiz-back-btn">{{ __('Back') }}</a>
                        <a href="{{ route('organization.bundle-course.index') }}" class="theme-btn theme-button1">{{ __('Done') }}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <input type="hidden" class="addBundleCourseRoute" value="{{ route('organization.bundle-course.addBundleCourse') }}">
    <input type="hidden" class="removeBundleCourseRoute" value="{{ route('organization.bundle-course.removeBundleCourse') }}">
    <input type="hidden" class="bundle_id" value="{{ $bundle->id }}">
@endsection

@push('script')
    <script src="{{ asset('frontend/assets/js/custom/bundle.js') }}"></script>
@endpush
