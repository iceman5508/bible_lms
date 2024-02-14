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
                            <h2>{{ __('Course Lessons') }}</h2>
                        </div>
                    </div>
                    <div class="breadcrumb__content__right">
                        <nav aria-label="breadcrumb">
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a
                                        href="{{route('admin.dashboard')}}">{{__('Dashboard')}}</a></li>
                                <li class="breadcrumb-item active" aria-current="page">{{__('Course Lessons')}}</li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
        @if($course->course_type == COURSE_TYPE_GENERAL || $course->course_type == COURSE_TYPE_LIVE_CLASS)
        <div class="row">
            <div class="col-md-12">
                <div class="customers__area bg-style mb-30">
                    <div class="item-title d-flex justify-content-between">
                        <h2>{{ __('Course Lessons and Lectures') }}</h2>
                    </div>

                    <!-- View Curriculum Start -->
                    <div class="admin-course-watch-page-area">
                        <div class="curriculum-content">
                            <div class="accordion" id="accordionExample">
                                @forelse(@$course->lessons as $key => $lesson)
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="heading{{ $key }}">
                                        <button
                                            class="accordion-button font-medium font-18 {{ $key == 0 ? '' : 'collapsed' }}"
                                            type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $key }}"
                                            aria-expanded="{{ $key == 0 ? 'true' : 'false' }}"
                                            aria-controls="collapseOne">
                                            {{ $lesson->name }}
                                        </button>
                                    </h2>
                                    <div id="collapse{{ $key }}"
                                        class="accordion-collapse collapse {{ $key == 0 ? 'show' : '' }}"
                                        aria-labelledby="heading{{ $key }}" data-bs-parent="#accordionExample">
                                        <div class="accordion-body">
                                            <div class="play-list">

                                                <!-- Note End-->
                                                <!-- If User Logged In then add Class Name in play-list-item = "venobox"-->
                                                <!-- If Preview has for this course add Class Name in play-list-item = "preview-enabled"-->
                                                <!-- Note Start-->
                                                @if($course->course_type == COURSE_TYPE_GENERAL)
                                                    @forelse($lesson->lectures as $lecture)
                                                    @if($lecture->type == 'video')
                                                    <a title="See video preview"
                                                        class="edit-lecture-preview-show d-flex align-items-center justify-content-between color-heading font-medium font-16 mb-3"
                                                        data-bs-toggle="modal"
                                                        href="#html5VideoPlayerModal{{ $lecture->id }}">
                                                        <div class="d-flex flex-grow-1">
                                                            <div><img
                                                                    src="{{ asset('frontend/assets/img/courses-img/play.svg') }}"
                                                                    alt="play"></div>
                                                            <div class="font-medium font-16 lecture-edit-title">
                                                                {{$lecture->title}}</div>
                                                        </div>

                                                        <div class="upload-course-video-6-text flex-shrink-0">
                                                            <span class="see-preview-video font-medium font-16">Preview
                                                            </span>
                                                            <span class="video-time-count">{{ $lecture->file_duration
                                                                }}</span>
                                                        </div>
                                                    </a>

                                                    <!-- HTML5 Video Player Modal Start-->
                                                    <div class="modal fade VideoTypeModal"
                                                        id="html5VideoPlayerModal{{ $lecture->id }}" tabindex="-1"
                                                        aria-hidden="true">

                                                        <div class="modal-header border-bottom-0">
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                                aria-label="Close"><span class="iconify"
                                                                    data-icon="akar-icons:cross"></span></button>
                                                        </div>
                                                        <div class="modal-dialog modal-dialog-centered">
                                                            <div class="modal-content">
                                                                <div class="modal-body">
                                                                    <div class="video-player-area">
                                                                        <!-- HTML 5 Video -->
                                                                        <video class="js-player" id="playerVideoHTML5"
                                                                            playsinline controls controlsList="nodownload">
                                                                            <source
                                                                                src="{{getVideoFile($lecture->file_path)}}"
                                                                                type="video/mp4" />
                                                                        </video>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    @elseif($lecture->type == 'youtube')
                                                    <a class="edit-lecture-preview-show d-flex align-items-center justify-content-between color-heading font-medium font-16 mb-3"
                                                        data-bs-toggle="modal"
                                                        href="#newVideoPlayerModal{{ $lecture->id }}">
                                                        <div class="d-flex flex-grow-1">
                                                            <div><img
                                                                    src="{{ asset('frontend/assets/img/courses-img/play.svg') }}"
                                                                    alt="play"></div>
                                                            <div class="font-medium font-16 lecture-edit-title">
                                                                {{$lecture->title}}</div>
                                                        </div>

                                                        <div class="upload-course-video-6-text flex-shrink-0">
                                                            <span
                                                                class="see-preview-video font-medium font-16">Preview</span>
                                                            <span class="video-time-count">{{ $lecture->file_duration
                                                                }}</span>
                                                        </div>
                                                    </a>

                                                    <!-- Youtube Video Player Modal Start-->
                                                    <div class="modal fade VideoTypeModal"
                                                        id="newVideoPlayerModal{{ $lecture->id }}" tabindex="-1"
                                                        aria-hidden="true">

                                                        <div class="modal-header border-bottom-0">
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                                aria-label="Close"><span class="iconify"
                                                                    data-icon="akar-icons:cross"></span></button>
                                                        </div>
                                                        <div class="modal-dialog modal-dialog-centered">
                                                            <div class="modal-content">
                                                                <div class="modal-body">
                                                                    <div class="video-player-area">
                                                                        <!-- Youtube Video -->
                                                                        <div class="plyr__video-embed js-player"
                                                                            id="playerVideoYoutube">
                                                                            <iframe
                                                                                src="https://www.youtube.com/embed/{{ $lecture->url_path }}"
                                                                                allowfullscreen allowtransparency
                                                                                allow="autoplay"></iframe>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    @elseif($lecture->type == 'vimeo')
                                                    <a class="edit-lecture-preview-show d-flex align-items-center justify-content-between color-heading font-medium font-16 mb-3"
                                                        data-bs-toggle="modal"
                                                        href="#vimeoVideoPlayerModal{{ $lecture->id }}">
                                                        <div class="d-flex flex-grow-1">
                                                            <div><img
                                                                    src="{{ asset('frontend/assets/img/courses-img/play.svg') }}"
                                                                    alt="play"></div>
                                                            <div class="font-medium font-16 lecture-edit-title">
                                                                {{$lecture->title}}</div>
                                                        </div>

                                                        <div class="upload-course-video-6-text flex-shrink-0">
                                                            <span class="see-preview-video font-medium font-16">Preview
                                                                Video</span>
                                                        </div>
                                                    </a>

                                                    <!-- Vimeo Video Player Modal Start-->
                                                    <div class="modal fade VideoTypeModal"
                                                        id="vimeoVideoPlayerModal{{ $lecture->id }}" tabindex="-1"
                                                        aria-hidden="true">

                                                        <div class="modal-header border-bottom-0">
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                                aria-label="Close"><span class="iconify"
                                                                    data-icon="akar-icons:cross"></span></button>
                                                        </div>
                                                        <div class="modal-dialog modal-dialog-centered">
                                                            <div class="modal-content">
                                                                <div class="modal-body">
                                                                    <div class="video-player-area">
                                                                        <!-- Vimeo Video -->
                                                                        <div class="plyr__video-embed"
                                                                            id="playerVideoVimeo">
                                                                            <iframe
                                                                                src="https://player.vimeo.com/video/{{ $lecture->url_path }}?loop=false&amp;byline=false&amp;portrait=false&amp;title=false&amp;speed=true&amp;transparent=0&amp;gesture=media"
                                                                                allowfullscreen allowtransparency
                                                                                allow="autoplay"></iframe>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- Vimeo Video Player Modal End-->
                                                    @elseif($lecture->type == 'text')
                                                    <a type="button" data-lecture_text="{{ $lecture->text }}"
                                                        class="lectureText edit-lecture-preview-show d-flex align-items-center justify-content-between color-heading font-medium font-16 mb-3"
                                                        data-bs-toggle="modal" href="#textUploadModal">
                                                        <div class="d-flex flex-grow-1">
                                                            <div><img
                                                                    src="{{ asset('frontend/assets/img/courses-img/text.svg') }}"
                                                                    alt="text"></div>
                                                            <div class="font-medium font-16 lecture-edit-title">
                                                                {{$lecture->title}}</div>
                                                        </div>

                                                        <div class="upload-course-video-6-text flex-shrink-0">
                                                            <span
                                                                class="see-preview-video font-medium font-16">Preview</span>
                                                            <span class="video-time-count">{{ $lecture->file_duration
                                                                }}</span>
                                                        </div>
                                                    </a>
                                                    @elseif($lecture->type == 'image')
                                                    <a data-lecture_image="{{ getImageFile($lecture->image) }}"
                                                        class="lectureImage edit-lecture-preview-show d-flex align-items-center justify-content-between color-heading font-medium font-16 mb-3"
                                                        data-bs-toggle="modal" href="#imageUploadModal">
                                                        <div class="d-flex flex-grow-1">
                                                            <div><img
                                                                    src="{{ asset('frontend/assets/img/courses-img/preview-image.svg') }}"
                                                                    alt="image"></div>
                                                            <div class="font-medium font-16 lecture-edit-title">
                                                                {{$lecture->title}}</div>
                                                        </div>

                                                        <div class="upload-course-video-6-text flex-shrink-0">
                                                            <span class="see-preview-video font-medium font-16">Preview
                                                                Image </span>
                                                        </div>
                                                    </a>
                                                    @elseif($lecture->type == 'pdf')
                                                    <a class="edit-lecture-preview-show d-flex align-items-center justify-content-between color-heading font-medium font-16 mb-3"
                                                        data-maxwidth="800px" target="_blank"
                                                        href="{{ getImageFile($lecture->pdf) }}">
                                                        <div class="d-flex flex-grow-1">
                                                            <div><img
                                                                    src="{{ asset('frontend/assets/img/courses-img/file-pdf.svg') }}"
                                                                    alt="PDF"></div>
                                                            <div class="font-medium font-16 lecture-edit-title">
                                                                {{$lecture->title}}</div>
                                                        </div>

                                                        <div class="upload-course-video-6-text flex-shrink-0">
                                                            <span class="see-preview-video font-medium font-16">Preview
                                                                PDF</span>
                                                        </div>
                                                    </a>
                                                    @elseif($lecture->type == 'slide_document')
                                                    <a data-lecture_slide="{{ $lecture->slide_document }}"
                                                        class="lectureSlide edit-lecture-preview-show d-flex align-items-center justify-content-between color-heading font-medium font-16 mb-3"
                                                        data-bs-toggle="modal" href="#slideModal">
                                                        <div class="d-flex flex-grow-1">
                                                            <div><img
                                                                    src="{{ asset('frontend/assets/img/courses-img/slide-preview.svg') }}"
                                                                    alt="Slide Doc"></div>
                                                            <div class="font-medium font-16 lecture-edit-title">
                                                                {{$lecture->title}}</div>
                                                        </div>

                                                        <div class="upload-course-video-6-text flex-shrink-0">
                                                            <span class="see-preview-video font-medium font-16">Preview
                                                                Slide</span>
                                                        </div>
                                                    </a>
                                                    @elseif($lecture->type == 'audio')
                                                    <a class="edit-lecture-preview-show d-flex align-items-center justify-content-between color-heading font-medium font-16 mb-3"
                                                        data-bs-toggle="modal" href="#audioPlayerModal{{ $lecture->id }}">
                                                        <div class="d-flex flex-grow-1">
                                                            <div>
                                                                <img src="{{ asset('frontend/assets/img/courses-img/preview-audio-o.svg') }}"
                                                                    alt="play">
                                                            </div>
                                                            <div class="font-medium font-16 lecture-edit-title">
                                                                {{$lecture->title}}</div>
                                                        </div>

                                                        <div class="upload-course-video-6-text flex-shrink-0">
                                                            <span
                                                                class="see-preview-video font-medium font-16">Preview</span>
                                                        </div>
                                                    </a>

                                                    <!-- Audio Player Modal Start-->
                                                    <div class="modal fade venoBoxTypeModal"
                                                        id="audioPlayerModal{{ $lecture->id }}" tabindex="-1"
                                                        aria-hidden="true">

                                                        <div class="modal-header border-bottom-0">
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                                aria-label="Close"><span class="iconify"
                                                                    data-icon="akar-icons:cross"></span></button>
                                                        </div>
                                                        <div class="modal-dialog modal-dialog-centered">
                                                            <div class="modal-content">
                                                                <div class="modal-body">
                                                                    <!--Audio -->
                                                                    <audio class="js-player" id="player" controls>
                                                                        <source src="{{ getVideoFile($lecture->audio) }}"
                                                                            type="audio/mp3" >
                                                                    </audio>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- Audio Player Modal End-->
                                                    @endif
                                                    @empty
                                                    <div class="row">
                                                        <p>{{ __('No Data Found') }}</p>
                                                    </div>
                                                    @endforelse
                                                @else
                                                    @forelse($lesson->live_class as $liveClass)
                                                        <a title="See video preview"
                                                            class="edit-lecture-preview-show d-flex align-items-center justify-content-between color-heading font-medium font-16 mb-3"
                                                            data-bs-toggle="modal"
                                                            href="#html5VideoPlayerModal{{ $liveClass->id }}">
                                                            <div class="d-flex flex-grow-1">
                                                                <div><img
                                                                        src="{{ asset('frontend/assets/img/courses-img/play.svg') }}"
                                                                        alt="play"></div>
                                                                <div class="font-medium font-16 lecture-edit-title">
                                                                    {{$liveClass->title}}</div>
                                                            </div>

                                                            <div class="upload-course-video-6-text flex-shrink-0">
                                                                <span class="see-preview-video font-medium font-16">Preview
                                                                </span>
                                                                <span class="video-time-count">{{ $liveClass->duration
                                                                    }}</span>
                                                            </div>
                                                        </a>

                                                        <!-- HTML5 Video Player Modal Start-->
                                                        <div class="modal fade VideoTypeModal"
                                                            id="html5VideoPlayerModal{{ $liveClass->id }}" tabindex="-1"
                                                            aria-hidden="true">

                                                            <div class="modal-header border-bottom-0">
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                                    aria-label="Close"><span class="iconify"
                                                                        data-icon="akar-icons:cross"></span></button>
                                                            </div>
                                                            <div class="modal-dialog modal-dialog-centered">
                                                                <div class="modal-content">
                                                                    <div class="modal-body">
                                                                        <div class="video-player-area">
                                                                            <!-- HTML 5 Video -->
                                                                            <video class="js-player" id="playerVideoHTML5"
                                                                                playsinline controls controlsList="nodownload">
                                                                                <source
                                                                                    src="{{getVideoFile($liveClass->path)}}"
                                                                                    type="video/mp4" />
                                                                            </video>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @empty
                                                    <div class="row">
                                                        <p>{{ __('No Data Found') }}</p>
                                                    </div>
                                                    @endforelse
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @empty
                                <div class="row">
                                    <p>{{ __('No Data Found') }}</p>
                                </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                    <!-- View Curriculam Start -->

                </div>
            </div>
        </div>
        @else
        <div class="row">
            <div class="col-md-12">
                <div class="customers__area bg-style mb-30">
                    <div class="item-title d-flex justify-content-between">
                        <h2>{{ __('SCORM Course') }}</h2>
                    </div>

                    <!-- View Curriculum Start -->
                    <div class="admin-course-watch-page-area">
                        <iframe id="scorm_player" class="scorm-content" frameBorder="0" src="{{ asset("scorm/".$course->scorm_course->uuid.'/'.$course->scorm_course->entry_url) }}"
                            width="100%" title="Scorm course"></iframe>
                    </div>
                    <!-- View Curriculam Start -->

                </div>
            </div>
        </div>
        @endif

        <div class="row">
            <div class="col-md-12">
                <div class="profile__timeline__area bg-style">
                    <div class="item-title">
                        <h2>{{__('Enrolled Courses')}}</h2>
                    </div>
                    <div class="profile__table">
                        <table class="table-style">
                            <thead>
                            <tr>
                                <th>{{__('Image')}}</th>
                                <th>{{__('Title')}}</th>
                                <th>{{__('Expired at')}}</th>
                                <th>{{__('Validity')}}</th>
                                <th>{{__('Status')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                                @forelse($students as $studentEnrollment)
                                <tr>
                                    <td>
                                        <a target="_blank" href="{{route('student.view', [$studentEnrollment->uuid])}}"><img src="{{ getImageFile($studentEnrollment->user->image_path) }}" alt="course" class="img-fluid" width="80"></a>
                                    </td>
                                    <td>
                                        <span class="data-text"><a target="_blank" href="{{route('student.view', [$studentEnrollment->uuid])}}">{{ $studentEnrollment->name }}</a></span>
                                    </td>
                                    <td class="font-15 color-heading">
                                        {{ $studentEnrollment->end_date }}
                                    </td>
                                    <td class="font-15 color-heading">
                                        {{ (checkIfExpired($studentEnrollment)) ? (checkIfLifetime($studentEnrollment->end_date) ? __('Lifetime') : \Carbon\Carbon::now()->diffForHumans( \Carbon\Carbon::parse($studentEnrollment->end_date), true).' '.__(' left') ) : __('Expired') }}
                                    </td>
                                    <td>
                                        <span id="hidden_id" style="display: none">{{$studentEnrollment->id}}</span>
                                        <select name="status" class="status label-inline font-weight-bolder mb-1 badge badge-info">
                                            <option value="{{ ACCESS_PERIOD_ACTIVE }}" @if($studentEnrollment->status == ACCESS_PERIOD_ACTIVE) selected @endif>{{ __('Active') }}</option>
                                            <option value="{{ ACCESS_PERIOD_DEACTIVATE }}" @if($studentEnrollment->status == ACCESS_PERIOD_DEACTIVATE) selected @endif>{{ __('Revoke') }}</option>
                                        </select>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center">{{ __("No Student Found") }}</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                        <div class="mt-3">
                            {{$students->links()}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Page content area end -->


<!--  Text Upload Modal Start -->
<div class="modal fade textUploadModal venoBoxTypeModal" id="textUploadModal" tabindex="-1" aria-hidden="true">
    <div class="modal-header border-bottom-0">
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><span class="iconify"
                data-icon="akar-icons:cross"></span></button>
    </div>
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-body">
                <p class="getLectureText"></p>
            </div>
        </div>
    </div>
</div>
<!-- Text Upload Modal End -->

<!-- Image Upload Modal Start -->
<div class="modal fade textUploadModal venoBoxTypeModal" id="imageUploadModal" tabindex="-1" aria-hidden="true">
    <div class="modal-header border-bottom-0">
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><span class="iconify"
                data-icon="akar-icons:cross"></span></button>
    </div>
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-body">
                <img src="" alt="" class="img-fluid getLectureImage">
            </div>
        </div>
    </div>
</div>
<!-- Image Upload Modal End -->

<!-- Slide Show Upload Modal Start-->
<div class="modal fade venoBoxTypeModal" id="slideModal" tabindex="-1" aria-hidden="true">
    <div class="modal-header border-bottom-0">
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><span class="iconify"
                data-icon="akar-icons:cross"></span></button>
    </div>
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <iframe class="getLectureSlide" src="" width="100%" height="400" frameborder="0"
                    scrolling="no"></iframe>
            </div>
        </div>
    </div>
</div>
<!-- Slide Show Upload Modal End-->

<!-- Audio Player Modal Start-->
<div class="modal fade venoBoxTypeModal" id="audioPlayerModal" tabindex="-1" aria-hidden="true">
    <div class="modal-header border-bottom-0">
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><span class="iconify"
                data-icon="akar-icons:cross"></span></button>
    </div>
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <!--Audio -->
                <audio class="getLectureAudio" src="" type="audio/mp3" style="width: 550px" controls
                    controlsList="nodownload">
                </audio>
            </div>
        </div>
    </div>
</div>
<!-- Audio Player Modal End-->
@endsection

@push('style')
<link rel="stylesheet" href="{{asset('frontend/assets/fonts/feather/feather.css')}}">

<!-- Video Player css -->
<link rel="stylesheet" href="{{ asset('frontend/assets/vendor/video-player/plyr.css') }}">
@endpush

@push('script')
<!--Feather Icon-->
<script src="{{asset('frontend/assets/js/feather.min.js')}}"></script>

<!-- Video Player js -->
<script src="{{ asset('frontend/assets/vendor/video-player/plyr.js') }}"></script>
<script>
    const players = Array.from(document.querySelectorAll('.js-player')).map((p) => new Plyr(p));
</script>
<!-- Video Player js -->

<script>
    "use strict"
    $('.lectureText').on('click', function () {
        var text = $(this).data("lecture_text")
        $('.getLectureText').html(text)
    })

    $('.lectureImage').on('click', function () {
        var image = $(this).data("lecture_image")
        $('.getLectureImage').attr('src', image)
    })

    $('.lectureSlide').on('click', function () {
        var slide = $(this).data("lecture_slide")
        $('.getLectureSlide').attr('src', slide)
    })
</script>

<script type="text/javascript">
    'use strict';
	//For Scorm course body
	$(document).ready(function(){
	  var width = $('#scorm_player').width();
	  $('#scorm_player').attr("height", width/2);
	  window.onresize = function(event) {
	    var width = $('#scorm_player').width();
	    $('#scorm_player').attr("height", width/2);
	  };
	});

    $(".status").change(function () {
        var id = $(this).closest('tr').find('#hidden_id').html();
        var status_value = $(this).closest('tr').find('.status option:selected').val();
        Swal.fire({
            title: "{{ __('Are you sure to change status?') }}",
            text: "{{ __('You won`t be able to revert this!') }}",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "{{__('Yes, Change it!')}}",
            cancelButtonText: "{{__('No, cancel!')}}",
            reverseButtons: true
        }).then(function (result) {
            if (result.value) {
                $.ajax({
                    type: "POST",
                    url: "{{route('admin.student.changeEnrollmentStatus')}}",
                    data: {"status": status_value, "id": id, "_token": "{{ csrf_token() }}",},
                    datatype: "json",
                    success: function (data) {
                        toastr.options.positionClass = 'toast-bottom-right';
                        toastr.success('', '{{ __("Enrollment status has been updated") }}');
                    },
                    error: function () {
                        alert("Error!");
                    },
                });
            } else if (result.dismiss === "cancel") {
            }
        });
    });
	//End for Scorm course body
</script>
@endpush
