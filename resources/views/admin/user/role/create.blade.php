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
                                <h2>{{__('Add Role')}}</h2>
                            </div>
                        </div>
                        <div class="breadcrumb__content__right">
                            <nav aria-label="breadcrumb">
                                <ul class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">{{__('Dashboard')}}</a></li>
                                    <li class="breadcrumb-item"><a href="{{route('role.index')}}">{{__('Roles')}}</a></li>
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
                            <h2>{{__('Add Role')}}</h2>
                        </div>
                        <form action="{{route('role.store')}}" method="post" enctype="multipart/form-data">
                            @csrf

                            <div class="custom-form-group mb-3 row">
                                <label for="name" class="col-lg-3 text-lg-right text-black"> {{__('Name')}} </label>
                                <div class="col-lg-9">
                                    <input type="text" name="name" id="name" value="{{old('name')}}" class="form-control flat-input" placeholder="{{__('Name')}}">
                                    @if ($errors->has('name'))
                                        <span class="text-danger"><i class="fas fa-exclamation-triangle"></i> {{ $errors->first('name') }}</span>
                                    @endif
                                </div>
                            </div>

                            <div class="custom-form-group mb-3 row">
                                <label for="name" class="col-lg-3 text-lg-right text-black"> {{__('Select Permission')}} </label>
                                <div class="col-lg-9">
                                   <div class="row text-black">
                                       @foreach($permissions as $permission)
                                       <div class="col-md-4 mb-2">
                                           <div class="form-check mb-0 d-flex align-items-center">
                                               <input class="form-check-input" type="checkbox" name="permissions[]" id="permission{{$permission->id}}" value="{{$permission->id}}" >
                                               <label class="form-check-label m-lg-1 mb-0 color-heading" for="permission{{$permission->id}}">{{ucwords(str_ireplace("_", " ", $permission->name))}} </label>
                                           </div>
                                       </div>
                                       @endforeach
                                   </div>
                                    @if ($errors->has('permissions'))
                                        <span class="text-danger"><i class="fas fa-exclamation-triangle"></i> {{ $errors->first('permissions') }}</span>
                                    @endif
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-12 text-right">
                                    @saveWithAnotherButton
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
