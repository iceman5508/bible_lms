<!--Star:: Rating --->
<div class="course-item-bottom">
    <div class="course-rating d-flex align-items-center">
        <span class="font-medium font-14 me-2">{{ @$cart->course->average_rating }}</span>
        <ul class="rating-list d-flex align-items-center me-2">
            @if(@$cart->course->average_rating >= 1)
                <li class="star-full"><span class="iconify" data-icon="bi:star-fill"></span></li>
                @if(@$cart->course->average_rating > 1 && @$cart->course->average_rating < 2)
                    <li class="star-full"><span class="iconify" data-icon="bi:star-half"></span></li>
                    <li class=""><span class="iconify" data-icon="bi:star-fill"></span></li>
                    <li class=""><span class="iconify" data-icon="bi:star-fill"></span></li>
                    <li class=""><span class="iconify" data-icon="bi:star-fill"></span></li>
                @elseif(@$cart->course->average_rating >= 2)
                    <li class="star-full"><span class="iconify" data-icon="bi:star-fill"></span></li>
                    @if(@$cart->course->average_rating > 2 && @$cart->course->average_rating < 3)
                        <li class="star-full"><span class="iconify" data-icon="bi:star-half"></span></li>
                        <li class=""><span class="iconify" data-icon="bi:star-fill"></span></li>
                        <li class=""><span class="iconify" data-icon="bi:star-fill"></span></li>
                    @elseif(@$cart->course->average_rating >= 3)
                        <li class="star-full"><span class="iconify" data-icon="bi:star-fill"></span></li>
                        @if(@$cart->course->average_rating > 3 && @$cart->course->average_rating < 4)
                            <li class="star-full"><span class="iconify" data-icon="bi:star-half"></span></li>
                            <li class=""><span class="iconify" data-icon="bi:star-fill"></span></li>
                        @elseif(@$cart->course->average_rating >= 4)
                            <li class="star-full"><span class="iconify" data-icon="bi:star-fill"></span></li>
                            @if(@$cart->course->average_rating > 4 && @$cart->course->average_rating < 5)
                                <li class="star-full"><span class="iconify" data-icon="bi:star-half"></span></li>
                            @elseif(@$cart->course->average_rating >= 5)
                                <li class="star-full"><span class="iconify" data-icon="bi:star-fill"></span></li>
                            @else
                                <li class=""><span class="iconify" data-icon="bi:star-fill"></span></li>
                            @endif

                        @else
                            <li class=""><span class="iconify" data-icon="bi:star-fill"></span></li>
                            <li class=""><span class="iconify" data-icon="bi:star-fill"></span></li>
                        @endif
                    @else
                        <li class=""><span class="iconify" data-icon="bi:star-fill"></span></li>
                        <li class=""><span class="iconify" data-icon="bi:star-fill"></span></li>
                        <li class=""><span class="iconify" data-icon="bi:star-fill"></span></li>
                    @endif
                @else
                    <li class=""><span class="iconify" data-icon="bi:star-fill"></span></li>
                    <li class=""><span class="iconify" data-icon="bi:star-fill"></span></li>
                    <li class=""><span class="iconify" data-icon="bi:star-fill"></span></li>
                    <li class=""><span class="iconify" data-icon="bi:star-fill"></span></li>
                @endif
            @else
                <li class=""><span class="iconify" data-icon="bi:star-fill"></span></li>
                <li class=""><span class="iconify" data-icon="bi:star-fill"></span></li>
                <li class=""><span class="iconify" data-icon="bi:star-fill"></span></li>
                <li class=""><span class="iconify" data-icon="bi:star-fill"></span></li>
                <li class=""><span class="iconify" data-icon="bi:star-fill"></span></li>
            @endif

        </ul>
        <span class="rating-count font-14">({{ @$cart->course->reviews->count() }})</span>
    </div>
</div>
<!--End:: Rating --->
