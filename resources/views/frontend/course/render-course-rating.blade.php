@if($course->average_rating >= 1)
    <li class="star-full"><span class="iconify" data-icon="bi:star-fill"></span></li>
    @if($course->average_rating > 1 && $course->average_rating < 2)
        <li class="star-full"><span class="iconify" data-icon="bi:star-half"></span></li>
        <li class=""><span class="iconify" data-icon="bi:star-fill"></span></li>
        <li class=""><span class="iconify" data-icon="bi:star-fill"></span></li>
        <li class=""><span class="iconify" data-icon="bi:star-fill"></span></li>
    @elseif($course->average_rating >= 2)
        <li class="star-full"><span class="iconify" data-icon="bi:star-fill"></span></li>
        @if($course->average_rating > 2 && $course->average_rating < 3)
            <li class="star-full"><span class="iconify" data-icon="bi:star-half"></span></li>
            <li class=""><span class="iconify" data-icon="bi:star-fill"></span></li>
            <li class=""><span class="iconify" data-icon="bi:star-fill"></span></li>
        @elseif($course->average_rating >= 3)
            <li class="star-full"><span class="iconify" data-icon="bi:star-fill"></span></li>
            @if($course->average_rating > 3 && $course->average_rating < 4)
                <li class="star-full"><span class="iconify" data-icon="bi:star-half"></span></li>
                <li class=""><span class="iconify" data-icon="bi:star-fill"></span></li>
            @elseif($course->average_rating >= 4)
                <li class="star-full"><span class="iconify" data-icon="bi:star-fill"></span></li>
                @if($course->average_rating > 4 && $course->average_rating < 5)
                    <li class="star-full"><span class="iconify" data-icon="bi:star-half"></span></li>
                @elseif($course->average_rating >= 5)
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
