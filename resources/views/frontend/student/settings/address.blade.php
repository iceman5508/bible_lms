@extends('frontend.layouts.app')

@section('content')
    <div class="bg-page">
        <!-- Page Header Start -->
        @include('frontend.student.settings.header')
        <!-- Page Header End -->

        <!-- Student Profile Page Area Start -->
        <section class="student-profile-page">
            <div class="container">
                <div class="student-profile-page-content">
                    <div class="row">
                        <div class="col-12">
                            <div class="row bg-white">
                                <!-- Student Profile Left part -->
                                @include('frontend.student.settings.sidebar')
                                <!-- Student Profile Right part -->
                                <div class="col-lg-9 p-0">
                                    <div class="student-profile-right-part">
                                        <h6>{{ __('Profile') }}</h6>
                                        <form action="{{ route('student.address.update', [$student->uuid]) }}" method="POST"
                                            enctype="multipart/form-data">
                                            @csrf
                                            <div class="row">
                                                <div class="col-md-12 mb-30">
                                                    <label
                                                        class="font-medium font-15 color-heading">{{ __('Country') }}</label>
                                                    <select name="country_id" id="country_id" class="form-select">
                                                        <option value="">{{ __('Select Country') }}</option>
                                                        @foreach ($countries as $country)
                                                            <option value="{{ $country->id }}"
                                                                @if (old('country_id')) {{ old('country_id') == $country->id ? 'selected' : '' }} @else  {{ $student->country_id == $country->id ? 'selected' : '' }} @endif>{{ $country->country_name }}</option>
                                                        @endforeach
                                                    </select>
                                                    @if ($errors->has('country_id'))
                                                        <span class="text-danger"><i
                                                                class="fas fa-exclamation-triangle"></i>
                                                            {{ $errors->first('country_id') }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-4 mb-30">
                                                    <label
                                                        class="font-medium font-15 color-heading">{{ __('State') }}</label>
                                                    <select name="state_id" id="state_id" class="form-select">
                                                        <option value="">{{ __('Select State') }}</option>
                                                        @if (old('country_id'))
                                                            @foreach ($states as $state)
                                                                <option value="{{ $state->id }}"
                                                                    {{ old('state_id') == $state->id ? 'selected' : '' }}>
                                                                    {{ $state->name }}</option>
                                                            @endforeach
                                                        @else
                                                            @if ($student->country)
                                                                @foreach ($student->country->states as $selected_state)
                                                                    <option value="{{ $selected_state->id }}"
                                                                        {{ $student->state_id == $selected_state->id ? 'selected' : '' }}>
                                                                        {{ $selected_state->name }}</option>
                                                                @endforeach
                                                            @endif
                                                        @endif
                                                    </select>
                                                    @if ($errors->has('state_id'))
                                                        <span class="text-danger"><i
                                                                class="fas fa-exclamation-triangle"></i>
                                                            {{ $errors->first('state_id') }}</span>
                                                    @endif
                                                </div>
                                                <div class="col-md-4 mb-30">
                                                    <label
                                                        class="font-medium font-15 color-heading">{{ __('City') }}</label>
                                                    <select name="city_id" id="city_id" class="form-select">
                                                        <option value="">{{ __('Select City') }}</option>
                                                        @if (old('state_id'))
                                                            @foreach ($cities as $city)
                                                                <option value="{{ $city->id }}"
                                                                    {{ old('city_id') == $city->id ? 'selected' : '' }}>
                                                                    {{ $city->name }}</option>
                                                            @endforeach
                                                        @else
                                                            @if ($student->state)
                                                                @foreach ($student->state->cities as $selected_city)
                                                                    <option value="{{ $selected_city->id }}"
                                                                        {{ $student->city_id == $selected_city->id ? 'selected' : '' }}>
                                                                        {{ $selected_city->name }}</option>
                                                                @endforeach
                                                            @endif
                                                        @endif
                                                    </select>
                                                    @if ($errors->has('city_id'))
                                                        <span class="text-danger"><i
                                                                class="fas fa-exclamation-triangle"></i>
                                                            {{ $errors->first('city_id') }}</span>
                                                    @endif
                                                </div>
                                                <div class="col-md-4 mb-30">
                                                    <label
                                                        class="font-medium font-15 color-heading">{{ __('Postal Code') }}</label>
                                                    <input type="text" name="postal_code"
                                                        value="{{ $student->postal_code }}" class="form-control"
                                                        placeholder="{{ __('Postal Code') }}">

                                                    @if ($errors->has('postal_code'))
                                                        <span class="text-danger"><i
                                                                class="fas fa-exclamation-triangle"></i>
                                                            {{ $errors->first('postal_code') }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12 mb-30">
                                                    <label
                                                        class="font-medium font-15 color-heading">{{ __('Address') }}</label>
                                                    <input type="text" name="address" value="{{ $student->address }}"
                                                        class="form-control" placeholder="{{ __('Type your address') }}">
                                                    @if ($errors->has('address'))
                                                        <span class="text-danger"><i
                                                                class="fas fa-exclamation-triangle"></i>
                                                            {{ $errors->first('address') }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="row mb-30 d-none">
                                                <div class="col-md-6">
                                                    <label class="font-medium font-15 color-heading">{{ __('Lat') }}</label>
                                                    <input type="number" step="any" name="lat" value="{{ $user->lat }}"
                                                        class="form-control" id="lat" placeholder="Type your lat">
                                                    @if ($errors->has('lat'))
                                                        <span class="text-danger"><i class="fas fa-exclamation-triangle"></i>
                                                            {{ $errors->first('lat') }}</span>
                                                    @endif
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="font-medium font-15 color-heading">{{ __('Long') }}</label>
                                                    <input type="number" step="any" name="long" value="{{ $user->long }}"
                                                        class="form-control" id="long" placeholder="Type your long">
                                                    @if ($errors->has('long'))
                                                        <span class="text-danger"><i class="fas fa-exclamation-triangle"></i>
                                                            {{ $errors->first('long') }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12 mb-30">
                                                    <label class="font-medium font-15 color-heading">{{ __('Select Location') }}</label>
                                                    <div id="map"></div>
                                                    <div class="position-relative">
                                                        <div class="position-absolute bottom-0 start-0">
                                                            <pre id="coordinates" class="coordinates"></pre>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <button type="submit"
                                                    class="theme-btn theme-button1 theme-button3 font-15 fw-bold">{{ __('Update') }}</button>
                                            </div>
                                        </form>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- Student Profile Page Area End -->
    </div>
@endsection

@push('style')
<link href="https://api.mapbox.com/mapbox-gl-js/v2.10.0/mapbox-gl.css" rel="stylesheet">
<script src="https://api.mapbox.com/mapbox-gl-js/v2.10.0/mapbox-gl.js"></script>
<style type="text/css">
    #map {
        width: 100%;
        height: 480px;
    }
    .coordinates {
        background: rgba(0, 0, 0, 0.5);
        color: #fff;
        padding: 5px 10px;
        margin: 0;
        font-size: 11px;
        line-height: 18px;
        border-radius: 3px;
        display: none;
    }
</style>
@endpush

@push('script')
    <script src="https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-geocoder/v5.0.0/mapbox-gl-geocoder.min.js"></script>
    <link rel="stylesheet" href="https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-geocoder/v5.0.0/mapbox-gl-geocoder.css" type="text/css">
    <script src="{{ asset('frontend/assets/js/custom/student-profile.js') }}"></script>
    <script>
        const ACCESSTOKENKEY = "{{ get_option('map_api_key') }}";
        const APPTHEMECOLOR="{{ empty(get_option('app_theme_color')) ? '#5e3fd7' : get_option('app_theme_color') }}"

        "use strict";
        mapboxgl.accessToken = ACCESSTOKENKEY;
        var latId = document.getElementById('lat');
        var longId = document.getElementById('long');
        var coordinates = document.getElementById('coordinates');
        var point = [longId.value, latId.value];
        var zoom = 2;
        var map = new mapboxgl.Map({
            container: 'map',
            style: 'mapbox://styles/mapbox/streets-v11',
            center: [longId.value, latId.value],
            zoom: zoom
        });

        var marker = new mapboxgl.Marker({
            color: APPTHEMECOLOR,
            draggable: true
        }).setLngLat(point)
            .addTo(map);

        function onDragEnd() {
            var lngLat = marker.getLngLat();
            longId.value = lngLat.lng;
            latId.value = lngLat.lat;
        }
        marker.on('dragend', onDragEnd);

        $(document).on("change", "#country_id,#state_id,#city_id", function () {
            var country = ($(document).find('#country_id').find(":selected").val() != '') ? ',' + $(document).find('#country_id').find(":selected").text() : '';
            var state = ($(document).find('#state_id').find(":selected").val() != '') ? ',' + $(document).find('#state_id').find(":selected").text() : '';
            var city = ($(document).find('#city_id').find(":selected").val() != '') ? $(document).find('#city_id').find(":selected").text() : '';
            var search = city + state + country;
            var url = 'https://api.mapbox.com/geocoding/v5/mapbox.places/' + search + '.json?access_token='+ACCESSTOKENKEY;
            $.ajax({
                url: url,
                cache: false,
                success: function (res) {
                    var newPoint = [res.features['0'].center[0], res.features['0'].center[1]];
                    map.project(newPoint);
                    map.flyTo({ 'center': newPoint });
                    marker.setLngLat(newPoint);
                    longId.value = newPoint[0];
                    latId.value = newPoint[1];
                }
            });
        });

        $(document).on('input', '#lat,#long', function () {
            var newPoint = [longId.value, latId.value];
            map.project(newPoint);
            map.flyTo({ 'center': newPoint });
            marker.setLngLat(newPoint);
        });
    </script>

@endpush
