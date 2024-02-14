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
                                <h2>{{ __('Update Promotion Course') }}</h2>
                            </div>
                        </div>
                        <div class="breadcrumb__content__right">
                            <nav aria-label="breadcrumb">
                                <ul class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">{{__('Dashboard')}}</a></li>
                                    <li class="breadcrumb-item "><a href="{{ route('promotion.index') }}">{{ __('Promotion List') }}</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">{{ __('Add/Remove Promotion Course') }}</li>
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
                            <h2>{{ @$promotion->name }}</h2>
                        </div>
                        <div class="customers__table">
                            <table id="promotion-courses-table" class="row-border data-table-filter table-style">
                                <thead>
                                <tr>
                                    <th>SL.</th>
                                    <th>{{__('Image')}}</th>
                                    <th>{{__('Name')}}</th>
                                    <th>{{__('Action')}}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($courses as $course)
                                    <tr class="removable-item">
                                        <td>{{ @$loop->iteration }}</td>
                                        <td><a href="#"> <img src="{{getImageFile($course->image_path)}}" width="80"> </a></td>
                                        <td>{{$course->title}}</td>
                                        <td>
                                            <div class="action__buttons appendAddRemove{{ $course->id }}">
                                                @if(in_array($course->id, @$promotionCourseIds))
                                                    <button class="btn-action ms-2 btn btn-danger removePromotion" data-course_id="{{$course->id}}">
                                                        <span class="iconify" data-icon="bi:trash"></span>
                                                    </button>
                                                @elseif(in_array($course->id, @$alreadyAddedPromotionCourseIds))
                                                    <button class="btn-action ms-2 text-danger" data-course_id="{{$course->uuid}}">
                                                        {{ __('Already Added Another Promotion') }}
                                                    </button>
                                                @else
                                                    <button class="btn-action ms-2 btn btn-primary addPromotion" data-course_id="{{$course->id}}">
                                                        <span class="iconify" data-icon="ant-design:plus-square-outlined"></span>
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
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

    <input type="hidden" class="addPromotionCourseRoute" value="{{ route('promotion.addPromotionCourseList') }}">
    <input type="hidden" class="removePromotionCourseRoute" value="{{ route('promotion.removePromotionCourseList') }}">
    <input type="hidden" class="promotion_id" value="{{ $promotion->id }}">
@endsection


@push('style')
    <link rel="stylesheet" href="{{asset('admin/css/jquery.dataTables.min.css')}}">
@endpush

@push('script')
    <script src="{{asset('admin/js/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('admin/js/custom/data-table-page.js')}}"></script>
    <script src="{{ asset('admin/js/custom/promotion.js') }}"></script>
@endpush
