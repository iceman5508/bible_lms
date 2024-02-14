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
                    <div class="email-inbox__area  bg-style admin-instructor-procedure-settings">
                        <div class="item-top mb-30"><h2>{{ __(@$title) }}</h2></div>
                        <form action="{{route('settings.instructor-procedure.update')}}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div id="add_repeater" class="mb-3">
                                <div data-repeater-list="instructor_procedures" class="">
                                    @if($instructorProcedures->count() > 0)
                                        @foreach($instructorProcedures as $procedure)
                                            <div data-repeater-item="" class="form-group row ">
                                                <input type="hidden" name="id" value="{{ @$procedure['id'] }}"/>
                                                <div class="custom-form-group mb-3 col-md-12 col-lg-3 col-xl-3 col-xxl-2">
                                                    <label for="image_{{ @$procedure['id'] }}" class=" text-lg-right text-black"> {{ __('Image') }} </label>

                                                    <div class="upload-img-box">
                                                        @if($procedure->image)
                                                            <img src="{{getImageFile($procedure->image_path)}}">
                                                        @else
                                                            <img src="" alt="">
                                                        @endif
                                                        <input type="file" name="image" id="image_{{ @$procedure['id'] }}" accept="image/*" onchange="preview505540DimensionFile(this)">
                                                        <div class="upload-img-box-icon">
                                                            <i class="fa fa-camera"></i>
                                                            <p class="m-0">{{ __('Image') }}</p>
                                                        </div>
                                                    </div>
                                                    <p><span class="text-black">{{ __('Accepted Files') }}: </span>JPG, JPEG, PNG <br> <span class="text-black">{{ __('Accepted Size') }}:</span> 505 x 540 (1MB)</p>

                                                </div>
                                                <div class="custom-form-group mb-3 col-md-12 col-lg-4 col-xl-4 col-xxl-4">
                                                    <label for="title_{{ @$procedure['id'] }}" class="text-lg-right text-black"> {{ __('Title') }} </label>
                                                    <div class="">
                                                        <input type="text" name="title" id="title_{{ @$procedure['id'] }}" value="{{ $procedure->title }}" class="form-control" placeholder="{{ __('Type title') }}" required>
                                                    </div>
                                                </div>
                                                <div class="custom-form-group mb-3 col-md-12 col-lg-4 col-xl-4 col-xxl-5">
                                                    <label for="subtitle_{{ @$procedure['id'] }}" class="text-lg-right text-black"> {{ __('Subtitle') }} </label>
                                                    <textarea name="subtitle" id="subtitle_{{ @$procedure['id'] }}" class="form-control" rows="5"
                                                              required>{{ $procedure->subtitle }}</textarea>

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
                                                <label for="image" class=" text-lg-right text-black"> {{ __('Image') }} </label>

                                                <div class="upload-img-box">
                                                    <img src="" alt="">
                                                    <input type="file" name="image" id="image" accept="image/*"  onchange="preview505540DimensionFile(this)">
                                                    <div class="upload-img-box-icon">
                                                        <i class="fa fa-camera"></i>
                                                        <p class="m-0">{{ __('Image') }}</p>
                                                    </div>
                                                </div>
                                                <p><span class="text-black">{{ __('Accepted Files') }}: </span>{{ __('JPG') }}, {{ __('JPEG') }}, {{ __('PNG') }} <br> <span class="text-black">{{ __('Accepted Size') }}:</span> 505 x 540 (1MB)</p>
                                            </div>
                                            <div class="custom-form-group mb-3 col-md-12 col-lg-4 col-xl-4 col-xxl-4">
                                                <label for="title" class="text-lg-right text-black">{{ __('Title') }}</label>
                                                <div class="">
                                                    <input type="text" name="title" id="title" value="" class="form-control" placeholder="{{ __('Type title') }}" required>
                                                </div>
                                            </div>
                                            <div class="custom-form-group mb-3 col-md-12 col-lg-4 col-xl-4 col-xxl-5">
                                                <label for="subtitle" class="text-lg-right text-black">{{ __('Subtitle') }}</label>
                                                <div class="">
                                                    <textarea name="subtitle" id="subtitle" class="form-control" rows="5"  required></textarea>
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
    <script src="{{ asset('common/js/jquery.repeater.min.js') }}"></script>
    <script src="{{ asset('common/js/add-repeater.js') }}"></script>
@endpush
