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
                            <ul id="progressbar" class="upload-course-item-block d-flex align-items-center justify-content-center">
                                <li class="active" id="account"><strong>{{ __('Course Overview') }}</strong></li>
                                <li class="active" id="personal"><strong>{{ __('Upload Video') }}</strong></li>
                                <li id="instructor"><strong>{{ __('Instructor') }}</strong></li>
                                <li id="confirm"><strong>{{ __('Submit Process') }}</strong></li>
                            </ul>

                            <!-- Upload Course Step-1 Item Start -->
                            <div class="upload-course-step-item upload-course-overview-step-item">
                                <!-- Upload Course Step-2 Item Start -->
                                <div class="upload-course-step-item upload-course-video-step-item">


                                    @if($course->lessons->count() > 0)
                                        <!-- Upload Course Video-2 start -->

                                        <div id="upload-course-video-2">
                                            <div class="upload-course-item-block course-overview-step1 radius-8">
                                                <div class="upload-course-item-block-title mb-3">
                                                    <p class="color-para">Section list of <span class="color-heading">“{{$course->title}}”</span></p>
                                                </div>
                                                @if($course->lectures->count() > 0)
                                                    <div id="upload-course-video-6" class="upload-course-video-6">
                                                        <div class="accordion mb-30" id="video-upload-done-phase">
                                                            @foreach($course->lessons as $key => $lesson)
                                                                <div class="accordion-item video-upload-final-item mb-2">
                                                                    <div class="accordion-header mb-0" id="headingOne">
                                                                        <button
                                                                            class="accordion-button upload-introduction-title-box d-flex align-items-center justify-content-between"
                                                                            type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{$key}}" aria-expanded="true"
                                                                            aria-controls="collapse{{$key}}">
                                                                            <span class="font-16 ps-4">{{$lesson->name}}</span>
                                                                            <span class="d-flex upload-course-video-6-duration-count">
                                                                            <span class="upload-course-duration-text font-14 color-para font-medium"><span class="iconify"
                                                                                                                                                           data-icon="octicon:device-desktop-24"></span>{{ __('Video') }} <span
                                                                                    class="color-heading">({{$lesson->lectures->count()}})</span></span>
                                                                            <span class="upload-course-duration-text font-14 color-para font-medium"><span class="iconify"
                                                                                                                                                           data-icon="ant-design:clock-circle-outlined"></span>{{ __('Duration') }} <span
                                                                                    class="color-heading">({{@$lesson->lectures->count() > 0 ? lessonVideoDuration($course->id, $lesson->id) : '0 min'}})</span></span>
                                                                    </span>
                                                                        </button>
                                                                    </div>
                                                                    <div id="collapse{{$key}}" class="accordion-collapse collapse {{$key == 0 ? 'show' : '' }} "
                                                                         aria-labelledby="heading{{$key}}" data-bs-parent="#video-upload-done-phase">
                                                                        <div class="accordion-body">
                                                                            @forelse($lesson->lectures as $lecture)
                                                                                <div class="main-upload-video-processing-item removable-item">

                                                                                    <div class="main-upload-video-processing-img-wrap-edit-img">

                                                                                        @if($lecture->type == 'video')
                                                                                            <a data-normal_video="{{ getVideoFile($lecture->file_path) }}" title="See video preview"
                                                                                               class="normalVideo edit-lecture-preview-show d-flex align-items-center justify-content-between color-heading font-medium font-16 mb-3"
                                                                                               data-bs-toggle="modal" href="#html5VideoPlayerModal">
                                                                                                <div class="d-flex flex-grow-1">
                                                                                                    <div><img src="{{ asset('frontend/assets/img/courses-img/play.svg') }}"
                                                                                                              alt="play"></div>
                                                                                                    <div class="font-medium font-16 lecture-edit-title">{{$lecture->title}}</div>
                                                                                                </div>

                                                                                                <div class="upload-course-video-6-text flex-shrink-0">
                                                                                                    <span class="see-preview-video font-medium font-16">{{ __('Preview Video') }}</span>
                                                                                                </div>
                                                                                            </a>
                                                                                        @elseif($lecture->type == 'youtube')
                                                                                            <a class="edit-lecture-preview-show d-flex align-items-center justify-content-between color-heading font-medium font-16 mb-3 venobox"
                                                                                               data-autoplay="true" data-maxwidth="800px" data-vbtype="video"
                                                                                               data-href="https://www.youtube.com/embed/{{ $lecture->url_path }}">
                                                                                                <div class="d-flex flex-grow-1">
                                                                                                    <div><img src="{{ asset('frontend/assets/img/courses-img/play.svg') }}"
                                                                                                              alt="play"></div>
                                                                                                    <div class="font-medium font-16 lecture-edit-title">{{$lecture->title}}</div>
                                                                                                </div>

                                                                                                <div class="upload-course-video-6-text flex-shrink-0">
                                                                                                    <span class="see-preview-video font-medium font-16">{{ __('Preview Video') }}</span>
                                                                                                </div>
                                                                                            </a>
                                                                                        @elseif($lecture->type == 'vimeo')
                                                                                             <a class="edit-lecture-preview-show d-flex align-items-center justify-content-between color-heading font-medium font-16 mb-3 venobox" data-autoplay="true"
                                                                                             data-maxwidth="800px" data-vbtype="video" data-href="https://vimeo.com/{{ $lecture->url_path }}">
                                                                                                <div class="d-flex flex-grow-1">
                                                                                                    <div><img src="{{ asset('frontend/assets/img/courses-img/play.svg') }}" alt="play"></div>
                                                                                                    <div class="font-medium font-16 lecture-edit-title">{{$lecture->title}}</div>
                                                                                                </div>

                                                                                                <div class="upload-course-video-6-text flex-shrink-0">
                                                                                                    <span class="see-preview-video font-medium font-16">{{ __('Preview Video') }}</span>
                                                                                                </div>
                                                                                            </a>
                                                                                        @elseif($lecture->type == 'text')
                                                                                            <a type="button" data-lecture_text="{{ $lecture->text }}" class="lectureText edit-lecture-preview-show d-flex align-items-center justify-content-between color-heading font-medium font-16 mb-3"
                                                                                               data-bs-toggle="modal" href="#textUploadModal">
                                                                                                <div class="d-flex flex-grow-1">
                                                                                                    <div><img src="{{ asset('frontend/assets/img/courses-img/text.svg') }}"
                                                                                                              alt="text"></div>
                                                                                                    <div class="font-medium font-16 lecture-edit-title">{{$lecture->title}}</div>
                                                                                                </div>

                                                                                                <div class="upload-course-video-6-text flex-shrink-0">
                                                                                                    <span class="see-preview-video font-medium font-16">{{ __('Preview') }}</span>
                                                                                                </div>
                                                                                            </a>
                                                                                        @elseif($lecture->type == 'image')
                                                                                            <a data-lecture_image="{{ getImageFile($lecture->image) }}"
                                                                                               class="lectureImage edit-lecture-preview-show d-flex align-items-center justify-content-between color-heading font-medium font-16 mb-3"
                                                                                               data-bs-toggle="modal" href="#imageUploadModal">
                                                                                            <div class="d-flex flex-grow-1">
                                                                                                <div><img src="{{ asset('frontend/assets/img/courses-img/preview-image.svg') }}"
                                                                                                          alt="image"></div>
                                                                                                <div class="font-medium font-16 lecture-edit-title">{{$lecture->title}}</div>
                                                                                            </div>

                                                                                            <div class="upload-course-video-6-text flex-shrink-0">
                                                                                                <span class="see-preview-video font-medium font-16">{{ __('Preview Image') }} </span>
                                                                                            </div>
                                                                                            </a>
                                                                                        @elseif($lecture->type == 'pdf')
                                                                                        <a data-lecture_pdf="{{ getVideoFile($lecture->pdf) }}" class="lecturePdf edit-lecture-preview-show d-flex align-items-center justify-content-between color-heading font-medium font-16 mb-3"
                                                                                            data-bs-toggle="modal" href="#pdfModal">
                                                                                                <div class="d-flex flex-grow-1">
                                                                                                    <div><img src="{{ asset('frontend/assets/img/courses-img/file-pdf.svg') }}"
                                                                                                              alt="PDF"></div>
                                                                                                    <div class="font-medium font-16 lecture-edit-title">{{$lecture->title}}</div>
                                                                                                </div>

                                                                                                <div class="upload-course-video-6-text flex-shrink-0">
                                                                                                    <span class="see-preview-video font-medium font-16">{{ __('Preview PDF') }}</span>
                                                                                                </div>
                                                                                            </a>
                                                                                        @elseif($lecture->type == 'slide_document')
                                                                                            <a data-lecture_slide="{{ $lecture->slide_document }}"
                                                                                               class="lectureSlide edit-lecture-preview-show d-flex align-items-center justify-content-between color-heading font-medium font-16 mb-3"
                                                                                               data-bs-toggle="modal" href="#slideModal">
                                                                                                <div class="d-flex flex-grow-1">
                                                                                                    <div><img src="{{ asset('frontend/assets/img/courses-img/slide-preview.svg') }}"
                                                                                                              alt="Slide Doc"></div>
                                                                                                    <div class="font-medium font-16 lecture-edit-title">{{$lecture->title}}</div>
                                                                                                </div>

                                                                                                <div class="upload-course-video-6-text flex-shrink-0">
                                                                                                    <span class="see-preview-video font-medium font-16">{{ __('Preview Slide') }}</span>
                                                                                                </div>
                                                                                            </a>
                                                                                        @elseif($lecture->type == 'audio')
                                                                                            <a data-lecture_audio="{{ getVideoFile($lecture->audio) }}" class=" lectureAudio edit-lecture-preview-show d-flex align-items-center justify-content-between color-heading font-medium font-16 mb-3"
                                                                                               data-bs-toggle="modal" href="#audioPlayerModal">
                                                                                                <div class="d-flex flex-grow-1">
                                                                                                    <div>
                                                                                                        <img
                                                                                                            src="{{ asset('frontend/assets/img/courses-img/preview-audio-o.svg') }}"
                                                                                                            alt="play">
                                                                                                    </div>
                                                                                                    <div class="font-medium font-16 lecture-edit-title">{{$lecture->title}}</div>
                                                                                                </div>

                                                                                                <div class="upload-course-video-6-text flex-shrink-0">
                                                                                                    <span class="see-preview-video font-medium font-16">{{ __('Preview') }}</span>
                                                                                                </div>
                                                                                            </a>

                                                                                        @endif


                                                                                    </div>

                                                                                    <div class="d-flex">
                                                                                        <div class="flex-shrink-0">
                                                                                            <div
                                                                                                class="video-upload-done-phase-action-btns font-14 color-heading text-center font-medium">
                                                                                                <a href="{{route('organization.edit.lecture', [$course->uuid, $lesson->uuid, $lecture->uuid])}}"
                                                                                                   type="button"
                                                                                                   class="upload-course-video-edit-btn upload-course-video-main-edit-btn font-14 color-para font-medium bg-transparent border-0 mx-2"><span
                                                                                                        class="iconify" data-icon="clarity:note-edit-line"></span>{{ __('Edit') }}</a>
                                                                                                <a href="javascript:void(0);"
                                                                                                   data-url="{{route('organization.delete.lecture', [$course->uuid,  $lecture->uuid])}}"
                                                                                                   class="upload-course-video-edit-btn font-14 color-para font-medium bg-transparent border-0 mx-2 delete"><span
                                                                                                        class="iconify" data-icon="ant-design:delete-outlined"></span>{{ __('Delete') }}</a>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>

                                                                                </div>
                                                                            @empty
                                                                                <div class="d-flex justify-content-between align-items-center">
                                                                                    <div class="upload-introduction-box-content-img">
                                                                                        <img src="{{asset('frontend/assets/img/instructor-img/upload-lesson-icon.png')}}"
                                                                                             alt="upload">
                                                                                    </div>

                                                                                    <div>
                                                                                        <button type="button" data-name="{{ $lesson->name }}"
                                                                                                data-route="{{ route('organization.lesson.update', [$course->uuid, $lesson->uuid]) }}"
                                                                                                class=" upload-course-video-edit-btn font-14 color-para font-medium bg-transparent border-0 editLesson"
                                                                                                data-bs-toggle="modal" data-bs-target="#becomeAnInstructor">
                                                                                            <span class="iconify" data-icon="clarity:note-edit-line"></span>{{ __('Edit') }}
                                                                                        </button>
                                                                                        <button
                                                                                            class="upload-course-video-edit-btn font-14 color-para font-medium bg-transparent border-0 deleteItem"
                                                                                            data-formid="delete_row_form_{{$lesson->uuid}}">
                                                                                            <span class="iconify" data-icon="ant-design:delete-outlined"></span>{{ __('Delete') }}
                                                                                        </button>
                                                                                        <form action="{{ route('organization.lesson.delete', [$lesson->uuid]) }}" method="post"
                                                                                              id="delete_row_form_{{ $lesson->uuid }}">
                                                                                            {{ method_field('DELETE') }}
                                                                                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                                                        </form>
                                                                                    </div>
                                                                                </div>
                                                                            @endforelse


                                                                            <div class="mt-3 lecture-edit-upload-btn">
                                                                                <a href="{{route('organization.upload.lecture', [$course->uuid, $lesson->uuid])}}"
                                                                                   class="common-upload-video-btn color-heading font-13 font-medium ms-0 mt-4">
                                                                                   <span class="iconify" data-icon="feather:upload-cloud"></span>{{ __('Upload lesson') }}</a>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @endforeach

                                                        </div>
                                                    </div>
                                                @else
                                                    @foreach($course->lessons as $lesson)
                                                        <div class="upload-video-introduction-box-wrap">

                                                            <!-- upload-video-introduction-box -->
                                                            <div class="upload-video-introduction-box">

                                                                <div class="upload-introduction-title-box d-flex align-items-center justify-content-between">
                                                                    <h6 class="font-16">{{$lesson->name}}</h6>
                                                                    <div class="d-flex upload-course-video-6-duration-count">
                                                                        <div class="upload-course-duration-text font-14 color-para font-medium">
                                                                            <span class="iconify" data-icon="octicon:device-desktop-24"></span>Video<span class="color-heading">(0)</span></div>
                                                                        <div class="upload-course-duration-text font-14 color-para font-medium">
                                                                            <span class="iconify" data-icon="ant-design:clock-circle-outlined"></span>Duration<span class="color-heading">(0)</span></div>
                                                                    </div>
                                                                </div>

                                                                <div class="upload-introduction-box-content d-flex align-items-center justify-content-between">
                                                                    <div class="upload-introduction-box-content-left d-flex align-items-center">
                                                                        <div class="upload-introduction-box-content-img">
                                                                            <img src="{{asset('frontend/assets/img/instructor-img/upload-lesson-icon.png')}}" alt="upload">
                                                                        </div>
                                                                        <a href="{{route('organization.upload.lecture', [$course->uuid, $lesson->uuid])}}"
                                                                           class="common-upload-lesson-btn font-13 font-medium">
                                                                           <span class="iconify" data-icon="feather:upload-cloud"></span>{{ __('Upload lesson') }}</a>
                                                                    </div>
                                                                    <div class="d-flex">
                                                                        <button type="button" data-name="{{ $lesson->name }}"
                                                                                data-route="{{ route('organization.lesson.update', [$course->uuid, $lesson->uuid]) }}"
                                                                                class="upload-course-video-edit-btn font-14 color-para font-medium bg-transparent border-0 editLesson"
                                                                                data-bs-toggle="modal" data-bs-target="#becomeAnInstructor">
                                                                            <span class="iconify" data-icon="clarity:note-edit-line"></span>{{ __('Edit') }}</button>
                                                                        <button
                                                                            class="upload-course-video-edit-btn font-14 color-para font-medium bg-transparent border-0 deleteItem"
                                                                            data-formid="delete_row_form_{{$lesson->uuid}}">
                                                                            <span class="iconify" data-icon="ant-design:delete-outlined"></span>{{ __('Delete') }}</button>

                                                                        <form action="{{ route('organization.lesson.delete', [$lesson->uuid]) }}" method="post"
                                                                              id="delete_row_form_{{ $lesson->uuid }}">
                                                                            {{ method_field('DELETE') }}
                                                                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                                        </form>
                                                                    </div>

                                                                </div>

                                                            </div>

                                                        </div>
                                                    @endforeach
                                                @endif

                                            </div>

                                            <div class="add-more-section-wrap d-none">
                                                <form method="POST" action="{{route('organization.lesson.store', [$course->uuid])}}" class="row g-3 needs-validation" novalidate>
                                                    @csrf
                                                    <div class="row mb-30">
                                                        <div class="col-md-12">
                                                            <label class="label-text-title color-heading font-medium font-16 mb-3">Section title of the course “ {{$course->title}}
                                                                ”</label>
                                                            <input type="text" name="name" value="{{old('name')}}" required class="form-control" placeholder="Section title">
                                                            @if ($errors->has('name'))
                                                                <span class="text-danger"><i class="fas fa-exclamation-triangle"></i> {{ $errors->first('name') }}</span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="stepper-action-btns d-flex justify-content-between align-items-center">
                                                        <div>
                                                            <a href="javascript:void(0);" class="theme-btn theme-button3 cancel-add-more-section">{{__('Cancel')}}</a>
                                                            <button type="submit" class="theme-btn default-hover-btn theme-button1">{{__('Save')}}</button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>

                                            <div class="stepper-action-btns d-flex justify-content-between align-items-center add-more-lesson-box">
                                                <div>
                                                    <a href="{{route('organization.course.edit', [$course->uuid, 'step=category'])}}"
                                                       class="theme-btn theme-button3">{{__('Back')}}</a>
                                                    @if($course->lectures->count() > 0)
                                                        <a href="{{route('organization.course.edit', [$course->uuid, 'step=instructors'])}}" type="button"
                                                           class="theme-btn theme-button1">{{__('Save and continue')}}</a>
                                                    @endif
                                                </div>

                                                <!-- When click this button show: "add-more-section-wrap" -->
                                                <a href="javascript:void (0);" type="button" class="add-more-section-btn font-14 color-heading font-medium"><span class="iconify"
                                                                                                                                                                  data-icon="akar-icons:circle-plus"></span>{{__('Add More Section')}}
                                                </a>
                                            </div>

                                        </div>

                                        <!-- Upload Course Video-2 end -->

                                        <input type="button" name="previous" class="previous action-button-previous action-button theme-btn theme-button3" value="Back"/> <input
                                            type="button" name="next" class="next action-button default-hover-btn theme-btn theme-button1" value="Save & continue"/>

                                    @else
                                        <!-- Upload Course Video-1 start -->
                                        <form method="POST" action="{{route('organization.lesson.store', [$course->uuid])}}" class="row g-3 needs-validation" novalidate>
                                            @csrf
                                            <div id="upload-course-video-1">

                                                <div class="upload-course-item-block course-overview-step1 radius-8">
                                                    <div class="upload-course-item-block-title mb-3">
                                                        <p class="color-para">{{ __('To Upload your course videos please create your section and lesson details first!') }}</p>
                                                    </div>

                                                    <div class="row mb-30">
                                                        <div class="col-md-12">
                                                            <label class="label-text-title color-heading font-medium font-16 mb-3">{{ __('Section title of the coures') }} “ {{$course->title}}
                                                                ”</label>
                                                            <input type="text" name="name" value="{{old('name')}}" class="form-control" placeholder="Introduction" required>
                                                            @if ($errors->has('name'))
                                                                <span class="text-danger"><i class="fas fa-exclamation-triangle"></i> {{ $errors->first('name') }}</span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="stepper-action-btns">
                                                    <a href="{{route('organization.course.edit', [$course->uuid, 'step=category'])}}"
                                                       class="theme-btn theme-button3">{{__('Back')}}</a>
                                                    <button type="submit" class="theme-btn default-hover-btn theme-button1">{{__('Save and continue')}}</button>
                                                </div>

                                            </div>
                                        </form>

                                        <!-- Upload Course Video-1 end -->
                                    @endif
                                </div>
                                <!-- Upload Course Step-6 Item End -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('modal')
    <!--  Lesson Update Modal Start -->
    <div class="modal fade edit_modal" id="becomeAnInstructor" tabindex="-1" aria-labelledby="becomeAnInstructorLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="becomeAnInstructorLabel">{{ __('Edit Lesson Name') }}</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <form method="POST" id="updateEditModal" action="" class="needs-validation">
                    @csrf
                    <div class="modal-body">

                        <div class="row mb-30">
                            <div class="col-md-12">
                                <label class="label-text-title color-heading font-medium font-16 mb-2">{{__('Name')}}</label>
                                <input type="text" name="name" class="form-control" id="lessonName" placeholder="Write your lesson name" value="" required>
                            </div>
                            @if ($errors->has('name'))
                                <span class="text-danger"><i class="fas fa-exclamation-triangle"></i> {{ $errors->first('name') }}</span>
                            @endif
                        </div>

                        <div class="modal-footer d-flex justify-content-center align-items-center">
                            <button type="submit" class="theme-btn theme-button1 default-hover-btn">{{__('Submit')}}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Lesson Update Modal End -->

    <!--  Text Upload Modal Start -->
    <div class="modal fade textUploadModal venoBoxTypeModal" id="textUploadModal" tabindex="-1" aria-hidden="true">
        <div class="modal-header border-bottom-0">
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><span class="iconify" data-icon="akar-icons:cross"></span></button>
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
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><span class="iconify" data-icon="akar-icons:cross"></span></button>
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
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><span class="iconify" data-icon="akar-icons:cross"></span></button>
        </div>
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    <iframe class="getLectureSlide" src="" width="100%" height="400" frameborder="0" scrolling="no"></iframe>
                </div>
            </div>
        </div>
    </div>
    <!-- Slide Show Upload Modal End-->

    <!-- PDF Show Upload Modal Start-->
    <div class="modal fade venoBoxTypeModal" id="pdfModal" tabindex="-1" aria-hidden="true">
        <div class="modal-header border-bottom-0">
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><span class="iconify" data-icon="akar-icons:cross"></span></button>
        </div>
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    <embed src="" class="getLecturePdf pdf-reader-frame">
                </div>
            </div>
        </div>
    </div>
    <!-- PDF Show Upload Modal End-->

    <!-- Audio Player Modal Start-->
    <div class="modal fade venoBoxTypeModal" id="audioPlayerModal" tabindex="-1" aria-hidden="true">
        <div class="modal-header border-bottom-0">
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><span class="iconify" data-icon="akar-icons:cross"></span></button>
        </div>
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    <!--Audio -->
                    <audio class="getLectureAudio js-player" src="" type="audio/mp3" style="width: 550px" controls controlsList="nodownload">
                    </audio>
                </div>
            </div>
        </div>
    </div>
    <!-- Audio Player Modal End-->

    <!-- HTML5 Video Player Modal Start-->
    <div class="modal fade VideoTypeModal" id="html5VideoPlayerModal" tabindex="-1" aria-hidden="true">
        <div class="modal-header border-bottom-0">
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><span class="iconify" data-icon="akar-icons:cross"></span></button>
        </div>
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="video-player-area">
                        <!-- HTML 5 Video -->
                        <video width="738" height="80%" class="getNormalVideo js-player" controls controlsList="nodownload">
                        </video>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- HTML5 Video Player Modal End-->
@endsection

@push('style')
    <!-- Video Player css -->
    <link rel="stylesheet" href="{{ asset('frontend/assets/vendor/video-player/plyr.css') }}">

    <!-- Summernote CSS - CDN Link -->
    <link href="{{ asset('common/css/summernote/summernote.min.css') }}" rel="stylesheet">
    <link href="{{ asset('common/css/summernote/summernote-lite.min.css') }}" rel="stylesheet">
    <!-- //Summernote CSS - CDN Link -->

@endpush


@push('script')
    <script>
        $(function () {
            'use strict'
            $('.editLesson').on('click', function (e) {
                e.preventDefault();
                $('#lessonName').val($(this).data('name'))
                let route = $(this).data('route');
                $('#updateEditModal').attr("action", route)
            })
        })
    </script>
    <script src="{{asset('frontend/assets/js/custom/form-validation.js')}}"></script>
    <script src="{{asset('frontend/assets/js/custom/upload-lesson.js')}}"></script>
    <script src="{{asset('frontend/assets/js/custom/index.js')}}"></script>

    <!-- Video Player js -->
    <script src="{{ asset('frontend/assets/vendor/video-player/plyr.js') }}"></script>
    <script>
        "use strict"
        const zai_player = new Plyr('#player');
        const zai_player1 = new Plyr('#playerVideoYoutube');
        const zai_player2 = new Plyr('#playerVideoHTML5');
        const zai_player3 = new Plyr('#playerVideoVimeo');
    </script>
    <!-- Video Player js -->

    <script>
        const players = Array.from(document.querySelectorAll('.js-player')).map((p) => new Plyr(p));
    </script>

    <!-- Summernote JS - CDN Link -->
    <script src="{{ asset('common/js/summernote/summernote-lite.min.js') }}"></script>
    <script>
        $(document).ready(function () {
            $("#summernote").summernote({dialogsInBody: true});
            $('.dropdown-toggle').dropdown();
        });
    </script>
    <!-- //Summernote JS - CDN Link -->

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

        $('.normalVideo').on('click', function () {
            var video = $(this).data("normal_video")
            $('.getNormalVideo').attr('src', video)
        })

        $('.lectureAudio').on('click', function () {
            var audio = $(this).data("lecture_audio")
            $('.getLectureAudio').attr('src', audio)
        })

        $('.lecturePdf').on('click', function () {
            var pdf = $(this).data("lecture_pdf")
            $('.getLecturePdf').attr('src', pdf)
        })
    </script>

@endpush
