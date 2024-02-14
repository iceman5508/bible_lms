@extends('layouts.organization')

@section('breadcrumb')
    <div class="page-banner-content text-center">
        <h3 class="page-banner-heading text-white pb-15"> {{__('My Courses')}} </h3>

        <!-- Breadcrumb Start-->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb justify-content-center">
                <li class="breadcrumb-item font-14"><a href="{{route('organization.dashboard')}}">{{__('Dashboard')}}</a></li>
                <li class="breadcrumb-item font-14" aria-current="page"><a href="{{ route('organization.course.index') }}">{{__('My Courses')}}</a></li>
                <li class="breadcrumb-item font-14"><a href="{{ route('organization.exam.index', $exam->course->uuid) }}">{{ __('Quiz List') }}</a></li>
                <li class="breadcrumb-item font-14 active" aria-current="page">{{ __('True False') }}</li>
            </ol>
        </nav>
    </div>
@endsection

@section('content')
    <div class="instructor-profile-right-part">
        <div class="instructor-quiz-list-page instructor-quiz-details-page">

            <div class="instructor-my-courses-title d-flex justify-content-between align-items-center">
                <h6>{{$exam->name}}</h6>
                <p>{{ @$exam->course->title }}</p>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                            <tr>
                                <th scope="col">{{ __('Question') }}</th>
                                <th scope="col">{{ __('True') }}</th>
                                <th scope="col">{{ __('False') }}</th>
                                <th scope="col">{{ __('Action') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if($exam->questions->count() > 0)
                                @foreach($exam->questions as $key => $question)
                                <tr>
                                    <td>{{\Illuminate\Support\Str::words($question->name, 1)}}</td>
                                    @foreach($question->options as $key_option => $option)
                                        <td>
                                            @if($option->is_correct_answer == 'yes') <span class="iconify" data-icon="bi:check-lg"></span> @endif {{$option->name}} </td>
                                    @endforeach
                                    <td>
                                        <div class="quiz-details-action-btns">
                                            <a href="{{route('organization.exam.edit-true-false', [$question->uuid])}}"  class="quiz-details-btn"><span class="iconify" data-icon="bxs:edit"></span></a>
                                            <a href="javascript:void(0);" data-url="{{route('organization.exam.delete-question', [$question->uuid])}}" class="quiz-details-btn delete"><span class="iconify" data-icon="gg:trash"></span></a>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            @endif
                            </tbody>
                        </table>
                    </div>

                    <a href="{{route('organization.exam.index', [$exam->course->uuid])}}" class="theme-btn theme-button3 quiz-back-btn default-hover-btn">{{ __('Back to Quiz') }}</a>
                </div>
            </div>

        </div>
    </div>
@endsection

@push('style')

@endpush

@push('script')
    <script src="{{asset('frontend/assets/js/custom/form-validation.js')}}"></script>
@endpush
