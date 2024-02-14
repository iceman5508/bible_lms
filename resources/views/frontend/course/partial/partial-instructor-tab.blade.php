<div class="tab-pane fade" id="Instructor" role="tabpanel" aria-labelledby="Instructor-tab">
    <div class="row">
        <h6 class="mb-4 col-12">{{ __('Meet Your Instructor') }}</h6>

        <div class="meet-instructor-item-wrap">
            @foreach ($course->course_instructors->where('status', STATUS_APPROVED) as $course_instructor)

            <div class="col-12">
                <div class="meet-instructor-item theme-border mb-25 pb-20 radius-8">
                    @php
                        if($course_instructor->user->role == USER_ROLE_INSTRUCTOR){
                            $userType = 'instructor';
                        }
                        else{
                            $userType = 'organization';
                        }
                    @endphp

                    <div class="meet-instructor-top-title mb-25 p-20 bg-light border-bottom radius-8">
                        <h6 class="font-18"><a href="{{ route('userProfile',$course_instructor->user->id) }}">{{ @$course_instructor->user->name }}</a></h6>
                    </div>
                    <div class="meet-instructor-top-part row px-20">
                        <div class="col-md-7 col-lg-12 col-xl-7">
                            <div class="meet-your-instructor-left d-flex">
                                <div class="meet-instructor-img-wrap flex-shrink-0">
                                <a href="{{ route('userProfile',$course_instructor->user->id) }}">
                                    <img src="{{ getImageFile(@$course_instructor->user->image_path) }}" alt="img">
                                </a>
                                </div>
                                <div class="flex-grow-1">
                                    {{-- <p class="font-medium color-heading mb-1">{{ @$course_instructor->user->name }}</p> --}}
                                    <p class="font-12 mb-2"><a href="{{ route('userProfile',$course_instructor->user->id) }}">{{ @$course_instructor->user->professional_title }}</a></p>
                                    <div class="teacher-tag color-hover bg-light-purple font-medium font-14 radius-4">{{
                                        __('Instructor') }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-5 col-lg-12 col-xl-5">
                            <div class="meet-your-instructor-right">
                                <div class="d-flex">
                                    <div>
                                        @php
                                            $total_instructor_course =  count($course_instructor->user->$userType->courses);
                                            $total_instructor_students = $course_instructor->user->$userType->enrollments->count();
                                        @endphp
                                        <div class="meet-instructor-extra-info-item color-heading"><span class="iconify"
                                                data-icon="bi:star"></span>{{ number_format(getUserAverageRating($course_instructor->instructor_id), 1) }} {{
                                            __('Rating') }}</div>
                                        <div class="meet-instructor-extra-info-item color-heading"><span class="iconify"
                                                data-icon="ph:student"></span>{{ $total_instructor_students }} {{ __('Students') }}
                                        </div>
                                    </div>
                                    <div>
                                        <div class="meet-instructor-extra-info-item color-heading"><span class="iconify"
                                                data-icon="cil:badge"></span>
                                            {{ get_instructor_ranking_level(@$course_instructor->user->badges) }}
                                        </div>
                                        <div class="meet-instructor-extra-info-item color-heading"><span class="iconify"
                                                data-icon="ph:monitor-light"></span>{{ $total_instructor_course }} {{ __('Courses') }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="meet-your-instructor-content-part px-20">
                        <h6 class="font-16">{{ __('About Instructor') }}</h6>
                        <p>{{ @$course_instructor->user->$userType->about_me }}</p>

                    </div>
                </div>
            </div>

            @endforeach
        </div>

    </div>
</div>
