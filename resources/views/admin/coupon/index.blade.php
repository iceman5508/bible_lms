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
                                <h2>{{ __('Coupon') }}</h2>
                            </div>
                        </div>
                        <div class="breadcrumb__content__right">
                            <nav aria-label="breadcrumb">
                                <ul class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">{{__('Dashboard')}}</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">{{__('Coupon')}}</li>
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
                            <h2>{{ __('Coupon List') }}</h2>
                            <a href="{{route('coupon.create')}}" class="btn btn-success btn-sm"> <i class="fa fa-plus"></i> {{ __('Add Coupon') }} </a>
                        </div>
                        <div class="customers__table">
                            <table id="customers-table" class="row-border data-table-filter table-style">
                                <thead>
                                <tr>
                                    <th>{{ __('SL') }}</th>
                                    <th>{{ __('Coupon Code Name') }}</th>
                                    <th>{{ __('Duration') }}</th>
                                    <th>{{ __('Details') }}</th>
                                    <th>{{ __('Creator') }}</th>
                                    <th>{{ __('Status') }}</th>
                                    <th>{{ __('Action') }}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($coupons as $coupon)
                                    <tr class="removable-item">
                                        <td>{{$loop->iteration}}</td>
                                        <td>{{$coupon->coupon_code_name}}</td>
                                        <td>
                                            <div class="finance-table-inner-item my-2">
                                                <span class="fw-bold mr-1">{{ __('Start Date') }}:</span> {{ $coupon->start_date }}
                                            </div>

                                            <div class="finance-table-inner-item my-2">
                                                <span class="fw-bold mr-1">{{ __('End Date') }}:</span>{{ $coupon->end_date }}
                                            </div>
                                        </td>

                                        <td>
                                            <span class="fw-bold mr-1">{{ __('Coupon Type') }}:</span>
                                            @if($coupon->coupon_type == 1)
                                                {{ __('Global') }}
                                            @elseif($coupon->coupon_type == 2)
                                                {{ __('Instructor') }}
                                            @elseif($coupon->coupon_type == 3)
                                                {{ __('Course') }}
                                            @endif
                                            <br>
                                            <span class="fw-bold mr-1">{{ __('Minimum Amount to Use') }}: </span>
                                            @if(get_currency_placement() == 'after')
                                                {{ $coupon->minimum_amount }} {{ get_currency_symbol() }}
                                            @else
                                                {{ get_currency_symbol() }} {{ $coupon->minimum_amount }}
                                            @endif

                                            <br>
                                            <span class="fw-bold mr-1">Percentage: </span>{{ $coupon->percentage }}%
                                        </td>
                                        <td>{{ @$coupon->creator->name }}</td>

                                        <td>
                                            @if($coupon->status == 1)
                                                <span class="status bg-green">{{ __('Active') }}</span>
                                            @else
                                                <span class="status bg-red">{{ __('Deactivated') }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="action__buttons">
                                                <a href="{{route('coupon.edit', [$coupon->uuid])}}" class="btn-action" title="Edit">
                                                    <img src="{{asset('admin/images/icons/edit-2.svg')}}" alt="edit">
                                                </a>
                                                <a href="javascript:void(0);" data-url="{{route('coupon.delete', [$coupon->uuid])}}" class="btn-action delete" title="Delete">
                                                    <img src="{{asset('admin/images/icons/trash-2.svg')}}" alt="trash">
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            <div class="mt-3">
                                {{$coupons->links()}}
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
