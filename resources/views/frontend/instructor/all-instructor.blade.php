@extends('frontend.layouts.app')

@section('content')
<!-- Page Header Start -->
<header class="page-banner-header gradient-bg position-relative">
    <div class="section-overlay">
        <div class="container">
            <div class="row">
                <div class="col-12 col-sm-12 col-md-12">
                    <div class="page-banner-content text-center">
                        <h3 class="page-banner-heading text-white pb-15">{{ __('All Instructor') }}</h3>

                        <!-- Breadcrumb Start-->
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb justify-content-center">
                                <li class="breadcrumb-item font-14"><a href="{{ url('/') }}">{{ __('Home') }}</a></li>
                                <li class="breadcrumb-item font-14 active" aria-current="page">{{ __('All Instructor') }}</li>
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
<!-- Our Top Instructor Area Start -->
<section class="top-instructor-area section-t-space bg-white">
    <div class="container">
        <div class="row top-instructor-content-wrap">
            @forelse($instructors as $instructorUser)
            <!-- Single Instructor Item start-->
            <div class="col-md-6 col-lg-6 col-xl-3 mt-0 mb-25">
                <x-frontend.instructor :user="$instructorUser" :type=INSTRUCTOR_CARD_TYPE_ONE />
            </div>
            <!-- Single Instructor Item End-->
            @empty
                <div class="no-course-found text-center">
                    <img src="{{ asset('frontend/assets/img/empty-data-img.png') }}" alt="img" class="img-fluid">
                    <h5 class="mt-3">{{ __('No Instructor Found') }}</h5>
                </div>
            @endforelse
        </div>
        <!-- Pagination Start -->
        @if(@$instructors->hasPages())
            {{ @$instructors->links('frontend.paginate.paginate') }}
        @endif
        <!-- Pagination End -->
    </div>
</section>
<!-- Our Top Instructor Area End -->

@endsection
