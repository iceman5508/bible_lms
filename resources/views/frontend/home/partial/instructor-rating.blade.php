<ul class="rating-list d-flex align-items-center me-2">
    @if($average_rating == 0)
        <li><span class="iconify" data-icon="bi:star-fill"></span></li>
        <li><span class="iconify" data-icon="bi:star-fill"></span></li>
        <li><span class="iconify" data-icon="bi:star-fill"></span></li>
        <li><span class="iconify" data-icon="bi:star-fill"></span></li>
        <li><span class="iconify" data-icon="bi:star-fill"></span></li>
    @elseif($average_rating == 1)
        <li class="star-full"><span class="iconify" data-icon="bi:star-fill"></span></li>
        <li><span class="iconify" data-icon="bi:star-fill"></span></li>
        <li><span class="iconify" data-icon="bi:star-fill"></span></li>
        <li><span class="iconify" data-icon="bi:star-fill"></span></li>
        <li><span class="iconify" data-icon="bi:star-fill"></span></li>
    @elseif($average_rating > 1 && $average_rating < 2)
        <li class="star-full"><span class="iconify" data-icon="bi:star-fill"></span></li>
        <li class="star-full"><span class="iconify" data-icon="bi:star-half"></span></li>
        <li><span class="iconify" data-icon="bi:star-fill"></span></li>
        <li><span class="iconify" data-icon="bi:star-fill"></span></li>
        <li><span class="iconify" data-icon="bi:star-fill"></span></li>
    @elseif($average_rating == 2)
        <li class="star-full"><span class="iconify" data-icon="bi:star-fill"></span></li>
        <li class="star-full"><span class="iconify" data-icon="bi:star-fill"></span></li>
        <li><span class="iconify" data-icon="bi:star-fill"></span></li>
        <li><span class="iconify" data-icon="bi:star-fill"></span></li>
        <li><span class="iconify" data-icon="bi:star-fill"></span></li>
    @elseif($average_rating > 2 && $average_rating < 3)
        <li class="star-full"><span class="iconify" data-icon="bi:star-fill"></span></li>
        <li class="star-full"><span class="iconify" data-icon="bi:star-fill"></span></li>
        <li class="star-full"><span class="iconify" data-icon="bi:star-half"></span></li>
        <li><span class="iconify" data-icon="bi:star-fill"></span></li>
        <li><span class="iconify" data-icon="bi:star-fill"></span></li>
    @elseif($average_rating == 3)
        <li class="star-full"><span class="iconify" data-icon="bi:star-fill"></span></li>
        <li class="star-full"><span class="iconify" data-icon="bi:star-fill"></span></li>
        <li class="star-full"><span class="iconify" data-icon="bi:star-fill"></span></li>
        <li><span class="iconify" data-icon="bi:star-fill"></span></li>
        <li><span class="iconify" data-icon="bi:star-fill"></span></li>
    @elseif($average_rating > 3 && $average_rating < 4)
        <li class="star-full"><span class="iconify" data-icon="bi:star-fill"></span></li>
        <li class="star-full"><span class="iconify" data-icon="bi:star-fill"></span></li>
        <li class="star-full"><span class="iconify" data-icon="bi:star-fill"></span></li>
        <li class="star-full"><span class="iconify" data-icon="bi:star-half"></span></li>
        <li><span class="iconify" data-icon="bi:star-fill"></span></li>

    @elseif($average_rating == 4)
        <li class="star-full"><span class="iconify" data-icon="bi:star-fill"></span></li>
        <li class="star-full"><span class="iconify" data-icon="bi:star-fill"></span></li>
        <li class="star-full"><span class="iconify" data-icon="bi:star-fill"></span></li>
        <li class="star-full"><span class="iconify" data-icon="bi:star-fill"></span></li>
        <li><span class="iconify" data-icon="bi:star-fill"></span></li>
    @elseif($average_rating > 4 && $average_rating < 5)
        <li class="star-full"><span class="iconify" data-icon="bi:star-fill"></span></li>
        <li class="star-full"><span class="iconify" data-icon="bi:star-fill"></span></li>
        <li class="star-full"><span class="iconify" data-icon="bi:star-fill"></span></li>
        <li class="star-full"><span class="iconify" data-icon="bi:star-fill"></span></li>
        <li class="star-full"><span class="iconify" data-icon="bi:star-half"></span></li>
    @else
        <li class="star-full"><span class="iconify" data-icon="bi:star-fill"></span></li>
        <li class="star-full"><span class="iconify" data-icon="bi:star-fill"></span></li>
        <li class="star-full"><span class="iconify" data-icon="bi:star-fill"></span></li>
        <li class="star-full"><span class="iconify" data-icon="bi:star-fill"></span></li>
        <li class="star-full"><span class="iconify" data-icon="bi:star-fill"></span></li>
    @endif
</ul>
