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
                                <h2>{{__('Courses')}}</h2>
                            </div>
                        </div>
                        <div class="breadcrumb__content__right">
                            <nav aria-label="breadcrumb">
                                <ul class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">{{__('Dashboard')}}</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">{{__('Approved Courses')}}</li>
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
                            <h2>Hold Courses</h2>
                        </div>
                        <div class="customers__table all-course">
                            <table id="customers-table" class="row-border data-table-filter table-style">
                                <thead>
                                <tr>
                                    <th>{{__('Image')}}</th>
                                    <th>{{__('Title')}}</th>
                                    <th>{{__('Instructor')}}</th>
                                    <th>{{__('Category')}}</th>
                                    <th>{{__('Subcategory')}}</th>
                                    <th>{{__('Price')}}</th>
                                    <th>{{__('Action')}}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($courses as $course)
                                    <tr class="removable-item">
                                        <td>
                                            <a href="#"> <img src="{{getImageFile($course->image_path)}}" width="80"> </a>
                                        </td>
                                        <td>
                                            {{$course->title}}
                                        </td>


                                        <td>
                                            {{$course->instructor ? $course->instructor->name : '' }}
                                        </td>
                                        <td>
                                            {{$course->category ? $course->category->name : '' }}
                                        </td>
                                        <td>
                                            {{$course->subcategory ? $course->subcategory->name : '' }}
                                        </td>
                                        <td>
                                            @if(get_currency_placement() == 'after')
                                                {{$course->price}} {{ get_currency_symbol() }}
                                            @else
                                                {{ get_currency_symbol() }} {{$course->price}}
                                            @endif
                                        </td>
                                        <td>

                                            <div class="action__buttons">

                                                <a href="{{route('admin.course.status-change', [$course->uuid, 1])}}" class="btn-action approve-btn mr-30" title="Make as Active">
                                                    {{__('Approve')}}
                                                </a>

                                                <a href="{{route('admin.course.view', [$course->uuid])}}" target="_blank" class="btn-action mr-30" title="View Details">
                                                    <img src="{{asset('admin/images/icons/eye-2.svg')}}" alt="eye">
                                                </a>

                                                <button class="btn-action ms-2 deleteItem" data-formid="delete_row_form_{{$course->uuid}}">
                                                    <img src="{{asset('admin/images/icons/trash-2.svg')}}" alt="trash">
                                                </button>

                                                <form action="{{route('admin.course.delete', [$course->uuid])}}" method="get" id="delete_row_form_{{ $course->uuid }}">

                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            <div class="mt-3">
                                {{$courses->links()}}
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
