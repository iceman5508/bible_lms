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
                            <h2>{{ __('Edit Currency') }}</h2>
                            <a href="{{ route('settings.currency.index') }}" class="btn btn-success btn-sm" >
                                <i class="fa fa-arrow-left"></i> {{ __('Back') }}
                            </a>
                        </div>
                        <form action="{{route('settings.currency.update', [$currency->id])}}" method="post" class="form-horizontal">
                            @csrf
                            @method('patch')
                            <div class="row">
                                <div class="col-12">
                                    <div class="input__group mb-25">
                                        <label for="currency_code">{{ __('Currency ISO Code') }}</label>
                                        <input type="text" name="currency_code" id="currency_code" placeholder="{{ __('Type currency code') }}" value="{{ $currency->currency_code }}" required>
                                        @if ($errors->has('currency_code'))
                                            <span class="text-danger"><i class="fas fa-exclamation-triangle"></i> {{ $errors->first('currency_code') }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="input__group mb-25">
                                        <label for="symbol">{{ __('Symbol') }}</label>
                                        <input type="text" name="symbol" id="symbol" placeholder="{{ __('Type symbol') }}" value="{{ $currency->symbol }}" required>
                                        @if ($errors->has('symbol'))
                                            <span class="text-danger"><i class="fas fa-exclamation-triangle"></i> {{ $errors->first('symbol') }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="input__group mb-25">
                                        <label for="currency_placement">{{ __('Currency Placement') }}</label>
                                        <select name="currency_placement" id="" required>
                                            <option value="">--{{ __('Select Option') }}--</option>
                                            <option value="before" @if($currency->currency_placement == 'before') selected @endif>{{ __('Before Amount') }}</option>
                                            <option value="after" @if($currency->currency_placement == 'after') selected @endif>{{ __('After Amount') }}</option>
                                        </select>
                                        @if ($errors->has('currency_placement'))
                                            <span class="text-danger"><i class="fas fa-exclamation-triangle"></i> {{ $errors->first('currency_placement') }}</span>
                                        @endif
                                   </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-check">
                                        <input name="current_currency" class="form-check-input" type="checkbox" value="on" id="flexCheckChecked" {{ @$currency->current_currency == 'on' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="flexCheckChecked">
                                            {{ __('Current Currency') }}
                                        </label>
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
