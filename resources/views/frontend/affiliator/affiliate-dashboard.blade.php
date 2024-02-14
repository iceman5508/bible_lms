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
                            <h3 class="page-banner-heading color-heading pb-15">{{ __('Affiliate Dashboard') }}</h3>

                            <!-- Breadcrumb Start-->
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb justify-content-center">
                                    <li class="breadcrumb-item font-14"><a href="{{ url('/') }}">{{ __('Home') }}</a></li>
                                    <li class="breadcrumb-item font-14 active" aria-current="page">{{ __('Affiliate Dashboard') }}</li>
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

                    <div class="row affiliator-dashboard-title align-items-center border-bottom mb-30 pb-20 mx-0">
                        <div class="col-md-12 col-lg-5 col-xl-5 px-0">
                            <h5>{{ __('Affiliate Dashboard') }}</h5>
                        </div>
                        <div class="col-md-12 col-lg-7 col-xl-7 px-0">
                            <div class="affiliate-top-title-btns text-end">
                                <!-- Withdrawal modal trigger Button -->
                                <a href=" {{ route('wallet./') }}" class="theme-btn theme-button1 default-hover-btn">{{ __('My Wallet') }}</a>
                            </div>
                        </div>
                    </div>

                    <div class="affiliate-dashboard-top-box row">
                        <div class="col-md-6 col-lg-4">
                            <div class="affiliate-dashboard-item radius-4 p-30 mb-30">
                                <p class="font-18">{{ __('Total Number of Affiliate') }}<span class="iconify ms-1" data-icon="fluent:arrow-trending-20-filled"></span></p>
                                <h4 class="affiliate-dashboard-item-title mt-3">{{$totalAffiliateCount}}</h4>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-4">
                            <div class="affiliate-dashboard-item radius-4 p-30 mb-30">
                                <p class="font-18">{{ __('Total Affiliate') }}<span class="iconify ms-1" data-icon="fluent:arrow-trending-20-filled"></span></p>
                                <h4 class="affiliate-dashboard-item-title mt-3">{{get_currency_symbol().$totalAffiliate}}</h4>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-4">
                            <div class="affiliate-dashboard-item radius-4 p-30 mb-30">
                                <p class="font-18">{{ __('Total Commission Earnings') }}<span class="iconify ms-1" data-icon="fluent:arrow-trending-20-filled"></span></p>
                                <h4 class="affiliate-dashboard-item-title mt-3">{{get_currency_symbol().$totalCommission}}</h4>
                            </div>
                        </div>
                    </div>

                    <div class="row booking-history-tabs">
                        <div class="col-12">
                            <ul class="nav nav-tabs assignment-nav-tabs live-class-list-nav-tabs" id="myTab" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active" id="earning-history-tab" data-bs-toggle="tab" data-bs-target="#earning-history" type="button" role="tab"
                                            aria-controls="earning-history" aria-selected="true">{{ __('Earning history') }}
                                    </button>
                                </li>
                            </ul>

                            <div class="tab-content" id="myTabContent">
                                <div class="tab-pane fade active show" id="earning-history" role="tabpanel" aria-labelledby="earning-history-tab">
                                    <div class="table-responsive">
                                        <table id="my-earnings" class="table booking-history-past-table">
                                            <thead>
                                            <tr>
                                                <th>{{__('Date')}}</th>
                                                <th>{{__('Course Name')}}</th>
                                                <th>{{__('Actual Amount')}}</th>
                                                <th>{{__('Earned Amount')}}</th>
                                                <th>{{__('Commission %')}}</th>
                                            </tr>
                                            </thead>

                                        </table>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!--Withdrawal Modal Start-->
    <div class="modal fade" id="withdrawalModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="withdrawal-modal-title text-center">
                        <p class="font-13 font-medium">{{ __('Available Balance') }}</p>
                        <h4>
                            @if(get_currency_placement() == 'after')
                                {{userBalance()}} {{ get_currency_symbol() }}
                            @else
                                {{ get_currency_symbol() }} {{userBalance()}}
                            @endif
                        </h4>
                    </div>
                    <form method="POST" action="{{route('wallet.process-withdraw')}}">
                        @csrf
                        <div class="row">

                            <div class="col-md-12 mb-30">
                                <div class="label-text-title color-heading font-medium font-16 mb-3">{{ __('Amount' ) }}
                                    <span class="cursor tooltip-show-btn share-referral-big-btn primary-btn get-referral-btn border-0" data-toggle="popover"
                                          data-bs-placement="bottom" data-bs-content="Meridian sun strikes upper urface of the impenetrable foliage of my trees">
                                   !
                                </span>
                                </div>
                                <input type="number" name="amount" min="1" class="form-control" placeholder="{{ __('Type amount') }}" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 mb-30">
                                <div class="label-text-title color-heading font-medium font-16 mb-3">{{ __('Method') }}
                                    <span class="cursor tooltip-show-btn share-referral-big-btn primary-btn get-referral-btn border-0" data-toggle="popover"
                                          data-bs-placement="bottom" data-bs-content="Meridian sun strikes upper urface of the impenetrable foliage of my trees">
                                       !
                                </span>
                                </div>

                                <div class="withdrawal-radio-item-wrap form-control">
                                    <div class="form-check">
                                        <div class="withdrawal-radio-item">
                                            <input class="form-check-input" type="radio" name="payment_method" value="paypal" required id="flexRadioDefault3">
                                            <label class="form-check-label" for="flexRadioDefault3">
                                                {{ __('Withdraw with Paypal') }}
                                            </label>
                                        </div>
                                        <div class="withdrawal-radio-img">
                                            <img src="{{ asset('frontend/assets/img/instructor-img/paypal-icon.png') }}" alt="paypal">
                                        </div>
                                    </div>
                                </div>
                                <div class="withdrawal-radio-item-wrap form-control">
                                    <div class="form-check">
                                        <div class="withdrawal-radio-item">
                                            <input class="form-check-input" type="radio" name="payment_method" value="card" required id="flexRadioDefault4">
                                            <label class="form-check-label" for="flexRadioDefault4">
                                                {{ __('Withdraw with Card') }}
                                            </label>
                                        </div>
                                        <div class="withdrawal-radio-img">
                                            <img src="{{ asset('frontend/assets/img/instructor-img/mastercard-icon.png') }}" alt="card">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <button type="submit" class="theme-btn theme-button1 theme-button3 font-15 fw-bold w-100">{{ __('Make Withdraw') }}</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!--Withdrawal Modal End-->

</section>
<!-- Wishlist Page Area End -->

</div>
@endsection

@push('script')
    <script>
        (function($) {
            "use strict";
            $('#my-earnings').DataTable({
                processing: true,
                serverSide: true,
                language: {
                    'paginate': {
                        'previous': '<span class="iconify" data-icon="icons8:angle-left"></span>',
                        'next': '<span class="iconify" data-icon="icons8:angle-right"></span>'
                    }
                },
                pageLength: 25,
                responsive: true,
                ajax: "{{route('affiliate.my-affiliate-list')}}",
                order: [1, 'desc'],
                autoWidth:false,
                dom: '<"row"<"col-sm-4"l><"col-sm-4"B><"col-sm-4"f>>tr<"bottom"<"row"<"col-sm-6"i><"col-sm-6"p>>><"clear">',
                buttons: [
                    { extend: 'copy', className: 'theme-btn theme-button1 default-hover-btn' },
                    { extend: 'excel', className: 'theme-btn theme-button1 default-hover-btn' },
                    { extend: 'pdf', className: 'theme-btn theme-button1 default-hover-btn' }
                ],
                columns: [
                    {"data": "date", "title":'Date'},
                    {"data": "course_name","title":"Course Name"},
                    {"data": "actual_price","title":"Actual Amount"},
                    {"data": "commission","title":"Earned Amount"},
                    {"data": "commission_percentage","title":"Commission %"},

                ]
            });
        })(jQuery)
    </script>
@endpush
