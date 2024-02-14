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
                    <div class="row">
                        <div class="col-md-12">
                            <div class="email-inbox__area bg-style">
                                <div class="item-top mb-30"><h2>{{ __("System Font") }}</h2></div>
                                <form action="{{route('settings.general_setting.cms.update')}}" method="post" class="form-horizontal">
                                    @csrf
        
                                    <div class="row">
                                        <div class="col-lg-3">
                                            <label>{{ __('Design') }} <span class="text-danger">*</span></label>
                                        </div>
                                        <div class="col-lg-9 mb-15">
                                            <input type="radio" id="default" name="app_font_design_type" value="1"
                                                   {{ (empty(get_option('app_font_design_type')) || get_option('app_font_design_type')) ? 'checked' : '' }} required>
                                            <label for="default">{{ __('Default') }}</label><br>
                                            <input type="radio" id="custom" name="app_font_design_type" value="2" {{ get_option('app_font_design_type') == 2 ? 'checked' : '' }}>
                                            <label for="custom">{{ __('Custom') }}</label><br>
                                        </div>
                                    </div>
                                    <div class="customDiv">
                                        <div class="bg-dark-primary-soft-varient p-3">
                                            <p><b>Note: </b>Please choose/Use the same type of font for the best performance. Because every font-weight is not the same, in that case, if you use other fonts (That font-weight is different) sometimes it causes some minor problems. </p>
                                            <p>When you add font. Please add every possible font-weight is available of this specific font. As if, your app will be looking cool.</p>
                                        </div>
                                        <br>
                                        <div class="row">
                                            <div class="col-lg-3">
                                                <label>{{ __('Font Family') }} </label>
                                            </div>
                                            <div class="col-lg-9 mb-15">
                                                <input type="text" name="app_font_family" class="color-picker w-100" placeholder="'Jost', sans-serif"
                                                       value="{{ get_option('app_font_family') }}">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-3">
                                                <label>{{ __('Font Link') }} </label>
                                            </div>
                                            <div class="col-lg-9 mb-15">
                                                <input type="text" name="app_font_link" class="color-picker w-100"
                                                       value="{{ get_option('app_font_link') }}">
                                            </div>
                                        </div>
                                    </div>
        
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="input__group general-settings-btn">
                                                <button type="submit" class="btn btn-blue float-right">{{__('Update')}}</button>
                                            </div>
                                        </div>
                                    </div>
        
                                </form>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="email-inbox__area bg-style">
                                <div class="item-top mb-30"><h2>{{ __("Certificate Font") }}</h2></div>
                                <form action="{{route('settings.general_setting.cms.update')}}" enctype="multipart/form-data" method="post" class="form-horizontal">
                                    @csrf
                                    <div class="row">
                                        <div class="col-lg-3">
                                            <label>{{ __('Certificate font') }} <span class="text-danger">*</span> </label>
                                        </div>
                                        <div class="col-lg-9 mb-15">
                                            <input type="file" class="form-control" required name="certificate_font" value="{{ get_option('certificate_font') }}">
                                            @if(!empty(get_option('certificate_font')))
                                            <span><a target="_blank" href="{{ getVideoFile(get_option('certificate_font')) }}">View</a></span>
                                            @endif
                                        </div>
                                    </div>
        
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="input__group general-settings-btn">
                                                <button type="submit" class="btn btn-blue float-right">{{__('Update')}}</button>
                                            </div>
                                        </div>
                                    </div>
        
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Page content area end -->
@endsection

@push('style')
    <link rel="stylesheet" href="{{asset('frontend/assets/css/for-certificate.css')}}">
@endpush

@push('script')

    <script src="{{asset('frontend/assets/js/color.js')}}"></script>

    <script>
        "use strict"

        $(function () {
            var app_font_design_type = "{{ empty(get_option('app_font_design_type')) ? 1 : get_option('app_font_design_type') }}";
            appDesignType(app_font_design_type)
        })
        $("input[name='app_font_design_type']").click(function () {
            var app_font_design_type = $("input[name='app_font_design_type']:checked").val();
            appDesignType(app_font_design_type)
        });

        function appDesignType(app_font_design_type) {
            if (app_font_design_type == 1) {
                $('.customDiv').addClass('d-none')
            } else {
                $('.customDiv').removeClass('d-none')
            }
        }

    </script>
@endpush
