@extends('layouts.instructor')

@section('breadcrumb')
    <div class="page-banner-content text-center">
        <h3 class="page-banner-heading text-white pb-15"> {{__('Affiliate  Request List')}} </h3>

        <!-- Breadcrumb Start-->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb justify-content-center">
                <li class="breadcrumb-item font-14"><a href="{{route('instructor.dashboard')}}">{{__('Dashboard')}}</a></li>
                <li class="breadcrumb-item font-14"><a href="{{ route('instructor.request-list') }}">{{ __('Request List') }}</a></li>
            </ol>
        </nav>
    </div>
@endsection

@section('content')
    <div class="instructor-profile-right-part">
        <div class="instructor-quiz-list-page instructor-notice-list-page">

            <div class="row">
                <div class="col-12">
                    @if(count($requests) > 0)
                        <div class="table-responsive table-responsive-xl">
                            <table class="table">
                                <thead>
                                <tr>
                                    <th scope="col">{{__('Time')}}</th>
                                    <th scope="col">{{__('Request Name')}}</th>
                                    <th scope="col">{{__('Address')}}</th>
                                    <th scope="col">{{__('Comments')}}</th>
                                    <th scope="col">{{__('Status')}}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($requests as $request)
                                    <tr>
                                        <td>{{ $request->created_at }}</td>
                                        <td>{{ $request->user->name }}</td>
                                        <td>{{ $request->address }}</td>
                                        <td>{{ $request->comments }}</td>
                                        <td>{{ statusAction($request->status) }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        <!-- Pagination Start -->
                        @if(@$requests->hasPages())
                            {{ @$requests->links('frontend.paginate.paginate') }}
                        @endif
                        <!-- Pagination End -->
                    @else
                        <!-- If there is no data Show Empty Design Start -->
                        <div class="empty-data">
                            <img src="{{ asset('frontend/assets/img/empty-data-img.png') }}" alt="img" class="img-fluid">
                            <h5 class="my-3">{{__('Empty Request')}}</h5>
                        </div>
                        <!-- If there is no data Show Empty Design End -->
                    @endif
                    <!-- Add Notice Button Start -->
                    <!-- Add Notice Button End -->

                </div>
            </div>

        </div>
    </div>
@endsection
