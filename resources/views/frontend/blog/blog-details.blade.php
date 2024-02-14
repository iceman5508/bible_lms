@extends('frontend.layouts.app')

@section('meta')
    <meta name="description" content="{{ __($blog->meta_description) }}">
    <meta name="keywords" content="{{ __($blog->meta_keywords) }}">

    <!-- Open Graph meta tags for social sharing -->
    <meta property="og:type" content="Learning">
    <meta property="og:title" content="{{ __($blog->meta_title) }}">
    <meta property="og:description" content="{{ __($blog->meta_description) }}">
    <meta property="og:image" content="{{ getImageFile($blog->og_image) }}">
    <meta property="og:url" content="{{ url()->current() }}">

    <meta property="og:site_name" content="{{ __(get_option('app_name')) }}">

    <!-- Twitter Card meta tags for Twitter sharing -->
    <meta name="twitter:card" content="Learning">
    <meta name="twitter:title" content="{{ __($blog->meta_title) }}">
    <meta name="twitter:description" content="{{ __($blog->meta_description) }}">
    <meta name="twitter:image" content="{{ getImageFile($blog->og_image) }}">
@endsection

@section('content')
    <div class="bg-page">

        <!-- Page Header Start -->
        <header class="page-banner-header gradient-bg position-relative">
            <div class="section-overlay">
                <div class="container">
                    <div class="row">
                        <div class="col-12 col-sm-12 col-md-12">
                            <div class="page-banner-content text-center">
                                <h3 class="page-banner-heading text-white pb-15">{{ __(@$pageTitle) }}</h3>

                                <!-- Breadcrumb Start-->
                                <nav aria-label="breadcrumb">
                                    <ol class="breadcrumb justify-content-center">
                                        <li class="breadcrumb-item font-14"><a href="{{ url('/') }}">{{ __('Home') }}</a></li>
                                        <li class="breadcrumb-item font-14 active" aria-current="page">{{ __(@$pageTitle) }}</li>
                                    </ol>
                                </nav>
                                <!-- Breadcrumb End-->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>
        <!-- Page Header End -->

        <!-- Course Single Details Area Start -->
        <section class="blog-page-area blog-details-page section-t-space">
            <div class="container">
                <div class="row">
                    <div class="col-12 col-md-7 col-lg-8">

                        <div class="blog-page-left-content">

                            <!-- Blog Item Start -->
                            <div class="blog-item mb-0">

                                <div class="blog-item-img-wrap overflow-hidden position-relative">
                                    <img src="{{ getImageFile($blog->image_path) }}" alt="img" class="img-fluid">
                                    <div
                                        class="blog-item-tag position-absolute font-12 font-semi-bold text-white bg-hover radius-3">{{ __(@$blog->category->name) }}</div>
                                </div>

                                <div class="blog-item-bottom-part blog-details-content-wrap">
                                    <h3 class="card-title blog-title">{{ __($blog->title) }}</h3>
                                    <p class="blog-author-name-publish-date font-13 font-medium color-gray">{{ $blog->user->name }}
                                        / {{ $blog->created_at->format(' j  M, Y')  }}</p>
                                    <p class="card-text blog-content">{!! $blog->details !!}</p>


                                </div>
                            </div>
                            <!-- Blog Item End -->

                            <!-- Block 2-->
                            <div class="block-2">
                                <div class="share-article d-flex align-items-center">
                                    <div class="share-box color-gray font-12 me-4 font-medium">{{ __('Share') }}:</div>
                                    <div class="social-share-box">
                                        <a href="http://www.facebook.com/sharer.php?u={{ route('blog-details', $blog->slug) }}" target="_blank" title="Facebook">
                                            <button class="social-share-btn"><span class="iconify" data-icon="bx:bxl-facebook"></span></button>
                                        </a>
                                        <a href="https://twitter.com/share?url={{ route('blog-details', $blog->slug) }}" target="_blank" title="Twitter">
                                            <button class="social-share-btn"><span class="iconify" data-icon="ant-design:twitter-outlined"></span></button>
                                        </a>
                                        <a href="https://www.linkedin.com/shareArticle?url={{ route('blog-details', $blog->slug) }}"
                                           rel="me" title="Linkedin" target="_blank">
                                            <button class="social-share-btn"><span class="iconify" data-icon="bxl:linkedin"></span></button>
                                        </a>
                                    </div>
                                </div>

                            </div>
                            <!-- Block 2-->

                            <!-- Customer Comments and Reply Section-->
                            <div class="blog-comments-section mt-4">

                                <div class="appendCommentList">
                                    @include('frontend.blog.render-comment-list')
                                </div>
                            </div>

                            <!-- Customer Comments and Reply Section-->
                            @if(@Auth::user())
                            <!-- Leave a Comment Area-->
                            <div class="leave-comment-area bg-white">
                                <h2 class="blog-comment-title">{{ __('Leave a comment') }}</h2>

                                <form action="#">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="form-group para-color font-14 color-gray mb-30">
                                                <input type="text" class="form-control name" required placeholder="{{ __('Your Name *') }}" value="{{ @Auth::user()->name }}" readonly>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group para-color font-14 color-gray mb-30">
                                                <input type="email" class="form-control email" id="cus_email" required placeholder="{{ __('Your Email *') }}" value="{{ @Auth::user()->email }}"
                                                       readonly>
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="form-group para-color font-14 color-gray nessage-text mb-30">
                                                <textarea name="message" rows="3" class="form-control comment" id="cus_comment" required placeholder="{{ __('Your Comments *') }}"></textarea>
                                            </div>
                                            <div class="contact-sub-btn">
                                                <button type="button" class="theme-button theme-button1 theme-button3 submitComment">{{ __('Submit Now') }}</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <!-- Leave a Comment Area-->
                            @endif

                        </div>

                    </div>
                    <div class="col-12 col-md-5 col-lg-4">
                        <div class="blog-page-right-content bg-white">

                            <div class="blog-sidebar-box">
                                <form class="blog-sidebar-search-box position-relative">
                                    <div class="input-group">
                                        <input class="form-control border-0 searchBlog" type="search" placeholder="{{ __('Search...') }}">
                                        <button class="bg-transparent border-0"><span class="iconify" data-icon="akar-icons:search"></span></button>
                                    </div>

                                    <!-- Search Bar Suggestion Box Start -->
                                    <div class="search-bar-suggestion-box searchBlogBox d-none custom-scrollbar">
                                        <ul class="appendBlogSearchList">

                                        </ul>
                                    </div>
                                    <!-- Search Bar Suggestion Box End -->

                                </form>
                            </div>

                            <div class="blog-sidebar-box">
                                <h6 class="blog-sidebar-box-title">{{ __('Recent Blogs') }}</h6>
                                <ul class="popular-posts">
                                    @foreach($recentBlogs as $recentBlog)
                                        <li>
                                            <div class="sidebar-blog-item d-flex">
                                                <div class="flex-shrink-0">
                                                    <div class="sidebar-blog-item-img-wrap overflow-hidden">
                                                        <a href="{{ route('blog-details', $recentBlog->slug) }}">
                                                            <img src="{{ getImageFile($recentBlog->image_path) }}" alt="img" class="img-fluid"></a>
                                                    </div>
                                                </div>
                                                <div class="flex-grow-1 {{selectedLanguage()->rtl == 1 ? 'me-3' : 'ms-3' }}">
                                                    <h6 class="sidebar-blog-item-title"><a href="{{ route('blog-details', $recentBlog->slug) }}">{{ __(@$recentBlog->title) }}</a></h6>
                                                    <p class="blog-author-name-publish-date font-12 font-medium color-gray mb-0">{{ $recentBlog->created_at->format(' j  M, Y')  }}</p>
                                                </div>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>

                            <div class="blog-sidebar-box">
                                <h6 class="blog-sidebar-box-title">{{ __('Categories') }}</h6>
                                <ul class="blog-sidebar-categories">
                                    @foreach($blogCategories as $blogCategory)
                                        <li><a href="{{ route('categoryBlogs', $blogCategory->slug) }}" class="font-15">{{ $blogCategory->name }} ({{ $blogCategory->blogs_count }})</a></li>
                                    @endforeach
                                </ul>
                            </div>

                            <div class="blog-sidebar-box">
                                <h6 class="blog-sidebar-box-title">{{ __('Tags') }}</h6>
                                <ul class="blog-sidebar-tags">
                                    @foreach($tags as $tag)
                                        <li><a href="#">{{ $tag->name }}</a></li>
                                    @endforeach
                                </ul>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- Course Single Details Area End -->

    </div>

    <!-- Comment Reply Modal Start -->
    <div class="modal fade" id="commentReplyModal" tabindex="-1" aria-labelledby="writeReviewModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="writeReviewModalLabel">{{ __('Comment Reply') }}</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('blog-comment-reply.store') }}" method="post">
                    @csrf
                    <input type="hidden" name="parent_id" class="parent_id">
                    <input type="hidden" name="blog_id" class="blog_id" value="{{ $blog->id }}">
                    <input type="hidden" name="user_id" class="user_id" value="{{ @Auth::user()->id }}">
                    <div class="modal-body">
                        <!-- Leave a Comment Area-->

                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group para-color font-14 color-gray mb-30">
                                    <input type="text" name="name" class="form-control" required value="{{ @Auth::user()->name }}" readonly>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group para-color font-14 color-gray mb-30">
                                    <input type="email" name="email" class="form-control" id="cus_email" required value="{{ @Auth::user()->email }}" readonly>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group para-color font-14 color-gray nessage-text mb-30">
                                    <textarea name="comment" rows="3" class="form-control" id="cus_comment" required placeholder="{{ __('Your Comments *') }}"></textarea>
                                </div>
                            </div>
                        </div>
                        <!-- Leave a Comment Area-->
                    </div>
                    <div class="modal-footer d-flex justify-content-between align-items-center">
                        <button type="button" class="theme-btn theme-button3 modal-back-btn" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                        <button type="submit" class="theme-btn theme-button1 default-hover-btn">{{ __('Submit Review') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Comment Reply Modal End -->
    <input type="hidden" class="searchBlogRoute" value="{{ route('search-blog.list') }}">


    <input type="hidden" class="blog_id" value="{{ @$blog->id }}">
    <input type="hidden" class="user_id" value="{{ @Auth::user()->id }}">
    <input type="hidden" class="user_name" value="{{ @Auth::user()->name }}">
    <input type="hidden" class="user_email" value="{{ @Auth::user()->email }}">
    <input type="hidden" class="blogCommentStoreRoute" value="{{ route('blog-comment.store') }}">
@endsection

@push('script')
    <!-- Start:: Blog Search & Blog Comment and reply-->
    <script src="{{ asset('frontend/assets/js/custom/search-blog-list.js') }}"></script>
    <script src="{{ asset('frontend/assets/js/custom/blog-comment-reply.js') }}"></script>
    <!-- End:: Blog Search & Blog Comment and reply-->
@endpush
