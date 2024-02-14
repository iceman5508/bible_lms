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
                                            <li class="breadcrumb-item font-14 active" aria-current="page">{{ __('Leaderboard') }}
                                            </li>
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
        <section class="forum-likes-leaderboard-area section-b-space">
            <div class="container">
                <div class="row">
                    <!-- Forum Likes Tab Area Start -->
                    <div class="col-12 col-md-12">
                        <div class="forum-likes-tabs">
                            <div class="row">
                                        <div class="col-12"><h5 class="mb-25">{{ __('Most Commented Authors') }}</h5></div>
                                        @foreach($topContributors as $topContributor)
                                        <!-- Forum Author Item Start-->
                                        <div class="col-md-6 col-lg-6 col-xl-4">
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
                                        </div>
                                        <!-- Forum Author Item End-->
                                        @endforeach

                                        <!-- Pagination Start -->
                                        <div class="col-12">
                                            @if(@$topContributors->hasPages())
                                                {{ @$topContributors->links('frontend.paginate.paginate') }}
                                            @endif
                                        </div>
                                        <!-- Pagination End -->
                                    </div>
                        </div>
                    </div>
                    <!-- Forum Likes Tab Area End -->
                </div>
            </div>
        </section>
        <!-- Ask a question area end -->
    </div>
@endsection
