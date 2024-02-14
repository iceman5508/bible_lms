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
                                <h2>{{__('Edit Template')}}</h2>
                            </div>
                        </div>
                        <div class="breadcrumb__content__right">
                            <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                                <ul class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">{{__('Dashboard')}}</a></li>
                                    <li class="breadcrumb-item"><a href="{{route('email-template.index')}}">{{__('Email Template')}}</a></li>
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
                            <h2>{{__('Edit Template')}}</h2>
                        </div>
                        <form action="{{route('email-template.update', [$template->uuid])}}" method="post" enctype="multipart/form-data">
                            @csrf

                            <div class="custom-form-group mb-3 row">
                                <label for="name" class="col-lg-3 text-lg-right text-black"> {{__('Name')}} </label>
                                <div class="col-lg-9">
                                    <input type="text" name="name" id="name" value="{{$template->name}}" class="form-control flat-input" placeholder=" {{__('Name')}} ">
                                    @if ($errors->has('name'))
                                        <span class="text-danger"><i class="fas fa-exclamation-triangle"></i> {{ $errors->first('name') }}</span>
                                    @endif
                                </div>
                            </div>

                            <div class="custom-form-group mb-3 row">
                                <label for="subject" class="col-lg-3 text-lg-right text-black"> {{__('Subject')}} </label>
                                <div class="col-lg-9">
                                    <input type="text" name="subject" id="subject" value="{{$template->subject}}" class="form-control flat-input" placeholder=" {{__('Subject')}} ">
                                    @if ($errors->has('subject'))
                                        <span class="text-danger"><i class="fas fa-exclamation-triangle"></i> {{ $errors->first('subject') }}</span>
                                    @endif
                                </div>
                            </div>

                            <div class="custom-form-group mb-3 row">
                                <label for="body" class="col-lg-3 text-lg-right text-black"> {{__('Body')}} </label>
                                <div class="col-lg-9">
                                    <textarea name="body" id="body" class="form-control" placeholder="{{__('Body')}}" rows="10">{{$template->body}}</textarea>
                                    @if ($errors->has('body'))
                                        <span class="text-danger"><i class="fas fa-exclamation-triangle"></i> {{ $errors->first('body') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="mb-3">
                                <div class="col-md-12 text-right">
                                    <button type="submit" class="btn btn-blue mr-30">{{__('Save')}}</button>
                                    <a href="javascript:void(0)" class="btn btn-green preview-template"><i class="fa fa-eye"></i> {{ __('Preview') }}</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Page content area end -->

    <!--previewModal Modal Start-->
    <div class="modal fade" id="previewModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <h4 id="mail_subject"></h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="body_content"></div>
                </div>
            </div>
        </div>
    </div>
    <!--previewModal Modal End-->

@endsection

@push('script')
    <script src="{{asset('admin/js/custom/email-template.js')}}"></script>
@endpush
