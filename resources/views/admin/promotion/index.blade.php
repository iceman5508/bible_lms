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
                                <h2>{{ __('Promotion') }}</h2>
                            </div>
                        </div>
                        <div class="breadcrumb__content__right">
                            <nav aria-label="breadcrumb">
                                <ul class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">{{__('Dashboard')}}</a></li>
                                    <li class="breadcrumb-item">{{ __('Promotion') }}</li>
                                    <li class="breadcrumb-item active" aria-current="page">{{ __('Promotion List') }}</li>
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
                            <h2>{{ __('Promotion List') }}</h2>
                            <a href="{{ route('promotion.create') }}" class="btn btn-success btn-sm">
                                <i class="fa fa-plus"></i> {{ __('Add Promotion') }}
                            </a>
                        </div>
                        <div class="customers__table">
                            <table id="customers-table" class="row-border data-table-filter table-style">
                                <thead>
                                <tr>
                                    <th>{{ __('SL') }}.</th>
                                    <th>{{ __('Name') }}</th>
                                    <th>{{ __('Duration') }}</th>
                                    <th>{{ __('Percentage') }} (%)</th>
                                    <th>{{ __('Total Course') }}</th>
                                    <th>{{ __('Status') }}</th>
                                    <th>{{ __('Created By') }}</th>
                                    <th>{{ __('Action')}}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($promotions as $promotion)
                                    <tr class="removable-item">
                                        <td>{{ @$loop->iteration }}</td>
                                        <td>{{$promotion->name}}</td>
                                        <td>
                                            {{ date('d M Y, H:i:s', strtotime(@$promotion->start_date)) }} <span class="text-black">to</span> {{ date('d M Y, H:i:s', strtotime(@$promotion->end_date)) }}
                                        </td>
                                        <td>{{ $promotion->percentage }}</td>
                                        <td>{{ @$promotion->promotionCourses->count() }}</td>
                                        <td>
                                            <span id="hidden_id" style="display: none">{{$promotion->id}}</span>
                                            <select name="status" class="status label-inline font-weight-bolder mb-1 badge badge-info">
                                                <option value="1" @if($promotion->status == 1) selected @endif>{{ __('Active') }}</option>
                                                <option value="0" @if($promotion->status != 1) selected @endif>{{ __('Deactivated') }}</option>
                                            </select>
                                        </td>
                                        <td>{{ $promotion->user->name }}</td>
                                        <td>
                                            <div class="action__buttons">
                                                <a href="{{ route('promotion.editPromotionCourse', $promotion->uuid) }}" class="btn-action mr-1" title="{{ __('View promotion details') }}">
                                                    <button class="btn btn-primary btn-sm">+/- {{ __('Course') }}</button>
                                                </a>
                                                <a href="{{ route('promotion.edit', $promotion->uuid) }}" class="btn-action mr-1 edit" data-toggle="tooltip" title="{{ __('Edit') }}">
                                                    <img src="{{asset('admin/images/icons/edit-2.svg')}}" alt="edit">
                                                </a>
                                                <button class="btn-action ms-2 deleteItem" data-formid="delete_row_form_{{$promotion->uuid}}">
                                                    <img src="{{asset('admin/images/icons/trash-2.svg')}}" alt="trash">
                                                </button>

                                                <form action="{{route('promotion.delete', [$promotion->uuid])}}" method="post" id="delete_row_form_{{ $promotion->uuid }}">
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
                                {{$promotions->links()}}
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
            console.log(id, status_value)
            Swal.fire({
                title: "{{ __('Are you sure to change status?') }}",
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
                        url: "{{route('promotion.changePromotionStatus')}}",
                        data: {"status": status_value, "id": id, "_token": "{{ csrf_token() }}",},
                        datatype: "json",
                        success: function (data) {
                            toastr.success('', '{{ __('Promotion status has been updated') }}');
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
