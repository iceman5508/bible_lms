@extends('layouts.organization')

@section('breadcrumb')
<div class="page-banner-content text-center">
    <h3 class="page-banner-heading text-white pb-15"> {{__('Upload Course')}} </h3>

    <!-- Breadcrumb Start-->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb justify-content-center">
            <li class="breadcrumb-item font-14"><a href="{{route('organization.dashboard')}}">{{__('Dashboard')}}</a></li>
            <li class="breadcrumb-item font-14"><a href="{{ route('organization.course.index') }}">{{__('My Courses')}}</a></li>
            <li class="breadcrumb-item font-14"><strong>{{ __('Instructor') }}</strong></li>
            <li class="breadcrumb-item font-14 active" aria-current="page">{{__('Upload Course')}}</li>
        </ol>
    </nav>
</div>
@endsection

@section('content')
<div class="instructor-profile-right-part instructor-upload-course-box-part">
    <div class="instructor-upload-course-box">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div id="msform">
                        <!-- progressbar -->
                        <ul id="progressbar"
                            class="upload-course-item-block d-flex align-items-center justify-content-center">
                            <li class="active" id="account"><strong>{{ __('Course Overview') }}</strong></li>
                            <li class="active" id="personal"><strong>{{ __('Upload Video') }}</strong></li>
                            <li class="active" id="personal"><strong>{{ __('Instructor') }}</strong></li>
                            <li id="confirm"><strong>{{ __('Submit Process') }}</strong></li>
                        </ul>

                        <form method="POST" action="{{route('organization.course.store.instructor', [$course->uuid])}}"
                            class="row g-3 needs-validation" novalidate>
                            @csrf
                            <div class="upload-course-step-item upload-course-overview-step-item">
                                <div class="row">
                                    @php
                                    $instructors_id =
                                    @$course->course_instructors->pluck('instructor_id')->toArray();
                                    @endphp
                                    <div class="col-md-12">
                                        <label class="label-text-title color-heading font-medium font-16 mb-3">{{
                                            __('Other Instructors') }}
                                            <span
                                                class="cursor tooltip-show-btn share-referral-big-btn primary-btn get-referral-btn border-0"
                                                data-toggle="popover" data-bs-placement="bottom"
                                                data-bs-content="Meridian sun strikes upper urface of the impenetrable foliage of my trees">
                                                !
                                            </span>
                                        </label>
                                        <select class="select2 mb-3" id="instructor-id" multiple>
                                            @foreach ($instructors as $instructor)
                                            @php
                                            $oldData = @old('instructor_id')[$instructor->id];
                                            @endphp
                                            <option value="{{ $instructor->id }}" @if($oldData == $instructor->id) selected
                                                @elseif(in_array($instructor->id, $instructors_id)) selected @endif>{{
                                                $instructor->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="row" id="instructor-block">
                                    <div class="col-md-12">
                                        <table class="mt-3 table table-sm">
                                            <thead>
                                                <tr>
                                                    <th>{{ __('Instructor Name') }}</th>
                                                    <th>{{ __('Revenew share (in % between 0 to 100)') }}</th>
                                                </tr>
                                            </thead>
                                            <tbody id="instructor-parent">
                                                @foreach ($instructors as $instructor)
                                                @php
                                                $oldData = @old('instructor_id')[$instructor->id];
                                                $oldShare = @old('share')[$instructor->id];
                                                $oldShare = ($oldShare) ? $oldShare : @$course->course_instructors->where('instructor_id', $instructor->id)->first()->share;
                                                @endphp
                                                @if($oldData == $instructor->id || in_array($instructor->id, $instructors_id))
                                                <tr class="instructor-child">
                                                    <td>
                                                        <input type="hidden" name="instructor_id[{{ $instructor->id }}]"
                                                            value="{{ $instructor->id }}">
                                                        <input type="text" disabled
                                                            value="{{ $instructor->name }}"
                                                            class="form-control">
                                                    </td>
                                                    <td>
                                                        <input type="number" min=0 max=100
                                                            name="share[{{ $instructor->id }}]" value="{{ $oldShare }}"
                                                            class="form-control"
                                                            placeholder="Type revenew share parcentage" required>
                                                    </td>
                                                </tr>
                                                @endif
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="stepper-action-btns">
                                <a href="{{route('organization.course.edit', [$course->uuid, 'step=lesson'])}}"
                                    class="theme-btn theme-button3">{{__('Back')}}</a>
                                <button type="submit" class="theme-btn default-hover-btn theme-button1">{{__('Save and
                                    continue')}}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('style')
<link rel="stylesheet" href="{{asset('common/css/select2.css')}}">
@endpush

@push('script')
<script src="{{asset('frontend/assets/js/custom/form-validation.js')}}"></script>
<script src="{{asset('frontend/assets/js/custom/index.js')}}"></script>
<script src="{{asset('common/js/select2.min.js')}}"></script>

<script>
    "use strict"
    var jInstructors = @json($instructors);

    $(document).ready(function() {
        $('.select2').select2({
            width: '100%'
        });

        if(!$(document).find('.instructor-child').length){
            $(document).find('#instructor-block').addClass('d-none');
        }

        $(document).on('select2:select', '#instructor-id', function(e){
            $(document).find('#instructor-block').removeClass('d-none');
            let selected_id = e.params.data.id;
            let instructor = jInstructors.find(x => x.id == selected_id);
            let html = `<tr class="instructor-child">
                            <td>
                                <input type="hidden" name="instructor_id[${instructor.id}]" value="${instructor.id}">
                                <input type="text" disabled value="${instructor.name}" class="form-control">
                            </td>
                            <td >
                                <input type="number" min=0 max=100 name="share[${instructor.id}]" value="0" class="form-control" placeholder="Type revenew share parcentage" required>
                            </td>
                        </tr>`;

            $(document).find('#instructor-parent').append(html);
        });

        $(document).on('select2:unselecting', '#instructor-id', function(e){
            $(document).find('input[name^=instructor_id][value='+e.params.args.data.id+']').closest('.instructor-child').remove();

            if(!$(document).find('.instructor-child').length){
                $(document).find('#instructor-block').addClass('d-none');
            }
        })
    });
</script>

@endpush
