@extends('frontend.layouts.app')
@section('content')
<div class="bg-page">
    <!-- Consultation Page Header Start -->
    <header class="page-banner-header gradient-bg position-relative">
        <div class="section-overlay">
            <div class="container">
                <div class="row">
                    <div class="col-12 col-sm-12 col-md-12">
                        <div class="page-banner-content text-center">
                            <h3 class="page-banner-heading text-white pb-15">{{ __('Subscription Panel') }}</h3>
                            <!-- Breadcrumb Start-->
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb justify-content-center">
                                    <li class="breadcrumb-item font-14"><a href="{{ url('/') }}">{{ __('Home') }}</a>
                                    </li>
                                    <li class="breadcrumb-item font-14 active" aria-current="page">{{ __('Subscription
                                        Panel') }}
                                    </li>
                                </ol>
                            </nav>
                            <!-- Breadcrumb End-->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>
    <!-- Consultation Page Header End -->

    @include('frontend.home.partial.subscription-home-list')

</div>
@endsection