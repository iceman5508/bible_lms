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
                                    <li class="breadcrumb-item active" aria-current="page">{{ __('Cancel Consultation List') }}</li>
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
                            <h2>{{ __('Cancel List') }}</h2>
                            <div>
                                <a href="{{route('consultation-report.revenue-report')}}" class="btn btn-success btn-sm">
                                    {{ __('Consultation Revenue Report') }} <span class="iconify" data-icon="akar-icons:arrow-right"></span>
                                </a>
                            </div>
                        </div>
                        <div class="mb-5">
                            <caption><b>Note:</b> If any instructor cancel his/her booking consultation. Admin need to back money manually. After manually back money, admin can
                                send money status change <b>No</b> to <b>Yes</b></caption>
                        </div>
                        <div class="customers__table">

                            <table id="customers-table" class="row-border data-table-filter table-style">
                                <thead>
                                <tr>
                                    <th>{{ __('SL') }}.</th>
                                    <th>{{ __('Student Details') }}</th>
                                    <th>{{__('Instructor')}}</th>
                                    <th>{{ __('Slot Details') }}</th>
                                    <th>{{ __('Admin Commission') }}</th>
                                    <th>{{ __('Instructor Commission') }}</th>
                                    <th>{{ __('Total Back Money') }}</th>
                                    <th>{{ __('Status') }}</th>
                                    <th>{{ __('Send Back Money') }}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($bookingHistories as $bookingHistory)
                                    <tr class="removable-item">
                                        <td>{{ $bookingHistory->id }}</td>

                                        <td>
                                            <p><h6 class="font-15 d-inline">{{ __('Name') }}</h6>: {{ @$bookingHistory->user->student->name }}</p>
                                            <p><h6 class="font-15 d-inline">{{ __('Email') }}</h6>: {{ @$bookingHistory->user->email }}</p>
                                            <p><h6 class="font-15 d-inline">{{ __('Phone Number') }}</h6>: {{ @$bookingHistory->user->student->phone_number }}</p>
                                        </td>
                                        <td>
                                            <p><h6 class="font-15 d-inline">{{ __('Name') }}</h6>: {{ @$bookingHistory->instructorUser->instructor->name }}</p>
                                            <p><h6 class="font-15 d-inline">{{ __('Email') }}</h6>: {{ @$bookingHistory->instructorUser->email }}</p>
                                            <p><h6 class="font-15 d-inline">{{ __('Phone Number') }}</h6>: {{ @$bookingHistory->instructorUser->instructor->phone_number }}</p>
                                        </td>
                                        <td>
                                            <strong>{{ __('Date') }}: </strong>{{ $bookingHistory->date }} <br>
                                            <strong>{{ __('Time') }}: </strong>{{ @$bookingHistory->time }} <br>
                                        </td>
                                        <td>
                                            @if($bookingHistory->send_back_money_status == 1)
                                                {{ $bookingHistory->back_admin_commission }}
                                            @else
                                                @if(get_currency_placement() == 'after')
                                                    {{@$bookingHistory->order_item->admin_commission}} {{ get_currency_symbol() }}
                                                @else
                                                    {{ get_currency_symbol() }}  {{@$bookingHistory->order_item->admin_commission}}
                                                @endif
                                            @endif
                                        </td>
                                        <td>
                                            @if($bookingHistory->send_back_money_status == 1)
                                                {{ $bookingHistory->back_owner_balance }}
                                            @else
                                                @if(get_currency_placement() == 'after')
                                                    {{@$bookingHistory->order_item->owner_balance}} {{ get_currency_symbol() }}
                                                @else
                                                    {{ get_currency_symbol() }} {{@$bookingHistory->order_item->owner_balance}}
                                                @endif
                                            @endif


                                        </td>
                                        <td>
                                            @if($bookingHistory->send_back_money_status == 1)
                                                {{ ($bookingHistory->back_admin_commission ?? 0) + ($bookingHistory->back_owner_balance ?? 0) }}
                                            @else
                                            @php $total = @$bookingHistory->order_item->admin_commission + @$bookingHistory->order_item->owner_balance @endphp
                                                @if(get_currency_placement() == 'after')
                                                    <b>{{ $total }} {{ get_currency_symbol() }}</b>
                                                @else
                                                    <b>{{ get_currency_symbol() }} {{ $total }}</b>
                                                @endif
                                            @endif
                                        </td>
                                        <td>
                                            @if($bookingHistory->status == BOOKING_HISTORY_STATUS_APPROVE)
                                                <span class="status active">{{ __('Approved') }}</span>
                                            @elseif($bookingHistory->status == BOOKING_HISTORY_STATUS_CANCELLED)
                                                <span class="status blocked">{{ __('Cancelled') }}</span>
                                            @elseif($bookingHistory->status == BOOKING_HISTORY_STATUS_COMPLETED)
                                                <span class="status active">{{ __('Completed') }}</span>
                                            @else
                                                {{ __('Pending') }}
                                            @endif
                                        </td>
                                        <td>
                                            @if($bookingHistory->send_back_money_status == SEND_BACK_MONEY_STATUS_YES)
                                                <span class="status active">{{ __('Yes') }}</span>
                                            @else
                                                <span id="hidden_id" style="display: none">{{$bookingHistory->id}}</span>
                                                <select name="status" class="status label-inline font-weight-bolder mb-1 badge badge-info">
                                                    <option value="1" @if($bookingHistory->send_back_money_status == SEND_BACK_MONEY_STATUS_YES) selected @endif>{{ __('Yes') }}</option>
                                                    <option value="0" @if($bookingHistory->send_back_money_status != SEND_BACK_MONEY_STATUS_YES) selected @endif>{{ __('No') }}</option>
                                                </select>
                                            @endif

                                        </td>

                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            <div class="mt-3">
                                {{$bookingHistories->links()}}
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
                title: "{{ __('Are you sure to back money?') }}",
                text: "{{ __('You won`t be able to revert this!') }}",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "{{__('Yes, Change it!')}}",
                cancelButtonText: "{{__('No, cancel!')}}",
                reverseButtons: true
            }).then(function (result) {
                if (result.value) {
                    $.ajax({
                        type: "POST",
                        url: "{{route('report.changeConsultationStatus')}}",
                        data: {"status": status_value, "id": id, "_token": "{{ csrf_token() }}",},
                        datatype: "json",
                        success: function (response) {
                            if(response.status == 403) {
                                toastr.options.positionClass = 'toast-bottom-right';
                                toastr.error('', "{{ __('Already money send') }}");
                            }else if(response.status == 200) {
                                toastr.options.positionClass = 'toast-bottom-right';
                                toastr.success('', "{{ __('Status updated successfully') }}");
                            }

                            location.reload()
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
