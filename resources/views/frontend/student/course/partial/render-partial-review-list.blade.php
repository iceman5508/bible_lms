@foreach($reviews as $review)
    <!-- Customer Review Item Start-->
    <div class="customer-review-item d-flex">
        <div class="flex-shrink-0 customer-review-item-img-wrap radius-50 overflow-hidden">
            <img src="{{ getImageFile(@$review->user->image_path) }}" alt="user" class="radius-50">
        </div>
        <div class="flex-grow-1 ms-3">
            <h6>{{ @$review->user->name }}</h6>
            <p>{{ @$review->created_at->diffForHumans() }}</p>

            <ul class="rating-list d-flex align-items-center me-2">
                <li class="{{ $review->rating >= 1 ? 'star-full': '' }}"><span class="iconify" data-icon="bi:star-fill"></span></li>
                <li class="{{ $review->rating >= 2 ? 'star-full': '' }}"><span class="iconify" data-icon="bi:star-fill"></span></li>
                <li class="{{ $review->rating >= 3 ? 'star-full': '' }}"><span class="iconify" data-icon="bi:star-fill"></span></li>
                <li class="{{ $review->rating >= 4 ? 'star-full': '' }}"><span class="iconify" data-icon="bi:star-fill"></span></li>
                <li class="{{ $review->rating >= 5 ? 'star-full': '' }}"><span class="iconify" data-icon="bi:star-fill"></span></li>
            </ul>

            <p>{{ $review->comment }}</p>
        </div>
    </div>
    <!-- Customer Review Item End-->
@endforeach
