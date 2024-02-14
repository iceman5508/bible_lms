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
                    <div class="customers__area bg-style mb-30">
                        <div class="item-title d-flex justify-content-between">
                            <h2>{{ __('Edit Country') }}</h2>
                            <a href="{{ route('settings.location.country.index') }}" class="btn btn-success btn-sm" >
                                <i class="fa fa-arrow-left"></i> {{ __('Back') }}
                            </a>
                        </div>
                        <form action="{{route('settings.location.country.update', [$country->id])}}" method="post" class="form-horizontal">
                            @csrf
                            @method('patch')
                            <div class="row">
                                <div class="col-6">
                                    <div class="input__group mb-25">
                                        <label for="country_name">{{ __('Name') }}</label>
                                        <input type="text" name="country_name" id="country_name" placeholder="{{ __('Type country name') }}" value="{{ $country->country_name }}" required>
                                        @if ($errors->has('country_name'))
                                            <span class="text-danger"><i class="fas fa-exclamation-triangle"></i> {{ $errors->first('country_name') }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="input__group mb-25">
                                        <label for="country_name">{{ __('Short Name') }}</label>
                                        <input type="text" name="short_name" id="short_name" placeholder="{{ __('Type short name') }}" value="{{ $country->short_name }}" required>
                                        @if ($errors->has('short_name'))
                                            <span class="text-danger"><i class="fas fa-exclamation-triangle"></i> {{ $errors->first('short_name') }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="input__group mb-25">
                                        <label for="phonecode">{{ __('Phone Code') }}</label>
                                        <input type="text" name="phonecode" id="phonecode" placeholder="{{ __('Type phone code') }}" value="{{ $country->phonecode }}" required>
                                        @if ($errors->has('phonecode'))
                                            <span class="text-danger"><i class="fas fa-exclamation-triangle"></i> {{ $errors->first('phonecode') }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="input__group mb-25">
                                        <label for="continent">{{ __('Continent') }}</label>
                                        <input type="text" name="continent" id="continent" placeholder="{{ __('Type continent') }}" value="{{ $country->continent }}" required>
                                        @if ($errors->has('continent'))
                                            <span class="text-danger"><i class="fas fa-exclamation-triangle"></i> {{ $errors->first('continent') }}</span>
                                        @endif
                                    </div>
                                </div>

                            </div>


                            <div class="row mb-3">
                                <div class="col-md-12 text-right">
                                    @updateButton
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
