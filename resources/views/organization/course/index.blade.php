@extends('layouts.organization')

@section('breadcrumb')
    <div class="page-banner-content text-center">
        <h3 class="page-banner-heading text-white pb-15"> {{__('My Courses')}} </h3>

        <!-- Breadcrumb Start-->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb justify-content-center">
                <li class="breadcrumb-item font-14"><a href="{{route('organization.dashboard')}}">{{__('Dashboard')}}</a></li>
                <li class="breadcrumb-item font-14 active" aria-current="page">{{__('My Courses')}}</li>
            </ol>
        </nav>
    </div>
@endsection

@section('content')
    <div class="instructor-profile-right-part">
        <div class="instructor-my-courses-box bg-white">
            <div class="instructor-my-courses-title d-flex justify-content-between align-items-center">
                <h6>{{ __('My Courses') }}</h6>
                <h6 class="font-16"><span class="font-medium">Total:</span> {{$number_of_course}}</h6>
            </div>
            <div class="row">

                @forelse($courses as $course)
                    <!-- Course item start -->
                    <div class="col-12 col-sm-6 col-lg-12">
                        <div class="card course-item instructor-my-course-item bg-white">
                            <div class="course-img-wrap flex-shrink-0 overflow-hidden">
                                @if($course->private_mode)
                                <span class="course-tag badge unpublish-badge radius-3 font-14 font-medium position-absolute">{{ __('Private') }}</span>
                                @elseif($course->status == 1)
                                 <span class="course-tag badge publish-badge radius-3 font-14 font-medium position-absolute">{{ __('Published') }}</span>
                                @elseif($course->status == 2)
                                 <span class="course-tag badge publish-badge radius-3 font-14 font-medium position-absolute">{{ __('Waiting for Review') }}</span>
                                @elseif($course->status == 3)
                                <span class="course-tag badge unpublish-badge radius-3 font-14 font-medium position-absolute">{{ __('Hold') }}</span>
                                @elseif($course->status == 4)
                                <span class="course-tag badge unpublish-badge radius-3 font-14 font-medium position-absolute">{{ __('Draft') }}</span>
                                @elseif($course->status == 6)
                                <span class="course-tag badge unpublish-badge radius-3 font-14 font-medium position-absolute">{{ __('Upcoming Pending') }}</span>
                                @elseif($course->status == 7)
                                <span class="course-tag badge publish-badge radius-3 font-14 font-medium position-absolute">{{ __('Upcoming') }}</span>
                                @else
                                <span class="course-tag badge unpublish-badge radius-3 font-14 font-medium position-absolute">{{ __('Pending') }}</span>
                                @endif
                                @if($course->learner_accessibility == 'paid')
                                    <span class="course-tag badge radius-3 font-14 font-medium position-absolute bg-white color-hover">
                                        @if(get_currency_placement() == 'after')
                                            {{$course->price}} {{ get_currency_symbol() }}
                                        @else
                                            {{ get_currency_symbol() }} {{$course->price}}
                                        @endif
                                    </span>
                                @elseif($course->learner_accessibility == 'free')
                                    <span class="course-tag badge radius-3 font-14 font-medium position-absolute bg-white color-hover">
                                        {{ __("Free") }}
                                    </span>
                                @endif

                                <a href="#"><img src="{{getImageFile($course->image_path)}}" alt="course" class="img-fluid"></a>
                            </div>
                            <div class="card-body">

                                <div class="instructor-courses-info-duration-wrap">
                                    <ul class="d-flex align-items-center justify-content-between">
                                        <li class="font-medium font-12"><span class="iconify" data-icon="octicon:device-desktop-24"></span>Video<span class="instructor-courses-info-duration-wrap-text font-medium color-heading">({{ @$course->lectures->count() }})</span></li>
                                        <li class="font-medium font-12"><span class="iconify" data-icon="ant-design:clock-circle-outlined"></span>Duration<span class="instructor-courses-info-duration-wrap-text font-medium color-heading">({{ @$course->VideoDuration }})</span></li>
                                        <li class="font-medium font-12"><span class="iconify" data-icon="carbon:user-multiple"></span>Enrolled<span class="instructor-courses-info-duration-wrap-text font-medium color-heading">({{  courseStudents($course->id) }})</span></li>
                                    </ul>
                                </div>

                                <div class="instructor-my-course-item-left">
                                    <h5 class="card-title course-title"><a href="{{ route('course-details', $course->slug) }}">{{ Str::limit($course->title, 40) }}</a></h5>
                                    <div class="course-item-bottom">
                                        <div class="course-rating d-flex align-items-center">
                                            <span class="font-medium font-14">{{ number_format($course->average_rating, 1) }}</span>
                                            <ul class="rating-list d-flex align-items-center">
                                                @include('frontend.course.render-course-rating')
                                            </ul>
                                            <span class="rating-count font-14">({{ @$course->reviews->count() }})</span>
                                        </div>
                                        <div class="instructor-my-courses-btns d-inline-flex">
                                            <a href="{{route('organization.course.edit', [$course->uuid])}}" class="para-color font-14 font-medium d-flex align-items-center"><span class="iconify" data-icon="bx:bx-edit"></span>{{ __("Edit") }}</a>

                                            @if($course->user_id == auth()->id())
                                            <button class="para-color font-14 font-medium d-flex align-items-center deleteItem" data-formid="delete_row_form_{{$course->uuid}}">
                                                <span class="iconify" data-icon="ant-design:delete-outlined"></span>{{ __("Delete") }}
                                            </button>

                                            <form action="{{ route('organization.course.delete', [$course->uuid]) }}" method="post" id="delete_row_form_{{ $course->uuid }}">
                                                {{ method_field('DELETE') }}
                                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                            </form>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="instructor-my-course-btns">
                                <a href="{{ route('organization.resource.index', [$course->uuid]) }}" class="theme-button theme-button1 instructor-course-btn">{{ __('Resources') }}</a>
                                <a href="{{route('organization.exam.index', [$course->uuid])}}" class="theme-button theme-button1 instructor-course-btn">{{ __('Quiz') }}</a>
                                <a href="{{ route('organization.assignment.index', [$course->uuid]) }}" class="theme-button theme-button1 instructor-course-btn">{{ __('Assignment') }}</a>
                            </div>
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
    </div>
@endsection

