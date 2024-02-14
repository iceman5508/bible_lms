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
                                <h2>{{__('Student Profile')}}</h2>
                            </div>
                        </div>
                        <div class="breadcrumb__content__right">
                            <nav aria-label="breadcrumb">
                                <ul class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">{{__('Dashboard')}}</a></li>
                                    <li class="breadcrumb-item"><a href="{{route('instructor.index')}}">{{__('All Instructors')}}</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">{{__('Student Profile')}}</li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-4 col-md-5">
                    <div class="profile__item bg-style">
                        <div class="profile__item__top">
                            <div class="user-img">
                                <img src="{{asset($student->user ? $student->user->image_path  : '')}}" alt="img">
                            </div>
                            <div class="user-text">
                                <h2>{{$student->name}}</h2>
                            </div>
                        </div>
                        <div class="profile__item__content">
                            <h2>{{__('Personal Information')}}</h2>
                            <p>
                                {{$student->about_me}}
                            </p>
                        </div>
                        <ul class="profile__item__list">
                            <li>
                                <div class="list-item">
                                    <h2>{{__('Name')}}:</h2>
                                    <p>{{$student->name}}</p>
                                </div>
                            </li>
                            <li>
                                <div class="list-item">
                                    <h2>{{__('Phone')}}:</h2>
                                    <p>{{$student->phone_number}}</p>
                                </div>
                            </li>
                            <li>
                                <div class="list-item">
                                    <h2>{{__('Email')}}:</h2>
                                    <p>{{$student->user ? $student->user->email : '' }}</p>
                                </div>
                            </li>
                            <li>
                                <div class="list-item">
                                    <h2>{{ __('Address') }}:</h2>
                                    <p>{{$student->address}} </p>
                                </div>
                            </li>
                            <li>
                                <div class="list-item">
                                    <h2>{{ __('Location') }}:</h2>
                                    <p>{{$student->city ?  $student->city->name.', ' : ''}}  {{$student->state ?  $student->state->name.', ' : ''}} {{$student->country ? $student->country->country_name : ''}} </p>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-8 col-md-7">
                    <div class="profile__status__area">
                        <div class="row">
                            <div class="col-lg-12 col-md-12">
                                <div class="status__item bg-style">
                                    <div class="status-img">
                                        <img src="{{asset('admin/images/status-icon/done.png')}}" alt="icon">
                                    </div>
                                    <div class="status-text">
                                        <h2>{{ studentCoursesCount($student->user_id) }}</h2>
                                        <p>{{ __('Total Enrolled Courses') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="profile__timeline__area bg-style">
                        <div class="item-title">
                            <h2>{{__('Enrolled Courses')}}</h2>
                        </div>
                        <div class="profile__table">
                            <table class="table-style">
                                <thead>
                                <tr>
                                    <th>{{__('Image')}}</th>
                                    <th>{{__('Title')}}</th>
                                    <th>{{__('Validity')}}</th>
                                    <th>{{__('Status')}}</th>
                                </tr>
                                </thead>
                                <tbody>
                                    @foreach($enrollments as $enrollment)
                                    <tr>
                                        <td>
                                            <a target="_blank" href="{{route('admin.course.view', [$enrollment->course->uuid])}}"><img src="{{ getImageFile(@$enrollment->course->image_path) }}" alt="course" class="img-fluid" width="80"></a>
                                        </td>
                                        <td>
                                            <span class="data-text"><a target="_blank" href="{{route('admin.course.view', [$enrollment->course->uuid])}}">{{ @$enrollment->course->title }}</a></span>
                                        </td>
                                        <td>
                                            {{ $enrollment->end_date }}
                                        </td>
                                        <td>
                                            <span id="hidden_id" style="display: none">{{$enrollment->id}}</span>
                                            <select name="status" class="status label-inline font-weight-bolder mb-1 badge badge-info">
                                                <option value="{{ ACCESS_PERIOD_ACTIVE }}" @if($enrollment->status == ACCESS_PERIOD_ACTIVE) selected @endif>{{ __('Active') }}</option>
                                                <option value="{{ ACCESS_PERIOD_DEACTIVATE }}" @if($enrollment->status == ACCESS_PERIOD_DEACTIVATE) selected @endif>{{ __('Revoke') }}</option>
                                            </select>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            <div class="mt-3">
                                {{@$enrollments->links()}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Page content area end -->
@endsection

@push('script')
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
            confirmButtonText: "{{__('Yes, Change it!')}}",
            cancelButtonText: "{{__('No, cancel!')}}",
            reverseButtons: true
        }).then(function (result) {
            if (result.value) {
                $.ajax({
                    type: "POST",
                    url: "{{route('admin.student.changeEnrollmentStatus')}}",
                    data: {"status": status_value, "id": id, "_token": "{{ csrf_token() }}",},
                    datatype: "json",
                    success: function (data) {
                        toastr.options.positionClass = 'toast-bottom-right';
                        toastr.success('', '{{ __("Enrollment status has been updated") }}');
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