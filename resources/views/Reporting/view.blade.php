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
                                    <li class="breadcrumb-item"><a href="{{route('student_report')}}">{{__('Student Reports')}}</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">{{ __('Report') }}</li>
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
                            <h2>{{ __('Student Report for ') }} {{$student->name}}</h2>
                        </div>
                        <div class="customers__table">
                            <table id="customers-table2" class="row-border data-table-filter table-style">
                                <thead>
                                <tr>
                                    <th>{{__('Course')}}</th>
                                    <th>{{__('Assignment')}}</th>
                                    <th>{{__('Submitted')}}</th>
                                    <th>{{__('Grade')}}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($reports as $report => $assignments)
                                    @foreach($assignments as $assignment )

{{--                                        @php dd($assignment['submitted']) @endphp--}}

                                    <tr class="removable-item">
                                        <td>
                                            {{$assignment['course']}}
                                        </td>
                                        <td>
                                            {{$assignment['name']}}

                                        </td>
                                        <td>{{$assignment['submitted']}}</td>
                                        <td> {{$assignment['marks']}}</td>
                                    </tr>
                                    @endforeach
                                @endforeach
                                </tbody>
                            </table>

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

        $('#customers-table2').DataTable({
            "paging": true,
            "info": true,
            //searching: false,
            language: {
                searchPlaceholder: "Type..."
            }
        });
    </script>
@endpush
