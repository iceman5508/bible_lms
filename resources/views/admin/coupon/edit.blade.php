
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
                                <h2>{{ __('Edit Coupon') }}</h2>
                            </div>
                        </div>
                        <div class="breadcrumb__content__right">
                            <nav aria-label="breadcrumb">
                                <ul class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">{{__('Dashboard')}}</a></li>
                                    <li class="breadcrumb-item"><a href="{{route('coupon.index')}}">{{ __('Coupon List') }}</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">{{ __('Edit Coupon') }}</li>
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
                            <h2>{{ __('Edit Coupon') }}</h2>
                        </div>
                        <form action="{{route('coupon.update', $coupon->uuid)}}" method="post" class="form-horizontal">
                            @csrf

                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <div class="input__group text-black">
                                        <label>{{ __('Coupon Code Name') }} <span class="text-danger">*</span></label>
                                        <input type="text" name="coupon_code_name" value="{{ $coupon->coupon_code_name }}" placeholder="{{ __('Type coupon code name') }}"
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
                                        <input type="date" name="start_date" value="{{ $coupon->start_date }}" class="form-control" required>
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
                                        <input type="date" name="end_date" value="{{ $coupon->end_date }}" class="form-control" required>
                                        @if ($errors->has('end_date'))
                                            <span class="text-danger"><i class="fas fa-exclamation-triangle"></i> {{ $errors->first('end_date') }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" name="coupon_type" value="{{ $coupon->coupon_type }}" readonly>
                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <div class="input__group text-black">
                                        <label>{{ __('Coupon Type') }} <span class="text-danger">*</span></label>
                                        <select name="" id="coupon_type" class="form-control" disabled="true">
                                            <option value="">--{{ __('Select Option') }}--</option>
                                            <option value="1" @if($coupon->coupon_type == 1) selected @endif>{{ __('Global') }}</option>
                                            <option value="2" @if($coupon->coupon_type == 2) selected @endif>{{ __('Instructor') }}</option>
                                            <option value="3" @if($coupon->coupon_type == 3) selected @endif>{{ __('Course') }}</option>
                                        </select>
                                    </div>
                                    @if ($errors->has('coupon_type'))
                                        <span class="text-danger"><i class="fas fa-exclamation-triangle"></i> {{ $errors->first('coupon_type') }}</span>
                                    @endif
                                </div>
                            </div>


                            <div class="row mb-3 d-none" id="course_id_div">
                                <div class="col-md-12">
                                    <div class="input__group text-black">
                                        <label>{{ __('Course') }}</label>
                                        <select name="course_ids[]" multiple id="course_ids" class="multiple-basic-single">
                                            @foreach($courses as $course)
                                                <option value="{{ $course->id }}"  @if(in_array($course->id, $selectedCourseIDs ?? [])) selected @endif>
                                                    {{ $course->title }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-3 d-none" id="instructor_id_div">
                                <div class="col-md-12">
                                    <div class="input__group text-black">
                                        <label for="instructor_ids">{{ __('Instructor') }}</label>
                                        <select name="instructor_ids[]" multiple id="instructor_ids" class="multiple-basic-single form-control">
                                            @foreach($instructors as $instructor)
                                                <option value="{{ $instructor->id }}" @if(in_array($instructor->id, $selectedInstructorIDs ?? [])) selected @endif>
                                                    {{ $instructor->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <div class="input__group text-black">
                                        <label>Discount (Percentage %)(ex: 15, 25, 30, 40 ...)<span class="text-danger">*</span></label>
                                        <input type="number" min="0" step="any" name="percentage" value="{{ $coupon->percentage }}" placeholder="%"
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
                                        <input type="number" min="0" name="minimum_amount" value="{{ $coupon->minimum_amount }}" placeholder="{{ __('Type minimum amount') }}"
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
                                            <option value="1" @if($coupon->status == 1) selected @endif>{{ __('Active') }}</option>
                                            <option value="0" @if($coupon->status != 1) selected @endif>{{ __('Deactivated') }}</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-12 text-right">
                                    <button class="btn btn-primary" type="submit">{{ __('Update') }}</button>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <!-- Page content area end -->

    <input type="hidden" class="couponType" value="{{$coupon->coupon_type}}">
@endsection


@push('script')
    <script src="{{ asset('admin/js/custom/coupon-edit.js') }}"></script>
@endpush
