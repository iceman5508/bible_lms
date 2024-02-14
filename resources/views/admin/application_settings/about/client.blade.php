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
                    <div class="email-inbox__area bg-style admin-about-us-client">
                        <div class="item-top mb-30"><h2>{{ __(@$title) }}</h2></div>
                        <form action="{{route('settings.about.client.update')}}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div id="add_repeater" class="mb-3">
                                <div data-repeater-list="client_details" class="">
                                    @if($clients->count() > 0)
                                        @foreach($clients as $client)
                                            <div data-repeater-item="" class="form-group row">
                                                <input type="hidden" name="id" value="{{ @$client['id'] }}"/>
                                                <div class="custom-form-group mb-3 col-md-12 col-lg-3 col-xl-3 col-xxl-2">
                                                    <label for="image_{{ @$client['id'] }}" class=" text-lg-right text-black"> {{ __('Client Logo') }} </label>
                                                    <div class="upload-img-box">
                                                        @if($client->logo)
                                                            <img src="{{getImageFile($client->image_path)}}">
                                                        @else
                                                            <img src="">
                                                        @endif
                                                        <input type="file" name="logo" id="image_{{ @$client['id'] }}" accept="image/*" onchange="preview12041DimensionFile(this)">
                                                        <div class="upload-img-box-icon">
                                                            <i class="fa fa-camera"></i>
                                                        </div>
                                                    </div>
                                                    <p><span class="text-black">{{ __('Accepted Files') }}:</span> PNG <br> <span class="text-black">{{ __('Accepted Size') }}:</span> 120 x 41</p>
                                                </div>
                                                <div class="custom-form-group mb-3 col-md-12 col-lg-8 col-xl-8 col-xxl-9">
                                                    <label for="name_{{ @$client['id'] }}" class="text-lg-right text-black"> {{ __('Client Name') }} </label>
                                                    <div class="">
                                                        <input type="text" name="name" id="name_{{ @$client['id'] }}" value="{{ $client->name }}" class="form-control" placeholder="{{ __('Type name') }}" required>
                                                    </div>
                                                </div>

                                                <div class="col-lg-1 mb-3 removeClass">
                                                    <label class="text-lg-right text-black opacity-0">{{ __('Remove') }}</label>
                                                    <a href="javascript:;" data-repeater-delete=""
                                                       class="btn btn-icon-remove btn-danger">
                                                        <i class="fas fa-times"></i>
                                                    </a>
                                                </div>

                                            </div>
                                        @endforeach
                                    @else
                                        <div data-repeater-item="" class="form-group row">
                                            <div class="custom-form-group mb-3 col-md-12 col-lg-3 col-xl-3 col-xxl-2">
                                                <label for="image" class=" text-lg-right text-black"> {{ __('Client Logo') }} </label>
                                                <div class="upload-img-box">
                                                    <img src="">
                                                    <input type="file" name="logo" id="image" accept="image/*"  onchange="preview12041DimensionFile(this)">
                                                    <div class="upload-img-box-icon">
                                                        <i class="fa fa-camera"></i>
                                                        <p class="m-0">{{__('Image')}}</p>
                                                    </div>
                                                </div>
                                                <p><span class="text-black">{{ __('Accepted Files') }}:</span> {{ __('PNG') }} <br> <span class="text-black">{{ __('Accepted Size') }}:</span> 120 x 41</p>
                                            </div>
                                            <div class="custom-form-group mb-3 col-md-12 col-lg-8 col-xl-8 col-xxl-9">
                                                <label for="name" class="text-lg-right text-black"> {{ __('Client Name') }}</label>
                                                <div class="">
                                                    <input type="text" name="name" id="name" value="" class="form-control" placeholder="{{ __('Type name') }}" required>
                                                </div>
                                            </div>

                                            <div class="col-lg-1 mb-3 removeClass">
                                                <label class="text-lg-right text-black opacity-0">{{ __('Remove') }}</label>
                                                <a href="javascript:;" data-repeater-delete=""
                                                   class="btn btn-icon-remove btn-danger">
                                                    <i class="fas fa-times"></i>
                                                </a>
                                            </div>

                                        </div>
                                    @endif
                                </div>

                                <div class="col-lg-2">
                                    <a id="add" href="javascript:;" data-repeater-create=""
                                       class="btn btn-blue">
                                        <i class="fas fa-plus"></i> {{ __('Add') }}
                                    </a>
                                </div>

                            </div>

                            <div class="row justify-content-end">
                                <div class="col-md-2 text-right">
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
    <script src="{{ asset('common/js/jquery.repeater.min.js') }}"></script>
    <script src="{{ asset('common/js/add-repeater.js') }}"></script>
@endpush
