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
                                <h2>{{__('Edit User')}}</h2>
                            </div>
                        </div>
                        <div class="breadcrumb__content__right">
                            <nav aria-label="breadcrumb">
                                <ul class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">{{__('Dashboard')}}</a></li>
                                    <li class="breadcrumb-item"><a href="{{route('user.index')}}">{{__('All Users')}}</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">{{ __($title) }}</li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-horizontal__item bg-style">
                        <div class="item-top mb-30">
                            <h2>{{__('Edit User')}}</h2>
                        </div>
                        <form action="{{route('user.update', [$user->id])}}" method="post" enctype="multipart/form-data">
                            @csrf

                            <div class="custom-form-group mb-3 row">
                                <label for="name" class="col-lg-3 text-lg-right text-black"> {{__('Name')}} </label>
                                <div class="col-lg-9">
                                    <input type="text" name="name" id="name" value="{{$user->name}}" class="form-control flat-input" placeholder="{{__('Name')}}">
                                    @if ($errors->has('name'))
                                        <span class="text-danger"><i class="fas fa-exclamation-triangle"></i> {{ $errors->first('name') }}</span>
                                    @endif
                                </div>
                            </div>

                            <div class="custom-form-group mb-3 row">
                                <label for="email" class="col-lg-3 text-lg-right text-black"> {{__('Email')}} </label>
                                <div class="col-lg-9">
                                    <input type="email" name="email" id="email" value="{{$user->email}}" class="form-control flat-input" placeholder="{{__('Email')}}">
                                    @if ($errors->has('email'))
                                        <span class="text-danger"><i class="fas fa-exclamation-triangle"></i> {{ $errors->first('email') }}</span>
                                    @endif
                                </div>
                            </div>

                            <div class="custom-form-group mb-3 row">
                                <label class="col-lg-3 text-lg-right text-black">{{ __('Area
                                    Code')
                                    }}</label>
                                <div class="col-lg-9">
                                    <div class="input__group">
                                        <select class="form-control" name="area_code">
                                            <option value>{{ __("Select Code") }}</option>
                                            @foreach ($countries as $country)
                                            <option value="{{ $country->phonecode }}" @if(old('area_code', $user->area_code)==$country->
                                                phonecode) selected @endif>{{ $country->short_name.'('.$country->phonecode.')' }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @if ($errors->has('area_code'))
                                    <span class="text-danger"><i class="fas fa-exclamation-triangle"></i> {{
                                        $errors->first('area_code') }}</span>
                                    @endif
                                </div>
                            </div>

                            <div class="custom-form-group mb-3 row">
                                <label for="phone_number" class="col-lg-3 text-lg-right text-black"> {{__('Phone')}} </label>
                                <div class="col-lg-9">
                                    <input type="text" name="phone_number" id="phone_number" value="{{$user->phone_number}}" class="form-control flat-input" placeholder="{{__('Phone')}}">
                                    @if ($errors->has('phone_number'))
                                        <span class="text-danger"><i class="fas fa-exclamation-triangle"></i> {{ $errors->first('phone_number') }}</span>
                                    @endif
                                </div>
                            </div>

                            <div class="custom-form-group mb-3 row">
                                <label for="address" class="col-lg-3 text-lg-right text-black"> {{__('Address')}} </label>
                                <div class="col-lg-9">
                                    <textarea name="address" id="address" class="form-control" placeholder="{{__('Address')}}">{{$user->address}}</textarea>
                                    @if ($errors->has('address'))
                                        <span class="text-danger"><i class="fas fa-exclamation-triangle"></i> {{ $errors->first('address') }}</span>
                                    @endif
                                </div>
                            </div>

                            <div class="custom-form-group mb-3 row">
                                <label for="role_name" class="col-lg-3 text-lg-right text-black"> {{__('Select Role')}} </label>
                                <div class="col-lg-9">
                                    <select name="role_name" id="role_name" class="form-control">
                                        <option value="">{{__('Select Role')}}</option>
                                        @foreach($roles as $role)
                                            <option value="{{$role->name}}"  @if(count($user->getRoleNames()) > 0) {{$user->getRoleNames()[0] == $role->name ? 'selected' : '' }}@endif >{{$role->name}}</option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('role_name'))
                                        <span class="text-danger"><i class="fas fa-exclamation-triangle"></i> {{ $errors->first('role_name') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-12 text-right">
                                    @updateButton
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Page content area end -->
@endsection

