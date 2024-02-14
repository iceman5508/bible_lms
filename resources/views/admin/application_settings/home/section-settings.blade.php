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
                                <h2>{{ __('Home Settings') }}</h2>
                            </div>
                        </div>
                        <div class="breadcrumb__content__right">
                            <nav aria-label="breadcrumb">
                                <ul class="breadcrumb">
                                   <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">{{__('Dashboard')}}</a></li>
                                    <li class="breadcrumb-item"><a>{{__('Application Setting')}}</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">{{ __(@$title) }}</li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-3 col-md-4">
                    @include('admin.application_settings.home-sidebar')
                </div>
                <div class="col-lg-9 col-md-8">
                    <div class="customers__area bg-style mb-30">
                        <div class="item-title d-flex justify-content-between">
                            <h2>{{ __('Section Settings') }}</h2>
                        </div>
                        <div class="customers__table">
                            <table id="customers-table" class="row-border data-table-filter table-style">
                                <thead>
                                <tr>
                                    <th>{{__('SL')}}</th>
                                    <th>{{__('Section Name')}}</th>
                                    <th>{{ __('Status') }}</th>
                                </tr>
                                </thead>
                                <tbody>
                                    <tr class="removable-item">
                                        <td>1</td>
                                        <td>{{ __('Special Feature Area') }}</td>
                                        <td>
                                            <span id="hidden_attribute_name" style="display: none">special_feature_area</span>
                                            <select name="status" class="status label-inline font-weight-bolder mb-1 badge badge-info">
                                                <option value="1" @if($home->special_feature_area == 1) selected @endif>{{ __('Active') }}</option>
                                                <option value="0" @if($home->special_feature_area != 1) selected @endif>{{ __('Disable') }}</option>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr class="removable-item">
                                        <td>2</td>
                                        <td>{{ __('Courses Area') }}</td>
                                        <td>
                                            <span id="hidden_attribute_name" style="display: none">courses_area</span>
                                            <select name="status" class="status label-inline font-weight-bolder mb-1 badge badge-info">
                                                <option value="1" @if($home->courses_area == 1) selected @endif>{{ __('Active') }}</option>
                                                <option value="0" @if($home->courses_area != 1) selected @endif>{{ __('Disable') }}</option>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr class="removable-item">
                                        <td>3</td>
                                        <td>{{ __('Category Courses Area') }}</td>
                                        <td>
                                            <span id="hidden_attribute_name" style="display: none">category_courses_area</span>
                                            <select name="status" class="status label-inline font-weight-bolder mb-1 badge badge-info">
                                                <option value="1" @if($home->category_courses_area == 1) selected @endif>{{ __('Active') }}</option>
                                                <option value="0" @if($home->category_courses_area != 1) selected @endif>{{ __('Disable') }}</option>
                                            </select>
                                        </td>
                                    </tr>
                                    @if(isAddonInstalled('LMSZAIPRODUCT'))
                                    <tr class="removable-item">
                                        <td>3</td>
                                        <td>{{ __('Product Area') }}</td>
                                        <td>
                                            <span id="hidden_attribute_name" style="display: none">product_area</span>
                                            <select name="status" class="status label-inline font-weight-bolder mb-1 badge badge-info">
                                                <option value="1" @if($home->product_area == 1) selected @endif>{{ __('Active') }}</option>
                                                <option value="0" @if($home->product_area != 1) selected @endif>{{ __('Disable') }}</option>
                                            </select>
                                        </td>
                                    </tr>
                                    @endif
                                    <tr class="removable-item">
                                        <td>4</td>
                                        <td>{{ __('Upcoming Courses Area') }}</td>
                                        <td>
                                            <span id="hidden_attribute_name" style="display: none">upcoming_courses_area</span>
                                            <select name="status" class="status label-inline font-weight-bolder mb-1 badge badge-info">
                                                <option value="1" @if($home->upcoming_courses_area == 1) selected @endif>{{ __('Active') }}</option>
                                                <option value="0" @if($home->upcoming_courses_area != 1) selected @endif>{{ __('Disable') }}</option>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr class="removable-item">
                                        <td>5</td>
                                        <td>{{ __('Bundle Area') }}</td>
                                        <td>
                                            <span id="hidden_attribute_name" style="display: none">bundle_area</span>
                                            <select name="status" class="status label-inline font-weight-bolder mb-1 badge badge-info">
                                                <option value="1" @if($home->bundle_area == 1) selected @endif>{{ __('Active') }}</option>
                                                <option value="0" @if($home->bundle_area != 1) selected @endif>{{ __('Disable') }}</option>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr class="removable-item">
                                        <td>6</td>
                                        <td>{{ __('Top Category Area') }}</td>
                                        <td>
                                            <span id="hidden_attribute_name" style="display: none">top_category_area</span>
                                            <select name="status" class="status label-inline font-weight-bolder mb-1 badge badge-info">
                                                <option value="1" @if($home->top_category_area == 1) selected @endif>{{ __('Active') }}</option>
                                                <option value="0" @if($home->top_category_area != 1) selected @endif>{{ __('Disable') }}</option>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr class="removable-item">
                                        <td>7</td>
                                        <td>{{ __('Consultation Area') }}</td>
                                        <td>
                                            <span id="hidden_attribute_name" style="display: none">consultation_area</span>
                                            <select name="status" class="status label-inline font-weight-bolder mb-1 badge badge-info">
                                                <option value="1" @if($home->consultation_area == 1) selected @endif>{{ __('Active') }}</option>
                                                <option value="0" @if($home->consultation_area != 1) selected @endif>{{ __('Disable') }}</option>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr class="removable-item">
                                        <td>8</td>
                                        <td>{{ __('Instructor Area') }}</td>
                                        <td>
                                            <span id="hidden_attribute_name" style="display: none">instructor_area</span>
                                            <select name="status" class="status label-inline font-weight-bolder mb-1 badge badge-info">
                                                <option value="1" @if($home->instructor_area == 1) selected @endif>{{ __('Active') }}</option>
                                                <option value="0" @if($home->instructor_area != 1) selected @endif>{{ __('Disable') }}</option>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr class="removable-item">
                                        <td>9</td>
                                        <td>{{ __('Video Area') }}</td>
                                        <td>
                                            <span id="hidden_attribute_name" style="display: none">video_area</span>
                                            <select name="status" class="status label-inline font-weight-bolder mb-1 badge badge-info">
                                                <option value="1" @if($home->video_area == 1) selected @endif>{{ __('Active') }}</option>
                                                <option value="0" @if($home->video_area != 1) selected @endif>{{ __('Disable') }}</option>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr class="removable-item">
                                        <td>10</td>
                                        <td>{{ __('Customer Says Area') }}</td>
                                        <td>
                                            <span id="hidden_attribute_name" style="display: none">customer_says_area</span>
                                            <select name="status" class="status label-inline font-weight-bolder mb-1 badge badge-info">
                                                <option value="1" @if($home->customer_says_area == 1) selected @endif>{{ __('Active') }}</option>
                                                <option value="0" @if($home->customer_says_area != 1) selected @endif>{{ __('Disable') }}</option>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr class="removable-item">
                                        <td>11</td>
                                        <td>{{ __('Achievement Area') }}</td>
                                        <td>
                                            <span id="hidden_attribute_name" style="display: none">achievement_area</span>
                                            <select name="status" class="status label-inline font-weight-bolder mb-1 badge badge-info">
                                                <option value="1" @if($home->achievement_area == 1) selected @endif>{{ __('Active') }}</option>
                                                <option value="0" @if($home->achievement_area != 1) selected @endif>{{ __('Disable') }}</option>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr class="removable-item">
                                        <td>12</td>
                                        <td>{{ __('FAQ Area') }}</td>
                                        <td>
                                            <span id="hidden_attribute_name" style="display: none">faq_area</span>
                                            <select name="status" class="status label-inline font-weight-bolder mb-1 badge badge-info">
                                                <option value="1" @if($home->faq_area == 1) selected @endif>{{ __('Active') }}</option>
                                                <option value="0" @if($home->faq_area != 1) selected @endif>{{ __('Disable') }}</option>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr class="removable-item">
                                        <td>13</td>
                                        <td>{{ __('Instructor Support Area') }}</td>
                                        <td>
                                            <span id="hidden_attribute_name" style="display: none">instructor_support_area</span>
                                            <select name="status" class="status label-inline font-weight-bolder mb-1 badge badge-info">
                                                <option value="1" @if($home->instructor_support_area == 1) selected @endif>{{ __('Active') }}</option>
                                                <option value="0" @if($home->instructor_support_area != 1) selected @endif>{{ __('Disable') }}</option>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr class="removable-item">
                                        <td>14</td>
                                        <td>{{ __('Subscription list area') }}</td>
                                        <td>
                                            <span id="hidden_attribute_name" style="display: none">subscription_show</span>
                                            <select name="status" class="status label-inline font-weight-bolder mb-1 badge badge-info">
                                                <option value="1" @if($home->subscription_show == 1) selected @endif>{{ __('Active') }}</option>
                                                <option value="0" @if($home->subscription_show != 1) selected @endif>{{ __('Disable') }}</option>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr class="removable-item">
                                        <td>15</td>
                                        <td>{{ __('SaaS List area') }}</td>
                                        <td>
                                            <span id="hidden_attribute_name" style="display: none">saas_show</span>
                                            <select name="status" class="status label-inline font-weight-bolder mb-1 badge badge-info">
                                                <option value="1" @if($home->saas_show == 1) selected @endif>{{ __('Active') }}</option>
                                                <option value="0" @if($home->saas_show != 1) selected @endif>{{ __('Disable') }}</option>
                                            </select>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
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

    <script>
        'use strict'
        $(".status").change(function () {
            var attribute_name = $(this).closest('tr').find('#hidden_attribute_name').html();
            var status = $(this).closest('tr').find('.status option:selected').val();
            console.log(attribute_name, status)
            Swal.fire({
                title: "{{ __('Are you sure to change status?') }}",
                text: "{{ __('You won`t be able to revert this!') }}",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "{{__('Yes, Change it!')}}",
                cancelButtonText: "{{__('No, cancel!')}}",
                reverseButtons: true
            }).then(function (result) {
                if (result.value) {
                    $.ajax({
                        type: "POST",
                        url: "{{route('settings.sectionSettingsStatusChange')}}",
                        data: {"attribute_name": attribute_name, "status": status,  "_token": "{{ csrf_token() }}",},
                        datatype: "json",
                        success: function (data) {
                            toastr.success('', "{{ __('Section status has been changed') }}");
                        },
                        error: function () {
                            alert("Error!");
                        },
                    });
                } else if (result.dismiss === "cancel") {
                }
            });
        });
    </script>
@endpush
