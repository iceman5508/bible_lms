@extends('layouts.organization')

@section('breadcrumb')
    <div class="page-banner-content text-center">
        <h3 class="page-banner-heading text-white pb-15">Hey, {{auth::user()->organization ? auth::user()->organization->name : ''  }} <img
                src="{{asset('frontend/assets/img/student-profile-img/waving-hand.png')}}" alt="student" class="me-2"></h3>

        <!-- Breadcrumb Start-->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb justify-content-center">
                <li class="breadcrumb-item font-14"><a href="{{route('main.index')}}">{{__('Home')}}</a></li>
                <li class="breadcrumb-item font-14 active" aria-current="page">{{__('Dashboard')}}</li>
            </ol>
        </nav>
    </div>
@endsection

@section('content')
    <div class="instructor-profile-right-part">
        <div class="instructor-dashboard-box">

            <div class="row instructor-dashboard-top-part instructor-only-dashboard-top-part">

                <div class="col-md-4 mb-30">
                    <div class="instructor-dashboard-top-part-item d-flex align-items-center radius-8">
                        <div class="instructor-dashboard-top-part-icon flex-shrink-0">
                            <span class="iconify" data-icon="clarity:dollar-line"></span>
                        </div>
                        <div class="flex-grow-1 ms-3">

                            <div class="d-flex justify-content-between align-items-center">
                                <h6 class="para-color font-14 font-semi-bold">{{__('Earning')}}<span
                                        class="color-gray font-13 font-normal">({{__('This Month')}})</span></h6>
                            </div>

                            <h5>
                                @if(get_currency_placement() == 'after')
                                    {{ number_format(@$total_earning_this_month, 2) }} {{ get_currency_symbol() }}
                                @else
                                    {{ get_currency_symbol() }} {{ number_format(@$total_earning_this_month, 2) }}
                                @endif
                            </h5>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 mb-30">
                    <div class="instructor-dashboard-top-part-item d-flex align-items-center radius-8">
                        <div class="instructor-dashboard-top-part-icon flex-shrink-0">
                            <span class="iconify" data-icon="carbon:user-multiple"></span>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="para-color font-14 font-semi-bold">{{__('Total Enroll')}} <span
                                    class="color-gray font-13 font-normal">({{__('This Month')}})</span></h6>
                            <h5>{{ @$total_enroll_this_month ?? 0 }}</h5>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 mb-30">
                    <div class="instructor-dashboard-top-part-item d-flex align-items-center radius-8">
                        <div class="instructor-dashboard-top-part-icon flex-shrink-0">
                            <span class="iconify" data-icon="material-symbols:menu-book-outline"></span>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="para-color font-14 font-semi-bold">{{__('Total Course')}}
                            </h6>
                            <h5>{{ $totalCourse ?? 0 }}</h5>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 mb-30">
                    <div class="instructor-dashboard-top-part-item d-flex align-items-center radius-8">
                        <div class="instructor-dashboard-top-part-icon flex-shrink-0">
                            <span class="iconify" data-icon="mingcute:user-follow-line"></span>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="para-color font-14 font-semi-bold">{{__('Total Instructor')}}
                            </h6>
                            <h5>{{ $totalInstructor ?? 0 }}</h5>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 mb-30">
                    <div class="instructor-dashboard-top-part-item d-flex align-items-center radius-8">
                        <div class="instructor-dashboard-top-part-icon flex-shrink-0">
                            <span class="iconify" data-icon="fa-solid:user-graduate"></span>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="para-color font-14 font-semi-bold">{{__('Total Student')}}
                            </h6>
                            <h5>{{ $totalStudent ?? 0 }}</h5>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 mb-30">
                    <div class="instructor-dashboard-top-part-item d-flex align-items-center radius-8">
                        <div class="instructor-dashboard-top-part-icon flex-shrink-0">
                            <span class="iconify" data-icon="ion:diamond-outline"></span>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="para-color font-14 font-semi-bold">{{__('Best Selling Course')}}
                            </h6>
                            <h5>
                                @if (@$best_selling_course->course->title)
                                {{ Str::limit(@$best_selling_course->course->title, 25) }}
                                @else
                                    {{ __('No course found yet.') }}
                                @endif
                            </h5>
                        </div>
                    </div>
                </div>

            </div>

            <div class="row recently-added-courses">

                <div class="col-lg-12 col-xl-6 mb-30">
                    <div class="recently-added-courses-box radius-8">
                        <div class="recently-added-courses-title d-flex justify-content-between align-items-center mb-4">
                            <h6 class="font-18">{{__('Recently Added Courses')}}</h6>
                            <a href="{{route('organization.course.index')}}" class="bg-transparent color-heading font-11 font-medium">{{ __('View All') }}</a>
                        </div>
                        <div class="recently-added-course-item-wrap">
                            @foreach($recentCourses as $recentCourse)
                                <div class="recently-added-course-item d-flex align-items-center">
                                    <div class="recently-added-course-item-left flex-shrink-0">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0">
                                                <div class="user-img-wrap">
                                                    <img src="{{getImageFile($recentCourse->image_path)}}" alt="img">
                                                </div>
                                            </div>
                                            <div class="flex-grow-1 ms-3">
                                                <h6 class="font-15">{{ Str::limit($recentCourse->title, 25) }}</h6>
                                                <p class="font-14">{{ courseStudents($recentCourse->id) }} {{__('Enroll')}} </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="recently-added-course-item-right flex-grow-1 justify-content-end text-end ms-3">
                                        <button class="font-12 font-medium color-gray">{{@$recentCourse->created_at->diffForHumans()}}</button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="col-lg-12 col-xl-6 mb-30">
                    <div class="recently-added-courses-box organization-top-seller-box radius-8">
                        <div class="your-rank-title d-flex justify-content-between align-items-center mb-2">
                            <h6 class="font-18">{{ __('Top Instructor (This Month)') }}</h6>
                        </div>

                        <div class="ranking-items-wrap position-relative py-0 pb-3">
                            <div id="donut"></div>
                        </div>

                    </div>
                </div>
            </div>

            <div class="row upload-your-course-today mb-lg-0">
                <div class="col-lg-12 col-xl-6 mb-30">
                    <div class="upload-your-course-part radius-8">
                        <h6 class="font-18 text-white">{{__('Upload Your Course Today')}}</h6>
                        <a href="{{route('organization.course.create')}}" class="upload-your-course-today-btn bg-hover text-white font-12 font-medium">{{__('Upload Course')}}</a>
                    </div>
                </div>

                <div class="col-lg-6 mb-30">
                    <div class="instructor-dashboard-chart-box radius-8">
                        <div class="chart-title d-flex justify-content-between align-items-center">
                            <h6 class="font-18">{{__('Sale Statistics')}}</h6>
                        </div>
                        <!-- Chart -->
                        <div class="chart-wrap1">
                            <div id="chart1" class="w-100">
                            </div>
                        </div>
                        <!-- Chart -->
                    </div>
                </div>
            </div>

        </div>

    </div>
@endsection

@push('style')

@endpush

@push('script')
    <script>
        var allPercentage = @json($allPercentage);
        var allName = @json($allName);
        var currencySymbol = "{{ get_currency_symbol() }}";
    </script>
    <!--Apexcharts js-->
    <script src="{{asset('common/js/apexcharts.min.js')}}"></script>
    <script src="{{asset('frontend/assets/js/custom/organization-top-seller-chart.js')}}"></script>

    <!--Chart1 Script-->
    <script>
        // Chart Start
        var options = {
            chart: {
                height: '100%',
                type: "area"
            },
            dataLabels: {
                enabled: false,
            },
            series: [
                {
                    name: "Sale",
                    data: @json(@$totalPrice)
                }
            ],
            fill: {
                type: "gradient",
                colors: ['#5e3fd7'],
                gradient: {
                    shadeIntensity: 1,
                    opacityFrom: 0.7,
                    opacityTo: 0.9,
                    stops: [0, 90, 100]
                }
            },
            markers: {
                size: 4,
                colors: ['#fff'],
                strokeColors: '#5e3fd7',
                strokeWidth: 2,
                strokeOpacity: 0.9,
                strokeDashArray: 0,
                fillOpacity: 1,
                discrete: [],
                shape: "circle",
                radius: 2,
                offsetX: 0,
                offsetY: 0,
                onClick: undefined,
                onDblClick: undefined,
                showNullDataPoints: true,
                hover: {
                    size: undefined,
                    sizeOffset: 3
                }
            },
            stroke: {
                show: true,
                curve: 'smooth',
                lineCap: 'butt',
                colors: undefined,
                width: 3,
                dashArray: 0,
            },
            tooltip: {
                y: {
                    formatter: function (val) {
                        var result = val ;
                        if ("{{ get_currency_placement() }}" == 'after')
                        {
                             result = val + ' ' + "{{ get_currency_symbol() }}"
                        } else {
                             result = "{{ get_currency_symbol() }}" + ' ' + val
                        }
                        return result;
                    }
                }
            },
            xaxis: {
                categories: @json(@$months),
                axisBorder: {
                    show: false,
                    color: '#E7E3EB',
                    height: 1,
                    width: '100%',
                    offsetX: 0,
                    offsetY: 0
                },
                axisTicks: {
                    show: false,
                    borderType: 'solid',
                    color: '#E7E3EB',
                    height: 6,
                    offsetX: 0,
                    offsetY: 0
                },
            },
            yaxis: {
                show: true,
                showAlways: true,
                showForNullSeries: true,
                opposite: false,
                reversed: false,
                logarithmic: false,
                // logBase: 10,
                // tickAmount: 6,
                // min: 0.0,
                // max: 100.0,
                type: 'numeric',
                categories: [
                    '5', '10', '15', '20', '25', '30', '35', '40'
                ],
                axisBorder: {
                    show: false,
                    color: '#E7E3EB',
                    offsetX: 0,
                    offsetY: 0
                },
                axisTicks: {
                    show: false,
                    borderType: 'solid',
                    color: '#E7E3EB',
                    width: 6,
                    offsetX: 0,
                    offsetY: 0
                },
            },
        };
        var chart = new ApexCharts(document.querySelector("#chart1"), options);
        chart.render();

        // Chart End
    </script>
@endpush
