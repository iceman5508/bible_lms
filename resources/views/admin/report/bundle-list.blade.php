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
                                    <li class="breadcrumb-item active" aria-current="page">{{ __('Revenue Report') }}</li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-4 col-md-4 col-sm-4">
                    <div class="status__box status__box__v3 bg-style">
                        <div class="status__box__img">
                            <img src="{{ asset('admin') }}/images/admin-dashboard-icons/study.png" alt="icon">
                        </div>
                        <div class="status__box__text">
                            <h2 class="color-blue">{{ $total_enrolment_in_bundle }}</h2>
                            <h3>{{ __('Total Enrolled in Bundle') }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-4">
                    <div class="status__box status__box__v3 bg-style">
                        <div class="status__box__img">
                            <img src="{{ asset('admin') }}/images/admin-dashboard-icons/save-money.png" alt="icon">
                        </div>
                        <div class="status__box__text">
                            <h2 class="color-green">
                                @if(get_currency_placement() == 'after')
                                    {{ $grand_admin_commission }} {{ get_currency_symbol() }}
                                @else
                                    {{ get_currency_symbol() }} {{ $grand_admin_commission }}
                                @endif

                            </h2>
                            <h3>{{ __('Total Admin Commission') }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-4">
                    <div class="status__box status__box__v3 bg-style">
                        <div class="status__box__img">
                            <img src="{{ asset('admin') }}/images/admin-dashboard-icons/discount.png" alt="icon">
                        </div>
                        <div class="status__box__text">
                            <h2 class="color-purple">
                                @if(get_currency_placement() == 'after')
                                    {{ $grand_instructor_commission }} {{ get_currency_symbol() }}
                                @else
                                    {{ get_currency_symbol() }} {{ $grand_instructor_commission }}
                                @endif
                            </h2>
                            <h3>{{ __('Total Instructor Commission') }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="customers__area bg-style mb-30">
                        <div class="item-title d-flex justify-content-between">
                            <h2>{{ __('Revenue Report') }} ({{ __('Bundles') }})</h2>
                            <div>
                                <a href="{{route('course-report.revenue-report')}}" class="btn btn-success btn-sm">
                                    {{ __('Course Report') }} <span class="iconify" data-icon="akar-icons:arrow-right"></span>
                                </a>
                                <a href="{{route('consultation-report.revenue-report')}}" class="btn btn-success btn-sm">
                                    {{ __('Consultation Report') }} <span class="iconify" data-icon="akar-icons:arrow-right"></span>
                                </a>
                            </div>
                        </div>
                        <div class="customers__table">
                            <table id="customers-table" class="row-border data-table-filter table-style">
                                <thead>
                                <tr>
                                    <th>{{__('Image')}}</th>
                                    <th>{{__('Title')}}</th>
                                    <th>{{__('Instructor')}}</th>
                                    <th>{{ __('Total Admin Commission') }}</th>
                                    <th>{{ __('Total Instructor Commission') }}</th>
                                    <th>{{ __('Total Enrolled') }}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($bundleOrderItems as $orderItem)
                                    <tr class="removable-item">
                                        @php
                                            $bundleDetail = getBundleDetails($orderItem->bundle_id);
                                        @endphp
                                        <td>
                                            <a href="#"> <img src="{{getImageFile(@$bundleDetail['image'])}}" width="80" alt="No Image"> </a>
                                        </td>
                                        <td>{{@$bundleDetail['name']}}</td>
                                        <td>{{getInstructorName($orderItem->owner_user_id)}}</td>
                                        <td>
                                            @if(get_currency_placement() == 'after')
                                                {{$orderItem->total_admin_commission}} {{ get_currency_symbol() }}
                                            @else
                                                {{ get_currency_symbol() }}  {{$orderItem->total_admin_commission}}
                                            @endif
                                           </td>
                                        <td>
                                            @if(get_currency_placement() == 'after')
                                                {{$orderItem->total_owner_balance}} {{ get_currency_symbol() }}
                                            @else
                                                {{ get_currency_symbol() }} {{$orderItem->total_owner_balance}}
                                            @endif
                                        </td>
                                        <td>{{ $orderItem->total_enroll }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            <div class="mt-3">
                                {{$bundleOrderItems->links()}}
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
