@extends('layouts.auth')

@section('content')
    <!-- Sing In Area Start -->
    <section class="sign-up-page p-0">
        <div class="container-fluid p-0">
            <div class="row">
                <div class="col-md-5">
                    <div class="sign-up-left-content">
                        <div class="sign-up-top-logo">
                            <a href="{{ route('main.index') }}"><img src="{{getImageFile(get_option('app_logo'))}}" alt="logo"></a>
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
                        <form action="{{ route('forget-password.email') }}" method="post">
                            @csrf

                            <h5 class="mb-1">{{ __(get_option('forgot_title')) }}</h5>
                            <div class="forgot-pass-text mb-25 mt-3">
                                <p class="mb-2">{{ __(get_option('forgot_subtitle')) }}</p>
                            </div>

                            <div class="row mb-30">
                                <div class="col-md-12">
                                    <label class="label-text-title color-heading font-medium font-16 mb-3">{{ __('Email') }}</label>
                                    <input type="email" name="email" class="form-control" placeholder="{{ __('Type your email') }}">
                                </div>
                            </div>
                            <div class="row mb-30">
                                <div class="col-md-12">
                                    <button type="submit" class="theme-btn theme-button1 theme-button3 font-15 fw-bold w-100">{{ __(get_option('forgot_btn_name')) }}</button>
                                </div>
                            </div>
                            <div class="row mb-30">
                                <div class="col-md-12"><a href="{{ route('login') }}" class="color-hover text-decoration-underline font-medium">{{ __('Back to Login?') }}</a></div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Sing In Area End -->
@endsection
