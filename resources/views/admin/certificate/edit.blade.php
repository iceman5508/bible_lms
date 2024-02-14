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
                            <h2>{{ __(@$title) }}</h2>
                        </div>
                    </div>
                    <div class="breadcrumb__content__right">
                        <nav aria-label="breadcrumb">
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a
                                        href="{{route('admin.dashboard')}}">{{__('Dashboard')}}</a></li>
                                <li class="breadcrumb-item"><a href="{{route('certificate.index')}}">{{ __('Certificate
                                        List') }}</a></li>
                                <li class="breadcrumb-item active" aria-current="page">{{ __(@$title) }}</li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="customers__area bg-style mb-30 admin-certificate-page">
                    <div class="item-title d-flex justify-content-between">
                        <h2>{{ __(@$title) }}</h2>
                        <div><a href="{{route('certificate.index')}}"
                                class="theme-btn theme-button1 green-theme-btn default-hover-btn">{{ __('Certificate
                                List') }}</a></div>
                    </div>

                    <div class="admin-create-certifiate">
                        <div class="row">
                            <div class="col-12 col-md-7 col-lg-7 col-xl-8">
                                <div class="sticky-top" id="certificate-preview-div">
                                </div>
                                <div style="overflow: hidden; height: 0;">
                                    <div id="certificate-preview-div-hidden"
                                        style="width:1030px; height:734px; overflow:hidden">
                                        @include('admin.certificate.view')
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 col-md-5 col-lg-5 col-xl-4">
                                <form method="post" id="certificate-form"
                                    action="{{route('certificate.update', [$certificate->uuid])}}"
                                    enctype="multipart/form-data">
                                    @csrf
                                    <div class="create-certificate-sidebar admin-certificate-sidebar">
                                        <div class="accordion" id="accordionPanelsStayOpenExample">
                                            <div class="row">
                                                <div class="col-md-12 mb-15">
                                                    <div class="label-text-title color-heading font-16 mb-1">{{
                                                        __('Background Image') }}</div>
                                                    <div class="create-certificate-browse-file form-control mb-2">
                                                        <div>
                                                            <input type="file" name="background_image" accept="image/*"
                                                                class="form-control" title="Browse Image File">
                                                        </div>
                                                    </div>
                                                    <div class="recomended-size-for-img font-12">{{ __('Accepted Files')
                                                        }}: JPG, PNG</div>
                                                    <div class="recomended-size-for-img font-12">{{ __('Accepted Size')
                                                        }}: 1030 x 734</div>
                                                    @if ($errors->has('background_image'))
                                                    <span class="text-danger"><i
                                                            class="fas fa-exclamation-triangle"></i> {{
                                                        $errors->first('background_image') }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="accordion-item course-sidebar-accordion-item">
                                                <h2 class="accordion-header course-sidebar-title mb-2"
                                                    id="panelsStayOpen-headingOne">
                                                    <button class="accordion-button" type="button"
                                                        data-bs-toggle="collapse"
                                                        data-bs-target="#panelsStayOpen-collapseOne"
                                                        aria-expanded="true" aria-controls="panelsStayOpen-collapseOne">
                                                        {{ __('Certificate Number') }}
                                                    </button>
                                                </h2>
                                                <div id="panelsStayOpen-collapseOne"
                                                    class="accordion-collapse collapse show"
                                                    aria-labelledby="panelsStayOpen-headingOne">
                                                    <div class="accordion-body">
                                                        <div class="certificate-inner-box">
                                                            <div class="row">
                                                                <div class="col-md-12 mb-15">
                                                                    <div
                                                                        class="label-text-title color-heading font-16 mb-1">
                                                                        {{ __('Certificate Number Show') }}</div>
                                                                    <div
                                                                        class="admin-certificate-radio d-flex align-items-center">
                                                                        <div
                                                                            class="form-check mb-0 d-flex align-items-center">
                                                                            <input class="form-check-input" type="radio"
                                                                                name="show_number" id="show_number_yes"
                                                                                value="yes" {{$certificate->show_number
                                                                            == 'yes' ? 'checked' : '' }}>
                                                                            <label
                                                                                class="form-check-label mb-0 color-heading"
                                                                                for="show_number_yes">{{ __('Yes')
                                                                                }}</label>
                                                                        </div>
                                                                        <div
                                                                            class="form-check mb-0 d-flex align-items-center">
                                                                            <input class="form-check-input" type="radio"
                                                                                name="show_number" id="show_number_no"
                                                                                value="no" {{$certificate->show_number
                                                                            == 'no' ? 'checked' : '' }}>
                                                                            <label
                                                                                class="form-check-label mb-0 color-heading"
                                                                                for="show_number_no">{{ __('No')
                                                                                }}</label>
                                                                        </div>
                                                                    </div>
                                                                    @if ($errors->has('show_number'))
                                                                    <span class="text-danger"><i
                                                                            class="fas fa-exclamation-triangle"></i> {{
                                                                        $errors->first('show_number') }}</span>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-12 mb-15">
                                                                    <div
                                                                        class="label-text-title color-heading font-16 mb-1">
                                                                        {{ __('Position X') }}</div>
                                                                    <input type="number" min="0"
                                                                        name="number_x_position"
                                                                        value="{{$certificate->number_x_position}}"
                                                                        class="form-control" placeholder="0">
                                                                    @if ($errors->has('number_x_position'))
                                                                    <span class="text-danger"><i
                                                                            class="fas fa-exclamation-triangle"></i> {{
                                                                        $errors->first('number_x_position') }}</span>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-12 mb-15">
                                                                    <div
                                                                        class="label-text-title color-heading font-16 mb-1">
                                                                        {{ __('Position Y') }}</div>
                                                                    <input type="number" min="0"
                                                                        name="number_y_position"
                                                                        value="{{$certificate->number_y_position}}"
                                                                        class="form-control" placeholder="0">
                                                                    @if ($errors->has('number_y_position'))
                                                                    <span class="text-danger"><i
                                                                            class="fas fa-exclamation-triangle"></i> {{
                                                                        $errors->first('number_y_position') }}</span>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-6 mb-15">
                                                                    <div
                                                                        class="label-text-title color-heading font-16 mb-1">
                                                                        {{ __('Font Size') }}</div>
                                                                    <input type="number" min="1" name="number_font_size"
                                                                        value="{{$certificate->number_font_size}}"
                                                                        class="form-control" placeholder="30">
                                                                    @if ($errors->has('number_font_size'))
                                                                    <span class="text-danger"><i
                                                                            class="fas fa-exclamation-triangle"></i> {{
                                                                        $errors->first('number_font_size') }}</span>
                                                                    @endif
                                                                </div>
                                                                <div class="col-md-6 mb-15">
                                                                    <div
                                                                        class="label-text-title color-heading font-16 mb-1">
                                                                        {{ __('Font Color') }}</div>
                                                                    <span class="color-picker">
                                                                        <label for="colorPicker3" class="mb-0">
                                                                            <input type="color" name="number_font_color"
                                                                                value="{{$certificate->number_font_color}}"
                                                                                id="colorPicker3">
                                                                        </label>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="accordion-item course-sidebar-accordion-item">
                                                <h2 class="accordion-header course-sidebar-title mb-2"
                                                    id="panelsStayOpen-headingOne3">
                                                    <button class="accordion-button" type="button"
                                                        data-bs-toggle="collapse"
                                                        data-bs-target="#panelsStayOpen-collapseOne3"
                                                        aria-expanded="true"
                                                        aria-controls="panelsStayOpen-collapseOne3">
                                                        {{ __('Certificate Title') }}
                                                    </button>
                                                </h2>
                                                <div id="panelsStayOpen-collapseOne3"
                                                    class="accordion-collapse collapse show"
                                                    aria-labelledby="panelsStayOpen-headingOne3">
                                                    <div class="accordion-body">
                                                        <div class="certificate-inner-box">
                                                            <div class="row">
                                                                <div class="col-md-12 mb-15">
                                                                    <div
                                                                        class="label-text-title color-heading font-16 mb-1">
                                                                        {{ __('Title') }}</div>
                                                                    <input type="text" name="title"
                                                                        value="{{$certificate->title}}"
                                                                        class="form-control"
                                                                        placeholder="Certificate Title">
                                                                    @if ($errors->has('title'))
                                                                    <span class="text-danger"><i
                                                                            class="fas fa-exclamation-triangle"></i> {{
                                                                        $errors->first('title') }}</span>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-12 mb-15">
                                                                    <div
                                                                        class="label-text-title color-heading font-16 mb-1">
                                                                        {{ __('Position X') }}</div>
                                                                    <input type="number" min="0" name="title_x_position"
                                                                        value="{{$certificate->title_x_position}}"
                                                                        class="form-control" placeholder="0">
                                                                    @if ($errors->has('title_x_position'))
                                                                    <span class="text-danger"><i
                                                                            class="fas fa-exclamation-triangle"></i> {{
                                                                        $errors->first('title_x_position') }}</span>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-12 mb-15">
                                                                    <div
                                                                        class="label-text-title color-heading font-16 mb-1">
                                                                        {{ __('Position Y') }}</div>
                                                                    <input type="number" min="0" name="title_y_position"
                                                                        value="{{$certificate->title_y_position}}"
                                                                        class="form-control" placeholder="0">
                                                                    @if ($errors->has('title_y_position'))
                                                                    <span class="text-danger"><i
                                                                            class="fas fa-exclamation-triangle"></i> {{
                                                                        $errors->first('title_y_position') }}</span>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-6 mb-15">
                                                                    <div
                                                                        class="label-text-title color-heading font-16 mb-1">
                                                                        {{ __('Font Size') }}</div>
                                                                    <input type="number" min="1" name="title_font_size"
                                                                        value="{{$certificate->title_font_size}}"
                                                                        class="form-control" placeholder="30">
                                                                    @if ($errors->has('title_font_size'))
                                                                    <span class="text-danger"><i
                                                                            class="fas fa-exclamation-triangle"></i> {{
                                                                        $errors->first('title_font_size') }}</span>
                                                                    @endif
                                                                </div>
                                                                <div class="col-md-6 mb-15">
                                                                    <div
                                                                        class="label-text-title color-heading font-16 mb-1">
                                                                        {{ __('Font Color') }}</div>
                                                                    <span class="color-picker">
                                                                        <label for="colorPicker4" class="mb-0">
                                                                            <input type="color" name="title_font_color"
                                                                                value="{{$certificate->title_font_color}}"
                                                                                id="colorPicker4">
                                                                        </label>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="accordion-item course-sidebar-accordion-item">
                                                <h2 class="accordion-header course-sidebar-title mb-2"
                                                    id="panelsStayOpen-headingOne1">
                                                    <button class="accordion-button" type="button"
                                                        data-bs-toggle="collapse"
                                                        data-bs-target="#panelsStayOpen-collapseOne1"
                                                        aria-expanded="true"
                                                        aria-controls="panelsStayOpen-collapseOne1">
                                                        {{ __('Certificate Date') }}
                                                    </button>
                                                </h2>

                                                <div id="panelsStayOpen-collapseOne1"
                                                    class="accordion-collapse collapse show"
                                                    aria-labelledby="panelsStayOpen-headingOne1">
                                                    <div class="accordion-body">
                                                        <div class="certificate-inner-box">
                                                            <div class="row">
                                                                <div class="col-md-12 mb-15">
                                                                    <div
                                                                        class="label-text-title color-heading font-16 mb-1">
                                                                        {{ __('Certificate Date Show') }}</div>
                                                                    <div
                                                                        class="admin-certificate-radio d-flex align-items-center">
                                                                        <div
                                                                            class="form-check mb-0 d-flex align-items-center">
                                                                            <input class="form-check-input" type="radio"
                                                                                name="show_date" id="show_date_yes"
                                                                                value="yes" {{$certificate->show_date ==
                                                                            'yes' ? 'checked' : '' }} >
                                                                            <label
                                                                                class="form-check-label mb-0 color-heading"
                                                                                for="show_date_yes">Yes</label>
                                                                        </div>
                                                                        <div
                                                                            class="form-check mb-0 d-flex align-items-center">
                                                                            <input class="form-check-input" type="radio"
                                                                                name="show_date" id="show_date_no"
                                                                                value="no" {{$certificate->show_date ==
                                                                            'no' ? 'checked' : '' }} >
                                                                            <label
                                                                                class="form-check-label mb-0 color-heading"
                                                                                for="exampleRadios2">No</label>
                                                                        </div>
                                                                    </div>
                                                                    @if ($errors->has('show_date'))
                                                                    <span class="text-danger"><i
                                                                            class="fas fa-exclamation-triangle"></i> {{
                                                                        $errors->first('show_date') }}</span>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-12 mb-15">
                                                                    <div
                                                                        class="label-text-title color-heading font-16 mb-1">
                                                                        {{ __('Position X') }}</div>
                                                                    <input type="number" min="0" name="date_x_position"
                                                                        value="{{$certificate->date_x_position}}"
                                                                        class="form-control" placeholder="0">
                                                                    @if ($errors->has('date_x_position'))
                                                                    <span class="text-danger"><i
                                                                            class="fas fa-exclamation-triangle"></i> {{
                                                                        $errors->first('date_x_position') }}</span>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-12 mb-15">
                                                                    <div
                                                                        class="label-text-title color-heading font-16 mb-1">
                                                                        {{ __('Position Y') }}</div>
                                                                    <input type="number" min="0" name="date_y_position"
                                                                        value="{{$certificate->date_y_position}}"
                                                                        class="form-control" placeholder="0">
                                                                    @if ($errors->has('date_y_position'))
                                                                    <span class="text-danger"><i
                                                                            class="fas fa-exclamation-triangle"></i> {{
                                                                        $errors->first('date_y_position') }}</span>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-6 mb-15">
                                                                    <div
                                                                        class="label-text-title color-heading font-16 mb-1">
                                                                        {{ __('Font Size') }}</div>
                                                                    <input type="number" min="1" name="date_font_size"
                                                                        value="{{$certificate->date_font_size}}"
                                                                        class="form-control" placeholder="30">
                                                                    @if ($errors->has('date_font_size'))
                                                                    <span class="text-danger"><i
                                                                            class="fas fa-exclamation-triangle"></i> {{
                                                                        $errors->first('date_font_size') }}</span>
                                                                    @endif
                                                                </div>
                                                                <div class="col-md-6 mb-15">
                                                                    <div
                                                                        class="label-text-title color-heading font-16 mb-1">
                                                                        {{ __('Font Color') }}</div>
                                                                    <span class="color-picker">
                                                                        <label for="colorPicker5" class="mb-0">
                                                                            <input type="color" name="date_font_color"
                                                                                value="{{$certificate->date_font_color}}"
                                                                                id="colorPicker5">
                                                                        </label>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="accordion-item course-sidebar-accordion-item">
                                                <h2 class="accordion-header course-sidebar-title mb-2"
                                                    id="panelsStayOpen-headingOne2">
                                                    <button class="accordion-button" type="button"
                                                        data-bs-toggle="collapse"
                                                        data-bs-target="#panelsStayOpen-collapseOne2"
                                                        aria-expanded="true"
                                                        aria-controls="panelsStayOpen-collapseOne2">
                                                        {{ __('Student Name') }}
                                                    </button>
                                                </h2>

                                                <div id="panelsStayOpen-collapseOne2"
                                                    class="accordion-collapse collapse show"
                                                    aria-labelledby="panelsStayOpen-headingOne2">
                                                    <div class="accordion-body">
                                                        <div class="certificate-inner-box">
                                                            <div class="row">
                                                                <div class="col-md-12 mb-15">
                                                                    <div
                                                                        class="label-text-title color-heading font-16 mb-1">
                                                                        {{ __('Student Name') }}</div>
                                                                    <div
                                                                        class="admin-certificate-radio d-flex align-items-center">
                                                                        <div
                                                                            class="form-check mb-0 d-flex align-items-center">
                                                                            <input class="form-check-input" type="radio"
                                                                                name="show_student_name"
                                                                                id="show_student_name_yes" value="yes"
                                                                                {{$certificate->show_student_name ==
                                                                            'yes' ? 'checked' : '' }}>
                                                                            <label
                                                                                class="form-check-label mb-0 color-heading"
                                                                                for="show_student_name_yes">Yes</label>
                                                                        </div>
                                                                        <div
                                                                            class="form-check mb-0 d-flex align-items-center">
                                                                            <input class="form-check-input" type="radio"
                                                                                name="show_student_name"
                                                                                id="show_student_name_no" value="no"
                                                                                {{$certificate->show_student_name ==
                                                                            'no' ? 'checked' : '' }}>
                                                                            <label
                                                                                class="form-check-label mb-0 color-heading"
                                                                                for="show_student_name_no">No</label>
                                                                        </div>
                                                                    </div>
                                                                    @if ($errors->has('show_student_name'))
                                                                    <span class="text-danger"><i
                                                                            class="fas fa-exclamation-triangle"></i> {{
                                                                        $errors->first('show_student_name') }}</span>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-12 mb-15">
                                                                    <div
                                                                        class="label-text-title color-heading font-16 mb-1">
                                                                        {{ __('Position X') }}</div>
                                                                    <input type="number" min="0"
                                                                        name="student_name_x_position"
                                                                        value="{{$certificate->student_name_x_position}}"
                                                                        class="form-control" placeholder="0">
                                                                    @if ($errors->has('student_name_x_position'))
                                                                    <span class="text-danger"><i
                                                                            class="fas fa-exclamation-triangle"></i> {{
                                                                        $errors->first('student_name_x_position')
                                                                        }}</span>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-12 mb-15">
                                                                    <div
                                                                        class="label-text-title color-heading font-16 mb-1">
                                                                        {{ __('Position Y') }}</div>
                                                                    <input type="number" min="0"
                                                                        name="student_name_y_position"
                                                                        value="{{$certificate->student_name_y_position}}"
                                                                        class="form-control" placeholder="0">
                                                                    @if ($errors->has('student_name_y_position'))
                                                                    <span class="text-danger"><i
                                                                            class="fas fa-exclamation-triangle"></i> {{
                                                                        $errors->first('student_name_y_position')
                                                                        }}</span>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-6 mb-15">
                                                                    <div
                                                                        class="label-text-title color-heading font-16 mb-1">
                                                                        {{ __('Font Size') }}</div>
                                                                    <input type="number" min="1"
                                                                        name="student_name_font_size"
                                                                        value="{{$certificate->student_name_font_size}}"
                                                                        class="form-control" placeholder="30">
                                                                    @if ($errors->has('student_name_font_size'))
                                                                    <span class="text-danger"><i
                                                                            class="fas fa-exclamation-triangle"></i> {{
                                                                        $errors->first('student_name_font_size')
                                                                        }}</span>
                                                                    @endif
                                                                </div>
                                                                <div class="col-md-6 mb-15">
                                                                    <div
                                                                        class="label-text-title color-heading font-16 mb-1">
                                                                        {{ __('Font Color') }}</div>
                                                                    <span class="color-picker">
                                                                        <label for="colorPicker6" class="mb-0">
                                                                            <input type="color"
                                                                                name="student_name_font_color"
                                                                                value="{{$certificate->student_name_font_color}}"
                                                                                id="colorPicker6">
                                                                        </label>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="accordion-item course-sidebar-accordion-item">
                                                <h2 class="accordion-header course-sidebar-title mb-2"
                                                    id="panelsStayOpen-headingTwo">
                                                    <button class="accordion-button collapsed" type="button"
                                                        data-bs-toggle="collapse"
                                                        data-bs-target="#panelsStayOpen-collapseTwo"
                                                        aria-expanded="false"
                                                        aria-controls="panelsStayOpen-collapseTwo">
                                                        {{ __('Body') }}
                                                    </button>
                                                </h2>
                                                <div id="panelsStayOpen-collapseTwo"
                                                    class="accordion-collapse collapse show"
                                                    aria-labelledby="panelsStayOpen-headingTwo">
                                                    <div class="accordion-body">
                                                        <div class="certificate-inner-box">
                                                            <div class="row">
                                                                <div class="col-md-12 mb-15">
                                                                    <div
                                                                        class="label-text-title color-heading font-16 mb-1">
                                                                        {{ __('Body') }}</div>
                                                                    <textarea name="body" id="" cols="30" rows="6"
                                                                        class="form-control">{{$certificate->body}}</textarea>

                                                                    <div class="certificate-body-textarea-btns mt-1">
                                                                        <button class="color-hover">[name]</button>
                                                                        <button class="color-hover">[course]</button>
                                                                    </div>
                                                                    @if ($errors->has('body'))
                                                                    <span class="text-danger"><i
                                                                            class="fas fa-exclamation-triangle"></i> {{
                                                                        $errors->first('body') }}</span>
                                                                    @endif
                                                                </div>
                                                            </div>

                                                            <div class="row">
                                                                <div class="col-md-12 mb-15">
                                                                    <div
                                                                        class="label-text-title color-heading font-16 mb-1">
                                                                        {{ __('Position X') }}</div>
                                                                    <input type="number" min="0" name="body_x_position"
                                                                        value="{{$certificate->body_x_position}}"
                                                                        class="form-control" placeholder="0">
                                                                    @if ($errors->has('body_x_position'))
                                                                    <span class="text-danger"><i
                                                                            class="fas fa-exclamation-triangle"></i> {{
                                                                        $errors->first('body_x_position') }}</span>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-12 mb-15">
                                                                    <div
                                                                        class="label-text-title color-heading font-16 mb-1">
                                                                        {{ __('Position Y') }}</div>
                                                                    <input type="number" min="0" name="body_y_position"
                                                                        value="{{$certificate->body_y_position}}"
                                                                        class="form-control" placeholder="0">
                                                                    @if ($errors->has('body_y_position'))
                                                                    <span class="text-danger"><i
                                                                            class="fas fa-exclamation-triangle"></i> {{
                                                                        $errors->first('body_y_position') }}</span>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-6 mb-15">
                                                                    <div
                                                                        class="label-text-title color-heading font-16 mb-1">
                                                                        {{ __('Font Size') }}</div>
                                                                    <input type="number" min="0" name="body_font_size"
                                                                        value="{{$certificate->body_font_size}}"
                                                                        class="form-control" placeholder="30">
                                                                    @if ($errors->has('body_font_size'))
                                                                    <span class="text-danger"><i
                                                                            class="fas fa-exclamation-triangle"></i> {{
                                                                        $errors->first('body_font_size') }}</span>
                                                                    @endif
                                                                </div>
                                                                <div class="col-md-6 mb-15">
                                                                    <div
                                                                        class="label-text-title color-heading font-16 mb-1">
                                                                        {{ __('Font Color') }}</div>
                                                                    <span class="color-picker">
                                                                        <label for="colorPicker1" class="mb-0">
                                                                            <input type="color" name="body_font_color"
                                                                                value="{{$certificate->body_font_color}}"
                                                                                id="colorPicker1">
                                                                        </label>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="accordion-item course-sidebar-accordion-item">
                                                <h2 class="accordion-header course-sidebar-title mb-2"
                                                    id="panelsStayOpen-headingThree">
                                                    <button class="accordion-button collapsed" type="button"
                                                        data-bs-toggle="collapse"
                                                        data-bs-target="#panelsStayOpen-collapseThree"
                                                        aria-expanded="false"
                                                        aria-controls="panelsStayOpen-collapseThree">
                                                        {{ __('Certificate Role 01') }}
                                                    </button>
                                                </h2>
                                                <div id="panelsStayOpen-collapseThree"
                                                    class="accordion-collapse collapse show"
                                                    aria-labelledby="panelsStayOpen-headingThree">
                                                    <div class="accordion-body">
                                                        <div class="certificate-inner-box">
                                                            <div class="row">
                                                                <div class="col-md-12 mb-15">
                                                                    <div
                                                                        class="label-text-title color-heading font-16 mb-1">
                                                                        {{ __('Role 01 show') }}</div>
                                                                    <div
                                                                        class="admin-certificate-radio d-flex align-items-center">
                                                                        <div
                                                                            class="form-check mb-0 d-flex align-items-center">
                                                                            <input class="form-check-input" type="radio"
                                                                                name="role_1_show" id="role_1_show_yes"
                                                                                value="yes" {{old('role_1_show')=='yes'
                                                                                ? 'checked' : '' }}>
                                                                            <label
                                                                                class="form-check-label mb-0 color-heading"
                                                                                for="role_1_show_yes">Yes</label>
                                                                        </div>
                                                                        <div
                                                                            class="form-check mb-0 d-flex align-items-center">
                                                                            <input class="form-check-input" type="radio"
                                                                                name="role_1_show" id="role_1_show_no"
                                                                                value="no" {{old('role_1_show')=='no'
                                                                                ? 'checked' : '' }}>
                                                                            <label
                                                                                class="form-check-label mb-0 color-heading"
                                                                                for="role_1_show_no">No</label>
                                                                        </div>
                                                                    </div>
                                                                    @if ($errors->has('role_1_show'))
                                                                    <span class="text-danger"><i
                                                                            class="fas fa-exclamation-triangle"></i> {{
                                                                        $errors->first('role_1_show') }}</span>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-12 mb-15">
                                                                    <div
                                                                        class="label-text-title color-heading font-16 mb-1">
                                                                        {{ __('Title') }}</div>
                                                                    <input type="text" name="role_1_title"
                                                                        value="{{$certificate->role_1_title}}"
                                                                        class="form-control"
                                                                        placeholder="Administrator">
                                                                    @if ($errors->has('role_1_title'))
                                                                    <span class="text-danger"><i
                                                                            class="fas fa-exclamation-triangle"></i> {{
                                                                        $errors->first('role_1_title') }}</span>
                                                                    @endif
                                                                </div>
                                                            </div>


                                                            <div class="row">
                                                                <div class="col-md-12 mb-15">
                                                                    <div
                                                                        class="label-text-title color-heading font-16 mb-1">
                                                                        {{ __('Signature') }}</div>
                                                                    <div
                                                                        class="create-certificate-browse-file form-control mb-2">
                                                                        <div>
                                                                            <input type="file" name="role_1_signature"
                                                                                accept="image/*" class="form-control"
                                                                                title="Browse Image File">
                                                                        </div>
                                                                    </div>
                                                                    <div class="recomended-size-for-img font-12">{{
                                                                        __('Accepted Files') }}: PNG</div>
                                                                    <div class="recomended-size-for-img font-12">{{
                                                                        __('Accepted Size') }}: 120 x 60</div>
                                                                    @if ($errors->has('role_1_signature'))
                                                                    <span class="text-danger"><i
                                                                            class="fas fa-exclamation-triangle"></i> {{
                                                                        $errors->first('role_1_signature') }}</span>
                                                                    @endif
                                                                </div>
                                                            </div>

                                                            <div class="row">

                                                                <div class="col-md-12 mb-15">
                                                                    <div
                                                                        class="label-text-title color-heading font-16 mb-1">
                                                                        {{ __('Position X') }}</div>
                                                                    <input type="number" min="0"
                                                                        name="role_1_x_position"
                                                                        value="{{$certificate->role_1_x_position}}"
                                                                        class="form-control" placeholder="0">
                                                                    @if ($errors->has('role_1_x_position'))
                                                                    <span class="text-danger"><i
                                                                            class="fas fa-exclamation-triangle"></i> {{
                                                                        $errors->first('role_1_x_position') }}</span>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                            <div class="row">

                                                                <div class="col-md-12 mb-15">
                                                                    <div
                                                                        class="label-text-title color-heading font-16 mb-1">
                                                                        {{ __('Position Y') }}</div>
                                                                    <input type="number" min="0"
                                                                        name="role_1_y_position"
                                                                        value="{{$certificate->role_1_y_position}}"
                                                                        class="form-control" placeholder="0">
                                                                    @if ($errors->has('role_1_y_position'))
                                                                    <span class="text-danger"><i
                                                                            class="fas fa-exclamation-triangle"></i> {{
                                                                        $errors->first('role_1_y_position') }}</span>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-6 mb-15">
                                                                    <div
                                                                        class="label-text-title color-heading font-16 mb-1">
                                                                        {{ __('Font Size') }}</div>
                                                                    <input type="number" min="0" name="role_1_font_size"
                                                                        value="{{$certificate->role_1_font_size}}"
                                                                        class="form-control" placeholder="30">
                                                                    @if ($errors->has('role_1_font_size'))
                                                                    <span class="text-danger"><i
                                                                            class="fas fa-exclamation-triangle"></i> {{
                                                                        $errors->first('role_1_font_size') }}</span>
                                                                    @endif
                                                                </div>
                                                                <div class="col-md-6 mb-15">
                                                                    <div
                                                                        class="label-text-title color-heading font-16 mb-1">
                                                                        {{ __('Font Color') }}</div>
                                                                    <span class="color-picker">
                                                                        <label for="colorPicker2" class="mb-0">
                                                                            <input type="color" name="role_1_font_color"
                                                                                value="{{$certificate->role_1_font_color}}"
                                                                                id="colorPicker2">
                                                                        </label>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="accordion-item course-sidebar-accordion-item">
                                                <h2 class="accordion-header course-sidebar-title mb-2"
                                                    id="panelsStayOpen-headingThree1">
                                                    <button class="accordion-button collapsed" type="button"
                                                        data-bs-toggle="collapse"
                                                        data-bs-target="#panelsStayOpen-collapseThree1"
                                                        aria-expanded="false"
                                                        aria-controls="panelsStayOpen-collapseThree1">
                                                        {{ __('Certificate Role 02') }}
                                                    </button>
                                                </h2>
                                                <div id="panelsStayOpen-collapseThree1"
                                                    class="accordion-collapse collapse show"
                                                    aria-labelledby="panelsStayOpen-headingThree1">
                                                    <div class="accordion-body">
                                                        <div class="certificate-inner-box">
                                                            <div class="row">
                                                                <div class="col-md-12 mb-15">
                                                                    <div
                                                                        class="label-text-title color-heading font-16 mb-1">
                                                                        {{ __('Role 02 show') }}</div>
                                                                    <div
                                                                        class="admin-certificate-radio d-flex align-items-center">
                                                                        <div
                                                                            class="form-check mb-0 d-flex align-items-center">
                                                                            <input class="form-check-input" type="radio"
                                                                                name="role_2_show" id="role_2_show_yes"
                                                                                value="yes" {{old('role_2_show')=='yes'
                                                                                ? 'checked' : '' }}>
                                                                            <label
                                                                                class="form-check-label mb-0 color-heading"
                                                                                for="role_2_show_yes">Yes</label>
                                                                        </div>
                                                                        <div
                                                                            class="form-check mb-0 d-flex align-items-center">
                                                                            <input class="form-check-input" type="radio"
                                                                                name="role_2_show" id="role_2_show_no"
                                                                                value="no" {{old('role_2_show')=='no'
                                                                                ? 'checked' : '' }}>
                                                                            <label
                                                                                class="form-check-label mb-0 color-heading"
                                                                                for="role_2_show_no">No</label>
                                                                        </div>
                                                                    </div>
                                                                    @if ($errors->has('role_2_show'))
                                                                    <span class="text-danger"><i
                                                                            class="fas fa-exclamation-triangle"></i> {{
                                                                        $errors->first('role_2_show') }}</span>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-12 mb-15">
                                                                    <div
                                                                        class="label-text-title color-heading font-16 mb-1">
                                                                        {{ __('Title') }}</div>
                                                                    <input type="text" name="role_2_title"
                                                                        value="{{$certificate->role_2_title}}"
                                                                        class="form-control" placeholder="Instructor">
                                                                    @if ($errors->has('role_2_title'))
                                                                    <span class="text-danger"><i
                                                                            class="fas fa-exclamation-triangle"></i> {{
                                                                        $errors->first('role_2_title') }}</span>
                                                                    @endif
                                                                </div>
                                                            </div>

                                                            <div class="row">
                                                                <div class="col-md-12 mb-15">
                                                                    <div
                                                                        class="label-text-title color-heading font-16 mb-1">
                                                                        {{ __('Position X') }}</div>
                                                                    <input type="number" min="0"
                                                                        name="role_2_x_position"
                                                                        value="{{$certificate->role_2_x_position}}"
                                                                        class="form-control" placeholder="0">
                                                                    @if ($errors->has('role_2_x_position'))
                                                                    <span class="text-danger"><i
                                                                            class="fas fa-exclamation-triangle"></i> {{
                                                                        $errors->first('role_2_x_position') }}</span>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-12 mb-15">
                                                                    <div
                                                                        class="label-text-title color-heading font-16 mb-1">
                                                                        {{ __('Position Y') }}</div>
                                                                    <input type="number" min="0"
                                                                        name="role_2_y_position"
                                                                        value="{{$certificate->role_2_y_position}}"
                                                                        class="form-control" placeholder="0">
                                                                    @if ($errors->has('role_2_y_position'))
                                                                    <span class="text-danger"><i
                                                                            class="fas fa-exclamation-triangle"></i> {{
                                                                        $errors->first('role_2_y_position') }}</span>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-6 mb-15">
                                                                    <div
                                                                        class="label-text-title color-heading font-16 mb-1">
                                                                        {{ __('Font Size') }}</div>
                                                                    <input type="number" min="1" name="role_2_font_size"
                                                                        value="{{$certificate->role_2_font_size}}"
                                                                        class="form-control" placeholder="30">
                                                                    @if ($errors->has('role_2_font_size'))
                                                                    <span class="text-danger"><i
                                                                            class="fas fa-exclamation-triangle"></i> {{
                                                                        $errors->first('role_2_font_size') }}</span>
                                                                    @endif
                                                                </div>
                                                                <div class="col-md-6 mb-15">
                                                                    <div
                                                                        class="label-text-title color-heading font-16 mb-1">
                                                                        {{ __('Font Color') }}</div>
                                                                    <span class="color-picker">
                                                                        <label for="colorPicker7" class="mb-0">
                                                                            <input type="color" name="role_2_font_color"
                                                                                value="{{$certificate->role_2_font_color}}"
                                                                                id="colorPicker7">
                                                                        </label>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <input type="hidden" name="final_submit" value="0">
                                        <button type="button" id="save-and-preview-btn"
                                            class="theme-btn theme-button1 default-hover-btn mt-30">{{
                                            __('Save certificate') }}</button>
                                    </div>
                                </form>
                            </div>

                        </div>
                    </div>

                </div>
            </div>
        </div>

    </div>
</div>
<!-- Page content area end -->
@endsection

@push('style')
<link rel="stylesheet" href="{{asset('frontend/assets/fonts/feather/feather.css')}}">
<link rel="stylesheet" href="{{asset('frontend/assets/css/for-certificate.css')}}">
<link rel="preload" href="{{asset('frontend/assets/fonts/mongolian_baiti/MongolianBaiti.woff2')}}" as="font"
    type="font/woff" crossorigin>
<link rel="preload" href="{{asset('frontend/assets/fonts/mongolian_baiti/MongolianBaiti.woff2')}}" as="font"
    type="font/woff2" crossorigin>
@endpush

@push('script')
<script src="{{asset('frontend/assets/js/color.js')}}"></script>
<script src="{{ asset('frontend/assets/js/html2canvas.js') }}"></script>

<script>
    screenshot();
    $(':input').keypress(function(e){
        if ( e.which == 13 ) e.preventDefault();
    });

    $(document).on('input', '.form-control,.form-check-input', function(e){
        e.preventDefault();

        clearTimeout( $(this).data('timer') );

        var timer = setTimeout(function() {

            var form = $('#certificate-form');
            let enctype = form.prop("enctype");
            if (!enctype) {
                enctype = "application/x-www-form-urlencoded";
            }

            $.ajax({
                type: form.prop('method'),
                encType: enctype,
                contentType: false,
                processData: false,
                url: form.prop('action'),
                data: new FormData( form[0]),
                dataType: 'json',
                success: function(response){
                    if(typeof response.certificate != 'undefined'){
                        $('#certificate-preview-div-hidden').html(response.certificate);
                        screenshot()
                    }
                },
                error: function(error){
                    if(typeof error.responseJSON.errors != 'undefined'){
                        $.each(error.responseJSON.errors, function(ind, error){
                            toastr.error(error[0])
                        });
                    }
                }
            });
        }, 1000);
    });

    $(document).on('click', '#save-and-preview-btn', function(e){
        e.preventDefault();

        var form = $('#certificate-form');
        let enctype = form.prop("enctype");
        if (!enctype) {
            enctype = "application/x-www-form-urlencoded";
        }

        $(document).find(':input[name=final_submit]').val(1);

        $.ajax({
            type: form.prop('method'),
            encType: enctype,
            contentType: false,
            processData: false,
            url: form.prop('action'),
            data: new FormData( form[0]),
            dataType: 'json',
            success: function(response){
                if(typeof response.certificate != 'undefined'){
                    $('#certificate-preview-div-hidden').html(response.certificate);
                }
                else if(typeof response.view != 'undefined'){
                    window.location.href = response.view;
                }
            }
        });
    });


    function screenshot(){
        html2canvas(document.getElementById("certificate-preview-div-hidden")).then(function(canvas){
            $("#certificate-preview-div").html('<img class="img-fluid" src="'+canvas.toDataURL()+'" />');
	   });
    }
</script>
@endpush
