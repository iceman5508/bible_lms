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
                                <h2>{{ __('Order Report') }}</h2>
                            </div>
                        </div>
                        <div class="breadcrumb__content__right">
                            <nav aria-label="breadcrumb">
                                <ul class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">{{__('Dashboard')}}</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">{{ __('Order Report') }}</li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-2 col-md-6 col-sm-6">
                    <div class="status__box status__box__v3 bg-style">
                        <div class="status__box__img">
                            <img src="{{ asset('admin') }}/images/admin-dashboard-icons/paying.png" alt="icon">
                        </div>
                        <div class="status__box__text">
                            <h2 class="color-red">
                                @if(get_currency_placement() == 'after')
                                    {{ $grand_total - $cancel_consultation_money}} {{ get_currency_symbol() }}
                                @else
                                    {{ get_currency_symbol() }} {{ $grand_total - $cancel_consultation_money }}
                                @endif
                            </h2>
                            <h3>{{ __('Grand Total') }}</h3>
                        </div>
                    </div>
                </div>

                <div class="col-lg-2 col-md-6 col-sm-6">
                    <div class="status__box status__box__v3 bg-style">
                        <div class="status__box__img">
                            <img src="{{ asset('admin') }}/images/admin-dashboard-icons/commission-1.png" alt="icon">
                        </div>
                        <div class="status__box__text">
                            <h2 class="color-purple">
                                @if(get_currency_placement() == 'after')
                                    {{ $total_platform_charge }} {{ get_currency_symbol() }}
                                @else
                                    {{ get_currency_symbol() }} {{ $total_platform_charge }}
                                @endif
                            </h2>
                            <h3>{{ __('Total Platform Charge') }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2 col-md-6 col-sm-6">
                    <div class="status__box status__box__v3 bg-style">
                        <div class="status__box__img">
                            <img src="{{ asset('admin') }}/images/admin-dashboard-icons/save-money.png" alt="icon">
                        </div>
                        <div class="status__box__text">
                            <h2 class="color-green">
                                @if(get_currency_placement() == 'after')
                                    {{ $grand_admin_commission - $cancel_admin_commission_consultation_money }} {{ get_currency_symbol() }}
                                @else
                                    {{ get_currency_symbol() }} {{ $grand_admin_commission - $cancel_admin_commission_consultation_money }}
                                @endif

                            </h2>
                            <h3>{{ __('Total Admin Commission') }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2 col-md-6 col-sm-6">
                    <div class="status__box status__box__v3 bg-style">
                        <div class="status__box__img">
                            <img src="{{ asset('admin') }}/images/admin-dashboard-icons/money.png" alt="icon">
                        </div>
                        <div class="status__box__text">
                            <h2 class="color-blue">
                                @if(get_currency_placement() == 'after')
                                    {{ $total_revenue }} {{ get_currency_symbol() }}
                                @else
                                    {{ get_currency_symbol() }} {{ $total_revenue }}
                                @endif
                            </h2>
                            <h3>{{ __('Total Revenue') }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2 col-md-6 col-sm-6">
                    <div class="status__box status__box__v3 bg-style">
                        <div class="status__box__img">
                            <img src="{{ asset('admin') }}/images/admin-dashboard-icons/discount.png" alt="icon">
                        </div>
                        <div class="status__box__text">
                            <h2 class="color-purple">
                                @if(get_currency_placement() == 'after')
                                    {{ $grand_instructor_commission - $cancel_instructor_commission_consultation_money }} {{ get_currency_symbol() }}
                                @else
                                    {{ get_currency_symbol() }} {{ $grand_instructor_commission - $cancel_instructor_commission_consultation_money }}
                                @endif
                            </h2>
                            <h3>{{ __('Total Instructor Commission') }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2 col-md-6 col-sm-6">
                    <div class="status__box status__box__v3 bg-style">
                        <div class="status__box__img">
                            <img src="{{ asset('admin') }}/images/admin-dashboard-icons/study.png" alt="icon">
                        </div>
                        <div class="status__box__text">
                            <h2 class="color-blue">{{ $total_enrolment_in_course }}</h2>
                            <h3>{{ __('Total Enrolled in Courses') }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2 col-md-6 col-sm-6">
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
                <div class="col-lg-2 col-md-6 col-sm-6">
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
                <div class="col-lg-2 col-md-6 col-sm-6">
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
                <div class="col-lg-2 col-md-6 col-sm-6">
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
                <div class="col-lg-2 col-md-6 col-sm-6">
                    <div class="status__box status__box__v3 bg-style">
                        <div class="status__box__img">
                            <img src="{{ asset('admin') }}/images/admin-dashboard-icons/discount.png" alt="icon">
                        </div>
                        <div class="status__box__text">
                            <h2 class="color-purple"> {{ @$total_bundle_Course_enrolled }}</h2>
                            <h3>{{ __('Total Bundle Course enrolled') }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2 col-md-6 col-sm-6">
                    <div class="status__box status__box__v3 bg-style">
                        <div class="status__box__img">
                            <img src="{{ asset('admin') }}/images/admin-dashboard-icons/discount.png" alt="icon">
                        </div>
                        <div class="status__box__text">
                            <h2 class="color-purple">
                                @if(get_currency_placement() == 'after')
                                    {{ $grand_money_from_bundle }} {{ get_currency_symbol() }}
                                @else
                                    {{ get_currency_symbol() }} {{ $grand_money_from_bundle }}
                                @endif
                            </h2>
                            <h3>{{ __('Grand Total Bundle Course') }}</h3>
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="customers__area bg-style mb-30">
                        <div class="item-title d-flex justify-content-between">
                            <h2>{{ __('Order Report') }}</h2>
                        </div>
                        <div class="customers__table">
                            <table id="customers-table" class="row-border data-table-filter table-style admin-order-report-table">
                                <thead>
                                <tr>
                                    <th>{{ __('Student Name') }}</th>
                                    <th>{{ __('Order Number') }}</th>
                                    <th>{{ __('Sub Total') }}</th>
                                    <th>{{ __('Discount') }}</th>
                                    <th>{{ __('Platform Charge') }}</th>
                                    <th>{{ __('Grand Total') }}</th>
                                    <th class="admin-order-payment-method">{{ __('Payment Method & Details') }}</th>
                                    <th>{{ __('Total Admin Commission') }}</th>
                                    <th>{{ __('Total Instructor Commission') }}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($orders as $order)
                                    <tr class="removable-item">
                                        <td>{{@$order->user->student->name}}</td>
                                        <td>{{@$order->order_number}}</td>
                                        <td> @if(get_currency_placement() == 'after')
                                                {{@$order->sub_total}} {{ get_currency_symbol() }}
                                            @else
                                                {{ get_currency_symbol() }} {{@$order->sub_total}}
                                            @endif</td>
                                        <td> @if(get_currency_placement() == 'after')
                                                {{@$order->discount}} {{ get_currency_symbol() }}
                                            @else
                                                {{ get_currency_symbol() }} {{@$order->discount}}
                                            @endif</td>
                                        <td> @if(get_currency_placement() == 'after')
                                                {{@$order->platform_charge}} {{ get_currency_symbol() }}
                                            @else
                                                {{ get_currency_symbol() }} {{@$order->platform_charge}}
                                            @endif</td>
                                        <td> @if(get_currency_placement() == 'after')
                                                {{@$order->grand_total}} {{ get_currency_symbol() }}
                                            @else
                                                {{ get_currency_symbol() }} {{@$order->grand_total}}
                                            @endif</td>
                                        <td class="admin-order-payment-method">
                                            {{ucfirst(@$order->payment_method)}}
                                            @if(@$order->payment_method)
                                                <p class=" mb-0"><span class="fw-bold">{{ __('Payment Currency') }}</span>: {{ $order->payment_currency }}</p>
                                                <p class="font-bold mb-0"><span class="fw-bold">{{ __('Conversion Rate') }}</span> : {{ number_format($order->conversion_rate, 2) }}</p>
                                                <p class="font-bold mb-0"><span class="fw-bold">{{ __('Payment') }}</span>: {{ number_format($order->grand_total_with_conversation_rate, 2) }}
                                                </p>
                                                @if(@$order->payment_method == 'bank')
                                                    <p class="font-bold mb-0"><span class="fw-bold">{{ __('Deposit Bank Name') }}</span>:
                                                        {{ @$order->bank->name }}
                                                    </p>
                                                    <p class="font-bold mb-0"><span class="fw-bold">{{ __('Deposit By') }}</span>:
                                                        {{ $order->deposit_by }}
                                                    </p>
                                                    <p class="font-bold mb-0"><span class="fw-bold">{{ __('Deposit Slip') }}</span>:
                                                        <a class="color-blue" href="{{ getVideoFile($order->deposit_slip) }}" download>{{ __('Download') }}</a>
                                                    </p>
                                                @endif
                                            @endif

                                        </td>
                                        <td>
                                            @if(get_currency_placement() == 'after')
                                                {{$order->total_admin_commission}} {{ get_currency_symbol() }}
                                            @else
                                                {{ get_currency_symbol() }} {{$order->total_admin_commission}}
                                            @endif
                                        </td>
                                        <td>
                                            @if(get_currency_placement() == 'after')
                                                {{$order->total_owner_balance}} {{ get_currency_symbol() }}
                                            @else
                                                {{ get_currency_symbol() }} {{$order->total_owner_balance}}
                                            @endif

                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            <div class="mt-3">
                                {{$orders->links()}}
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
