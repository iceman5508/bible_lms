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
                    <div class="email-inbox__area  bg-style">
                        <div class="item-top mb-30"><h2>{{ __(@$title) }}</h2></div>
                        <form action="{{route('settings.about.our-history.update')}}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="custom-form-group mb-3 row">
                                <label for="our_history_title" class="col-lg-3 text-lg-right text-black"> {{ __('Our History Title') }} </label>
                                <div class="col-lg-9">
                                    <input type="text" name="our_history_title" id="our_history_title" value="{{ @$aboutUsGeneral->our_history_title }}"
                                           class="form-control" placeholder="{{ __('Type our history title') }}">
                                </div>
                            </div>

                            <div class="custom-form-group mb-3 row">
                                <label for="our_history_subtitle" class="col-lg-3 text-lg-right text-black"> {{ __('Our History Subtitle') }} </label>
                                <div class="col-lg-9 ">
                                <textarea name="our_history_subtitle" class="form-control" rows="5" id="our_history_subtitle" placeholder="{{ __('Type our history subtitle') }}"
                                          required>{{ @$aboutUsGeneral->our_history_subtitle }}</textarea>
                                </div>
                            </div>
                            <hr>
                            <div id="add_repeater" class="mb-3">
                                <div data-repeater-list="our_histories" class="">
                                    @if($ourHistories->count() > 0)
                                        @foreach($ourHistories as $ourHistory)
                                            <div data-repeater-item="" class="form-group row">
                                            <input  type="hidden" name="id" value="{{$ourHistory->id}}">
                                                <div class="custom-form-group mb-3 col-lg-3">
                                                    <label for="year_{{ $ourHistory['id'] }}" class=" text-lg-right text-black">{{ __('Year') }} </label>
                                                    <input type="number" name="year" id="year_{{ $ourHistory['id'] }}" value="{{ $ourHistory->year }}" class="form-control" placeholder="{{ __('Type year') }}" required>
                                                </div>

                                                <div class="custom-form-group mb-3 col-lg-4">
                                                    <label for="title_{{ $ourHistory['id'] }}" class="text-lg-right text-black"> {{ __('Title') }} </label>
                                                    <input type="text" name="title" id="title_{{ $ourHistory['id'] }}" value="{{ $ourHistory->title }}" class="form-control" placeholder="{{ __('Type subtitle') }}" required>
                                                </div>
                                                <div class="custom-form-group mb-3 col-lg-4">
                                                    <label for="subtitle_{{ $ourHistory['id'] }}" class="text-lg-right text-black"> {{ __('Subtitle') }} </label>
                                                    <textarea name="subtitle" class="form-control" rows="5" id="subtitle_{{ $ourHistory['id'] }}" required>{{ $ourHistory->subtitle }}</textarea>
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
                                            <div class="custom-form-group mb-3 col-lg-3">
                                                <label for="upgrade_skill_title" class=" text-lg-right text-black">{{ __('Year') }} </label>
                                                <input type="number" name="year" id="year" value="" class="form-control" placeholder="{{ __('Type year') }}" required>
                                            </div>

                                            <div class="custom-form-group mb-3 col-lg-4">
                                                <label for="title" class="text-lg-right text-black"> {{ __('Title') }} </label>
                                                <input type="text" name="title" id="title" value="" class="form-control" placeholder="{{ __('Type subtitle') }}" required>

                                            </div>
                                            <div class="custom-form-group mb-3 col-lg-4">
                                                <label for="subtitle" class="text-lg-right text-black"> {{ __('Subtitle') }} </label>
                                                <textarea name="subtitle" class="form-control" rows="5" id="subtitle" required></textarea>
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
