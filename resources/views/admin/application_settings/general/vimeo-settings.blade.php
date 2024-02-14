
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
                        <form action="{{route('settings.vimeo-settings.update')}}" method="post" class="form-horizontal">
                            @csrf
                            <div class="row input__group mb-25">
                                <label class="col-lg-3">{{ __('Vimeo Client ID') }} <span class="text-danger">*</span></label>
                                <div class="col-lg-9">
                                    <input type="text" name="VIMEO_CLIENT" value="{{ env('VIMEO_CLIENT') }}" class="form-control" >
                                </div>
                            </div>
                            <div class="row input__group mb-25">
                                <label class="col-lg-3">{{ __('Vimeo Secret') }}<span class="text-danger">*</span></label>
                                <div class="col-lg-9">
                                    <input type="text" name="VIMEO_SECRET" value="{{ env('VIMEO_SECRET') }}" class="form-control" >
                                </div>
                            </div>
                            <div class="row input__group mb-25">
                                <label class="col-lg-3">{{ __('Vimeo Token Access') }} <span class="text-danger">*</span></label>
                                <div class="col-lg-9">
                                    <input type="text" name="VIMEO_TOKEN_ACCESS" value="{{ env('VIMEO_TOKEN_ACCESS') }}" class="form-control" >
                                </div>
                            </div>
                            <div class="row input__group mb-25">
                                <label class="col-lg-3">{{ __('Vimeo Status') }} <span class="text-danger">*</span></label>
                                <div class="col-lg-9">
                                    <select name="VIMEO_STATUS" id="" class="form-control" required>
                                        <option value="">--{{ __('Select Option') }}--</option>
                                        <option value="active" @if(env('VIMEO_STATUS') == "active") selected @endif>{{ __('Active') }}</option>
                                        <option value="deactivated" @if(env('VIMEO_STATUS') == "deactivated") selected @endif>{{ __('Deactivated') }}</option>
                                    </select>
                                </div>
                            </div>

                            <div class="mb-20 row text-end">
                                <div class="col">
                                    <button type="submit" class="btn btn-blue float-right">{{ __('Update') }}</button>
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
