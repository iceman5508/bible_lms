<div class="tab-pane fade" id="Review" role="tabpanel" aria-labelledby="Review-tab">
    <div class="review-content">

        <!-- Review Tab Top Part Start-->
        <div class="review-tab-top-part d-flex justify-content-between align-items-center">
            <div class="review-tab-count-box radius-3 text-center">
                <h3 class="">{{ number_format($course->average_rating, 1) }}</h3>
                <ul class="rating-list d-flex align-items-center">
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
                </ul>
                <p>{{ $total_user_review }} {{ __('Reviews') }}</p>
            </div>

            <div class="review-progress-bar-wrap">
                <!-- Progress Bar -->
                <div class="barras">
                    <div class="progress-bar-box d-flex align-items-center">
                        <div class="progress-hint">
                            <div class="progress-star d-flex align-items-center me-2">
                                <div class="hint-number me-3 font-medium color-heading">5</div>
                                <ul class="rating-list d-flex align-items-center">
                                    <li class="star-full"><span class="iconify" data-icon="bi:star-fill"></span></li>
                                    <li class="star-full"><span class="iconify" data-icon="bi:star-fill"></span></li>
                                    <li class="star-full"><span class="iconify" data-icon="bi:star-fill"></span></li>
                                    <li class="star-full"><span class="iconify" data-icon="bi:star-fill"></span></li>
                                    <li class="star-full"><span class="iconify" data-icon="bi:star-fill"></span></li>
                                </ul>
                            </div>
                        </div>
                        <div class="barra">
                            <div class="barra-nivel" data-nivel="{{ number_format($five_star_percentage, 2) }}%"></div>
                        </div>
                        <div class="progress-hint-value font-14 color-heading">{{ $five_star_count }}</div>
                    </div>
                </div>
                <!-- Progress Bar -->
                <div class="barras">
                    <div class="progress-bar-box d-flex align-items-center">
                        <div class="progress-hint">
                            <div class="progress-star d-flex align-items-center me-2">
                                <div class="hint-number me-3 font-medium color-heading">4</div>
                                <ul class="rating-list d-flex align-items-center">
                                    <li class="star-full"><span class="iconify" data-icon="bi:star-fill"></span></li>
                                    <li class="star-full"><span class="iconify" data-icon="bi:star-fill"></span></li>
                                    <li class="star-full"><span class="iconify" data-icon="bi:star-fill"></span></li>
                                    <li class="star-full"><span class="iconify" data-icon="bi:star-fill"></span></li>
                                    <li><span class="iconify" data-icon="bi:star-fill"></span></li>
                                </ul>
                            </div>
                        </div>
                        <div class="barra">
                            <div class="barra-nivel" data-nivel="{{ number_format($four_star_percentage, 2) }}%"></div>
                        </div>
                        <div class="progress-hint-value font-14 color-heading">{{ $four_star_count }}</div>
                    </div>
                </div>
                <!-- Progress Bar -->
                <div class="barras">
                    <div class="progress-bar-box d-flex align-items-center">
                        <div class="progress-hint">
                            <div class="progress-star d-flex align-items-center me-2">
                                <div class="hint-number me-3 font-medium color-heading">3</div>
                                <ul class="rating-list d-flex align-items-center">
                                    <li class="star-full"><span class="iconify" data-icon="bi:star-fill"></span></li>
                                    <li class="star-full"><span class="iconify" data-icon="bi:star-fill"></span></li>
                                    <li class="star-full"><span class="iconify" data-icon="bi:star-fill"></span></li>
                                    <li><span class="iconify" data-icon="bi:star-fill"></span></li>
                                    <li><span class="iconify" data-icon="bi:star-fill"></span></li>
                                </ul>
                            </div>
                        </div>
                        <div class="barra">
                            <div class="barra-nivel" data-nivel="{{ number_format($three_star_percentage, 2) }}%"></div>
                        </div>
                        <div class="progress-hint-value font-14 color-heading">{{ $three_star_count }}</div>
                    </div>
                </div>
                <!-- Progress Bar -->
                <div class="barras">
                    <div class="progress-bar-box d-flex align-items-center">
                        <div class="progress-hint">
                            <div class="progress-star d-flex align-items-center me-2">
                                <div class="hint-number me-3 font-medium color-heading">2</div>
                                <ul class="rating-list d-flex align-items-center">
                                    <li class="star-full"><span class="iconify" data-icon="bi:star-fill"></span></li>
                                    <li class="star-full"><span class="iconify" data-icon="bi:star-fill"></span></li>
                                    <li><span class="iconify" data-icon="bi:star-fill"></span></li>
                                    <li><span class="iconify" data-icon="bi:star-fill"></span></li>
                                    <li><span class="iconify" data-icon="bi:star-fill"></span></li>
                                </ul>
                            </div>
                        </div>
                        <div class="barra">
                            <div class="barra-nivel" data-nivel="{{ number_format($two_star_percentage, 2) }}%"></div>
                        </div>
                        <div class="progress-hint-value font-14 color-heading">{{ $two_star_count }}</div>
                    </div>
                </div>
                <!-- Progress Bar -->
                <div class="barras">
                    <div class="progress-bar-box d-flex align-items-center">
                        <div class="progress-hint">
                            <div class="progress-star d-flex align-items-center me-2">
                                <div class="hint-number me-3 font-medium color-heading">1</div>
                                <ul class="rating-list d-flex align-items-center">
                                    <li class="star-full"><span class="iconify" data-icon="bi:star-fill"></span></li>
                                    <li><span class="iconify" data-icon="bi:star-fill"></span></li>
                                    <li><span class="iconify" data-icon="bi:star-fill"></span></li>
                                    <li><span class="iconify" data-icon="bi:star-fill"></span></li>
                                    <li><span class="iconify" data-icon="bi:star-fill"></span></li>
                                </ul>
                            </div>
                        </div>
                        <div class="barra">
                            <div class="barra-nivel" data-nivel="{{ number_format($first_star_percentage, 2) }}%"></div>
                        </div>
                        <div class="progress-hint-value font-14 color-heading">{{ $first_star_count }}</div>
                    </div>
                </div>

            </div>
        </div>
        <!-- Review Tab Top Part End-->

        <!-- Customer review Part Start-->
        <div class="customer-review-part">

            <div id="appendReviews">
                @include('frontend.student.course.partial.render-partial-review-list')
            </div>

            @if(count($loadMoreShowButtonReviews) > 3)
                <!-- Load More Button-->
                <div class="customer-review-load-more-btn d-block" id="loadMoreBtn">
                    <button class="theme-btn theme-button2 load-more-btn loadMore">{{ __('Load More') }} <span class="iconify" data-icon="icon-park-outline:loading-one"></span></button>
                </div>
            @endif
        </div>
        <!-- Customer review Part End-->

    </div>
</div>
