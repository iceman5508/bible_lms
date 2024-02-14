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
                                <li class="breadcrumb-item"><a
                                        href="{{route('admin.dashboard')}}">{{__('Dashboard')}}</a></li>
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
                    <div class="item-top mb-30">
                        <h2>{{ __(@$title) }}</h2>
                    </div>
                    <div class="bg-dark-primary-soft-varient p-4 border-1">
                        <h2>{{ __('Subscription Mode') }}: </h2>
                        <p>{{ __('If subscription mode is active then by default all student have to use the subscription
                            package.') }}</p>
                    </div>
                    <br>
                    <form action="{{route('settings.subscription_mode.change')}}" method="post" class="form-horizontal">
                        @csrf

                        <div class="form-group text-black row mb-3">
                            <label class="col-lg-3">{{ __('Subscription') }} <span class="text-danger">*</span></label>
                            <div class="col-lg-9">
                                <div class="col-lg-9">
                                    <select name="subscription_mode" required class="form-control subscription_mode">
                                        <option value="0" @if(get_option('subscription_mode') !=1) selected @endif>{{
                                            __('No') }}</option>
                                        <option value="1" @if(get_option('subscription_mode')==1) selected @endif>{{
                                            __('Yes') }}</option>
                                    </select>
                                    @if ($errors->has('subscription_mode'))
                                    <span class="text-danger"><i class="fas fa-exclamation-triangle"></i> {{
                                        $errors->first('subscription_mode') }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="form-group text-black row mb-3">
                            <label class="col-lg-3">{{ __('Default Subscription Type') }} <span class="text-danger">*</span></label>
                            <div class="col-lg-9">
                                <div class="col-lg-9">
                                    <select name="subscription_default_package_type" class="form-control saas_mode">
                                        <option value="monthly" @if(get_option('subscription_default_package_type') == 'monthly') selected @endif>{{ __('Monthly') }}</option>
                                        <option value="yearly" @if(get_option('subscription_default_package_type') == 'yearly') selected @endif>{{ __('Yearly') }}</option>
                                    </select>
                                </div>
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