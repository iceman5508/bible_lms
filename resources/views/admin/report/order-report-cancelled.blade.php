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
                                <h2>{{ __('Order Report Cancelled') }}</h2>
                            </div>
                        </div>
                        <div class="breadcrumb__content__right">
                            <nav aria-label="breadcrumb">
                                <ul class="breadcrumb">
                                    <li class="breadcrumb-item"><a
                                            href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">{{ __('Order Report Cancelled') }}</li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="customers__area bg-style mb-30">
                        <div class="item-title d-flex justify-content-between">
                            <h2>{{ __('Order Report Cancelled') }}</h2>
                        </div>
                        <div class="customers__table">
                            <table id="customers-table"
                                   class="row-border data-table-filter table-style admin-order-report-table">
                                <thead>
                                <tr>
                                    <th>Sl.</th>
                                    <th>{{ __('Student Details') }}</th>
                                    <th>{{ __('Order Number') }}</th>
                                    <th>{{ __('Sub Total') }}</th>
                                    <th>{{ __('Discount') }}</th>
                                    <th>{{ __('Platform Charge') }}</th>
                                    <th>{{ __('Grand Total') }}</th>
                                    <th class="admin-order-payment-method">{{ __('Payment Method & Details') }}</th>
                                    <th>{{ __('Total Admin Commission') }}</th>
                                    <th>{{ __('Total Instructor Commission') }}</th>
                                    <th>{{ __('Status') }}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($orders as $order)
                                    <tr class="removable-item">
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            <b>{{ __('Name') }}: </b>{{ @$order->user->student->name }} <br>
                                            <b>{{ __('Email') }}: </b>{{ @$order->user->email }}
                                        </td>
                                        <td>{{ @$order->order_number }}</td>
                                        <td>
                                            @if (get_currency_placement() == 'after')
                                                {{ @$order->sub_total }} {{ get_currency_symbol() }}
                                            @else
                                                {{ get_currency_symbol() }} {{ @$order->sub_total }}
                                            @endif
                                        </td>
                                        <td>
                                            @if (get_currency_placement() == 'after')
                                                {{ @$order->discount }} {{ get_currency_symbol() }}
                                            @else
                                                {{ get_currency_symbol() }} {{ @$order->discount }}
                                            @endif
                                        </td>
                                        <td>
                                            @if (get_currency_placement() == 'after')
                                                {{ @$order->platform_charge }} {{ get_currency_symbol() }}
                                            @else
                                                {{ get_currency_symbol() }} {{ @$order->platform_charge }}
                                            @endif
                                        </td>
                                        <td>
                                            @if (get_currency_placement() == 'after')
                                                {{ @$order->grand_total }} {{ get_currency_symbol() }}
                                            @else
                                                {{ get_currency_symbol() }} {{ @$order->grand_total }}
                                            @endif
                                        </td>
                                        <td class="admin-order-payment-method">
                                            {{ ucfirst(@$order->payment_method) }}
                                            @if (@$order->payment_method)
                                                <p class=" mb-0"><span class="fw-bold">{{ __('Payment Currency') }}</span>:
                                                    {{ $order->payment_currency }}</p>
                                                <p class="font-bold mb-0"><span class="fw-bold">{{ __('Conversion Rate') }}</span> :
                                                    {{ number_format($order->conversion_rate, 2) }}</p>
                                                <p class="font-bold mb-0"><span class="fw-bold">{{ __('Payment') }}</span>:
                                                    {{ number_format($order->grand_total_with_conversation_rate, 2) }}
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
                                            @if (get_currency_placement() == 'after')
                                                {{ $order->total_admin_commission }} {{ get_currency_symbol() }}
                                            @else
                                                {{ get_currency_symbol() }} {{ $order->total_admin_commission }}
                                            @endif
                                        </td>
                                        <td>
                                            @if (get_currency_placement() == 'after')
                                                {{ $order->total_owner_balance }} {{ get_currency_symbol() }}
                                            @else
                                                {{ get_currency_symbol() }} {{ $order->total_owner_balance }}
                                            @endif

                                        </td>
                                        <td><span class="btn btn-danger">{{ __('Cancelled') }}</span></td>
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
    <link rel="stylesheet" href="{{ asset('admin/css/jquery.dataTables.min.css') }}">
@endpush

@push('script')
    <script src="{{ asset('admin/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('admin/js/custom/data-table-page.js') }}"></script>
@endpush
