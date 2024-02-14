@extends('layouts.instructor')

@section('breadcrumb')
    <div class="page-banner-content text-center">
        <h3 class="page-banner-heading text-white pb-15"> {{__('Manage Question')}} </h3>

        <!-- Breadcrumb Start-->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb justify-content-center">
                <li class="breadcrumb-item font-14"><a href="{{route('instructor.dashboard')}}">{{__('Dashboard')}}</a></li>
                <li class="breadcrumb-item font-14" aria-current="page"><a href="{{ route('instructor.course') }}">{{__('My Courses')}}</a></li>
                <li class="breadcrumb-item font-14"><a href="{{ route('exam.index', @$exam->course->uuid) }}">{{ __('Quiz List') }}</a></li>
                <li class="breadcrumb-item font-14 active" aria-current="page">{{ __('Edit MCQ Quiz') }}</li>
            </ol>
        </nav>
    </div>
@endsection


@section('content')
    <div class="instructor-profile-right-part">
        <div class="instructor-add-question-page bg-white">
            <div class="instructor-my-courses-title d-flex justify-content-between align-items-center">
                <h6>{{$exam->name}}</h6>
                <p>{{ @$exam->course->title }}</p>
            </div>
            <div class="row">

                <form action="{{route('exam.update-mcq-question', [$question->uuid])}}" method="post" class="add-question-form needs-validation" novalidate>
                    @csrf

                    <div class="upload-course-item-block radius-8">

                        <div class="row">
                            <div class="col-md-12 mb-30">
                                <label class="label-text-title color-heading font-medium font-16 mb-3">{{ __('Question') }}  </label>
                                <input type="text" name="name" value="{{$question->name}}" required class="form-control" placeholder="{{ __('Enter your question') }}">
                            </div>
                        </div>

                        <div class="question-openion-box">
                                @foreach($question->options as $key => $option)
                                    <div class="row">
                                        <div class="col-md-12 mb-30">
                                            <div class="openion-item d-flex align-items-center">
                                                <input type="text" name="options[]" value="{{$option->name}}" required class="form-control" placeholder="{{__('Option')}} {{$key + 1}}">

                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio"  name="is_correct_answer[]" value="{{$key}}" {{$option->is_correct_answer == 'yes' ? 'checked' : ''}} id="correct_ans{{$key}}">
                                                    <label class="form-check-label mb-0" for="correct_ans{{$key}}">
                                                        {{__('Correct Answer')}}
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach

                        </div>

                        <div class="question-openion-btn-wrap d-flex justify-content-between align-items-start mb-20">

                            <div class="add-question-save-btns">

                                <a href="{{route('exam.view', [$exam->uuid])}}" class="theme-btn theme-button3 show-last-phase-back-btn">{{__('Cancel')}}</a>
                                <input type="submit" class="theme-btn theme-button1" value="{{__('Save')}}" name="save">

                            </div>
                        </div>

                    </div>

                </form>
            </div>
        </div>
    </div>
@endsection


@push('script')
    <script src="{{asset('frontend/assets/js/custom/form-validation.js')}}"></script>
@endpush
