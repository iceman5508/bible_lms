@extends('layouts.instructor')

@section('breadcrumb')
    <div class="page-banner-content text-center">
        <h3 class="page-banner-heading text-white pb-15">{{ __('Discussion') }}</h3>

        <!-- Breadcrumb Start-->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb justify-content-center">
                <li class="breadcrumb-item font-14"><a href="{{route('instructor.dashboard')}}">{{__('Dashboard')}}</a></li>
                <li class="breadcrumb-item font-14 active" aria-current="page">{{ __('Discussion') }}</li>
            </ol>
        </nav>
    </div>
@endsection

@section('content')
    <div class="instructor-profile-right-part">
        <div class="instructor-quiz-list-page instructor-discussion-page">

            <div class="instructor-my-courses-title d-flex justify-content-between align-items-center">
                <h6>{{ __('Discussion') }}</h6>
            </div>

            <div class="row">
                <div class="col-12 col-md-12 col-lg-12 col-xl-5 col-xxl-4">
                    <div class="instructor-disscussion-page-leftside border-1 radius-4">

                        <div class="message-user-top-part">
                            <div class="search-message-box input-group">
                                <span class="input-group-text bg-transparent" id="basic-addon2"><span class="iconify" data-icon="eva:search-fill"></span></span>
                                <input type="text" class="form-control font-14 search_course_title" placeholder="{{ __('Search Courses title') }}" aria-label="Username" aria-describedby="basic-addon2">
                            </div>
                        </div>

                        <div class="message-user-list-wrap instructor-discussion-list-wrap custom-scrollbar appendDiscussionCourseList">
                            @include('instructor.discussion.render-discussion-course-list')
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-12 col-lg-12 col-xl-7 col-xxl-8">
                    <div class="instructor-discussion-page-rightside radius-4 appendDiscussionList">

                    </div>
                </div>
            </div>

        </div>
    </div>

    <input type="hidden" class="firstCourseId" value="{{ @$first_course_id->id }}">
    <input type="hidden" class="discussionIndexRoute" value="{{ route('discussion.index') }}">
    <input type="hidden" class="courseDiscussionListRoute" value="{{ route('course-discussion.list') }}">
@endsection

@push('script')
    <script src="{{ asset('frontend/assets/js/instructor/discussion.js') }}"></script>
@endpush
