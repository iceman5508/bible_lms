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
                                <h2>{{ __('Application Setting') }}</h2>
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
                        <form action="{{route('settings.save.setting')}}" method="post" class="form-horizontal">
                            @csrf

                            <div class="row">
                                <div class="col-lg-4">
                                    <label>{{ __('Registration Bonus System') }} <span class="text-danger">*</span></label>
                                    <select name="registration_system_bonus_mode" required class="form-control">
                                        <option value="0" @if(get_option('registration_system_bonus_mode') != 1) selected @endif>{{ __('No') }}</option>
                                        <option value="1" @if(get_option('registration_system_bonus_mode') == 1) selected @endif>{{ __('Yes') }}</option>
                                    </select>
                                    @if ($errors->has('registration_system_bonus_mode'))
                                        <span class="text-danger"><i class="fas fa-exclamation-triangle"></i> {{ $errors->first('registration_system_bonus_mode') }}</span>
                                    @endif
                                </div>
                                <div class="col-lg-4">
                                    <label>{{ __('Bonus Amount').' ('.get_currency_code().')' }} <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" value="{{ get_option('registration_bonus_amount') }}" name="registration_bonus_amount" min="1">
                                    @if ($errors->has('registration_bonus_amount'))
                                        <span class="text-danger"><i class="fas fa-exclamation-triangle"></i> {{ $errors->first('registration_bonus_amount') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="row justify-content-end mt-3">
                                <div class="col-md-1">
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
