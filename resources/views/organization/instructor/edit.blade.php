@extends('layouts.organization')

@section('breadcrumb')
    <div class="page-banner-content text-center">
        <h3 class="page-banner-heading text-white pb-15"> {{ __('Edit Instructor') }} </h3>

        <!-- Breadcrumb Start-->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb justify-content-center">
                <li class="breadcrumb-item font-14"><a href="{{ route('organization.dashboard') }}">{{ __('Dashboard') }}</a>
                </li>
                <li class="breadcrumb-item font-14 active" aria-current="page">{{ __('Edit Instructor') }}</li>
            </ol>
        </nav>
    </div>
@endsection

@section('content')
    <div class="instructor-profile-right-part">
        <form method="POST" action="{{ route('organization.instructor.update', $instructor->uuid) }}"
            enctype="multipart/form-data" autocomplete="off">
            @csrf
            <div class="instructor-profile-info-box">
                <div class="instructor-my-courses-title d-flex justify-content-between align-items-center">
                    <h6>{{ __('Personal Info') }}</h6>
                </div>
                <div class="row">
                    <div class="col-md-4 mb-30">
                        <div class="upload-img-box mt-3 height-200">
                            <img src="{{ asset(@$instructor->user->image_path) }}">
                            <input type="file" name="image" id="image" accept="image/*"
                                onchange="previewFile(this)">
                            <div class="upload-img-box-icon">
                                <i class="fa fa-camera"></i>
                                <p class="m-0">{{ __('Image') }}</p>
                            </div>
                        </div>
                        @if ($errors->has('image'))
                            <span class="text-danger"><i class="fas fa-exclamation-triangle"></i>
                                {{ $errors->first('image') }}</span>
                        @endif
                        <div class="author-info">
                            <p class="font-14">{{ __('Accepted Image Files') }}: JPEG, JPG, PNG <br>
                                {{ __('Accepted Size') }}: 300 x 300 (1MB)</p>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-30">
                        <label class="font-medium font-15 color-heading">{{ __('First Name') }} <span
                                class="text-danger">*</span></label>
                        <input type="text" name="first_name" value="{{ $instructor->first_name }}"
                            placeholder="{{ __('First Name') }}" class="form-control" required>
                        @if ($errors->has('first_name'))
                            <span class="text-danger"><i class="fas fa-exclamation-triangle"></i>
                                {{ $errors->first('first_name') }}</span>
                        @endif
                    </div>
                    <div class="col-md-6 mb-30">
                        <label class="font-medium font-15 color-heading">{{ __('Last Name') }} <span
                                class="text-danger">*</span></label>
                        <input type="text" name="last_name" value="{{ $instructor->last_name }}"
                            placeholder="{{ __('Last Name') }}" class="form-control" required>
                        @if ($errors->has('last_name'))
                            <span class="text-danger"><i class="fas fa-exclamation-triangle"></i>
                                {{ $errors->first('last_name') }}</span>
                        @endif
                    </div>
                    <div class="col-md-6 mb-30">
                        <label class="font-medium font-15 color-heading">{{ __('Email') }} <span
                                class="text-danger">*</span></label>
                        <input type="email" name="email" value="{{ $instructor->user->email }}"
                            placeholder="{{ __('Email') }}" class="form-control" required>
                        @if ($errors->has('email'))
                            <span class="text-danger"><i class="fas fa-exclamation-triangle"></i>
                                {{ $errors->first('email') }}</span>
                        @endif
                    </div>
                    <div class="col-md-6 mb-30">
                        <label class="font-medium font-15 color-heading">{{ __('Password') }}</label>
                        <input type="password" name="password" value=""
                            placeholder="{{ __('Password') }}" class="form-control">
                        @if ($errors->has('password'))
                            <span class="text-danger"><i class="fas fa-exclamation-triangle"></i>
                                {{ $errors->first('password') }}</span>
                        @endif
                    </div>
                    <div class="col-md-6 mb-30">
                        <label class="font-medium font-15 color-heading">{{ __('Professional Title') }} <span
                                class="text-danger">*</span></label>
                        <input type="text" name="professional_title" value="{{ $instructor->professional_title }}"
                            placeholder="{{ __('Professional Title') }}" class="form-control" required>
                        @if ($errors->has('professional_title'))
                            <span class="text-danger"><i class="fas fa-exclamation-triangle"></i>
                                {{ $errors->first('professional_title') }}</span>
                        @endif
                    </div>
                    <div class="col-md-6 mb-30">
                        <label class="label-text-title color-heading font-medium font-16 mb-3">{{ __('Area Code') }}
                            <span class="text-danger">*</span></label>
                        <select class="form-control" name="area_code">
                            <option value>{{ __('Select Code') }}</option>
                            @foreach ($countries as $country)
                                <option value="{{ $country->phonecode }}"
                                    @if ($instructor->user->area_code == $country->phonecode) selected @endif>
                                    {{ $country->short_name . '(' . $country->phonecode . ')' }}</option>
                            @endforeach
                        </select>
                        @if ($errors->has('area_code'))
                            <span class="text-danger"><i class="fas fa-exclamation-triangle"></i>
                                {{ $errors->first('area_code') }}</span>
                        @endif
                    </div>
                    <div class="col-md-6 mb-30">
                        <label class="font-medium font-15 color-heading">{{ __('Phone Number') }}<span
                                class="text-danger">*</span></label>
                        <input type="text" name="phone_number" value="{{ $instructor->user->mobile_number }}"
                            placeholder="{{ __('Phone Number') }}" class="form-control">
                        @if ($errors->has('phone_number'))
                            <span class="text-danger"><i class="fas fa-exclamation-triangle"></i>
                                {{ $errors->first('phone_number') }}</span>
                        @endif
                    </div>
                    <div class="col-md-6 mb-30">
                        <label class="font-medium font-15 color-heading">{{ __('Country') }}</label>
                        <select name="country_id" id="country_id" class="form-select">
                            <option value="">{{ __('Select Country') }}</option>
                            @foreach ($countries as $country)
                                <option value="{{ $country->id }}"
                                    @if ($instructor->country_id) {{ $instructor->country_id == $country->id ? 'selected' : '' }} @endif>
                                    {{ $country->country_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6 mb-30">
                        <label class="font-medium font-15 color-heading">{{ __('State') }}</label>
                        <select name="state_id" id="state_id" class="form-select">
                            <option value="">{{ __('Select State') }}</option>
                            {{-- @if ($instructor->country_id)
                                @foreach ($states as $state)
                                    <option value="{{ $state->id }}"
                                        {{ $instructor->state_id == $state->id ? 'selected' : '' }}>{{ $state->name }}
                                    </option>
                                @endforeach
                            @endif --}}

                            @if (old('country_id'))
                                @foreach ($states as $state)
                                    <option value="{{ $state->id }}"
                                        {{ old('state_id') == $state->id ? 'selected' : '' }}
                                        data-value="{{ $state->name }}">{{ $state->name }}</option>
                                @endforeach
                            @else
                                @if ($instructor->country)
                                    @foreach ($instructor->country->states as $selected_state)
                                        <option value="{{ $selected_state->id }}"
                                            {{ $instructor->state_id == $selected_state->id ? 'selected' : '' }}
                                            data-value="{{ $selected_state->name }}">
                                            {{ $selected_state->name }}</option>
                                    @endforeach
                                @endif
                            @endif
                        </select>
                    </div>
                    <div class="col-md-6 mb-30">
                        <label class="font-medium font-15 color-heading">{{ __('City') }}</label>
                        <select name="city_id" id="city_id" class="form-select">
                            <option value="">{{ __('Select City') }}</option>
                            @if (old('state_id'))
                                @foreach ($cities as $city)
                                    <option value="{{ $city->id }}"
                                        {{ old('city_id') == $city->id ? 'selected' : '' }}
                                        data-value="{{ $city->name }}">{{ $city->name }}</option>
                                @endforeach
                            @else
                                @if ($instructor->state)
                                    @foreach ($instructor->state->cities as $selected_city)
                                        <option value="{{ $selected_city->id }}"
                                            {{ $instructor->city_id == $selected_city->id ? 'selected' : '' }}
                                            data-value="{{ $selected_city->name }}">
                                            {{ $selected_city->name }}</option>
                                    @endforeach
                                @endif
                            @endif
                        </select>
                    </div>
                    <div class="col-md-6 mb-30">
                        <label class="font-medium font-15 color-heading">{{ __('Address') }} <span
                                class="text-danger">*</span></label>
                        <input type="text" name="address" value="{{ $instructor->address }}"
                            placeholder="{{ __('Address') }}" class="form-control" required>
                        @if ($errors->has('address'))
                            <span class="text-danger"><i class="fas fa-exclamation-triangle"></i>
                                {{ $errors->first('address') }}</span>
                        @endif
                    </div>
                    <div class="col-md-6 mb-30">
                        <label class="font-medium font-15 color-heading">{{ __('Postal Code') }} <span
                                class="text-danger">*</span></label>
                        <input type="text" name="postal_code" value="{{ $instructor->postal_code }}"
                            placeholder="{{ __('Postal Code') }}" class="form-control" required>
                        @if ($errors->has('postal_code'))
                            <span class="text-danger"><i class="fas fa-exclamation-triangle"></i>
                                {{ $errors->first('postal_code') }}</span>
                        @endif
                    </div>
                    <div class="col-md-6 mb-30">
                        <label class="font-medium font-15 color-heading">{{ __('Gender') }}<span
                                class="text-danger">*</span></label>
                        <select name="gender" id="gender" class="form-select" required>
                            <option value="Male" {{ $instructor->gender == 'Male' ? 'selected' : '' }}>
                                {{ __('Male') }}
                            </option>
                            <option value="Female" {{ $instructor->gender == 'Female' ? 'selected' : '' }}>
                                {{ __('Female') }}</option>
                            <option value="Others" {{ $instructor->gender == 'Others' ? 'selected' : '' }}>
                                {{ __('Others') }}</option>
                        </select>
                    </div>
                    @php
                        $social_link = json_decode($instructor->social_link);
                    @endphp
                    <div class="col-md-6 mb-30">
                        <label class="font-medium font-15 color-heading">{{ __('Facebook') }} </label>
                        <input type="text" name="social_link[facebook]"
                            value="{{ $instructor->social_link ? $social_link->facebook : '' }}"
                            placeholder="https://facebook.com" class="form-control">
                    </div>
                    <div class="col-md-6 mb-30">
                        <label class="font-medium font-15 color-heading">{{ __('Twitter') }}</label>
                        <input type="text" name="social_link[twitter]"
                            value="{{ $instructor->social_link ? $social_link->twitter : '' }}" class="form-control"
                            placeholder="https://twitter.com">
                    </div>
                    <div class="col-md-6 mb-30">
                        <label class="font-medium font-15 color-heading">{{ __('Linkedin') }}</label>
                        <input type="text" name="social_link[linkedin]"
                            value="{{ $instructor->social_link ? $social_link->linkedin : '' }}" class="form-control"
                            placeholder="https://linkedin.com">
                    </div>
                    <div class="col-md-6 mb-30">
                        <label class="font-medium font-15 color-heading">{{ __('Pinterest') }}</label>
                        <input type="text" name="social_link[pinterest]"
                            value="{{ $instructor->social_link ? $social_link->pinterest : '' }}" class="form-control"
                            placeholder="https://pinterest.com">
                    </div>
                    <div class="col-md-12">
                        <label class="font-medium font-15 color-heading">{{ __('About Instructor') }} <span
                                class="text-danger">*</span></label>
                        <textarea name="about_me" id="" cols="15" rows="5" class="form-control" required>{{ $instructor->about_me }}</textarea>
                        @if ($errors->has('about_me'))
                            <span class="text-danger"><i class="fas fa-exclamation-triangle"></i>
                                {{ $errors->first('about_me') }}</span>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-12">
                <button type="submit"
                    class="theme-btn theme-button1 theme-button3 font-15 fw-bold">{{ __('Upadate') }}</button>
            </div>
        </form>
    </div>
@endsection

@push('style')
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/custom/img-view.css') }}">
@endpush

@push('script')
    <script src="{{ asset('frontend/assets/js/custom/organization.js') }}"></script>
    <script src="{{ asset('frontend/assets/js/custom/img-view.js') }}"></script>
@endpush
