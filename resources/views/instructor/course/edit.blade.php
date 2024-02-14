@extends('layouts.instructor')

@section('breadcrumb')
<div class="page-banner-content text-center">
    <h3 class="page-banner-heading text-white pb-15"> {{__('Upload Course')}} </h3>

    <!-- Breadcrumb Start-->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb justify-content-center">
            <li class="breadcrumb-item font-14"><a href="{{route('instructor.dashboard')}}">{{__('Dashboard')}}</a></li>
            <li class="breadcrumb-item font-14"><a href="{{ route('instructor.course') }}">{{__('My Courses')}}</a></li>
            <li class="breadcrumb-item font-14 active" aria-current="page">{{__('Upload Course')}}</li>
        </ol>
    </nav>
</div>
@endsection

@section('content')
<div class="instructor-profile-right-part instructor-upload-course-box-part">
    <div class="instructor-upload-course-box">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div id="msform">
                        <!-- progressbar -->
                        <ul id="progressbar"
                            class="upload-course-item-block d-flex align-items-center justify-content-center">
                            <li class="active" id="account"><strong>{{ __('Course Overview') }}</strong></li>
                            <li id="personal"><strong>{{ __('Upload Video') }}</strong></li>
                            <li id="instructor"><strong>{{ __('Instructors') }}</strong></li>
                            <li id="confirm"><strong>{{ __('Submit process') }}</strong></li>
                        </ul>

                        <!-- Upload Course Step-1 Item Start -->
                        <div class="upload-course-step-item upload-course-overview-step-item">

                            <!-- Upload Course Overview-1 start -->
                            <div id="upload-course-overview-1">
                                <form method="POST" action="{{route('course.update.overview', [$course->uuid])}}"
                                    id="step1" enctype="multipart/form-data" class="row g-3 needs-validation" novalidate>
                                    @csrf
                                    @if(get_option('courseUploadRuleTitle'))
                                    <div class="upload-course-item-block course-overview-step1 radius-8 mb-30">
                                        <div class="upload-course-item-block-title mb-3">
                                            <h6 class="font-20">{{ __(get_option('courseUploadRuleTitle')) }}</h6>
                                        </div>
                                        <ul class="mb-30">
                                            @foreach($rules as $rule)
                                            <li><span class="iconify" data-icon="akar-icons:arrow-right"></span>{{
                                                $rule->description }}</li>
                                            @endforeach
                                        </ul>

                                    </div>
                                    @endif
                                    <div class="upload-course-item-block course-overview-step1 radius-8">
                                        <div class="upload-course-item-block-title mb-3">
                                            <h6 class="font-20">{{ __('Course Details') }}</h6>
                                        </div>

                                        <div class="row mb-30">
                                            <div class="col-md-12">
                                                <div class="label-text-title color-heading font-medium font-16 mb-3">{{
                                                    __('Course Type') }}
                                                    <span class="text-danger">*</span>
                                                </div>

                                                <select name="course_type" id="course_type" class="form-select"
                                                    required>
                                                    <option value="">{{ __('Select Course
                                                        Type') }}</option>
                                                    <option value="{{ COURSE_TYPE_GENERAL }}"
                                                        {{old('course_type', $course->course_type)==COURSE_TYPE_GENERAL ? 'selected' : '' }}>
                                                        General</option>
                                                    <option value="{{ COURSE_TYPE_SCORM }}"
                                                        {{old('course_type', $course->course_type)==COURSE_TYPE_SCORM ? 'selected' : '' }}>
                                                        SCORM</option>
                                                </select>

                                                @if ($errors->has('course_type'))
                                                <span class="text-danger"><i class="fas fa-exclamation-triangle"></i> {{
                                                    $errors->first('course_type') }}</span>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="row mb-30">
                                            <div class="col-md-12">
                                                <div class="label-text-title color-heading font-medium font-16 mb-3">{{
                                                    __('Course Title') }}
                                                    <span class="text-danger">*</span>
                                                </div>

                                                <input type="text" name="title" value="{{$course->title}}"
                                                    class="form-control"
                                                    placeholder="Eg: Figma Essential Training - 2021" required>
                                                @if ($errors->has('title'))
                                                <span class="text-danger"><i class="fas fa-exclamation-triangle"></i> {{
                                                    $errors->first('title') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="row mb-30">
                                            <div class="col-md-12">
                                                <div class="label-text-title color-heading font-medium font-16 mb-3">{{
                                                    __('Course Subtitle') }}
                                                    <span class="text-danger">*</span>
                                                </div>

                                                <input type="text" name="subtitle" value="{{$course->subtitle}}"
                                                    class="form-control"
                                                    placeholder="Eg: Figma Essential Training - 2021" required>
                                                @if ($errors->has('subtitle'))
                                                <span class="text-danger"><i class="fas fa-exclamation-triangle"></i> {{
                                                    $errors->first('subtitle') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                        @if(get_option('subscription_mode'))
                                        <div class="row mb-30">
                                            <div class="col-md-12">
                                                <div class="label-text-title color-heading font-medium font-16 mb-3">{{
                                                    __('Enable for subscription') }}
                                                    <span class="text-danger">*</span>
                                                </div>

                                                <select name="is_subscription_enable" id="is_subscription_enable" class="form-select"
                                                    required>
                                                    <option value="{{ PACKAGE_STATUS_ACTIVE }}"
                                                        {{old('is_subscription_enable', $course->is_subscription_enable)==PACKAGE_STATUS_ACTIVE ? 'selected' : '' }}>
                                                        {{ __("Enable") }}</option>
                                                    <option value="{{ PACKAGE_STATUS_DISABLED }}"
                                                        {{old('is_subscription_enable', $course->is_subscription_enable)==PACKAGE_STATUS_DISABLED ? 'selected' : '' }}>
                                                        {{ __("Disabled") }}</option>
                                                </select>

                                                @if ($errors->has('is_subscription_enable'))
                                                <span class="text-danger"><i class="fas fa-exclamation-triangle"></i> {{
                                                    $errors->first('is_subscription_enable') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                        @endif
                                        <div class="row mb-30">
                                            <div class="col-md-12">
                                                <div class="label-text-title color-heading font-medium font-16 mb-3">{{
                                                    __('Course Description Key Points') }}
                                                    <span class="text-danger">*</span>
                                                </div>
                                                <div id="add_repeater">
                                                    <div data-repeater-list="key_points" class="">
                                                        @if($keyPoints->count() > 0)
                                                        @foreach($keyPoints as $keyPoint)
                                                        <label for="name_{{ $keyPoint['id'] }}"
                                                            class="text-lg-right text-black"> {{ __('Name') }} </label>
                                                        <div data-repeater-item=""
                                                            class="form-group row align-items-center">
                                                            <input type="hidden" name="id" value="{{ $keyPoint->id }}">
                                                            <div class="custom-form-group mb-3 col-md-10">
                                                                <input type="text" name="name"
                                                                    id="name_{{ $keyPoint['id'] }}"
                                                                    value="{{ $keyPoint['name'] }}" class="form-control"
                                                                    placeholder="Type key point name" required>
                                                            </div>

                                                            <div class="col mb-3">
                                                                <a href="javascript:;" data-repeater-delete=""
                                                                    class="theme-btn theme-button1 default-delete-btn-red default-hover-btnn btn-danger">
                                                                    <span class="iconify"
                                                                        data-icon="akar-icons:cross"></span>
                                                                </a>
                                                            </div>
                                                        </div>
                                                        @endforeach
                                                        @else
                                                        <div data-repeater-item=""
                                                            class="form-group row align-items-center">
                                                            <div class="custom-form-group mb-3 col-md-10">
                                                                <label for="name" class="text-lg-right text-black"> {{
                                                                    __('Name') }} </label>
                                                                <input type="text" name="name" id="name" value=""
                                                                    class="form-control"
                                                                    placeholder="Type key point name" required>
                                                            </div>

                                                            <div class="col mb-3">
                                                                <a href="javascript:;" data-repeater-delete=""
                                                                    class="theme-btn theme-button1 default-delete-btn-red default-hover-btn btn-danger">
                                                                    <span class="iconify"
                                                                        data-icon="akar-icons:cross"></span>
                                                                </a>
                                                            </div>
                                                        </div>
                                                        @endif
                                                    </div>

                                                    <div class="col-lg-2">
                                                        <a id="add" href="javascript:;" data-repeater-create=""
                                                            class="theme-btn default-hover-btn theme-button1">
                                                            <span class="iconify" data-icon="akar-icons:plus"></span> {{
                                                            __('Add') }}
                                                        </a>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mb-30">
                                            <div class="col-md-12">
                                                <div class="label-text-title color-heading font-medium font-16 mb-3">{{
                                                    __('Course Description') }}
                                                    <span class="text-danger">*</span>
                                                </div>
                                                <textarea class="form-control" name="description" cols="30" rows="10"
                                                    required
                                                    placeholder="Course description in 250 characters">{{$course->description}}</textarea>
                                                @if ($errors->has('description'))
                                                <span class="text-danger"><i class="fas fa-exclamation-triangle"></i> {{
                                                    $errors->first('description') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12 mb-30">
                                                <label class="font-medium font-15 color-heading">{{__('Meta Title')}}</label>
                                                <input type="text" name="meta_title" value="{{$course->meta_title}}" class="form-control" placeholder="{{ __('Meta Title') }}">
                                                @if ($errors->has('meta_title'))
                                                    <span class="text-danger"><i class="fas fa-exclamation-triangle"></i> {{ $errors->first('meta_title') }}</span>
                                                @endif

                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-12 mb-30">
                                                <label class="font-medium font-15 color-heading">{{__('Meta Description')}}</label>
                                                <textarea class="form-control" name="meta_description" id="exampleFormControlTextarea1" rows="3" placeholder="{{ __('Type Meta Description') }}">{{$course->meta_description}}</textarea>
                                                @if ($errors->has('meta_description'))
                                                    <span class="text-danger"><i class="fas fa-exclamation-triangle"></i> {{ $errors->first('meta_description') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12 mb-30">
                                                <label class="font-medium font-15 color-heading">{{__('Meta Keywords')}}</label>
                                                <input type="text" name="meta_keywords" value="{{$course->meta_keywords}}" class="form-control" placeholder="{{ __('Type meta keywords (comma separated)') }}">
                                                @if ($errors->has('meta_keywords'))
                                                    <span class="text-danger"><i class="fas fa-exclamation-triangle"></i> {{ $errors->first('meta_keywords') }}</span>
                                                @endif

                                            </div>
                                        </div>
                                        
                                        <div class="row">
                                            <div class="col-md-12 mb-30">
                                                <div class="input__group mb-25">
                                                    <label>{{ __('OG Image') }}</label>
                                                    <div class="upload-img-box">
                                                        @if($course->og_image != NULL && $course->og_image != '')
                                                            <img src="{{getImageFile($course->og_image)}}">
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
                                        </div>
                                    </div>

                                    <div class="stepper-action-btns">
                                        <a href="{{route('instructor.course')}}"
                                            class="theme-btn theme-button3">{{__('Back')}}</a>
                                        <button type="submit"
                                            class="theme-btn default-hover-btn theme-button1">{{__('Save and
                                            continue')}}</button>
                                    </div>
                                </form>
                            </div>
                            <!-- Upload Course Overview-1 end -->

                        </div>

                        <!-- Upload Course Step-1 Item End -->

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('style')
<link rel="stylesheet" href="{{asset('common/css/select2.css')}}">
@endpush

@push('script')
<script src="{{asset('common/js/select2.min.js')}}"></script>
<script src="{{asset('frontend/assets/js/custom/upload-course.js')}}"></script>
<script src="{{ asset('common/js/jquery.repeater.min.js') }}"></script>
<script src="{{ asset('common/js/add-repeater.js') }}"></script>
<script src="{{asset('admin/js/custom/image-preview.js')}}"></script>
@endpush

@push('style')
    <link rel="stylesheet" href="{{asset('admin/css/custom/image-preview.css')}}">
@endpush
