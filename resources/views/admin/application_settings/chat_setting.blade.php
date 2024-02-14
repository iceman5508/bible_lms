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
                                <h2>{{ __('Application Setting') }}</h2>
                            </div>
                        </div>
                        <div class="breadcrumb__content__right">
                            <nav aria-label="breadcrumb">
                                <ul class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">{{__('Dashboard')}}</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">{{ __(@$title) }}</li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-3 col-md-4">
                    @include('admin.application_settings.sidebar')
                </div>
                <div class="col-lg-9 col-md-8">
                    <div class="email-inbox__area bg-style">
                        <div class="item-top mb-30"><h2>{{ __(@$title) }}</h2></div>
                        <form action="{{route('settings.save.setting')}}" method="post" class="form-horizontal">
                            @csrf

                            <div class="row">
                                <div class="col-lg-4">
                                    <label>{{ __('Chat System Driver') }} <span class="text-danger">*</span></label>
                                    <select name="broadcast_default" required class="form-control">
                                        <option value="pusher" @if(get_option('broadcast_default') == 'pusher') selected @endif>{{ __('Pusher') }}</option>
                                        <option value="null" @if(get_option('broadcast_default') == 'null') selected @endif>{{ __('Ajax') }}</option>
                                    </select>
                                    @if ($errors->has('broadcast_default'))
                                        <span class="text-danger"><i class="fas fa-exclamation-triangle"></i> {{ $errors->first('broadcast_default') }}</span>
                                    @endif
                                </div>
                                <div class="col-lg-4">
                                    <label>{{ __('Pusher ID') }} <span class="text-danger">*</span></label>
                                    <input type="text" value="{{ get_option('pusher_app_id') }}" class="form-control" name="pusher_app_id">
                                    @if ($errors->has('pusher_app_id'))
                                        <span class="text-danger"><i class="fas fa-exclamation-triangle"></i> {{ $errors->first('pusher_app_id') }}</span>
                                    @endif
                                </div>
                                <div class="col-lg-4">
                                    <label>{{ __('Pusher Key') }} <span class="text-danger">*</span></label>
                                    <input type="text" value="{{ get_option('pusher_app_key') }}" class="form-control" name="pusher_app_key">
                                    @if ($errors->has('pusher_app_key'))
                                        <span class="text-danger"><i class="fas fa-exclamation-triangle"></i> {{ $errors->first('pusher_app_key') }}</span>
                                    @endif
                                </div>
                                <div class="col-lg-4">
                                    <label>{{ __('Pusher Secret') }} <span class="text-danger">*</span></label>
                                    <input type="text" value="{{ get_option('pusher_app_secret') }}" class="form-control" name="pusher_app_secret">
                                    @if ($errors->has('pusher_app_secret'))
                                        <span class="text-danger"><i class="fas fa-exclamation-triangle"></i> {{ $errors->first('pusher_app_secret') }}</span>
                                    @endif
                                </div>
                                <div class="col-lg-4">
                                    <label>{{ __('Pusher Cluster') }} <span class="text-danger">*</span></label>
                                    <input type="text" value="{{ get_option('pusher_app_cluster') }}" class="form-control" name="pusher_app_cluster">
                                    @if ($errors->has('pusher_app_cluster'))
                                        <span class="text-danger"><i class="fas fa-exclamation-triangle"></i> {{ $errors->first('pusher_app_cluster') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="row justify-content-end mt-3">
                                <div class="col-md-1">
                                    <button type="submit" class="btn btn-blue float-right">{{ __('Update') }}</button>
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
