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
                            <p>You need to follow some instruction after maintenance mode changes. Instruction list given below-</p>
                            <div class="text-black">
                                <li>If you select maintenance mode <b>Maintenance On</b>,
                                    you need to input secret key for maintenance work. Otherwise you can't work this website. And your created secret key helps you to work under
                                    maintenance.
                                </li>
                                <li>After created maintenance key, you can use this website secretly through this url <span class="iconify"
                                                                                                                            data-icon="arcticons:url-forwarder"></span> <span
                                        class="text-primary">{{ url('') }}/(Your created secret key)</span></li>
                                <li>Only one time url is browsing with secret key and you can browse your site in maintenance mode. When maintenance mode on, any user can see
                                    maintenance mode error message.
                                </li>
                                <li>Unfortunately you forget your secret key and try to connect with your website. <br> Then you go to your project folder location
                                    <b>Main Files</b>(where your file in cpanel or your hosting)<span class="iconify" data-icon="arcticons:url-forwarder"></span><b>storage</b>
                                    <span class="iconify" data-icon="arcticons:url-forwarder"></span><b>framework</b>. You can see 2 files and need to delete 2 files. Files are:
                                    <br>
                                    1. down <br>
                                    2. maintenance.php
                                </li>
                            </div>
                        </div>
                        <br>
                        <form action="{{route('settings.maintenance.change')}}" method="post" class="form-horizontal">
                            @csrf

                            <div class="form-group text-black row mb-3">
                                <label class="col-lg-3">{{ __('Maintenance Mode') }} <span class="text-danger">*</span></label>
                                <div class="col-lg-9">
                                    <div class="col-lg-9">
                                        <select name="maintenance_mode" id="" class="form-control maintenance_mode">
                                            <option value="">--{{ __('Select Option') }}--</option>
                                            <option value="1" @if(get_option('maintenance_mode') == 1) selected @endif>{{ __('Maintenance On') }}</option>
                                            <option value="2" @if(get_option('maintenance_mode') != 1) selected @endif>{{ __('Live') }}</option>
                                        </select>
                                        @if ($errors->has('maintenance_mode'))
                                            <span class="text-danger"><i class="fas fa-exclamation-triangle"></i> {{ $errors->first('maintenance_mode') }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="form-group text-black row mb-3">
                                <label class="col-lg-3">{{ __('Maintenance Mode Secret Key') }}</label>
                                <div class="col-lg-9">
                                    <input type="text" name="maintenance_secret_key" value="{{ get_option('maintenance_secret_key') }}" minlength="6"
                                           class="form-control maintenance_secret_key">
                                    @if ($errors->has('maintenance_secret_key'))
                                        <span class="text-danger"><i class="fas fa-exclamation-triangle"></i> {{ $errors->first('maintenance_secret_key') }}</span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group text-black row mb-3">
                                <label class="col-lg-3">{{ __('Maintenance Mode Url') }} </label>
                                <div class="col-lg-9">
                                    <input type="text" name="" value="" class="form-control maintenance_mode_url" disabled>
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
