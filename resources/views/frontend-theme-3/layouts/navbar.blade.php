@php
    $authUser = @auth()->user();
@endphp

<div class="landing-menu">
    <section class="menu-section-area @auth isLoginMenu @endauth">
        <!-- Navigation -->
        <nav class="navbar sticky-header navbar-expand-lg {{ request()->route()->getName() == 'main.index'? 'nav-home': '' }}"
            id="mainNav">
            <div class="container-fluid">
                @if(request()->route()->getName() == 'main.index')
                <a class="navbar-brand sf-sticky-logo" href="{{ url('/') }}"><img
                        src="{{ getImageFile(get_option('app_black_logo')) }}" alt="Logo"></a>
                @else
                <a class="navbar-brand sf-sticky-logo" href="{{ url('/') }}"><img
                                src="{{ getImageFile(get_option('app_logo')) }}" alt="Logo"></a>
                @endif
                <a class="navbar-brand" href="{{ url('/') }}"><img src="{{ getImageFile(get_option('app_logo')) }}"
                        alt="Logo"></a>
                <button class="navbar-toggler sf-navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="main-menu-collapse collapse navbar-collapse" id="navbarSupportedContent">

                    <div class="header-nav-left-side me-auto d-flex">
                        <ul class="navbar-nav mb-2 mb-lg-0">
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="librariesDropdown"
                                    data-bs-toggle="dropdown">
                                    {{ __('Courses') }}
                                </a>
                                <ul class="dropdown-menu {{ $selectedLanguage->rtl == 1 ? 'dropdown-menu-end' : '' }}">
                                    @foreach ($categories as $category)
                                        <li>
                                            <a href="{{ route('category-courses', $category->slug) }}"
                                                class="dropdown-item @if (count($category->subcategories) > 0) dropdown-toggle @endif">{{ $category->name }}</a>
                                            @if (count($category->subcategories) > 0)
                                                <ul class="submenu dropdown-menu">
                                                    @foreach ($category->subcategories as $subcategory)
                                                        <li><a class="dropdown-item"
                                                                href="{{ route('subcategory-courses', $subcategory->slug) }}">{{ $subcategory->name }}</a>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            @endif
                                        </li>
                                    @endforeach
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                    <li><a href="{{ route('courses') }}">{{ __('All Courses') }}</a></li>
                                </ul>
                            </li>
                        </ul>

                        <form action="#">
                            <div class="input-group landing">
                                <button class="input-group-text pe-0" id="basic-addon1"><img
                                        src="{{ asset('frontend/assets/img/icons-svg/search-2.svg') }}"
                                        alt="Search"></button>
                                <input class="form-control me-2 searchCourse" id="searchCourse" type="search"
                                    name="keyword" value="{{ request('keyword') }}"
                                    placeholder="{{ __('Search Course') }}..." aria-label="Search">
                            </div>

                            <!-- Search Bar Suggestion Box Start -->
                            <div class="search-bar-suggestion-box searchBox d-none custom-scrollbar">
                                <ul class="appendCourseSearchList">

                                </ul>
                            </div>
                            <!-- Search Bar Suggestion Box End -->
                        </form>
                    </div>
                    <div class="header-nav-right-side d-flex">
                        <ul class="navbar-nav">
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="pagesDropdown"
                                    data-bs-toggle="dropdown">{{ __('Pages') }}</a>
                                <ul class="dropdown-menu {{ $selectedLanguage->rtl == 1 ? 'dropdown-menu-end' : '' }}">
                                    @foreach ($staticMenus ?? [] as $staticMenu)
                                        <li><a class="dropdown-item"
                                                href="{{ route($staticMenu->slug) }}">{{ __($staticMenu->name) }}</a>
                                        </li>
                                    @endforeach

                                    @foreach ($dynamicMenus ?? [] as $dynamicMenu)
                                        <li><a class="dropdown-item"
                                                href="{{ route('page', @$dynamicMenu->page->slug) }}">{{ __($dynamicMenu->name) }}</a>
                                        </li>
                                    @endforeach
                                </ul>
                            </li>
                            @if (
                                @$authUser->role == USER_ROLE_INSTRUCTOR ||
                                    @$authUser->role == USER_ROLE_STUDENT ||
                                    @$authUser->role == USER_ROLE_ORGANIZATION)
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('forum.index') }}">{{ __('Forum') }}</a>
                                </li>
                                @if (@$authUser->role == USER_ROLE_STUDENT)
                                    @if (@$authUser->instructor || @$authUser->organization)
                                        <li class="nav-item">
                                            <span class="nav-link">{{ __('Request Pending') }}</span>
                                        </li>
                                    @else
                                        <li class="nav-item">
                                            <a class="nav-link"
                                                href="{{ route('student.become-an-instructor') }}">{{ __('Become an
                                                                                                                                                                            Instructor') }}</a>
                                        </li>
                                    @endif
                                @elseif(@$authUser->role == USER_ROLE_INSTRUCTOR || @$authUser->role == USER_ROLE_ORGANIZATION)
                                    <!-- Status 1 = Approved,  Status 2 = Blocked,  Status 0 = Pending -->
                                    @if (@$authUser->instructor->status == STATUS_APPROVED)
                                        @if (\Illuminate\Support\Str::contains(url()->current(), 'instructor'))
                                            <li class="nav-item">
                                                <a class="nav-link"
                                                    href="{{ route('student.dashboard') }}">{{ __('Student Panel') }}</a>
                                            </li>
                                        @else
                                            <li class="nav-item">
                                                <a class="nav-link"
                                                    href="{{ route('instructor.dashboard') }}">{{ __('Instructor Panel') }}</a>
                                            </li>
                                        @endif
                                    @elseif(@$authUser->organization->status == STATUS_APPROVED)
                                        @if (\Illuminate\Support\Str::contains(url()->current(), 'organization'))
                                            <li class="nav-item">
                                                <a class="nav-link"
                                                    href="{{ route('student.dashboard') }}">{{ __('Student Panel') }}</a>
                                            </li>
                                        @else
                                            <li class="nav-item">
                                                <a class="nav-link"
                                                    href="{{ route('organization.dashboard') }}">{{ __('Organization Panel') }}</a>
                                            </li>
                                        @endif
                                    @elseif(@$authUser->instructor->status == STATUS_REJECTED)
                                        <li class="nav-item">
                                            <span class="nav-link">{{ __('Blocked From Instructor Panel') }}</span>
                                        </li>
                                    @elseif(@$authUser->organization->status == STATUS_REJECTED)
                                        <li class="nav-item">
                                            <span class="nav-link">{{ __('Blocked From Organization Panel') }}</span>
                                        </li>
                                    @else
                                        <li class="nav-item">
                                            <span class="nav-link">{{ __('Request Pending') }}</span>
                                        </li>
                                    @endif
                                @endif
                            @else
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('forum.index') }}">{{ __('Forum') }}</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('blogs') }}">{{ __('Blog') }}</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" aria-current="page"
                                        href="{{ route('contact') }}">{{ __('Contact') }}</a>
                                </li>
                            @endif
                            <li class="nav-item dropdown menu-round-btn menu-language-btn dropdown-top-space">
                                <a class="nav-link" href="#">
                                    <img src="{{ asset($selectedLanguage->flag) }}" alt="Flag"
                                        class="radius-50">
                                </a>
                                <ul class="dropdown-menu {{ $selectedLanguage->rtl == 1 ? 'dropdown-menu-start' : 'dropdown-menu-end' }}"
                                    data-bs-popper="none">
                                    @foreach (appLanguages() as $app_lang)
                                        <li><a class="dropdown-item"
                                                href="{{ url('/local/' . $app_lang->iso_code) }}">
                                                <img src="{{ asset($app_lang->flag) }}" alt="Flag"
                                                    class="radius-50">{{ $app_lang->language }}</a></li>
                                    @endforeach
                                </ul>
                            </li>
                            @if (@$authUser->role != USER_ROLE_ADMIN)
                                @if (auth::user())
                                    <!-- Menu Notification Option Start -->
                                    <li
                                        class="nav-item dropdown menu-round-btn menu-notification-btn dropdown-top-space">
                                        <a class="nav-link menu-cart-btn" href="#">
                                            <i data-feather="bell"></i>
                                            <span
                                                class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                                {{ count($student_notifications) + count($instructor_notifications) }}
                                            </span>
                                        </a>
                                        <div class="dropdown-menu {{ $selectedLanguage->rtl == 1 ? 'dropdown-menu-start' : 'dropdown-menu-end' }}"
                                            data-bs-popper="none">
                                            <ul class="nav nav-pills menu-notification-tab-list" id="pills-tab"
                                                role="tablist">
                                                <li class="nav-item" role="presentation">
                                                    <button class="nav-link active" id="pills-student-tab"
                                                        data-bs-toggle="pill" data-bs-target="#pills-student"
                                                        type="button" role="tab" aria-controls="pills-student"
                                                        aria-selected="false">{{ __('Student') }}</button>
                                                </li>
                                                <li class="nav-item" role="presentation">
                                                    <button class="nav-link " id="pills-instructor-tab"
                                                        data-bs-toggle="pill" data-bs-target="#pills-instructor"
                                                        type="button" role="tab"
                                                        aria-controls="pills-instructor"
                                                        aria-selected="true">{{ __('Instructor') }}</button>
                                                </li>
                                            </ul>
                                            <div class="tab-content" id="pills-tabContent">
                                                <div class="tab-pane fade show active" id="pills-student"
                                                    role="tabpanel" aria-labelledby="pills-student-tab">
                                                    <div class="custom-scrollbar menu-notification-tab-content">
                                                        @forelse ($student_notifications as $student_notification)
                                                            <!-- Message User Item Start -->
                                                            <a href="{{ route('notification.url', [$student_notification->uuid]) }}"
                                                                class="message-user-item cursor d-flex align-items-center">
                                                                <div class="message-user-item-left">
                                                                    <div class="d-flex align-items-center">
                                                                        <div class="flex-shrink-0">
                                                                            <div
                                                                                class="user-img-wrap position-relative radius-50">
                                                                                <img src="{{ asset($student_notification->sender->image_path) }}"
                                                                                    alt="img" class="radius-50">
                                                                            </div>
                                                                        </div>
                                                                        <div
                                                                            class="flex-grow-1 {{ $selectedLanguage->rtl == 1 ? 'me-2' : 'ms-2' }}">
                                                                            <h6 class="color-heading font-14">
                                                                                {{ $student_notification->sender->name }}
                                                                            </h6>
                                                                            <p class="font-13">
                                                                                {{ $student_notification->text }}</p>
                                                                            <div class="font-11 color-gray mt-1">
                                                                                {{ @$student_notification->created_at->diffForHumans() }}
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </a>
                                                            <!-- Message User Item End -->
                                                        @empty
                                                            <div class="no-notification-found-box">
                                                                <p class="text-center">{{ __('No Data Found') }}</p>
                                                            </div>
                                                        @endforelse
                                                    </div>
                                                    @if (count($student_notifications))
                                                        <div class="dropdown-divider"></div>
                                                        <form action="{{ route('notification.all-read') }}"
                                                            method="POST">
                                                            @csrf
                                                            <input type="hidden" name="type" value="3">
                                                            <button type="submit"
                                                                class="dropdown-item dropdown-footer">{{ __('Mark all as read') }}</button>
                                                        </form>
                                                    @endif
                                                </div>

                                                <div class="tab-pane fade" id="pills-instructor" role="tabpanel"
                                                    aria-labelledby="pills-instructor-tab">
                                                    <div class="custom-scrollbar menu-notification-tab-content">
                                                        @forelse ($instructor_notifications as $instructor_notification)
                                                            <!-- Message User Item Start -->
                                                            <a href="{{ route('notification.url', [$instructor_notification->uuid]) }}"
                                                                class="message-user-item d-flex align-items-center">
                                                                <div class="message-user-item-left">
                                                                    <div class="d-flex align-items-center">
                                                                        <div class="flex-shrink-0">
                                                                            <div
                                                                                class="user-img-wrap position-relative radius-50">
                                                                                <img src="{{ asset(@$instructor_notification->sender->image_path) }}"
                                                                                    alt="img" class="radius-50">
                                                                            </div>
                                                                        </div>
                                                                        <div
                                                                            class="flex-grow-1 {{ $selectedLanguage->rtl == 1 ? 'me-2' : 'ms-2' }}">
                                                                            <h6 class="color-heading font-14">
                                                                                {{ @$instructor_notification->sender->name }}
                                                                            </h6>
                                                                            <p class="font-13">
                                                                                {{ $instructor_notification->text }}
                                                                            </p>
                                                                            <div class="font-11 color-gray mt-1">
                                                                                {{ @$instructor_notification->created_at->diffForHumans() }}
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </a>
                                                            <!-- Message User Item End -->
                                                        @empty
                                                            <div class="no-notification-found-box">
                                                                <p class="text-center">{{ __('No Data Found') }}</p>
                                                            </div>
                                                        @endforelse
                                                    </div>
                                                    @if (count($instructor_notifications))
                                                        <div class="dropdown-divider"></div>
                                                        <form action="{{ route('notification.all-read') }}"
                                                            method="POST">
                                                            @csrf
                                                            <input type="hidden" name="type" value="2">
                                                            <button type="submit"
                                                                class="dropdown-item dropdown-footer">{{ __('Mark all as read') }}</button>
                                                        </form>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    <!-- Menu Notification Option End -->
                                @endif

                                <!-- Menu Cart Option Start -->
                                <li class="nav-item menu-round-btn menu-cart-btn">
                                    <a class="nav-link menu-cart-btn" aria-current="page"
                                        href="{{ route('student.cartList') }}">
                                        <i data-feather="shopping-bag"></i>
                                        <span
                                            class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger cartQuantity">
                                            {{ @$cartQuantity }}
                                        </span>
                                    </a>
                                </li>
                                <!-- Menu Cart Option End -->
                            @endif
                            @if (Route::has('login'))
                                @auth

                                    @if (isEnableOpenAI())
                                        <!-- AI Option Start -->
                                        <li class="nav-item menu-round-btn">
                                            <a id="ai-content-toggle" class="nav-link" aria-current="page">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0 24 24">
                                                    <path fill="currentColor"
                                                        d="M8.356 5H7.01L5 13h1.028l.464-1.875h2.316L9.26 13h1.062Zm-1.729 5.322L7.644 5.95h.045l.984 4.373ZM11.238 13V5h1v8Zm.187 1H4V4h10v4.78a5.504 5.504 0 0 1 4-.786V6h-2V4a2.006 2.006 0 0 0-2-2h-2V0h-2v2H8V0H6v2H4a2.006 2.006 0 0 0-2 2v2H0v2h2v2H0v2h2v2a2.006 2.006 0 0 0 2 2h2v2h2v-2h2v2h2v-1.992A5.547 5.547 0 0 1 11.425 14Zm2.075-.5A3.5 3.5 0 1 1 17 17a3.499 3.499 0 0 1-3.5-3.5ZM17 19c-2.336 0-7 1.173-7 3.5V24h14v-1.5c0-2.328-4.664-3.5-7-3.5Z" />
                                                </svg>
                                            </a>
                                        </li>
                                        <!-- AI Option End -->
                                    @endif

                                    <!-- Menu User Dropdown Menu Option Start -->
                                    <li class="nav-item dropdown menu-round-btn menu-user-btn dropdown-top-space">
                                        <a class="nav-link" href="#">
                                            <img src="{{ asset(auth::user()->image_path) }}" alt="user"
                                                class="radius-50">
                                        </a>
                                        <div class="dropdown-menu-profile dropdown-menu {{ $selectedLanguage->rtl == 1 ? 'dropdown-menu-start' : 'dropdown-menu-end' }}"
                                            data-bs-popper="none">

                                            <!-- Dropdown User Info Item Start -->
                                            <div class="dropdown-user-info">
                                                <div class="message-user-item d-flex align-items-center">
                                                    <div class="message-user-item-left">
                                                        <div class="d-flex align-items-center">
                                                            <div class="flex-shrink-0">
                                                                <div class="user-img-wrap position-relative radius-50">
                                                                    <img src="{{ asset(auth::user()->image_path) }}"
                                                                        alt="img" class="radius-50">
                                                                </div>
                                                            </div>
                                                            <div
                                                                class="flex-grow-1 {{ $selectedLanguage->rtl == 1 ? 'me-2' : 'ms-2' }}">
                                                                <h6 class="color-heading font-14">{{ auth::user()->name }}
                                                                </h6>
                                                                <p class="font-13">{{ auth::user()->email }}</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Dropdown User Info Item End -->
                                            @if (@$authUser->role != USER_ROLE_ADMIN)
                                                <ul class="user-dropdown-item-box">
                                                    <li><a class="dropdown-item"
                                                            href="{{ route('student.my-learning') }}"><span
                                                                class="iconify"
                                                                data-icon="akar-icons:book"></span>{{ __('My Learning') }}
                                                        </a></li>
                                                    @if (@$authUser->role == USER_ROLE_STUDENT && auth()->user()->student->organization_id != null)
                                                        <li>
                                                            <a class="dropdown-item"
                                                                href="{{ route('student.organization_course') }}"><span
                                                                    class="iconify mr-15"
                                                                    data-icon="ion:log-in-outline"></span>
                                                                {{ __('Organization Course') }}</a>
                                                        </li>
                                                    @endif
                                                    <li>
                                                        <a class="dropdown-item"
                                                            href="{{ route('student.my-consultation') }}">
                                                            <span class="iconify mr-15"
                                                                data-icon="ic:round-support-agent"></span>
                                                            {{ __('My Consultation') }}
                                                        </a>
                                                    </li>
                                                    @if (isAddonInstalled('LMSZAIPRODUCT'))
                                                        <li>
                                                            <a class="dropdown-item"
                                                                href="{{ route('lms_product.student.purchase_list') }}">
                                                                <span class="iconify mr-15"
                                                                    data-icon="carbon:product"></span>
                                                                {{ __('My Product') }}
                                                            </a>
                                                        </li>
                                                    @endif
                                                    @if (Auth::user()->role == USER_ROLE_STUDENT)
                                                        <li>
                                                            <a class="dropdown-item"
                                                                href="{{ route('student.chat.index') }}">
                                                                <span class="iconify" data-icon="ic:outline-chat"></span>
                                                                {{ __('Chat') }}</a>
                                                        </li>
                                                    @endif
                                                    <li><a class="dropdown-item"
                                                            href="{{ route('student.wishlist') }}"><span class="iconify"
                                                                data-icon="bx:bx-heart"></span>{{ __('Wishlist') }}</a>
                                                    </li>
                                                    @if (get_option('subscription_mode'))
                                                        <li>
                                                            <a class="dropdown-item"
                                                                href="{{ route('subscription_panel') }}"><span
                                                                    class="iconify"
                                                                    data-icon="akar-icons:desktop-device"></span>
                                                                {{ __('Subscription Panel') }}</a>
                                                        </li>
                                                        <li>
                                                            <a class="dropdown-item"
                                                                href="{{ route('student.subscription_plan') }}"><span
                                                                    class="iconify"
                                                                    data-icon="eos-icons:subscriptions-created"></span>
                                                                {{ __('Subscription Plan') }}</a>
                                                        </li>
                                                    @endif
                                                    @if (auth()->user()->role != USER_ROLE_STUDENT && get_option('saas_mode'))
                                                        <li>
                                                            <a class="dropdown-item"
                                                                href="{{ route('saas_panel') }}"><span class="iconify"
                                                                    data-icon="ic:outline-view-list"></span>
                                                                {{ __('SaaS Panel') }}</a>
                                                        </li>
                                                        <li>
                                                            <a class="dropdown-item"
                                                                href="{{ route('saas_plan') }}"><span class="iconify"
                                                                    data-icon="mdi:subscriptions"></span>
                                                                {{ __('SaaS Plan') }}</a>
                                                        </li>
                                                    @endif
                                                </ul>
                                                <ul class="user-dropdown-item-box">
                                                    <li>
                                                        <a class="dropdown-item"
                                                            href="{{ route('student.profile') }}"><span class="iconify"
                                                                data-icon="bytesize:settings"></span>
                                                            {{ __('Profile Settings') }}</a>
                                                    </li>
                                                    @if (@$authUser->role == USER_ROLE_STUDENT && get_option('device_control'))
                                                        <li>
                                                            <a class="dropdown-item"
                                                                href="{{ route('student.all_login_device') }}"><span
                                                                    class="iconify"
                                                                    data-icon="akar-icons:desktop-device"></span>
                                                                {{ __('Device Control') }}</a>
                                                        </li>
                                                    @endif

                                                    @if (Auth::user()->role == USER_ROLE_STUDENT ||
                                                            Auth::user()->is_affiliator == AFFILIATOR ||
                                                            (Auth::user()->role == USER_ROLE_INSTRUCTOR && @$authUser->instructor->status == STATUS_APPROVED) ||
                                                            (Auth::user()->role == USER_ROLE_ORGANIZATION && @$authUser->organization->status == STATUS_APPROVED))
                                                        <li>
                                                            <a class="dropdown-item"
                                                                href="{{ route('wallet./') }}"><span class="iconify"
                                                                    data-icon="et:wallet"></span>
                                                                {{ __('My Wallet') }}
                                                            </a>
                                                        </li>
                                                    @endif
                                                </ul>

                                                @if (Auth::user()->is_affiliator == NOT_AFFILIATOR && get_option('referral_status') == 1)
                                                    <ul class="user-dropdown-item-box">
                                                        <li>
                                                            <a class="dropdown-item"
                                                                href="{{ route('affiliate.become-an-affiliate') }}"><span
                                                                    class="iconify"
                                                                    data-icon="tabler:affiliate"></span>{{ __('Become an
                                                                                                                                                                                                                                                    Affiliator') }}
                                                            </a>
                                                        </li>
                                                    </ul>
                                                @elseif(Auth::user()->is_affiliator == AFFILIATE_REQUEST_PENDING && get_option('referral_status') == 1)
                                                    <ul class="user-dropdown-item-box">
                                                        <li>
                                                            <a class="dropdown-item" href="#"><span class="iconify"
                                                                    data-icon="tabler:affiliate"></span>{{ __('Affiliate Request Pending') }}
                                                            </a>
                                                        </li>
                                                    </ul>
                                                @elseif(Auth::user()->is_affiliator == AFFILIATE_REQUEST_REJECTED && get_option('referral_status') == 1)
                                                    <ul class="user-dropdown-item-box">
                                                        <li>
                                                            <a class="dropdown-item" href="#"><span class="iconify"
                                                                    data-icon="tabler:affiliate"></span>{{ __('Affiliate Request Rejected') }}
                                                            </a>
                                                        </li>
                                                    </ul>
                                                @elseif(Auth::user()->is_affiliator == AFFILIATOR)
                                                    <ul class="user-dropdown-item-box">
                                                        <li>
                                                            <a class="dropdown-item"
                                                                href="{{ route('affiliate.dashboard') }}"><span
                                                                    class="iconify" data-icon="tabler:affiliate"></span>
                                                                {{ __('Affiliator Panel') }}
                                                            </a>
                                                        </li>
                                                    </ul>
                                                @endif
                                            @endif
                                            @if (@$authUser->role == USER_ROLE_ADMIN)
                                                <ul class="user-dropdown-item-box">
                                                    <li>
                                                        <a class="dropdown-item" href="{{ route('admin.dashboard') }}">
                                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                                xmlns:xlink="http://www.w3.org/1999/xlink"
                                                                aria-hidden="true" role="img"
                                                                class="iconify iconify--bxs" width="1em"
                                                                height="1em" preserveAspectRatio="xMidYMid meet"
                                                                viewBox="0 0 24 24" data-icon="bxs:dashboard">
                                                                <path fill="currentColor"
                                                                    d="M4 13h6a1 1 0 0 0 1-1V4a1 1 0 0 0-1-1H4a1 1 0 0 0-1 1v8a1 1 0 0 0 1 1zm-1 7a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1v-4a1 1 0 0 0-1-1H4a1 1 0 0 0-1 1v4zm10 0a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1v-7a1 1 0 0 0-1-1h-6a1 1 0 0 0-1 1v7zm1-10h6a1 1 0 0 0 1-1V4a1 1 0 0 0-1-1h-6a1 1 0 0 0-1 1v5a1 1 0 0 0 1 1z">
                                                                </path>
                                                            </svg>
                                                            {{ __('Admin Dashboard') }}
                                                        </a>
                                                    </li>
                                                </ul>
                                            @endif
                                            <ul class="user-dropdown-item-box">
                                                <li><a class="dropdown-item"
                                                        href="{{ route('support-ticket-faq') }}"><span class="iconify"
                                                            data-icon="bx:bx-help-circle"></span>{{ __('Help
                                                                                                                                                                                                                            Support') }}
                                                    </a></li>
                                                <li><a class="dropdown-item" href="{{ route('logout') }}"><span
                                                            class="iconify"
                                                            data-icon="ic:round-logout"></span>{{ __('Logout') }}</a>
                                                </li>
                                            </ul>
                                        </div>
                                    </li>
                                    <!-- Menu User Dropdown Menu Option End -->
                                @else
                                    <li class="nav-item  menu-sign-in-btn">
                                        <a href="{{ route('login') }}" class="nav-link theme-button1"
                                            aria-current="page">{{ __('Sign
                                                                                                                                                                In') }}</a>
                                    </li>

                                    @if (Route::has('register'))
                                        <li class="nav-item  menu-sign-in-btn">
                                            <a href="{{ route('register') }}" class="nav-link theme-button1"
                                                aria-current="page">{{ __('Sign Up') }}</a>
                                        </li>
                                    @endif
                                @endauth
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
        </nav>
        <!-- Navigation -->
    </section>
</div>

@if (isEnableOpenAI())
    @include('addon.AI.content-generation')
@endif
