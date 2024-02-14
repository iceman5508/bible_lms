<div class="email__sidebar bg-style">
    <div class="sidebar__item">
        <ul class="sidebar__mail__nav">
            <li>
                <a href="{{ route('settings.theme-setting') }}" class="list-item {{ @$themeSettingsActiveClass }}">
                    <img src="{{ asset('admin/images/heroicon/outline/cog.svg') }}" alt="icon">
                    <span>{{ __('Theme Settings') }}</span>
                </a>
            </li>
            <li>
                <a href="{{ route('settings.section-settings') }}" class="list-item {{ @$sectionSettingsActiveClass }}">
                    <img src="{{ asset('admin/images/heroicon/outline/cog.svg') }}" alt="icon">
                    <span>{{ __('Section Settings') }}</span>
                </a>
            </li>
            <li>
                <a href="{{ route('settings.banner-section') }}" class="list-item {{ @$bannerSectionActiveClass }}">
                    <img src="{{ asset('admin/images/heroicon/outline/cog.svg') }}" alt="icon">
                    <span>{{ __('Banner Section') }}</span>
                </a>
            </li>
            <li>
                <a href="{{ route('settings.special-feature-section') }}" class="list-item {{ @$specialSectionActiveClass }}">
                    <img src="{{ asset('admin/images/heroicon/outline/cog.svg') }}" alt="icon">
                    <span>{{ __('Special Feature Section') }}</span>
                </a>
            </li>
            <li>
                <a href="{{ route('settings.course-section') }}" class="list-item {{ @$courseSectionActiveClass }}">
                    <img src="{{ asset('admin/images/heroicon/outline/cog.svg') }}" alt="icon">
                    <span>{{ __('Course Section') }}</span>
                </a>
            </li>
            <li>
                <a href="{{ route('settings.category-course-section') }}" class="list-item {{ @$categoryCourseSectionActiveClass }}">
                    <img src="{{ asset('admin/images/heroicon/outline/cog.svg') }}" alt="icon">
                    <span>{{ __('Category Course Section') }}</span>
                </a>
            </li>
            @if(isAddonInstalled('LMSZAIPRODUCT'))
            <li>
                <a href="{{ route('settings.product-section') }}" class="list-item {{ @$productSectionActiveClass }}">
                    <img src="{{ asset('admin/images/heroicon/outline/cog.svg') }}" alt="icon">
                    <span>{{ __('Product Section') }}</span>
                </a>
            </li>
            @endif
            <li>
                <a href="{{ route('settings.upcoming-course-section') }}" class="list-item {{ @$upcomingCourseSectionActiveClass }}">
                    <img src="{{ asset('admin/images/heroicon/outline/cog.svg') }}" alt="icon">
                    <span>{{ __('Upcoming Course Section') }}</span>
                </a>
            </li>
            <li>
                <a href="{{ route('settings.bundle-course-section') }}" class="list-item {{ @$bundleCourseSectionActiveClass }}">
                    <img src="{{ asset('admin/images/heroicon/outline/cog.svg') }}" alt="icon">
                    <span>{{ __('Bundle Course Section') }}</span>
                </a>
            </li>
            <li>
                <a href="{{ route('settings.top-category-section') }}" class="list-item {{ @$topCategorySectionActiveClass }}">
                    <img src="{{ asset('admin/images/heroicon/outline/cog.svg') }}" alt="icon">
                    <span>{{ __('Top Category Section') }}</span>
                </a>
            </li>
            <li>
                <a href="{{ route('settings.top-instructor-section') }}" class="list-item {{ @$topInstructorSectionActiveClass }}">
                    <img src="{{ asset('admin/images/heroicon/outline/cog.svg') }}" alt="icon">
                    <span>{{ __('Top Instructor Section') }}</span>
                </a>
            </li>
            <li>
                <a href="{{ route('settings.become-instructor-video-section') }}" class="list-item {{ @$becomeInstructorVideoSectionActiveClass }}">
                    <img src="{{ asset('admin/images/heroicon/outline/cog.svg') }}" alt="icon">
                    <span>{{ __('Become Instructor Video Section') }}</span>
                </a>
            </li>
            <li>
                <a href="{{ route('settings.customer-say-section') }}" class="list-item {{ @$customerSaySectionActiveClass }}">
                    <img src="{{ asset('admin/images/heroicon/outline/cog.svg') }}" alt="icon">
                    <span>{{ __('Customer Say Section') }}</span>
                </a>
            </li>
            <li>
                <a href="{{ route('settings.achievement-section') }}" class="list-item {{ @$achievementSectionActiveClass }}">
                    <img src="{{ asset('admin/images/heroicon/outline/cog.svg') }}" alt="icon">
                    <span>{{ __('Achievement Section') }}</span>
                </a>
            </li>

        </ul>
    </div>
</div>
