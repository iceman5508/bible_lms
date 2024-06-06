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
                                <h2>{{ __('Reporting') }}</h2>
                            </div>
                        </div>
                        <div class="breadcrumb__content__right">
                            <nav aria-label="breadcrumb">
                                <ul class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">{{__('Dashboard')}}</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">{{ __('All Student Report') }}</li>
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
                            <h2>{{ __('All Student Report') }}</h2>
                        </div>
                        <div class="customers__table">
                            <table id="customers-table" class="row-border data-table-filter table-style">
                                <thead>
                                <tr>
                                    <th>{{__('Image')}}</th>
                                    <th>{{__('Details')}}</th>
                                    <th>{{__('Country')}}</th>
                                    <th>{{__('Address')}}</th>
                                    <th>{{ __('Total Course Enroll') }}</th>
                                    <th>{{__('Status')}}</th>
                                    <th>{{__('Action')}}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($students as $student)
                                    <tr class="removable-item">
                                        <td>
                                            <img src="{{getImageFile($student->user ? @$student->user->image_path : '')}}" width="80">
                                        </td>
                                        <td>
                                            {{__('Name')}}: {{$student->name}}<br>
                                            {{__('Email')}}: {{$student->user->email}}<br>
                                            {{__('Phone')}}: {{$student->phone_number ?? @$student->user->phone_number}}<br>

                                        </td>
                                        <td>{{$student->country ? $student->country->country_name : '' }}</td>
                                        <td>{{$student->address}}</td>
                                        <td>{{ studentCoursesCount($student->user_id) }}</td>
                                        <td>
                                            <span id="hidden_id" style="display: none">{{$student->id}}</span>
                                            <select name="status" class="status label-inline font-weight-bolder mb-1 badge badge-info">
                                                <option value="1" @if($student->status == 1) selected @endif>{{ __('Approved') }}</option>
                                                <option value="2" @if($student->status == 2) selected @endif>{{ __('Blocked') }}</option>
                                            </select>
                                        </td>
                                        <td>
                                            <div class="action__buttons">
                                                <a href="{{route('student.view', [$student->uuid])}}" class="btn-action mr-30" title="View Details">
                                                    <img src="{{asset('admin/images/icons/eye-2.svg')}}" alt="eye">
                                                </a>
                                                <a href="{{route('run_report', [$student->uuid])}}" class="btn-action mr-30" title="RUn Report">
                                                    <img src="{{asset('admin/images/icons/edit-2.svg')}}" alt="edit">
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            <div class="mt-3">
                                {{$students->links()}}
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

    </script>
@endpush
