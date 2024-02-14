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
                                <h2>{{ __('Home Settings') }}</h2>
                            </div>
                        </div>
                        <div class="breadcrumb__content__right">
                            <nav aria-label="breadcrumb">
                                <ul class="breadcrumb">
                                   <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">{{__('Dashboard')}}</a></li>
                                    <li class="breadcrumb-item"><a>{{__('Application Setting')}}</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">{{ __(@$title) }}</li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-3 col-md-4">
                    @include('admin.application_settings.home-sidebar')
                </div>
                <div class="col-lg-9 col-md-8">
                    <div class="email-inbox__area bg-style">
                        <div class="item-top mb-30"><h2>{{ __(@$title) }}</h2></div>
                        <form action="{{route('settings.banner-section.update')}}" method="post" class="form-horizontal" enctype="multipart/form-data">
                            @csrf
                            <div class="input__group mb-25 row">
                                <label for="banner_mini_words_title" class="col-lg-3"> {{ __('Mini Title') }}</label>
                                <div class="col-lg-9">
                                    <select class="form-control multiple-select-input" name="banner_mini_words_title[]" id="banner_mini_words_title" multiple="multiple">
                                        @foreach($home->banner_mini_words_title ?? [] as $item)
                                            <option value="{{ @$item }}" selected>{{ @$item }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="input__group mb-25 row">
                                <label for="banner_first_line_title" class="col-lg-3"> {{ __('First Line Title') }} <span class="text-danger">*</span></label>
                                <div class="col-lg-9">
                                    <input type="text" name="banner_first_line_title" id="banner_first_line_title"
                                           value="{{ @$home->banner_first_line_title }}" class="form-control" required>
                                </div>
                            </div>
                            <div class="input__group mb-25 row">
                                <label for="banner_second_line_title" class="col-lg-3"> {{ __('Second Line Title') }} <span class="text-danger">*</span></label>
                                <div class="col-lg-9">
                                    <input type="text" name="banner_second_line_title" id="banner_second_line_title"
                                           value="{{ @$home->banner_second_line_title }}" class="form-control" required>
                                </div>
                            </div>
                            @if(get_option('theme', THEME_DEFAULT) == THEME_DEFAULT)
                            <div class="input__group mb-25 row">
                                <label for="banner_second_line_changeable_words" class="col-lg-3"> {{ __('Second Line Changeable Word Title') }}</label>
                                <div class="col-lg-9">
                                    <select class="form-control multiple-select-input" name="banner_second_line_changeable_words[]" id="banner_second_line_changeable_words" multiple="multiple">
                                        @foreach($home->banner_second_line_changeable_words ?? [] as $item)
                                            <option value="{{ @$item }}" selected>{{ @$item }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            @endif
                            <div class="input__group mb-25 row">
                                <label for="banner_third_line_title" class="col-lg-3"> {{ __('Third Line Title') }} <span class="text-danger">*</span></label>
                                <div class="col-lg-9">
                                    <input type="text" name="banner_third_line_title" id="banner_third_line_title" value="{{ @$home->banner_third_line_title }}" class="form-control" required>
                                </div>
                            </div>
                            <div class="input__group mb-25 row">
                                <label for="banner_subtitle" class="col-lg-3"> {{ __('Subtitle') }} <span class="text-danger">*</span></label>
                                <div class="col-lg-9">
                                    <input type="text" name="banner_subtitle" id="banner_subtitle" value="{{ @$home->banner_subtitle }}" class="form-control" required>
                                </div>
                            </div>
                            <div class="input__group mb-25 row">
                                <label for="banner_first_button_name" class="col-lg-3">{{ __('First Button Name') }} </label>
                                <div class="col-lg-9">
                                    <input type="text" name="banner_first_button_name" id="banner_first_button_name" value="{{ @$home->banner_first_button_name }}"
                                           class="form-control" >
                                </div>
                            </div>
                            <div class="input__group mb-25 row">
                                <label for="banner_first_button_link" class="col-lg-3">{{ __('First Button Link') }} </label>
                                <div class="col-lg-9">
                                    <input type="text" name="banner_first_button_link" id="banner_first_button_link" value="{{ @$home->banner_first_button_link }}"
                                           class="form-control" >
                                </div>
                            </div>
                            <div class="input__group mb-25 row">
                                <label for="banner_second_button_name" class="col-lg-3">{{ __('Second Button Name') }}</label>
                                <div class="col-lg-9">
                                    <input type="text" name="banner_second_button_name" id="banner_second_button_name" value="{{ @$home->banner_second_button_name }}"
                                           class="form-control" >
                                </div>
                            </div>
                            <div class="input__group mb-25 row">
                                <label for="banner_second_button_link" class="col-lg-3">{{ __('Second Button Link') }} </label>
                                <div class="col-lg-9">
                                    <input type="text" name="banner_second_button_link" id="banner_second_button_link" value="{{ @$home->banner_second_button_link }}"
                                           class="form-control" >
                                </div>
                            </div>

                            <div class="input__group mb-25 row">
                                <label class="col-lg-3">{{ __('Banner Image') }}</label>
                                <div class="col-lg-5">
                                    <div class="upload-img-box">
                                        @if( @$home->banner_image)
                                            <img src="{{getImageFile(@$home->banner_image_path)}}">
                                        @else
                                            <img src="" alt="img">
                                        @endif
                                        <input type="file" name="banner_image" id="banner_image" accept="image/*" onchange="previewFile(this)">
                                        <div class="upload-img-box-icon">
                                            <i class="fa fa-camera"></i>
                                            <p class="m-0">{{ __('Banner Image') }}</p>
                                        </div>
                                    </div>
                                    @if ($errors->has('banner_image'))
                                        <span class="text-danger"><i class="fas fa-exclamation-triangle"></i> {{ $errors->first('banner_image') }}</span>
                                    @endif
                                    <p><span class="text-black">{{ __('Accepted Files') }}:</span> PNG,SVG <br> <span class="text-black">{{ __('Recommend Size') }}:</span> 800 x 540 (1MB)</p>
                                </div>
                            </div>

                            @if(get_option('theme', THEME_DEFAULT) == THEME_THREE)
                            <div class="input__group mb-25 row">
                                <label for="banner_left_card_title" class="col-lg-3">{{ __('Left Card Title') }}</label>
                                <div class="col-lg-9">
                                    <input type="text" name="banner_left_card_title" id="banner_left_card_title" value="{{ get_option('banner_left_card_title') }}"
                                           class="form-control" >
                                </div>
                            </div>
                            <div class="input__group mb-25 row">
                                <label for="banner_left_card_description" class="col-lg-3">{{ __('Left Card Description') }} </label>
                                <div class="col-lg-9">
                                    <input type="text" name="banner_left_card_description" id="banner_left_card_description" value="{{ get_option('banner_left_card_description') }}"
                                           class="form-control" >
                                </div>
                            </div>

                            <div class="input__group mb-25 row">
                                <label class="col-lg-3">{{ __('Left Card Icon') }}</label>
                                <div class="col-lg-5">
                                    <div class="upload-img-box">
                                        @if(get_option('banner_left_card_icon') != '')
                                            <img src="{{getImageFile(get_option('banner_left_card_icon'))}}">
                                        @else
                                            <img src="">
                                        @endif
                                        <input type="file" name="banner_left_card_icon" id="banner_left_card_icon" accept="image/*" onchange="previewFile(this)">
                                        <div class="upload-img-box-icon">
                                            <i class="fa fa-camera"></i>
                                            <p class="m-0">{{__('Right Card Icon')}}</p>
                                        </div>
                                    </div>
                                    @if ($errors->has('banner_left_card_icon'))
                                        <span class="text-danger"><i class="fas fa-exclamation-triangle"></i> {{ $errors->first('banner_left_card_icon') }}</span>
                                    @endif
                                    <p><span class="text-black">{{ __('Accepted Files') }}:</span> PNG,SVG <br> <span class="text-black">{{ __('Recommend Size') }}:</span> 50 x 50 (100KB)</p>
                                </div>
                            </div>
                            <div class="input__group mb-25 row">
                                <label for="banner_right_card_title" class="col-lg-3">{{ __('Right Card Title') }}</label>
                                <div class="col-lg-9">
                                    <input type="text" name="banner_right_card_title" id="banner_right_card_title" value="{{ get_option('banner_right_card_title') }}"
                                           class="form-control" >
                                </div>
                            </div>
                            <div class="input__group mb-25 row">
                                <label for="banner_right_card_description" class="col-lg-3">{{ __('Right Card Description') }} </label>
                                <div class="col-lg-9">
                                    <input type="text" name="banner_right_card_description" id="banner_right_card_description" value="{{ get_option('banner_right_card_description') }}"
                                           class="form-control" >
                                </div>
                            </div>

                            <div class="input__group mb-25 row">
                                <label class="col-lg-3">{{ __('Right Card Icon') }}</label>
                                <div class="col-lg-5">
                                    <div class="upload-img-box">
                                        @if(get_option('banner_right_card_icon') != '')
                                            <img src="{{getImageFile(get_option('banner_right_card_icon'))}}">
                                        @else
                                            <img src="">
                                        @endif
                                        <input type="file" name="banner_right_card_icon" id="banner_right_card_icon" accept="image/*" onchange="previewFile(this)">
                                        <div class="upload-img-box-icon">
                                            <i class="fa fa-camera"></i>
                                            <p class="m-0">{{__('Right Card Icon')}}</p>
                                        </div>
                                    </div>
                                    @if ($errors->has('banner_right_card_icon'))
                                        <span class="text-danger"><i class="fas fa-exclamation-triangle"></i> {{ $errors->first('banner_right_card_icon') }}</span>
                                    @endif
                                    <p><span class="text-black">{{ __('Accepted Files') }}:</span> PNG,SVG <br> <span class="text-black">{{ __('Recommend Size') }}:</span> 50 x 50 (100KB)</p>
                                </div>
                            </div>
                            @endif

                            <div class="row">
                                <div class="col-12">
                                    <div class="input__group general-settings-btn">
                                        <button type="submit" class="btn btn-blue">{{__('Update')}}</button>
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
    <link rel="stylesheet" href="{{asset('admin/css/custom/image-preview.css')}}">
@endpush

@push('script')
    <script src="{{asset('admin/js/custom/image-preview.js')}}"></script>
@endpush
