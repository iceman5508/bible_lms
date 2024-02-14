@extends('frontend.layouts.app')
@section('content')
    <div class="bg-page">
        <header class="page-banner-header blank-page-banner-header gradient-bg position-relative">
            <div class="section-overlay">
                <div class="blank-page-banner-wrap pb-0">
                    <div class="container">
                        <div class="row">
                            <div class="col-12 col-sm-12 col-md-12">
                                <div class="page-banner-content text-center">
                                    <h3 class="page-banner-heading color-heading pb-15">{{ __(@$pageTitle) }}</h3>
                                    <nav aria-label="breadcrumb">
                                        <ol class="breadcrumb justify-content-center">
                                            <li class="breadcrumb-item font-14"><a
                                                    href="{{ url('/') }}">{{ __('Home') }}</a></li>
                                            <li class="breadcrumb-item font-14 active" aria-current="page">
                                                {{ __(@$pageTitle) }}</li>
                                        </ol>
                                    </nav>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>
        <section class="wishlist-page-area my-courses-page">
            <div class="container">
                <div class="row d-flex justify-content-center">

                    <div class="col-md-5">
                        <div class="pricing-item position-relative  theme-border radius-8 mb-40 most-popular-plan">
                            <div class="most-popular-content position-absolute text-white font-14 text-center w-100">{{ __(@$userPackage->package->title) }}</div>
                            <div class="pricing-content-box">
                                <div class="pricing-content">
                                    <div class="pricing-content-inner d-flex flex-column justify-content-between">
                                        <div class="pricing-feature mb-30">
                                            <ul>
                                                <li>
                                                    <span
                                                        class="check-icon-wrap radius-50 font-13 d-inline-flex align-items-center justify-content-center me-2">
                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                            xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true"
                                                            role="img" class="iconify iconify--bi" width="1em"
                                                            height="1em" preserveAspectRatio="xMidYMid meet"
                                                            viewBox="0 0 16 16" data-icon="bi:check-lg">
                                                            <path fill="currentColor"
                                                                d="M12.736 3.97a.733.733 0 0 1 1.047 0c.286.289.29.756.01 1.05L7.88 12.01a.733.733 0 0 1-1.065.02L3.217 8.384a.757.757 0 0 1 0-1.06a.733.733 0 0 1 1.047 0l3.052 3.093l5.4-6.425a.247.247 0 0 1 .02-.022Z">
                                                            </path>
                                                        </svg>
                                                    </span>
                                                    {{ __('Enroll Date') }} {{ date('Y-m-d H:i',strtotime($userPackage->enroll_date)) }}
                                                </li>
                                                <li>
                                                    <span
                                                        class="check-icon-wrap radius-50 font-13 d-inline-flex align-items-center justify-content-center me-2">
                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                            xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true"
                                                            role="img" class="iconify iconify--bi" width="1em"
                                                            height="1em" preserveAspectRatio="xMidYMid meet"
                                                            viewBox="0 0 16 16" data-icon="bi:check-lg">
                                                            <path fill="currentColor"
                                                                d="M12.736 3.97a.733.733 0 0 1 1.047 0c.286.289.29.756.01 1.05L7.88 12.01a.733.733 0 0 1-1.065.02L3.217 8.384a.757.757 0 0 1 0-1.06a.733.733 0 0 1 1.047 0l3.052 3.093l5.4-6.425a.247.247 0 0 1 .02-.022Z">
                                                            </path>
                                                        </svg>
                                                    </span>
                                                    {{ __('Expired Date') }} {{ date('Y-m-d H:i',strtotime($userPackage->expired_date)) }}
                                                </li>
                                                <li>
                                                    <table class="table bg-white my-courses-page-table">
                                                        <thead>
                                                            <tr>
                                                                <th>{{ __('Name') }}</th>
                                                                <th>{{ __('Limit') }}</th>
                                                                <th>{{ __('Remaining') }}</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @if($userPackage->package_type == PACKAGE_TYPE_SAAS_INSTRUCTOR)
                                                            <tr>
                                                                <td>{{ __('Course') }}</td>
                                                                <td>{{ $userPackage->course }}</td>
                                                                <td>{{ $userPackage->course-$courseCount > 0 ? $userPackage->course-$courseCount : 0 }}</td>
                                                            </tr>
                                                            <tr>
                                                                <td>{{ __('Bundle Course') }}</td>
                                                                <td>{{ $userPackage->bundle_course }}</td>
                                                                <td>{{ $userPackage->bundle_course-$bundleCourseCount > 0 ? $userPackage->bundle_course-$bundleCourseCount :0 }}</td>
                                                            </tr>
                                                            <tr>
                                                                <td>{{ __('Subscription Course') }}</td>
                                                                <td>{{ $userPackage->subscription_course }}</td>
                                                                <td>{{ $userPackage->subscription_course-$subscriptionCourseCount > 0 ? $userPackage->subscription_course-$subscriptionCourseCount : 0 }}</td>
                                                            </tr>
                                                            <tr>
                                                                <td>{{ __('Consultancy') }}</td>
                                                                <td>{{ $userPackage->consultancy }}</td>
                                                                <td>{{ $userPackage->consultancy-$consultancyCount > 0 ? $userPackage->consultancy-$consultancyCount : 0 }}</td>
                                                            </tr>
                                                            <tr>
                                                                <td>{{ __('Admin Commission') }}</td>
                                                                <td colspan="2">{{ $userPackage->admin_commission }}%</td>
                                                            </tr>
                                                            @elseif($userPackage->package_type == PACKAGE_TYPE_SAAS_ORGANIZATION)
                                                            <tr>
                                                                <td>{{ __('Instructor') }}</td>
                                                                <td>{{ $userPackage->instructor }}</td>
                                                                <td>{{ $userPackage->instructor-$instructorCount > 0 ?$userPackage->instructor-$instructorCount : 0 }}</td>
                                                            </tr>
                                                            <tr>
                                                                <td>{{ __('Student') }}</td>
                                                                <td>{{ $userPackage->student }}</td>
                                                                <td>{{ $userPackage->student-$studentCount > 0 ?$userPackage->student-$studentCount : 0 }}</td>
                                                            </tr>
                                                            <tr>
                                                                <td>{{ __('Course') }}</td>
                                                                <td>{{ $userPackage->course }}</td>
                                                                <td>{{ $userPackage->course-$courseCount > 0 ?$userPackage->course-$courseCount : 0 }}</td>
                                                            </tr>
                                                            <tr>
                                                                <td>{{ __('Bundle Course') }}</td>
                                                                <td>{{ $userPackage->bundle_course }}</td>
                                                                <td>{{ $userPackage->bundle_course-$bundleCourseCount > 0 ?$userPackage->bundle_course-$bundleCourseCount : 0 }}</td>
                                                            </tr>
                                                            <tr>
                                                                <td>{{ __('Subscription Course') }}</td>
                                                                <td>{{ $userPackage->subscription_course }}</td>
                                                                <td>{{ $userPackage->subscription_course-$subscriptionCourseCount > 0 ?$userPackage->subscription_course-$subscriptionCourseCount : 0 }}</td>
                                                            </tr>
                                                            <tr>
                                                                <td>{{ __('Consultancy') }}</td>
                                                                <td>{{ $userPackage->consultancy }}</td>
                                                                <td>{{ $userPackage->consultancy-$consultancyCount > 0 ? $userPackage->consultancy-$consultancyCount : 0 }}</td>
                                                            </tr>
                                                            <tr>
                                                                <td>{{ __('Admin Commission') }}</td>
                                                                <td colspan="2">{{ $userPackage->admin_commission }}%</td>
                                                            </tr>
                                                            @endif
                                                        </tbody>
                                                    </table>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </section>

    </div>
@endsection
