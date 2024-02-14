@extends('frontend.layouts.app')
@section('meta')
    @php
        $metaData = getMeta('forum');
    @endphp

    <meta name="description" content="{{ $metaData['meta_description'] }}">
    <meta name="keywords" content="{{ $metaData['meta_keyword'] }}">

    <!-- Open Graph meta tags for social sharing -->
    <meta property="og:type" content="Learning">
    <meta property="og:title" content="{{ $metaData['meta_title'] }}">
    <meta property="og:description" content="{{ $metaData['meta_description'] }}">
    <meta property="og:image" content="{{ $metaData['og_image'] }}">
    <meta property="og:url" content="{{ url()->current() }}">

    <meta property="og:site_name" content="{{ get_option('app_name') }}">

    <!-- Twitter Card meta tags for Twitter sharing -->
    <meta name="twitter:card" content="Learning">
    <meta name="twitter:title" content="{{ $metaData['meta_title'] }}">
    <meta name="twitter:description" content="{{ $metaData['meta_description'] }}">
    <meta name="twitter:image" content="{{ $metaData['og_image'] }}">
@endsection
@section('content')
    <div class="">
        <!-- Forum Page Header Start -->
        <header class="page-banner-header blank-page-banner-header gradient-bg position-relative">
            <div class="section-overlay">
                <div class="blank-page-banner-wrap banner-less-header-wrap">
                    <div class="container">
                        <div class="row">
                            <div class="col-12 col-sm-12 col-md-12">
                                <div class="page-banner-content">

                                    <!-- Breadcrumb Start-->
                                    <nav aria-label="breadcrumb">
                                        <ol class="breadcrumb">
                                            <li class="breadcrumb-item font-14"><a href="{{ route('main.index') }}"><span
                                                        class="iconify" data-icon="bx:home-alt"></span> {{ __('Home') }}</a>
                                            </li>
                                            <li class="breadcrumb-item font-14" aria-current="page"><a href="{{ route('forum.index') }}">{{ __('Forum') }}</a></li>
                                            <li class="breadcrumb-item font-14 active" aria-current="page">{{ __('Forum Details') }}</li>
                                        </ol>
                                    </nav>
                                    <!-- Breadcrumb End-->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>
        <!-- Forum Page Header End -->

        <!-- Forum Details Start -->
        <section class="forum-categories-area forum-details-page-area section-b-space">
            <div class="container">
                <div class="row forum-top-row-part">
                    <!-- Left Side Start-->
                    <div class="col-xs-12 col-md-10 col-lg-10 col-xl-9">
                        <div class="forum-details-leftside">

                            <h3 class="mb-30">{{ __($forumPost->title) }}</h3>
                            <!-- Timeline Starts-->
                            <!-- Forum Details item Start -->
                            <div class="forum-details-item">

                                <div class="forum-details-item-top d-flex justify-content-between">
                                    <div class="forum-details-top-left">
                                        <div class="d-flex align-items-center">
                                            <div class="forum-category-single-item-img-wrap flex-shrink-0 radius-50 small-background-img-prop"
                                                 style="background-image: url('{{ getImageFile(@$forumPost->user->image_path) }}')">
                                            </div>
                                            <div class="flex-grow-1 ms-3">
                                                @if(@$forumPost->user->role == 1)
                                                    <h6>{{ $forumPost->user->name }}</h6>
                                                    <p class="font-medium">Admin</p>
                                                @elseif(@$forumPost->user->role == 2)
                                                    <h6>{{ $forumPost->user->instructor->name }}</h6>
                                                    <p class="font-medium">Instructor</p>
                                                @elseif(@$forumPost->user->role == 3)
                                                    <h6>{{ $forumPost->user->student->name }}</h6>
                                                    <p class="font-medium">Student</p>
                                                @else
                                                    <h6>Unknown</h6>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="forum-details-top-right">
                                        <ul class="forum-category-single-item-bottom-right d-flex align-items-center">
                                            <li><span class="iconify" data-icon="ant-design:star-outlined"></span>{{ countUserReplies($forumPost->user_id) }}</li>
                                            <li><span class="iconify" data-icon="bi:eye"></span>{{ $forumPost->total_seen }}</li>
                                            <li><span class="iconify" data-icon="fluent:comment-24-regular"></span>{{ $forumPost->forumPostComments->count() }}</li>
                                        </ul>
                                    </div>
                                </div>

                                <div class="forum-details-middle">
                                    <p>{!! $forumPost->description !!}</p>
                                </div>

                                <div
                                    class="forum-details-bottom forum-category-single-item-bottom d-flex justify-content-between">
                                    <ul class="forum-category-single-item-bottom-left d-flex align-items-center">
                                        <li><span class="iconify" data-icon="ci:calendar-calendar"></span>{{@$forumPost->created_at->format(' j  M, Y')  }}</li>
                                    </ul>
                                    @if(@Auth::user())
                                    <ul class="forum-category-single-item-bottom-right d-flex align-items-center">
                                        <li><a href="#reply_answer" class="theme-btn theme-button1 theme-button3 reply-btn">{{ __('Reply') }}</a>
                                        </li>
                                    </ul>
                                    @endif
                                </div>

                            </div>
                            <!-- Forum Details item End -->

                            <div class="replies-wrap scrollspy-example" data-bs-spy="scroll" data-bs-target="#list-example" data-bs-offset="0" data-bs-smooth-scroll="true" tabindex="0">

                                <h5>{{ __('Replies') }} ({{ $forumPost->forumPostComments->count() }})</h5>

                                @foreach($forumPostComments ?? [] as $forumPostComment)
                                <!-- Timeline Item Box Start -->
                                <div id="timeline-menu-item{{ $forumPostComment->id }}" class="mb-30">
                                    <!-- Replies Box Start -->
                                    <div class="replies-box border-1 radius-4">

                                        <!-- Forum Details item Start -->
                                        <div class="forum-details-item reply-item">

                                            <div class="forum-details-item-top d-flex justify-content-between">
                                                <div class="forum-details-top-left">
                                                    <div class="d-flex align-items-center">
                                                        <div class="forum-category-single-item-img-wrap flex-shrink-0 radius-50 small-background-img-prop"
                                                             style="background-image: url('{{ getImageFile($forumPostComment->user->image_path) }}')">
                                                        </div>
                                                        <div class="flex-grow-1 ms-3">
                                                            @if(@$forumPostComment->user->role == 1)
                                                                <h6>{{ $forumPostComment->user->name }}</h6>
                                                                <p class="font-medium">{{ __('Admin') }}</p>
                                                            @elseif(@$forumPostComment->user->role == 2)
                                                                <h6>{{ $forumPostComment->user->instructor->name }}</h6>
                                                                <p class="font-medium">{{ __('Instructor') }}</p>
                                                            @elseif(@$forumPostComment->user->role == 3)
                                                                <h6>{{ $forumPostComment->user->student->name }}</h6>
                                                                <p class="font-medium">{{ __('Student') }}</p>
                                                            @else
                                                                <h6>{{ __('Unknown') }}</h6>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="forum-details-top-right">
                                                    <ul
                                                        class="forum-category-single-item-bottom-right d-flex align-items-center">
                                                        <li><span class="iconify" data-icon="ant-design:star-outlined"></span>{{ countUserReplies($forumPostComment->user_id) }}</li>
                                                        <li><span class="iconify" data-icon="fluent:comment-24-regular"></span>{{ $forumPostComment->forumPostCommentReplies->count() }}
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>

                                            <div class="forum-details-middle">
                                                <p>{!! $forumPostComment->comment !!}</p>
                                            </div>

                                            <div
                                                class="forum-details-bottom forum-category-single-item-bottom d-flex justify-content-between">
                                                <ul class="forum-category-single-item-bottom-left d-flex align-items-center">
                                                    <li><span class="iconify" data-icon="ci:calendar-calendar"></span>{{ @$forumPostComment->created_at->format(' j  M, Y')  }}</li>
                                                </ul>
                                                @if(@Auth::user())
                                                <ul class="forum-category-single-item-bottom-right d-flex align-items-center">
                                                    <li><span class="iconify" data-icon="mi:repeat"></span></li>
                                                    <li>
                                                        <div class="forum-category-single-item-img-wrap flex-shrink-0 radius-50 small-background-img-prop"
                                                             style="background-image: url('{{ getImageFile(@Auth::user()->image_path) }}')">
                                                        </div>
                                                    </li>
                                                    <li class="font-medium">
                                                        @if(@Auth::user()->role == 1)
                                                            <h6>{{Auth::user()->name }}</h6>
                                                        @elseif(@Auth::user()->role == 2)
                                                            <h6>{{Auth::user()->instructor->name }}</h6>
                                                        @elseif(@Auth::user()->role == 3)
                                                            <h6>{{Auth::user()->student->name }}</h6>
                                                        @endif
                                                    </li>
                                                    <li>
                                                        <button class="theme-btn theme-button1 theme-button3 reply-btn replyBtn" data-bs-toggle="modal" data-bs-target="#commentReplyModal" data-parent_id="{{ $forumPostComment->id }}">{{ __('Reply') }}</button>
                                                    </li>
                                                </ul>
                                                @endif
                                            </div>

                                        </div>
                                        <!-- Forum Details item End -->

                                        @foreach($forumPostComment->forumPostCommentReplies as $forumPostCommentReply)
                                        <!-- Reply Inner Forum Details item Start -->
                                        <div class="forum-details-item reply-item reply-item-inner">

                                            <div class="forum-details-item-top d-flex justify-content-between">
                                                <div class="forum-details-top-left">
                                                    <div class="d-flex align-items-center">
                                                        <div class="forum-category-single-item-img-wrap flex-shrink-0 radius-50 small-background-img-prop"
                                                             style="background-image: url('{{ getImageFile($forumPostCommentReply->user->image_path) }}')">
                                                        </div>
                                                        <div class="flex-grow-1 ms-3">
                                                            @if(@$forumPostCommentReply->user->role == 1)
                                                                <h6>{{ $forumPostCommentReply->user->name }}</h6>
                                                                <p class="font-medium">{{ __('Admin') }}</p>
                                                            @elseif(@$forumPostCommentReply->user->role == 2)
                                                                <h6>{{ $forumPostCommentReply->user->instructor->name }}</h6>
                                                                <p class="font-medium">{{ __('Instructor') }}</p>
                                                            @elseif(@$forumPostCommentReply->user->role == 3)
                                                                <h6>{{ $forumPostCommentReply->user->student->name }}</h6>
                                                                <p class="font-medium">{{ __('Student') }}</p>
                                                            @else
                                                                <h6>{{ __('Unknown') }}</h6>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="forum-details-top-right">
                                                    <ul
                                                        class="forum-category-single-item-bottom-right d-flex align-items-center">
                                                        <li><span class="iconify" data-icon="ant-design:star-outlined"></span>{{ countUserReplies($forumPostCommentReply->user_id) }}
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>

                                            <div class="forum-details-middle">
                                                <p>{!! $forumPostCommentReply->comment !!}</p>
                                            </div>

                                            <div
                                                class="forum-details-bottom forum-category-single-item-bottom d-flex justify-content-between">
                                                <ul class="forum-category-single-item-bottom-left d-flex align-items-center">
                                                    <li><span class="iconify" data-icon="ci:calendar-calendar"></span> {{ @$forumPostCommentReply->created_at->format(' j  M, Y')  }}</li>
                                                </ul>
                                                <ul class="forum-category-single-item-bottom-right d-flex align-items-center">
                                                    <li>
                                                        @if(@Auth::user())
                                                        <button class="theme-btn theme-button1 theme-button3 reply-btn replyBtn" data-bs-toggle="modal" data-bs-target="#commentReplyModal" data-parent_id="{{ $forumPostComment->id }}">{{ __('Reply') }}</button>
                                                        @endif
                                                    </li>
                                                </ul>
                                            </div>

                                        </div>
                                        <!-- Reply Inner Forum Details item End -->
                                        @endforeach
                                    </div>
                                    <!-- Replies Box End -->
                                </div>
                                <!-- Timeline Item Box End -->
                                @endforeach
                            </div>
                            <!-- Timeline End-->
                            @if(@Auth::user())
                            <div class="reply-to-the-topic-box border-1 radius-4 p-30 mt-30" id="reply_answer">
                                <h5 class="font-24 mb-20">{{ __('Reply') }} </h5>

                                <form action="{{ route('forum.forumPostComment.store') }}" method="post">
                                    @csrf
                                    <input type="hidden" name="forum_post_id" value="{{ $forumPost->id }}">
                                    <textarea name="comment" id="summernote"></textarea>
                                    <button type="submit" class="mt-20 theme-btn theme-button1 theme-button3 reply-btn">{{ __('Reply') }}</button>
                                </form>

                            </div>
                            @endif

                        </div>

                    </div>
                    <!-- Left Side End-->

                    <!-- Right Side Start-->
                    <div class="col-xs-12 col-md-2 col-lg-2 col-xl-3">
                        <div class="forum-details-rightside p-30 pt-0 sticky-top">
                            @if(count($forumPostComments) >= 1)
                            <ul class="forum-timeline-menu list-group sticky-top" id="list-example">
                                <div class="timeline-topic-publish-date position-absolute">{{@$forumPost->created_at->format(' j  M, Y')  }}</div>

                                @foreach($forumPostComments ?? [] as $forumPostComment)
                                <li><a href="#timeline-menu-item{{ $forumPostComment->id }}" class="timeline-menu-item list-group-item"><span class="timeline-comment-count">{{ $loop->iteration }}/{{ count($forumPostComments) }}</span>{{ @$forumPostComment->created_at->format(' j  M, Y') }}</a></li>
                                @php $lastComment = $forumPostComment; @endphp
                                @endforeach

                                @if(isset($lastComment))
                                <div class="timeline-topic-end-date position-absolute">{{ @$lastComment->created_at->format(' j  M, Y') }}</div>
                                @endif
                            </ul>
                            @endif
                        </div>
                    </div>
                    <!-- Right Side End-->
                </div>

                @if(count($suggestedForumPosts) >= 1)
                <div class="row">
                    <div class="col-md-12 col-lg-9">
                        <div class="suggested-topic-area mt-100">
                            <h3 class="mb-4">{{ __('Suggested Topics') }}</h3>
                            <div class="forum-categories-wrap">
                                @foreach($suggestedForumPosts as $suggestedForumPost)
                                <!-- forum-category-single-item start -->
                                <div class="forum-category-single-item d-flex border-1 radius-4">
                                    <div class="forum-category-single-item-img-wrap flex-shrink-0 radius-50 small-background-img-prop"
                                         style="background-image: url({{ getImageFile($suggestedForumPost->user->image_path) }})">
                                    </div>
                                    <div class="forum-category-single-item-right flex-grow-1 ms-3">
                                        <p class="font-14 color-hover">{{ @$suggestedForumPost->forumCategory->title }}</p>
                                        <h6 class="font-20">{{ $suggestedForumPost->title }}</h6>
                                        <p class="font-15">{!! strip_tags(Str::words($suggestedForumPost->description, 60)) !!}</p>

                                        <div class="forum-category-single-item-bottom d-flex justify-content-between">
                                            <ul class="forum-category-single-item-bottom-left d-flex align-items-center">
                                                @if(@$suggestedForumPost->user->role == 1)
                                                    <li class="forum-category-single-item-bottom-left-name font-14 radius-4">By
                                                        <a href="#" class="color-heading font-medium ps-2">{{ $suggestedForumPost->user->name }}</a>
                                                    </li>
                                                    <li>{{ __('Admin') }}</li>
                                                @elseif(@$suggestedForumPost->user->role == 2)
                                                    <li class="forum-category-single-item-bottom-left-name font-14 radius-4">By
                                                        <a href="#" class="color-heading font-medium ps-2">{{ $suggestedForumPost->user->instructor->name }}</a>
                                                    </li>
                                                    <li>{{ __('Instructor') }}</li>
                                                @elseif(@$suggestedForumPost->user->role == 3)
                                                    <li class="forum-category-single-item-bottom-left-name font-14 radius-4">By
                                                        <a href="#" class="color-heading font-medium ps-2">{{ $suggestedForumPost->user->student->name }}</a>
                                                    </li>
                                                    <li>{{ __('Student') }}</li>
                                                @endif
                                                <li><span class="iconify" data-icon="ci:calendar-calendar"></span>{{ @$suggestedForumPost->created_at->format(' j  M, Y')  }}</li>
                                            </ul>
                                            <ul class="forum-category-single-item-bottom-right d-flex align-items-center">
                                                <li><span class="iconify" data-icon="ant-design:star-outlined"></span>{{ countUserReplies($suggestedForumPost->user_id) }}</li>
                                                <li><span class="iconify" data-icon="bi:eye"></span>{{ $suggestedForumPost->total_seen }}</li>
                                                <li><span class="iconify" data-icon="fluent:comment-24-regular"></span>{{ $suggestedForumPost->forumPostComments->count() }}</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <!-- forum-category-single-item end -->
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </section>
        <!-- Forum Details End -->

        @if(count($blogs) >= 1)
            <!-- Forum community blog articles Area Start -->
            <section class="community-blog-articles-area section-t-space section-b-85-space bg-page">
                <div class="container">
                    <div class="row">
                        <div class="col-12">
                            <div class="section-title text-center">
                                <h3 class="section-heading">{{ __('Community Blog Articles') }}</h3>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        @foreach($blogs as $blog)
                            <!-- Blog Item Start -->
                            <div class="col-md-6">
                                <div class="blog-item">

                                    <div class="blog-item-img-wrap overflow-hidden position-relative">
                                        <a href="{{ route('blog-details', $blog->slug) }}"><img src="{{ getImageFile($blog->image) }}" alt="img" class="img-fluid"></a>
                                        <div class="blog-item-tag position-absolute font-12 font-semi-bold text-white bg-hover radius-3">{{ @$blog->category->name }}</div>
                                    </div>

                                    <div class="blog-item-bottom-part">
                                        <h3 class="card-title blog-title"><a href="{{ route('blog-details', $blog->slug) }}">{{ Str::limit($blog->title, 50) }}</a></h3>
                                        <p class="blog-author-name-publish-date font-13 font-medium color-gray">{{ $blog->user->name }} / {{ @$blog->created_at->format(' j  M, Y')  }}</p>
                                        <p class="card-text blog-content">{!!  Str::limit($blog->details, 200) !!}</p>

                                        <div class="blog-read-more-btn">
                                            <a href="{{ route('blog-details', $blog->slug) }}" class="theme-btn font-15 ps-0 font-medium color-hover">{{ __('Read More') }} <i data-feather="arrow-right"></i></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Blog Item Start -->
                        @endforeach
                        <!-- section button start-->
                        <div class="col-12 text-center section-btn">
                            <a href="{{ route('blogs') }}" class="theme-btn theme-button1">{{ __('All Blogs') }} <i data-feather="arrow-right"></i></a>
                        </div>
                        <!-- section button end-->
                    </div>
                </div>
            </section>
            <!-- Forum community blog articles Area End -->
        @endif
    </div>

    <!-- Comment Reply Modal Start -->
    <div class="modal fade" id="commentReplyModal" tabindex="-1" aria-labelledby="writeReviewModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="writeReviewModalLabel">{{ __('Reply') }}</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('forum.forumPostCommentReply.store') }}" method="post">
                    @csrf
                    <input type="hidden" name="parent_id" class="parent_id">
                    <input type="hidden" name="forum_post_id" class="forum_post_id" value="{{ $forumPost->id }}">
                    <div class="modal-body">
                        <!-- Leave a Comment Area-->

                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group para-color font-14 color-gray nessage-text mb-30">
                                    <textarea name="comment" id="summernote2"></textarea>
                                </div>
                            </div>
                        </div>
                        <!-- Leave a Comment Area-->
                    </div>
                    <div class="modal-footer d-flex justify-content-between align-items-center">
                        <button type="button" class="theme-btn theme-button3 modal-back-btn" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                        <button type="submit" class="theme-btn theme-button1 default-hover-btn">{{ __('Reply') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Comment Reply Modal End -->
@endsection

@push('style')
    <!-- Summernote CSS - CDN Link -->
    <link href="{{ asset('common/css/summernote/summernote.min.css') }}" rel="stylesheet">
    <link href="{{ asset('common/css/summernote/summernote-lite.min.css') }}" rel="stylesheet">
    <!-- //Summernote CSS - CDN Link -->
@endpush

@push('script')
    <!-- Summernote JS - CDN Link -->
    <script src="{{ asset('common/js/summernote/summernote-lite.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $("#summernote").summernote({dialogsInBody: true});
            $('.dropdown-toggle').dropdown();
        });

        $(document).ready(function() {
            $("#summernote2").summernote({dialogsInBody: true});
            $('.dropdown-toggle').dropdown();
        });
    </script>
    <!-- //Summernote JS - CDN Link -->

    <script>
        'use strict'

        $(document).on('click', '.replyBtn', function () {
            let parent_id = $(this).data('parent_id');
            $('.parent_id').val(parent_id)
        });

    </script>
@endpush
