<div class="tab-pane fade" id="Curriculum" role="tabpanel" aria-labelledby="Curriculum-tab">
    <div class="curriculum-content">
        <div class="accordion" id="accordionExample">
            @forelse(@$course->lessons as $key => $lesson)
                <div class="accordion-item">
                    <h2 class="accordion-header" id="heading{{ $key }}">
                        <button class="accordion-button font-medium font-18 {{ $key == 0 ? '' : 'collapsed' }}" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $key }}" aria-expanded="{{ $key == 0 ? 'true' : 'false' }}" aria-controls="collapseOne">
                            {{ __($lesson->name) }}
                        </button>
                    </h2>
                    <div id="collapse{{ $key }}" class="accordion-collapse collapse {{ $key == 0 ? 'show' : '' }}" aria-labelledby="heading{{ $key }}" data-bs-parent="#accordionExample">
                        <div class="accordion-body">
                            <div class="play-list">

                                <!-- Note End-->
                                <!-- If User Logged In then add Class Name in play-list-item = "venobox"-->
                                <!-- If Preview has for this course add Class Name in play-list-item = "preview-enabled"-->
                                <!-- Note Start-->

                                @forelse($lesson->lectures as  $lecture)
                                    @if($lecture->lecture_type == 1)
                                        @if($lecture->type == 'video')
                                            <a title="See video preview" class="edit-lecture-preview-show d-flex align-items-center justify-content-between color-heading font-medium font-16 mb-3" data-bs-toggle="modal" href="#html5VideoPlayerModal{{ $lecture->id }}">
                                                <div class="d-flex flex-grow-1">
                                                    <div><img src="{{ asset('frontend/assets/img/courses-img/play.svg') }}" alt="play"></div>
                                                    <div class="font-medium font-16 lecture-edit-title">{{$lecture->title}}</div>
                                                </div>

                                                <div class="upload-course-video-6-text flex-shrink-0">
                                                    <span class="see-preview-video font-medium font-16">{{ __('Preview') }} </span>
                                                    <span class="video-time-count">{{ $lecture->file_duration }}</span>
                                                </div>
                                            </a>

                                            <!-- HTML5 Video Player Modal Start-->
                                            <div class="modal fade VideoTypeModal" id="html5VideoPlayerModal{{ $lecture->id }}" tabindex="-1" aria-hidden="true">

                                                <div class="modal-header border-bottom-0">
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><span class="iconify" data-icon="akar-icons:cross"></span></button>
                                                </div>
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content">
                                                        <div class="modal-body">
                                                            <div class="video-player-area">
                                                                <!-- HTML 5 Video -->
                                                                <video class="js-player" id="playerVideoHTML5" playsinline controls  controlsList="nodownload">
                                                                    <source src="{{getVideoFile($lecture->file_path)}}" type="video/mp4" >
                                                                </video>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @elseif($lecture->type == 'youtube')
                                            <a class="edit-lecture-preview-show d-flex align-items-center justify-content-between color-heading font-medium font-16 mb-3" data-bs-toggle="modal" href="#newVideoPlayerModal{{ $lecture->id }}">
                                                <div class="d-flex flex-grow-1">
                                                    <div><img src="{{ asset('frontend/assets/img/courses-img/play.svg') }}" alt="play"></div>
                                                    <div class="font-medium font-16 lecture-edit-title">{{$lecture->title}}</div>
                                                </div>

                                                <div class="upload-course-video-6-text flex-shrink-0">
                                                    <span class="see-preview-video font-medium font-16">{{ __('Preview') }}</span>
                                                    <span class="video-time-count">{{ $lecture->file_duration }}</span>
                                                </div>
                                            </a>

                                            <!-- Youtube Video Player Modal Start-->
                                            <div class="modal fade VideoTypeModal" id="newVideoPlayerModal{{ $lecture->id }}" tabindex="-1" aria-hidden="true">

                                                <div class="modal-header border-bottom-0">
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><span class="iconify" data-icon="akar-icons:cross"></span></button>
                                                </div>
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content">
                                                        <div class="modal-body">
                                                            <div class="video-player-area">
                                                                <!-- Youtube Video -->
                                                                <div class="plyr__video-embed js-player" id="playerVideoYoutube">
                                                                    <iframe
                                                                        src="https://www.youtube.com/embed/{{ $lecture->url_path }}"
                                                                        allowfullscreen
                                                                        allowtransparency
                                                                        allow="autoplay"
                                                                    ></iframe>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @elseif($lecture->type == 'vimeo')
                                            <a class="edit-lecture-preview-show d-flex align-items-center justify-content-between color-heading font-medium font-16 mb-3" data-bs-toggle="modal" href="#vimeoVideoPlayerModal{{ $lecture->id }}">
                                                <div class="d-flex flex-grow-1">
                                                    <div><img src="{{ asset('frontend/assets/img/courses-img/play.svg') }}" alt="play"></div>
                                                    <div class="font-medium font-16 lecture-edit-title">{{$lecture->title}}</div>
                                                </div>

                                                <div class="upload-course-video-6-text flex-shrink-0">
                                                    <span class="see-preview-video font-medium font-16">{{ __('Preview') }}</span>
                                                    <span class="video-time-count">{{ $lecture->file_duration }}</span>
                                                </div>
                                            </a>

                                            <!-- Vimeo Video Player Modal Start-->
                                            <div class="modal fade VideoTypeModal" id="vimeoVideoPlayerModal{{ $lecture->id }}" tabindex="-1" aria-hidden="true">

                                                <div class="modal-header border-bottom-0">
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><span class="iconify" data-icon="akar-icons:cross"></span></button>
                                                </div>
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content">
                                                        <div class="modal-body">
                                                            <div class="video-player-area">
                                                                <!-- Vimeo Video -->
                                                                <div class="plyr__video-embed" id="playerVideoVimeo">
                                                                    <iframe
                                                                        src="https://player.vimeo.com/video/{{ $lecture->url_path }}?loop=false&amp;byline=false&amp;portrait=false&amp;title=false&amp;speed=true&amp;transparent=0&amp;gesture=media"
                                                                        allowfullscreen
                                                                        allowtransparency
                                                                        allow="autoplay"
                                                                    ></iframe>


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
                                                    <div><img src="{{ asset('frontend/assets/img/courses-img/text.svg') }}" alt="text"></div>
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
                                                    <div><img src="{{ asset('frontend/assets/img/courses-img/preview-image.svg') }}" alt="image"></div>
                                                    <div class="font-medium font-16 lecture-edit-title">{{$lecture->title}}</div>
                                                </div>

                                                <div class="upload-course-video-6-text flex-shrink-0">
                                                    <span class="see-preview-video font-medium font-16">{{ __('Preview Image') }} </span>
                                                </div>
                                            </a>
                                        @elseif($lecture->type == 'pdf')
                                            <a class="edit-lecture-preview-show d-flex align-items-center justify-content-between color-heading font-medium font-16 mb-3"
                                               data-maxwidth="800px" target="_blank" href="{{ getImageFile($lecture->pdf) }}">
                                                <div class="d-flex flex-grow-1">
                                                    <div><img src="{{ asset('frontend/assets/img/courses-img/file-pdf.svg') }}" alt="PDF"></div>
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
                                                    <div><img src="{{ asset('frontend/assets/img/courses-img/slide-preview.svg') }}" alt="Slide Doc"></div>
                                                    <div class="font-medium font-16 lecture-edit-title">{{$lecture->title}}</div>
                                                </div>

                                                <div class="upload-course-video-6-text flex-shrink-0">
                                                    <span class="see-preview-video font-medium font-16">{{ __('Preview Slide') }}</span>
                                                </div>
                                            </a>
                                        @elseif($lecture->type == 'audio')
                                            <a class="edit-lecture-preview-show d-flex align-items-center justify-content-between color-heading font-medium font-16 mb-3" data-bs-toggle="modal" href="#audioPlayerModal{{ $lecture->id }}">
                                                <div class="d-flex flex-grow-1">
                                                    <div>
                                                        <img src="{{ asset('frontend/assets/img/courses-img/preview-audio-o.svg') }}" alt="play">
                                                    </div>
                                                    <div class="font-medium font-16 lecture-edit-title">{{$lecture->title}}</div>
                                                </div>

                                                <div class="upload-course-video-6-text flex-shrink-0">
                                                    <span class="see-preview-video font-medium font-16">{{ __('Preview') }}</span>
                                                    <span class="video-time-count">{{ $lecture->file_duration }}</span>
                                                </div>
                                            </a>

                                            <!-- Audio Player Modal Start-->
                                            <div class="modal fade venoBoxTypeModal" id="audioPlayerModal{{ $lecture->id }}" tabindex="-1" aria-hidden="true">

                                                <div class="modal-header border-bottom-0">
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><span class="iconify" data-icon="akar-icons:cross"></span></button>
                                                </div>
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content">
                                                        <div class="modal-body">
                                                            <!--Audio -->
                                                            <audio class="js-player" id="player" controls>
                                                                <source src="{{ getVideoFile($lecture->audio) }}" type="audio/mp3" >
                                                            </audio>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Audio Player Modal End-->

                                        @endif
                                    @else

                                    <!-- Play List Item Start-->
                                    <a class="play-list-item d-flex align-items-center justify-content-between" data-autoplay="true" data-maxwidth="800px" data-vbtype="video" data-href="">
                                        <div class="play-list-left d-flex align-items-center">
                                            <div>
                                                @if($lecture->type == 'audio')
                                                    <img src="{{ asset('frontend/assets/img/courses-img/preview-audio-o.svg') }}" alt="play">
                                                @elseif($lecture->type == 'video' || $lecture->type == 'vimeo' || $lecture->type == 'youtube')
                                                    <img src="{{ asset('frontend/assets/img/courses-img/play.svg') }}" alt="play">
                                                @elseif($lecture->type == 'slide_document')
                                                    <img src="{{ asset('frontend/assets/img/courses-img/slide-preview.svg') }}" alt="Slide Doc">
                                                @elseif($lecture->type == 'pdf')
                                                    <img src="{{ asset('frontend/assets/img/courses-img/file-pdf.svg') }}" alt="PDF">
                                                @elseif($lecture->type == 'image')
                                                    <img src="{{ asset('frontend/assets/img/courses-img/preview-image.svg') }}" alt="image">
                                                @elseif($lecture->type == 'text')
                                                    <img src="{{ asset('frontend/assets/img/courses-img/text.svg') }}" alt="text">
                                                @endif
                                            </div>
                                            <p>{{ __($lecture->title) }}</p>
                                        </div>
                                        <div class="play-list-right d-flex">
                                            <span class="show-preview"></span>
                                            <span class="video-time-count"><span class="iconify me-5" data-icon="ant-design:lock-outlined"></span>{{ $lecture->file_duration }}</span>
                                        </div>
                                    </a>
                                    <!-- Play List Item End-->
                                    @endif

                                @empty
                                    <div class="row">
                                        <p>{{ __('No Data Found') }}!</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="row">
                    <p>{{ __('No Data Found') }}!</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
