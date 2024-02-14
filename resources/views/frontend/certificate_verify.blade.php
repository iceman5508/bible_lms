@extends('frontend.layouts.app')
@section('meta')
    @php
        $metaData = getMeta('verify_certificate');
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
    <!-- Page Header Start -->
    <header class="page-banner-header gradient-bg position-relative">
        <div class="section-overlay">
            <div class="container">
                <div class="row">
                    <div class="col-12 col-sm-12 col-md-12">
                        <div class="page-banner-content text-center">
                            <h3 class="page-banner-heading text-white pb-15">{{ __('Validate Certificate') }}</h3>

                            <!-- Breadcrumb Start-->
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb justify-content-center">
                                    <li class="breadcrumb-item font-14"><a href="{{ url('/') }}">{{__('Home')}}</a></li>
                                    <li class="breadcrumb-item font-14 active" aria-current="page">{{ __('Validate
                                        Certificate') }}</li>
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

    <!-- Certificate Verification Page Area Start -->
    <section class="certificate-verification-page-area section-t-space">
        <div class="container">
            <div class="certificate-verification-wrap bg-white p-30 radius-4">

                <div class="certificate-verify-top">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <div class="certificate-verify-top-left">
                                <img src="{{ asset('frontend/assets/img/verify-certificate-img.jpg') }}" alt="" class="img-fluid">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="certificate-check-form">
                                <div class="form-vertical__item bg-style">
                                    <div class="item-top mb-3">
                                        <h3 class="font-24">{{__('Certificate Varification')}}</h3>
                                        <p class="pt-3">To validate certificates please enter the certificate id in this input field
                                            and click on validate button.</p>
                                    </div>
                                    <form id="certificate-form">
                                        @csrf
                                        <div class="input__group mb-25">
                                            <label class="label-text-title color-heading" for="certificate_number"> {{__('Certificate ID')}} </label>
                                            <input type="text" name="certificate_number" class="form-control flat-input" placeholder=" {{__('Enter certificate ID')}} ">
                                        </div>
                                        <div class="input__group mb-25">
                                            <button type="submit" id="check-certificate" class="theme-btn default-hover-btn theme-button1">Verify Now</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="certificate-verify-result-box" id="verified-data">
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

@push('script')
<script>
    $(document).on('submit', '#certificate-form', function(e){
        e.preventDefault();

        var form = $('#certificate-form');

        $.ajax({
            type: 'GET',
            url: "{{ route('verify_certificate') }}",
            data: $(form).serialize(),
            dataType: 'json',
            success: function(response){
                $('#verified-data').html(response.data);
            }
        });
    });
</script>
@endpush
