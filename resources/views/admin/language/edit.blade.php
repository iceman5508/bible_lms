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
                                <h2>{{__('Edit Language')}}</h2>
                            </div>
                        </div>
                        <div class="breadcrumb__content__right">
                            <nav aria-label="breadcrumb">
                                <ul class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">{{__('Dashboard')}}</a></li>
                                    <li class="breadcrumb-item"><a href="{{route('language.index')}}">{{__('Language Settings')}}</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">{{__('Edit Language')}}</li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-vertical__item bg-style">
                        <div class="item-top mb-30">
                            <h2>{{__('Edit Language')}}</h2>
                        </div>
                        <form action="{{route('language.update', $language->id)}}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="input__group mb-25">
                                <label for="language"> {{__('Name')}} </label>
                                <div>
                                    <input type="text" name="language" id="language" value="{{ $language->language }}" class="form-control flat-input" placeholder=" {{__('Name')}} ">
                                    @if ($errors->has('language'))
                                        <span class="text-danger"><i class="fas fa-exclamation-triangle"></i> {{ $errors->first('language') }}</span>
                                    @endif
                                </div>
                            </div>

                            <div class="input__group mb-25">
                                <label for="iso_code"> {{__('ISO Code')}} </label>
                                <div>
                                    <input type="text" name="iso_code" id="iso_code" value="{{$language->iso_code }}" class="form-control flat-input" placeholder=" {{__('ISO Code')}} " disabled>
                                    @if ($errors->has('iso_code'))
                                        <span class="text-danger"><i class="fas fa-exclamation-triangle"></i> {{ $errors->first('iso_code') }}</span>
                                    @endif
                                    <div class="mt-5">
                                        <span class="status blocked">{{ __('Note:') }} {{ __('You can`t change it.') }}</span>
                                    </div>
                                    <div class="mt-3">
                                        <a href="https://en.wikipedia.org/wiki/ISO_3166-1#Current_codes" target="_blank"><b><i class="fa fa-list mr-1"></i> {{ __('ISO Code List') }} </b></a>
                                    </div>

                                </div>
                            </div>


                            <div class="input__group mb-25">
                                <label for="flag" class="col-lg-3 text-lg-right text-black"> {{__('Flag')}} </label>
                                <div class="col-lg-3">
                                    <div class="upload-img-box">
                                        @if($language->flag)
                                            <img src="{{ getImageFile($language->flag) }}" alt="">
                                        @else
                                            <img src="{{ getImageFile('uploads/default/no-image-found.png') }}">
                                        @endif
                                        <input type="file" name="flag" id="flag" accept="image/*" onchange="previewFile(this)">

                                    </div>
                                </div>
                            </div>

                            <div class="custom-form-group mb-3 row">
                                <label for="rtl" class="col-lg-2 text-lg-right text-black"> {{ __('RTL Support') }}</label>
                                <div class="col-lg-1">
                                    <input type="checkbox" {{ $language->rtl == 1 ? 'checked' : '' }} name="rtl" value="1" id="rtl">
                                </div>
                            </div>
                            <div class="custom-form-group mb-3 row">
                                <label for="default_language" class="col-lg-2 text-lg-right text-black"> {{ __('Default Language') }}</label>
                                <div class="col-lg-1">
                                    <input type="checkbox" {{ $language->default_language == 'on' ? 'checked' : '' }} name="default_language" value="on" id="default_language">
                                </div>
                            </div>


                            <div class="input__group">
                                <div class="input__button ">
                                    <button type="submit" class="btn btn-blue">{{__('Update')}}</button>
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
