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
                                <h2>{{__('SaaS View')}}</h2>
                            </div>
                        </div>
                        <div class="breadcrumb__content__right">
                            <nav aria-label="breadcrumb">
                                <ul class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">{{__('Dashboard')}}</a></li>
                                    <li class="breadcrumb-item"><a href="{{route('admin.saas.index')}}">{{__('SaaS')}}</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">{{__('SaaS View')}}</li>
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
                                <img src="{{asset($saas->icon)}}" alt="img">
                            </div>
                            <div class="user-text">
                                <h2>{{$saas->title}}</h2>
                            </div>
                        </div>
                        <div class="profile__item__content">
                            <h2>{{__('Personal Information')}}</h2>
                            <p>
                                {{$saas->details}}
                            </p>
                        </div>
                        <ul class="profile__item__list">
                            <li>
                                <div class="list-item">
                                    <h2>{{__('SaaS Type')}}:</h2>
                                    <p>{{($saas->package_type == PACKAGE_TYPE_SAAS_INSTRUCTOR) ? __('Instructor SaaS') : __('Organization SaaS')}}</p>
                                </div>
                            </li>
                            <li>
                                <div class="list-item">
                                    <h2>{{__(' Price')}}:</h2>
                                    <p>{{$saas->monthly_price}}</p>
                                </div>
                            </li>
                            <li>
                                <div class="list-item">
                                    <h2>{{__('Monthly Price')}}:</h2>
                                    <p>{{$saas->monthly_price}}</p>
                                </div>
                            </li>
                            <li>
                                <div class="list-item">
                                    <h2>{{__('Discounted Monthly Price')}}:</h2>
                                    <p>{{$saas->discounted_monthly_price}}</p>
                                </div>
                            </li>
                            <li>
                                <div class="list-item">
                                    <h2>{{__('Monthly Price')}}:</h2>
                                    <p>{{$saas->yearly_price}}</p>
                                </div>
                            </li>
                            <li>
                                <div class="list-item">
                                    <h2>{{__('Discounted Monthly Price')}}:</h2>
                                    <p>{{$saas->discounted_yearly_price}}</p>
                                </div>
                            </li>
                            <li>
                                <div class="list-item">
                                    <h2>{{__('Admin Commission')}}:</h2>
                                    <p>{{$saas->admin_commission}}%</p>
                                </div>
                            </li>
                            @if($saas->package_type == PACKAGE_TYPE_SAAS_ORGANIZATION)
                            <li>
                                <div class="list-item">
                                    <h2>{{__('Student Limit')}}:</h2>
                                    <p>{{$saas->student}}</p>
                                </div>
                            </li>
                            <li>
                                <div class="list-item">
                                    <h2>{{__('Instructor Limit')}}:</h2>
                                    <p>{{$saas->instructor}}</p>
                                </div>
                            </li>
                            @endif
                            <li>
                                <div class="list-item">
                                    <h2>{{__('Course Limit')}}:</h2>
                                    <p>{{$saas->course}}</p>
                                </div>
                            </li>
                            <li>
                                <div class="list-item">
                                    <h2>{{__('Bundle Course Limit')}}:</h2>
                                    <p>{{$saas->bundle_course}}</p>
                                </div>
                            </li>
                            <li>
                                <div class="list-item">
                                    <h2>{{__('Subscription Course Limit')}}:</h2>
                                    <p>{{$saas->consultancy}}</p>
                                </div>
                            </li>
                            <li>
                                <div class="list-item">
                                    <h2>{{__('Consultancy Limit')}}:</h2>
                                    <p>{{$saas->consultancy}}</p>
                                </div>
                            </li>
                            <li>
                                <div class="list-item">
                                    <h2>{{__('Status')}}:</h2>
                                    <p>{{ getPackageStatus($saas->status) }}</p>
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
                                        <h2>{{ $totalSaases }}</h2>
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
                                @foreach($userSaases as $userSaas)
                                    <tr>
                                        <td>
                                            @if($saas->package_type == PACKAGE_TYPE_SAAS_INSTRUCTOR)
                                            <a href="{{route('instructor.view', [@$userSaas->user->instructor->uuid])}}"><img src="{{asset(@$userSaas->user ? @$userSaas->user->image_path  : '')}}" alt="course" class="img-fluid" width="80"></a>
                                            @else
                                            {{-- TODO: organization --}}
                                            {{-- <a href="{{route('student.view', [@$userSaas->user->student->uuid])}}"><img src="{{asset(@$userSaas->user ? @$userSaas->user->image_path  : '')}}" alt="course" class="img-fluid" width="80"></a> --}}
                                            @endif
                                        </td>
                                        <td>
                                            @if($saas->package_type == PACKAGE_TYPE_SAAS_INSTRUCTOR)
                                            <span class="data-text"><a href="{{route('instructor.view', [@$userSaas->user->instructor->uuid])}}">{{ @$userSaas->user->instructor->name }}</a></span>
                                            @else
                                            {{-- TODO: organization --}}
                                            {{-- <span class="data-text"><a href="{{route('instructor.view', [@$userSaas->user->instructor->uuid])}}">{{ @$userSaas->user->instructor->name }}</a></span> --}}
                                            @endif
                                        </td>
                                        <td>
                                            <span class="data-text">{{ getPackageStatus($userSaas->status) }}</span>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            <div class="mt-3">
                                {{@$userSaases->links()}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Page content area end -->
@endsection
