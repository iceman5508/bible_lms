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
                            <h2>{{ __('Wallet Recharge Pending') }}</h2>
                        </div>
                    </div>
                    <div class="breadcrumb__content__right">
                        <nav aria-label="breadcrumb">
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a
                                        href="{{route('admin.dashboard')}}">{{__('Dashboard')}}</a></li>
                                <li class="breadcrumb-item active" aria-current="page">{{ __('Wallet Recharge Pending List')
                                    }}</li>
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
                        <h2>{{ __('Wallet Recharge Pending List') }}</h2>
                    </div>
                    <div class="customers__table">
                        <table class="table-style">
                            <thead>
                                <tr>
                                    <th>{{__('Date')}}</th>
                                    <th>{{__('Transaction ID')}}</th>
                                    <th>{{__('Payment method')}}</th>
                                    <th>{{__('Amount')}}</th>
                                    <th>{{__('')}}</th>
                                    <th>{{__('Status')}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($walletRecharges as $recharge)
                                <tr>
                                    <td>
                                        <span class="data-text">{{ $recharge->created_at->format('Y-m-d H:i:s') }}</span>
                                    </td>
                                    <td>
                                        <span class="data-text">{{ $recharge->payment->payment_id }}</span>
                                    </td>
                                    <td>
                                        <span class="data-text">{{ $recharge->payment_method }}</span></td>
                                    <td>
                                        <span class="data-text">{{ $recharge->payment->grand_total }}</span>
                                    </td>
                                    <td>
                                        @if($recharge->payment_method == 'bank')
                                            <p class="font-bold mb-0"><span class="fw-bold">{{ __('Deposit Bank Name') }}</span>:
                                                {{ $recharge->payment->bank->name }}
                                            </p>
                                            <p class="font-bold mb-0"><span class="fw-bold">{{ __('Deposit By') }}</span>:
                                                {{ $recharge->payment->deposit_by }}
                                            </p>
                                            <p class="font-bold mb-0"><span class="fw-bold">{{ __('Deposit Slip') }}</span>:
                                                <a class="color-blue" href="{{ getVideoFile($recharge->payment->deposit_slip) }}" download>{{ __('Download') }}</a>
                                            </p>
                                        @endif
                                    </td>
                                    <td>
                                        <span id="hidden_id" style="display: none">{{$recharge->id}}</span>
                                        <select name="status" class="status label-inline font-weight-bolder mb-1 badge badge-info">
                                            <option value="{{ STATUS_ACCEPTED }}" @if($recharge->status == STATUS_ACCEPTED) selected @endif>{{ __("Accepted") }}</option>
                                            <option value="{{ STATUS_PENDING }}" @if($recharge->status == STATUS_PENDING) selected @endif>{{ __("Pending") }}</option>
                                            <option value="{{ STATUS_REJECTED }}" @if($recharge->status == STATUS_REJECTED) selected @endif>{{ __("Rejected") }}</option>
                                        </select>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="mt-3">
                            {{$walletRecharges->links()}}
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
<script>
    'use strict'
        $(".status").change(function () {

            var id = $(this).closest('tr').find('#hidden_id').html();
            var status_value = $(this).closest('tr').find('.status option:selected').val();
            Swal.fire({
                title: "{{ __('Are you sure to change status?') }}",
                text: "{{ __('You won`t be able to revert this!') }}",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "{{ __('Yes, Change it!') }}",
                cancelButtonText: "{{ __('No, cancel!') }}",
                reverseButtons: true
            }).then(function (result) {
                if (result.value) {
                    $.ajax({
                        type: "POST",
                        url: "{{route('admin.wallet_recharge.changeStatus')}}",
                        data: {"status": status_value, "id": id, "_token": "{{ csrf_token() }}",},
                        datatype: "json",
                        success: function (data) {
                            toastr.options.positionClass = 'toast-bottom-right';
                            if(data.data == 'success'){
                                toastr.success('', "{{ __('Status has been updated') }}");
                                location.reload();
                            }
                            else{
                                toastr.error('', "{{ __('Status has cannot be changed') }}");
                            }
                        },
                        error: function () {
                            alert("Error!");
                        },
                    });
                } else if (result.dismiss === "cancel") {
                }
            });
        });
</script>
@endpush