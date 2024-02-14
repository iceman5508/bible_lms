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
                                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></li>
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
                            <h2>{{ __('Edit Bank') }}</h2>
                            <a href="{{ route('settings.bank.index') }}" class="btn btn-success btn-sm">
                                <i class="fa fa-arrow-left"></i> {{ __('Back') }}
                            </a>
                        </div>
                        <form action="{{ route('settings.bank.update', [$bank->id]) }}" method="post"
                            class="form-horizontal">
                            @csrf
                            @method('patch')
                            <div class="row">
                                <div class="col-6">
                                    <div class="input__group mb-25">
                                        <label for="name">{{ __('Name') }}</label>
                                        <input type="text" name="name" id="name" placeholder="{{ __('Type name') }}"
                                            value="{{ $bank->name }}" required>
                                        @if ($errors->has('name'))
                                            <span class="text-danger"><i class="fas fa-exclamation-triangle"></i>
                                                {{ $errors->first('name') }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="input__group mb-25">
                                        <label for="account_name">{{ __('Account Name') }}</label>
                                        <input type="text" name="account_name" id="account_name"
                                            placeholder="{{ __('Type Account Name') }}" value="{{ $bank->account_name }}" required>
                                        @if ($errors->has('account_name'))
                                            <span class="text-danger"><i class="fas fa-exclamation-triangle"></i>
                                                {{ $errors->first('account_name') }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="input__group mb-25">
                                        <label for="account_number">{{ __('Account Number') }}</label>
                                        <input type="text" name="account_number" id="account_number"
                                            placeholder="{{ __('Type Account Number') }}" value="{{ $bank->account_number }}" required>
                                        @if ($errors->has('account_number'))
                                            <span class="text-danger"><i class="fas fa-exclamation-triangle"></i>
                                                {{ $errors->first('account_number') }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="input__group mb-25">
                                        <label for="status">{{ __('Status') }}</label>
                                        <select name="status" id="status">
                                            <option value="1" {{ $bank->status == 1 ? 'selected' : '' }}>{{ __('Active') }}</option>
                                            <option value="0" {{ $bank->status != 1 ? 'selected' : '' }}>{{ __('Inactive') }}</option>
                                        </select>
                                    </div>
                                </div>

                            </div>


                            <div class="row mb-3">
                                <div class="col-md-12 text-right">
                                    <button type="submit" class="btn btn-purple">{{ __('Update') }}</button>
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
