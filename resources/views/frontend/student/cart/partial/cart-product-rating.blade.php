@php
$reviewCount = @$cart->product->reviews()->count();
$averate_percent = @$cart->product->average_review * 20;
@endphp
<!--Star:: Rating --->
<div class="course-item-bottom">
    <div class="align-items-baseline d-flex gap-2 rating-part">
        <span>{{ number_format(@$cart->product->average_review, 1) }}</span>
        <div class="rating-part-star">
            <div class="course-rating search-instructor-rating w-100 mb-0 d-inline-flex align-items-center justify-content-center">
                <div class="star-ratings">
                    <div class="fill-ratings" style="width: {{ $averate_percent ?? 0}}%">
                        <span>★★★★★</span>
                    </div>
                    <div class="empty-ratings">
                        <span>★★★★★</span>
                    </div>
                </div>
            </div>
        </div>
        <span class="num-total">({{ $reviewCount ?? 0 }})</span>
    </div>
</div>
<!--End:: Rating --->
