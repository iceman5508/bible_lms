@extends('layouts.organization')

@section('breadcrumb')
    <div class="page-banner-content text-center">
        <h3 class="page-banner-heading text-white pb-15"> {{__('Manage Certificate')}} </h3>

        <!-- Breadcrumb Start-->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb justify-content-center">
                <li class="breadcrumb-item font-14"><a href="{{route('organization.dashboard')}}">{{__('Dashboard')}}</a></li>
                <li class="breadcrumb-item font-14 active" aria-current="page">{{__('Manage Certificate')}}</li>
            </ol>
        </nav>
    </div>
@endsection

@section('content')
    <div class="instructor-profile-right-part">
        <div class="instructor-quiz-list-page instructor-certificate-page">

            <div class="instructor-my-courses-title d-flex justify-content-between align-items-center">
                <h6>{{ __('Certificate') }}</h6>
            </div>

            <div class="row">
                <div class="col-12">
                    @if($courses->count() > 0)
                    <div class="table-responsive table-responsive-xl">
                        <table class="table">
                            <thead>
                            <tr>
                                <th scope="col">{{ __('Course Name') }}</th>
                                <th scope="col">{{ __('Total Certificate') }}</th>
                                <th scope="col">{{ __('Action') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($courses as $course)
                            <tr>
                                <td>
                                    <div class="instructor-certificate-course-list-wrap d-flex align-items-center">
                                        <div class="instructor-certificate-course-img">
                                            <img src="{{getImageFile($course->image_path)}}" alt="img" class="img-fluid">
                                        </div>
                                        <span title="{{$course->title}}">{{ Str::limit($course->title, 30)}}</span>
                                    </div>
                                </td>
                                <td>{{$course->certificate ? 1 : 0}}</td>
                                <td>
                                    @if($course->certificate)
                                        <div class="red-blue-action-btns">
                                            <a href="{{route('organization.certificate.edit', [$course->certificate->uuid])}}" class="theme-btn default-edit-btn-blue">{{__('Edit Certificate')}}</a>
                                            <a href="{{route('organization.certificate.view', [$course->certificate->uuid])}}" class="theme-btn theme-button1 green-theme-btn default-hover-btn">{{__('View Certificate')}}</a>
                                        </div>
                                    @else
                                        <div class="red-blue-action-btns">
                                            <a href="{{route('organization.certificate.add', [$course->uuid])}}" class="theme-btn theme-button1 default-hover-btn">{{ __('Add Certificate') }}</a>
                                            <a href="javascript:void(0)" class="theme-btn theme-button1 default-hover-btn disabled-btn">{{ __('View Certificate') }}</a>
                                        </div>
                                    @endif
                                </td>
                            </tr>
                            @endforeach

                            </tbody>
                        </table>
                    </div>
                    @else
                    <!-- If there is no data Show Empty Design Start -->
                    <div class="empty-data">
                        <img src="{{ asset('frontend/assets/img/empty-data-img.png') }}" alt="img" class="img-fluid">
                        <h5 class="my-3">{{ __('Empty Certificate') }}</h5>
                    </div>
                    <!-- If there is no data Show Empty Design End -->
                    @endif
                </div>
            </div>

        </div>
    </div>
@endsection
