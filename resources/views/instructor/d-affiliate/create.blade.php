@extends('layouts.instructor')

@section('breadcrumb')
    <div class="page-banner-content text-center">
        <h3 class="page-banner-heading text-white pb-15"> {{__('Become An Affiliate')}} </h3>

        <!-- Breadcrumb Start-->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb justify-content-center">
                <li class="breadcrumb-item font-14"><a href="{{route('instructor.dashboard')}}">{{__('Dashboard')}}</a></li>
                <li class="breadcrumb-item font-14"><a href="{{ route('instructor.become-affiliate') }}">{{ __('Become An Affiliate') }}</a></li>
                <li class="breadcrumb-item font-14 active" aria-current="page">{{__('Apply')}}</li>
            </ol>
        </nav>
    </div>
@endsection

@section('content')
  <div class="instructor-profile-right-part">
      <div class="instructor-quiz-list-page instructor-notice-board-page">
        <div class="row">
          <div class="col-12">
            <form action="{{route('instructor.create-affiliate-request')}}" method="post">
                @csrf
                <div class="row">
                    <div class="col-md-6 mb-30">
                        <label class="label-text-title color-heading font-medium font-16 mb-3">{{ __('Name') }} <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="name" value="{{Auth::user()->name}}" readonly>
                    </div>
                    <div class="col-md-6 mb-30">
                        <label class="label-text-title color-heading font-medium font-16 mb-3">{{ __('Email') }} <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="email" value="{{Auth::user()->email}}" readonly>
                    </div>
                </div>
                <div class="row mb-30">
                    <div class="col-md-12">
                        <div class="label-text-title color-heading font-medium font-16 mb-3">{{ __('Address') }} <span class="text-danger">*</span></div>
                        <textarea class="form-control" name="address" cols="30" rows="10"></textarea>
                    </div>
                </div>
              <div>
                <button type="submit" class="theme-btn theme-button1 default-hover-btn">{{__('Apply')}}</button>
              </div>
            </form>

          </div>
        </div>

      </div>
  </div>
@endsection
