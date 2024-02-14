<div class="tab-pane fade" id="Discussion" role="tabpanel" aria-labelledby="Discussion-tab">
    <div class="row">
        <div class="col-12">
            <div class="after-purchase-course-watch-tab bg-white p-30">
                <div class="discussion-top-block d-flex">
                    <div class="discussion-left-img-wrap flex-shrink-0">
                        <img src="{{ getImageFile(auth()->user()->image_path) }}" alt="img">
                    </div>
                    <div class="discussion-righ-wrap flex-grow-1">
                        <div class="start-conversation-btn-wrap">
                            <button><span class="iconify" data-icon="healthicons:group-discussion-meetingx3-outline"></span>{{ __('Start a Conversation') }}</button>
                        </div>
                        <div class="editor-wrap">
                            <form action="{{ route('student.discussion.create') }}" method="post">
                                @csrf
                                <input type="hidden" name="course_id" value="{{ $course->id }}">
                                <input type="hidden" name="comment_as" value="2">
                                <div class="editor-text-wrapper">
                                    <textarea id="mytextarea" class="commentDiscussionPost" name="discussionComment"
                                              placeholder="{{ __('Share tips and shortcuts or simply start a discussion about this class ....') }}"></textarea>
                                </div>
                                <button type="submit" class="theme-btn theme-button1 default-hover-btn mt-30 ">{{ __('Post') }}</button>
                            </form>
                        </div>
                    </div>
                </div>

                <div>
                    @include('frontend.student.course.partial.render-partial-discussion-list')
                </div>
            </div>
        </div>
    </div>
</div>
