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
                                <h2>{{__('Add Language')}}</h2>
                            </div>
                        </div>
                        <div class="breadcrumb__content__right">
                            <nav aria-label="breadcrumb">
                                <ul class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">{{__('Dashboard')}}</a></li>
                                    <li class="breadcrumb-item"><a href="{{route('course-language.index')}}">{{__('Languages')}}</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">{{__('Add Language')}}</li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-vertical__item bg-style">
                            <div class="item-top mb-30">
                                <h2>{{__('Add Language')}}</h2>
                            </div>
                            <form action="{{route('course-language.store')}}" method="post" enctype="multipart/form-data">
                                @csrf

                                <div class="input__group mb-25">
                                    <label for="name"> {{__('Name')}}<span class="text-danger">*</span></label>
                                    <div>
                                        <input type="text" name="name" id="name" value="{{old('name')}}" class="form-control" placeholder=" {{__('Name')}} ">
                                        @if ($errors->has('name'))
                                            <span class="text-danger"><i class="fas fa-exclamation-triangle"></i> {{ $errors->first('name') }}</span>
                                        @endif
                                    </div>
                                </div>

                                <div class="input__group">
                                    <div>
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

