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
                                <h2>{{__('Affiliation settings')}}</h2>
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
                <div class="col-md-12">
                    <div class="customers__area bg-style mb-30">

                        <!-- Affiliation Settings Content Start -->
                        <div class="add-product__area bg-style">
                            <form action="{{route('affiliate.referral-settings.update')}}" method="post" class="form-horizontal">
                                @csrf
                                <div class="row mb-45">
                                    <div class="add-product__title">
                                        <h2>{{ __(@$title) }}</h2>
                                    </div>

                                    <div class="col-md-6 col-lg-4">
                                        <div class="input__group mb-25">
                                            <label>{{ __('Allow public affiliator') }}</label>
                                            <select name="referral_status" id="allow_affiliator">
                                                <option value="1" @if(get_option('referral_status') == 1) selected @endif>{{ __('Yes') }}</option>
                                                <option value="0" @if(get_option('referral_status') != 1) selected @endif>{{ __('No') }}</option>
                                            </select>
                                        </div>
                                    </div>

{{--                                    <div class="col-md-6 col-lg-4">--}}
{{--                                        <div class="input__group mb-25">--}}
{{--                                            <label>Block Instructor affiliator</label>--}}
{{--                                            <select name="block_instructor_affiliator" id="block_instructor_affiliator">--}}
{{--                                                <option value="yes">Yes</option>--}}
{{--                                                <option value="no">No</option>--}}
{{--                                            </select>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
                                    <div class="col-md-6 col-lg-4">
                                        <div class="input-group mb-3">
                                            <label>{{ __('Affiliate commission percentage') }}</label>
                                            <div class="input-group">
                                                <input  type="number" placeholder="7.5" min="0" step="0.01" title="Percentage" pattern="^\d+(?:\.\d{1,2})?$" name="referral_commission_percentage" value="{{ get_option('referral_commission_percentage') }}" class="form-control" required>
                                                <span class="input-group-text">%</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="add-product__button mt-5">
{{--                                            <a type="submit"  class="btn btn-blue">--}}
{{--                                                Save changes--}}
{{--                                            </a>--}}
                                            <button type="submit" class="btn btn-blue float-right">{{__('Save changes')}}</button>

                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <!-- Affiliation Settings Content End -->

                    </div>
                </div>
            </div>

        </div>
    </div>
    <!-- Page content area end -->
@endsection

@push('style')
    <link rel="stylesheet" href="{{asset('admin/css/jquery.dataTables.min.css')}}">
@endpush

@push('script')
<script src="{{asset('admin/js/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('admin/js/custom/data-table-page.js')}}"></script>
@endpush

