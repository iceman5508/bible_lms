<div class="tab-pane fade" id="Discussion" role="tabpanel" aria-labelledby="Discussion-tab">
    <div class="row">
        @if(count($discussions) > 0)
            <div class="discussion-comment-block">
                <!-- Single Comment Box Start -->
                <div class="single-comment-box">
                    @foreach(@$discussions as $discussion)
                        <div class="discussion-comment-item d-flex">
                            <div class="discussion-comment-left-img-wrap flex-shrink-0">
                                <img src="{{ asset(@$discussion->user->image_path) }}" alt="img">
                            </div>
                            <div class="comment-content-part flex-grow-1">

                                <div class="comment-content-part-top">
                                    <h6 class="font-18 mb-15">{{@$discussion->user->name}}
                                        @if($discussion->comment_as == 1)
                                            <span class="teacher-badge bg-light-purple color-hover font-14 font-medium radius-4 px-2 py-1">{{ __('Instructor') }}</span>
                                        @endif
                                    </h6>
                                    <p class="font-15">
                                        {!! $discussion->comment !!}
                                    </p>
                                </div>

                                <div class="discussion-comment-extra-info d-flex justify-content-between mt-2">
                                    <div class="extra-info-left d-flex">
                                        <div class="last-active-status font-15 color-gray">{{ $discussion->created_at->diffforhumans() }}</div>
                                    </div>
                                    <div class="extra-info-right d-flex">
                                        <div class="font-15 d-flex align-items-center"><span class="iconify" data-icon="fa-solid:comment"></span>{{ count($discussion->replies) }}</div>
                                    </div>
                                </div>


                            </div>
                        </div>
                        @foreach($discussion->replies as $reply)
                            <div class="discussion-comment-item discussion-inner-comment-item d-flex">
                                <div class="discussion-comment-left-img-wrap flex-shrink-0">
                                    <img src="{{ asset(@$reply->user->image_path) }}" alt="img">
                                </div>
                                <div class="comment-content-part flex-grow-1">

                                    <div class="comment-content-part-top">
                                        <h6 class="font-18 mb-15">{{ @$reply->user->name }}
                                            @if($reply->comment_as == 1)
                                            <span class="teacher-badge bg-light-purple color-hover font-14 font-medium radius-4 px-2 py-1">{{ __('Instructor') }}</span>
                                            @endif
                                        </h6>
                                        <p class="font-15">{!! $reply->comment !!}</p>
                                    </div>

                                    <div class="discussion-comment-extra-info d-flex justify-content-between mt-2">
                                        <div class="extra-info-left d-flex">
                                            <div class="last-active-status font-15 color-gray">{{ $reply->created_at->diffforhumans() }}</div>
                                        </div>
                                    </div>


                                </div>
                            </div>
                        @endforeach
                        <!-- Discussion Reply Block -->
                    @endforeach
                </div>
                <!-- Single Comment Box End -->
            </div>
        @else
            <div class="no-course-found text-center">
                <img src="{{ asset('frontend/assets/img/empty-data-img.png') }}" alt="img" class="img-fluid">
                <h5 class="mt-3">{{ __('No Discussion Found') }}</h5>
            </div>
        @endif
    </div>
</div>
