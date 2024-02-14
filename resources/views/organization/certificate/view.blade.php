@extends('layouts.organization')

@section('breadcrumb')
<div class="page-banner-content text-center">
    <h3 class="page-banner-heading text-white pb-15"> {{__('View Certificate')}} </h3>

    <!-- Breadcrumb Start-->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb justify-content-center">
            <li class="breadcrumb-item font-14"><a href="{{route('organization.dashboard')}}">{{__('Dashboard')}}</a></li>
            <li class="breadcrumb-item font-14"><a href="{{ route('organization.certificate.index') }}">{{__('Manage
                    Certificate')}}</a></li>
            <li class="breadcrumb-item font-14 active" aria-current="page">{{__('View Certificate')}}</li>
        </ol>
    </nav>
</div>
@endsection

@section('content')
<div class="instructor-profile-right-part">
    <div class="instructor-quiz-list-page instructor-certificate-view-page">

        <div class="instructor-my-courses-title d-flex justify-content-between align-items-center">
            <h6>{{__('View Certificate')}}</h6>
        </div>

        <div class="row">
            <div class="col-12">
                <div id="certificate-preview-div">
                </div>
                <div style="overflow: hidden; height: 0;">
                    <div id="certificate-preview-div-hidden" style="width:1030px; height:734px; overflow:hidden">
                        @include('organization.certificate.preview')
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection

@push('style')
<link rel="stylesheet" href="{{asset('frontend/assets/css/for-certificate.css')}}">
<link rel="preload" href="{{asset('frontend/assets/fonts/mongolian_baiti/MongolianBaiti.woff2')}}" as="font"
    type="font/woff" crossorigin>
<link rel="preload" href="{{asset('frontend/assets/fonts/mongolian_baiti/MongolianBaiti.woff2')}}" as="font"
    type="font/woff2" crossorigin>
@endpush

@push('script')
<script src="{{ asset('frontend/assets/js/html2canvas.js') }}"></script>
<script>
    screenshot();
    function screenshot(){
        html2canvas(document.getElementById("certificate-preview-div-hidden")).then(function(canvas){
            $("#certificate-preview-div").html('<img class="img-fluid" src="'+canvas.toDataURL()+'" />');
        });
    }
</script>
@endpush
