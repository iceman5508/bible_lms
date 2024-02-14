@extends('layouts.instructor')

@section('breadcrumb')
    <div class="page-banner-content text-center">
        <h3 class="page-banner-heading text-white pb-15"> {{ __('Assignment') }} </h3>

        <!-- Breadcrumb Start-->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb justify-content-center">
                <li class="breadcrumb-item font-14"><a href="{{route('instructor.dashboard')}}">{{__('Dashboard')}}</a></li>
                <li class="breadcrumb-item font-14"><a href="{{route('instructor.course')}}">{{__('My Courses')}}</a></li>
                <li class="breadcrumb-item font-14"><a href="{{route('assignment.index', $course->uuid)}}">{{ __('Assignment') }}</a></li>
                <li class="breadcrumb-item font-14 active" aria-current="page">{{ __('Create Assignment') }}</li>
            </ol>
        </nav>
    </div>
@endsection

@section('content')
    <div class="instructor-profile-right-part">
        <div class="instructor-create-new-quiz-page instructor-create-assignment-page bg-white">
            <div class="instructor-my-courses-title d-flex justify-content-between align-items-center">
                <h6>{{ __('Create New Assignment') }}</h6>
                <p>{{ $course->title }}</p>
            </div>
            <div class="row">
                <div class="col-12">
                    <form class="create-new-quiz-form" action="{{ route('assignment.store', [$course->uuid]) }}" method="post"  enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-12 mb-30">
                                <label class="label-text-title color-heading font-medium font-16 mb-3">{{ __('Assignment Topic') }}</label>
                                <input type="text" class="form-control" name="name" placeholder="{{ __('Enter your assignment topic') }}" value="{{ old('name') }}" required>
                                @if ($errors->has('name'))
                                    <span class="text-danger"><i class="fas fa-exclamation-triangle"></i> {{ $errors->first('name') }}</span>
                                @endif
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12 mb-30">
                                <label class="label-text-title color-heading font-medium font-16 mb-3">{{ __('Assignment Marks') }}</label>
                                <input type="number" class="form-control" name="marks" placeholder="{{ __('Enter your Assignment Marks') }}" value="{{ old('marks') }}" required>
                                @if ($errors->has('marks'))
                                    <span class="text-danger"><i class="fas fa-exclamation-triangle"></i> {{ $errors->first('marks') }}</span>
                                @endif
                            </div>
                        </div>

                        <div class="row mb-30">
                            <div class="col-md-12">
                                <div class="label-text-title color-heading font-medium font-16 mb-3">{{ __('Assignment Details') }}</div>
                                <textarea class="form-control" name="description" cols="30" rows="10" placeholder="{{ __('Enter your assignment details') }}">{{ old('description') }}</textarea>
                                @if ($errors->has('description'))
                                    <span class="text-danger"><i class="fas fa-exclamation-triangle"></i> {{ $errors->first('description') }}</span>
                                @endif
                            </div>
                        </div>

                        <div class="row mb-30">
                            <div class="col-md-12">
                                <div class="create-assignment-upload-files">
                                    <div>
                                        <input type="file" name="file" class="form-control" title="Upload Your Files" />
                                    </div>
                                    <p class="font-14 color-heading text-center mt-2 color-gray">{{ __('Accepted files') }} (PDF or ZIP) </p>
                                </div>
                            </div>
                        </div>
                        @if ($errors->has('file'))
                            <span class="text-danger"><i class="fas fa-exclamation-triangle"></i> {{ $errors->first('file') }}</span>
                        @endif
                        <div>
                            <button class="theme-btn theme-button3 quiz-back-btn">{{ __('Back') }}</button>
                            <button type="submit" class="theme-btn theme-button1">{{ __('Create') }}</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

