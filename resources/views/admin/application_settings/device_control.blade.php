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
                        <div class="bg-dark-primary-soft-varient p-4 border-1">
                            <h2>{{ __('Instructions') }}: </h2>
                            <p>{{ __('If device control on it will restrict student to login more than the limited devices') }}</p>
                        </div>
                        <br>
                        <form action="{{route('settings.device_control.change')}}" method="post" class="form-horizontal">
                            @csrf

                            <div class="form-group text-black row mb-3">
                                <label class="col-lg-3">{{ __('Device Control') }} <span class="text-danger">*</span></label>
                                <div class="col-lg-9">
                                    <div class="col-lg-9">
                                        <select name="device_control" required class="form-control device_control">
                                            <option value="0" @if(get_option('device_control') != 1) selected @endif>{{ __('No') }}</option>
                                            <option value="1" @if(get_option('device_control') == 1) selected @endif>{{ __('Yes') }}</option>
                                        </select>
                                        @if ($errors->has('device_control'))
                                            <span class="text-danger"><i class="fas fa-exclamation-triangle"></i> {{ $errors->first('device_control') }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="form-group text-black row mb-3">
                                <label class="col-lg-3">{{ __('Device limit') }} </label>
                                <div class="col-lg-9">
                                    <input type="number" min=1 required name="device_limit" value="{{ get_option('device_limit') }}" class="form-control">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12">
                                    <div class="input__group general-settings-btn">
                                        <button type="submit" class="btn btn-blue float-right">{{ __('Update') }}</button>
                                    </div>
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

@push('script')
    <script>
        "use strict"
        $('.maintenance_secret_key').on('keyup', function () {
            var secret_value = $('.maintenance_secret_key').val()
            var url = "{{ url('') }}" + "/"
            $('.maintenance_mode_url').val(url + secret_value);
        })

        $(function () {
            var url = "{{ url('') }}" + "/" + "{{ get_option('maintenance_secret_key') }}"
            $('.maintenance_mode_url').val(url);

            var maintenance_mode = "{{ get_option('maintenance_mode') }}"
            maintenanceMode(maintenance_mode)
        })

        $('.maintenance_mode').change(function () {
            var maintenance_mode = this.value
            maintenanceMode(maintenance_mode)
        });

        function maintenanceMode(maintenance_mode) {
            if (maintenance_mode == 1) {
                $(".maintenance_secret_key").attr("required", "true");
            } else if (maintenance_mode == 2) {
                $(".maintenance_secret_key").removeAttr('required');
                $('.maintenance_secret_key').val('');
                $('.maintenance_mode_url').val("{{ url('') }}");
            }
        }

    </script>
@endpush
