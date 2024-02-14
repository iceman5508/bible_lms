@extends('layouts.admin')

@section('content')
<!-- Page content area start -->
<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="customers__area bg-style mb-30">
                    <div class="item-title d-flex justify-content-between">
                        <h2>{{ __('Edit Student') }}</h2>
                    </div>
                    <form action="{{route('student.update', $student->uuid)}}" method="post" class="form-horizontal"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="input__group mb-25">
                                    <label>{{__('First Name')}} <span class="text-danger">*</span></label>
                                    <input type="text" name="first_name" value="{{ $student->first_name }}"
                                        placeholder="First name" class="form-control" required>
                                    @if ($errors->has('first_name'))
                                    <span class="text-danger"><i class="fas fa-exclamation-triangle"></i> {{
                                        $errors->first('first_name') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="input__group mb-25">
                                    <label>{{__('Last Name')}} <span class="text-danger">*</span></label>
                                    <input type="text" name="last_name" value="{{ $student->last_name }}"
                                        placeholder="Last name" class="form-control" required>
                                    @if ($errors->has('last_name'))
                                    <span class="text-danger"><i class="fas fa-exclamation-triangle"></i> {{
                                        $errors->first('last_name') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="input__group mb-25">
                                    <label>{{__('Email')}} <span class="text-danger">*</span></label>
                                    <input type="email" name="email" value="{{ @$student->user->email }}"
                                        placeholder="Email" class="form-control" required>
                                    @if ($errors->has('email'))
                                    <span class="text-danger"><i class="fas fa-exclamation-triangle"></i> {{
                                        $errors->first('email') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="input__group mb-25">
                                    <label>{{ __('Password') }} </label>
                                    <input type="password" name="password" value="" placeholder="Password"
                                        class="form-control">
                                    @if ($errors->has('password'))
                                    <span class="text-danger"><i class="fas fa-exclamation-triangle"></i> {{
                                        $errors->first('password') }}</span>
                                    @endif
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="input__group mb-25">
                                    <label class="label-text-title color-heading font-medium font-16 mb-3">{{ __('Area
                                        Code')
                                        }} <span class="text-danger">*</span></label>
                                    <select class="form-control" name="area_code">
                                        <option value>{{ __("Select Code") }}</option>
                                        @foreach ($countries as $country)
                                        <option value="{{ $country->phonecode }}" @if(old('area_code', @$student->
                                            user->area_code)==$country->
                                            phonecode) selected @endif>{{ $country->short_name.'('.$country->phonecode.')' }}</option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('area_code'))
                                    <span class="text-danger"><i class="fas fa-exclamation-triangle"></i> {{
                                        $errors->first('area_code') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="input__group mb-25">
                                    <label>{{__('Phone Number')}} <span class="text-danger">*</span></label>
                                    <input type="text" name="phone_number" value="{{ $student->phone_number }}"
                                        placeholder="Phone number" class="form-control" required>
                                    @if ($errors->has('phone_number'))
                                    <span class="text-danger"><i class="fas fa-exclamation-triangle"></i> {{
                                        $errors->first('phone_number') }}</span>
                                    @endif
                                </div>
                            </div>
                        
                            <div class="col-md-6">
                                <div class="input__group mb-25">
                                    <label>{{ __('Address') }} <span class="text-danger">*</span></label>
                                    <input type="text" name="address" value="{{ $student->address }}"
                                        placeholder="Address" class="form-control" required>
                                    @if ($errors->has('address'))
                                    <span class="text-danger"><i class="fas fa-exclamation-triangle"></i> {{
                                        $errors->first('address') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="input__group mb-25">
                                    <label>{{ __('Postal Code') }} <span class="text-danger">*</span></label>
                                    <input type="text" name="postal_code" value="{{ $student->postal_code }}"
                                        placeholder="Postal Code" class="form-control" required>
                                    @if ($errors->has('postal_code'))
                                    <span class="text-danger"><i class="fas fa-exclamation-triangle"></i> {{
                                        $errors->first('postal_code') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="input__group mb-25">
                                    <label>{{__('Country')}}</label>
                                    <select name="country_id" id="country_id" class="form-select">
                                        <option value="">{{__('Select Country')}}</option>
                                        @foreach($countries as $country)
                                        <option value="{{$country->id}}" @if(old('country_id'))
                                            {{old('country_id')==$country->id ? 'selected' : '' }}
                                            @else
                                            {{$student->country_id == $country->id ? 'selected' : '' }}
                                            @endif >{{$country->country_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="input__group mb-25">
                                    <label>{{__('State')}}</label>
                                    <select name="state_id" id="state_id" class="form-select">
                                        <option value="">{{__('Select State')}}</option>
                                        @if(old('country_id'))
                                        @foreach($states as $state)
                                        <option value="{{$state->id}}" {{old('state_id')==$state->id ? 'selected' : ''}}
                                            >{{$state->name}}</option>
                                        @endforeach
                                        @else
                                        @if($student->country)
                                        @foreach($student->country->states as $selected_state)
                                        <option value="{{$selected_state->id}}" {{$student->state_id ==
                                            $selected_state->id ? 'selected' : '' }} >{{$selected_state->name}}</option>
                                        @endforeach
                                        @endif
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="input__group mb-25">
                                    <label>{{__('City')}}</label>
                                    <select name="city_id" id="city_id" class="form-select">
                                        <option value="">{{__('Select City')}}</option>
                                        @if(old('state_id'))
                                        @foreach($cities as $city)
                                        <option value="{{$city->id}}" {{old('city_id')==$city->id ? 'selected' : '' }}
                                            >{{$city->name}}</option>
                                        @endforeach
                                        @else
                                        @if($student->state)
                                        @foreach($student->state->cities as $selected_city)
                                        <option value="{{$selected_city->id}}" {{$student->city_id == $selected_city->id
                                            ? 'selected' : '' }} >{{$selected_city->name}}</option>
                                        @endforeach
                                        @endif
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="input__group mb-25">
                                    <label>{{__('Gender')}}<span class="text-danger">*</span></label>
                                    <select name="gender" id="gender" class="form-select" required>
                                        <option value="">{{__('Select Option')}}</option>
                                        <option value="Male" {{ $student->gender == 'Male' ? 'selected' : '' }} >{{
                                            __('Male') }}</option>
                                        <option value="Female" {{ $student->gender == 'Female' ? 'selected' : '' }} >{{
                                            __('Female') }}</option>
                                        <option value="Others" {{ $student->gender == 'Others' ? 'selected' : '' }} >{{
                                            __('Others') }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="input__group mb-25">
                                    <label>{{ __('About Student') }} <span class="text-danger">*</span></label>
                                    <textarea name="about_me" id="" cols="15" rows="5" required>{{ $student->about_me
                                        }}</textarea>
                                    @if ($errors->has('about_me'))
                                    <span class="text-danger"><i class="fas fa-exclamation-triangle"></i> {{
                                        $errors->first('about_me') }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-3">
                                <div class="upload-img-box mb-25">
                                    @if(@$student->user->image)
                                    <img src="{{asset(@$student->user->image_path)}}" alt="img">
                                    @else
                                    <img src="" alt="No img">
                                    @endif
                                    <input type="file" name="image" id="image" accept="image/*"
                                        onchange="previewFile(this)">
                                    <div class="upload-img-box-icon">
                                        <i class="fa fa-camera"></i>
                                        <p class="m-0">{{__('Image')}}</p>
                                    </div>
                                </div>
                            </div>
                            @if ($errors->has('image'))
                            <span class="text-danger"><i class="fas fa-exclamation-triangle"></i> {{
                                $errors->first('image') }}</span>
                            @endif
                            <p>{{ __('Accepted Image Files') }}: JPEG, JPG, PNG <br> {{ __('Accepted Size') }}: 300 x
                                300 (1MB)</p>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-12 text-right">
                                <button class="btn btn-primary" type="submit">{{ __('Save') }}</button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>

    </div>
</div>
<!-- Page content area end -->
@endsection

@push('style')
<link rel="stylesheet" href="{{asset('admin/css/custom/image-preview.css')}}">
@endpush

@push('script')
<script src="{{asset('admin/js/custom/image-preview.js')}}"></script>
<script src="{{asset('admin/js/custom/admin-profile.js')}}"></script>
@endpush