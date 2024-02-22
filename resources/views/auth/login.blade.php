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
                                            <div class="font-15 fw-bold gap-2 google-login theme-btn theme-button1 theme-button3 w-100">
                                                <svg version="1.1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" xmlns:xlink="http://www.w3.org/1999/xlink" style="display: block;"><path fill="#EA4335" d="M24 9.5c3.54 0 6.71 1.22 9.21 3.6l6.85-6.85C35.9 2.38 30.47 0 24 0 14.62 0 6.51 5.38 2.56 13.22l7.98 6.19C12.43 13.72 17.74 9.5 24 9.5z"></path><path fill="#4285F4" d="M46.98 24.55c0-1.57-.15-3.09-.38-4.55H24v9.02h12.94c-.58 2.96-2.26 5.48-4.78 7.18l7.73 6c4.51-4.18 7.09-10.36 7.09-17.65z"></path><path fill="#FBBC05" d="M10.53 28.59c-.48-1.45-.76-2.99-.76-4.59s.27-3.14.76-4.59l-7.98-6.19C.92 16.46 0 20.12 0 24c0 3.88.92 7.54 2.56 10.78l7.97-6.19z"></path><path fill="#34A853" d="M24 48c6.48 0 11.93-2.13 15.89-5.81l-7.73-6c-2.15 1.45-4.92 2.3-8.16 2.3-6.26 0-11.57-4.22-13.47-9.91l-7.98 6.19C6.51 42.62 14.62 48 24 48z"></path><path fill="none" d="M0 0h48v48H0z"></path></svg>
                                                <a href="{{ route('login.google') }}">{{ __('Sign in with Google') }}</a>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                @if(env('FACEBOOK_LOGIN_STATUS') == 1)
                                    <div class="row mb-2">
                                        <div class="col-md-12">
                                            <div class="theme-btn theme-button1 theme-button3 font-15 fw-bold w-100 facebook-login gap-2">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="2500" height="2500" viewBox="126.445 2.281 589 589"><circle cx="420.945" cy="296.781" r="294.5" fill="#3c5a9a"/><path d="M516.704 92.677h-65.239c-38.715 0-81.777 16.283-81.777 72.402.189 19.554 0 38.281 0 59.357H324.9v71.271h46.174v205.177h84.847V294.353h56.002l5.067-70.117h-62.531s.14-31.191 0-40.249c0-22.177 23.076-20.907 24.464-20.907 10.981 0 32.332.032 37.813 0V92.677h-.032z" fill="#fff"/></svg>
                                                <a href="{{ route('login.facebook') }}">{{ __('Sign in with Facebook') }}</a>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                @if(env('TWITTER_LOGIN_STATUS') == 1)
                                    <div class="row mb-0">
                                        <div class="col-md-12">
                                            <div class="theme-btn theme-button1 theme-button3 font-15 fw-bold w-100 twitter-login gap-2">
                                                <a href="{{ route('login.twitter') }}" class="">{{ __('Sign in with') }}</a>
                                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" width="24px" height="24px" viewBox="0 0 24 24" xml:space="preserve"><path d="M14.095479,10.316482L22.286354,1h-1.940718l-7.115352,8.087682L7.551414,1H1l8.589488,12.231093L1,23h1.940717  l7.509372-8.542861L16.448587,23H23L14.095479,10.316482z M11.436522,13.338465l-0.871624-1.218704l-6.924311-9.68815h2.981339  l5.58978,7.82155l0.867949,1.218704l7.26506,10.166271h-2.981339L11.436522,13.338465z"/></svg>
                                            </div>
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
