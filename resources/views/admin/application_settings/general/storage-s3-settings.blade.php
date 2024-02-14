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
                                <h2>{{ __('Application Settings') }}</h2>
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
                        <form action="{{route('settings.storage-settings.update')}}" method="post" class="form-horizontal">
                            @csrf
                            <div class="form-group text-black row mb-3">
                                <label class="col-lg-3">{{ __('Video Storage Driver') }} <span class="text-danger">*</span></label>
                                <div class="col-lg-9">
                                    <select name="STORAGE_DRIVER" id="storage_driver" class="form-control" required>
                                        <option value="public" @if(env('STORAGE_DRIVER') == "public") selected @endif>Public</option>
                                        <option value="s3" @if(env('STORAGE_DRIVER') == "s3") selected @endif>AWS S3</option>
                                        <option value="wasabi" @if(env('STORAGE_DRIVER') == "wasabi") selected @endif>Wasabi S3</option>
                                        <option value="vultr" @if(env('STORAGE_DRIVER') == "vultr") selected @endif>Vultr S3</option>
                                    </select>
                                </div>
                            </div>
                            <div class="d-none" id="aws">
                                <div class="row input__group mb-25">
                                    <label class="col-lg-3">{{ __('AWS Access Key ID') }} <span class="text-danger">*</span></label>
                                    <div class="col-lg-9">
                                        <input type="text" name="AWS_ACCESS_KEY_ID" value="{{ env('AWS_ACCESS_KEY_ID') }}" class="form-control" >
                                    </div>
                                </div>
                                <div class="row input__group mb-25">
                                    <label class="col-lg-3">{{ __('AWS Secret Access Key') }} <span class="text-danger">*</span></label>
                                    <div class="col-lg-9">
                                        <input type="text" name="AWS_SECRET_ACCESS_KEY" value="{{ env('AWS_SECRET_ACCESS_KEY') }}" class="form-control" >
                                    </div>
                                </div>
                                <div class="row input__group mb-25">
                                    <label class="col-lg-3">{{ __('AWS Default Region') }} <span class="text-danger">*</span></label>
                                    <div class="col-lg-9">
                                        <input type="text" name="AWS_DEFAULT_REGION" value="{{ env('AWS_DEFAULT_REGION') }}" class="form-control" >
                                    </div>
                                </div>
                                <div class="row input__group mb-25">
                                    <label class="col-lg-3">{{ __('AWS Bucket') }} <span class="text-danger">*</span></label>
                                    <div class="col-lg-9">
                                        <input type="text" name="AWS_BUCKET" value="{{ env('AWS_BUCKET') }}" class="form-control" >
                                    </div>
                                </div>
                            </div>

                            <div class="d-none" id="wasabi">
                                <div class="row input__group mb-25">
                                    <label class="col-lg-3">{{ __('WAS Access Key ID') }} <span class="text-danger">*</span></label>
                                    <div class="col-lg-9">
                                        <input type="text" name="WASABI_ACCESS_KEY_ID" value="{{ env('WASABI_ACCESS_KEY_ID') }}" class="form-control">
                                    </div>
                                </div>
                                <div class="row input__group mb-25">
                                    <label class="col-lg-3">{{ __('WAS Secret Access Key') }} <span class="text-danger">*</span></label>
                                    <div class="col-lg-9">
                                        <input type="text" name="WASABI_SECRET_ACCESS_KEY" value="{{ env('WASABI_SECRET_ACCESS_KEY') }}" class="form-control">
                                    </div>
                                </div>
                                <div class="row input__group mb-25">
                                    <label class="col-lg-3">{{ __('WAS Default Region') }} <span class="text-danger">*</span></label>
                                    <div class="col-lg-9">
                                        <input type="text" name="WASABI_DEFAULT_REGION" value="{{ env('WASABI_DEFAULT_REGION') }}" class="form-control">
                                    </div>
                                </div>
                                <div class="row input__group mb-25">
                                    <label class="col-lg-3">{{ __('WAS Bucket') }} <span class="text-danger">*</span></label>
                                    <div class="col-lg-9">
                                        <input type="text" name="WASABI_BUCKET" value="{{ env('WASABI_BUCKET') }}" class="form-control">
                                    </div>
                                </div>
                            </div>

                            <div class="d-none" id="vultr">
                                <div class="row input__group mb-25">
                                    <label class="col-lg-3">{{ __('VULTR Access Key') }} <span class="text-danger">*</span></label>
                                    <div class="col-lg-9">
                                        <input type="text" name="VULTR_ACCESS_KEY_ID" value="{{ env('VULTR_ACCESS_KEY_ID') }}" class="form-control">
                                    </div>
                                </div>
                                <div class="row input__group mb-25">
                                    <label class="col-lg-3">{{ __('VULTR Secret Key') }} <span class="text-danger">*</span></label>
                                    <div class="col-lg-9">
                                        <input type="text" name="VULTR_SECRET_ACCESS_KEY" value="{{ env('VULTR_SECRET_ACCESS_KEY') }}" class="form-control">
                                    </div>
                                </div>
                                <div class="row input__group mb-25">
                                    <label class="col-lg-3">{{ __('VULTR Region') }} <span class="text-danger">*</span></label>
                                    <div class="col-lg-9">
                                        <input type="text" name="VULTR_DEFAULT_REGION" value="{{ env('VULTR_DEFAULT_REGION') }}" class="form-control">
                                    </div>
                                </div>
                                <div class="row input__group mb-25">
                                    <label class="col-lg-3">{{ __('VULTR Bucket') }} <span class="text-danger">*</span></label>
                                    <div class="col-lg-9">
                                        <input type="text" name="VULTR_BUCKET" value="{{ env('VULTR_BUCKET') }}" class="form-control">
                                    </div>
                                </div>
                            </div>

                            <div class="mb-20 row text-end">
                                <div class="col">
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


@push('script')
    <script>
        $(function (){
            var value = $('#storage_driver').val();
            storage(value)
        })

        $('#storage_driver').on('change', function (){
            var value = this.value
            storage(value)
        })

        function storage(STORAGE_DRIVER)
        {
            if (STORAGE_DRIVER == 'public') {
                $('#aws').addClass('d-none');
                $('#wasabi').addClass('d-none');
                $('#vultr').addClass('d-none');
                $('#do').addClass('d-none');
            } else if(STORAGE_DRIVER == 's3') {
                $('#aws').removeClass('d-none');
                $('#wasabi').addClass('d-none');
                $('#vultr').addClass('d-none');
                $('#do').addClass('d-none');
            } else if(STORAGE_DRIVER == 'wasabi') {
                $('#aws').addClass('d-none');
                $('#wasabi').removeClass('d-none');
                $('#vultr').addClass('d-none');
                $('#do').addClass('d-none');
            } else if(STORAGE_DRIVER == 'vultr') {
                $('#aws').addClass('d-none');
                $('#wasabi').addClass('d-none');
                $('#vultr').removeClass('d-none');
                $('#do').addClass('d-none');
            }
        }
    </script>
@endpush
