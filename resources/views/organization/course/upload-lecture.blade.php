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
                            <li class="active" id="personal"><strong>{{ __('Upload Video') }}</strong></li>
                            <li id="instructor"><strong>{{ __('Instructors') }}</strong></li>
                            <li id="confirm"><strong>{{ __('Submit Process') }}</strong></li>
                        </ul>

                        <!-- Upload Course Step-1 Item Start -->
                        <div class="upload-course-step-item upload-course-overview-step-item">
                            <!-- Upload Course Step-2 Item Start -->
                            <div class="upload-course-step-item upload-course-video-step-item">
                                <form method="POST" action="{{route('organization.store.lecture', [$course->uuid, $lesson->uuid])}}"
                                    class="row g-3 needs-validation" novalidate enctype="multipart/form-data">
                                    @csrf
                                    <!-- Upload Course Video-4 start -->
                                    <div id="upload-course-video-4">
                                        <div class="upload-course-item-block course-overview-step1 radius-8">

                                            <div class="row mb-30">
                                                <div class="col-md-12">
                                                    <div class="d-flex">
                                                        <div
                                                            class="label-text-title color-heading font-medium font-16 mb-3 mr-15">
                                                            {{ __('Type') }}: </div>
                                                        <div>
                                                            <label class="mr-15"><input type="radio" name="type" value="video" checked class="lecture-type"> {{ __('Upload Video') }}</label>
                                                            <label class="mr-15"><input type="radio" name="type" value="youtube" class="lecture-type" id="lectureTypeYoutube"> {{ __('Youtube') }} </label>
                                                            @if(env('VIMEO_STATUS') == 'active')
                                                            <label class="mr-15"><input type="radio" name="type" value="vimeo" class="lecture-type">  {{ __('Vimeo') }}</label>
                                                            @endif
                                                            <label class="mr-15"><input type="radio" name="type" value="text" class="lecture-type" id="lectureTypeText"> {{ __('Text') }} </label>
                                                            <label class="mr-15"><input type="radio" name="type" value="image" class="lecture-type" id="lectureTypeImage"> {{ __('Image') }} </label>
                                                            <label class="mr-15"><input type="radio" name="type" value="pdf" class="lecture-type" id="lectureTypePDF"> {{ __('PDF') }} </label>
                                                            <label class="mr-15"><input type="radio" name="type" value="slide_document" class="lecture-type" id="lectureTypePowerpoint"> {{ __('Slide Document') }} </label>
                                                            <label class="mr-15"><input type="radio" name="type" value="audio" class="lecture-type" id="lectureTypeAudio"> {{ __('Audio') }} </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div id="video">
                                                <label
                                                    class="label-text-title color-heading font-medium font-16 mb-3">{{
                                                    __('Upload Video') }}<span class="text-danger">*</span></label>
                                                <div
                                                    class="upload-course-video-4-wrap upload-introduction-box-content-left d-flex align-items-center flex-column">
                                                    <div class="upload-introduction-box-content-img mb-3">
                                                        <img src="{{asset('frontend/assets/img/instructor-img/upload-lesson-icon.png')}}"
                                                            alt="upload">
                                                    </div>
                                                    <input type="hidden" id="file_duration" name="file_duration">
                                                    <input type="file" name="video_file" accept="video/mp4" class="form-control" value="{{ old('video_file') }}" id="video_file" title="{{ __('Upload lesson') }}" required>
                                                </div>

                                                <p class="font-14 color-gray text-center mt-3 pb-30">No file selected
                                                    (MP4 or WMV)</p>
                                            </div>

                                            <div id="youtube" class="d-none">
                                                <label class="label-text-title color-heading font-medium font-16 mb-3">{{ __('Lesson Youtube Video ID') }} <span class="text-danger">*</span></label>
                                                <input type="text" name="youtube_url_path" class="form-control youtube-url" value="{{ old('youtube_url_path') }}" id="youtube_url_path" placeholder="{{ __('Type Your Youtube Video ID') }}">
                                            </div>

                                            <div id="vimeo" class="d-none">
                                                <div class="row mb-30">
                                                    <div class="col-md-12">
                                                        <label
                                                            class="label-text-title color-heading font-medium font-16 mb-3">{{
                                                            __('Vimeo Upload Type') }} <span
                                                                class="text-danger">*</span></label>
                                                        <select name="vimeo_upload_type"
                                                            class="form-select vimeo_upload_type">
                                                            <option value="">--{{ __('Select Option') }}--</option>
                                                            <option value="1" @if(old('vimeo_upload_type')==1) selected
                                                                @endif>{{ __('Video File Upload') }}</option>
                                                            <option value="2" @if(old('vimeo_upload_type')==2) selected
                                                                @endif>{{ __('Vimeo Uploaded Video ID') }}</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="vimeo_Video_file_upload_div d-none">
                                                    <label
                                                        class="label-text-title color-heading font-medium font-16 mb-3">{{
                                                        __('Upload Video') }}<span class="text-danger">*</span></label>
                                                    <div
                                                        class="upload-course-video-4-wrap upload-introduction-box-content-left d-flex align-items-center flex-column">
                                                        <div class="upload-introduction-box-content-img mb-3">
                                                            <img src="{{asset('frontend/assets/img/instructor-img/upload-lesson-icon.png')}}"
                                                                alt="upload">
                                                        </div>
                                                        <input type="file" name="vimeo_url_path" accept="video/mp4"
                                                            class="form-control" value="{{ old('vimeo_url_path') }}"
                                                            id="vimeo_url_path" title="Upload lesson">
                                                    </div>
                                                    @if ($errors->has('vimeo_url_path'))
                                                    <span class="text-danger"><i
                                                            class="fas fa-exclamation-triangle"></i> {{
                                                        $errors->first('vimeo_url_path') }}</span>
                                                    @endif
                                                </div>
                                                <div class="vimeo_uploaded_Video_id_div d-none">
                                                    <div class="row mb-30">
                                                        <div class="col-md-12">
                                                            <label class="label-text-title color-heading font-medium font-16 mb-3">{{ __('Uploaded Video ID') }}<span class="text-danger">*</span></label>
                                                            <div class="upload-course-video-4-wrap upload-introduction-box-content-left d-flex align-items-center flex-column">
                                                                <input type="text" name="vimeo_url_uploaded_path" placeholder="{{ __('Type your uploaded video ID (ex: 123654)') }}" class="form-control" value="{{ old('vimeo_url_uploaded_path') }}" id="vimeo_url_uploaded_path">
                                                            </div>
                                                        </div>
                                                        @if ($errors->has('vimeo_url_uploaded_path'))
                                                        <span class="text-danger"><i
                                                                class="fas fa-exclamation-triangle"></i> {{
                                                            $errors->first('vimeo_url_uploaded_path') }}</span>
                                                        @endif
                                                    </div>
                                                    <div class="row mb-30">
                                                        <div class="col-md-12">
                                                            <label class="label-text-title color-heading font-medium font-16 mb-3">{{ __('Lesson File Duration') }} (00:00) <span class="text-danger">*</span></label>
                                                            <input type="text" name="vimeo_file_duration" value="{{old('vimeo_file_duration')}}" class="form-control customVimeoFileDuration" placeholder="{{ __('Type file duration') }}" >
                                                            @if ($errors->has('vimeo_file_duration'))
                                                            <span class="text-danger"><i
                                                                    class="fas fa-exclamation-triangle"></i> {{
                                                                $errors->first('vimeo_file_duration') }}</span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div id="text" class="d-none">
                                                <label
                                                    class="label-text-title color-heading font-medium font-16 mb-3">{{
                                                    __('Lesson Description') }} <span
                                                        class="text-danger">*</span></label>
                                                <textarea name="text_description" id="summernote"
                                                    class="textDescription" cols="30" rows="10">{{
                                                    old('text_description') }}</textarea>
                                            </div>
                                            <div id="imageDiv" class="d-none">
                                                <div class="row align-items-center">
                                                    <div class="col-12">
                                                        <label
                                                            class="label-text-title color-heading font-medium font-16 mb-3">{{
                                                            __('Lesson Image') }} <span
                                                                class="text-danger">*</span></label>
                                                    </div>
                                                    <div class="col-md-6 mb-30">
                                                        <div class="upload-img-box mt-3 height-200">
                                                            <img src="">
                                                            <input type="file" name="image" id="image" accept="image/*"
                                                                onchange="previewFile(this)">
                                                            <div class="upload-img-box-icon">
                                                                <i class="fa fa-camera"></i>
                                                                <p class="m-0">{{__('Image')}}</p>
                                                            </div>
                                                        </div>
                                                        @if ($errors->has('image'))
                                                        <span class="text-danger"><i
                                                                class="fas fa-exclamation-triangle"></i> {{
                                                            $errors->first('image') }}</span>
                                                        @endif
                                                    </div>
                                                    <div class="col-md-6 mb-30">
                                                        <p class="font-14 color-gray">{{ __('Preferable image size:') }} (1MB)</p>
                                                        <p class="font-14 color-gray">{{ __('Preferable filetype:') }} jpg, jpeg, png</p>
                                                    </div>
                                                </div>
                                            </div>

                                            <div id="pdfDiv" class="d-none">
                                                <label
                                                    class="label-text-title color-heading font-medium font-16 mb-3">{{
                                                    __('Upload PDF') }} <span class="text-danger">*</span></label>
                                                <div
                                                    class="upload-course-video-4-wrap upload-introduction-box-content-left d-flex align-items-center flex-column">
                                                    <div class="upload-introduction-box-content-img mb-3">
                                                        <img src="{{asset('frontend/assets/img/instructor-img/upload-lesson-icon.png')}}"
                                                            alt="upload">
                                                    </div>
                                                    <input type="file" name="pdf" accept="application/pdf"
                                                        class="form-control" value="{{ old('pdf') }}" id="pdf"
                                                        title="Upload lesson pdf">
                                                </div>
                                            </div>
                                            <div id="slide_documentDiv" class="d-none">
                                                <label
                                                    class="label-text-title color-heading font-medium font-16 mb-3">{{
                                                    __('Write your Slide Embed Code') }}<span
                                                        class="text-danger">*</span></label>
                                                <div
                                                    class="upload-course-video-4-wrap upload-introduction-box-content-left d-flex align-items-center flex-column">
                                                    <input type="text" name="slide_document" class="form-control"
                                                        value="{{ old('slide_document') }}" id="slide_document"
                                                        title="Upload lesson slide document">
                                                </div>
                                            </div>
                                            <div id="audioDiv" class="d-none">
                                                <label
                                                    class="label-text-title color-heading font-medium font-16 mb-3">{{
                                                    __('Upload Audio') }} <span class="text-danger">*</span></label>
                                                <div
                                                    class="upload-course-video-4-wrap upload-introduction-box-content-left d-flex align-items-center flex-column">
                                                    <div class="upload-introduction-box-content-img mb-3">
                                                        <img src="{{asset('frontend/assets/img/instructor-img/upload-lesson-icon.png')}}"
                                                            alt="upload">
                                                    </div>
                                                    <input type="file" name="audio" class="form-control"
                                                        value="{{ old('audio') }}" id="audio"
                                                        title="Upload lesson audio">
                                                </div>
                                            </div>
                                            <div>
                                                @if ($errors->has('video_file'))
                                                <span class="text-danger"><i class="fas fa-exclamation-triangle"></i> {{
                                                    $errors->first('video_file') }}</span>
                                                @endif
                                                @if ($errors->has('youtube_url'))
                                                <span class="text-danger"><i class="fas fa-exclamation-triangle"></i> {{
                                                    $errors->first('youtube_url') }}</span>
                                                @endif
                                                @if ($errors->has('vimeo_url'))
                                                <span class="text-danger"><i class="fas fa-exclamation-triangle"></i> {{
                                                    $errors->first('vimeo_url') }}</span>
                                                @endif
                                                @if ($errors->has('text_description'))
                                                <span class="text-danger"><i class="fas fa-exclamation-triangle"></i> {{
                                                    $errors->first('text_description') }}</span>
                                                @endif
                                                @if ($errors->has('image'))
                                                <span class="text-danger"><i class="fas fa-exclamation-triangle"></i> {{
                                                    $errors->first('image') }}</span>
                                                @endif
                                                @if ($errors->has('pdf'))
                                                <span class="text-danger"><i class="fas fa-exclamation-triangle"></i> {{
                                                    $errors->first('pdf') }}</span>
                                                @endif
                                                @if ($errors->has('slide_document'))
                                                <span class="text-danger"><i class="fas fa-exclamation-triangle"></i> {{
                                                    $errors->first('slide_document') }}</span>
                                                @endif
                                                @if ($errors->has('audio'))
                                                <span class="text-danger"><i class="fas fa-exclamation-triangle"></i> {{
                                                    $errors->first('audio') }}</span>
                                                @endif
                                            </div>

                                            <div class="main-upload-video-processing-box">
                                                <div class="d-flex main-upload-video-processing-item">
                                                    <div class="flex-grow-1 ms-3">
                                                        <div class="row mb-30">
                                                            <div class="col-md-12">
                                                                <label class="label-text-title color-heading font-medium font-16 mb-3">{{ __('Lesson Title') }} <span class="text-danger">*</span></label>
                                                                <input type="text" name="title" value="{{old('title')}}" class="form-control" placeholder="{{ __('First steps') }}" required>
                                                            </div>
                                                        </div>
                                                        <div class="row mb-30">
                                                            <div class="col-md-12">
                                                                <label
                                                                    class="label-text-title color-heading font-medium font-16 mb-3">{{
                                                                    __('Learner\'s Visibility') }} <span
                                                                        class="text-danger">*</span></label>
                                                                <select name="lecture_type" class="form-select"
                                                                    required>
                                                                    <option value="">--Select Option--</option>
                                                                    <option value="1" @if(old('lecture_type')==1)
                                                                        selected @endif>{{ __('Show') }}</option>
                                                                    <option value="2" @if(old('lecture_type')==2)
                                                                        selected @endif>{{ __('Lock') }}</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="row mb-30 d-none" id="fileDuration">
                                                            <div class="col-md-12">
                                                                <label class="label-text-title color-heading font-medium font-16 mb-3">{{ __('Lesson File Duration') }} (00:00) <span class="text-danger">*</span></label>
                                                                <input type="text" name="youtube_file_duration" value="{{old('file_duration')}}" class="form-control customFileDuration" placeholder="{{ __('First file duration') }}" >
                                                                @if ($errors->has('youtube_file_duration'))
                                                                <span class="text-danger"><i
                                                                        class="fas fa-exclamation-triangle"></i> {{
                                                                    $errors->first('youtube_file_duration') }}</span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        @if($course->drip_content == DRIP_AFTER_DAY)
                                                        <div class="row mb-30" id="drip-day">
                                                            <div class="col-md-12">
                                                                <label
                                                                    class="label-text-title color-heading font-medium font-16 mb-3">{{
                                                                    __('Lesson available after x days') }} <span class="text-danger">*</span></label>
                                                                <input type="number" min=1 required name="after_day"
                                                                    value="{{old('unlock_date', 0)}}"
                                                                    class="form-control" placeholder="Days">
                                                                @if ($errors->has('after_day'))
                                                                <span class="text-danger"><i
                                                                        class="fas fa-exclamation-triangle"></i> {{
                                                                    $errors->first('after_day') }}</span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        @elseif($course->drip_content == DRIP_UNLOCK_DATE)
                                                        <div class="row mb-30" id="drip-date">
                                                            <div class="col-md-12">
                                                                <div class="input__group text-black">
                                                                    <label
                                                                        class="label-text-title color-heading font-medium font-16 mb-3">{{
                                                                        __('Lesson available by date') }} <span class="text-danger">*</span></label>
                                                                    <input type="date" name="unlock_date"
                                                                        value="{{old('unlock_date')}}"
                                                                        class="form-control" required>
                                                                    @if ($errors->has('unlock_date'))
                                                                    <span class="text-danger"><i
                                                                            class="fas fa-exclamation-triangle"></i> {{
                                                                        $errors->first('unlock_date') }}</span>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                        @elseif($course->drip_content == DRIP_PRE_IDS)
                                                        <div class="row mb-30" id="drip-pre-requisite">
                                                            <div class="col-md-12">
                                                                <label
                                                                    class="label-text-title color-heading font-medium font-16 mb-3">{{
                                                                    __('Pre-requisites lesson') }}
                                                                    <span class="text-danger">*</span>
                                                                </label>
                                                                <select name="pre_ids[]" required class="select2 form-control" multiple>
                                                                    @foreach ($lessons as $pr_lesson)
                                                                    <optgroup label="{{ $pr_lesson->name }}">
                                                                        @foreach ($pr_lesson->lectures as $pr_lecture)
                                                                        <option value="{{ $pr_lecture->id }}">{{ $pr_lecture->title }}</option>
                                                                        @endforeach
                                                                      </optgroup>
                                                                    @endforeach
                                                                </select>

                                                                @if ($errors->has('pre_ids'))
                                                                <span class="text-danger"><i
                                                                        class="fas fa-exclamation-triangle"></i> {{
                                                                    $errors->first('pre_ids') }}</span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        @endif
                                                        <div class="row mb-30">
                                                            <div
                                                                class="col-md-12 main-upload-video-processing-item-btns">
                                                                <button type="submit"
                                                                    class="theme-btn upload-video-processing-item-save-btn">{{__('Save')}}</button>
                                                                <a href="{{route('organization.course.edit', [$course->uuid, 'step=lesson'])}}"
                                                                    class="theme-btn default-hover-btn default-back-btn theme-button3">{{__('Back')}}</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                    <!-- Upload Course Video-4 end -->
                                </form>
                            </div>
                            <!-- Upload Course Step-6 Item End -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<input type="hidden" value="{{ old('type') }}" class="oldTypeYoutube">
@endsection

@push('style')
<link rel="stylesheet" href="{{asset('frontend/assets/css/custom/img-view.css')}}">

<link rel="stylesheet" href="{{asset('common/css/select2.css')}}">
<!-- Summernote CSS - CDN Link -->
<link href="{{ asset('common/css/summernote/summernote.min.css') }}" rel="stylesheet">
<link href="{{ asset('common/css/summernote/summernote-lite.min.css') }}" rel="stylesheet">
<!-- //Summernote CSS - CDN Link -->
@endpush

@push('script')

<script src="{{asset('common/js/select2.min.js')}}"></script>
<script src="{{asset('frontend/assets/js/custom/form-validation.js')}}"></script>
<script src="{{asset('frontend/assets/js/custom/img-view.js')}}"></script>
<script src="{{asset('frontend/assets/js/custom/upload-lesson.js')}}"></script>

<!-- Summernote JS - CDN Link -->
<script src="{{ asset('common/js/summernote/summernote-lite.min.js') }}"></script>
<script>
    $(document).ready(function() {
        $('.select2').select2();
        $("#summernote").summernote({dialogsInBody: true});
        $('.dropdown-toggle').dropdown();
    });
</script>
<!-- //Summernote JS - CDN Link -->
@endpush