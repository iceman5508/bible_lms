@extends('layouts.admin')

@section('content')
    <!-- Page content area start -->
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="breadcrumb__content">
                        <div class="breadcrumb__content__left">
                            <div class="breadcrumb__title">
                                <h2>{{ __('Application Settings') }}</h2>
                            </div>
                        </div>
                        <div class="breadcrumb__content__right">
                            <nav aria-label="breadcrumb">
                                <ul class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">{{__('Dashboard')}}</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">{{ __(@$title) }}</li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-3 col-md-4">
                    @include('admin.application_settings.sidebar')
                </div>
                <div class="col-lg-9 col-md-8">
                    <div class="email-inbox__area bg-style">
                        <div class="item-top mb-30"><h2>{{ __(@$title) }}</h2></div>
                        <form action="{{route('settings.social-login-settings.update')}}" method="post" class="form-horizontal" enctype="multipart/form-data">
                            @csrf
                            <div class="item-top mb-30"><h6>{{ __('Google Credentials') }}</h6></div>
                            <div class="form-group text-black row mb-3">
                                <label class="col-lg-3">{{ __('Google Login Status') }}</label>
                                <div class="col-lg-9">
                                    <select name="GOOGLE_LOGIN_STATUS" id="" class="form-control">
                                        <option value="">--{{ __('Select option') }}--</option>
                                        <option value="1" @if(env('GOOGLE_LOGIN_STATUS') == 1) selected @endif>{{ __('Active') }}</option>
                                        <option value="0" @if(env('GOOGLE_LOGIN_STATUS') != 1) selected @endif>{{ __('Deactivated') }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group text-black row mb-3">
                                <label class="col-lg-3">{{ __('Google Client ID') }}</label>
                                <div class="col-lg-9">
                                    <input type="text" name="GOOGLE_CLIENT_ID" value="{{env('GOOGLE_CLIENT_ID')}}" class="form-control">
                                </div>
                            </div>
                            <div class="form-group text-black row mb-3">
                                <label class="col-lg-3">{{ __('Google Client Secret') }} </label>
                                <div class="col-lg-9">
                                    <input type="text" name="GOOGLE_CLIENT_SECRET" value="{{env('GOOGLE_CLIENT_SECRET')}}" class="form-control">
                                </div>
                            </div>
                            <div class="form-group text-black row mb-3">
                                <label class="col-lg-3">{{ __('Google Redirect URL') }} </label>
                                <div class="col-lg-9">
                                    <input type="text" name="GOOGLE_REDIRECT_URL" value="{{env('GOOGLE_REDIRECT_URL')}}" class="form-control">
                                </div>
                            </div>
                            <hr>
                            <div class="item-top mb-30"><h6>{{ __('Facebook Credentials') }}</h6></div>
                            <div class="form-group text-black row mb-3">
                                <label class="col-lg-3">{{ __('Facebook Login Status') }}</label>
                                <div class="col-lg-9">
                                    <select name="FACEBOOK_LOGIN_STATUS" id="" class="form-control">
                                        <option value="">--{{ __('Select option') }}--</option>
                                        <option value="1" @if(env('FACEBOOK_LOGIN_STATUS') == 1) selected @endif>{{ __('Active') }}</option>
                                        <option value="0" @if(env('FACEBOOK_LOGIN_STATUS') != 1) selected @endif>{{ __('Deactivated') }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group text-black row mb-3">
                                <label class="col-lg-3">{{ __('Facebook Client ID') }}</label>
                                <div class="col-lg-9">
                                    <input type="text" name="FACEBOOK_CLIENT_ID" value="{{env('FACEBOOK_CLIENT_ID')}}" class="form-control">
                                </div>
                            </div>
                            <div class="form-group text-black row mb-3">
                                <label class="col-lg-3">{{ __('Facebook Client Secret') }} </label>
                                <div class="col-lg-9">
                                    <input type="text" name="FACEBOOK_CLIENT_SECRET" value="{{env('FACEBOOK_CLIENT_SECRET')}}" class="form-control">
                                </div>
                            </div>
                            <div class="form-group text-black row mb-3">
                                <label class="col-lg-3">{{ __('Facebook Redirect URL') }} </label>
                                <div class="col-lg-9">
                                    <input type="text" name="FACEBOOK_REDIRECT_URL" value="{{env('FACEBOOK_REDIRECT_URL')}}" class="form-control">
                                </div>
                            </div>
                            <hr>
                            <div class="item-top mb-30"><h6>{{ __('Twitter Credentials') }}</h6></div>
                            <div class="form-group text-black row mb-3">
                                <label class="col-lg-3">{{ __('Twitter Login Status') }} </label>
                                <div class="col-lg-9">
                                    <select name="TWITTER_LOGIN_STATUS" id="" class="form-control">
                                        <option value="">--{{ __('Select option') }}--</option>
                                        <option value="1" @if(env('TWITTER_LOGIN_STATUS') == 1) selected @endif>{{ __('Active') }}</option>
                                        <option value="0" @if(env('TWITTER_LOGIN_STATUS') != 1) selected @endif>{{ __('Deactivated') }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group text-black row mb-3">
                                <label class="col-lg-3">{{ __('Twitter Client ID') }}</label>
                                <div class="col-lg-9">
                                    <input type="text" name="TWITTER_CLIENT_ID" value="{{env('TWITTER_CLIENT_ID')}}" class="form-control">
                                </div>
                            </div>
                            <div class="form-group text-black row mb-3">
                                <label class="col-lg-3">{{ __('Twitter Client Secret') }} </label>
                                <div class="col-lg-9">
                                    <input type="text" name="TWITTER_CLIENT_SECRET" value="{{env('TWITTER_CLIENT_SECRET')}}" class="form-control">
                                </div>
                            </div>
                            <div class="form-group text-black row mb-3">
                                <label class="col-lg-3">{{ __('Twitter Redirect URL') }} </label>
                                <div class="col-lg-9">
                                    <input type="text" name="TWITTER_REDIRECT_URL" value="{{env('TWITTER_REDIRECT_URL')}}" class="form-control">
                                </div>
                            </div>

                            <div class="mb-20 row text-end">
                                <div class="col">
                                    <button type="submit" class="btn btn-blue float-right">{{__('Update')}}</button>
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

