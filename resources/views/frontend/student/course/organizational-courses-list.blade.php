@extends('frontend.layouts.app')

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
                                <h3 class="page-banner-heading color-heading pb-15">{{__('Organization Course')}}</h3>

                                <!-- Breadcrumb Start-->
                                <nav aria-label="breadcrumb">
                                    <ol class="breadcrumb justify-content-center">
                                        <li class="breadcrumb-item font-14"><a href="{{url('/')}}">{{__('Home')}}</a>
                                        </li>
                                        <li class="breadcrumb-item font-14 active" aria-current="page">
                                            {{__('Organization Course')}}</li>
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

    <!-- Wishlist Page Area Start -->
    <section class="wishlist-page-area my-courses-page">
        <div class="container">
            <div class="bg-body pt-4 row">

                @forelse($courses as $course)
                <!-- Course item start -->
                <div class="col-12 col-sm-6 col-lg-12">
                    <div class="card course-item instructor-my-course-item bg-white">
                        <div class="flex-shrink-0 overflow-hidden">
                            @if($course->private_mode)
                            <span
                                class="course-tag badge unpublish-badge radius-3 font-14 font-medium position-absolute">{{
                                __('Private') }}</span>
                            @elseif($course->status == 1)
                            <span
                                class="course-tag badge publish-badge radius-3 font-14 font-medium position-absolute">{{
                                __('Published') }}</span>
                            @elseif($course->status == 2)
                            <span
                                class="course-tag badge publish-badge radius-3 font-14 font-medium position-absolute">{{
                                __('Waiting for Review') }}</span>
                            @elseif($course->status == 3)
                            <span
                                class="course-tag badge unpublish-badge radius-3 font-14 font-medium position-absolute">{{
                                __('Hold') }}</span>
                            @elseif($course->status == 4)
                            <span
                                class="course-tag badge unpublish-badge radius-3 font-14 font-medium position-absolute">{{
                                __('Draft') }}</span>
                            @else
                            <span
                                class="course-tag badge unpublish-badge radius-3 font-14 font-medium position-absolute">{{
                                __('Pending') }}</span>
                            @endif
                            @if($course->learner_accessibility == 'paid')
                            <span
                                class="course-tag badge radius-3 font-14 font-medium position-absolute bg-white color-hover">
                                @if(get_currency_placement() == 'after')
                                {{$course->price}} {{ get_currency_symbol() }}
                                @else
                                {{ get_currency_symbol() }} {{$course->price}}
                                @endif
                            </span>
                            @elseif($course->learner_accessibility == 'free')
                            <span
                                class="course-tag badge radius-3 font-14 font-medium position-absolute bg-white color-hover">
                                {{ __("Free") }}
                            </span>
                            @endif

                            <a href="#"><img src="{{getImageFile($course->image_path)}}" alt="course"
                                    class="h-100 img-fluid"></a>
                        </div>
                        <div class="card-body">

                            <div class="instructor-courses-info-duration-wrap">
                                <ul class="d-flex align-items-center justify-content-between">
                                    <li class="font-medium font-12"><span class="iconify"
                                            data-icon="octicon:device-desktop-24"></span>Video<span
                                            class="instructor-courses-info-duration-wrap-text font-medium color-heading">({{
                                            @$course->lectures->count() }})</span></li>
                                    <li class="font-medium font-12"><span class="iconify"
                                            data-icon="ant-design:clock-circle-outlined"></span>Duration<span
                                            class="instructor-courses-info-duration-wrap-text font-medium color-heading">({{
                                            @$course->VideoDuration }})</span></li>
                                    <li class="font-medium font-12"><span class="iconify"
                                            data-icon="carbon:user-multiple"></span>Enrolled<span
                                            class="instructor-courses-info-duration-wrap-text font-medium color-heading">({{
                                            courseStudents($course->id) }})</span></li>
                                </ul>
                            </div>

                            <div class="instructor-my-course-item-left">
                                <h5 class="card-title course-title"><a
                                        href="{{ route('course-details', $course->slug) }}">{{
                                        Str::limit($course->title, 40) }}</a></h5>
                                <div class="course-item-bottom">
                                    <div class="course-rating d-flex align-items-center">
                                        <span class="font-medium font-14">{{ number_format($course->average_rating, 1)
                                            }}</span>
                                        <ul class="rating-list d-flex align-items-center">
                                            @include('frontend.course.render-course-rating')
                                        </ul>
                                        <span class="rating-count font-14">({{ @$course->reviews->count() }})</span>
                                    </div>
                                    <div class="instructor-my-courses-btns d-inline-flex">
                                        @if (is_null($course->organization_id))
                                        <a href="{{route('instructor.course.edit', [$course->uuid])}}"
                                            class="para-color font-14 font-medium d-flex align-items-center"><span
                                                class="iconify" data-icon="bx:bx-edit"></span>{{ __('Edit') }}</a>

                                        @if($course->user_id == auth()->id())
                                        <button
                                            class="para-color font-14 font-medium d-flex align-items-center deleteItem"
                                            data-formid="delete_row_form_{{$course->uuid}}">
                                            <span class="iconify" data-icon="ant-design:delete-outlined"></span>{{
                                            __('Delete') }}
                                        </button>

                                        <form action="{{ route('instructor.course.delete', [$course->uuid]) }}"
                                            method="post" id="delete_row_form_{{ $course->uuid }}">
                                            {{ method_field('DELETE') }}
                                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        </form>
                                        @endif
                                        @else
                                        <span class="para-color font-14 font-medium d-flex align-items-center">
                                            @if (!is_null($course->organization))
                                            <span>{{ __('Organization') }}</span> : {{ $course->organization->first_name
                                            }} {{ $course->organization->last_name }}
                                            @elseif(!is_null($course->instructor))
                                            <span>{{ __('Instructor') }}</span> : {{ $course->instructor->first_name }}
                                            {{ $course->instructor->last_name }}
                                            @endif
                                        </span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                        </div>
                        @if (is_null($course->organization))
                        <div class="instructor-my-course-btns">
                            <a href="{{ route('resource.index', [$course->uuid]) }}"
                                class="theme-button theme-button1 instructor-course-btn">{{ __('Resources') }}</a>
                            <a href="{{route('exam.index', [$course->uuid])}}"
                                class="theme-button theme-button1 instructor-course-btn">{{ __('Quiz') }}</a>
                            <a href="{{ route('assignment.index', [$course->uuid]) }}"
                                class="theme-button theme-button1 instructor-course-btn">{{ __('Assignment') }}</a>
                        </div>
                        @endif
                    </div>
                </div>
                <!-- Course item end -->
                @empty
                <!-- If there is no data Show Empty Design Start -->
                <div class="empty-data">
                    <img src="{{ asset('frontend/assets/img/empty-data-img.png') }}" alt="img" class="img-fluid">
                    <h5 class="my-3">{{ __('Empty Course') }}</h5>
                </div>
                <!-- If there is no data Show Empty Design End -->
                @endforelse

                <!-- Pagination Start -->
                @if(@$courses->hasPages())
                {{ @$courses->links('frontend.paginate.paginate') }}
                @endif
                <!-- Pagination End -->

            </div>
        </div>
    </section>
    <!-- Wishlist Page Area End -->

    <!--Write Review Modal Start-->
    <div class="modal fade" id="writeReviewModal" tabindex="-1" aria-labelledby="writeReviewModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="writeReviewModalLabel">{{ __('Write a Review') }}</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="row mb-4">
                            <div class="col-md-12 text-center">
                                <div class="btn-group give-rating-group" role="group"
                                    aria-label="Basic checkbox toggle button group">
                                    <input type="checkbox" class="btn-check" id="btncheck1" name="rating">
                                    <label class="give-rating-star" for="btncheck1"><span class="iconify"
                                            data-icon="bi:star-fill"></span></label>

                                    <input type="checkbox" class="btn-check" id="btncheck2" name="rating">
                                    <label class="give-rating-star" for="btncheck2"><span class="iconify"
                                            data-icon="bi:star-fill"></span></label>

                                    <input type="checkbox" class="btn-check" id="btncheck3" name="rating">
                                    <label class="give-rating-star" for="btncheck3"><span class="iconify"
                                            data-icon="bi:star-fill"></span></label>

                                    <input type="checkbox" class="btn-check" id="btncheck4" name="rating">
                                    <label class="give-rating-star" for="btncheck4"><span class="iconify"
                                            data-icon="bi:star-fill"></span></label>

                                    <input type="checkbox" class="btn-check" id="btncheck5" name="rating">
                                    <label class="give-rating-star" for="btncheck5"><span class="iconify"
                                            data-icon="bi:star-fill"></span></label>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-30">
                            <div class="col-md-12">
                                <label class="font-medium font-15 color-heading">{{ __('Feedback') }}</label>
                                <textarea class="form-control feedback" id="exampleFormControlTextarea1" rows="3"
                                    placeholder="{{ __('Please write your feedback here') }}"></textarea>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer d-flex justify-content-between align-items-center">
                    <button type="button" class="theme-btn theme-button3" data-bs-dismiss="modal">{{ __('Cancel')
                        }}</button>
                    <button type="button" class="theme-btn theme-button1 submitReview">{{ __('Submit Review')
                        }}</button>
                </div>
            </div>
        </div>
    </div>
    <!--Write Review Modal End-->

</div>
<input type="hidden" class="course_id">
<input type="hidden" class="studentReviewCreateRoute" value="{{ route('student.review.create') }}">
<input type="hidden" class="courseMyLearningRoute" value="{{ route('student.my-learning') }}">
@endsection

@push('script')
<script src="{{ asset('frontend/assets/js/course/student-my-learning-filter.js') }}"></script>
<script src="{{ asset('frontend/assets/js/course/course-review-create.js') }}"></script>
@endpush