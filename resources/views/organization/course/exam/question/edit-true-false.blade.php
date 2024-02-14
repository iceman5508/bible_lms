@extends('layouts.organization')

@section('breadcrumb')
    <div class="page-banner-content text-center">
        <h3 class="page-banner-heading text-white pb-15"> {{__('My Courses')}} </h3>

        <!-- Breadcrumb Start-->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb justify-content-center">
                <li class="breadcrumb-item font-14"><a href="{{route('organization.dashboard')}}">{{__('Dashboard')}}</a></li>
                <li class="breadcrumb-item font-14" aria-current="page"><a href="{{ route('organization.course.index') }}">{{__('My Courses')}}</a></li>
                <li class="breadcrumb-item font-14"><a href="{{ route('organization.exam.index', @$exam->course->uuid) }}">{{ __('Quiz List') }}</a></li>
                <li class="breadcrumb-item font-14 active" aria-current="page">{{ __('Edit True False') }}</li>
            </ol>
        </nav>
    </div>
@endsection

@section('content')
    <div class="instructor-profile-right-part">
        <div class="instructor-add-question-page instructor-add-true-false-question bg-white">
            <div class="instructor-my-courses-title d-flex justify-content-between align-items-center">
                <h6>Question for {{$exam->name}}</h6>
                <p>{{ @$exam->course->title }}</p>
            </div>
            <div class="row">


                <form action="{{route('organization.exam.update-true-false-question', [$question->uuid])}}" method="post" class="add-question-form add-true-false-question-form needs-validation" novalidate>
                    @csrf

                    <div class="upload-course-item-block radius-8">

                        <div class="row">
                            <div class="col-md-12 mb-30">
                                <label class="label-text-title color-heading font-medium font-16 mb-3">{{ __('Question') }} </label>
                                <input type="text" name="name" value="{{$question->name}}" required class="form-control" placeholder="{{ __('Enter your question') }}">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12 mb-30">
                                <div class="true-false-item-wrap d-flex align-items-center">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio"  name="is_correct_answer" value="1" {{$question->options[0]->is_correct_answer == 'yes' ? 'checked' : '' }} required id="correct_ans1">
                                        <label class="form-check-label mb-0 color-heading" for="correct_ans1">
                                            {{__('True')}}
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio"  name="is_correct_answer" value="0" {{$question->options[1]->is_correct_answer == 'yes' ? 'checked' : '' }} required id="correct_ans2">
                                        <label class="form-check-label mb-0 color-heading" for="correct_ans2">
                                            {{__('False')}}
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="question-openion-btn-wrap d-flex justify-content-between align-items-start mb-20">
                            <div class="add-question-save-btns">
                                <a href="{{route('organization.exam.view', [$exam->uuid])}}" class="theme-btn theme-button3 show-last-phase-back-btn">{{__('Cancel')}}</a>
                                <input type="submit" class="theme-btn theme-button1" value="{{__('Save')}}" name="save">
                            </div>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
@endsection

@push('style')

@endpush

@push('script')
    <script src="{{asset('frontend/assets/js/custom/form-validation.js')}}"></script>
@endpush
