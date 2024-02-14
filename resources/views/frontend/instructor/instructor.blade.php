@extends('frontend.layouts.app')
@push('style')
<style>
    .leaflet-popup-content {
        padding: 0 !important;
        width: 260px !important;
    }
</style>
@endpush
@section('meta')
    @php
        $metaData = getMeta('instructor');
    @endphp

    <meta name="description" content="{{ __($metaData['meta_description']) }}">
    <meta name="keywords" content="{{ __($metaData['meta_keyword']) }}">

    <!-- Open Graph meta tags for social sharing -->
    <meta property="og:type" content="Learning">
    <meta property="og:title" content="{{ __($metaData['meta_title']) }}">
    <meta property="og:description" content="{{ __($metaData['meta_description']) }}">
    <meta property="og:image" content="{{ __($metaData['og_image']) }}">
    <meta property="og:url" content="{{ url()->current() }}">

    <meta property="og:site_name" content="{{ __(get_option('app_name')) }}">

    <!-- Twitter Card meta tags for Twitter sharing -->
    <meta name="twitter:card" content="Learning">
    <meta name="twitter:title" content="{{ __($metaData['meta_title']) }}">
    <meta name="twitter:description" content="{{ __($metaData['meta_description']) }}">
    <meta name="twitter:image" content="{{ __($metaData['og_image']) }}">
@endsection
@section('content')
<div class="bg-page">
    <!-- Page Header Start -->
    <header class="page-banner-header blank-page-banner-header gradient-bg position-relative">
        <div class="section-overlay">
            <div class="blank-page-banner-wrap banner-less-header-wrap p-0">
            </div>
        </div>
    </header>
    <!-- Page Header End -->

    <!-- Instructor Search Map Area Start -->
    <section class="insturctor-search-map-area">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12 p-0">
                    <div id="map" class="w-100"></div>
                </div>
            </div>
        </div>
    </section>
    <!-- Instructor Search Map Area End -->

    <!-- Instructor Search Page Area Start -->
    <section class="courses-page-area instructor-search-page-area">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <!-- Instructors Filter Bar Start-->
                    <div class="courses-filter-bar instructors-filter-bar">
                        <div class="row align-items-center">
                            <div class="filter-bar-left col-sm-6 col-md-6 col-lg-6">
                                <div class="filter-bar-left-top">{{ __('Total Showing: 12') }}</div>
                            </div>

                            <div class="filter-bar-right col-sm-6 col-md-6 col-lg-6 text-end">
                                <div class="filter-box align-items-center justify-content-end">
                                    <div class="filter-box-short-icon color-gray font-15"><p>{{ __('Sort By') }}:</p></div>
                                    <select class="form-select form-select-sm filterSortBy" name="sort_by" onchange="filterData()">
                                        <option value="" selected>{{ __('Default') }}</option>
                                        <option value="asc">{{ __('Newest') }}</option>
                                        <option value="desc">{{ __('Oldest') }}</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                    </div>
                    <!-- Instructors Filter Bar End-->
                </div>
            </div>
            <div class="row shop-content">
                <!-- instructor Sidebar start-->
                <div class="col-md-4 col-lg-3 col-xl-3">
                    <div class="courses-sidebar-area bg-light">

                        <div class="accordion" id="accordionPanelsStayOpenExample">

                            <div class="accordion-item course-sidebar-accordion-item">
                                <h2 class="accordion-header course-sidebar-title" id="panelsStayOpen-headingTen">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseTen" aria-expanded="false" aria-controls="panelsStayOpen-collapseTen">
                                        {{ __('Filter') }}
                                    </button>
                                </h2>
                                <div id="panelsStayOpen-collapseTen" class="accordion-collapse collapse show" aria-labelledby="panelsStayOpen-headingTen">
                                    <div class="accordion-body">

                                        <div class="sidebar-switch-item d-flex justify-content-between mb-2">
                                            <div class="switch-item-left-text">Available for meeting</div>
                                            <div class="radio-right-text mx-3">
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" onchange="filterData()" type="checkbox" name="available_for_meeting" role="switch" id="flexSwitchCheckChecked1">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="sidebar-switch-item d-flex justify-content-between mb-2">
                                            <div class="switch-item-left-text">Free Meeting</div>
                                            <div class="radio-right-text mx-3">
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" onchange="filterData()" type="checkbox" name="free_meeting" role="switch" id="flexSwitchCheckChecked2">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="sidebar-switch-item d-flex justify-content-between mb-2">
                                            <div class="switch-item-left-text">Discount</div>
                                            <div class="radio-right-text mx-3">
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" onchange="filterData()" type="checkbox" name="discount_meeting" role="switch" id="flexSwitchCheckChecked3">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="accordion-item course-sidebar-accordion-item">
                                <h2 class="accordion-header course-sidebar-title" id="panelsStayOpen-headingOne">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseOne" aria-expanded="false" aria-controls="panelsStayOpen-collapseOne">
                                        {{ __('Categories') }}
                                    </button>
                                </h2>
                                <div id="panelsStayOpen-collapseOne" class="accordion-collapse collapse show" aria-labelledby="panelsStayOpen-headingOne">
                                    <div class="accordion-body">
                                        @foreach ($categories as $category)
                                        <div class="sidebar-radio-item">
                                            <div class="form-check">
                                                <input class="form-check-input filterDifficultyLevel" type="checkbox" name="category_ids[]" onclick="filterData()" value="{{ $category->id }}" id="exampleRadios{{ $category->id }}">
                                                <label class="form-check-label" for="exampleRadios{{ $category->id }}">
                                                    {{ $category->name }}
                                                </label>
                                            </div>
                                            <div class="radio-right-text"></div>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            
                            <div class="accordion-item course-sidebar-accordion-item">
                                <h2 class="accordion-header course-sidebar-title" id="panelsStayOpen-headingEight">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseEight" aria-expanded="false" aria-controls="panelsStayOpen-collapseEight">
                                        {{ __('Meeting Types') }}
                                    </button>
                                </h2>
                                <div id="panelsStayOpen-collapseEight" class="accordion-collapse collapse show" aria-labelledby="panelsStayOpen-headingEight">
                                    <div class="accordion-body">
                                        <div class="sidebar-radio-item">
                                            <div class="form-check">
                                                <input class="form-check-input filterDifficultyLevel" type="checkbox" name="available_type" onclick="filterData()" id="exampleRadiosDifficulty9" value="3">
                                                <label class="form-check-label" for="exampleRadiosDifficulty9">
                                                    {{ __('All') }}
                                                </label>
                                            </div>
                                            <div class="radio-right-text"></div>
                                        </div>
                                        <div class="sidebar-radio-item">
                                            <div class="form-check">
                                                <input class="form-check-input filterDifficultyLevel" type="checkbox" name="available_type" onclick="filterData()" id="exampleRadiosDifficulty10" value="1">
                                                <label class="form-check-label" for="exampleRadiosDifficulty10">
                                                    {{ __('In person') }}
                                                </label>
                                            </div>
                                            <div class="radio-right-text"></div>
                                        </div>
                                        <div class="sidebar-radio-item">
                                            <div class="form-check">
                                                <input class="form-check-input filterDifficultyLevel" type="checkbox" name="available_type" onclick="filterData()" id="exampleRadiosDifficulty11" value="2">
                                                <label class="form-check-label" for="exampleRadiosDifficulty11">
                                                    {{ __('Online') }}
                                                </label>
                                            </div>
                                            <div class="radio-right-text"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="accordion-item course-sidebar-accordion-item">
                                <h2 class="accordion-header course-sidebar-title" id="panelsStayOpen-headingThree">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseThree" aria-expanded="false" aria-controls="panelsStayOpen-collapseThree">
                                        {{ __('Rating') }}
                                    </button>
                                </h2>
                                <div id="panelsStayOpen-collapseThree" class="accordion-collapse collapse show" aria-labelledby="panelsStayOpen-headingThree">
                                    <div class="accordion-body">

                                        <div class="sidebar-radio-item">
                                            <div class="form-check">
                                                <input class="form-check-input filterRating" type="checkbox" name="rating[]" onclick="filterData()" id="exampleRadios41" value="5">
                                                <label class="form-check-label" for="exampleRadios41">
                                                    <span class="iconify" data-icon="bi:star-fill"></span>{{ __('5 star') }}
                                                </label>
                                            </div>
                                            <div class="radio-right-text"></div>
                                        </div>

                                        <div class="sidebar-radio-item">
                                            <div class="form-check">
                                                <input class="form-check-input filterRating" type="checkbox" name="rating[]" onclick="filterData()" id="exampleRadios42" value="4">
                                                <label class="form-check-label" for="exampleRadios42">
                                                    <span class="iconify" data-icon="bi:star-fill"></span>{{ __('4 star or above') }}
                                                </label>
                                            </div>
                                            <div class="radio-right-text"></div>
                                        </div>

                                        <div class="sidebar-radio-item">
                                            <div class="form-check">
                                                <input class="form-check-input filterRating" type="checkbox" name="rating[]" onclick="filterData()" id="exampleRadios43" value="3">
                                                <label class="form-check-label" for="exampleRadios43">
                                                    <span class="iconify" data-icon="bi:star-fill"></span>{{ __('3 star or above') }}
                                                </label>
                                            </div>
                                            <div class="radio-right-text"></div>
                                        </div>

                                        <div class="sidebar-radio-item">
                                            <div class="form-check">
                                                <input class="form-check-input filterRating" type="checkbox" name="rating[]" onclick="filterData()" id="exampleRadios44" value="2">
                                                <label class="form-check-label" for="exampleRadios44">
                                                    <span class="iconify" data-icon="bi:star-fill"></span>{{ __('2 star or above') }}
                                                </label>
                                            </div>
                                            <div class="radio-right-text"></div>
                                        </div>

                                        <div class="sidebar-radio-item">
                                            <div class="form-check">
                                                <input class="form-check-input filterRating" type="checkbox" name="rating[]" onclick="filterData()" id="exampleRadios45" value="1">
                                                <label class="form-check-label" for="exampleRadios45">
                                                    <span class="iconify" data-icon="bi:star-fill"></span>{{ __('1 star or above') }}
                                                </label>
                                            </div>
                                            <div class="radio-right-text"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="accordion-item course-sidebar-accordion-item">
                                <h2 class="accordion-header course-sidebar-title" id="panelsStayOpen-headingFour">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseFour" aria-expanded="false" aria-controls="panelsStayOpen-collapseFour">
                                        {{ __('Price') }}
                                    </button>
                                </h2>
                                <div id="panelsStayOpen-collapseFour" class="accordion-collapse collapse show" aria-labelledby="panelsStayOpen-headingFour">
                                    <div class="accordion-body">
                                        <div id="slider-range-instructor" class="price-filter-range mt-0"></div>

                                        <div class="range-value-box">
                                            <div class="range-value-wrap"><label for="price-min">$Min:</label><input disabled type="number" min=0 value=0 id="price-min" name="price_min" class="price-range-field" /></div>
                                            <div class="range-value-wrap"><label for="price-max">$Max:</label><input disabled type="number" max="{{ $priceMax }}" value="{{ $priceMax }}" name="price_max" id="price-max" class="price-range-field" /></div>
                                        </div>

                                    </div>
                                </div>
                            </div>

                            <div class="accordion-item course-sidebar-accordion-item">
                                <h2 class="accordion-header course-sidebar-title" id="panelsStayOpen-headingFive">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseFive" aria-expanded="false" aria-controls="panelsStayOpen-collapseFive">
                                        {{ __('Select Date') }}
                                    </button>
                                </h2>
                                <div id="panelsStayOpen-collapseFive" class="accordion-collapse collapse show" aria-labelledby="panelsStayOpen-headingFive">
                                    <div class="accordion-body">

                                        <div class="sidebar-radio-item">
                                            <div class="form-check">
                                                <input class="form-check-input filterDuration" type="checkbox" name="consultation_day[]" onclick="filterData()" id="exampleRadiosDuration35" value="0">
                                                <label class="form-check-label" for="exampleRadiosDuration35">{{ __('Sunday') }}</label>
                                            </div>
                                        </div>

                                        <div class="sidebar-radio-item">
                                            <div class="form-check">
                                                <input class="form-check-input filterDuration" type="checkbox" name="consultation_day[]" onclick="filterData()" id="exampleRadiosDuration36" value="1">
                                                <label class="form-check-label" for="exampleRadiosDuration36">{{ __('Monday') }}</label>
                                            </div>
                                        </div>
                                        <div class="sidebar-radio-item">
                                            <div class="form-check">
                                                <input class="form-check-input filterDuration" type="checkbox" name="consultation_day[]" onclick="filterData()" id="exampleRadiosDuration37" value="2">
                                                <label class="form-check-label" for="exampleRadiosDuration37">{{ __('Tuesday') }}</label>
                                            </div>
                                        </div>
                                        <div class="sidebar-radio-item">
                                            <div class="form-check">
                                                <input class="form-check-input filterDuration" type="checkbox" name="consultation_day[]" onclick="filterData()" id="exampleRadiosDuration38" value="3">
                                                <label class="form-check-label" for="exampleRadiosDuration38">{{ __('Wednesday') }}</label>
                                            </div>
                                        </div>
                                        <div class="sidebar-radio-item">
                                            <div class="form-check">
                                                <input class="form-check-input filterDuration" type="checkbox" name="consultation_day[]" onclick="filterData()" id="exampleRadiosDuration39" value="4">
                                                <label class="form-check-label" for="exampleRadiosDuration39">{{ __('Thursday') }}</label>
                                            </div>
                                        </div>
                                        <div class="sidebar-radio-item">
                                            <div class="form-check">
                                                <input class="form-check-input filterDuration" type="checkbox" name="consultation_day[]" onclick="filterData()" id="exampleRadiosDuration40" value="5">
                                                <label class="form-check-label" for="exampleRadiosDuration40">{{ __('Friday') }}</label>
                                            </div>
                                        </div>
                                        <div class="sidebar-radio-item">
                                            <div class="form-check">
                                                <input class="form-check-input filterDuration" type="checkbox" name="consultation_day[]" onclick="filterData()" id="exampleRadiosDuration41" value="6">
                                                <label class="form-check-label" for="exampleRadiosDuration41">{{ __('Saturday') }}</label>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>

                            <div class="accordion-item course-sidebar-accordion-item">
                                <h2 class="accordion-header course-sidebar-title" id="panelsStayOpen-headingNine">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseNine" aria-expanded="false" aria-controls="panelsStayOpen-collapseNine">
                                        {{ __('Select Location') }}
                                    </button>
                                </h2>
                                <div id="panelsStayOpen-collapseNine" class="accordion-collapse collapse show" aria-labelledby="panelsStayOpen-headingNine">
                                    <div class="accordion-body">

                                        <div class="sidebar-select-item me-4 mb-3">
                                            <label>Country</label>
                                            <select class="form-select" onchange="filterData()" name="country">
                                                <option value="" selected>Select Country</option>
                                                @foreach ($countries as $country)
                                                    <option value="{{ $country->id }}">{{ $country->country_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="sidebar-select-item me-4 mb-3">
                                            <label>Province</label>
                                            <select class="form-select" onchange="filterData()" name="state">
                                                <option value="" selected>Select Province</option>
                                                @foreach ($states as $state)
                                                    <option value="{{ $state->id }}">{{ $state->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="sidebar-select-item me-4 mb-3">
                                            <label>City</label>
                                            <select class="form-select" onchange="filterData()" name="city">
                                                <option value="" selected>Select City</option>
                                                @foreach ($cities as $city)
                                                    <option value="{{ $city->id }}">{{ $city->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>

                    </div>
                </div>
                <!-- instructor Sidebar End-->

                <!-- Show all Instructor area start-->
                <div class="col-md-8 col-lg-9 col-xl-9 show-all-course-area-wrap" id="instructorParentBlock">
                    @include('frontend.instructor.render_instructor')
                    <!-- all courses grid End-->
                </div>
            </div>
            <!-- Show all Instructor area End-->
        </div>
    </div>
</section>
<!-- Instructor Search Page Area End -->

</div>

@endsection
@push('style')
<link href='https://api.mapbox.com/mapbox.js/v3.3.1/mapbox.css' rel='stylesheet' />
    <link href='https://api.mapbox.com/mapbox.js/plugins/leaflet-markercluster/v1.0.0/MarkerCluster.css' rel='stylesheet' />
    <link href='https://api.mapbox.com/mapbox.js/plugins/leaflet-markercluster/v1.0.0/MarkerCluster.Default.css' rel='stylesheet' />
@endpush
@push('script')
<script src='https://api.mapbox.com/mapbox.js/v3.3.1/mapbox.js'></script>
<script src='https://api.mapbox.com/mapbox.js/plugins/leaflet-markercluster/v1.0.0/leaflet.markercluster.js'></script>
<script>
    const paginateRoute = "{{ route('instructor_more') }}";
    const filterRoute = "{{ route('filter.instructor') }}";
    const maxPrice = "{{ $priceMax }}";
    const mapData =  @json($mapData);
    L.mapbox.accessToken = "{{ get_option('map_api_key') }}";
</script>
<script src="{{ asset('frontend/assets/js/custom/instructor-filter.js') }}"></script>
@endpush
