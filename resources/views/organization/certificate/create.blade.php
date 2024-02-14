@extends('layouts.organization')

@section('breadcrumb')
<div class="page-banner-content text-center">
    <h3 class="page-banner-heading text-white pb-15"> {{__('Create Certificate')}} </h3>

    <!-- Breadcrumb Start-->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb justify-content-center">
            <li class="breadcrumb-item font-14"><a href="{{route('organization.dashboard')}}">{{__('Dashboard')}}</a></li>
            <li class="breadcrumb-item font-14"><a href="{{ route('organization.certificate.index') }}">{{__('Manage
                    Certificate')}}</a></li>
            <li class="breadcrumb-item font-14 active" aria-current="page">{{__('Create Certificate')}}</li>
        </ol>
    </nav>
</div>
@endsection

@section('content')
<div class="instructor-profile-right-part">
    <div class="instructor-create-certificate-page only-frontend-create-certificate-page">

        <div class="instructor-my-courses-title d-flex justify-content-between align-items-center">
            <h6>{{__('Create Certificate')}}</h6>
        </div>

        <div class="row create-certificate-row">
            <div class="col-12 col-md-12 col-lg-6 col-xl-5 col-xxl-4">
                <div class="create-certificate-sidebar">
                    <form method="POST"
                        action="{{route('organization.certificate.store', [$course->uuid, $certificate->uuid])}}"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="accordion" id="accordionPanelsStayOpenExample">
                            <div class="accordion-item course-sidebar-accordion-item">
                                <h2 class="accordion-header course-sidebar-title mb-2" id="panelsStayOpen-headingOne">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#panelsStayOpen-collapseOne" aria-expanded="true"
                                        aria-controls="panelsStayOpen-collapseOne">
                                        {{ __('Certificate Title') }}
                                    </button>
                                </h2>
                                <div id="panelsStayOpen-collapseOne" class="accordion-collapse collapse show"
                                    aria-labelledby="panelsStayOpen-headingOne">
                                    <div class="accordion-body">
                                        <div class="certificate-inner-box">
                                            <div class="row">
                                                <div class="col-md-12 mb-15">
                                                    <div class="label-text-title color-heading font-16 mb-1">{{
                                                        __('Title') }}</div>
                                                    <input type="text" name="title" value="{{$certificate->title}}"
                                                        class="form-control" placeholder="Certificate Title" required>
                                                    @if ($errors->has('title'))
                                                    <span class="text-danger"><i
                                                            class="fas fa-exclamation-triangle"></i> {{
                                                        $errors->first('title') }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12 mb-15">
                                                    <div class="label-text-title color-heading font-16 mb-1">{{
                                                        __('Position X') }}</div>
                                                    <input type="number" name="title_x_position"
                                                        value="{{$certificate->title_x_position??0}}" required step="any"
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
                                                    <div class="label-text-title color-heading font-16 mb-1">{{
                                                        __('Position Y') }}</div>
                                                    <input type="number" name="title_y_position"
                                                        value="{{$certificate->title_y_position??0}}" required step="any"
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
                                                    <div class="label-text-title color-heading font-16 mb-1">{{ __('Font
                                                        Size') }}</div>
                                                    <input type="number" name="title_font_size"
                                                        value="{{$certificate->title_font_size??0}}" 
                                                        class="form-control" placeholder="30">
                                                    @if ($errors->has('title_font_size'))
                                                    <span class="text-danger"><i
                                                            class="fas fa-exclamation-triangle"></i> {{
                                                        $errors->first('title_font_size') }}</span>
                                                    @endif
                                                </div>
                                                <div class="col-md-6 mb-15">
                                                    <div class="label-text-title color-heading font-16 mb-1">{{ __('Font
                                                        Color') }}</div>
                                                    <span class="color-picker">
                                                        <label for="colorPicker" class="mb-0">
                                                            <input type="color" name="title_font_color"
                                                                value="{{$certificate->title_font_color}}"
                                                                id="colorPicker">
                                                        </label>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="accordion-item course-sidebar-accordion-item">
                                <h2 class="accordion-header course-sidebar-title mb-2" id="panelsStayOpen-headingTwo">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#panelsStayOpen-collapseTwo" aria-expanded="false"
                                        aria-controls="panelsStayOpen-collapseTwo">
                                        {{ __('Body') }}
                                    </button>
                                </h2>
                                <div id="panelsStayOpen-collapseTwo" class="accordion-collapse collapse show"
                                    aria-labelledby="panelsStayOpen-headingTwo">
                                    <div class="accordion-body">
                                        <div class="certificate-inner-box">
                                            <div class="row">
                                                <div class="col-md-12 mb-15">
                                                    <div class="label-text-title color-heading font-16 mb-1">{{
                                                        __('Body') }}</div>
                                                    <textarea name="body" id="" cols="30" rows="6" class="form-control"
                                                        required>{{$certificate->body}}</textarea>

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
                                                    <div class="label-text-title color-heading font-16 mb-1">{{
                                                        __('Position X') }}</div>
                                                    <input type="number" name="body_x_position"
                                                        value="{{$certificate->body_x_position??0}}" required step="any"
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
                                                    <div class="label-text-title color-heading font-16 mb-1">{{
                                                        __('Position Y') }}</div>
                                                    <input type="number" name="body_y_position"
                                                        value="{{$certificate->body_y_position??0}}" required step="any"
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
                                                    <div class="label-text-title color-heading font-16 mb-1">{{ __('Font
                                                        Size') }}</div>
                                                    <input type="number" name="body_font_size"
                                                        value="{{$certificate->body_font_size??0}}" 
                                                        class="form-control" placeholder="30">
                                                    @if ($errors->has('body_font_size'))
                                                    <span class="text-danger"><i
                                                            class="fas fa-exclamation-triangle"></i> {{
                                                        $errors->first('body_font_size') }}</span>
                                                    @endif
                                                </div>
                                                <div class="col-md-6 mb-15">
                                                    <div class="label-text-title color-heading font-16 mb-1">{{ __('Font
                                                        Color') }}</div>
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
                            @if($certificate->role_2_show == 'yes')
                            <div class="accordion-item course-sidebar-accordion-item">
                                <h2 class="accordion-header course-sidebar-title mb-2" id="panelsStayOpen-headingThree">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#panelsStayOpen-collapseThree" aria-expanded="false"
                                        aria-controls="panelsStayOpen-collapseThree">
                                        {{ __('Your Signature') }}
                                    </button>
                                </h2>
                                <div id="panelsStayOpen-collapseThree" class="accordion-collapse collapse show"
                                    aria-labelledby="panelsStayOpen-headingThree">
                                    <div class="accordion-body">
                                        <div class="certificate-inner-box">
                                            <div class="row">
                                                <div class="col-md-12 mb-15">
                                                    <div class="label-text-title color-heading font-16 mb-1">{{
                                                        __('Signature') }} </div>
                                                    <div class="create-certificate-browse-file form-control mb-2">
                                                        <div>
                                                            <input type="file" name="signature" accept="image/*"
                                                                class="form-control" title="Browse Image File">
                                                        </div>
                                                    </div>
                                                    <div class="recomended-size-for-img font-12">({{ __('Recommend
                                                        Size') }} 120x60 px)</div>
                                                    @if ($errors->has('signature'))
                                                    <span class="text-danger"><i
                                                            class="fas fa-exclamation-triangle"></i> {{
                                                        $errors->first('signature') }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12 mb-15">
                                                    <div class="label-text-title color-heading font-16 mb-1">{{
                                                        __('Position X') }}</div>
                                                    <input type="text" name="role_2_x_position"
                                                        value="{{$certificate->role_2_x_position??0}}" required
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
                                                    <div class="label-text-title color-heading font-16 mb-1">{{
                                                        __('Position Y') }}</div>
                                                    <input type="text" name="role_2_y_position"
                                                        value="{{$certificate->role_2_y_position??0}}" required
                                                        class="form-control" placeholder="0">
                                                    @if ($errors->has('role_2_y_position'))
                                                    <span class="text-danger"><i
                                                            class="fas fa-exclamation-triangle"></i> {{
                                                        $errors->first('role_2_y_position') }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                        <button type="submit" class="theme-btn theme-button1 default-hover-btn mt-30">{{ __('Save
                            Certificate') }}</button>
                    </form>
                </div>
            </div>
            <div class="col-12 col-md-12 col-lg-6 col-xl-7 col-xxl-8">
                <div class="sticky-top" id="certificate-preview-div">
                </div>
                <div style="overflow: hidden; height: 0;">
                    <div id="certificate-preview-div-hidden" style="width:1030px; height:734px; overflow:hidden">
                        @include('admin.certificate.view')
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection

@push('style')
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
    function screenshot(){
        html2canvas(document.getElementById("certificate-preview-div-hidden")).then(function(canvas){
            $("#certificate-preview-div").html('<img class="img-fluid" src="'+canvas.toDataURL()+'" />');
        });
    }
</script>
@endpush
