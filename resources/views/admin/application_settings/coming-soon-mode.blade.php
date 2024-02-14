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
                            <p>You need to follow some instruction after coming soon mode changes. Instruction list given below-</p>
                            <div class="text-black">
                                <li>If you select coming soon mode <b>Coming Soon On</b>,
                                    you need to input secret key for coming soon work. Otherwise you can't work this website. And your created secret key helps you to work under
                                    coming soon mode.
                                </li>
                                <li>After created coming soon key, you can use this website secretly through this url <span class="iconify" data-icon="arcticons:url-forwarder"></span> 
                                    <span class="text-primary">{{ url('') }}/(Your created secret key)</span></li>
                                <li>Only one time url is browsing with secret key and you can browse your site in coming soon mode. When coming soon mode on, any user can see coming soon mode page.
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
                        <form action="{{route('settings.coming-soon.change')}}" method="post" class="form-horizontal">
                            @csrf

                            <div class="form-group text-black row mb-3">
                                <label class="col-lg-3">{{ __('Coming Soon Mode') }} <span class="text-danger">*</span></label>
                                <div class="col-lg-9">
                                    <div class="col-lg-9">
                                        <select name="coming_soon_mode" id="" class="form-control coming_soon_mode">
                                            <option value="">--{{ __('Select Option') }}--</option>
                                            <option value="1" @if(get_option('coming_soon_mode') == 1) selected @endif>{{ __('Coming Soon On') }}</option>
                                            <option value="2" @if(get_option('coming_soon_mode') != 1) selected @endif>{{ __('Live') }}</option>
                                        </select>
                                        @if ($errors->has('coming_soon_mode'))
                                            <span class="text-danger"><i class="fas fa-exclamation-triangle"></i> {{ $errors->first('coming_soon_mode') }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="form-group text-black row mb-3">
                                <label class="col-lg-3">{{ __('Coming Soon Mode Secret Key') }}</label>
                                <div class="col-lg-9">
                                    <input type="text" name="coming_soon_secret_key" value="{{ get_option('coming_soon_secret_key') }}" minlength="6" class="form-control coming_soon_secret_key">
                                    @if ($errors->has('coming_soon_secret_key'))
                                        <span class="text-danger"><i class="fas fa-exclamation-triangle"></i> {{ $errors->first('coming_soon_secret_key') }}</span>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="form-group text-black row mb-3">
                                <label class="col-lg-3">{{ __('Launch Time') }}</label>
                                <div class="col-lg-9">
                                    <input type="datetime-local" name="coming_live_at" value="{{ get_option('coming_live_at') }}" class="form-control coming_live_at">
                                    @if ($errors->has('coming_live_at'))
                                        <span class="text-danger"><i class="fas fa-exclamation-triangle"></i> {{ $errors->first('coming_live_at') }}</span>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="form-group text-black row mb-3">
                                <label class="col-lg-3">{{ __('Coming Soon Mode Page Title') }}</label>
                                <div class="col-lg-9">
                                    <input type="text" name="coming_soon_title" value="{{ get_option('coming_soon_title') }}" minlength="6" class="form-control coming_soon_title">
                                    @if ($errors->has('coming_soon_title'))
                                        <span class="text-danger"><i class="fas fa-exclamation-triangle"></i> {{ $errors->first('coming_soon_title') }}</span>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="form-group text-black row mb-3">
                                <label class="col-lg-3">{{ __('Coming Soon Mode Page Description') }}</label>
                                <div class="col-lg-9">
                                    <textarea name="coming_soon_description" value="" class="form-control coming_soon_description">{!! nl2br(get_option('coming_soon_description')) !!}</textarea>
                                    @if ($errors->has('coming_soon_description'))
                                        <span class="text-danger"><i class="fas fa-exclamation-triangle"></i> {{ $errors->first('coming_soon_description') }}</span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group text-black row mb-3">
                                <label class="col-lg-3">{{ __('Coming Soon Mode Url') }} </label>
                                <div class="col-lg-9">
                                    <input type="text" name="" value="" class="form-control coming_url" disabled>
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
        $('.coming_soon_secret_key').on('keyup', function () {
            var secret_value = $('.coming_soon_secret_key').val()
            var url = "{{ url('') }}" + "/"
            $('.coming_url').val(url + secret_value);
        })

        $(function () {
            var url = "{{ url('') }}" + "/" + "{{ get_option('coming_soon_secret_key') }}"
            $('.coming_url').val(url);

            var coming = "{{ get_option('coming') }}"
            maintenanceMode(coming)
        })

        $('.coming').change(function () {
            var coming = this.value
            maintenanceMode(coming)
        });

        function maintenanceMode(coming) {
            if (coming == 1) {
                $(".coming_soon_secret_key").attr("required", "true");
            } else if (coming == 2) {
                $(".coming_soon_secret_key").removeAttr('required');
                $('.coming_soon_secret_key').val('');
                $('.coming_url').val("{{ url('') }}");
            }
        }

    </script>
@endpush
