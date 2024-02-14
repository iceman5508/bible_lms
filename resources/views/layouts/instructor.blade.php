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
        $defaultMeta = getMeta('default');
    @endphp

    <meta name="description" content="{{ $defaultMeta['meta_description'] }}">
    <meta name="keywords" content="{{ $defaultMeta['meta_keyword'] }}">
    
    <!-- Open Graph meta tags for social sharing -->
    <meta property="og:type" content="Learning">
    <meta property="og:title" content="{{ $defaultMeta['meta_title'] }}">
    <meta property="og:description" content="{{ $defaultMeta['meta_description'] }}">
    <meta property="og:image" content="{{ $defaultMeta['og_image'] }}">
    <meta property="og:url" content="{{ url()->current() }}">

    <meta property="og:site_name" content="{{ get_option('app_name') }}">
    
    <!-- Twitter Card meta tags for Twitter sharing -->
    <meta name="twitter:card" content="Learning">
    <meta name="twitter:title" content="{{ $defaultMeta['meta_title'] }}">
    <meta name="twitter:description" content="{{ $defaultMeta['meta_description'] }}">
    <meta name="twitter:image" content="{{ $defaultMeta['og_image'] }}">

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

    <!-- Sweet Alert css -->
    <link rel="stylesheet" href="{{asset('admin/sweetalert2/sweetalert2.css')}}">

    <!-- Custom styles for this template -->
    <link href="{{asset('frontend/assets/css/style.css')}}" rel="stylesheet">
    <link href="{{asset('frontend/assets/css/extra.css')}}" rel="stylesheet">

    <!-- Responsive Css-->
    <link rel="stylesheet" href="{{asset('frontend/assets/css/responsive.css')}}">

    <!-- FAVICONS -->
    <link rel="icon" href="{{ getImageFile(get_option('app_fav_icon')) }}" type="image/png" sizes="16x16">
    <link rel="shortcut icon" href="{{ getImageFile(get_option('app_fav_icon')) }}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ getImageFile(get_option('app_fav_icon')) }}">

    <link rel="apple-touch-icon-precomposed" type="image/x-icon" href="{{ getImageFile(get_option('app_fav_icon')) }}" sizes="72x72" >
    <link rel="apple-touch-icon-precomposed" type="image/x-icon" href="{{ getImageFile(get_option('app_fav_icon')) }}" sizes="114x114" >
    <link rel="apple-touch-icon-precomposed" type="image/x-icon" href="{{ getImageFile(get_option('app_fav_icon')) }}" sizes="144x144" >
    <link rel="apple-touch-icon-precomposed" type="image/x-icon" href="{{ getImageFile(get_option('app_fav_icon')) }}" >


        
    @if(isEnableOpenAI())
    <link rel="stylesheet" href="{{asset('addon/AI/css/main.css')}}">
    @endif

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    @stack('style')
    
    @if(!empty(get_option('certificate_regular')) && get_option('certificate_regular') != '' )
    <style>
        .certificateFont {
            @font-face {
                font-family: "certificateFont";
                url: {{ asset(get_option('certificate_font')) }},
            }
        }
    </style>
    @endif
    @toastr_css
    @include('frontend.layouts.dynamic-style')
    <script>
        function getLanguage(){
            return {
                "sEmptyTable": "{{ __('No data available in table') }}",
                "sInfo": "{{__('Showing _START_ To _END_ Of _TOTAL_ Entries')}}",
                "sInfoEmpty": "{{__('Showing 0 to 0 of 0 entries')}}",
                "sInfoFiltered": "{{__('(filtered from _MAX_ total entries)')}}",
                "sInfoPostFix": "",
                "sInfoThousands": ",",
                "sLengthMenu": "{{__('Show _MENU_ entries')}}",
                "sLoadingRecords": "{{__('Loading...')}}",
                "sProcessing": "{{__('Processing...')}}",
                "sSearch": "{{__('Search:')}}",
                "sZeroRecords": "{{__('No matching records found')}}",
                "oPaginate": {
                    "sFirst": "{{__('First')}}",
                    "sLast": "{{__('Last')}}",
                    "sNext": "{{__('Next')}}",
                    "sPrevious": "{{__('Previous')}}"
                },
                "oAria": {
                    "sSortAscending": ": {{__('activate to sort column ascending')}}",
                    "sSortDescending": ": {{__('activate to sort column descending')}}"
                }
            };
        }
    </script>
</head>


@php
    $selectedLanguage = selectedLanguage();
@endphp

<body class="bg-page {{$selectedLanguage->rtl == 1 ? 'direction-rtl' : 'direction-ltr' }} ">

@if(get_option('allow_preloader') == 1)
    <!-- Pre Loader Area start -->
     <div id="preloader">
        <div id="preloader_status"><img src="{{getImageFile(get_option('app_preloader'))}}" alt="img" /></div>
    </div>
    <!-- Pre Loader Area End -->
@endif

<!--Main Menu/Navbar Area Start -->
@include(getThemePath().'.layouts.navbar')
<!--Main Menu/Navbar Area Start -->

<!-- Page Header Start -->

<header class="page-banner-header gradient-bg position-relative">
    <div class="section-overlay">
        <div class="container">
            <div class="row">
                <div class="col-12 col-sm-12 col-md-12">
                    @yield('breadcrumb')
                </div>
            </div>
        </div>
    </div>
</header>

<!-- Page Header End -->

<!-- Instructor Dashboard Page Area Start -->
<section class="instructor-profile-page section-t-space">
    <div class="container">
        <div class="instructor-dashboard-page-content">
            <div class="row">
                <div class="col-12">
                    <div class="row instructor-dashboard-two-part-join bg-white radius-8">
                        <!-- Instructor Dashboard Left part -->
                        <div class="col-lg-3 instructor-profile-left-part-wrap p-0">
                            @include('instructor.common.left-menu')
                        </div>
                        <!-- Instructor Dashboard Right part -->
                        <div class="col-lg-9 p-0">
                            @yield('content')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Instructor Dashboard Page Area End -->

@yield('modal')

<!-- Footer Start -->
@include('frontend.layouts.footer')
<!-- Footer End -->

<script>
    var deleteTitle = '{{ __("Sure! You want to delete?") }}';
    var deleteText = '{{ __("You wont be able to revert this!") }}';
    var deleteConfirmButton = '{{ __("Yes, Delete It!") }}';
    var deleteSuccessText = '{{ __("Item has been deleted") }}';
</script>

<!--=======================================
    All Jquery Script link
===========================================-->
<!-- Bootstrap core JavaScript -->
<script src="{{asset('frontend/assets/vendor/jquery/jquery-3.6.0.min.js')}}"></script>
<script src="{{asset('frontend/assets/vendor/jquery/popper.min.js')}}"></script>
<script src="{{asset('frontend/assets/vendor/bootstrap/js/bootstrap.min.js')}}"></script>

<!-- ==== Plugin JavaScript ==== -->
<script src="{{asset('frontend/assets/vendor/jquery-easing/jquery.easing.min.js')}}"></script>

<script src="{{asset('frontend/assets/js/jquery-ui.min.js')}}"></script>

<!--WayPoints JS Script-->
<script src="{{asset('frontend/assets/js/waypoints.min.js')}}"></script>

<!--Counter Up JS Script-->
<script src="{{asset('frontend/assets/js/jquery.counterup.min.js')}}"></script>

<script src="{{asset('frontend/assets/js/owl.carousel.min.js')}}"></script>

<!-- Range Slider -->
<script src="{{asset('frontend/assets/js/price_range_script.js')}}"></script>

<!--Feather Icon-->
<script src="{{asset('frontend/assets/js/feather.min.js')}}"></script>

<!--Iconify Icon-->
<script src="{{ asset('common/js/iconify.min.js') }}"></script>

<!--Venobox-->
<script src="{{asset('frontend/assets/js/venobox.min.js')}}"></script>

<!-- Menu js -->
<script src="{{asset('frontend/assets/js/menu.js')}}"></script>

<!-- Custom scripts for this template -->
<script src="{{asset('frontend/assets/js/frontend-custom.js')}}"></script>

<script src="{{asset('admin/sweetalert2/sweetalert2.all.js')}}"></script>
<input type="hidden" id="base_url" value="{{url('/')}}">
<!-- Start:: Navbar Search  -->
<input type="hidden" class="search_route" value="{{ route('search-course.list') }}">
<script src="{{ asset('frontend/assets/js/custom/search-course.js') }}"></script>
<!-- End:: Navbar Search  -->

@if(isEnableOpenAI())
<script src="{{asset('addon/AI/js/main.js')}}"></script>
@endif


@stack('script')

@toastr_js
@toastr_render

@if (@$errors->any())
    <script>
        "use strict";
        @foreach ($errors->all() as $error)
            toastr.options.positionClass = 'toast-bottom-right';
            toastr.error("{{ $error }}")
        @endforeach
    </script>
@endif
</body>

</html>
