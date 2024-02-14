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
                    <div class="email-inbox__area bg-style admin-about-us-instructor-support">
                        <div class="item-top mb-30"><h2>{{ __(@$title) }}</h2></div>
                        <form action="{{route('settings.about.instructor-support.update')}}" method="post" enctype="multipart/form-data">
                            @csrf

                            <div class=" custom-form-group mb-3 row">
                                <label for="instructor_support_title" class="col-lg-3 text-lg-right text-black"> {{ __('Instructor Support Title') }} </label>
                                <div class="col-lg-9">
                                    <input type="text" name="instructor_support_title" id="instructor_support_title" value="{{ @$aboutUsGeneral->instructor_support_title }}"
                                           class="form-control" placeholder="{{ __('Type team member title') }}">
                                    @if ($errors->has('instructor_support_title'))
                                        <span class="text-danger"><i class="fas fa-exclamation-triangle"></i> {{ $errors->first('instructor_support_title') }}</span>
                                    @endif
                                </div>
                            </div>

                            <div class="col-lg-12 custom-form-group mb-3 row">
                                <label for="instructor_support_subtitle" class="col-lg-3 text-lg-right text-black"> {{ __('Instructor Support Subtitle') }} </label>
                                <div class="col-lg-9">
                                    <textarea name="instructor_support_subtitle" class="form-control" rows="5" id="instructor_support_subtitle"
                                              required>{{ @$aboutUsGeneral->instructor_support_subtitle }}</textarea>
                                    @if ($errors->has('instructor_support_subtitle'))
                                        <span class="text-danger"><i class="fas fa-exclamation-triangle"></i> {{ $errors->first('instructor_support_subtitle') }}</span>
                                    @endif
                                </div>
                            </div>
                            <hr>
                            <div id="add_repeater" class="mb-3">
                                <div data-repeater-list="instructor_supports" class="">
                                    @if(count($instructorSupports) > 0)
                                        @foreach($instructorSupports as $instructorSupport)
                                            <div data-repeater-item="" class="form-group row ">
                                                <input type="hidden" name="id" value="{{ @$instructorSupport['id'] }}"/>
                                                <div class="custom-form-group mb-3 col-lg-2">
                                                    <label for="image_{{ $instructorSupport->id }}" class=" text-lg-right text-black"> Logo </label>
                                                    <div class="upload-img-box">
                                                        @if($instructorSupport->logo)
                                                            <img src="{{getImageFile($instructorSupport->image_path)}}">
                                                        @else
                                                            <img src="">
                                                        @endif
                                                        <input type="file" name="logo" id="image_{{ $instructorSupport->id }}" accept="image/*" onchange="preview148DimensionsFile(this)">
                                                        <div class="upload-img-box-icon">
                                                            <i class="fa fa-camera"></i>
                                                        </div>
                                                    </div>
                                                    <p><span class="text-black">{{ __('Accepted Files') }}:</span> PNG <br> <span class="text-black">{{ __('Accepted Size') }}:</span> 148 x 148</p>
                                                </div>

                                                <div class="custom-form-group mb-3 col-lg-5">
                                                    <label for="title_{{ $instructorSupport->id }}" class="text-lg-right text-black"> {{ __('Title') }} </label>
                                                    <input type="text" name="title" id="title_{{ $instructorSupport->id }}" value="{{ $instructorSupport->title }}"
                                                           class="form-control" placeholder="{{ __('Type name') }}">

                                                </div>
                                                <div class="custom-form-group mb-3 col-lg-5">
                                                    <label for="subtitle_{{ $instructorSupport->id }}" class="text-lg-right text-black"> {{ __('Subtitle') }} </label>
                                                    <textarea name="subtitle" class="form-control" rows="5" id="subtitle_{{ $instructorSupport->id }}"
                                                              required>{{ $instructorSupport->subtitle }}</textarea>
                                                </div>
                                                <div class="custom-form-group mb-3 col-lg-3">
                                                    <label for="button_name_{{ $instructorSupport->id }}" class="text-lg-right text-black"> {{ __('Button Name') }} </label>
                                                    <input type="text" name="button_name" id="button_name_{{ $instructorSupport->id }}"
                                                           value="{{ $instructorSupport->button_name }}" class="form-control" placeholder="{{ __('Type button name') }}">
                                                </div>
                                                <div class="custom-form-group mb-3 col-lg-9">
                                                    <label for="button_link_{{ $instructorSupport->id }}" class="text-lg-right text-black"> {{ __('Button Link') }} </label>
                                                    <input type="text" name="button_link" id="button_link_{{ $instructorSupport->id }}"
                                                           value="{{ $instructorSupport->button_link }}" class="form-control" placeholder="{{ __('Type button link') }}">
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
                                        <div data-repeater-item="" class="form-group row ">
                                            <div class="custom-form-group mb-3 col-lg-2">
                                                <label for="image" class=" text-lg-right text-black"> {{ __('Logo') }} </label>
                                                <div class="upload-img-box">
                                                    <img src="">
                                                    <input type="file" name="logo" id="image" accept="image/*" onchange="preview148DimensionsFile(this)">
                                                    <div class="upload-img-box-icon">
                                                        <i class="fa fa-camera"></i>
                                                    </div>
                                                </div>
                                                <p><span class="text-black">{{ __('Accepted Files') }}:</span> {{ __('PNG') }} <br> <span class="text-black">{{ __('Accepted Size') }}:</span> 148 x 148</p>
                                            </div>

                                            <div class="custom-form-group mb-3 col-lg-5">
                                                <label for="title" class="text-lg-right text-black"> {{ __('Title') }} </label>
                                                <input type="text" name="title" id="title" value="" class="form-control" placeholder="{{ __('Type name') }}">

                                            </div>
                                            <div class="custom-form-group mb-3 col-lg-5">
                                                <label for="subtitle" class="text-lg-right text-black"> {{ __('Subtitle') }} </label>
                                                <textarea name="subtitle" class="form-control" rows="5" id="subtitle" required></textarea>
                                            </div>
                                            <div class="custom-form-group mb-3 col-lg-3">
                                                <label for="button_name" class="text-lg-right text-black"> {{ __('Button Name') }} </label>
                                                <input type="text" name="button_name" id="button_name" value="" class="form-control" placeholder="{{ __('Type button name') }}">
                                            </div>
                                            <div class="custom-form-group mb-3 col-lg-9">
                                                <label for="button_link" class="text-lg-right text-black"> {{ __('Button Link') }} </label>
                                                <input type="text" name="button_link" id="button_link" value="" class="form-control" placeholder="{{ __('Type button link') }}">
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
