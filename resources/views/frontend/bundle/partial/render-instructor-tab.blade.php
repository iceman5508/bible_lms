@php
    if($bundle->user->role == USER_ROLE_INSTRUCTOR){
        $userType = 'instructor';
    }
    else{
        $userType = 'organization';
    }
@endphp
<div class="tab-pane fade" id="Instructor" role="tabpanel" aria-labelledby="Instructor-tab">
    <div class="row">
        <h6 class="mb-4 col-12">{{ __('Meet Your Instructor') }}</h6>
        <div class="col-md-7 col-lg-12 col-xl-7 p-0">
            <div class="meet-your-instructor-left d-flex">
                <div class="meet-instructor-img-wrap flex-shrink-0">
                    <img src="{{ getImageFile(@$bundle->user->image_path) }}" alt="img">
                </div>
                <div class="flex-grow-1">
                    <p class="font-medium color-heading mb-1">{{ __(@$bundle->user->$userType->name) }}</p>
                    <p class="font-12 mb-2">{{ __(@$bundle->user->$userType->professional_title) }}</p>
                    <div class="teacher-tag color-hover bg-light-purple font-medium font-14 radius-4">{{ __('Instructor') }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-5 col-lg-12 col-xl-5 p-0">
            <div class="meet-your-instructor-right">
                <div class="d-flex">
                    <div>
                        <div class="meet-instructor-extra-info-item color-heading"><span class="iconify" data-icon="bi:star"></span>{{ number_format(@$instructor_average_rating, 1) }} {{ __('Rating') }}</div>
                        <div class="meet-instructor-extra-info-item color-heading"><span class="iconify" data-icon="ph:student"></span> {{ @$total_students }} {{ __('Students') }}</div>
                    </div>
                    <div>
                        <div class="meet-instructor-extra-info-item color-heading"><span class="iconify" data-icon="cil:badge"></span>{{ get_instructor_ranking_level(@$bundle->user->badges) }}
                        </div>
                        <div class="meet-instructor-extra-info-item color-heading"><span class="iconify" data-icon="ph:monitor-light"></span>{{ @$bundle->user->$userType->courses->count() }} {{ __('Courses') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 meet-your-instructor-content-part">
            <h6 class="font-16">{{ __('About Instructor') }}</h6>
            <p>{{ __(@$bundle->user->$userType->about_me) }}</p>
        </div>
    </div>
</div>
