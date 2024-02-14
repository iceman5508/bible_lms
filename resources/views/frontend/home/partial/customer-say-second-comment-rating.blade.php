<ul class="rating-list d-flex align-items-center me-2">
    @if(get_option('customer_say_second_comment_rating_star') == 1)
        <li class="star-full"><span class="iconify" data-icon="bi:star-fill"></span></li>
        <li><span class="iconify" data-icon="bi:star-fill"></span></li>
        <li><span class="iconify" data-icon="bi:star-fill"></span></li>
        <li><span class="iconify" data-icon="bi:star-fill"></span></li>
        <li><span class="iconify" data-icon="bi:star-fill"></span></li>
    @elseif(get_option('customer_say_second_comment_rating_star') > 1 && get_option('customer_say_second_comment_rating_star') < 2)
        <li class="star-full"><span class="iconify" data-icon="bi:star-fill"></span></li>
        <li class="star-full"><span class="iconify" data-icon="bi:star-half"></span></li>
        <li><span class="iconify" data-icon="bi:star-fill"></span></li>
        <li><span class="iconify" data-icon="bi:star-fill"></span></li>
        <li><span class="iconify" data-icon="bi:star-fill"></span></li>
    @elseif(get_option('customer_say_second_comment_rating_star') == 2)
        <li class="star-full"><span class="iconify" data-icon="bi:star-fill"></span></li>
        <li class="star-full"><span class="iconify" data-icon="bi:star-fill"></span></li>
        <li><span class="iconify" data-icon="bi:star-fill"></span></li>
        <li><span class="iconify" data-icon="bi:star-fill"></span></li>
        <li><span class="iconify" data-icon="bi:star-fill"></span></li>
    @elseif(get_option('customer_say_second_comment_rating_star') > 2 && get_option('customer_say_second_comment_rating_star') < 3)
        <li class="star-full"><span class="iconify" data-icon="bi:star-fill"></span></li>
        <li class="star-full"><span class="iconify" data-icon="bi:star-fill"></span></li>
        <li class="star-full"><span class="iconify" data-icon="bi:star-half"></span></li>
        <li><span class="iconify" data-icon="bi:star-fill"></span></li>
        <li><span class="iconify" data-icon="bi:star-fill"></span></li>
    @elseif(get_option('customer_say_second_comment_rating_star') == 3)
        <li class="star-full"><span class="iconify" data-icon="bi:star-fill"></span></li>
        <li class="star-full"><span class="iconify" data-icon="bi:star-fill"></span></li>
        <li class="star-full"><span class="iconify" data-icon="bi:star-fill"></span></li>
        <li><span class="iconify" data-icon="bi:star-fill"></span></li>
        <li><span class="iconify" data-icon="bi:star-fill"></span></li>
    @elseif(get_option('customer_say_second_comment_rating_star') > 3 && get_option('customer_say_second_comment_rating_star') < 4)
        <li class="star-full"><span class="iconify" data-icon="bi:star-fill"></span></li>
        <li class="star-full"><span class="iconify" data-icon="bi:star-fill"></span></li>
        <li class="star-full"><span class="iconify" data-icon="bi:star-fill"></span></li>
        <li class="star-full"><span class="iconify" data-icon="bi:star-half"></span></li>
        <li><span class="iconify" data-icon="bi:star-fill"></span></li>

    @elseif(get_option('customer_say_second_comment_rating_star') == 4)
        <li class="star-full"><span class="iconify" data-icon="bi:star-fill"></span></li>
        <li class="star-full"><span class="iconify" data-icon="bi:star-fill"></span></li>
        <li class="star-full"><span class="iconify" data-icon="bi:star-fill"></span></li>
        <li class="star-full"><span class="iconify" data-icon="bi:star-fill"></span></li>
        <li><span class="iconify" data-icon="bi:star-fill"></span></li>
    @elseif(get_option('customer_say_second_comment_rating_star') > 4 && get_option('customer_say_second_comment_rating_star') < 5)
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
