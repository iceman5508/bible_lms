@foreach ($users as $instructorUser)
    <div class="col-sm-6 col-md-6 col-lg-6 col-xl-4 mt-0 mb-25">
        <x-frontend.instructor :user="$instructorUser" :type=INSTRUCTOR_CARD_TYPE_ONE />
    </div>
@endforeach
