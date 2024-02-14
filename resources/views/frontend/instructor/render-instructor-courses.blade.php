@foreach(@$courses as $course)
    @php 
        $userRelation = getUserRoleRelation($course->user);
    @endphp
    <!-- Course item start -->
    <div class="col-12 col-md-6 col-lg-6 col-xl-4">
        @include('frontend.partials.course')
    </div>
    <!-- Course item end -->
@endforeach


