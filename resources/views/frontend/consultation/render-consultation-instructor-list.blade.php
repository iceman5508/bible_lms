@forelse($consultationInstructors as $consultationInstructor)
    <!-- consultation-instructor-item start -->
    <div class="col-sm-6 col-md-6 col-lg-6 col-xl-4 mb-4">
        <x-frontend.instructor :user="$consultationInstructor" :type=INSTRUCTOR_CARD_TYPE_TWO />
    </div>
    <!-- consultation-instructor-item end -->
@empty
    <div class="no-course-found text-center">
        <img src="{{ asset('frontend/assets/img/empty-data-img.png') }}" alt="img" class="img-fluid">
        <h5 class="mt-3">{{ __('No Record Found') }}</h5>
    </div>
@endforelse

<!-- Pagination Start -->
<div class="col-12">
    @if(@$consultationInstructors->hasPages())
        {{ @$consultationInstructors->links('frontend.paginate.paginate') }}
    @endif
</div>
<!-- Pagination End -->
