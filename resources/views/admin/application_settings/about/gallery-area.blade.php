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
                        <form action="{{route('settings.about.gallery-area.update')}}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="custom-form-group mb-3 row">
                                <label for="gallery_area_title" class="col-lg-3 text-lg-right text-black"> {{ __('Gallery Area Title') }} </label>
                                <div class="col-lg-9">
                                    <input type="text" name="gallery_area_title" id="gallery_area_title" value="{{ @$aboutUsGeneral->gallery_area_title }}"
                                           class="form-control" placeholder="{{ __('Type gallery area title') }}">
                                    @if ($errors->has('gallery_area_title'))
                                        <span class="text-danger"><i class="fas fa-exclamation-triangle"></i> {{ $errors->first('gallery_area_title') }}</span>
                                    @endif
                                </div>
                            </div>

                            <div class="custom-form-group mb-3 row">
                                <label for="gallery_area_subtitle" class="col-lg-3 text-lg-right text-black"> {{ __('Gallery Area Subtitle') }} </label>
                                <div class="col-lg-9">
                                    <textarea name="gallery_area_subtitle" class="form-control" rows="5" id="gallery_area_subtitle">{{ @$aboutUsGeneral->gallery_area_subtitle }}</textarea>

                                    @if ($errors->has('gallery_area_subtitle'))
                                        <span class="text-danger"><i class="fas fa-exclamation-triangle"></i> {{ $errors->first('gallery_area_subtitle') }}</span>
                                    @endif
                                </div>
                            </div>

                            <div class="custom-form-group mb-3 row">
                                <label class="col-lg-3">{{ __('Gallery First Image') }}</label>
                                <div class="col-lg-5">
                                    <div class="upload-img-box">
                                        @if( @$aboutUsGeneral->gallery_first_image)
                                            <img src="{{getImageFile(@$aboutUsGeneral->gallery_first_image)}}">
                                        @else
                                            <img src="" alt="img">
                                        @endif
                                        <input type="file" name="gallery_first_image" id="gallery_first_image" accept="image/*" onchange="previewFile(this)">
                                        <div class="upload-img-box-icon">
                                            <i class="fa fa-camera"></i>
                                            <p class="m-0">{{ __('Image') }}</p>
                                        </div>
                                    </div>
                                    @if ($errors->has('gallery_first_image'))
                                        <span class="text-danger"><i class="fas fa-exclamation-triangle"></i> {{ $errors->first('gallery_first_image') }}</span>
                                    @endif
                                    <p><span class="text-black">{{ __('Accepted Files') }}:</span> JPG <br> <span class="text-black">{{ __('Accepted Size') }}:</span> 536 x 644 (1MB)</p>
                                </div>
                            </div>

                            <div class="custom-form-group mb-3 row">
                                <label class="col-lg-3">{{ __('Gallery Second Image') }}</label>
                                <div class="col-lg-5">
                                    <div class="upload-img-box">
                                        @if( @$aboutUsGeneral->gallery_second_image)
                                            <img src="{{getImageFile(@$aboutUsGeneral->gallery_second_image)}}">
                                        @else
                                            <img src="" alt="img">
                                        @endif
                                        <input type="file" name="gallery_second_image" id="gallery_second_image" accept="image/*" onchange="previewFile(this)">
                                        <div class="upload-img-box-icon">
                                            <i class="fa fa-camera"></i>
                                            <p class="m-0">{{ __('Image') }}</p>
                                        </div>
                                    </div>
                                    @if ($errors->has('gallery_second_image'))
                                        <span class="text-danger"><i class="fas fa-exclamation-triangle"></i> {{ $errors->first('gallery_second_image') }}</span>
                                    @endif
                                    <p><span class="text-black">{{ __('Accepted Files') }}:</span> JPG <br> <span class="text-black">{{ __('Accepted Size') }}:</span> 536 x 309 (1MB)</p>
                                </div>
                            </div>

                            <div class="custom-form-group mb-3 row">
                                <label class="col-lg-3">{{ __('Gallery Third Image') }}</label>
                                <div class="col-lg-5">
                                    <div class="upload-img-box">
                                        @if( @$aboutUsGeneral->gallery_third_image)
                                            <img src="{{getImageFile(@$aboutUsGeneral->gallery_third_image)}}">
                                        @else
                                            <img src="" alt="img">
                                        @endif
                                        <input type="file" name="gallery_third_image" id="gallery_third_image" accept="image/*" onchange="previewFile(this)">
                                        <div class="upload-img-box-icon">
                                            <i class="fa fa-camera"></i>
                                            <p class="m-0">{{ __('Image') }}</p>
                                        </div>
                                    </div>
                                    @if ($errors->has('gallery_third_image'))
                                        <span class="text-danger"><i class="fas fa-exclamation-triangle"></i> {{ $errors->first('gallery_third_image') }}</span>
                                    @endif
                                    <p><span class="text-black">{{ __('Accepted Files') }}:</span> JPG <br> <span class="text-black">{{ __('Accepted Size') }}:</span> 536 x 309 (1MB)</p>
                                </div>
                            </div>


                            <div class="row justify-content-end">
                                <div class="col-md-2 text-right ">
                                    @updateButton
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
