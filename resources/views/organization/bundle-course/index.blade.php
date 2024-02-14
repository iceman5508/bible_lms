@extends('layouts.organization')

@section('breadcrumb')
    <div class="page-banner-content text-center">
        <h3 class="page-banner-heading text-white pb-15"> {{__('Bundles Courses')}} </h3>

        <!-- Breadcrumb Start-->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb justify-content-center">
                <li class="breadcrumb-item font-14"><a href="{{route('organization.dashboard')}}">{{__('Dashboard')}}</a></li>
                <li class="breadcrumb-item font-14 active" aria-current="page">{{__('Bundles Courses')}}</li>
            </ol>
        </nav>
    </div>
@endsection

@section('content')
    <div class="instructor-profile-right-part">
        <div class="instructor-quiz-list-page instructor-add-assignment-page instructor-panel-bundles-courses-page">
            <div class="row m-0 quiz-list-page-top mb-4">
                <div class="col-md-8">
                    <div class="quiz-list-page-top-left">
                        <h5 class="text-white mb-5">{{ __('Add Your Bundles Courses') }}</h5>
                        <a href="{{ route('organization.bundle-course.createStepOne') }}" class="create-new-quiz-btn font-medium">{{ __('Create Bundles Courses') }}</a>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="quiz-list-page-top-right">
                        <img src="{{ asset('frontend/assets/img/quiz-img/bundles-courses.png') }}" alt="img" class="img-fluid">
                    </div>
                </div>

            </div>

            <div class="row">
                @forelse($bundles as $bundle)
                <!-- Course item start -->
                <div class="col-sm-12 col-md-6 col-lg-6 col-xl-4">
                    <div class="card course-item border-0 radius-3 bg-white">
                        <div class="course-img-wrap overflow-hidden">
                            <a href="{{ route('organization.bundle-course.editStepOne', $bundle->uuid) }}"><img src="{{ getImageFile($bundle->image) }}" alt="course" class="img-fluid"></a>
                        </div>
                        <div class="card-body">
                            <h5 class="card-title course-title"><a href="{{ route('organization.bundle-course.editStepOne', $bundle->uuid) }}">{{ $bundle->name }}</a></h5>
                            <p class="card-text instructor-name-certificate font-medium font-12">{{ @$bundle->user->instructor->name }}</p>
                            <div class="course-item-bottom">
                                <div class="instructor-bottom-item font-14 font-semi-bold mb-15">{{ __('Courses') }}: <span class="color-hover">{{ @$bundle->bundleCourses->count() }}</span></div>
                                <div class="instructor-bottom-item font-14 font-semi-bold mb-15">{{ __('Price') }}: <span class="color-hover">
                                        @if(get_currency_placement() == 'after')
                                            {{$bundle->price}} {{ get_currency_symbol() }}
                                        @else
                                            {{ get_currency_symbol() }} {{$bundle->price}}
                                        @endif</span></div>
                            </div>
                            <div class="instructor-my-courses-btns d-inline-flex ">
                                <a href="{{ route('organization.bundle-course.editStepOne', $bundle->uuid) }}"
                                   class="para-color font-14 font-medium d-flex align-items-center"><span
                                        class="iconify" data-icon="bx:bx-edit"></span>{{ __('Edit') }}</a>

                                <button data-formid="delete_row_form_{{$bundle->id}}" class="deleteItem para-color font-14 font-medium d-flex align-items-center">
                                    <span class="iconify" data-icon="ant-design:delete-outlined"></span>{{ __('Delete') }}
                                </button>

                                <form action="{{route('organization.bundle-course.delete', [$bundle->uuid])}}" method="post" id="delete_row_form_{{ $bundle->id }}">
                                    {{ method_field('DELETE') }}
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                </form>
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
                        <h5 class="my-3">{{ __('Empty Bundles Courses') }}</h5>
                    </div>
                    <!-- If there is no data Show Empty Design End -->
                </div>
                @endforelse

                <!-- Pagination Start -->
                @if(@$bundles->hasPages())
                    {{ @$bundles->links('frontend.paginate.paginate') }}
                @endif
                <!-- Pagination End -->

            </div>

        </div>
    </div>
@endsection
