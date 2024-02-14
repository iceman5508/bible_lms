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
                                <h2>{{ __('Add Coupon') }}</h2>
                            </div>
                        </div>
                        <div class="breadcrumb__content__right">
                            <nav aria-label="breadcrumb">
                                <ul class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">{{__('Dashboard')}}</a></li>
                                    <li class="breadcrumb-item"><a href="{{route('coupon.index')}}">{{ __('Coupon List') }}</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">{{ __('Add Coupon') }}</li>
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
                            <h2>{{ __('Add Coupon') }}</h2>
                        </div>
                        <form action="{{route('coupon.store')}}" method="post" class="form-horizontal">
                            @csrf

                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <div class="input__group text-black">
                                        <label>{{ __('Coupon Code Name') }} <span class="text-danger">*</span></label>
                                        <input type="text" name="coupon_code_name" value="{{old('coupon_code_name')}}" placeholder="{{ __('Type coupon code name') }}"
                                               class="form-control" required>
                                        @if ($errors->has('coupon_code_name'))
                                            <span class="text-danger"><i class="fas fa-exclamation-triangle"></i> {{ $errors->first('coupon_code_name') }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <div class="input__group text-black">
                                        <label>{{ __('Start Date') }} <span class="text-danger">*</span></label>
                                        <input type="date" name="start_date" value="{{old('start_date')}}" class="form-control" required>
                                        @if ($errors->has('start_date'))
                                            <span class="text-danger"><i class="fas fa-exclamation-triangle"></i> {{ $errors->first('start_date') }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <div class="input__group text-black">
                                        <label>{{ __('End Date') }} <span class="text-danger">*</span></label>
                                        <input type="date" name="end_date" value="{{old('end_date')}}" class="form-control" required>
                                        @if ($errors->has('end_date'))
                                            <span class="text-danger"><i class="fas fa-exclamation-triangle"></i> {{ $errors->first('end_date') }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <div class="input__group text-black">
                                        <label>{{ __('Coupon Type') }} <span class="text-danger">*</span></label>
                                        <select name="coupon_type" id="coupon_type" class="form-control" required>
                                            <option value="">--{{ __('Select Option') }}--</option>
                                            <option value="1">{{ __('Global') }}</option>
                                            <option value="2">{{ __('Instructor') }}</option>
                                            <option value="3">{{ __('Course') }}</option>
                                        </select>
                                    </div>
                                </div>
                            </div>


                            <div class="row mb-3 d-none" id="course_id_div">
                                <div class="col-md-12">
                                    <div class="input__group">
                                        <label>{{ __('Course') }}</label>
                                        <select name="course_ids[]" multiple id="course_ids" class="multiple-basic-single">
                                            @foreach($courses as $course)
                                                <option value="{{ $course->id }}">{{ $course->title }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-3 d-none" id="instructor_id_div">
                                <div class="col-md-12">
                                    <div class="input__group">
                                        <label for="instructor_ids">{{ __('Instructor') }}</label>
                                        <select name="instructor_ids[]" multiple id="instructor_ids" class="multiple-basic-single">
                                            @foreach($instructors as $instructor)
                                                <option value="{{ $instructor->id }}">{{ $instructor->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <div class="input__group text-black">
                                        <label>Discount (Percentage %)(ex: 15, 25, 30, 40 ...)<span class="text-danger">*</span></label>
                                        <input type="number" min="0" step="any" name="percentage" value="{{old('percentage')}}" placeholder="%"
                                               class="form-control" required>
                                        @if ($errors->has('percentage'))
                                            <span class="text-danger"><i class="fas fa-exclamation-triangle"></i> {{ $errors->first('percentage') }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <div class="input__group text-black">
                                        <label>{{ __('Minimum Amount to Apply Coupon') }}<span class="text-danger">*</span></label>
                                        <input type="number" min="0" name="minimum_amount" value="{{old('minimum_amount')}}" placeholder="{{ __('Type minimum amount') }}"
                                               class="form-control" required>
                                        @if ($errors->has('minimum_amount'))
                                            <span class="text-danger"><i class="fas fa-exclamation-triangle"></i> {{ $errors->first('minimum_amount') }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <div class="input__group text-black">
                                        <label>{{ __('Status') }} <span class="text-danger">*</span></label>
                                        <select name="status" id="status" class="form-control">
                                            <option value="">--{{ __('Select Option') }}--</option>
                                            <option value="1">{{ __('Active') }}</option>
                                            <option value="0">{{ __('Deactivated') }}</option>
                                        </select>
                                    </div>
                                </div>
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

@endpush

@push('script')
    <script src="{{ asset('admin/js/custom/coupon-create.js') }}"></script>
@endpush
