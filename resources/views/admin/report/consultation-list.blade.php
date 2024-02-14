@extends('layouts.admin')

@section('content')
    <!-- Page content area start -->
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="breadcrumb__content">
                        <div class="breadcrumb__content__left">
                            <div class="breadcrumb__title">
                                <h2>{{ __('Revenue Report') }}</h2>
                            </div>
                        </div>
                        <div class="breadcrumb__content__right">
                            <nav aria-label="breadcrumb">
                                <ul class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">{{__('Dashboard')}}</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">{{ __('Revenue report') }}</li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="status__box status__box__v3 bg-style">
                        <div class="status__box__img">
                            <img src="{{ asset('admin') }}/images/admin-dashboard-icons/shop.png" alt="icon">
                        </div>
                        <div class="status__box__text">
                            <h2 class="color-purple">
                                @if(get_currency_placement() == 'after')
                                    {{ $grand_money_from_consultation - $cancel_consultation_money }} {{ get_currency_symbol() }}
                                @else
                                    {{ get_currency_symbol() }} {{ $grand_money_from_consultation - $cancel_consultation_money }}
                                @endif
                            </h2>
                            <h3>{{ __('Grand Total From Consultation') }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="status__box status__box__v3 bg-style">
                        <div class="status__box__img">
                            <img src="{{ asset('admin') }}/images/admin-dashboard-icons/money-loss.png" alt="icon">
                        </div>
                        <div class="status__box__text">
                            <h2 class="color-purple">
                                @if(get_currency_placement() == 'after')
                                    {{ $cancel_consultation_money }} {{ get_currency_symbol() }}
                                @else
                                    {{ get_currency_symbol() }} {{ $cancel_consultation_money }}
                                @endif
                            </h2>
                            <h3>{{ __('Cancel Money From Consultation') }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="status__box status__box__v3 bg-style">
                        <div class="status__box__img">
                            <img src="{{ asset('admin') }}/images/admin-dashboard-icons/laptop.png" alt="icon">
                        </div>
                        <div class="status__box__text">
                            <h2 class="color-purple">{{ $total_enrolment_in_consultation - $total_cancel_consultation }}</h2>
                            <h3>{{ __('Total Enrolled in Consultation') }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="status__box status__box__v3 bg-style">
                        <div class="status__box__img">
                            <img src="{{ asset('admin') }}/images/admin-dashboard-icons/principal.png" alt="icon">
                        </div>
                        <div class="status__box__text">
                            <h2 class="color-purple">{{  $total_cancel_consultation }}</h2>
                            <h3>{{ __('Total Cancel Enrolled in Consultation') }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="customers__area bg-style mb-30">
                        <div class="item-title d-flex justify-content-between">
                            <h2>{{ __('Revenue Report') }} ({{ __('Consultation') }})</h2>
                            <div>
                                <a href="{{route('course-report.revenue-report')}}" class="btn btn-success btn-sm">
                                    Course Report <span class="iconify" data-icon="akar-icons:arrow-right"></span>
                                </a>
                                <a href="{{route('bundle-report.revenue-report')}}" class="btn btn-success btn-sm">
                                    Bundle Report <span class="iconify" data-icon="akar-icons:arrow-right"></span>
                                </a>
                            </div>
                        </div>
                        <div class="customers__table">
                            <table id="customers-table" class="row-border data-table-filter table-style">
                                <thead>
                                <tr>
                                    <th>{{ __('SL') }}</th>
                                    <th>{{ __('Slot Details') }}</th>
                                    <th>{{__('Instructor')}}</th>
                                    <th>{{ __('Total Admin Commission') }}</th>
                                    <th>{{ __('Total Instructor Commission') }}</th>
                                    <th>{{ __('Total Enroll') }}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($consultationOrderItems as $consultationOrderItem)
                                    <tr class="removable-item">
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            <strong>{{ __('Date') }}: </strong>{{ $consultationOrderItem->consultation_date }} <br>
                                            @php
                                                $booking = getBookingHistoryDetails($consultationOrderItem->consultation_slot_id);
                                            @endphp
                                            <strong>Time: </strong>{{ @$booking['time'] }} <br>
                                        </td>
                                        <td>{{getInstructorName($consultationOrderItem->owner_user_id)}}</td>
                                        <td>
                                            @if(get_currency_placement() == 'after')
                                                {{$consultationOrderItem->total_admin_commission}} {{ get_currency_symbol() }}
                                            @else
                                                {{ get_currency_symbol() }}  {{$consultationOrderItem->total_admin_commission}}
                                            @endif
                                        </td>
                                        <td>
                                            @if(get_currency_placement() == 'after')
                                                {{$consultationOrderItem->total_owner_balance}} {{ get_currency_symbol() }}
                                            @else
                                                {{ get_currency_symbol() }} {{$consultationOrderItem->total_owner_balance}}
                                            @endif
                                        </td>
                                        <td>{{ $consultationOrderItem->total_enroll }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            <div class="mt-3">
                                {{$consultationOrderItems->links()}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <!-- Page content area end -->
@endsection

@push('style')
    <link rel="stylesheet" href="{{asset('admin/css/jquery.dataTables.min.css')}}">
@endpush

@push('script')
    <script src="{{asset('admin/js/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('admin/js/custom/data-table-page.js')}}"></script>
@endpush
