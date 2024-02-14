@extends('layouts.organization')

@section('breadcrumb')
    <div class="page-banner-content text-center">
        <h3 class="page-banner-heading text-white pb-15"> {{__('View Notice')}} </h3>

        <!-- Breadcrumb Start-->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb justify-content-center">
                <li class="breadcrumb-item font-14"><a href="{{route('organization.dashboard')}}">{{__('Dashboard')}}</a></li>
                <li class="breadcrumb-item font-14"><a href="{{ route('organization.notice-board.course-notice.index') }}">{{ __('Notice Board Course List') }}</a></li>
                <li class="breadcrumb-item font-14"><a href="{{ route('organization.notice-board.index', $course->uuid) }}">{{__('Notice List')}}</a></li>
                <li class="breadcrumb-item font-14 active" aria-current="page">{{__('View Notice')}}</li>
            </ol>
        </nav>
    </div>
@endsection

@section('content')
  <div class="instructor-profile-right-part">
      <div class="instructor-quiz-list-page instructor-notice-details-page">
        <div class="instructor-my-courses-title d-flex justify-content-between align-items-center">
          <h6>{{ $course->title }}</h6>
      </div>

        <div class="row">
          <div class="col-12">
              <div class="row mb-30">
                <div class="col-md-12">
                    <label class="label-text-title color-heading font-medium font-16 mb-3">{{__('Notice Topic')}}</label>
                    <p>{{ $notice->topic }}</p>
                </div>
              </div>
              <div class="row mb-30">
                <div class="col-md-12">
                    <label class="label-text-title color-heading font-medium font-16 mb-3">{{__('Notice Details')}}</label>
                    <p>{{ $notice->details }}</p>
                </div>
              </div>

              <div>
                <a href="{{ route('organization.notice-board.index', $course->uuid) }}" class="theme-btn theme-button3 quiz-back-btn default-hover-btn">{{ __('Back') }}</a>
                <a href="{{ route('organization.notice-board.edit', [$course->uuid, $notice->uuid]) }}" class="theme-btn theme-button1 default-hover-btn">{{ __('Edit') }}</a>
              </div>
          </div>
        </div>
      </div>
  </div>
@endsection
