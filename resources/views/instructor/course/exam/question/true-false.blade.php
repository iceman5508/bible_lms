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
                <li class="breadcrumb-item font-14 active" aria-current="page">{{ __('True False') }}</li>
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
                <a href="javascript:void (0);" data-bs-toggle="modal" data-bs-target="#bulkUpload"  class="theme-btn theme-button1 add-question-form-btn">{{ __('Bulk Upload') }}</a>
            </div>
            <div class="row">


                <form action="{{route('exam.save-true-false-question', [$exam->uuid])}}" method="post" class="add-question-form add-true-false-question-form needs-validation" novalidate>
                    @csrf

                    <div class="upload-course-item-block radius-8">

                        <div class="row">
                            <div class="col-md-12 mb-30">
                                <label class="label-text-title color-heading font-medium font-16 mb-3">{{ __('Question') }} {{$exam->questions->count() + 1}} </label>
                                <input type="text" name="name" required class="form-control" placeholder="{{ __('Enter your question') }}">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12 mb-30">
                                <div class="true-false-item-wrap d-flex align-items-center">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio"  name="is_correct_answer" value="1" required id="correct_ans1">
                                        <label class="form-check-label mb-0 color-heading" for="correct_ans1">
                                            {{__('True')}}
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio"  name="is_correct_answer" value="0" required id="correct_ans2">
                                        <label class="form-check-label mb-0 color-heading" for="correct_ans2">
                                            {{__('False')}}
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="question-openion-btn-wrap d-flex justify-content-between align-items-start mb-20">
                            <div class="add-question-save-btns">
                                <input type="submit" class="theme-btn theme-button1 mr-30" value="{{__('Save and another')}}" name="save_and_add">
                                <input type="submit" class="theme-btn theme-button1" value="{{__('Save')}}" name="save">
                            </div>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
@endsection

@section('modal')
    <!-- start balk upload modal-->
    <div class="modal fade" id="bulkUpload" tabindex="-1" aria-labelledby="bulkUpload" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="bulkUpload">{{__('Bulk Upload')}}</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="{{route('exam.bulk-upload-true-false', [$exam->uuid])}}" class="needs-validation" novalidate enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="create-assignment-upload-files">
                                    <div>
                                        <input type="file" name="question_file" accept=".csv" required class="form-control" title="Attach CSV File" />
                                    </div>
                                    <p class="font-14 color-heading text-center mt-2 color-gray"><a href="{{url('bulk_upload/true-false-question.csv')}}" download=""> <i class="fa fa-download"></i> {{ __('View Sample File') }}</a> </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer d-flex justify-content-center align-items-center">
                        <button type="submit" class="theme-btn theme-button1 default-hover-btn">{{__('Upload')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- end balk upload modal-->
@endsection

@push('script')
    <script src="{{asset('frontend/assets/js/custom/form-validation.js')}}"></script>
@endpush

