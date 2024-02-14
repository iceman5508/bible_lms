@forelse(@$courses as $course)
    <li class="search-bar-result-item">
        <a href="{{ route('course-details', $course->slug) }}">
            <img src="{{ getImageFile($course->image_path) }}" alt="img">
            <span>{{ __($course->title) }}</span>
        </a>
    </li>
@empty
    <li class="search-bar-result-item no-search-result-found">{{ __('No Data Found') }}</li>
@endforelse
