@forelse($forums as $forum)
<li class="search-bar-result-item">
    <a href="{{ route('forum.forumPostDetails', $forum->uuid) }}">
        <span>{{ $forum->title }}</span>
    </a>
</li>
@empty
<li class="search-bar-result-item no-search-result-found">{{ __('No Data Found') }}</li>
@endforelse
