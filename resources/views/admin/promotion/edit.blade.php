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
                                <h2>{{ __('Edit Promotion') }}</h2>
                            </div>
                        </div>
                        <div class="breadcrumb__content__right">
                            <nav aria-label="breadcrumb">
                                <ul class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">{{__('Dashboard')}}</a></li>
                                    <li class="breadcrumb-item"><a href="{{route('promotion.index')}}">{{ __('All Promotion') }}</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">{{ __('Edit Promotion') }}</li>
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
                            <h2>{{ __('Edit Promotion') }}/h2>
                        </div>
                        <form action="{{route('promotion.update', $promotion->uuid)}}" method="post" class="form-horizontal">
                            @csrf
                            {{ method_field('PUT') }}
                            <div class="input__group mb-25">
                                <label>{{__('Name')}} <span class="text-danger">*</span></label>
                                <input type="text" name="name" value="{{ $promotion->name }}" placeholder="{{__('Name')}}" class="form-control" required>
                                @if ($errors->has('name'))
                                    <span class="text-danger"><i class="fas fa-exclamation-triangle"></i> {{ $errors->first('name') }}</span>
                                @endif
                            </div>

                            <div class="input__group mb-25">
                                <label>{{ __('Start Date') }} <span class="text-danger">*</span></label>
                                <input type="datetime-local" name="start_date" value="{{ $promotion->start_date }}" placeholder="{{ __('Start Date') }}" class="form-control" required>
                                @if ($errors->has('start_date'))
                                    <span class="text-danger"><i class="fas fa-exclamation-triangle"></i> {{ $errors->first('start_date') }}</span>
                                @endif
                            </div>

                            <div class="input__group mb-25">
                                <label>{{ __('End Date') }} <span class="text-danger">*</span></label>
                                <input type="datetime-local" name="end_date" value="{{ $promotion->end_date }}" placeholder="{{ __('End Date') }}" class="form-control" required>
                                @if ($errors->has('end_date'))
                                    <span class="text-danger"><i class="fas fa-exclamation-triangle"></i> {{ $errors->first('end_date') }}</span>
                                @endif
                            </div>

                            <div class="input__group mb-25">
                                <label>{{ __('Percentage')}} <span class="text-danger">*</span></label>
                                <input type="number" step="any" min="1" name="percentage" value="{{ $promotion->percentage }}" placeholder="{{ __('Percentage')}}" class="form-control">
                                @if ($errors->has('percentage'))
                                    <span class="text-danger"><i class="fas fa-exclamation-triangle"></i> {{ $errors->first('percentage') }}</span>
                                @endif
                            </div>

                            <div class="input__group mb-25">
                                <label>{{ __('Status')}} <span class="text-danger">*</span></label>
                                <select name="status">
                                    <option value="">--{{ __('Select Option') }}--</option>
                                    <option value="1" {{ @$promotion->status == 1 ? 'selected' : ''}}>{{ __('Active')}}</option>
                                    <option value="0" {{ @$promotion->status != 1 ? 'selected' : ''}}>{{ __('Deactivated') }}</option>
                                </select>
                                @if ($errors->has('status'))
                                    <span class="text-danger"><i class="fas fa-exclamation-triangle"></i> {{ $errors->first('status') }}</span>
                                @endif
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
@endsection
