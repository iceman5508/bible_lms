@extends('layouts.auth')

@section('content')
<!-- Sing Up Area Start -->
<section class="sign-up-page p-0">
    <div class="container-fluid p-0">
        <div class="row">
            <div class="col-md-5">
                <div class="sign-up-left-content">
                    <div class="sign-up-top-logo">
                        <a href="{{ route('main.index') }}"><img src="{{getImageFile(get_option('app_logo'))}}"
                                alt="logo"></a>
                    </div>
                    <p>{{ __(get_option('sign_up_left_text')) }}</p>
                    @if(get_option('sign_up_left_image'))
                    <div class="sign-up-bottom-img">
                        <img src="{{getImageFile(get_option('sign_up_left_image'))}}" alt="hero" class="img-fluid">
                    </div>
                    @endif
                </div>
            </div>
            <div class="col-md-7">
                <div class="sign-up-right-content bg-white">
                    <form method="POST" action="{{route('store.sign-up')}}">
                        @csrf
                        <h5 class="mb-1">{{__('Create an Account')}}</h5>
                        <p class="font-14 mb-30">{{__('Already have an account?')}} <a href="{{route('login')}}"
                                class="color-hover text-decoration-underline font-medium">{{__('Sign In')}}</a></p>

                        <div class="row mb-30">
                            <div class="col-md-12">
                                <label class="label-text-title color-heading font-medium font-16 mb-3"
                                    for="email">{{__('Email')}} <span class="text-danger">*</span></label>
                                <input type="email" name="email" id="email" value="{{old('email')}}"
                                    class="form-control" placeholder="Type your email">
                                @if ($errors->has('email'))
                                <span class="text-danger"><i class="fas fa-exclamation-triangle"></i> {{
                                    $errors->first('email') }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="row mb-30">
                            <div class="col-md-4">
                                <div class="input__group">
                                    <label class="label-text-title color-heading font-medium font-16 mb-3">{{ __('Code')
                                        }}</label>
                                    <select class="form-control" name="area_code">
                                        <option value >{{ __('Select Code') }}</option>
                                        @foreach ($countries as $country)
                                        <option value="{{ $country->phonecode }}" @if(old('area_code')==$country->
                                            phonecode) selected @endif>{{ $country->short_name.'('.$country->phonecode.')' }}</option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('area_code'))
                                    <span class="text-danger"><i class="fas fa-exclamation-triangle"></i> {{
                                        $errors->first('area_code') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-8">
                                <label class="label-text-title color-heading font-medium font-16 mb-3"
                                    for="mobile_number">{{__('Phone Number')}}</label>
                                <input type="text" name="mobile_number" id="mobile_number"
                                    value="{{old('mobile_number')}}" class="form-control"
                                    placeholder="Type your mobile number">
                                @if ($errors->has('mobile_number'))
                                <span class="text-danger"><i class="fas fa-exclamation-triangle"></i> {{
                                    $errors->first('mobile_number') }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="row mb-30">
                            <div class="col-md-6">
                                <label class="label-text-title color-heading font-medium font-16 mb-3"
                                    for="first_name">{{__('First Name')}} <span class="text-danger">*</span></label>
                                <input type="text" name="first_name" id="first_name" value="{{old('first_name')}}"
                                    class="form-control" placeholder="{{__('First Name')}}">
                                @if ($errors->has('first_name'))
                                <span class="text-danger"><i class="fas fa-exclamation-triangle"></i> {{
                                    $errors->first('first_name') }}</span>
                                @endif
                            </div>
                            <div class="col-md-6">
                                <label class="label-text-title color-heading font-medium font-16 mb-3"
                                    for="last_name">{{__('Last Name')}} <span class="text-danger">*</span></label>
                                <input type="text" name="last_name" id="last_name" value="{{old('last_name')}}"
                                    class="form-control" placeholder="{{__('Last Name')}}">
                                @if ($errors->has('last_name'))
                                <span class="text-danger"><i class="fas fa-exclamation-triangle"></i> {{
                                    $errors->first('last_name') }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="row mb-30">
                            <div class="col-md-12">
                                <label class="label-text-title color-heading font-medium font-16 mb-3"
                                    for="password">{{__('Password')}} <span class="text-danger">*</span></label>

                                <div class="form-group mb-0 position-relative">
                                    <input type="password" name="password" id="password" value="{{old('password')}}"
                                        class="form-control password" placeholder="*********">
                                    <span class="toggle cursor fas fa-eye pass-icon"></span>
                                </div>

                                @if ($errors->has('password'))
                                <span class="text-danger"><i class="fas fa-exclamation-triangle"></i> {{
                                    $errors->first('password') }}</span>
                                @endif

                            </div>

                        </div>

                        <div class="row mb-30">
                            <div class="col-md-12">
                                <div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="" id="flexCheckChecked"
                                            checked>
                                        <label class="form-check-label mb-0" for="flexCheckChecked">
                                            By clicking Create account, I agree that I have read and accepted the <a
                                                href="{{ route('terms-conditions') }}"
                                                class="color-hover text-decoration-underline">Terms of Use</a> and <a
                                                href="{{ route('privacy-policy') }}"
                                                class="color-hover text-decoration-underline">Privacy Policy.</a>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-12">
                                <button type="submit"
                                    class="theme-btn theme-button1 theme-button3 font-15 fw-bold w-100">{{__('Sign
                                    Up')}}</button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Sing Up Area End -->
@endsection
