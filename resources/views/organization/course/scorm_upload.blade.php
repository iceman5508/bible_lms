@extends('layouts.organization')

@section('breadcrumb')
<div class="page-banner-content text-center">
    <h3 class="page-banner-heading text-white pb-15"> {{__('Upload Course')}} </h3>

    <!-- Breadcrumb Start-->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb justify-content-center">
            <li class="breadcrumb-item font-14"><a href="{{route('organization.dashboard')}}">{{__('Dashboard')}}</a></li>
            <li class="breadcrumb-item font-14"><a href="{{ route('organization.course.index') }}">{{__('My Courses')}}</a></li>
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
                            <li class="active" id="personal"><strong>{{ __('Upload SCORM File') }}</strong></li>
                            <li id="organization"><strong>{{ __('Instructor') }}</strong></li>
                            <li id="confirm"><strong>{{ __('Submit Process') }}</strong></li>
                        </ul>

                        <!-- Upload Course Step-1 Item Start -->
                        <div class="upload-course-step-item upload-course-overview-step-item">
                            <form method="POST" action="{{route('organization.scorm.store', [$course->uuid])}}"
                                class="row g-3 needs-validation" novalidate enctype="multipart/form-data">
                                @csrf
                                <!-- Upload Course Step-2 Item Start -->
                                <div class="upload-course-step-item upload-course-video-step-item">
                                    @if(isset($course->scorm_course) && request()->get('scorm-upload') != 1)
                                    <div class="row mb-30 mt-25" id="fileDuration">
                                        <div class="col-md-12">
                                            <label class="label-text-title color-heading font-medium font-16 mb-3">{{
                                                __('Scorm File Duration') }} (00:00:00) <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" name="duration"
                                                value="{{ $course->scorm_course->duration }}"
                                                class="form-control customFileDuration"
                                                placeholder="00:00:00" required="required">
                                            @if ($errors->has('duration'))
                                            <span class="text-danger"><i class="fas fa-exclamation-triangle"></i> {{
                                                $errors->first('duration') }}</span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12 text-center">
                                            <a target="_blank"
                                                href="{{route('student.my-course.show', [$course->slug])}}"
                                                class="see-preview-video font-medium font-16">{{
                                                    __('Preview') }}</a>
                                            <a href="{{route('organization.course.edit', [$course->uuid, 'step=lesson', 'scorm-upload=1'])}}"
                                                class="common-upload-video-btn color-heading font-13 font-medium ms-0 mt-4"><span
                                                    class="iconify" data-icon="feather:upload-cloud"></span>{{
                                                __('Upload SCORM') }}</a>
                                        </div>
                                    </div>
                                    @else
                                    <input type="hidden" name="scorm_upload" value="1">
                                    <div class="row mb-30 mt-25" id="fileDuration">
                                        <div class="col-md-12">
                                            <label class="label-text-title color-heading font-medium font-16 mb-3">{{
                                                __('Scorm File Duration') }} (00:00:00) <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" name="duration" value=""
                                                class="form-control customFileDuration"
                                                placeholder="00:00:00" required="required">
                                            @if ($errors->has('duration'))
                                            <span class="text-danger"><i class="fas fa-exclamation-triangle"></i> {{
                                                $errors->first('duration') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label class="label-text-title color-heading font-medium font-16 mb-3">{{
                                                __('Upload
                                                SCORM file') }}<span class="text-danger">*</span></label>
                                            <div
                                                class="upload-course-video-4-wrap upload-introduction-box-content-left d-flex align-items-center flex-column">
                                                <div class="upload-introduction-box-content-img mb-3">
                                                    <img src="{{asset('frontend/assets/img/instructor-img/upload-zip-icon.png')}}"
                                                        alt="upload">
                                                </div>
                                                <input type="file" name="scorm_file"
                                                    accept="zip,application/octet-stream,application/zip,application/x-zip,application/x-zip-compressed"
                                                    class="form-control" value="{{ old('scorm_file') }}" id="scorm_file"
                                                    title="Upload SCORM" required>
                                            </div>

                                            <p class="font-14 color-gray text-center mt-3 pb-30">{{ __('No file
                                                selected') }} (zip)
                                            </p>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                                <!-- Upload Course Step-6 Item End -->

                                <div class="stepper-action-btns">
                                    @if(request()->get('scorm-upload') == 1)
                                    <a href="{{route('organization.course.edit', [$course->uuid, 'step=lesson'])}}"
                                        class="theme-btn theme-button3">{{__('Back')}}</a>
                                    @else
                                    <a href="{{route('organization.course.edit', [$course->uuid, 'step=category'])}}"
                                        class="theme-btn theme-button3">{{__('Back')}}</a>
                                    @endif
                                    <button type="submit" class="theme-btn default-hover-btn theme-button1">{{__('Save
                                        and
                                        continue')}}</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


@push('script')
>
<script src="{{asset('frontend/assets/js/custom/form-validation.js')}}"></script>
<script src="{{asset('frontend/assets/js/custom/upload-lesson.js')}}"></script>
<script src="{{asset('frontend/assets/js/custom/index.js')}}"></script>

@endpush
