@extends('layouts.instructor')

@section('breadcrumb')
    <div class="page-banner-content text-center">
        <h3 class="page-banner-heading text-white pb-15"> {{__('Add_notice')}} </h3>

        <!-- Breadcrumb Start-->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb justify-content-center">
                <li class="breadcrumb-item font-14"><a href="{{route('instructor.dashboard')}}">{{__('Dashboard')}}</a></li>
                <li class="breadcrumb-item font-14"><a href="{{ route('notice-board.course-notice.index') }}">{{ __('Notice Board Course List') }}</a></li>
                <li class="breadcrumb-item font-14 active" aria-current="page">{{__('Add Notice')}}</li>
            </ol>
        </nav>
    </div>
@endsection

@section('content')
  <div class="instructor-profile-right-part">
      <div class="instructor-quiz-list-page instructor-notice-board-page">

        <div class="instructor-my-courses-title d-flex justify-content-between align-items-center">
          <h6>{{ $course->title }}</h6>
      </div>

        <div class="row">
          <div class="col-12">

            <form action="{{ route('notice-board.store', $course->uuid) }}" method="post">
                @csrf
              <div class="row mb-30">
                <div class="col-md-12">
                    <label class="label-text-title color-heading font-medium font-16 mb-3">{{__('Notice Topic')}}</label>
                    <input type="text" name="topic" class="form-control" placeholder="{{__('Notice Topic')}}" required value="{{ old('topic') }}">
                    @if ($errors->has('topic'))
                        <span class="text-danger"><i class="fas fa-exclamation-triangle"></i> {{ $errors->first('topic') }}</span>
                    @endif
                </div>
              </div>
              <div class="row mb-30">
                <div class="col-md-12">
                    <label class="label-text-title color-heading font-medium font-16 mb-3">{{__('Notice Details')}}</label>
                    <textarea class="form-control" name="details" cols="30" rows="10" placeholder="{{__('Notice Details')}}" required>{{ old('details') }}</textarea>
                    @if ($errors->has('details'))
                        <span class="text-danger"><i class="fas fa-exclamation-triangle"></i> {{ $errors->first('details') }}</span>
                    @endif
                </div>
              </div>

              <div>
                <a href="{{ route('notice-board.index', $course->uuid) }}" class="theme-btn theme-button3 quiz-back-btn default-hover-btn">{{__('Back')}}</a>
                <button type="submit" class="theme-btn theme-button1 default-hover-btn">{{__('Create')}}</button>
              </div>
            </form>

          </div>
        </div>

      </div>
  </div>
@endsection
