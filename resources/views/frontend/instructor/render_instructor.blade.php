<div class="show-all-course-area show-all-instructors-area">
    <!-- all courses grid Start-->
    <div id="loading" class="no-course-found text-center d-none">
        <div id="inner-status"><img src="{{ asset('frontend/assets/img/loader.svg') }}" alt="img" /></div>
    </div>
    <div class="row courses-grids" id="instructorBlock">
        @forelse($users as $instructorUser)
        <!-- Single Instructor Item start-->
        <div class="col-sm-6 col-md-6 col-lg-6 col-xl-4 mt-0 mb-25">
            <x-frontend.instructor :user="$instructorUser" :type=INSTRUCTOR_CARD_TYPE_ONE />
        </div>
        <!-- Single Instructor Item End-->
        @empty
            <div class="no-course-found text-center">
                <img src="{{ asset('frontend/assets/img/empty-data-img.png') }}" alt="img"
                    class="img-fluid">
                <h5 class="mt-3">{{ __('No Instructor Found') }}</h5>
            </div>
        @endforelse
    </div>

    @if($users->hasPages())
        <!-- Load More Button-->
        <div class="d-block" >
            <button  id="loadMoreBtn" type="button" class="theme-btn theme-button2 load-more-btn">{{ __('Load More') }} <span class="iconify" data-icon="icon-park-outline:loading-one"></span></button>
        </div>
    @endif

</div>
