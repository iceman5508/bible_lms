<div class="card instructor-item search-instructor-item position-relative text-center border-0 {{ $type == INSTRUCTOR_CARD_TYPE_THREE ? 'p-20' : 'p-30' }} px-3">
    @php
    $percent = $user->hourly_rate && $user->hourly_old_rate ? (($user->hourly_old_rate -
    $user->hourly_rate) * 100) / $user->hourly_old_rate : 0;
    @endphp
    @if ($percent && $user->consultation_available == 1)
    <span class="instructor-price-cutoff badge radius-3 font-12 font-medium position-absolute bg-orange">{{
        round(@$percent) }}%
        {{ __('off') }}</span>
    @endif
    <div class="search-instructor-img-wrap mb-15"><a
            href="{{ route('userProfile', $user->id) }}">
            <img src="{{ getImageFile(@$user->image_path) }}" alt="instructor"
                class="fit-image rounded-circle"></a>
    </div>
    <div class="card-body p-0">
        <h6 class="card-title"><a href="{{ route('userProfile', $user->id) }}">{{
                $user->name }}</a>
        </h6>
        <p class="card-text instructor-designation font-medium mb-15">
            {{ @$user->professional_title }}
            @if(get_instructor_ranking_level($user->badges))<span class="mx-2">||</span>{{ get_instructor_ranking_level($user->badges) }}</p>@endif

        <?php
        $average_rating = $user->courses->where('average_rating', '>', 0)->avg('average_rating');
        ?>
        <div
            class="course-rating search-instructor-rating w-100 mb-15 d-inline-flex align-items-center justify-content-center">
            <span class="font-medium font-14 me-2">{{ number_format(@$average_rating, 1) }}</span>
            <div class="star-ratings">
                <div class="fill-ratings" style="width: {{ $average_rating * 20 }}%">
                    <span>★★★★★</span>
                </div>
                <div class="empty-ratings">
                    <span>★★★★★</span>
                </div>
            </div>
            <span class="rating-count font-14 ms-2">({{ count(@$user->courses->where('average_rating', '>', 0))
                }})</span>
        </div>
        <div class="search-instructor-bottom-item font-14 font-medium">
            <div class="search-instructor-award-img d-inline-flex flex-wrap justify-content-center">
                @foreach ($user->badges as $badge)
                <img src="{{ asset($badge->badge_image) }}" title="{{ $badge->name }}" alt="{{ $badge->name }}"
                    class="fit-image rounded-circle">
                @endforeach
            </div>
        </div>

        <div class="search-instructor-price d-inline-flex align-items-center mb-15">
            @if ($user->consultation_available == 1)
                @if ($user->hourly_rate < $user->hourly_old_rate)
                <div class="search-instructor-new-price font-medium mx-1">
                    {{ $user->hourly_rate }}/{{ __('Hour') }}
                </div>
                <div
                    class="search-instructor-old-price text-decoration-line-through color-gray font-13 font-medium mx-1">
                    {{ $user->hourly_old_rate }}</div>
                @else
                <div class="search-instructor-new-price font-medium mx-1">
                    {{ $user->hourly_rate }}/{{ __('Hour') }}
                </div>
                <div
                    class="search-instructor-old-price text-decoration-line-through color-gray font-13 font-medium mx-1">
                </div>
                @endif
            @else
            <div class="search-instructor-new-price font-medium mx-1"></div>
            <div
                class="search-instructor-old-price text-decoration-line-through color-gray font-13 font-medium mx-1">
            </div>
            @endif
        </div>
        <div class="w-100">
            @if($type == INSTRUCTOR_CARD_TYPE_ONE || $type == INSTRUCTOR_CARD_TYPE_THREE)
            <a {{ $type == INSTRUCTOR_CARD_TYPE_THREE ? 'target=_blank' : '' }} href="{{ route('userProfile', $user->id) }}"
                class="green-theme-btn theme-button1 w-100">{{ __('View Profile') }}</a>
            @elseif($type == INSTRUCTOR_CARD_TYPE_TWO)
            @php $hourly_fee = 0; @endphp
            @if($currencyPlacement ?? get_currency_placement() == 'after')
                @php $hourly_fee = $user->hourly_rate . ' ' . $currencySymbol ?? get_currency_symbol() . '/h'; @endphp
            @else
                @php $hourly_fee = $currencySymbol ?? get_currency_symbol() . ' ' . $user->hourly_rate . '/h'; @endphp
            @endif
            <button type="button" data-type="{{ $user->available_type }}"
                data-booking_instructor_user_id="{{ $user->id }}" data-hourly_fee="{{ $hourly_fee }}"
                data-hourly_rate="{{ $user->hourly_rate }}"
                data-get_off_days_route="{{ route('getOffDays', $user->id) }}"
                class="theme-btn theme-button1 theme-button3 w-100 bookSchedule" data-bs-toggle="modal"
                data-bs-target="#consultationBookingModal">{{ __('Book Schedule') }}
            </button>
            @endif
        </div>
    </div>
</div>
