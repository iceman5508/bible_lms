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
                    <div class="customers__area bg-style mb-30">
                        <div class="item-title d-flex justify-content-between">
                            <h2>{{ __('Theme Settings') }}</h2>
                        </div>
                        <form action="{{route('settings.save.setting')}}" method="post" class="form-horizontal" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="bg-dark-secondary-soft-varient col-12 mb-24 py-2 rounded text-body text-center">
                                    <small>{{ __('You need to adjust the homepage data from `Application Setting/Home Settings` to get the best view.') }}</small>
                                </div>
                                <div class="col-lg-6 col-sm-12 mb-24">
                                    <div class="input__group mb-25">
                                        <label for="name">{{ __('Choose Theme') }}</label>
                                        <select name="theme" id="theme" required>
                                            @foreach(getThemes() as $index => $theme)
                                                <option value="{{ $index }}" {{ $index == get_option('theme', THEME_DEFAULT) ? 'selected' : '' }}>{{ $theme }} </option>
                                            @endforeach
                                        </select>
                                        @if ($errors->has('theme'))
                                            <span class="text-danger"><i class="fas fa-exclamation-triangle"></i> {{ $errors->first('theme') }}</span>
                                        @endif
                                    </div>
                                    @updateButton
                                </div>
                                <div class="col-lg-6 col-sm-12 h-500 overflow-auto overflow-y-auto">
                                    <img class="theme-img {{ get_option('theme', THEME_DEFAULT) == THEME_DEFAULT ? '' : 'd-none' }}" id="theme-{{ THEME_DEFAULT }}" src="{{ asset('admin/images/background/theme-1.jpg') }}" alt="">
                                    <img class="theme-img {{ get_option('theme', THEME_DEFAULT) == THEME_TWO ? '' : 'd-none' }}" id="theme-{{ THEME_TWO }}" src="{{ asset('admin/images/background/theme-2.jpg') }}" alt="">
                                    <img class="theme-img {{ get_option('theme', THEME_DEFAULT) == THEME_THREE ? '' : 'd-none' }}" id="theme-{{ THEME_THREE }}" src="{{ asset('admin/images/background/theme-3.jpg') }}" alt="">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-12 text-right">
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
        $(document).on('change', '#theme', function(){
            $('.theme-img').addClass('d-none');
            $('#theme-'+$(this).val()).removeClass('d-none');
        });
    </script>
@endpush