@forelse($courses as $course)
    <!-- Discussion Course Item Start -->
    <div class="message-user-item instructor-discussion-course-item cursor d-flex align-items-center active">
        <div class="message-user-item-left flex-shrink-0">
            <div class="d-flex align-items-center courseId" data-course_id="{{ $course->id }}">
                <div class="flex-shrink-0">
                    <div class="user-img-wrap position-relative">
                        <img src="{{ getImageFile($course->image) }}" alt="img" class="img-fluid">
                    </div>
                </div>
                <div class="flex-grow-1 ms-3 ">
                    <h6 class="font-16 font-medium">{{ Str::limit($course->title, 15) }}</h6>
                    <p class="font-13">{{ Str::limit($course->description, 21) }}</p>
                </div>
            </div>
        </div>
        <div class="message-user-item-right flex-grow-1 justify-content-end ms-3">
            <div class="message-user-notification-box unread-discussion bg-orange mt-2"></div>
        </div>
    </div>
    <!-- Discussion Course Item End -->
@empty
    <div class="empty-data ">
        <h6 class="my-3">{{ __('Empty Course') }}</h6>
    </div>
@endforelse
