@extends('frontend.layouts.app')
@section('meta')
    <meta name="description" content="{{ __(@$page->meta_description) }}">
    <meta name="keywords" content="{{ __(@$page->meta_keywords) }}">

    <!-- Open Graph meta tags for social sharing -->
    <meta property="og:type" content="Learning">
    <meta property="og:title" content="{{ __(@$page->meta_title) }}">
    <meta property="og:description" content="{{ __(@$page->meta_description) }}">
    <meta property="og:image" content="{{ getImageFile(@$page->og_image) }}">
    <meta property="og:url" content="{{ url()->current() }}">

    <meta property="og:site_name" content="{{ __(get_option('app_name')) }}">

    <!-- Twitter Card meta tags for Twitter sharing -->
    <meta name="twitter:card" content="Learning">
    <meta name="twitter:title" content="{{ __(@$page->meta_title) }}">
    <meta name="twitter:description" content="{{ __(@$page->meta_description) }}">
    <meta name="twitter:image" content="{{ getImageFile(@$page->og_image) }}">
@endsection
@section('content')

    <div class="bg-page">
        <!-- Course Single Page Header Start -->
        <header class="page-banner-header gradient-bg position-relative">
            <div class="section-overlay">
                <div class="container">
                    <div class="row">
                        <div class="col-12 col-sm-12 col-md-12">
                            <div class="page-banner-content text-center">
                                <h3 class="page-banner-heading text-white pb-15">{{ __(@$page->en_title) }}</h3>

                                <!-- Breadcrumb Start-->
                                <nav aria-label="breadcrumb">
                                    <ol class="breadcrumb justify-content-center">
                                        <li class="breadcrumb-item font-14"><a href="{{ url('/') }}">{{ __('Home') }}</a></li>
                                        <li class="breadcrumb-item font-14 active" aria-current="page">{{ __(@$page->en_title) }}</li>
                                    </ol>
                                </nav>
                                <!-- Breadcrumb End-->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>
        <!-- Course Single Page Header End -->

        <!-- FAQ Area Start -->
        <section class="faq-area support-tickets-page section-t-space">
            <div class="container">
                <!-- Tab Content-->
                <div class="row align-items-center">
                    <div class="col-md-12">
                        {!! @$page->en_description !!}
                    </div>
                </div>

            </div>
        </section>
        <!-- FAQ Area End -->
    </div>

@endsection
