@if(count($enrollments) > 0)
    <div class="table-responsive">
        <table class="table bg-white my-courses-page-table">
            <thead>
            <tr>
                <th scope="col" class="color-gray font-15 font-medium">{{__('Course')}}</th>
                <th scope="col" class="color-gray font-15 font-medium">{{__('Author')}}</th>
                <th scope="col" class="color-gray font-15 font-medium">{{__('Price')}}</th>
                <th scope="col" class="color-gray font-15 font-medium">{{__('Order ID')}}</th>
                <th scope="col" class="color-gray font-15 font-medium">{{__('Validity')}}</th>
                <th scope="col" class="color-gray font-15 font-medium">{{__('Progress')}}</th>
                <th scope="col" class="color-gray font-15 font-medium">{{__('Action')}}</th>
            </tr>
            </thead>
            <tbody>

            @foreach($enrollments as $enrollment)
                @if($enrollment->course_id)
                <tr>
                    <td class="wishlist-course-item">
                        <div class="card course-item wishlist-item border-0 d-flex align-items-center">
                            <div class="course-img-wrap flex-shrink-0 overflow-hidden">
                                <?php
                                $special = @$course->specialPromotionTagCourse->specialPromotionTag->name;
                                ?>
                                @if($special)
                                    <span class="course-tag badge radius-3 font-12 font-medium position-absolute bg-orange">
                                        {{ @$special }}
                                    </span>
                                @endif
                                <a href="{{ route('student.my-course.show', @$enrollment->course->slug) }}"><img src="{{ getImageFile(@$enrollment->course->image_path) }}" alt="course" class="img-fluid"></a>
                            </div>
                            <div class="card-body flex-grow-1">
                                <h5 class="card-title course-title"><a href="{{ route('student.my-course.show', @$enrollment->course->slug) }}">{{ @$enrollment->course->title }}</a></h5>
                                @if(get_option('refund_system_mode', false))
                                    <div class="card-text font-medium font-11 mb-1">
                                        @if($enrollment->unit_price > 0 && $enrollment->user_id == $enrollment->order->user_id)
                                        <button class="color-gray2 me-2 font-medium bg-transparent border-0 my-course-give-a-review-btn my-learning-refund courseRefund" data-amount="{{ $enrollment->unit_price }}" data-id="{{ @$enrollment->id }}"><span class="iconify me-1" data-icon="gridicons:refund"></span>{{ __('Refund') }}</button>
                                        @endif
                                        <button class="color-gray2 me-2 font-medium bg-transparent border-0 my-course-give-a-review-btn star-full my-learning-give-review courseReview" data-course_id="{{ @$enrollment->course->id }}" data-bs-toggle="modal" data-bs-target="#writeReviewModal"><span class="iconify me-1" data-icon="bi:star-fill"></span>{{ __('Give') }}</button>
                                        <a target="_blank" href="{{route('student.download-invoice', [@$enrollment->id])}}" class="color-gray2 me-2 my-learning-invoice"><img src="{{ asset('frontend/assets/img/courses-img/invoice-icon.png') }}" alt="report" class="me-1">{{__('Invoice')}}</a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td class="wishlist-price font-15 color-heading">{{ @$enrollment->course->instructor->name }}</td>
                    <td class="wishlist-price font-15 color-heading">
                        @if($enrollment->unit_price > 0)
                            @if(get_currency_placement() == 'after')
                                {{ $enrollment->unit_price }} {{ get_currency_symbol() }}
                            @else
                                {{ get_currency_symbol() }} {{ $enrollment->unit_price }}
                            @endif

                        @else
                            {{ __('Free') }}
                        @endif
                    </td>
                    <td class="wishlist-price font-15 color-heading">{{@$enrollment->order->order_number}}</td>
                    <td class="font-15 color-heading">{{ (checkIfExpired($enrollment)) ? (checkIfLifetime($enrollment->end_date) ? __('Lifetime') : \Carbon\Carbon::now()->diffInDays($enrollment->end_date, false).' '.__('days left') ) : __('Expired') }}</td>

                    <td class="wishlist-price font-15 color-heading">
                        <div class="review-progress-bar-wrap">
                            <!-- Progress Bar -->
                            <div class="barras">
                                <div class="progress-bar-box">
                                    <div class="progress-hint-value font-14 color-heading">{{number_format(studentCourseProgress(@$enrollment->course->id, @$enrollment->id), 2)}}%</div>
                                    <div class="barra">
                                        <div class="barra-nivel" data-nivel="{{number_format(studentCourseProgress(@$enrollment->course->id, @$enrollment->id), 2)}}%" style="width: {{number_format(studentCourseProgress(@$enrollment->course->id, @$enrollment->id), 2)}}%;"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </td>
                    <td class="wishlist-add-to-cart-btn">
                        @if(checkIfExpired($enrollment))
                        <a href="{{ route('student.my-course.show', @$enrollment->course->slug) }}" class="theme-button theme-button1 theme-button3 font-13">{{ __('View') }}</a>
                        @else
                        <a href="{{ route('course-details', @$enrollment->course->slug) }}" class="theme-button theme-button1 theme-button3 font-13">{{ __('Renew') }}</a>
                        @endif
                    </td>
                </tr>
                @endif
            @endforeach

            </tbody>
        </table>
    </div>
@else
    <!-- If there is no data Show Empty Design Start -->
    <div class="empty-data">
        <img src="{{ asset('frontend/assets/img/empty-data-img.png') }}" alt="img" class="img-fluid">
        <h4 class="my-3">{{ __('Empty Course') }}</h4>
    </div>
    <!-- If there is no data Show Empty Design End -->
@endif
<!-- Pagination Start -->
@if(@$enrollments->hasPages())
    {{ @$enrollments->links('frontend.paginate.paginate') }}
@endif
<!-- Pagination End -->
