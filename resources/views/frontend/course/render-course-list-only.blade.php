@foreach($courses as $course)
    <!-- Course item start -->
    @php
        $userRelation = getUserRoleRelation($course->user);
    @endphp
    <div class="col-sm-6 col-md-6 col-lg-6 col-xl-4">
        @include('frontend.partials.course')
    </div>
@endforeach
