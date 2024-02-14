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
                            <h2>{{ __(@$title) }}</h2>
                            <button class="btn btn-success btn-sm" type="button" data-bs-toggle="modal"
                                data-bs-target="#add-todo-modal">
                                <i class="fa fa-plus"></i> {{ __('Add Bank') }}
                            </button>
                        </div>

                        <form action="{{ route('settings.save.setting') }}" class="form-horizontal" method="post">
                            @csrf
                            <div class="row">
                                <div class="col-4">
                                    <div class="input__group mb-25">
                                        <label>{{ __('Currency ISO Code') }} </label>
                                        <input type="text" name="bank_currency"
                                            value="{{ get_option('bank_currency') }}"
                                            class="form-control bank_currency">
                                    </div>
                                </div>
                                <div class="col-4">
                                    <label>{{ __('Conversion Rate') }} </label>
                                    <div class="input-group mb-3">
                                        <span class="input-group-text">{{ '1 ' . get_currency_symbol() . ' = ' }}</span>
                                        <input type="number" step="any" min="0" name="bank_conversion_rate"
                                            value="{{ get_option('bank_conversion_rate') ? get_option('bank_conversion_rate') : 1 }}"
                                            class="form-control">
                                        <span class="input-group-text bank_append_currency"></span>
                                    </div>
                                </div>

                                <div class="col-4">
                                    <div class="input__group mb-25">
                                        <label for="bank_status">{{ __('Status') }} </label>
                                        <div class="input-group mb-3">
                                            <select name="bank_status" id="bank_status" class="form-control">
                                                <option value="1" {{ get_option('bank_status') == 1 ? 'selected' : '' }}>{{ __('Enable') }} </option>
                                                <option value="0" {{ get_option('bank_status') == '0' ? 'selected' : '' }}>{{ __('Disable') }} </option>
                                            </select>
                                            <button class="btn btn-blue input-group-text" id="basic-addon2">{{ __('Update') }}</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>

                        <div class="customers__table">
                            <table id="customers-table" class="row-border data-table-filter table-style">
                                <thead>
                                    <tr>
                                        <th>{{ __('SL') }}</th>
                                        <th>{{ __('Name') }}</th>
                                        <th>{{ __('Account Name') }}</th>
                                        <th>{{ __('Account Number') }}</th>
                                        <th>{{ __('Status') }}</th>
                                        <th>{{ __('Action') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($banks as $bank)
                                        <tr class="removable-item">
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $bank->name }}</td>
                                            <td>{{ $bank->account_name }}</td>
                                            <td>{{ $bank->account_number }}</td>
                                            <td>
                                                <a href="{{ route('settings.bank.status', $bank->id) }}"> <span
                                                        class="status {{ $bank->status == 1 ? 'active' : 'blocked' }}">{{ $bank->status == 1 ? 'Active' : 'Inactive' }}</span></a>
                                            </td>
                                            <td>
                                                <div class="action__buttons">
                                                    <a href="{{ route('settings.bank.edit', [$bank->id]) }}"
                                                        class=" btn-action mr-1 edit" data-toggle="tooltip" title="Edit">
                                                        <img src="{{ asset('admin/images/icons/edit-2.svg') }}"
                                                            alt="edit">
                                                    </a>
                                                    <button class="btn-action ms-2 deleteItem"
                                                        data-formid="delete_row_form_{{ $bank->id }}">
                                                        <img src="{{ asset('admin/images/icons/trash-2.svg') }}"
                                                            alt="trash">
                                                    </button>

                                                    <form action="{{ route('settings.bank.delete', $bank->id) }}"
                                                        method="post" id="delete_row_form_{{ $bank->id }}">
                                                        {{ method_field('DELETE') }}
                                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="mt-3">
                                {{$banks->links()}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Page content area end -->

    <!-- Add Modal section start -->
    <div class="modal fade" id="add-todo-modal" aria-hidden="true" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content ">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('Add Bank') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('settings.bank.store') }}" method="post" autocomplete="off">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-6">
                                <div class="input__group mb-25">
                                    <label for="name">{{ __('Name') }}</label>
                                    <input type="text" name="name" id="name" placeholder="{{ __('Type Bank Name') }}"
                                        value="{{ old('name') }}" required>
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
                                        placeholder="{{ __('Type Bank Account Name') }}" value="{{ old('account_name') }}"
                                        required>
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
                                        placeholder="{{ __('Type Bank Account Number') }}" value="{{ old('account_number') }}"
                                        required>
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
                                        <option value="1">{{ __('Active') }}</option>
                                        <option value="0">{{ __('Inactive') }}</option>
                                    </select>

                                    @if ($errors->has('status'))
                                        <span class="text-danger"><i class="fas fa-exclamation-triangle"></i>
                                            {{ $errors->first('status') }}</span>
                                    @endif
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-purple">{{ __('Update') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Add Modal section end -->
@endsection

@push('style')
    <link rel="stylesheet" href="{{ asset('admin/css/jquery.dataTables.min.css') }}">
@endpush

@push('script')
    <script src="{{ asset('admin/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('admin/js/custom/data-table-page.js') }}"></script>
    <script src="{{ asset('admin/js/custom/payment-method.js') }}"></script>
@endpush
