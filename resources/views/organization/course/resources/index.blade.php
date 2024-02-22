@extends('layouts.organization')

@section('breadcrumb')
    <div class="page-banner-content text-center">
        <h3 class="page-banner-heading text-white pb-15"> {{ __('Resources') }} </h3>

        <!-- Breadcrumb Start-->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb justify-content-center">
                <li class="breadcrumb-item font-14"><a href="{{route('organization.dashboard')}}">{{__('Dashboard')}}</a></li>
                <li class="breadcrumb-item font-14 " aria-current="page"><a href="{{ route('organization.course.index', $course->uuid) }}">{{__('My Courses')}}</a></li>
                <li class="breadcrumb-item font-14 active" aria-current="page">{{ __('Resource List') }}</li>
            </ol>
        </nav>
    </div>
@endsection

@section('content')
    <div class="instructor-profile-right-part">
        <div class="instructor-quiz-list-page instructor-resources-page">

            <div class="instructor-my-courses-title d-flex justify-content-between align-items-center">
                <h6>{{ __('Resources') }}</h6>
                <p>{{ @$course->title }}</p>
            </div>

            <div class="row">
                <div class="col-12">
                    @if(count($resources) > 0)
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                <tr>
                                    <th scope="col">{{ __('Resources list') }}</th>
                                    <th scope="col">{{ __('Action') }}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($resources as $resource)
                                    <tr>
                                        <td>
                                            <div class="resource-list-text">
                                                <span class="iconify" data-icon="akar-icons:link-chain"></span>
                                                <a href="{{ getVideoFile($resource->file) }}"
                                                   class="text-decoration-underline">{{ @$resource->original_filename }}</a>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="red-blue-action-btns">
                                                <a href="javascript:void(0);" data-url="{{route('organization.resource.delete', [$resource->uuid])}}"
                                                   class="theme-btn default-delete-btn-red delete">
                                                    <span class="iconify" data-icon="gg:trash"></span>{{ __('Delete') }}
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <!-- If there is no data Show Empty Design Start -->
                        <div class="empty-data">
                            <img src="{{ asset('frontend') }}/assets/img/empty-data-img.png" alt="img" class="img-fluid">
                            <h5 class="my-3">{{ __('Empty Resources') }}</h5>
                        </div>
                        <!-- If there is no data Show Empty Design End -->
                    @endif

                    <!-- Add Resource Button Start -->
                    <a href="{{ route('organization.resource.create', $course->uuid) }}" class="add-resources-btn theme-btn theme-button1 default-hover-btn">{{ __('Add Resource') }}</a>
                    <!-- Add Resource Button End -->

                </div>
            </div>

        </div>
    </div>
@endsection
