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
                        <form action="{{route('settings.general_setting.cms.update')}}" method="post" class="form-horizontal" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group text-black row mb-3">
                                <label class="col-lg-3">{{ __('Main Image') }}</label>
                                <div class="col-lg-3">
                                    <div class="upload-img-box">
                                        @if(get_option('faq_image') != '')
                                            <img src="{{getImageFile(get_option('faq_image'))}}">
                                        @else
                                            <img src="">
                                        @endif
                                        <input type="file" name="faq_image" id="faq_image" accept="image/*" onchange="previewFile(this)">
                                        <div class="upload-img-box-icon">
                                            <i class="fa fa-camera"></i>
                                            <p class="m-0">{{ __('Image') }}</p>
                                        </div>
                                    </div>
                                    @if ($errors->has('faq_image'))
                                        <span class="text-danger"><i class="fas fa-exclamation-triangle"></i> {{ $errors->first('faq_image') }}</span>
                                    @endif
                                    <p><span class="text-black">{{ __('Accepted Files') }}:</span> {{ __('JPG,PNG, JPEG') }} <br> <span class="text-black">{{ __('Accepted Size') }}:</span> 650 x 650</p>
                                </div>
                            </div>
                            <div class="form-group text-black row mb-3">
                                <label class="col-lg-3" for="faq_title">{{ __('Title') }} <span class="text-danger">*</span></label>
                                <div class="col-lg-9">
                                    <input type="text" name="faq_title" value="{{get_option('faq_title')}}" class="form-control" required>
                                </div>
                            </div>
                            <div class="form-group text-black row mb-3">
                                <label class="col-lg-3" for="faq_subtitle">{{ __('Subtitle') }} <span class="text-danger">*</span></label>
                                <div class="col-lg-9">
                                    <textarea name="faq_subtitle" id="faq_subtitle" class="form-control" required>{{get_option('faq_subtitle')}}</textarea>
                                </div>
                            </div>
                            <div class="form-group text-black row mb-3">
                                <label class="col-lg-3" for="faq_image_title">{{ __('Image Title') }} <span class="text-danger">*</span></label>
                                <div class="col-lg-9">
                                    <input type="text" name="faq_image_title" value="{{get_option('faq_image_title')}}" class="form-control" required>
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
    <link rel="stylesheet" href="{{asset('admin/css/custom/image-preview.css')}}">
@endpush

@push('script')
    <script src="{{asset('admin/js/custom/image-preview.js')}}"></script>
@endpush
