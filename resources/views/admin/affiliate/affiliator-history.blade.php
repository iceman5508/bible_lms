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
                                <h2>{{__('Affliliator History')}}</h2>
                            </div>
                        </div>
                        <div class="breadcrumb__content__right">
                            <nav aria-label="breadcrumb">
                                <ul class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">{{__('Dashboard')}}</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">{{__('Affliliator History')}}</li>
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
                            <h2>{{ __('Affliliator History') }}</h2>
                        </div>

                        <!-- Affiliate History Table Start -->
                        <div class="table-responsive">
                            <table id="all-affiliate" class=" table row-border data-table-filter table-style">
                                <thead>
                                <tr>
                                    <th class="all">{{__('Date')}}</th>
                                    <th class="all">{{__('User')}}</th>
                                    <th class="all">{{__('Type')}}</th>
                                    <th class="none">{{__('Course Name')}}</th>
                                    <th class="all">{{__('Actual Amount')}}</th>
                                    <th class="all">{{__('Earned Amount')}}</th>
                                    <th class="all">{{__('Commission %')}}</th>
                                </tr>
                                </thead>
                            </table>
                        </div>
                        <!-- Affiliate History Table End -->

                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Page content area end -->
@endsection

@push('style')
{{--    <link rel="stylesheet" href="{{asset('admin/css/jquery.dataTables.min.css')}}">--}}
<!-- DataTables -->
<link rel="stylesheet" href="{{asset('frontend/assets/vendor/datatable/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
<link rel="stylesheet" href="{{asset('frontend/assets/vendor/datatable/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
<link rel="stylesheet" href="{{asset('frontend/assets/vendor/datatable/datatables-buttons/css/buttons.bootstrap4.min.css')}}">

@endpush

@push('script')
{{--<script src="{{asset('admin/js/jquery.dataTables.min.js')}}"></script>--}}
{{--<script src="{{asset('admin/js/custom/data-table-page.js')}}"></script>--}}
<!-- DataTables  & Plugins -->
<script src="{{asset('frontend/assets/vendor/datatable/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('frontend/assets/vendor/datatable/datatables-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
<script src="{{asset('frontend/assets/vendor/datatable/datatables-responsive/js/dataTables.responsive.min.js')}}"></script>
<script src="{{asset('frontend/assets/vendor/datatable/datatables-responsive/js/responsive.bootstrap4.min.js')}}"></script>
<script src="{{asset('frontend/assets/vendor/datatable/datatables-buttons/js/dataTables.buttons.min.js')}}"></script>
<script src="{{asset('frontend/assets/vendor/datatable/datatables-buttons/js/buttons.bootstrap4.min.js')}}"></script>
<script src="{{asset('frontend/assets/vendor/datatable/jszip/jszip.min.js')}}"></script>
<script src="{{asset('frontend/assets/vendor/datatable/pdfmake/pdfmake.min.js')}}"></script>
<script src="{{asset('frontend/assets/vendor/datatable/pdfmake/vfs_fonts.js')}}"></script>
<script src="{{asset('frontend/assets/vendor/datatable/datatables-buttons/js/buttons.html5.min.js')}}"></script>
<script src="{{asset('frontend/assets/vendor/datatable/datatables-buttons/js/buttons.print.min.js')}}"></script>
<script src="{{asset('frontend/assets/vendor/datatable/datatables-buttons/js/buttons.colVis.min.js')}}"></script>

<script>
    (function($) {
        "use strict";
        $('#all-affiliate').DataTable({
            processing: true,
            serverSide: true,
            language: {
                'paginate': {
                    'previous': '<span class="iconify" data-icon="icons8:angle-left"></span>',
                    'next': '<span class="iconify" data-icon="icons8:angle-right"></span>'
                }
            },
            pageLength: 25,

            responsive: true,
            ajax: "{{route('affiliate.affiliate-history-data')}}",
            order: [1, 'desc'],
            autoWidth:false,
            dom: '<"row"<"col-sm-4"l><"col-sm-4"B><"col-sm-4"f>>tr<"bottom"<"row"<"col-sm-6"i><"col-sm-6"p>>><"clear">',
            lengthMenu: [
                [ 10, 25, 50, -1 ],
                [ 10, 25, 50, 'all' ],
            ],
            buttons: [
                { extend: 'copy', className: 'theme-btn theme-button1 default-hover-btn' },
                { extend: 'excel', className: 'theme-btn theme-button1 default-hover-btn' },
                { extend: 'pdf', className: 'theme-btn theme-button1 default-hover-btn' }
            ],
            columns: [
                {"data": "date", "title":'Date'},
                {"name": "users.email","data": "email","title":"User"},
                {"name": "type","data": "type","title":"Type"},
                {"name": "courses.title","data": "title","title":"Course Name"},
                {"data": "actual_price","title":"Actual Amount"},
                {"data": "commission","title":"Earned Amount"},
                {"data": "commission_percentage","title":"Commission %"},

            ]
        });
    })(jQuery)
</script>
@endpush


