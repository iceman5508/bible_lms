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
                            <h2>{{ __('SaaS Purchase Pending') }}</h2>
                        </div>
                    </div>
                    <div class="breadcrumb__content__right">
                        <nav aria-label="breadcrumb">
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a
                                        href="{{route('admin.dashboard')}}">{{__('Dashboard')}}</a></li>
                                <li class="breadcrumb-item active" aria-current="page">{{ __('SaaS Purchase Pending List')
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
                        <h2>{{ __('All SaaS Purchase Pending List') }}</h2>
                    </div>
                    <div class="customers__table">
                        <table class="table-style">
                            <thead>
                                <tr>
                                    <th>{{__('Image')}}</th>
                                    <th>{{__('Name')}}</th>
                                    <th>{{__('Package Name')}}</th>
                                    <th>{{__('Purchase date')}}</th>
                                    <th>{{__('Expired at')}}</th>
                                    <th>{{__('')}}</th>
                                    <th>{{__('Status')}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($userSaases as $userSaas)
                                <tr>
                                    <td>
                                        <a  target="_blank" href="{{route('student.view', [@$userSaas->user->student->uuid])}}"><img
                                                src="{{asset(@$userSaas->user ? @$userSaas->user->image_path  : '')}}"
                                                alt="course" class="img-fluid" width="80"></a>
                                    </td>
                                    <td>
                                        <span  target="_blank" class="data-text"><a
                                                href="{{route('student.view', [@$userSaas->user->student->uuid])}}">{{
                                                @$userSaas->user->student->name }}</a></span>
                                    </td>
                                    <td>
                                        <span class="data-text"><a target="_blank" href="{{route('admin.subscriptions.show', [$userSaas->package_uuid])}}">{{ $userSaas->title }}</a></span>
                                    </td>
                                    <td>
                                        <span class="data-text">{{ $userSaas->enroll_date }}</span>
                                    </td>
                                    <td>
                                        <span class="data-text">{{ $userSaas->expired_date }}</span>
                                    </td>
                                    <td>
                                        @if($userSaas->payment->payment_method == 'bank')
                                            <p class="font-bold mb-0"><span class="fw-bold">{{ __('Deposit Bank Name') }}</span>:
                                                {{ $userSaas->payment->bank->name }}
                                            </p>
                                            <p class="font-bold mb-0"><span class="fw-bold">{{ __('Deposit By') }}</span>:
                                                {{ $userSaas->payment->deposit_by }}
                                            </p>
                                            <p class="font-bold mb-0"><span class="fw-bold">{{ __('Deposit Slip') }}</span>:
                                                <a class="color-blue" href="{{ getVideoFile($userSaas->payment->deposit_slip) }}" download>{{ __('Download') }}</a>
                                            </p>
                                        @endif
                                    </td>
                                    <td>
                                        <span id="hidden_id" style="display: none">{{$userSaas->id}}</span>
                                        <select name="status" class="status label-inline font-weight-bolder mb-1 badge badge-info">
                                            <option value="{{ PACKAGE_STATUS_PENDING }}" @if($userSaas->status == PACKAGE_STATUS_PENDING) selected @endif>{{ __("Unpaid") }}</option>
                                            <option value="{{ PACKAGE_STATUS_ACTIVE }}" @if($userSaas->status == PACKAGE_STATUS_ACTIVE) selected @endif>{{ __("Paid") }}</option>
                                        </select>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="mt-3">
                            {{$userSaases->links()}}
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
                        url: "{{route('admin.saas.purchase.changeStatus')}}",
                        data: {"status": status_value, "id": id, "_token": "{{ csrf_token() }}",},
                        datatype: "json",
                        success: function (data) {
                            toastr.options.positionClass = 'toast-bottom-right';
                            toastr.success('', "{{ __('Purchase status has been updated') }}");
                            location.reload();
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