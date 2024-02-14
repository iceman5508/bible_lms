@extends('frontend.layouts.app')

@section('content')
<div class="bg-page">
<!-- Page Header Start -->
<header class="page-banner-header blank-page-banner-header gradient-bg position-relative">
    <div class="section-overlay">
        <div class="blank-page-banner-wrap pb-0 min-h-auto">
        </div>
    </div>
</header>
<!-- Page Header End -->

<!-- Cart Page Area Start -->
<section class="thankyou-page-area">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12 col-lg-7">
                <div class="thankyou-box text-center bg-white px-5 py-5">
                    <img src="{{ asset('frontend/assets/img/thank-you-img.png') }}" alt="img" class="img-fluid">
                    <h5 class="mt-5">{{ __('Thank you for recharge') }}</h5>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Cart Page Area End -->
</div>
@endsection
