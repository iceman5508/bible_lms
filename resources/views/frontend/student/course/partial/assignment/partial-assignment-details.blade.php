<div class="course-watch-assignment-details-wrap ">
    <div class="course-watch-assignment-content ">

        <div class="watch-course-tab-inside-top-heading d-flex justify-content-between align-items-center">
            <h6>{{ __('Assignment Details') }}</h6>
        </div>

        <div class="row">
            <div class="col-md-12 col-lg-12 col-xl-12 col-xxl-7">
                <div class="course-watch-assignment-content-left">

                    <div class="course-watch-assignment-text mb-4">
                        <p class="font-18 color-heading font-medium mb-3">{{ __('Assignment Topic') }}</p>
                        <p class="color-gray">{{ @$assignment->name }}</p>
                    </div>

                    <div class="course-watch-assignment-text mb-4">
                        <p class="font-18 color-heading font-medium mb-3">{{ __('Assignment Description') }}</p>
                        <p class="color-gray">{{ @$assignment->description }}
                        </p>
                    </div>

                </div>
            </div>
            <div class="col-md-12 col-lg-12 col-xl-12 col-xxl-5">
                <div class="course-watch-assignment-content-right mb-30">
                    <div class="course-watch-assignment-top-text">
                        <div class="row">
                            <div class="col-6">
                                <h6 class="font-20 font-medium">{{ __('Marks') }}</h6>
                                <p class="color-gray">{{ @$assignment->marks }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="course-watch-assignment-files">
                        <h6 class="font-20 font-medium">{{ __('Assignment File') }}</h6>
                        <div class="resource-list-text">
                            <span class="iconify" data-icon="akar-icons:link-chain"></span>
                            <a href="{{ getVideoFile(@$assignment->file) }}" class="text-decoration-underline font-medium">{{ @$assignment->original_filename }}</a>
                        </div>

                    </div>

                    <div class="course-watch-assignment-files">
                        <h6 class="font-20 font-medium">{{ __('Your Submit File') }}</h6>
                        <div class="resource-list-text">
                            <span class="iconify" data-icon="akar-icons:link-chain"></span>
                            <a href="{{ getVideoFile(@$assignmentSubmit->file) }}" class="text-decoration-underline font-medium">{{ @$assignmentSubmit->original_filename }}</a>
                        </div>

                    </div>
                </div>
                <div class="col-12">
                    <button class="theme-btn default-back-btn default-hover-btn viewAssignmentList">{{ __('Back') }}</button>
                    <button class="theme-btn theme-button1 default-hover-btn viewAssignmentSubmit"
                            data-route="{{ route('student.assignment-submit') }}" data-assignment_id="{{ $assignment->id }}">
                        {{ __('Submit Assignment') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
