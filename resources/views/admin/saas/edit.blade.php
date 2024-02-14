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
                            <h2>{{ __('Update Saas Package') }}</h2>
                        </div>
                    </div>
                    <div class="breadcrumb__content__right">
                        <nav aria-label="breadcrumb">
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a
                                        href="{{route('admin.dashboard')}}">{{__('Dashboard')}}</a></li>
                                <li class="breadcrumb-item active" aria-current="page">{{ __('Update Saas Package') }}
                                </li>
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
                        <h2>{{ __('Update Saas Package') }}</h2>
                    </div>
                    <form action="{{route('admin.saas.update', $saas->uuid)}}" method="post" class="form-horizontal"
                        enctype="multipart/form-data">
                        @method('PUT')
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="input__group mb-25">
                                    <label class="label-text-title color-heading font-medium font-16 mb-3">{{ __('SaaS
                                        Type')
                                        }} <span class="text-danger">*</span></label>
                                    <select class="form-control" name="package_type">
                                        <option value="{{ PACKAGE_TYPE_SAAS_INSTRUCTOR }}" {{ old('package_type',
                                            $saas->package_type) == PACKAGE_TYPE_SAAS_INSTRUCTOR ? 'selected' : '' }}>{{
                                            __('Instructor') }}</option>
                                        <option value="{{ PACKAGE_TYPE_SAAS_ORGANIZATION }}" {{ old('package_type',
                                            $saas->package_type) == PACKAGE_TYPE_SAAS_ORGANIZATION ? 'selected' : ''
                                            }}>{{ __('Organization') }}</option>
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
                                    <input type="text" name="title" value="{{old('title', $saas->title)}}"
                                        placeholder="{{ __('Title') }}" class="form-control" required>
                                    @if ($errors->has('title'))
                                    <span class="text-danger"><i class="fas fa-exclamation-triangle"></i> {{
                                        $errors->first('title') }}</span>
                                    @endif
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="input__group mb-25">
                                    <label>{{__('Price Monthly')}} <span class="text-danger">*</span></label>
                                    <input type="number" min=0 name="monthly_price"
                                        value="{{old('monthly_price', $saas->monthly_price)}}"
                                        placeholder="{{ __('Price Monthly') }}" class="form-control" required>
                                    @if ($errors->has('monthly_price'))
                                    <span class="text-danger"><i class="fas fa-exclamation-triangle"></i> {{
                                        $errors->first('monthly_price') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="input__group mb-25">
                                    <label>{{__('Discounted Price Monthly')}} <span class="text-danger">*</span></label>
                                    <input type="number" min=0 name="discounted_monthly_price"
                                        value="{{old('discounted_monthly_price', $saas->discounted_monthly_price)}}"
                                        placeholder="{{ __('Discounted Price Monthly') }}" class="form-control"
                                        required>
                                    @if ($errors->has('discounted_monthly_price'))
                                    <span class="text-danger"><i class="fas fa-exclamation-triangle"></i> {{
                                        $errors->first('discounted_monthly_price') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="input__group mb-25">
                                    <label>{{__('Price Yearly')}} <span class="text-danger">*</span></label>
                                    <input type="number" min=0 name="yearly_price"
                                        value="{{old('yearly_price', $saas->yearly_price)}}"
                                        placeholder="{{ __('Price Yearly') }}" class="form-control" required>
                                    @if ($errors->has('yearly_price'))
                                    <span class="text-danger"><i class="fas fa-exclamation-triangle"></i> {{
                                        $errors->first('yearly_price') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="input__group mb-25">
                                    <label>{{__('Discounted Price Yearly')}} <span class="text-danger">*</span></label>
                                    <input type="number" min=0 name="discounted_yearly_price"
                                        value="{{old('discounted_yearly_price', $saas->discounted_yearly_price)}}"
                                        placeholder="{{ __('Discounted Price Yearly') }}" class="form-control" required>
                                    @if ($errors->has('discounted_yearly_price'))
                                    <span class="text-danger"><i class="fas fa-exclamation-triangle"></i> {{
                                        $errors->first('discounted_yearly_price') }}</span>
                                    @endif
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="input__group mb-25">
                                    <label>{{__('Admin Commission')}}% <span class="text-danger">*</span></label>
                                    <input type="number" min=0 max=100 name="admin_commission"
                                        value="{{old('admin_commission', $saas->admin_commission)}}"
                                        placeholder="{{ __('Admin Commission') }}" class="form-control" required>
                                    @if ($errors->has('admin_commission'))
                                    <span class="text-danger"><i class="fas fa-exclamation-triangle"></i> {{
                                        $errors->first('admin_commission') }}</span>
                                    @endif
                                </div>
                            </div>

                            <div class="col-md-6 organization-block">
                                <div class="input__group mb-25">
                                    <label>{{__('Student Limit')}} <span class="text-danger">*</span></label>
                                    <input type="number" min=0 name="student" value="{{old('student', $saas->student)}}" placeholder="{{ __('student Limit') }}" class="form-control" required>
                                    @if ($errors->has('student'))
                                        <span class="text-danger"><i class="fas fa-exclamation-triangle"></i> {{ $errors->first('student') }}</span>
                                    @endif
                                </div>
                            </div>

                            <div class="col-md-6 organization-block">
                                <div class="input__group mb-25">
                                    <label>{{__('Instructor Limit')}} <span class="text-danger">*</span></label>
                                    <input type="number" min=0 name="instructor"
                                        value="{{old('instructor', $saas->instructor)}}"
                                        placeholder="{{ __('Instructor Limit') }}" class="form-control" required>
                                    @if ($errors->has('instructor'))
                                    <span class="text-danger"><i class="fas fa-exclamation-triangle"></i> {{
                                        $errors->first('instructor') }}</span>
                                    @endif
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="input__group mb-25">
                                    <label>{{__('Course Limit')}} <span class="text-danger">*</span></label>
                                    <input type="number" min=0 name="course" value="{{old('course', $saas->course)}}"
                                        placeholder="{{ __('Course Limit') }}" class="form-control" required>
                                    @if ($errors->has('course'))
                                    <span class="text-danger"><i class="fas fa-exclamation-triangle"></i> {{
                                        $errors->first('course') }}</span>
                                    @endif
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="input__group mb-25">
                                    <label>{{__('Bundle Course Limit')}} <span class="text-danger">*</span></label>
                                    <input type="number" min=0 name="bundle_course"
                                        value="{{old('bundle_course', $saas->bundle_course)}}"
                                        placeholder="{{ __('Bundle Course Limit') }}" class="form-control" required>
                                    @if ($errors->has('bundle_course'))
                                    <span class="text-danger"><i class="fas fa-exclamation-triangle"></i> {{
                                        $errors->first('bundle_course') }}</span>
                                    @endif
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="input__group mb-25">
                                    <label>{{__('Subscription Course Limit')}} <span
                                            class="text-danger">*</span></label>
                                    <input type="number" min=0 name="subscription_course"
                                        value="{{old('subscription_course', $saas->subscription_course)}}"
                                        placeholder="{{ __('Subscription Course Limit') }}" class="form-control"
                                        required>
                                    @if ($errors->has('subscription_course'))
                                    <span class="text-danger"><i class="fas fa-exclamation-triangle"></i> {{
                                        $errors->first('subscription_course') }}</span>
                                    @endif
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="input__group mb-25">
                                    <label>{{__('Consulaltency Limit')}} <span class="text-danger">*</span></label>
                                    <input type="number" min=0 name="consultancy"
                                        value="{{old('consultancy', $saas->consultancy)}}"
                                        placeholder="{{ __('Consulaltency Limit') }}" class="form-control" required>
                                    @if ($errors->has('consultancy'))
                                    <span class="text-danger"><i class="fas fa-exclamation-triangle"></i> {{
                                        $errors->first('consultancy') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="input__group mb-25">
                                    <label class="label-text-title color-heading font-medium font-16 mb-3">{{ __('Show
                                        in Home')
                                        }} <span class="text-danger">*</span></label>
                                    <select class="form-control" name="in_home">
                                        <option value="{{ PACKAGE_STATUS_ACTIVE }}" {{ $saas->in_home ==
                                            PACKAGE_STATUS_ACTIVE ? 'selected' : '' }}>{{ __('YES') }}</option>
                                        <option value="{{ PACKAGE_STATUS_DISABLED }}" {{ $saas->in_home ==
                                            PACKAGE_STATUS_DISABLED ? 'selected' : '' }}>{{ __('NO') }}</option>
                                    </select>
                                    @if ($errors->has('in_home'))
                                    <span class="text-danger"><i class="fas fa-exclamation-triangle"></i> {{
                                        $errors->first('in_home') }}</span>
                                    @endif
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="input__group mb-25">
                                    <label class="label-text-title color-heading font-medium font-16 mb-3">{{
                                        __('Recomended')
                                        }} <span class="text-danger">*</span></label>
                                    <select class="form-control" name="recommended">
                                        <option value="{{ PACKAGE_STATUS_DISABLED }}" {{ $saas->recommended ==
                                            PACKAGE_STATUS_DISABLED ? 'selected' : '' }}>{{ __('NO') }}</option>
                                        <option value="{{ PACKAGE_STATUS_ACTIVE }}" {{ $saas->recommended ==
                                            PACKAGE_STATUS_ACTIVE ? 'selected' : '' }}>{{ __('YES') }}</option>
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
                                    <input type="number" min=1 value="{{ $saas->order }}" name="order" required>
                                    @if ($errors->has('description'))
                                    <span class="text-danger"><i class="fas fa-exclamation-triangle"></i> {{
                                        $errors->first('order') }}</span>
                                    @endif
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="input__group mb-25">
                                    <label>{{ __('Description') }}</label>
                                    <textarea name="description" rows="3">{{ old('description', $saas->description)
                                        }}</textarea>
                                    @if ($errors->has('description'))
                                    <span class="text-danger"><i class="fas fa-exclamation-triangle"></i> {{
                                        $errors->first('description') }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-3">
                                <label>{{ __('Icon') }} <span class="text-danger">*</span></label>
                                <div class="upload-img-box mb-25">
                                    @if($saas->icon)
                                    <img src="{{asset($saas->icon)}}" alt="img">
                                    @else
                                    <img src="" alt="No img">
                                    @endif
                                    <input type="file" name="icon" id="image" accept="image/*"
                                        onchange="previewFile(this)">
                                    <div class="upload-img-box-icon">
                                        <i class="fa fa-camera"></i>
                                        <p class="m-0">{{__('Image')}}</p>
                                    </div>
                                </div>
                            </div>

                            @if ($errors->has('icon'))
                            <span class="text-danger"><i class="fas fa-exclamation-triangle"></i> {{
                                $errors->first('icon') }}</span>
                            @endif
                            <p>{{ __('Accepted Image Files') }}: JPEG, JPG, PNG <br> {{ __('Accepted Size') }}: 80 x
                                80 (300KB)</p>
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
        if($(this).val() == {{ PACKAGE_TYPE_SAAS_INSTRUCTOR }}){
            $(document).find('.organization-block').addClass('d-none');
        }
        else{
            $(document).find('.organization-block').removeClass('d-none');
        }
    });

    $(document).find(':input[name=package_type]').trigger('change');
</script>
@endpush