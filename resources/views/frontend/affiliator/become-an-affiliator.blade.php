@extends('frontend.layouts.app')

@section('content')

<div class="bg-page">

<!-- Page Header Start -->
<header class="page-banner-header blank-page-banner-header gradient-bg position-relative">
    <div class="section-overlay">
        <div class="blank-page-banner-wrap">
            <div class="container">
                <div class="row">
                    <div class="col-12 col-sm-12 col-md-12">
                        <div class="page-banner-content text-center">
                            <h3 class="page-banner-heading color-heading pb-15">{{ __(@$title) }}</h3>

                            <!-- Breadcrumb Start-->
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb justify-content-center">
                                    <li class="breadcrumb-item font-14"><a href="{{ url('/') }}">{{ __('Home') }}</a></li>
                                    <li class="breadcrumb-item font-14 active" aria-current="page">{{ __(@$title) }}</li>
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
<!-- Page Header End -->

<!-- Wishlist Page Area Start -->
<section class="wishlist-page-area">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12 col-lg-10">
                <div class="affiliator-dashboard-wrap bg-white">
                    <div class="affiliator-dashboard-title d-flex align-items-center justify-content-between border-bottom mb-30 pb-20">
                        <h5>{{ __('Affilaiator Application Form') }}</h5>
                    </div>

                    <form enctype="multipart/form-data" action="{{route('affiliate.create-affiliate-request')}}"  method="post">
                        @csrf
                        <div class="row mb-30">
                            <div class="col-md-12">
                                <label class="label-text-title color-heading font-medium font-16 mb-2">{{ __('Name') }}</label>
                                <input type="text" name="name" value="{{Auth::user()->name}}" readonly class="form-control" id="name" placeholder="{{ __('Write your first name') }}" required="">
                            </div>
                        </div>

                        <div class="row mb-30">
                            <div class="col-md-12">
                                <label class="label-text-title color-heading font-medium font-16 mb-2">{{ __('Email address') }}</label>
                                <input type="email" name="email" value="{{Auth::user()->email}}" readonly class="form-control" id="email_address" placeholder="{{ __('Write your email') }}" required="">
                            </div>
                        </div>

                        <div class="row mb-30">
                            <div class="col-md-12">
                                <label class="label-text-title color-heading font-medium font-16 mb-2">{{ __('Address') }}</label>
                                <input type="text" name="address" class="form-control" id="address" placeholder="{{ __('Address') }}" required="">
                            </div>
                        </div>

{{--                        <div class="row mb-30">--}}
{{--                            <div class="col-md-12">--}}
{{--                                <label class="label-text-title color-heading font-medium font-16 mb-2">Phone Number</label>--}}
{{--                                <input type="text" name="phone_number" class="form-control" id="phone_number" placeholder="Phone Number" required="">--}}
{{--                            </div>--}}
{{--                        </div>--}}

                        <div class="row mb-30">
                            <div class="col-md-12">
                                <label class="label-text-title color-heading font-medium font-16 mb-2">{{ __('Cover Letter') }}</label>
                                <textarea name="letter" class="form-control" cols="30" rows="5" placeholder="{{ __('Your message') }}" required=""></textarea>
                            </div>
                        </div>

{{--                        <div class="row mb-30">--}}
{{--                            <div class="col-md-12">--}}
{{--                                <label class="label-text-title color-heading font-medium font-16 mb-2">Document</label>--}}
{{--                                <div class="create-assignment-upload-files">--}}
{{--                                    <input type="file" name="cv_file" accept="application/pdf" class="form-control">--}}
{{--                                    <p class="font-14 color-heading text-center mt-2 color-gray">If any document you want to share ( .pdf, .png, .jpg, jpeg ) Are accepted</p>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}

                        <div class="d-flex justify-content-center align-items-center">
                            <button type="submit" class="theme-btn theme-button1 default-hover-btn">{{ __('Apply Now') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Wishlist Page Area End -->

</div>

@endsection
