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
                                <h2>{{__('Subscription View')}}</h2>
                            </div>
                        </div>
                        <div class="breadcrumb__content__right">
                            <nav aria-label="breadcrumb">
                                <ul class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">{{__('Dashboard')}}</a></li>
                                    <li class="breadcrumb-item"><a href="{{route('admin.subscriptions.index')}}">{{__('Subscription')}}</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">{{__('Subscription View')}}</li>
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
                                <img src="{{asset($subscription->icon)}}" alt="img">
                            </div>
                            <div class="user-text">
                                <h2>{{$subscription->title}}</h2>
                            </div>
                        </div>
                        <div class="profile__item__content">
                            <h2>{{__('Personal Information')}}</h2>
                            <p>
                                {{$subscription->details}}
                            </p>
                        </div>
                        <ul class="profile__item__list">
                            <li>
                                <div class="list-item">
                                    <h2>{{__('Monthly Price')}}:</h2>
                                    <p>{{$subscription->monthly_price}}</p>
                                </div>
                            </li>
                            <li>
                                <div class="list-item">
                                    <h2>{{__('Discounted Monthly Price')}}:</h2>
                                    <p>{{$subscription->discounted_monthly_price}}</p>
                                </div>
                            </li>
                            <li>
                                <div class="list-item">
                                    <h2>{{__('Monthly Price')}}:</h2>
                                    <p>{{$subscription->yearly_price}}</p>
                                </div>
                            </li>
                            <li>
                                <div class="list-item">
                                    <h2>{{__('Discounted Monthly Price')}}:</h2>
                                    <p>{{$subscription->discounted_yearly_price}}</p>
                                </div>
                            </li>
                            <li>
                                <div class="list-item">
                                    <h2>{{__('Enroll Course Limit')}}:</h2>
                                    <p>{{$subscription->course}}</p>
                                </div>
                            </li>
                            <li>
                                <div class="list-item">
                                    <h2>{{__('Consultancy Limit')}}:</h2>
                                    <p>{{$subscription->consultancy}}</p>
                                </div>
                            </li>
                            <li>
                                <div class="list-item">
                                    <h2>{{__('Bundle Course Limit')}}:</h2>
                                    <p>{{$subscription->bundle_course}}</p>
                                </div>
                            </li>
                            <li>
                                <div class="list-item">
                                    <h2>{{__('Device')}}:</h2>
                                    <p>{{$subscription->device}}</p>
                                </div>
                            </li>
                            
                            <li>
                                <div class="list-item">
                                    <h2>{{__('Status')}}:</h2>
                                    <p>{{ getPackageStatus($subscription->status) }}</p>
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
                                        <h2>{{ $totalSubscription }}</h2>
                                        <p>{{ __('Total Subscribe') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="profile__timeline__area bg-style">
                        <div class="item-title">
                            <h2>{{__('Subscriber')}}</h2>
                        </div>
                        <div class="profile__table">
                            <table class="table-style">
                                <thead>
                                <tr>
                                    <th>{{__('Image')}}</th>
                                    <th>{{__('Name')}}</th>
                                    <th>{{__('Status')}}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($userSubscriptions as $userSubscription)
                                    <tr>
                                        <td>
                                            <a href="{{route('student.view', [@$userSubscription->user->student->uuid])}}"><img src="{{asset(@$userSubscription->user ? @$userSubscription->user->image_path  : '')}}" alt="course" class="img-fluid" width="80"></a>
                                        </td>
                                        <td>
                                            <span class="data-text"><a href="{{route('student.view', [@$userSubscription->user->student->uuid])}}">{{ @$userSubscription->user->student->name }}</a></span>
                                        </td>
                                        <td>
                                            <span class="data-text">{{ getPackageStatus($userSubscription->status) }}</span>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            <div class="mt-3">
                                {{@$userSubscriptions->links()}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Page content area end -->
@endsection
