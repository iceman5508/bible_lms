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
                                            <li class="breadcrumb-item font-14"><a href="{{ route('main.index') }}"><span class="iconify" data-icon="bx:home-alt"></span> {{ __('Home') }}</a>
                                            </li>
                                            <li class="breadcrumb-item font-14" aria-current="page"><a href="{{ route('forum.index') }}">{{ __('Forum') }}</a></li>
                                            <li class="breadcrumb-item font-14 active" aria-current="page">{{ __($forumCategory->title) }}</li>
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

        <!-- Ask a question area start -->
        <section class="forum-categories-area section-b-space">
            <div class="container">
                <div class="row">
                    <!-- Forum Categories Left Start -->
                    <div class="col-12 col-md-12 col-xl-8">
                        <div class="forum-categories-left">
                            <h3 class="mb-4 forumCategoryTitle">{{ __($forumCategory->title) }}</h3>

                            <div class="forum-categories-filter-box d-flex align-items-center">
                                <select id="inputState" class="form-select color-heading forumCategory">
                                    <option value="">{{ __('All Category') }}</option>
                                    @foreach($forumCategories as $category)
                                        <option value="{{ $category->id }}" @if($forumCategory->id == $category->id) selected @endif>{{ $category->title }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="forum-categories-wrap appendForumCategoryPosts">
                                @include('frontend.forum.partial.render-forum-posts')
                            </div>

                        </div>
                    </div>
                    <!-- Forum Categories Left End -->

                    <!-- Forum Categories Right Start -->
                    <div class="col-12 col-md-12 col-xl-4">
                        <div class="forum-categories-right">
                            <a href="{{ route('forum.askQuestion') }}" class="w-100 theme-btn theme-button1 theme-button3 forum-ask-question-btn">{{ __('Ask a Question') }}</a>
                            <ul class="forum-link-box radius-4 border-1 mt-4">
                                <li class="forum-link-box-title font-20 color-heading font-medium"><span class="iconify me-2" data-icon="bi:star"></span>{{ __('Top Contributors') }}</li>
                                @foreach($topContributors as $topContributor)
                                    <li>
                                        <div class="forum-author-item d-flex align-items-center justify-content-between">
                                            <div class="forum-author-item-left">
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-shrink-0">
                                                        <a href="#" class="forum-author-img-wrap radius-50 overflow-hidden">
                                                            <img src="{{ getImageFile($topContributor->image_path) }}" alt="">
                                                        </a>
                                                    </div>
                                                    <a href="#" class="flex-grow-1 mx-2 font-18 font-medium color-heading forum-author-name">
                                                        @if(@$topContributor->role == 1)
                                                            {{ $topContributor->name }}

                                                        @elseif(@$topContributor->role == 2)
                                                            {{ $topContributor->instructor->name }}

                                                        @elseif(@$topContributor->role == 3)
                                                            {{ $topContributor->student->name }}
                                                        @endif
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="author-item-right d-flex align-items-center">
                                                <span class="iconify" data-icon="bi:star"></span><span>{{ $topContributor->totalComments }}</span>
                                            </div>
                                        </div>
                                    </li>
                                @endforeach
                                <li class="forum-link-box-title font-18 color-heading font-medium text-center">
                                    <a href="{{ route('forum.forumLeaderboard') }}">View All <i data-feather="arrow-right"></i></a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <!-- Forum Categories Right End -->
                </div>
            </div>
        </section>
        <!-- Ask a question area end -->

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
                                        <div
                                            class="blog-item-tag position-absolute font-12 font-semi-bold text-white bg-hover radius-3">{{ __(@$blog->category->name) }}</div>
                                    </div>

                                    <div class="blog-item-bottom-part">
                                        <h3 class="card-title blog-title"><a href="{{ route('blog-details', $blog->slug) }}">{{ Str::limit($blog->title, 50) }}</a></h3>
                                        <p class="blog-author-name-publish-date font-13 font-medium color-gray">{{ $blog->user->name }}
                                            / {{ $blog->created_at->format(' j  M, Y')  }}</p>
                                        <p class="card-text blog-content">{!!  Str::limit($blog->details, 200) !!}</p>

                                        <div class="blog-read-more-btn">
                                            <a href="{{ route('blog-details', $blog->slug) }}"
                                               class="theme-btn font-15 ps-0 font-medium color-hover">{{ __('Read More') }} <i
                                                    data-feather="arrow-right"></i></a>
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
    <input type="hidden" class="renderForumCategoryPostsRoute" value="{{ route('forum.renderForumCategoryPosts') }}">
@endsection

@push('script')

    <script>
        'use strict'

        $(document).on('change', '.forumCategory', function () {
            var forum_category_id = this.value;
            var renderForumCategoryPostsRoute = $('.renderForumCategoryPostsRoute').val();
            $.ajax({
                type: "GET",
                url: renderForumCategoryPostsRoute,
                data: {"forum_category_id": forum_category_id,},
                datatype: "json",
                success: function (response) {
                    $('.appendForumCategoryPosts').html(response)
                    var title = $('.getForumCategoryName').val();
                    if (title) {
                        $('.forumCategoryTitle').html(title);
                    } else {
                        $('.forumCategoryTitle').html('All Categories');
                    }

                }
            });
        });
    </script>
@endpush
