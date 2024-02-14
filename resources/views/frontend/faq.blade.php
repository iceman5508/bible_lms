@extends('frontend.layouts.app')
@section('meta')
    @php
        $metaData = getMeta('faq');
    @endphp

    <meta name="description" content="{{ __($metaData['meta_description']) }}">
    <meta name="keywords" content="{{ __($metaData['meta_keyword']) }}">

    <!-- Open Graph meta tags for social sharing -->
    <meta property="og:type" content="Learning">
    <meta property="og:title" content="{{ __($metaData['meta_title']) }}">
    <meta property="og:description" content="{{ __($metaData['meta_description']) }}">
    <meta property="og:image" content="{{ __($metaData['og_image']) }}">
    <meta property="og:url" content="{{ url()->current() }}">

    <meta property="og:site_name" content="{{ __(get_option('app_name')) }}">

    <!-- Twitter Card meta tags for Twitter sharing -->
    <meta name="twitter:card" content="Learning">
    <meta name="twitter:title" content="{{ __($metaData['meta_title']) }}">
    <meta name="twitter:description" content="{{ __($metaData['meta_description']) }}">
    <meta name="twitter:image" content="{{ __($metaData['og_image']) }}">
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
                            <h3 class="page-banner-heading text-white pb-15">{{__('FAQ')}}</h3>

                            <!-- Breadcrumb Start-->
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb justify-content-center">
                                    <li class="breadcrumb-item font-14"><a href="{{ url('/') }}">{{__('Home')}}</a></li>
                                    <li class="breadcrumb-item font-14 active" aria-current="page">{{__('FAQ')}}</li>
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
    <section class="faq-area faq-page-area section-t-space">
        <div class="container">
            <div class="row">
                <div class="section-title">
                    <h3 class="section-heading">{{ __(get_option('faq_title')) }}</h3>
                    <p class="section-sub-heading">{{ __(get_option('faq_subtitle')) }}</p>
                </div>
            </div>

            <!-- Tab panel nav list -->
            <div class="faq-tab-nav-wrap d-flex justify-content-between">
                <ul class="nav nav-tabs tab-nav-list border-0 row" id="myTab" role="tablist">

                    <li class="nav-item col-md-3" role="presentation">
                        <a class="nav-link text-center active" id="Support-tab" data-bs-toggle="tab" href="#Support" role="tab" aria-controls="Support" aria-selected="true">
                            <!-- faq tab nav Item start-->
                            <div class="faq-tab-nav-item bg-white">
                                <h6>{{ Str::limit(get_option('faq_tab_first_title'), 22) }}</h6>
                                <div class="faq-tab-nav-img-wrap">
                                    <img src="{{ asset('frontend/assets/img/icons-svg/faq-page-tab-icon.png') }}" alt="img">
                                </div>
                                <p>{{ Str::limit(get_option('faq_tab_first_subtitle'), 80) }}</p>
                            </div>
                            <!-- faq tab nav Item End-->
                        </a>
                    </li>
                    <li class="nav-item col-md-3" role="presentation">
                        <a class="nav-link" id="Licensing-tab" data-bs-toggle="tab" href="#Licensing" role="tab" aria-controls="Licensing" aria-selected="false">
                            <!-- faq tab nav Item start-->
                            <div class="">
                                <div class="faq-tab-nav-item bg-white">
                                    <h6>{{ Str::limit(get_option('faq_tab_sec_title'), 22) }}</h6>
                                    <div class="faq-tab-nav-img-wrap">
                                        <img src="{{ asset('frontend/assets/img/icons-svg/faq-page-tab-icon.png') }}" alt="img">
                                    </div>
                                    <p>{{ Str::limit(get_option('faq_tab_sec_subtitle'), 80) }}</p>
                                </div>
                            </div>
                            <!-- faq tab nav Item End-->
                        </a>
                    </li>
                    <li class="nav-item col-md-3" role="presentation">
                        <a class="nav-link" id="Account-tab" data-bs-toggle="tab" href="#Account" role="tab" aria-controls="Account" aria-selected="false">
                            <!-- faq tab nav Item start-->
                            <div class="">
                                <div class="faq-tab-nav-item bg-white">
                                    <h6>{{ Str::limit(get_option('faq_tab_third_title'), 22) }}</h6>
                                    <div class="faq-tab-nav-img-wrap">
                                        <img src="{{ asset('frontend/assets/img/icons-svg/faq-page-tab-icon.png') }}" alt="img">
                                    </div>
                                    <p>{{ Str::limit(get_option('faq_tab_third_subtitle'), 80) }}</p>
                                </div>
                            </div>
                            <!-- faq tab nav Item End-->
                        </a>
                    </li>
                    <li class="nav-item col-md-3" role="presentation">
                        <a class="nav-link" id="Tax-tab" data-bs-toggle="tab" href="#Tax" role="tab" aria-controls="Tax" aria-selected="false">
                            <!-- faq tab nav Item start-->
                            <div class="">
                                <div class="faq-tab-nav-item bg-white">
                                    <h6>{{ Str::limit(get_option('faq_tab_four_title'), 22) }}</h6>
                                    <div class="faq-tab-nav-img-wrap">
                                        <img src="{{ asset('frontend/assets/img/icons-svg/faq-page-tab-icon.png') }}" alt="img">
                                    </div>
                                    <p>{{ Str::limit(get_option('faq_tab_four_subtitle'), 80) }}</p>
                                </div>
                            </div>
                            <!-- faq tab nav Item End-->
                        </a>
                    </li>
                </ul>
            </div>
            <!-- Tab panel nav list -->

            <div class="row align-items-center">
                <div class="col-md-12">
                    <div class="accordion" id="accordionExample">
                        @php $count = true @endphp
                        @foreach($faqs as $key => $faqQuestion)
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="heading{{ $faqQuestion->id }}">
                                    <button class="accordion-button font-medium font-18 {{ $count ? null : 'collapsed' }}" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $faqQuestion->id }}"
                                            aria-expanded="{{ $count ? 'true' : 'false' }}" aria-controls="collapse{{ $faqQuestion->id }}">
                                        {{ $key+1 }}. {{ __($faqQuestion->question) }}

                                    </button>
                                </h2>
                                <div id="collapse{{ $faqQuestion->id }}" class="accordion-collapse collapse {{ $count ? 'show' : null }}" aria-labelledby="heading{{ $faqQuestion->id }}" data-bs-parent="#accordionExample">
                                    <div class="accordion-body">
                                        {{ __($faqQuestion->answer) }}
                                    </div>
                                </div>
                            </div>
                            @php $count = false @endphp
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Tab Content-->

        </div>
    </section>
    <!-- FAQ Area End -->
</div>

@endsection
