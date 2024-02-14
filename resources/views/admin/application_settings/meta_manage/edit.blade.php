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
                                    <li class="breadcrumb-item"><a href="{{route('settings.meta.index')}}">{{__('Meta Management')}}</a></li>
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
                    <div class="email-inbox__area form-horizontal__item bg-style bg-style admin-dashboard-meta-settings">
                        <div class="item-top mb-30"><h2>{{ __(@$title) . ' - ' . $meta->page_name }}</h2></div>
                        <form action="{{route('settings.meta.update', [$meta->uuid])}}" method="post" enctype="multipart/form-data">
                            @csrf

                            <div class="input__group mb-25 row">
                                <label for="meta_title" class="col-lg-3 text-lg-right text-black mb-2"> {{__('Meta Title')}} </label>
                                <div class="col-lg-9">
                                    <textarea name="meta_title" id="meta_title" class="form-control" rows="5" required>{{$meta->meta_title}}</textarea>
                                </div>
                            </div>

                            <div class="input__group mb-25 row">
                                <label for="meta_description" class="col-lg-3 text-lg-right text-black mb-2"> {{__('Meta Description')}} </label>
                                <div class="col-lg-9">
                                    <textarea name="meta_description" id="meta_description" rows="5" class="form-control" required>{{$meta->meta_description}}</textarea>
                                </div>
                            </div>

                            <div class="input__group mb-25 row">
                                <label for="meta_keyword" class="col-lg-3 text-lg-right text-black mb-2"> {{__('Meta Keywords')}} ({{ __('Separate with commas') }})</label>
                                <div class="col-lg-9">
                                    <textarea name="meta_keyword" id="meta_keyword" rows="5" class="form-control">{{$meta->meta_keyword}}</textarea>
                                </div>
                            </div>

                            <div class="input__group mb-25 row">
                                <label for="og_image" class="col-lg-3 text-lg-right text-black mb-2">{{ __('OG Image') }}</label>
                                <div class="col-lg-9">
                                    <div class="upload-img-box">
                                        @if($meta->og_image != NULL && $meta->og_image != '')
                                            <img src="{{getImageFile($meta->og_image)}}">
                                        @else
                                            <img src="">
                                        @endif
                                        <input type="file" name="og_image" id="og_image" accept="image/*" onchange="previewFile(this)">
                                        <div class="upload-img-box-icon">
                                            <i class="fa fa-camera"></i>
                                            <p class="m-0">{{__('OG Image')}}</p>
                                        </div>
                                    </div>
                                    @if ($errors->has('og_image'))
                                        <span class="text-danger"><i class="fas fa-exclamation-triangle"></i> {{ $errors->first('og_image') }}</span>
                                    @endif
                                    <p><span class="text-black">{{ __('Accepted Files') }}:</span> PNG, JPG <br> <span class="text-black">{{ __('Recommend Size') }}:</span> 1200 x 627</p>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12">
                                    <div class="input__group general-settings-btn">
                                        <div class="float-right">
                                            @updateButton
                                        </div>
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
