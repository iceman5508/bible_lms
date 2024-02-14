@extends('frontend.layouts.app')
@section('meta')
    @php
        $metaData = getMeta('support_faq');
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
                                <h3 class="page-banner-heading text-white pb-15">{{__('Support Ticket')}}</h3>

                                <!-- Breadcrumb Start-->
                                <nav aria-label="breadcrumb">
                                    <ol class="breadcrumb justify-content-center">
                                        <li class="breadcrumb-item font-14"><a href="{{ url('/') }}">{{__('Home')}}</a></li>
                                        <li class="breadcrumb-item font-14 active" aria-current="page">{{__('Support Ticket')}}</li>
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
                <div class="row">
                    <div class="section-title text-center">
                        <h3 class="section-heading">{{ __(get_option('support_faq_title')) }}</h3>
                        <p class="section-sub-heading">{{ __(get_option('support_faq_subtitle')) }}</p>
                    </div>
                </div>


                <!-- Tab Content-->
                <div class="row align-items-center">
                    <div class="col-md-12">
                        <div class="accordion" id="accordionExample">
                            @php $count = true @endphp
                            @foreach($faqQuestions as $key => $faqQuestion)
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

                <div class="row">
                    <div class="col-12">
                        <div class="is-that-helpful-content text-center mt-30 pt-4">
                            <h5 class="mb-3">{{ __(get_option('ticket_title')) }}</h5>
                            <h4 class="mb-3">{{ __(get_option('ticket_subtitle')) }}</h4>
                            <div class="is-that-helpful-btns">
                                <a href="{{ route('student.support-ticket.create') }}" class="theme-btn theme-button1 default-hover-btn">{{ __('Create New Ticket') }}</a>
                                <a href="{{ route('student.support-ticket.create') }}" class="theme-btn theme-button1 default-hover-btn green-theme-btn">{{ __('View Ticket') }}</a>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </section>
        <!-- FAQ Area End -->
    </div>

@endsection
