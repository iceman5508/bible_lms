<div class="tab-pane fade" id="Courses-list" role="tabpanel" aria-labelledby="Courses-list-tab">
    <div class="row courses-bundles-courses-wrap">
@foreach($bundle->bundleCourses ?? [] as $bundleCourse)
    <!-- Course item start -->
    <div class="col-sm-12">
        <div class="card course-item courses-bundles-course-item radius-3 bg-white">
            <div class="course-img-wrap overflow-hidden">
                <a href="{{ route('course-details', @$bundleCourse->course->slug) }}"><img src="{{getImageFile(@$bundleCourse->course->image_path)}}" alt="course" class="img-fluid"></a>
                <div class="course-item-hover-btns position-absolute">
                    <span class="course-item-btn addToWishlist" data-course_id="{{ $bundleCourse->course_id }}" data-route="{{ route('student.addToWishlist') }}" title="{{ __('Add to Wishlist') }}">
                        <i data-feather="heart"></i>
                    </span>
                    <span class="course-item-btn addToCart" data-course_id="{{ $bundleCourse->course_id }}" data-route="{{ route('student.addToCart') }}" title="{{ __('Add to Cart') }}">
                        <i data-feather="shopping-bag"></i>
                    </span>
                </div>
            </div>
            @php 
                $relation = getUserRoleRelation($bundleCourse->course->user);
            @endphp
            <div class="card-body">
                <h5 class="card-title course-title"><a href="{{ route('course-details', @$bundleCourse->course->slug) }}">{{ \Illuminate\Support\Str::limit(__(@$bundleCourse->course->title), 70) }}</a></h5>
                <p class="card-text instructor-name-certificate font-medium font-12">
                    {{ @$bundleCourse->course->$relation->name }}
                    @if(get_instructor_ranking_level(@$bundleCourse->course->user->badges))
                        | {{ get_instructor_ranking_level(@$bundleCourse->course->user->badges) }}
                    @endif
                </p>
                <div class="course-item-bottom">
                    <div class="course-rating d-flex align-items-center">
                        <span class="font-medium font-14 me-2">{{ number_format(@$bundleCourse->course->average_rating, 1) }}</span>
                        <ul class="rating-list d-flex align-items-center me-2">
                            @if(@$bundleCourse->course->average_rating >= 1)
                                <li class="star-full"><span class="iconify" data-icon="bi:star-fill"></span></li>
                                @if(@$bundleCourse->course->average_rating > 1 && @$bundleCourse->course->average_rating < 2)
                                    <li class="star-full"><span class="iconify" data-icon="bi:star-half"></span></li>
                                    <li class=""><span class="iconify" data-icon="bi:star-fill"></span></li>
                                    <li class=""><span class="iconify" data-icon="bi:star-fill"></span></li>
                                    <li class=""><span class="iconify" data-icon="bi:star-fill"></span></li>
                                @elseif(@$bundleCourse->course->average_rating >= 2)
                                    <li class="star-full"><span class="iconify" data-icon="bi:star-fill"></span></li>
                                    @if(@$bundleCourse->course->average_rating > 2 && @$bundleCourse->course->average_rating < 3)
                                        <li class="star-full"><span class="iconify" data-icon="bi:star-half"></span></li>
                                        <li class=""><span class="iconify" data-icon="bi:star-fill"></span></li>
                                        <li class=""><span class="iconify" data-icon="bi:star-fill"></span></li>
                                    @elseif(@$bundleCourse->course->average_rating >= 3)
                                        <li class="star-full"><span class="iconify" data-icon="bi:star-fill"></span></li>
                                        @if(@$bundleCourse->course->average_rating > 3 && @$bundleCourse->course->average_rating < 4)
                                            <li class="star-full"><span class="iconify" data-icon="bi:star-half"></span></li>
                                            <li class=""><span class="iconify" data-icon="bi:star-fill"></span></li>
                                        @elseif(@$bundleCourse->course->average_rating >= 4)
                                            <li class="star-full"><span class="iconify" data-icon="bi:star-fill"></span></li>
                                            @if(@$bundleCourse->course->average_rating > 4 && @$bundleCourse->course->average_rating < 5)
                                                <li class="star-full"><span class="iconify" data-icon="bi:star-half"></span></li>
                                            @elseif(@$bundleCourse->course->average_rating >= 5)
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
                        <span class="rating-count font-14">({{ @$bundleCourse->course->reviews->count() }})</span>
                    </div>
                            @if(@$bundleCourse->course->learner_accessibility == 'paid')
                                    <?php
                                    $startDate = date('d-m-Y H:i:s', strtotime(@$bundleCourse->course->promotionCourse->promotion->start_date));
                                    $endDate = date('d-m-Y H:i:s', strtotime(@$bundleCourse->course->promotionCourse->promotion->end_date));
                                    $percentage = @$bundleCourse->course->promotionCourse->promotion->percentage;
                                    $discount_price = number_format(@$bundleCourse->course->price - ((@$bundleCourse->course->price * $percentage) / 100), 2);
                                    ?>
                                @if(now()->gt($startDate) && now()->lt($endDate))
                                    <div class="instructor-bottom-item font-14 font-semi-bold">
                                {{ __('Price') }}: <span class="color-hover">
                                    @if(get_currency_placement() == 'after')
                                                {{ $discount_price }} {{ get_currency_symbol() }}
                                            @else
                                                {{ get_currency_symbol() }} {{ $discount_price }}
                                            @endif
                                </span>
                                <span class="text-decoration-line-through fw-normal font-14 color-gray ps-3">
                                    @if(get_currency_placement() == 'after')
                                        {{ @$bundleCourse->course->price }} {{ get_currency_symbol() }}
                                    @else
                                        {{ get_currency_symbol() }} {{ @$bundleCourse->course->price }}
                                    @endif
                                </span>
                            </div>
                                @else
                                    <div class="instructor-bottom-item font-14 font-semi-bold">
                                {{ __('Price') }}: <span class="color-hover">
                                @if(get_currency_placement() == 'after')
                                                {{ @$bundleCourse->course->price }} {{ get_currency_symbol() }}
                                            @else
                                                {{ get_currency_symbol() }} {{ @$bundleCourse->course->price }}
                                            @endif
                                </span>
                            </div>
                                @endif
                            @elseif(@$bundleCourse->course->learner_accessibility == 'free')
                                <div class="instructor-bottom-item font-14 font-semi-bold">
                            {{ __('Free') }}
                        </div>
                            @endif
                        </span>
                </div>
            </div>
        </div>
    </div>
    <!-- Course item end -->
@endforeach
    </div>
</div>
