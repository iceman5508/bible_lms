@extends('layouts.organization')

@section('breadcrumb')
    <div class="page-banner-content text-center">
        <h3 class="page-banner-heading text-white pb-15"> {{ __('Instructor Profile') }} </h3>

        <!-- Breadcrumb Start-->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb justify-content-center">
                <li class="breadcrumb-item font-14"><a href="{{ route('organization.dashboard') }}">{{ __('Dashboard') }}</a>
                </li>
                <li class="breadcrumb-item font-14 active" aria-current="page">{{ __('Instructor Profile') }}</li>
            </ol>
        </nav>
    </div>
@endsection

@section('content')
    <div class="instructor-profile-right-part">
        <div class="instructor-quiz-list-page instructor-all-student-page">
            <div class="instructor-my-courses-title d-flex justify-content-between align-items-center">
                <h6>{{ __('Instructor Profile') }}</h6>
            </div>
            <div class="row instructor-dashboard-top-part">
                <div class="col-md-12 col-xxl-5">
                    <div class="organization-stu-profile-left theme-border radius-4 p-20 mb-25">

                        <div class="organization-student-profile">
                            <div class="organization-stu-top d-flex align-items-center">
                                <div class="flex-shrink-0 customer-review-item-img-wrap radius-50 overflow-hidden">
                                    <img src="http://localhost:8000/uploads_demo/user/1.jpg" alt="user" class="radius-50 fit-image">
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="font-20">Danial nrain</h6>
                                    <p>Php developer</p>
                                </div>
                            </div>
                            <div class="organization-stu-personal-info mt-3">
                                <h6 class="font-18">Personal Information </h6>
                                <p>Create a page to present who you are and what you do in one link.use about.me to grow their audience and get more clients.Create a page to present who you are and what you do in one link.</p>
                            </div>
                        </div>

                        <div class="organization-stu-personal-info-tbl mt-3">
                            <div class="table-responsive">
                                <table class="table">
                                    <tr>
                                        <td>Name:</td>
                                        <td>Johnny Deep</td>
                                    </tr>
                                    <tr>
                                        <td>Phone:</td>
                                        <td>(+880) 24563 24009</td>
                                    </tr>
                                    <tr>
                                        <td>Email:</td>
                                        <td>jhonny@gmail.com</td>
                                    </tr>
                                    <tr>
                                        <td>Address:</td>
                                        <td>Khulna, Bangladesh</td>
                                    </tr>
                                    <tr>
                                        <td>Location:</td>
                                        <td>Dhaka, Bangladesh</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <div class="organization-social-box mt-3">
                            <h6 class="font-18">Social Links</h6>
                            <ul class="flex-wrap">
                                <li><a href="#"><span class="iconify" data-icon="ant-design:facebook-filled"></span></a></li>
                                <li><a href="#"><span class="iconify" data-icon="ant-design:linkedin-filled"></span></a></li>
                                <li><a href="#"><span class="iconify" data-icon="ant-design:twitter-square-filled"></span></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 col-xxl-7">
                    <div class="row organization-stu-profile-right-top-part">
                        <div class="col-md-6 mb-30">
                            <div class="instructor-dashboard-top-part-item d-flex align-items-center radius-8">
                                <div class="instructor-dashboard-top-part-icon flex-shrink-0">
                                    <span class="iconify" data-icon="material-symbols:published-with-changes-rounded"></span>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="para-color font-14 font-semi-bold">{{__('Published Courses')}}</h6>
                                    <h5>06</h5>

                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-30">
                            <div class="instructor-dashboard-top-part-item d-flex align-items-center radius-8">
                                <div class="instructor-dashboard-top-part-icon flex-shrink-0">
                                    <span class="iconify" data-icon="material-symbols:pending-actions-rounded"></span>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="para-color font-14 font-semi-bold">{{__('Pending Courses')}}</h6>
                                    <h5>04</h5>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 mb-30">
                            <div class="instructor-dashboard-top-part-item d-flex align-items-center radius-8">
                                <div class="instructor-dashboard-top-part-icon flex-shrink-0">
                                    <span class="iconify" data-icon="material-symbols:attach-money-rounded"></span>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="para-color font-14 font-semi-bold">{{__('Total Earnings')}}</h6>
                                    <h5>$540.70</h5>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 mb-30">
                            <div class="instructor-dashboard-top-part-item d-flex align-items-center radius-8">
                                <div class="instructor-dashboard-top-part-icon flex-shrink-0">
                                    <span class="iconify" data-icon="healthicons:money-bag-outline"></span>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="para-color font-14 font-semi-bold">{{__('Total Sell')}}</h6>
                                    <h5>85</h5>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12 mb-30">
                        <div class="recently-added-courses-box organization-top-seller-box organization-stu-profile-right-certificate radius-8">
                            <div class="your-rank-title d-flex justify-content-between align-items-center mb-2">
                                <h6 class="font-18">{{ __('Certifications') }}</h6>
                            </div>
                            <div class="table-responsive">
                                <table class="table">
                                    <tr>
                                        <td>Title Of The Certificate</td>
                                        <td class="text-end">Year</td>
                                    </tr>
                                    <tr>
                                        <td>Title Of The Certificate</td>
                                        <td class="text-end">Year</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12 mb-30">
                        <div class="recently-added-courses-box organization-top-seller-box organization-stu-profile-right-certificate radius-8 mt-3">
                            <div class="your-rank-title d-flex justify-content-between align-items-center mb-2">
                                <h6 class="font-18">{{ __('Awards') }}</h6>
                            </div>
                            <div class="table-responsive">
                                <table class="table">
                                    <tr>
                                        <td>Title Of The Certificate</td>
                                        <td class="text-end">Year</td>
                                    </tr>
                                    <tr>
                                        <td>Title Of The Certificate</td>
                                        <td class="text-end">Year</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="organization-stu-skill-box mt-3">
                        <h6 class="font-18 mb-3">Skills</h6>
                        <ul class="flex-wrap">
                            <li><a href="#" class="radius-4 font-13 px-2 py-1">WEB DESIGN</a></li>
                            <li><a href="#" class="radius-4 font-13 px-2 py-1">WEB DEVELOPMENT</a></li>
                            <li><a href="#" class="radius-4 font-13 px-2 py-1">WEBFLOW DEVELOPMENT</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
