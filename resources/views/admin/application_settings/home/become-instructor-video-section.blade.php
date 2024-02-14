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
                    <div class="email-inbox__area bg-style admin-become-instructor-video">
                        <div class="item-top mb-30"><h2>{{ __(@$title) }}</h2></div>
                        <form action="{{route('settings.general_setting.cms.update')}}" method="post" class="form-horizontal" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group text-black row mb-3">
                                <label for="become_instructor_video_logo" class="col-lg-3">{{ __('Video') }}</label>
                                <div class="col-lg-4 col-xl-4 col-xxl-3">
                                    <input type="file" name="become_instructor_video" id="become_instructor_video" accept="video/mp4">
                                    <div class="video-area-left position-relative d-flex align-items-center justify-content-center mt-2">
                                        <img src="{{asset(get_option('become_instructor_video_preview_image'))}}" alt="video" class="img-fluid">
                                        <a class="play-btn position-absolute venobox vbox-item" data-autoplay="true" data-maxwidth="800px"
                                           data-vbtype="video" href="{{ getVideoFile(get_option('become_instructor_video')) }}">
                                            <img src="{{ asset('frontend/assets/img/icons-svg/play.svg') }}" alt="play">
                                        </a>
                                    </div>

                                </div>
                            </div>
                            <div class="form-group text-black row mb-3">
                                <label for="become_instructor_video_preview_image" class="col-lg-3">{{ __('Video Preview Image') }}</label>
                                <div class="col-lg-4 col-xl-4 col-xxl-3">
                                    <div class="upload-img-box">
                                        @if(get_option('become_instructor_video_preview_image') != '')
                                            <img src="{{getImageFile(get_option('become_instructor_video_preview_image'))}}">
                                        @else
                                            <img src="" alt="img">
                                        @endif
                                        <input type="file" name="become_instructor_video_preview_image" id="become_instructor_video_preview_image" accept="image/*" onchange="previewFile(this)">
                                        <div class="upload-img-box-icon">
                                            <i class="fa fa-camera"></i>
                                            <p class="m-0">{{ __('Preview Image') }}</p>
                                        </div>
                                    </div>
                                    @if ($errors->has('become_instructor_video_preview_image'))
                                        <span class="text-danger"><i class="fas fa-exclamation-triangle"></i> {{ $errors->first('become_instructor_video_preview_image') }}</span>
                                    @endif
                                    <p><span class="text-black">{{ __('Accepted Files') }}:</span> PNG <br> <span class="text-black">{{ __('Accepted Size') }}:</span> 835 x 630</p>
                                </div>
                            </div>
                            <div class="form-group text-black row mb-3">
                                <label for="become_instructor_video_logo" class="col-lg-3">{{ __('Logo') }}</label>
                                <div class="col-lg-4 col-xl-4 col-xxl-3">
                                    <div class="upload-img-box">
                                        @if(get_option('become_instructor_video_logo') != '')
                                            <img src="{{getImageFile(get_option('become_instructor_video_logo'))}}">
                                        @else
                                            <img src="" alt="img">
                                        @endif
                                        <input type="file" name="become_instructor_video_logo" id="become_instructor_video_logo" accept="image/*" onchange="previewFile(this)">
                                        <div class="upload-img-box-icon">
                                            <i class="fa fa-camera"></i>
                                            <p class="m-0">{{ __('Logo') }}</p>
                                        </div>
                                    </div>
                                    @if ($errors->has('become_instructor_video_logo'))
                                        <span class="text-danger"><i class="fas fa-exclamation-triangle"></i> {{ $errors->first('become_instructor_video_logo') }}</span>
                                    @endif
                                    <p><span class="text-black">{{ __('Accepted Files') }}:</span> PNG <br> <span class="text-black">{{ __('Accepted Size') }}:</span> 70 x 70</p>
                                </div>
                            </div>
                            <div class="form-group text-black row mb-3">
                                <label for="become_instructor_video_title" class="col-lg-3">{{ __('Title') }} <span class="text-danger">*</span></label>
                                <div class="col-lg-9">
                                    <input type="text" name="become_instructor_video_title" id="become_instructor_video_title" value="{{get_option('become_instructor_video_title')}}" class="form-control" required>
                                </div>
                            </div>
                            <div class="form-group text-black row mb-3">
                                <label for="become_instructor_video_subtitle" class="col-lg-3">{{ __('Subtitle') }} <span class="text-danger">*</span></label>
                                <div class="col-lg-9">
                                    <textarea name="become_instructor_video_subtitle" id="" rows="5" class="form-control">{{get_option('become_instructor_video_subtitle')}}</textarea>
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
