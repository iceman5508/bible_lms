@forelse(@$blogs as $blog)
    <li class="search-bar-result-item">
        <a href="{{ route('blog-details', $blog->slug) }}">
            <img src="{{ getImageFile($blog->image_path) }}" alt="img">
            <span>{{ Str::limit($blog->title, 30) }}</span>
        </a>
    </li>
@empty
    <li class="search-bar-result-item no-search-result-found">{{ __('No Data Found') }}</li>
@endforelse




