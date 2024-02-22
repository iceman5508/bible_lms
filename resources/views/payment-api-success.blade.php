<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="author" content="{{ get_option('app_copyright') }}">
    <meta name="robots" content="index, follow">
    <meta name="csrf-token" content="{{ csrf_token() }}" >
    <meta name="msapplication-TileImage" content="{{ getImageFile(get_option('app_logo')) }}">
    <meta name="msapplication-TileColor" content="rgba(103, 20, 222,.55)">
    <meta name="theme-color" content="#754FFE">

    @php
        $metaData = getMeta('auth');
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

    <title>{{ get_option('app_name') }} - {{ __(@$title) }}</title>

    <!--=======================================
      All Css Style link
    ===========================================-->

    <!-- Bootstrap core CSS -->
    <link href="{{asset('frontend/assets/vendor/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet">

    <link href="{{asset('frontend/assets/css/jquery-ui.min.css')}}" rel="stylesheet">

    <!-- Font Awesome for this template -->
    <link href="{{asset('frontend/assets/vendor/fontawesome-free/css/all.min.css')}}" rel="stylesheet" type="text/css">

    <!-- Custom fonts for this template -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    @if(get_option('app_font_design_type') == 2)
        @if(empty(get_option('app_font_link')))
            <link href="https://fonts.googleapis.com/css2?family=Jost:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
        @else
            {!! get_option('app_font_link') !!}
        @endif
    @else
        <link href="https://fonts.googleapis.com/css2?family=Jost:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    @endif
    <link rel="stylesheet" href="{{asset('frontend/assets/fonts/feather/feather.css')}}">

    <!-- Animate Css-->
    <link rel="stylesheet" href="{{asset('frontend/assets/css/animate.min.css')}}">

    <link rel="stylesheet" href="{{asset('frontend/assets/css/owl.carousel.min.css')}}">
    <link rel="stylesheet" href="{{asset('frontend/assets/css/owl.theme.default.min.css')}}">

    <link rel="stylesheet" href="{{asset('frontend/assets/css/venobox.min.css')}}">
    @include('frontend.layouts.dynamic-style')
    <!-- Custom styles for this template -->
    <link href="{{asset('frontend/assets/css/style.css')}}" rel="stylesheet">
    <link href="{{ asset('frontend/assets/css/extra.css') }}" rel="stylesheet">

    <!-- Responsive Css-->
    <link rel="stylesheet" href="{{asset('frontend/assets/css/responsive.css')}}">

    <!-- FAVICONS -->
    <link rel="icon" href="{{ getImageFile(get_option('app_fav_icon')) }}" type="image/png" sizes="16x16">
    <link rel="shortcut icon" href="{{ getImageFile(get_option('app_fav_icon')) }}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ getImageFile(get_option('app_fav_icon')) }}">

    <link rel="apple-touch-icon-precomposed" type="image/x-icon" href="{{asset('frontend/assets/img/apple-icon-72x72.png')}}" sizes="72x72" >
    <link rel="apple-touch-icon-precomposed" type="image/x-icon" href="{{asset('frontend/assets/img/apple-icon-114x114.png')}}" sizes="114x114" >
    <link rel="apple-touch-icon-precomposed" type="image/x-icon" href="{{asset('frontend/assets/img/apple-icon-144x144.png')}}" sizes="144x144" >
    <link rel="apple-touch-icon-precomposed" type="image/x-icon" href="{{asset('frontend/assets/img/favicon-16x16.png')}}" >

</head>

<body class="{{selectedLanguage()->rtl == 1 ? 'direction-rtl' : 'direction-ltr' }}">

@if(get_option('allow_preloader') == 1)
    <!-- Pre Loader Area start -->
    <div id="preloader">
        <div id="preloader_status"><img src="{{getImageFile(get_option('app_preloader'))}}" alt="img" /></div>
    </div>
    <!-- Pre Loader Area End -->
@endif

<div class="bg-page">
    <!-- Cart Page Area Start -->
    <section class="align-items-center pb-0 row thankyou-page-area vh-100">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-12 col-lg-7">
                    <div class="thankyou-course-list-area mt-30">
                        <div class="row align-items-center">
                            <div class="col-md-12">
                                @if ($status)
                                <div class="bg-white p-5 text-center thankyou-box">
                                    <h5 class="mt-5">{{ __('Thank you for Purchasing') }}</h5>
                                    <img src="{{ asset('frontend/assets/img/thank-you-img.png') }}" alt="img"
                                    class="img-fluid">
                                    <h6 class="mt-5">{{ $message }}</h6>
                                </div>
                                @else
                                <div class="bg-white p-5 text-center thankyou-box">
                                    <img src="{{ asset('frontend/assets/img/certificate-verify-faield.png') }}"
                                        alt="img" class="img-fluid">
                                    <h6 class="mt-5">{{ $message }}</h6>
                                </div>
                                @endif

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Cart Page Area End -->
</div>


<!--=======================================
    All Jquery Script link
===========================================-->
<!-- Bootstrap core JavaScript -->
<script src="{{asset('frontend/assets/vendor/jquery/jquery-3.6.0.min.js')}}"></script>
<script src="{{asset('frontend/assets/vendor/jquery/popper.min.js')}}"></script>
<script src="{{asset('frontend/assets/vendor/bootstrap/js/bootstrap.min.js')}}"></script>

<script src="{{asset('frontend/assets/js/frontend-custom.js')}}"></script>

<script>
    setTimeout(() => {
        alert("{{ __("Please Close this window") }}");
    }, 5000)
</script>
</body>

</html>
