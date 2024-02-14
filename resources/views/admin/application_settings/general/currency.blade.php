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
                            <h2>{{ __('Currency') }}</h2>
                            <button class="btn btn-success btn-sm" type="button" data-bs-toggle="modal" data-bs-target="#add-todo-modal">
                                <i class="fa fa-plus"></i> {{ __('Add Currency') }}
                            </button>
                        </div>
                        <div class="customers__table">
                                <table id="customers-table" class="row-border data-table-filter table-style">
                                    <thead>
                                    <tr>
                                        <th>{{__('SL')}}</th>
                                        <th>{{__('Currency Code')}}</th>
                                        <th>{{__('Symbol')}}</th>
                                        <th>{{__('Currency Placement')}}</th>
                                        <th>{{__('Action')}}</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($currencies as $currency)
                                        <tr class="removable-item">
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{$currency->currency_code}} <b>{{ @$currency->current_currency == 'on' ? '(Current Currency)' : '' }}</b></td>
                                            <td>{{@$currency->symbol}}</td>
                                            <td>
                                                @if($currency->currency_placement == 'before')
                                                    {{ __('Before Amount') }}
                                                @elseif($currency->currency_placement == 'after')
                                                    {{ __('After Amount') }}
                                                @endif
                                            </td>
                                            <td>
                                                <div class="action__buttons">
                                                    <a href="{{ route('settings.currency.edit', [$currency->id, Str::slug($currency->currency_code)]) }}" class=" btn-action mr-1 edit" data-toggle="tooltip" title="Edit">
                                                        <img src="{{asset('admin/images/icons/edit-2.svg')}}" alt="edit">
                                                    </a>
                                                    <button class="btn-action ms-2 deleteItem" data-formid="delete_row_form_{{$currency->id}}">
                                                        <img src="{{asset('admin/images/icons/trash-2.svg')}}" alt="trash">
                                                    </button>

                                                    <form action="{{ route('settings.currency.delete', $currency->id) }}" method="post" id="delete_row_form_{{ $currency->id }}">
                                                        {{ method_field('DELETE') }}
                                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Page content area end -->

    <!-- Add Modal section start -->
    <div class="modal fade" id="add-todo-modal" aria-hidden="true" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('Add Currency') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('settings.currency.store') }}" method="post">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="input__group mb-25">
                                    <label for="currency_code">{{ __('Currency ISO Code') }}</label>
                                    <input type="text" name="currency_code" id="currency_code" placeholder="{{ __('Type currency code') }}" value="{{ old('currency_code') }}" required>
                                    @if ($errors->has('currency_code'))
                                        <span class="text-danger"><i class="fas fa-exclamation-triangle"></i> {{ $errors->first('currency_code') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="input__group mb-25">
                                    <label for="symbol">{{ __('Symbol') }}</label>
                                    <input type="text" name="symbol" id="symbol" placeholder="{{ __('Type symbol') }}" value="{{ old('symbol') }}" required>
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
                                        <option value="before">{{ __('Before Amount') }}</option>
                                        <option value="after">{{ __('After Amount') }}</option>
                                    </select>
                                    @if ($errors->has('currency_placement'))
                                        <span class="text-danger"><i class="fas fa-exclamation-triangle"></i> {{ $errors->first('currency_placement') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-check">
                                    <input name="current_currency" class="form-check-input" type="checkbox" value="on" id="flexCheckChecked" >
                                    <label class="form-check-label" for="flexCheckChecked">
                                        {{ __('Current Currency') }}
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-purple">{{ __('Save') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Add Modal section end -->
@endsection

@push('style')
    <link rel="stylesheet" href="{{asset('admin/css/jquery.dataTables.min.css')}}">
@endpush

@push('script')
    <script src="{{asset('admin/js/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('admin/js/custom/data-table-page.js')}}"></script>
@endpush
