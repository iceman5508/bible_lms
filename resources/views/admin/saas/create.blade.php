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
                                <h2>{{ __('Add SaaS Package') }}</h2>
                            </div>
                        </div>
                        <div class="breadcrumb__content__right">
                            <nav aria-label="breadcrumb">
                                <ul class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">{{__('Dashboard')}}</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">{{ __('Add SaaS Package') }}</li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="customers__area bg-style mb-30">
                        <div class="item-title d-flex justify-content-between">
                            <h2>{{ __('Add SaaS Package') }}</h2>
                        </div>
                        <form action="{{route('admin.saas.store')}}" method="post" class="form-horizontal" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="input__group mb-25">
                                        <label class="label-text-title color-heading font-medium font-16 mb-3">{{ __('SaaS Type')
                                            }} <span class="text-danger">*</span></label>
                                        <select class="form-control" name="package_type">
                                            <option value="{{ PACKAGE_TYPE_SAAS_INSTRUCTOR }}">{{ __('Instructor') }}</option>
                                            <option value="{{ PACKAGE_TYPE_SAAS_ORGANIZATION }}">{{ __('Organization') }}</option>
                                        </select>
                                        @if ($errors->has('package_type'))
                                        <span class="text-danger"><i class="fas fa-exclamation-triangle"></i> {{
                                            $errors->first('package_type') }}</span>
                                        @endif
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="input__group mb-25">
                                        <label>{{__('Title')}} <span class="text-danger">*</span></label>
                                        <input type="text" name="title" value="{{old('title')}}" placeholder="{{ __('Title') }}" class="form-control" required>
                                        @if ($errors->has('title'))
                                            <span class="text-danger"><i class="fas fa-exclamation-triangle"></i> {{ $errors->first('title') }}</span>
                                        @endif
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="input__group mb-25">
                                        <label>{{__('Price Monthly')}} <span class="text-danger">*</span></label>
                                        <input type="number" min=0 name="monthly_price" value="{{old('monthly_price')}}" placeholder="{{ __('Price Monthly') }}" class="form-control" required>
                                        @if ($errors->has('monthly_price'))
                                            <span class="text-danger"><i class="fas fa-exclamation-triangle"></i> {{ $errors->first('monthly_price') }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="input__group mb-25">
                                        <label>{{__('Discounted Price Monthly')}} <span class="text-danger">*</span></label>
                                        <input type="number" min=0 name="discounted_monthly_price" value="{{old('discounted_monthly_price')}}" placeholder="{{ __('Discounted Price Monthly') }}" class="form-control" required>
                                        @if ($errors->has('discounted_monthly_price'))
                                            <span class="text-danger"><i class="fas fa-exclamation-triangle"></i> {{ $errors->first('discounted_monthly_price') }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="input__group mb-25">
                                        <label>{{__('Price Yearly')}} <span class="text-danger">*</span></label>
                                        <input type="number" min=0 name="yearly_price" value="{{old('yearly_price')}}" placeholder="{{ __('Price Yearly') }}" class="form-control" required>
                                        @if ($errors->has('yearly_price'))
                                            <span class="text-danger"><i class="fas fa-exclamation-triangle"></i> {{ $errors->first('yearly_price') }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="input__group mb-25">
                                        <label>{{__('Discounted Price Yearly')}} <span class="text-danger">*</span></label>
                                        <input type="number" min=0 name="discounted_yearly_price" value="{{old('discounted_yearly_price')}}" placeholder="{{ __('Discounted Price Yearly') }}" class="form-control" required>
                                        @if ($errors->has('discounted_yearly_price'))
                                            <span class="text-danger"><i class="fas fa-exclamation-triangle"></i> {{ $errors->first('discounted_yearly_price') }}</span>
                                        @endif
                                    </div>
                                </div>
                               
                                <div class="col-md-6">
                                    <div class="input__group mb-25">
                                        <label>{{__('Admin Commission')}}% <span class="text-danger">*</span></label>
                                        <input type="number" min=0 max=100 name="admin_commission" value="{{old('admin_commission')}}" placeholder="{{ __('Admin Commission') }}" class="form-control" required>
                                        @if ($errors->has('admin_commission'))
                                            <span class="text-danger"><i class="fas fa-exclamation-triangle"></i> {{ $errors->first('admin_commission') }}</span>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-md-6 d-none organization-block">
                                    <div class="input__group mb-25">
                                        <label>{{__('Student Limit')}} <span class="text-danger">*</span></label>
                                        <input type="number" min=0 name="student" value="{{old('student', 0)}}" placeholder="{{ __('student Limit') }}" class="form-control" required>
                                        @if ($errors->has('student'))
                                            <span class="text-danger"><i class="fas fa-exclamation-triangle"></i> {{ $errors->first('student') }}</span>
                                        @endif
                                    </div>
                                </div>
                               
                                <div class="col-md-6 d-none organization-block">
                                    <div class="input__group mb-25">
                                        <label>{{__('Instructor Limit')}} <span class="text-danger">*</span></label>
                                        <input type="number" min=0 name="instructor" value="{{old('instructor', 0)}}" placeholder="{{ __('Instructor Limit') }}" class="form-control" required>
                                        @if ($errors->has('instructor'))
                                            <span class="text-danger"><i class="fas fa-exclamation-triangle"></i> {{ $errors->first('instructor') }}</span>
                                        @endif
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="input__group mb-25">
                                        <label>{{__('Course Limit')}} <span class="text-danger">*</span></label>
                                        <input type="number" min=0 name="course" value="{{old('course', 0)}}" placeholder="{{ __('Course Limit') }}" class="form-control" required>
                                        @if ($errors->has('course'))
                                            <span class="text-danger"><i class="fas fa-exclamation-triangle"></i> {{ $errors->first('course') }}</span>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="input__group mb-25">
                                        <label>{{__('Bundle Course Limit')}} <span class="text-danger">*</span></label>
                                        <input type="number" min=0  name="bundle_course" value="{{old('bundle_course', 0)}}" placeholder="{{ __('Bundle Course Limit') }}" class="form-control" required>
                                        @if ($errors->has('bundle_course'))
                                            <span class="text-danger"><i class="fas fa-exclamation-triangle"></i> {{ $errors->first('bundle_course') }}</span>
                                        @endif
                                    </div>
                                </div>
                               
                                <div class="col-md-6">
                                    <div class="input__group mb-25">
                                        <label>{{__('Subscription Course Limit')}} <span class="text-danger">*</span></label>
                                        <input type="number" min=0  name="subscription_course" value="{{old('subscription_course', 0)}}" placeholder="{{ __('Subscription Course Limit') }}" class="form-control" required>
                                        @if ($errors->has('subscription_course'))
                                            <span class="text-danger"><i class="fas fa-exclamation-triangle"></i> {{ $errors->first('subscription_course') }}</span>
                                        @endif
                                    </div>
                                </div>
                               
                                <div class="col-md-6">
                                    <div class="input__group mb-25">
                                        <label>{{__('Consulaltency Limit')}} <span class="text-danger">*</span></label>
                                        <input type="number" min=0  name="consultancy" value="{{old('consultancy', 0)}}" placeholder="{{ __('Consulaltency Limit') }}" class="form-control" required>
                                        @if ($errors->has('consultancy'))
                                            <span class="text-danger"><i class="fas fa-exclamation-triangle"></i> {{ $errors->first('consultancy') }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="input__group mb-25">
                                        <label class="label-text-title color-heading font-medium font-16 mb-3">{{ __('Show In Home')
                                            }} <span class="text-danger">*</span></label>
                                        <select class="form-control" name="show_in_home">
                                            <option value="{{ PACKAGE_STATUS_ACTIVE }}">{{ __('YES') }}</option>
                                            <option value="{{ PACKAGE_STATUS_DISABLED }}">{{ __('YES') }}</option>
                                        </select>
                                        @if ($errors->has('show_in_home'))
                                        <span class="text-danger"><i class="fas fa-exclamation-triangle"></i> {{
                                            $errors->first('show_in_home') }}</span>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="input__group mb-25">
                                        <label class="label-text-title color-heading font-medium font-16 mb-3">{{ __('Recomended')
                                            }} <span class="text-danger">*</span></label>
                                        <select class="form-control" name="recommended">
                                            <option value="{{ PACKAGE_STATUS_DISABLED }}">{{ __('NO') }}</option>
                                            <option value="{{ PACKAGE_STATUS_ACTIVE }}">{{ __('YES') }}</option>
                                        </select>
                                        @if ($errors->has('recommended'))
                                        <span class="text-danger"><i class="fas fa-exclamation-triangle"></i> {{
                                            $errors->first('recommended') }}</span>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="input__group mb-25">
                                        <label class="label-text-title color-heading font-medium font-16 mb-3">{{
                                            __('Order')
                                            }} <span class="text-danger">*</span></label>
                                        <input type="number" min=1 value="1" name="order" required>
                                        @if ($errors->has('description'))
                                        <span class="text-danger"><i class="fas fa-exclamation-triangle"></i> {{ $errors->first('order') }}</span>
                                        @endif
                                    </div>
                                </div>
                                
                                <div class="col-md-12">
                                    <div class="input__group mb-25">
                                        <label>{{ __('Description') }}</label>
                                        <textarea name="description" rows="3">{{ old('description') }}</textarea>
                                        @if ($errors->has('description'))
                                            <span class="text-danger"><i class="fas fa-exclamation-triangle"></i> {{ $errors->first('description') }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-3">
                                    <label>{{ __('Icon') }} <span class="text-danger">*</span></label>
                                    <div class="upload-img-box mb-25">
                                        <img src="">
                                        <input type="file" name="icon" id="icon" accept="image/*" onchange="previewFile(this)">
                                        <div class="upload-img-box-icon">
                                            <i class="fa fa-camera"></i>
                                            <p class="m-0">{{__('Image')}}</p>
                                        </div>
                                    </div>
                                </div>
                                @if ($errors->has('icon'))
                                    <span class="text-danger"><i class="fas fa-exclamation-triangle"></i> {{ $errors->first('icon') }}</span>
                                @endif
                                <p>{{ __('Accepted Image Files') }}: JPEG, JPG, PNG <br> {{ __('Accepted Size') }}: 80 x 80 (300KB)</p>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-12 text-right">
                                    <button class="btn btn-primary" type="submit">{{ __('Save') }}</button>
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

@push('style')
    <link rel="stylesheet" href="{{asset('admin/css/custom/image-preview.css')}}">
@endpush

@push('script')
    <script src="{{asset('admin/js/custom/image-preview.js')}}"></script>
    <script>
        $(document).on('change', ':input[name=package_type]', function(){
            debugger;
            if($(this).val() == {{ PACKAGE_TYPE_SAAS_INSTRUCTOR }}){
                $(document).find('.organization-block').addClass('d-none');
            }
            else{
                $(document).find('.organization-block').removeClass('d-none');
            }
        });
    </script>
@endpush
