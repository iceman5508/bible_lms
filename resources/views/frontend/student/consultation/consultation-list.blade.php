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
                                    <h3 class="page-banner-heading color-heading pb-15">{{ __('My Consultation') }}</h3>

                                    <!-- Breadcrumb Start-->
                                    <nav aria-label="breadcrumb">
                                        <ol class="breadcrumb justify-content-center">
                                            <li class="breadcrumb-item font-14"><a href="{{url('/')}}">{{__('Home')}}</a></li>
                                            <li class="breadcrumb-item font-14 active" aria-current="page">{{ __('My Consultation') }}</li>
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
        <section class="wishlist-page-area my-courses-page">
            <div class="container">
                <div class="row">
                    <!-- Courses Filter Bar Start-->
                    <div class="col-12">
                        <div class="courses-filter-bar d-flex align-items-start justify-content-between">
                            <div class="filter-bar-left">
                                <a href="{{ route('consultationInstructorList') }}" class="theme-btn theme-button1 theme-button3">{{__('Browse More Consultation')}}</a>
                            </div>

                            <div class="filter-bar-right">
                                <div class="filter-box d-flex align-items-center justify-content-end bg-white">
                                    <div class="filter-box-short-icon d-flex justify-content-start align-items-center"><img src="{{ asset('frontend/assets/img/icons-svg/filter-list-icon.png') }}" alt="short"> <p>{{ __('Sort By') }}:</p></div>
                                    <select class="form-select form-select-sm filterBy" >
                                        <option value="">{{__('Select')}}</option>
                                        <option value="-1">{{__('Pending')}}</option>
                                        <option value="1">{{__('Approve')}}</option>
                                        <option value="2">{{__('Cancel')}}</option>
                                        <option value="3">{{__('Completed')}}</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Courses Filter Bar End-->
                    <div class="appendConsultationList">
                    @include('frontend.student.consultation.render-consultation-list')
                    </div>
                </div>
            </div>
        </section>
        <!-- Wishlist Page Area End -->
    </div>

    <input type="hidden" class="consultationMyConsultationRoute" value="{{ route('student.my-consultation') }}">
@endsection

@push('script')
<script>
    (function ($) {
        "use strict";
        $(document).on('change', ".filterBy", function () {
            var sortByID = this.value;
            var consultationMyConsultationRoute = $('.consultationMyConsultationRoute').val();
            $.ajax({
                type: "GET",
                url: consultationMyConsultationRoute,
                data: {"sortByID": sortByID},
                datatype: "json",
                success: function (response) {
                    $('.appendConsultationList').html(response)
                },
                error: function () {

                },
            });
        });
    })(jQuery)
</script>
@endpush
