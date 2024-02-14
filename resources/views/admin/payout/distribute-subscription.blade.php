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
                                <h2>{{__(@$pageTitle)}}</h2>
                            </div>
                        </div>
                        <div class="breadcrumb__content__right">
                            <nav aria-label="breadcrumb">
                                <ul class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">{{__('Dashboard')}}</a></li>
                                    <li class="breadcrumb-item"><a href="#">{{__('Payout')}}</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">{{__(@$title)}}</li>
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
                            <h2 class="pageTitle">{{ __('Monthly Pay Commission To Instructor') }}</h2>
                        </div>
                        <form action="{{ route('payout.store.distribute.subscription.calculation') }}" method="post" class="form-horizontal">
                            @csrf
                            <div class="row">
                                <div class="input__group mb-25 col-md-3">
                                    <label>{{__('Month-Year')}} <span class="text-danger">*</span></label>
                                    <input type="text" id="datepickerMonthYear" name="month_year" value="" placeholder="{{__('Month-Year')}}"
                                           class="form-control datepickerMonthYear">
                                </div>
                                <div class="input__group mb-25 col-md-3">
                                    <label>{{__('Current Subscription')}} <b><span class="monthly_year_level"></span></b><span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="number" min="0" step="any" name="current_subscription" value=""
                                               placeholder="{{__('Current Subscription')}}" class="form-control current_subscription" readonly>
                                    </div>
                                </div>
                                <div class="input__group mb-25 col-md-3">
                                    <label>{{__('Enroll Course')}} <b><span class="monthly_year_level"></span></b><span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="number" min="0" step="any" name="total_enroll_course" value=""
                                               placeholder="{{__('Enroll Course')}}" class="form-control total_enroll_course" readonly>
                                    </div>
                                </div>
                                <div class="input__group mb-25 col-md-3">
                                    <label>{{__('Total Income From Subscription')}} <b><span class="monthly_year_level"></span></b><span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="number" min="0" step="any" name="total_income_from_subscription" value="" placeholder="{{__('Total Income From Plan')}}"
                                               class="form-control total_income_from_subscription" readonly>
                                        <span class="input-group-text">{{ get_currency_symbol() }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-12 text-right">
                                    <button class="btn btn-primary" type="submit">{{ __('Send Money') }}</button>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="customers__area bg-style mb-30 admin-dashboard-blog-list-page">
                        <div class="item-title d-flex justify-content-between">
                            <h2 class="pageTitle">{{ __('History For Each Month') }}</h2>
                            <form action="{{ route('payout.store.distribute.subscription.calculation') }}" class="d-flex">
                                <div class="input__group">
                                    <select name="search_string" id="search_string" class="form-control">
                                        <option value="">{{ __('Filter Month Year') }}</option>
                                        @foreach($monthYears as $monthYear)
                                        <option value="{{ $monthYear->month_year }}" {{ app('request')->search_string == $monthYear->month_year }}>{{ $monthYear->month_year }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </form>
                        </div>
                        <div class="customers__table" id="appendList">
                            {{-- @include('admin.earning.partial.render-earning-histories') --}}
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <!-- Page content area end -->
@endsection

@push('style')
    <link href="{{ asset('admin/css/bootstrap-datepicker/1.6.4/css/bootstrap-datepicker.css') }}" rel="stylesheet"/>
@endpush

@push('script')

    <script src="{{ asset('admin/js/bootstrap-datepicker/1.6.4/js/bootstrap-datepicker.js') }}"></script>
    <script>
        $(function () {
            $("#datepickerMonthYear").datepicker({
                format: "MM-yyyy",
                viewMode: "months-years",
                minViewMode: "months",
            });
        });
    </script>

    <script>
        'use strict'
        $(".datepickerMonthYear").on('change', function () {
            var month_year = $('#datepickerMonthYear').val();
            if (month_year == null || !month_year) {
                return
            }
            
            $('.monthly_year_level').html('(' + month_year + ')');
            $.ajax({
                type: "GET",
                url: "{{ route('payout.distribute.earning.management.calculation') }}",
                data: {"month_year": month_year},
                datatype: "json",
                success: function (response) {
                    $('.current_subscription').val(response.current_subscription)
                    $('.total_income_from_subscription').val(response.total_income_from_subscription)
                    $('.total_enroll_course').val(response.total_enroll_course)
                },
                error: function () {
                    alert("Error!");
                },
            });
        });
    </script>

    <script>
        "use strict"
        $(document).on('click', '.pagination a', function (event) {
            event.preventDefault();
            var page = $(this).attr('href').split('page=')[1];
            var route = "{{ url()->current() }}" + "?page=" + page;
            var search_string = $('#search_string').val()
            var data = {'search_string':search_string}
            fetch_data(route, data);
        });

        $('#search_string').change(function (){
            var search_string = $('#search_string').val()
            var route = "{{ url()->current() }}";
            var data = {'search_string':search_string}
            fetch_data(route, data);
        })

        function fetch_data(route, data) {
            $.ajax({
                url: route,
                data: data,
                success: function (data) {
                    $('#appendList').html(data);
                }
            });
        }
    </script>
@endpush
