@if(count($discussions) > 0)
    <div class="discussion-comment-block">
        <!-- Single Comment Box Start -->
        <div class="single-comment-box">
            @foreach(@$discussions as $discussion)
                <div class="discussion-comment-item d-flex">
                    <div class="discussion-comment-left-img-wrap flex-shrink-0">
                        <img src="{{ getImageFile(@$discussion->user->image_path) }}" alt="img">
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
                                <div class="last-active-status font-15 color-gray">{{ @$discussion->created_at->diffforhumans() }}</div>
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
                            <img src="{{ getImageFile(@$reply->user->image_path) }}" alt="img">
                        </div>
                        <div class="comment-content-part flex-grow-1">

                            <div class="comment-content-part-top">
                                <h6 class="font-18 mb-15">{{ @$reply->user->name }}
                                    @if($reply->comment_as == 1)
                                    <span class="teacher-badge bg-light-purple color-hover font-14 font-medium radius-4 px-2 py-1">{{ __('Instructor') }}</span>
                                    @endif
                                </h6>
                                <p class="font-15">
                                    {!! $reply->comment !!}</p>
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
                <div class="discussion-reply-block d-flex">
                    <div class="discussion-comment-left-img-wrap flex-shrink-0">
                        <img src="{{ getImageFile(Auth::user()->image_path) }}" alt="img">
                    </div>
                    <div class="flex-grow-1">
                        <form action="{{ route('student.discussion.reply', $discussion->id) }}" method="post">
                            @csrf
                            <input type="hidden" name="course_id" value="{{ $course->id }}">
                            <input type="hidden" name="comment_as" value="2">
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <input type="text" name="commentReply" class="form-control font-15 color-gray" placeholder="Leave a reply...">
                                </div>
                                <div class="col-md-12 mb-3">
                                    <button type="submit" class="theme-btn theme-button1 default-hover-btn">{{ __('Reply') }}</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
        <!-- Single Comment Box End -->
    </div>
@endif
