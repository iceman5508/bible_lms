
@extends('layouts.instructor')

@section('breadcrumb')
    <div class="page-banner-content text-center">
        <h3 class="page-banner-heading text-white pb-15"> {{__('My Courses')}} </h3>

        <!-- Breadcrumb Start-->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb justify-content-center">
                <li class="breadcrumb-item font-14"><a href="{{route('instructor.dashboard')}}">{{__('Dashboard')}}</a></li>
                <li class="breadcrumb-item font-14" aria-current="page"><a href="{{ route('instructor.course') }}">{{__('My Courses')}}</a></li>
                <li class="breadcrumb-item font-14"><a href="{{ route('exam.index', @$exam->course->uuid) }}">{{ __('Quiz List') }}</a></li>
                <li class="breadcrumb-item font-14 active" aria-current="page">{{ __('Update Quiz') }}</li>
            </ol>
        </nav>
    </div>
@endsection

@section('content')
    <div class="instructor-profile-right-part">
        <div class="instructor-create-new-quiz-page bg-white">
            <div class="instructor-my-courses-title d-flex justify-content-between align-items-center">
                <h6>{{__('Edit Quiz')}}</h6>
                <p>{{ @$exam->course->title }}</p>
            </div>
            <div class="row">
                <div class="col-12">

                    <form method="POST" action="{{route('exam.update', [$exam->uuid])}}" class="create-new-quiz-form needs-validation" novalidate>
                        @csrf

                        <div class="row">
                            <div class="col-md-12 mb-30">
                                <label class="label-text-title color-heading font-medium font-16 mb-3">{{__('Quiz Name')}}</label>
                                <input type="text" name="name" value="{{$exam->name}}" class="form-control" placeholder="{{ __('Enter your quiz name') }}" required>
                                @if ($errors->has('name'))
                                    <span class="text-danger"><i class="fas fa-exclamation-triangle"></i> {{ $errors->first('name') }}</span>
                                @endif
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12 mb-30">
                                <label class="label-text-title color-heading font-medium font-16 mb-3">{{__('Quiz Types')}}</label>
                                <select name="type" id="type" class="form-select" required>
                                    <option value="multiple_choice" {{$exam->type == 'multiple_choice' ? 'selected' : '' }} >{{ __('Multiple Choice') }}</option>
                                    <option value="true_false" {{$exam->type == 'true_false' ? 'selected' : '' }} >{{ __('True False') }}</option>
                                </select>
                                @if ($errors->has('type'))
                                    <span class="text-danger"><i class="fas fa-exclamation-triangle"></i> {{ $errors->first('type') }}</span>
                                @endif
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12 mb-30">
                                <label class="label-text-title color-heading font-medium font-16 mb-3">{{__('Marks Per Question')}}</label>
                                <input type="number" min="1" name="marks_per_question" value="{{$exam->marks_per_question}}" class="form-control" placeholder="{{ __('Enter your marks per question') }}" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12 mb-30">
                                <label class="label-text-title color-heading font-medium font-16 mb-3">{{__('Time Duration')}} ({{ __('Minutes') }})</label>
                                <input type="number" min="1" class="form-control" name="duration" value="{{$exam->duration}}" placeholder="{{ __('Enter your time duration') }}" required>
                            </div>
                        </div>

                        <div>
                            <a href="{{route('exam.index', @$exam->course->uuid)}}" class="theme-btn theme-button3 quiz-back-btn">{{__('Back')}}</a>
                            <button type="submit" class="theme-btn theme-button1">{{__('Save')}}</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script src="{{asset('frontend/assets/js/custom/form-validation.js')}}"></script>
@endpush


