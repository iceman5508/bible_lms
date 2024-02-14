<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __(@$title) }}</title>

    <!-- Favicon included -->
    <link rel="shortcut icon" href="{{getImageFile(get_option('app_fav_icon'))}}" type="image/x-icon">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Apple touch icon included -->
    <link rel="apple-touch-icon" href="{{getImageFile(get_option('app_fav_icon'))}}">

    <link rel="stylesheet" href="{{asset('admin/sweetalert2/sweetalert2.css')}}">

    <!-- All CSS files included here -->
    <link rel="stylesheet" href="{{asset('admin/css/all.min.css')}}">
    <link rel="stylesheet" href="{{ asset('frontend/assets/vendor/bootstrap/css/bootstrap.min.css') }}">
    @stack('style')
    <link rel="stylesheet" href="{{asset('admin/css/metisMenu.min.css')}}">
    <link rel="stylesheet" href="{{asset('admin/styles/main.css')}}">
    <link rel="stylesheet" href="{{asset('admin/css/admin-extra.css')}}">
    <link href="{{asset('common/css/select2.css')}}" rel="stylesheet">

    @if(isEnableOpenAI())
    <link rel="stylesheet" href="{{asset('addon/AI/css/main.css')}}">
    @endif

    @toastr_css

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
    
</head>
<body class=" {{selectedLanguage()->rtl == 1 ? 'direction-rtl' : 'direction-ltr' }} ">

@if(get_option('allow_preloader') == 1)
    <!-- Pre Loader Area start -->
    <div id="preloader">
        <div id="preloader_status"><img src="{{getImageFile(get_option('app_preloader'))}}" alt="img" /></div>
    </div>
    <!-- Pre Loader Area End -->
@endif

<!-- Sidebar area start -->
@include('admin.common.sidebar')
<!-- Sidebar area end -->

<!-- Main Content area start -->
<div class="main-content">

    <!-- Header section start -->
    @include('admin.common.header')
    <!-- Header section end -->

    <!-- page content wrap start -->
    <div class="page-content-wrap">
        <!-- Page content area start -->
        @yield('content')
        <!-- Page content area end -->

        <!-- Footer section start -->
        @include('admin.common.footer')
        <!-- Footer section end -->
    </div>
    <!-- page content wrap end -->

    @if(isEnableOpenAI()) 
        @include('addon.AI.content-generation')
    @endif

</div>
<!-- Main Content area end -->
<script>
    var deleteTitle = '{{ __("Sure! You want to delete?") }}';
    var deleteText = '{{ __("You wont be able to revert this!") }}';
    var deleteConfirmButton = '{{ __("Yes, Delete It!") }}';
    var deleteSuccessText = '{{ __("Item has been deleted") }}';
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
<input type="hidden" id="base_url" value="{{url('/')}}">

<!-- All Javascript files included here -->
<script src="{{asset('frontend/assets/vendor/jquery/jquery-3.6.0.min.js')}}"></script>
<script src="{{asset('admin/js/popper.min.js')}}"></script>
<script src="{{ asset('frontend/assets/vendor/bootstrap/js/bootstrap.min.js') }}"></script>
<script src="{{asset('common/js/apexcharts.min.js')}}"></script>
<script src="{{asset('admin/sweetalert2/sweetalert2.all.js')}}"></script>
<script src="{{ asset('common/js/iconify.min.js') }}"></script>
@stack('script')
<script src="{{asset('admin/js/admin-custom.js')}}"></script>
<script src="{{asset('admin/js/metisMenu.min.js')}}"></script>
<script src="{{asset('admin/js/main.js')}}"></script>
<script src="{{asset('common/js/select2.min.js')}}"></script>
@if(isEnableOpenAI())
<script src="{{asset('addon/AI/js/main.js')}}"></script>
@endif


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
