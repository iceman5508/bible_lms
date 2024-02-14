<h6 class="blog-comment-title">{{ @$blogComments->count() }} {{ __('comments') }}</h6>
@foreach($blogComments as $blogComment)
    <!-- Single Comments-->
    <div class="main-comment">
        <div class="blog-comment-item">
            <div class="comment-author-img radius-50 overflow-hidden">
                <img src="{{ getImageFile(@$blogComment->user->image_path) }}" alt="avatar">
            </div>
            <div class="author-details">
                <h6 class="author-name font-16">{{ @$blogComment->user->name }}</h6>
                <div class="comment-date-time color-gray font-12">{{ @$blogComment->created_at->format(' j  M, Y')  }} AT
                    @php $timestamp = strtotime($blogComment->created_at);
                                                        $time = date("h:i A", $timestamp);
                    @endphp
                    {{ @$time }}</div>
                <p>{{ $blogComment->comment }}</p>
                @if(@Auth::user())
                <button class="blog-reply-btn font-medium color-hover text-decoration-underline reply-btn font-12 replyBtn"
                        data-bs-toggle="modal" data-bs-target="#commentReplyModal" data-parent_id="{{ $blogComment->id }}">
                    {{ __('Reply') }} <span class="iconify" data-icon="la:angle-right"></span></button>
                @endif
            </div>
        </div>

    @foreach($blogComment->blogCommentReplies as $reply)
        <!-- Under Single Comments-->
            <div class="blog-comment-item under-comment">
                <div class="comment-author-img radius-50 overflow-hidden">
                    <img src="{{ getImageFile(@$reply->user->image_path) }}" alt="avatar">
                </div>
                <div class="author-details">
                    <h6 class="author-name font-16">{{ @$reply->user->name }}</h6>
                    <div class="comment-date-time color-gray font-12">
                        {{ @$reply->created_at->format(' j  M, Y')  }} AT
                        @php
                            $timestamp = strtotime($reply->created_at);
                            $time = date("h:i A", $timestamp);
                        @endphp
                        {{ @$time }}
                    </div>
                    <p>{{ $reply->comment }} </p>
                    @if(@Auth::user())
                    <button class="blog-reply-btn font-medium color-hover text-decoration-underline reply-btn font-12 replyBtn"
                            data-bs-toggle="modal" data-bs-target="#commentReplyModal" data-parent_id="{{ $blogComment->id }}">{{ __('Reply') }}
                        <span class="iconify" data-icon="la:angle-right"></span></button>
                    @endif
                </div>
            </div>
            <!-- Under Single Comments-->
        @endforeach

    </div>
    <!-- Single Comments-->
@endforeach
