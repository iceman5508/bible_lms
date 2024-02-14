@extends('layouts.auth')

@section('content')
    <!-- Sing Up Area Start -->
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
                        <form method="POST" action="{{ route('login') }}">
                            @csrf

                            <h5 class="mb-1">{{__('Sign In')}}</h5>
                            <p class="font-14 mb-30">{{__('New User')}} ? <a href="{{route('sign-up')}}" class="color-hover text-decoration-underline font-medium">{{__('Create an Account')}}</a></p>

                            <div class="row mb-30">
                                <div class="col-md-12">
                                    <label class="label-text-title color-heading font-medium font-16 mb-3">{{__('Email or Phone')}}</label>
                                    <input type="text" name="email" value="{{old('email')}}" class="form-control email" placeholder="{{ __('Type your email or phone number') }}">
                                    @if ($errors->has('email'))
                                        <span class="text-danger"><i class="fas fa-exclamation-triangle"></i> {{ $errors->first('email') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="row mb-30">
                                <div class="col-md-12">
                                    <label class="label-text-title color-heading font-medium font-16 mb-3">{{__('Password')}}</label>
                                    <div class="form-group mb-0 position-relative">
                                        <input class="form-control password" name="password" value="{{old('password')}}" placeholder="*********" type="password">
                                        <span class="toggle cursor fas fa-eye pass-icon"></span>
                                    </div>

                                    @if ($errors->has('password'))
                                        <span class="text-danger"><i class="fas fa-exclamation-triangle"></i> {{ $errors->first('password') }}</span>
                                    @endif
                                </div>
                            </div>

                            <div class="row mb-30">
                                <div class="col-md-12"><a href="{{ route('forget-password') }}" class="color-hover text-decoration-underline font-medium">{{__('Forgot Password')}}?</a></div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-md-12">
                                    <button type="submit" class="theme-btn theme-button1 theme-button3 font-15 fw-bold w-100">{{__('Sign In')}}</button>
                                </div>
                            </div>

                            <div class="social-media-login-wrap">
                                @if(env('GOOGLE_LOGIN_STATUS') == 1)
                                    <div class="row mb-2">
                                        <div class="col-md-12">
                                            <a href="{{ route('login.google') }}" class="theme-btn theme-button1 theme-button3 font-15 fw-bold w-100 google-login">{{ __('Sign in with Google') }}</a>
                                        </div>
                                    </div>
                                @endif
                                @if(env('FACEBOOK_LOGIN_STATUS') == 1)
                                    <div class="row mb-2">
                                        <div class="col-md-12">
                                            <a href="{{ route('login.facebook') }}" class="theme-btn theme-button1 theme-button3 font-15 fw-bold w-100 facebook-login">{{ __('Sign in with Facebook') }}</a>
                                        </div>
                                    </div>
                                @endif
                                @if(env('TWITTER_LOGIN_STATUS') == 1)
                                    <div class="row mb-0">
                                        <div class="col-md-12">
                                            <a href="{{ route('login.twitter') }}" class="theme-btn theme-button1 theme-button3 font-15 fw-bold w-100 twitter-login">{{ __('Sign in with Twitter') }}</a>
                                        </div>
                                    </div>
                                @endif
                                @if(env('LOGIN_HELP') == 'active')
                                    <div class="table-responsive login-info-table mt-3">
                                        <table class="table table-bordered">
                                            <tbody>
                                            <tr>
                                                <td id="adminCredentialShow" class="login-info"><b>Admin:</b> admin@gmail.com | 123456</td>
                                                <td id="instructorCredentialShow" class="login-info"><b>Instructor:</b> instructor@gmail.com | 123456</td>
                                            </tr>
                                            <tr>
                                                <td id="studentCredentialShow" class="login-info"><b>Student:</b> student@gmail.com | 123456</td>
                                                <td id="affiliatorCredentialShow" class="login-info"><b>Affiliator:</b> affiliator@gmail.com | 123456</td>
                                            </tr>
                                            <tr>
                                                <td colspan="2" id="organizationCredentialShow" class="login-info"><b>Organization:</b> organization@gmail.com | 123456</td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                @endif
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Sing Up Area End -->
@endsection

@push('script')
    <script>
        "use strict"
        $('#adminCredentialShow').on('click', function (){
            $('.email').val('admin@gmail.com');
            $('.password').val('123456');
        });

        $('#instructorCredentialShow').on('click', function (){
            $('.email').val('instructor@gmail.com');
            $('.password').val('123456');
        });

        $('#studentCredentialShow').on('click', function (){
            $('.email').val('student@gmail.com');
            $('.password').val('123456');
        });

        $('#affiliatorCredentialShow').on('click', function (){
            $('.email').val('affililator@gmail.com');
            $('.password').val('123456');
        });

        $('#organizationCredentialShow').on('click', function (){
            $('.email').val('organization@gmail.com');
            $('.password').val('123456');
        });
    </script>
@endpush
