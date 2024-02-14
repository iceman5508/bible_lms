<!--Star:: Rating --->
<div class="course-item-bottom">
    <div class="course-rating d-flex align-items-center">
        <span class="font-medium font-14 me-2">
         <?php
         $average_rating = getUserAverageRating(@$cart->consultationSlot->user->id);
         ?>
            {{ number_format(@$average_rating, 1) }}
        </span>
        <ul class="rating-list d-flex align-items-center me-2">
            @if($average_rating >= 1)
                <li class="star-full"><span class="iconify" data-icon="bi:star-fill"></span></li>
                @if($average_rating > 1 && $average_rating < 2)
                    <li class="star-full"><span class="iconify" data-icon="bi:star-half"></span></li>
                    <li class=""><span class="iconify" data-icon="bi:star-fill"></span></li>
                    <li class=""><span class="iconify" data-icon="bi:star-fill"></span></li>
                    <li class=""><span class="iconify" data-icon="bi:star-fill"></span></li>
                @elseif($average_rating >= 2)
                    <li class="star-full"><span class="iconify" data-icon="bi:star-fill"></span></li>
                    @if($average_rating > 2 && $average_rating < 3)
                        <li class="star-full"><span class="iconify" data-icon="bi:star-half"></span></li>
                        <li class=""><span class="iconify" data-icon="bi:star-fill"></span></li>
                        <li class=""><span class="iconify" data-icon="bi:star-fill"></span></li>
                    @elseif($average_rating >= 3)
                        <li class="star-full"><span class="iconify" data-icon="bi:star-fill"></span></li>
                        @if($average_rating > 3 && $average_rating < 4)
                            <li class="star-full"><span class="iconify" data-icon="bi:star-half"></span></li>
                            <li class=""><span class="iconify" data-icon="bi:star-fill"></span></li>
                        @elseif($average_rating >= 4)
                            <li class="star-full"><span class="iconify" data-icon="bi:star-fill"></span></li>
                            @if($average_rating > 4 && $average_rating < 5)
                                <li class="star-full"><span class="iconify" data-icon="bi:star-half"></span></li>
                            @elseif($average_rating >= 5)
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
        <span class="rating-count font-14">({{ getInstructorTotalReview(@$cart->consultationSlot->user->id) }})</span>
    </div>
</div>
<!--End:: Rating --->
