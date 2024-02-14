<div class="discussion-comment-block instructor-discussion-comment-block custom-scrollbar">

    <!-- Single Comment Box Start -->
    <div class="single-comment-box instructor-discussion-single-comment-box">
        @forelse(@$discussions as $discussion)
            <div class="discussion-comment-item instructor-discussion-comment-item d-flex">
                <div class="discussion-comment-left-img-wrap flex-shrink-0">
                    <img src="{{ getImageFile(@$discussion->user->image_path) }}" alt="img">
                </div>
                <div class="comment-content-part flex-grow-1">
                    <div class="comment-content-part-top">
                        <h6 class="font-18 mb-15">{{@$discussion->user->name}}
                            @if($discussion->comment_as == 1)
                                <span class="teacher-badge bg-light-purple color-hover font-14 font-medium radius-4 px-2 py-1">Instructor</span>
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
                <div class="discussion-comment-item instructor-discussion-comment-item discussion-inner-comment-item instructor-discussion-inner-comment-item d-flex">
                    <div class="discussion-comment-left-img-wrap flex-shrink-0">
                        <img src="{{ getImageFile(@$reply->user->image_path) }}" alt="img">
                    </div>
                    <div class="comment-content-part flex-grow-1">

                        <div class="comment-content-part-top">
                            <h6 class="font-18 mb-15">{{ @$reply->user->name }}
                                @if($reply->comment_as == 1)
                                    <span class="teacher-badge bg-light-purple color-hover font-14 font-medium radius-4 px-2 py-1">{{ __('Instructor') }}</span>
                                @endif
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
            <div class="discussion-reply-block instructor-discussion-reply-block d-flex">
                <div class="discussion-comment-left-img-wrap flex-shrink-0">
                    <img src="{{ getImageFile(Auth::user()->image_path) }}" alt="img">
                </div>
                <div class="flex-grow-1">
                    <form>
                         <div class="row">
                            <div class="col-md-12 mb-3">
                                <input type="text" name="commentReply" class="commentReply_{{ $discussion->id }} form-control font-15 color-gray" placeholder="{{ __('Leave a reply...') }}">
                            </div>
                            <div class="col-md-12 mb-3">
                                <span class="theme-btn theme-button1 default-hover-btn instructorDiscussionReply"
                                      data-discussion_id="{{ $discussion->id }}"
                                      data-route="{{ route('organization.discussion.reply', $discussion->id) }}" data-course_id="{{ $discussion->course->id }}">
                                    {{ __('Reply') }}
                                </span>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        @empty
            <div class="empty-data ">
                <h5 class="my-3">{{ __('Empty Discussion') }}</h5>
            </div>
        @endforelse
    </div>
    <!-- Single Comment Box End -->

</div>
