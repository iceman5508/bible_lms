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
                    <div class="email-inbox__area bg-style">
                        <div class="item-top mb-30"><h2>{{ __(@$title) }}</h2></div>
                        <form action="{{route('settings.general_setting.cms.update')}}" method="post" class="form-horizontal">
                            @csrf

                            <div class="row">
                                <div class="col-lg-3">
                                    <label>{{ __('Design') }} <span class="text-danger">*</span></label>
                                </div>
                                <div class="col-lg-9 mb-15">
                                    <input type="radio" id="default" name="app_color_design_type" value="1"
                                           {{ (empty(get_option('app_color_design_type')) || get_option('app_color_design_type')) ? 'checked' : '' }} required>
                                    <label for="default">{{ __('Default') }}</label><br>
                                    <input type="radio" id="custom" name="app_color_design_type" value="2" {{ get_option('app_color_design_type') == 2 ? 'checked' : '' }}>
                                    <label for="custom">{{ __('Custom') }}</label><br>
                                </div>
                            </div>
                            <div class="customDiv">
                                <div class="row">
                                    <div class="col-lg-3"><label>{{ __('Theme Color') }} <span class="text-danger">*</span></label>
                                    </div>
                                    <div class="col-lg-9 mb-15">
                                         <span class="color-picker">
                                            <label for="colorPicker1" class="mb-0">
                                                <input type="color" name="app_theme_color"
                                                       value="{{ empty(get_option('app_theme_color')) ? '#5e3fd7' : get_option('app_theme_color') }}" id="colorPicker1">
                                            </label>
                                        </span>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-3">
                                        <label>{{ __('Navbar Background Color') }} <span class="text-danger">*</span></label>
                                    </div>
                                    <div class="col-lg-9 mb-15">
                                         <span class="color-picker">
                                            <label for="colorPicker2" class="mb-0">
                                                <input type="color" name="app_navbar_background_color"
                                                       value="{{ empty(get_option('app_navbar_background_color')) ? '#030060' : get_option('app_navbar_background_color') }}"
                                                       id="colorPicker2">
                                            </label>
                                        </span>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-3">
                                        <label>{{ __('Body Font Color') }} <span class="text-danger">*</span></label>
                                    </div>
                                    <div class="col-lg-9 mb-15">
                                         <span class="color-picker">
                                            <label for="colorPicker3" class="mb-0">
                                                <input type="color" name="app_body_font_color"
                                                       value="{{ empty(get_option('app_body_font_color')) ? '#52526C' : get_option('app_body_font_color') }}"
                                                       id="colorPicker3">
                                            </label>
                                        </span>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-3">
                                        <label>{{ __('Heading Color') }} <span class="text-danger">*</span></label>
                                    </div>
                                    <div class="col-lg-9 mb-15">
                                         <span class="color-picker">
                                            <label for="colorPicker4" class="mb-0">
                                                <input type="color" name="app_heading_color"
                                                       value="{{ empty(get_option('app_heading_color')) ? '#040453' : get_option('app_heading_color') }}" id="colorPicker4">
                                            </label>
                                        </span>
                                    </div>
                                </div>

                                <div class="row {{ get_option('theme', THEME_DEFAULT) == THEME_DEFAULT ? '' : 'd-none' }}">
                                    <div class="col-lg-3">
                                        <label>{{ __('Gradiant Banner Color') }} <span class="text-danger">*</span></label>
                                    </div>
                                    <div class="col-lg-9 mb-15">
                                        <span class="color-picker d-flex flex-wrap">
                                            <label for="colorPicker8" class="mb-0 me-3">
                                                <input class="color1" type="color" name="app_gradiant_banner_color1" value="{{  get_option('app_gradiant_banner_color1') }}"
                                                       id="colorPicker8">
                                            </label>
                                            <label for="colorPicker9" class="mb-0 me-3">
                                                <input class="color2" type="color" name="app_gradiant_banner_color2" value="{{  get_option('app_gradiant_banner_color2') }}"
                                                       id="colorPicker9">
                                            </label>
                                        </span>

                                        <div id="gradient" class="p-5">
                                            <input class="app_gradiant_banner_color" type="hidden" name="app_gradiant_banner_color"
                                                   value="{{  get_option('app_gradiant_banner_color') }}">
                                            <h2 class="text-white">{{ __('Current CSS Background') }}</h2>
                                            <h3 id="textContent" class="text-white"></h3>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-3">
                                        @if(get_option('theme', THEME_DEFAULT) == THEME_DEFAULT)
                                        <label>{{ __('Gradiant Footer Color') }} <span class="text-danger">*</span></label>
                                        @else
                                        <label>{{ __('Gradiant section Color') }} <span class="text-danger">*</span></label>
                                        @endif
                                    </div>
                                    <div class="col-lg-9 mb-15">
                                        <span class="color-picker d-flex flex-wrap">
                                            <label for="colorPicker12" class="mb-0 me-3">
                                                <input class="color5" type="color" name="app_gradiant_footer_color1" value="{{  get_option('app_gradiant_footer_color1') }}"
                                                       id="colorPicker12">
                                            </label>
                                            <label for="colorPicker13" class="mb-0 me-3">
                                                <input class="color6" type="color" name="app_gradiant_footer_color2" value="{{  get_option('app_gradiant_footer_color2') }}"
                                                       id="colorPicker13">
                                            </label>
                                        </span>

                                        <div id="gradiant3" class="p-5">
                                            <input class="app_gradiant_footer_color" type="hidden" name="app_gradiant_footer_color"
                                                   value="{{  get_option('app_gradiant_footer_color') }}">
                                            <h2 class="text-white">{{ __('Current CSS Background') }}</h2>
                                            <h3 id="textContent3" class="text-white"></h3>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-3">
                                        @if(get_option('theme', THEME_DEFAULT) == THEME_DEFAULT)
                                        <label>{{ __('Gradiant Overlay Background Color Opacity') }} <span class="text-danger">*</span></label>
                                        @else
                                        <label>{{ __('Gradiant Section Background Color Opacity') }} <span class="text-danger">*</span></label>
                                        @endif
                                    </div>
                                    <div class="col-lg-9 mb-15">
                                        <select name="app_gradiant_overlay_background_color_opacity" class="form-control">
                                            <option value="0" {{ get_option('app_gradiant_overlay_background_color_opacity') == 0 ? 'selected' : null }}>0</option>
                                            <option value="0.1" {{ get_option('app_gradiant_overlay_background_color_opacity') == 0.1 ? 'selected' : null }}>0.1</option>
                                            <option value="0.2" {{ get_option('app_gradiant_overlay_background_color_opacity') == 0.2 ? 'selected' : null }}>0.2</option>
                                            <option value="0.3" {{ get_option('app_gradiant_overlay_background_color_opacity') == 0.3 ? 'selected' : null }}>0.3</option>
                                            <option value="0.4" {{ get_option('app_gradiant_overlay_background_color_opacity') == 0.4 ? 'selected' : null }}>0.4</option>
                                            <option value="0.5" {{ get_option('app_gradiant_overlay_background_color_opacity') == 0.5 ? 'selected' : null }}>0.5</option>
                                            <option value="0.6" {{ get_option('app_gradiant_overlay_background_color_opacity') == 0.6 ? 'selected' : null }}>0.6</option>
                                            <option value="0.7" {{ get_option('app_gradiant_overlay_background_color_opacity') == 0.7 ? 'selected' : null }}>0.7</option>
                                            <option value="0.8" {{ get_option('app_gradiant_overlay_background_color_opacity') == 0.8 ? 'selected' : null }}>0.8</option>
                                            <option value="0.9" {{ get_option('app_gradiant_overlay_background_color_opacity') == 0.9 ? 'selected' : null }}>0.9</option>
                                            <option value="1" {{ get_option('app_gradiant_overlay_background_color_opacity') == 1 ? 'selected' : null }}>1</option>
                                        </select>
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
            var css = document.getElementById("textContent");
            var color1 = document.querySelector(".color1");
            var color2 = document.querySelector(".color2");
            var div = document.getElementById("gradient");

            var css3= document.getElementById("textContent3");
            var color5 = document.querySelector(".color5");
            var color6 = document.querySelector(".color6");
            var div3 = document.getElementById("gradiant3");

            setGradient()
            setGradient3()

            function setGradient() {
                var textContent = "linear-gradient(to right, " + color1.value + ", " + color2.value + ")"
                div.style.background = textContent;
                css.textContent = div.style.background + ";"
                $('.app_gradiant_banner_color').val(textContent);
            }

            function setGradient3() {
                var textContent3 = "linear-gradient(180deg, " + color5.value + ", " + color6.value + ")"
                div3.style.background = textContent3;
                css3.textContent3 = div3.style.background + ";"
                $('.app_gradiant_footer_color').val(textContent3);
            }

            color1.addEventListener("input", setGradient);
            color2.addEventListener("input", setGradient);

            color5.addEventListener("input", setGradient3);
            color6.addEventListener("input", setGradient3);
        })

        $(function () {
            var app_color_design_type = "{{ empty(get_option('app_color_design_type')) ? 1 : get_option('app_color_design_type') }}";
            appDesignType(app_color_design_type)
        })
        $("input[name='app_color_design_type']").click(function () {
            var app_design_type = $("input[name='app_color_design_type']:checked").val();
            appDesignType(app_design_type)
        });

        function appDesignType(app_color_design_type) {
            if (app_color_design_type == 1) {
                $('.customDiv').addClass('d-none')
            } else {
                $('.customDiv').removeClass('d-none')
            }
        }

    </script>
@endpush
