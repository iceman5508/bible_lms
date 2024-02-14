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
                                <h2>{{ __('Course Upload Rules') }}</h2>
                            </div>
                        </div>
                        <div class="breadcrumb__content__right">
                            <nav aria-label="breadcrumb">
                                <ul class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">{{__('Dashboard')}}</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">{{ __('Upload Rules') }}</li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="customers__area bg-style mb-30">
                        <div class="item-title d-flex justify-content-between">
                            <h2>{{ __('Course Upload Rules') }}</h2>
                        </div>
                        <form action="{{route('course-rules.store')}}" method="post" class="form-horizontal">
                            @csrf
                            <div class="input__group mb-25 row">
                                <label for="title" class="col-lg-11"> {{ __('Title') }} <span class="text-danger">*</span></label>
                                <div class="col-lg-11">
                                    <input type="text" name="courseUploadRuleTitle" id="courseUploadRuleTitle" value="{{ get_option('courseUploadRuleTitle') }}" class="form-control" required>
                                </div>
                            </div>

                            <div id="add_repeater" class="mb-3">
                                <div data-repeater-list="course_upload_rules" class="">
                                    @if($courseRules->count() > 0)
                                        @foreach($courseRules as $courseRule)
                                            <div data-repeater-item="" class="form-group row ">
                                                <input type="hidden" name="id" value="{{ @$courseRule['id'] }}"/>

                                                <div class="custom-form-group mb-3 col-md-11 col-lg-11 col-xl-11 col-xxl-11">
                                                    <label for="description_{{ @$courseRule['id'] }}" class="text-lg-right text-black"> {{ __('Description') }} </label>
                                                    <textarea name="description" id="description_{{ @$courseRule['id'] }}" class="form-control" rows="5"
                                                              required>{{ $courseRule->description }}</textarea>

                                                </div>

                                                <div class="col-lg-1 mt-4 removeClass">
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
                                            <div class="custom-form-group mb-3 col-md-11 col-lg-11 col-xl-11 col-xxl-11">
                                                <label for="description" class="text-lg-right text-black"> {{ __('Description') }} </label>
                                                <textarea name="description" id="description" class="form-control" rows="5" required></textarea>

                                            </div>

                                            <div class="col-lg-1 mt-4 removeClass">
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

                            <div class="row mb-3">
                                <div class="col-md-12 text-right">
                                    <button type="submit" class="btn btn-primary">{{ __('Save') }}</button>
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
    <script src="{{ asset('common/js/jquery.repeater.min.js') }}"></script>
    <script src="{{ asset('common/js/add-repeater.js') }}"></script>
@endpush
