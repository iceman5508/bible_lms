@extends('layouts.instructor')

@section('breadcrumb')
<div class="page-banner-content text-center">
    <h3 class="page-banner-heading text-white pb-15"> {{__('Instructor Request')}} </h3>

    <!-- Breadcrumb Start-->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb justify-content-center">
            <li class="breadcrumb-item font-14"><a href="{{route('instructor.dashboard')}}">{{__('Dashboard')}}</a></li>
            <li class="breadcrumb-item font-14 active" aria-current="page">{{__('Instructor Request')}}</li>
        </ol>
    </nav>
</div>
@endsection

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12 mt-25 instructor-my-courses-title d-flex justify-content-between align-items-center">
            <h6>{{ __('Instructor Request') }}</h6>
        </div>
        <div class="col-md-12">
            <div class="">
                <table id="instructor-request-datatable" class="table">
                    <thead>
                        <tr>
                            <th class="all">{{__('Course')}}</th>
                            <th class="none">{{__('Requested By')}}</th>
                            <th class="none">{{__('Share (%)')}}</th>
                            <th class="all">{{__('Status')}}</th>
                            <th class="all">{{__('Action')}}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($instructorRequests as $instructorRequest)
                        @php
                            $relation = getUserRoleRelation($instructorRequest->course->user);
                        @endphp
                        <tr>
                            <td>{{ $instructorRequest->course->title }}</td>
                            <td>{{ $instructorRequest->course->$relation->first_name.'
                                '.$instructorRequest->course->$relation->last_name }}</td>
                            <td>{{ $instructorRequest->share }}</td>
                            <td><span class="status {{statusClass($instructorRequest->status)}}">{{
                                    statusAction($instructorRequest->status) }}</span></td>
                            <td>
                                <div class="red-blue-action-btns">
                                    <form method="POST"
                                        action="{{ route('instructor.multi_instructor.change_status', $instructorRequest->id) }}">
                                        @csrf
                                        <input type="hidden" name="status" value="{{ STATUS_ACCEPTED }}">
                                        <button type="submit" {{ ($instructorRequest->status == STATUS_ACCEPTED) ?
                                            'disabled' : '' }} class="{{ ($instructorRequest->status == STATUS_ACCEPTED)
                                            ? 'disabled-btn' : '' }} theme-btn theme-button1 default-hover-btn">{{
                                            __('ACCEPT') }}</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5">
                                <!-- If there is no data Show Empty Design Start -->
                                <div class="empty-data">
                                    <img src="{{ asset('frontend/assets/img/empty-data-img.png') }}" alt="img"
                                        class="img-fluid">
                                    <h5 class="my-3">{{ __('Empty Request') }}</h5>
                                </div>
                                <!-- If there is no data Show Empty Design End -->
                            </td>
                            @endforelse
                        </tr>
                    </tbody>

                </table>
                <!-- Pagination Start -->
                @if(@$instructorRequests->hasPages())
                {{ @$instructorRequests->links('frontend.paginate.paginate') }}
                @endif
                <!-- Pagination End -->
            </div>
        </div>
    </div>
</div>
@endsection
