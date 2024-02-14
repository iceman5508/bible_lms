<div class="row courses-grids" id="appendCourseList">
@forelse($courses as $course)
    <!-- Course item start -->
    @php
        $userRelation = getUserRoleRelation($course->user);
    @endphp
    <div class="col-sm-6 col-md-6 col-lg-6 col-xl-4">
        @include('frontend.partials.course')
    </div>
    <!-- Course item end -->
@empty
    <div class="no-course-found text-center">
        <img src="{{ asset('frontend/assets/img/empty-data-img.png') }}" alt="img" class="img-fluid">
        <h5 class="mt-3">{{ __('Courses Not Found') }}</h5>
    </div>
@endforelse

<!-- Course item end -->
<!-- Pagination End -->
</div>
<!-- Pagination Start -->
<div class="col-12">
    @if($courses->hasPages())
        <!-- Load More Button-->
        <div class="d-block" >
            <button  id="loadMoreBtn" type="button" class="theme-btn theme-button2 load-more-btn">{{ __('Load More') }} <span class="iconify" data-icon="icon-park-outline:loading-one"></span></button>
        </div>
    @endif
</div>