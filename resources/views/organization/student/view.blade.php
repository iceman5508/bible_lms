@extends('layouts.organization')

@section('breadcrumb')
    <div class="page-banner-content text-center">
        <h3 class="page-banner-heading text-white pb-15"> {{ __('Students Profile') }} </h3>

        <!-- Breadcrumb Start-->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb justify-content-center">
                <li class="breadcrumb-item font-14"><a href="{{ route('organization.dashboard') }}">{{ __('Dashboard') }}</a>
                </li>
                <li class="breadcrumb-item font-14 active" aria-current="page">{{ __('Students Profile') }}</li>
            </ol>
        </nav>
    </div>
@endsection

@section('content')
    <div class="instructor-profile-right-part">
        <div class="instructor-quiz-list-page instructor-all-student-page">
            <div class="instructor-my-courses-title d-flex justify-content-between align-items-center">
                <h6>{{ __('Student Profile') }}</h6>
            </div>
            <div class="row instructor-dashboard-top-part">
                <div class="col-md-12 col-xxl-5">
                    <div class="organization-stu-profile-left theme-border radius-4 p-20 mb-25">

                        <div class="organization-student-profile">
                            <div class="organization-stu-top d-flex align-items-center">
                                <div class="flex-shrink-0 customer-review-item-img-wrap radius-50 overflow-hidden">
                                    <img src="{{ asset($student->user ? $student->user->image_path : '') }}" alt="user"
                                        class="radius-50 fit-image">
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="font-20">{{ $student->name }}</h6>
                                </div>
                            </div>
                            <div class="organization-stu-personal-info mt-3">
                                <h6 class="font-18">{{ __('Personal Information') }} </h6>
                                <p>{{ $student->about_me }}</p>
                            </div>
                        </div>
                        <div class="organization-stu-personal-info-tbl mt-3">
                            <div class="table-responsive">
                                <table class="table">
                                    <tr>
                                        <td>{{ __('Phone') }}:</td>
                                        <td>{{ $student->user->mobile_number }}</td>
                                    </tr>
                                    <tr>
                                        <td>{{ __('Email') }}:</td>
                                        <td>{{ $student->user->email }}</td>
                                    </tr>
                                    <tr>
                                        <td>{{ __('Address') }}:</td>
                                        <td>{{ $student->address }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 col-xxl-7">
                    <div class="row organization-stu-profile-right-top-part">
                        <div class="col-md-6 mb-30">
                            <div class="instructor-dashboard-top-part-item d-flex align-items-center radius-8">
                                <div class="instructor-dashboard-top-part-icon flex-shrink-0">
                                    <span class="iconify"
                                        data-icon="material-symbols:published-with-changes-rounded"></span>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="para-color font-14 font-semi-bold">{{ __('Total Enrolled') }}</h6>
                                    <h5>{{ $enrollments->count() }}</h5>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-30">
                            <div class="instructor-dashboard-top-part-item d-flex align-items-center radius-8">
                                <div class="instructor-dashboard-top-part-icon flex-shrink-0">
                                    <span class="iconify" data-icon="material-symbols:pending-actions-rounded"></span>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="para-color font-14 font-semi-bold">{{ __('Total Subscription') }}</h6>
                                    <h5>{{ $userPackageCount }}</h5>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12 mb-30">
                        <div
                            class="recently-added-courses-box organization-top-seller-box organization-stu-profile-right-certificate radius-8">
                            <div class="your-rank-title d-flex justify-content-between align-items-center mb-2">
                                <h6 class="font-18">{{ __('Enrollment') }}</h6>
                            </div>
                            <div class="table-responsive">
                                <table class="table">
                                    @foreach ($enrollments as $enrollment)
                                        <tr>
                                            <td>
                                                <a target="_blank" href="{{ route('course-details', [@$enrollment->course->slug]) }}">
                                                    <img src="{{ getImageFile(@$enrollment->course->image_path) }}"
                                                        class="img-fluid" width="80"></a>
                                            </td>
                                            <td class="text-end"><a target="_blank" href="{{ route('course-details', [@$enrollment->course->slug]) }}">{{ $enrollment->course->title }}</a></td>
                                        </tr>
                                    @endforeach
                                </table>
                            </div>
                        </div>
                        {{ @$enrollments->links('frontend.paginate.paginate') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
