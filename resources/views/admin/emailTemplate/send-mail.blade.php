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
                                <h2>{{__('Send Email')}}</h2>
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
                            <h2>{{__('Send Email')}}</h2>
                        </div>
                        <form action="{{route('email-template.send-email-to-user')}}" method="post" enctype="multipart/form-data">
                            @csrf

                            <div class="custom-form-group mb-3 row">
                                <label for="email_template_id" class="col-lg-3 text-lg-right text-black"> {{__('Name')}} </label>
                                <div class="col-lg-9">
                                   <select name="email_template_id" class="form-control" id="email_template_id">
                                        <option value="">-- {{ __('Select Template') }} --</option>
                                       @foreach($email_templates as $template)
                                        <option value="{{$template->id}}" {{old('email_template_id') == $template->id ? 'selected' : '' }} >{{$template->name}}</option>
                                       @endforeach
                                   </select>
                                    @if ($errors->has('email_template_id'))
                                        <span class="text-danger"><i class="fas fa-exclamation-triangle"></i> {{ $errors->first('email_template_id') }}</span>
                                    @endif
                                </div>
                            </div>

                            <div class="custom-form-group mb-3 row">
                                <label for="sender_type" class="col-lg-3 text-lg-right text-black"> {{__('Type')}} </label>
                                <div class="col-lg-9">
                                    <select name="sender_type" class="form-control" id="sender_type">
                                        <option value="">-- {{ __('Sender Type') }} --</option>
                                            <option value="instructor" {{old('sender_type') == 'instructor' ? 'selected' : '' }}>{{ __('Instructor') }}</option>
                                            <option value="student" {{old('sender_type') == 'student' ? 'selected' : '' }} >{{ __('Student') }}</option>
                                            <option value="from_csv" {{old('sender_type') == 'from_csv' ? 'selected' : '' }} >{{ __('Upload CSV') }}</option>
                                    </select>



                                    <div class="mt-3 d-flex justify-content-between {{old('sender_type') == 'from_csv' ? '' : 'd-none' }} " id="csv_mail_list">
                                        <div><input type="file" name="mail_list" accept=".csv" /></div>
                                        <p class="font-14 color-heading text-center mt-2 color-gray"><a href="{{url('bulk_upload/send-mail.csv')}}" download=""> <i class="fa fa-download"></i> {{ __('Sample File') }}</a> </p>
                                    </div>

                                    @if ($errors->has('sender_type'))
                                        <span class="text-danger"><i class="fas fa-exclamation-triangle"></i> {{ $errors->first('sender_type') }}</span>
                                    @endif

                                </div>
                            </div>
                            <div class="mb-3">
                                <div class="col-md-12 text-right">
                                    <button type="submit" class="btn btn-blue mr-30">{{__('Send')}}</button>
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

@push('script')
    <script src="{{asset('admin/js/custom/email-template.js')}}"></script>
@endpush
