@extends('layouts.instructor')

@section('breadcrumb')
    <div class="page-banner-content text-center">
        <h3 class="page-banner-heading text-white pb-15">Hey, {{auth::user()->instructor ? auth::user()->instructor->name : ''  }} <img
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
                                <h6 class="para-color font-11 font-semi-bold">{{__('Earning')}} <span
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
                            <h6 class="para-color font-11 font-semi-bold">{{__('Total Enroll')}} <span
                                    class="color-gray font-13 font-normal">({{__('This Month')}})</span></h6>
                            <h5>{{ @$total_enroll_this_month ?? 0 }}</h5>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 mb-30">
                    <div class="instructor-dashboard-top-part-item d-flex align-items-center radius-8">
                        <div class="instructor-dashboard-top-part-icon flex-shrink-0">
                            <span class="iconify" data-icon="ion:diamond-outline"></span>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="para-color font-11 font-semi-bold">{{__('Best Selling Course')}}
                            </h6>
                            <h5>{{ Str::limit(@$best_selling_course->course->title, 25) }}</h5>
                        </div>
                    </div>
                </div>

            </div>

            <div class="row recently-added-courses">

                <div class="col-lg-12 col-xl-6 mb-30">
                    <div class="recently-added-courses-box radius-8">
                        <div class="recently-added-courses-title d-flex justify-content-between align-items-center mb-4">
                            <h6 class="font-18">{{__('Recently Added Courses')}}</h6>
                            <a href="{{route('instructor.course')}}" class="bg-transparent color-heading font-11 font-medium">{{ __('View All') }}</a>
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
                    <div class="recently-added-courses-box radius-8">
                        <div class="your-rank-title d-flex justify-content-between align-items-center mb-4">
                            <h6 class="font-18">{{ __('Your Rank') }}</h6>
                            <a href="{{ route('instructor.ranking-level') }}" class="bg-transparent color-hover font-11 font-medium">{{__('List of Rank')}}</a>
                        </div>

                        <div class="ranking-items-wrap">
                                @if(!is_null($currentLevel))
                                    <div class="ranking-item d-flex align-items-center justify-content-between">
                                        <div class="d-flex align-items-center ranking-item-left">
                                            <div class="flex-shrink-0">
                                                <img src="{{ getImageFile($currentLevel->image_path) }}" alt="img" class="img-fluid">
                                            </div>
                                            <div class="flex-grow-1 ranking-content-in-right">
                                                <h6 class="font-15">{{ $currentLevel->name }}</h6>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center ranking-item-right">
                                            <div class="flex-shrink-0">
                                                <p class="font-15 color-heading text-center">{{__('Min Sale')}}</p>
                                                <p class="font-15 color-heading text-center">
                                                    @if(get_currency_placement() == 'after')
                                                        {{ $currentLevel->from }} {{ get_currency_symbol() }}
                                                    @else
                                                        {{ get_currency_symbol() }} {{ $currentLevel->from }}
                                                    @endif
                                                </p>
                                            </div>
                                            <div class="flex-shrink-0 ranking-content-in-right">
                                                <p class="font-15 color-heading text-center">{{__('Max Sale')}}</p>
                                                <p class="font-15 color-heading text-center">
                                                    @if(get_currency_placement() == 'after')
                                                        {{ $currentLevel->to }} {{ get_currency_symbol() }}
                                                    @else
                                                        {{ get_currency_symbol() }} {{ $currentLevel->to }}
                                                    @endif
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                @if(!is_null($secondLevel))
                                    <div class="ranking-item disable-ranking-item d-flex align-items-center justify-content-between">
                                        <div class="d-flex align-items-center ranking-item-left">
                                            <div class="flex-shrink-0">
                                                <img src="{{ getImageFile($secondLevel->image_path) }}" alt="img" class="img-fluid">
                                            </div>
                                            <div class="flex-grow-1 ranking-content-in-right">
                                                <h6 class="font-15">{{ $secondLevel->name }}</h6>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center ranking-item-right">
                                            <div class="flex-shrink-0">
                                                <p class="font-15 color-heading text-center">{{__('Min Sale')}}</p>
                                                <p class="font-15 color-heading text-center">
                                                    @if(get_currency_placement() == 'after')
                                                        {{ $secondLevel->from }} {{ get_currency_symbol() }}
                                                    @else
                                                        {{ get_currency_symbol() }} {{ $secondLevel->from }}
                                                    @endif
                                                </p>
                                            </div>
                                            <div class="flex-shrink-0 ranking-content-in-right">
                                                <p class="font-15 color-heading text-center">{{__('Max Sale')}}</p>
                                                <p class="font-15 color-heading text-center">
                                                    @if(get_currency_placement() == 'after')
                                                        {{ $secondLevel->to }} {{ get_currency_symbol() }}
                                                    @else
                                                        {{ get_currency_symbol() }} {{ $secondLevel->to }}
                                                    @endif
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                        </div>

                    </div>
                </div>
            </div>

            <div class="row upload-your-course-today mb-lg-0">
                <div class="col-lg-12 col-xl-6 mb-30">
                    <div class="upload-your-course-part radius-8">
                        <h6 class="font-18 text-white">{{__('Upload Your Course Today')}}</h6>
                        <a href="{{route('instructor.course.create')}}" class="upload-your-course-today-btn bg-hover text-white font-12 font-medium">{{__('Upload Course')}}</a>
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
    <!--Apexcharts js-->
    <script src="{{asset('common/js/apexcharts.min.js')}}"></script>

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
