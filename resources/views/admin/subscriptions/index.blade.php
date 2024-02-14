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
                                <h2>{{ __('Subscription Packages') }}</h2>
                            </div>
                        </div>
                        <div class="breadcrumb__content__right">
                            <nav aria-label="breadcrumb">
                                <ul class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">{{__('Dashboard')}}</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">{{ __('All Subscription Package') }}</li>
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
                            <h2>{{ __('All Subscription Package') }}</h2>
                        </div>
                        <div class="customers__table">
                            <table id="package-list-table" class="row-border data-table-filter table-style">
                                <thead>
                                <tr>
                                    <th>{{__('Icon')}}</th>
                                    <th>{{__('Title')}}</th>
                                    <th>{{__('Price')}}</th>
                                    <th>{{__('Enroll')}}</th>
                                    <th>{{__('Consultancy')}}</th>
                                    <th>{{ __('Bundle') }}</th>
                                    <th>{{ __('Device') }}</th>
                                    <th>{{__('Status')}}</th>
                                    <th>{{__('Action')}}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($subscriptions as $subscription)
                                    <tr class="removable-item">
                                        <td>
                                             <img src="{{getImageFile($subscription->icon)}}" width="80">
                                        </td>
                                        <td>{{ $subscription->title }}</td>
                                        <td>
                                            {{__('Monthly')}}: {{$subscription->monthly_price}}<br>
                                            {{__('Yearly')}}: {{$subscription->yearly_price}}<br>
                                        </td>
                                        <td>{{$subscription->course }}</td>
                                        <td>{{$subscription->consultancy}}</td>
                                        <td>{{$subscription->bundle_course}}</td>
                                        <td>{{$subscription->device}}</td>
                                        <td>
                                            <span id="hidden_id" style="display: none">{{$subscription->id}}</span>
                                            <select name="status" class="status label-inline font-weight-bolder mb-1 badge badge-info">
                                                <option value="{{ PACKAGE_STATUS_DISABLED }}" @if($subscription->status == PACKAGE_STATUS_DISABLED) selected @endif>{{ __('Disable') }}</option>
                                                <option value="{{ PACKAGE_STATUS_ACTIVE }}" @if($subscription->status == PACKAGE_STATUS_ACTIVE) selected @endif>{{ __('Enable') }}</option>
                                            </select>
                                        </td>
                                        <td>
                                            <div class="action__buttons">
                                                <a href="{{route('admin.subscriptions.show', [$subscription->uuid])}}" class="btn-action mr-30" title="View Details">
                                                    <img src="{{asset('admin/images/icons/eye-2.svg')}}" alt="eye">
                                                </a>
                                                <a href="{{route('admin.subscriptions.edit', [$subscription->uuid])}}" class="btn-action mr-30" title="Edit Details">
                                                    <img src="{{asset('admin/images/icons/edit-2.svg')}}" alt="edit">
                                                </a>
                                                <button class="ms-3">
                                                    <span data-formid="delete_row_form_{{$subscription->id}}" class="deleteItem">
                                                        <img src="{{asset('admin/images/icons/trash-2.svg')}}" alt="trash">
                                                    </span>
                                                </button>

                                                <form action="{{route('admin.subscriptions.destroy', [$subscription->uuid])}}" method="post" id="delete_row_form_{{ $subscription->id }}">
                                                    {{ method_field('DELETE') }}
                                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            <div class="mt-3">
                                {{$subscriptions->links()}}
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
                        url: "{{route('admin.subscriptions.changeStatus')}}",
                        data: {"status": status_value, "id": id, "_token": "{{ csrf_token() }}",},
                        datatype: "json",
                        success: function (data) {
                            toastr.options.positionClass = 'toast-bottom-right';
                            toastr.success('', "{{ __('Subscription status has been updated') }}");
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
