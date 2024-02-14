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
                        <form action="{{route('settings.general_setting.cms.update')}}" method="post" class="form-horizontal" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group text-black row mb-3">
                                <label for="course_logo" class="col-lg-3">{{ __('Logo') }}</label>
                                <div class="col-lg-4">
                                    <div class="upload-img-box">
                                        @if(get_option('course_logo') != '')
                                            <img src="{{getImageFile(get_option('course_logo'))}}">
                                        @else
                                            <img src="">
                                        @endif
                                        <input type="file" name="course_logo" id="course_logo" accept="image/*" onchange="previewFile(this)">
                                        <div class="upload-img-box-icon">
                                            <i class="fa fa-camera"></i>
                                            <p class="m-0">{{ __('Logo') }}</p>
                                        </div>
                                    </div>
                                    @if ($errors->has('course_logo'))
                                        <span class="text-danger"><i class="fas fa-exclamation-triangle"></i> {{ $errors->first('course_logo') }}</span>
                                    @endif
                                    <p><span class="text-black">{{ __('Accepted Files') }}:</span> PNG <br> <span class="text-black">{{ __('Accepted Size') }}:</span> 60 x 60</p>
                                </div>
                            </div>
                            <div class="form-group text-black row mb-3">
                                <label for="course_title" class="col-lg-3">{{ __('Course Title') }} <span class="text-danger">*</span></label>
                                <div class="col-lg-9">
                                    <input type="text" name="course_title" id="course_title" value="{{get_option('course_title')}}" class="form-control" required>
                                </div>
                            </div>
                            <div class="form-group text-black row mb-3">
                                <label for="course_subtitle" class="col-lg-3">{{ __('Course Subtitle') }} <span class="text-danger">*</span></label>
                                <div class="col-lg-9">
                                    <input type="text" name="course_subtitle" id="course_subtitle" value="{{get_option('course_subtitle')}}" class="form-control" required>
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
