@extends('layouts.instructor')

@section('breadcrumb')
    <div class="page-banner-content text-center">
        <h3 class="page-banner-heading text-white pb-15"> {{__('Add Certificate')}} </h3>

        <!-- Breadcrumb Start-->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb justify-content-center">
                <li class="breadcrumb-item font-14"><a href="{{route('instructor.dashboard')}}">{{__('Dashboard')}}</a></li>
                <li class="breadcrumb-item font-14"><a href="{{ route('instructor.certificate.index') }}">{{__('Manage Certificate')}}</a></li>
                <li class="breadcrumb-item font-14 active" aria-current="page">{{__('Add Certificate')}}</li>
            </ol>
        </nav>
    </div>
@endsection

@section('content')
    <div class="instructor-profile-right-part">
        <div class="instructor-quiz-list-page instructor-certificate-template-page">

            <div class="instructor-my-courses-title d-flex justify-content-between align-items-center">
                <h6>{{ __('Select Certificate') }}</h6>
            </div>

            <div class="row">
                @foreach($certificates as $certificate)
                <div class="col-12 col-md-6 col-lg-6 col-xl-6 col-xxl-4">
                    <div class="p-1 certificate-list" id="selected-{{$certificate->uuid}}">
                        <a href="javascript:void(0);" data-id="{{$certificate->uuid}}" class="set-uuid">
                            <img class="img-fluid" src="{{asset($certificate->image)}}" alt="{{$certificate->image}}">
                            <div class="certificate-template-name font-15 pt-2 pb-2">{{$certificate->title}}</div>
                        </a>
                    </div>
                </div>
                @endforeach
                @if(count($certificates))
                <div class="col-12 inner-next-btn-wrap">
                    <form method="post" action="{{route('instructor.certificate.setForCreate', [$course->uuid])}}">
                        @csrf
                        <input type="hidden" name="certificate_uuid" class="certificate-uuid">
                        <button type="submit" class="theme-btn theme-button1 default-hover-btn">{{ __('Next') }}</button>
                    </form>

                </div>
                @else
                        <div class="no-course-found text-center">
                            <img src="{{ asset('frontend/assets/img/empty-data-img.png') }}" alt="img" class="img-fluid">
                            <h5 class="mt-3">{{ __('Certificate Not Found') }}</h5>
                        </div>
                @endif
            </div>

        </div>
    </div>
@endsection

@push('style')

@endpush

@push('script')
    <script src="{{asset('frontend/assets/js/custom/certificate.js')}}"></script>
@endpush
