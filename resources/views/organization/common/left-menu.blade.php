<div class="instructor-profile-left-part bg-white">
    <nav class="account-page-menu">
        <ul>
            <li><a href="{{route('organization.dashboard')}}" class="{{active_if_full_match('organization/dashboard')}} {{ @$navDashboardActiveClass }}"><span class="iconify mr-15" data-icon="feather:home"></span>{{__('Dashboard')}}</a></li>
            <li class="menu-has-children current-menu-item {{@$navInstructorActiveClass}}">
                <span class="toggle-account-menu">
                    <span class="iconify" data-icon="fontisto:angle-down"></span>
                </span>
                <a href="#" class="{{@$navInstructorActiveClass}}"><span class="iconify mr-15" data-icon="mdi:teacher"></span>{{ __('Manage Instructor') }}</a>
                <ul class="account-sub-menu">
                    <li><a href="{{ route('organization.instructor.index') }}" class="{{@$subNavInstructorIndexActiveClass}}">{{ __('All Instructor') }}</a></li>
                    <li><a href="{{ route('organization.instructor.create') }}" class="{{@$subNavInstructorAddActiveClass}}">{{ __('Add Instructor') }}</a></li>
                </ul>
            </li>
            <li class="menu-has-children current-menu-item {{@$navStudentActiveClass}}">
                <span class="toggle-account-menu">
                    <span class="iconify" data-icon="fontisto:angle-down"></span>
                </span>
                <a href="#" class="{{@$navStudentActiveClass}}"><span class="iconify mr-15" data-icon="ph:student"></span>{{ __('Manage Student') }}</a>
                <ul class="account-sub-menu">
                    <li><a href="{{ route('organization.student.index') }}" class="{{@$subNavStudentIndexActiveClass}}">{{ __('All Student') }}</a></li>
                    <li><a href="{{ route('organization.student.create') }}" class="{{@$subNavStudentAddActiveClass}}">{{ __('Add Student') }}</a></li>
                </ul>
            </li>
            <li class="menu-has-children current-menu-item {{@$navCourseActiveClass}}">
                <span class="toggle-account-menu">
                    <span class="iconify" data-icon="fontisto:angle-down"></span>
                </span>
                <a href="#" class="{{@$navCourseActiveClass}}"><span class="iconify mr-15" data-icon="fluent:learning-app-20-regular"></span>{{ __('Manage Course') }}</a>
                <ul class="account-sub-menu">
                    <li><a href="{{ route('organization.course.index') }}" class="{{@$subNavCourseIndexActiveClass}}">{{ __('All Course') }}</a></li>
                    <li><a href="{{ route('organization.course.create') }}" class="{{@$subNavCourseAddActiveClass}}">{{ __('Add Course') }}</a></li>
                </ul>
            </li>
            <li><a href="{{ route('organization.bundle-course.index') }}" class="{{ @$navBundleCourseActiveClass }}" ><span class="iconify mr-15" data-icon="eos-icons:machine-learning-outlined"></span>{{__('Bundles Courses')}}</a></li>
            <li><a href="{{ route('organization.notice-board.course-notice.index') }}" class="{{ @$navNoticeBoardActiveClass }}"><span class="iconify mr-15" data-icon="ep:data-board"></span>{{__('Notice Board')}}</a></li>
            <li><a href="{{ route('organization.live-class.course-live-class.index') }}" class="{{ @$navLiveClassActiveClass }}"><span class="iconify mr-15" data-icon="fluent:live-24-regular"></span>{{__('Live Class')}}</a></li>
            <li class="menu-has-children current-menu-item {{@$navConsultationActiveClass}}">
                <span class="toggle-account-menu">
                    <span class="iconify" data-icon="fontisto:angle-down"></span>
                </span>
                <a href="#" class="{{@$navConsultationActiveClass}}"><span class="iconify mr-15" data-icon="ic:round-support-agent"></span>{{ __('Consultation') }}</a>
                <ul class="account-sub-menu">
                    <li><a href="{{ route('organization.consultation.dashboard') }}" class="{{@$subNavConsultationDashboardActiveClass}}">{{ __('Dashboard') }}</a></li>
                    <li><a href="{{ route('organization.bookingRequest') }}" class="{{ @$subNavBookingRequestActiveClass }}">{{ __('Booking Request') }}</a></li>
                    <li><a href="{{ route('organization.bookingHistory') }}" class="{{ @$subNavBookingHistoryActiveClass }}">{{ __('Booking History') }}</a></li>
                </ul>
            </li>
            <li><a href="{{route('organization.certificate.index')}}" class="{{ @$navCertificateActiveClass }}" ><span class="iconify mr-15" data-icon="fluent:certificate-20-regular"></span>{{__('Certificate')}}</a></li>

            <li><a href="{{route('organization.discussion.index')}}" class="{{ @$navDiscussionActiveClass }}" ><span class="iconify mr-15" data-icon="octicon:comment-discussion-24"></span>{{__('Discussion')}}</a></li>
            <li><a href="{{route('organization.chat.index')}}" class="{{ @$navChatActiveClass }}" ><span class="iconify mr-15" data-icon="ion-ios-chatboxes"></span>{{__('Chat')}}</a></li>
            <li><a href="{{route('organization.finance.analysis.index')}}" class="{{ @$navFinanceActiveClass }}" ><span class="iconify mr-15" data-icon="system-uicons:heart-rate"></span>{{__('Finance')}}</a></li>
           {{-- <li class="menu-has-children current-menu-item {{@$navFinanceActiveClass}}">
                <span class="toggle-account-menu">
                    <span class="iconify" data-icon="fontisto:angle-down"></span>
                </span>
                <a href="#" class="{{@$navFinanceActiveClass}}"><span class="iconify mr-15" data-icon="system-uicons:heart-rate"></span>{{__('Finance')}}</a>
                <ul class="account-sub-menu">
                    <li><a href="{{ route('organization.finance.analysis.index') }}" class="{{ @$subNavAnalysisActiveClass }}">{{__('Analysis')}}</a></li>
                    <li><a href="{{ route('organization.finance.withdraw-history.index') }}" class="{{ @$subNavWithdrawActiveClass }}">{{__('Withdraw History')}}</a></li>
                </ul>
            </li> --}}

            <li><a href="{{ route('organization.followings') }}" class="{{ @$navFollowingsActiveClass }}" ><span class="iconify mr-15" data-icon="fluent-mdl2:follow-user"></span>{{__('Followings')}}</a></li>
            <li><a href="{{ route('organization.followers') }}" class="{{ @$navFollowersActiveClass }}" ><span class="iconify mr-15" data-icon="fluent-mdl2:user-followed"></span>{{__('Followers')}}</a></li>
            <li class="menu-has-children current-menu-item {{@$navProfileActiveClass}}">
                <span class="toggle-account-menu">
                    <span class="iconify" data-icon="fontisto:angle-down"></span>
                </span>
                <a href="#" class="{{@$navProfileActiveClass}}"><span class="iconify mr-15" data-icon="bx:bx-user"></span>{{__('Profile')}}</a>
                <ul class="account-sub-menu">
                    <li><a href="{{ route('organization.profile') }}" class="{{ @$subNavProfileBasicActiveClass }}">{{__('Basic Information')}}</a></li>
                    <li><a href="{{ route('organization.address') }}" class="{{ @$subNavProfileAddressActiveClass }}">{{__('Address & Location')}}</a></li>
                </ul>
            </li>
            {{-- <li><a href="{{route('organization.my-card')}}" class="{{ @$navPaymentActiveClass }}" ><span class="iconify mr-15" data-icon="material-symbols:payments-outline"></span>{{ __('Payment Settings') }}</a></li> --}}
            <li><a href="{{route('organization.zoom-setting.update')}}" class="{{ @$navZoomSettingActiveClass }}" ><span class="iconify mr-15" data-icon="fluent:meet-now-28-filled"></span>{{ __('Zoom Settings') }}</a></li>
            @if(get_option('gmeet_status'))
            <li><a href="{{route('organization.gmeet_setting.update')}}" class="{{ @$navGmeetSettingActiveClass }}" ><span class="iconify mr-15" data-icon="fluent:meet-now-24-regular"></span>{{ __('Gmeet Settings') }}</a></li>
            @endif
        </ul>
    </nav>
</div>
