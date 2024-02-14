<div class="instructor-profile-left-part bg-white">
    <nav class="account-page-menu">
        <ul>
            <li><a href="{{route('instructor.dashboard')}}" class="{{active_if_full_match('instructor/dashboard')}} {{ @$navDashboardActiveClass }}"><span class="iconify mr-15" data-icon="feather:home"></span>{{__('Dashboard')}}</a></li>
            <li><a href="{{route('instructor.course.create')}}" class="{{active_if_full_match('instructor/course/create')}} {{ @$navCourseUploadActiveClass }}"><span class="iconify mr-15" data-icon="feather:upload"></span>{{__('Upload Course')}}</a></li>
            <li><a href="{{route('instructor.course')}}" class="{{active_if_full_match('instructor/course')}} {{ @$navCourseActiveClass }}" ><span class="iconify mr-15" data-icon="ion:log-in-outline"></span>{{__('My Courses')}}</a></li>
            @if(@auth()->user()->instructor->organization_id != NULL)
            <li><a href="{{route('instructor.course.organization')}}" class="{{active_if_full_match('instructor/course/organization')}} {{ @$navCourseOrganizationActiveClass }}" ><span class="iconify mr-15" data-icon="ion:log-in-outline"></span>{{__('Organization Courses')}}</a></li>
            @endif
            <li><a href="{{ route('instructor.bundle-course.index') }}" class="{{ @$navBundleCourseActiveClass }}" ><span class="iconify mr-15" data-icon="eos-icons:machine-learning-outlined"></span>{{__('Bundles Courses')}}</a></li>
            <li><a href="{{ route('instructor.all-student') }}" class="{{ @$navAllStudentActiveClass }}"><span class="iconify mr-15" data-icon="ph:student"></span>{{__('All Students')}}</a></li>
            <li><a href="{{ route('notice-board.course-notice.index') }}" class="{{ @$navNoticeBoardActiveClass }}"><span class="iconify mr-15" data-icon="ep:data-board"></span>{{__('Notice Board')}}</a></li>
            <li><a href="{{ route('live-class.course-live-class.index') }}" class="{{ @$navLiveClassActiveClass }}"><span class="iconify mr-15" data-icon="fluent:live-24-regular"></span>{{__('Live Class')}}</a></li>
            <li class="menu-has-children current-menu-item {{@$navConsultationActiveClass}}">
                <span class="toggle-account-menu">
                    <span class="iconify" data-icon="fontisto:angle-down"></span>
                </span>
                <a href="#" class="{{@$navConsultationActiveClass}}"><span class="iconify mr-15" data-icon="ic:round-support-agent"></span>{{ __('Consultation') }}</a>
                <ul class="account-sub-menu">
                    <li><a href="{{ route('instructor.consultation.dashboard') }}" class="{{@$subNavConsultationDashboardActiveClass}}">{{ __('Dashboard') }}</a></li>
                    <li><a href="{{ route('instructor.bookingRequest') }}" class="{{ @$subNavBookingRequestActiveClass }}">{{ __('Booking Request') }}</a></li>
                    <li><a href="{{ route('instructor.bookingHistory') }}" class="{{ @$subNavBookingHistoryActiveClass }}">{{ __('Booking History') }}</a></li>
                </ul>
            </li>
            @if(isAddonInstalled('LMSZAIPRODUCT'))
            <li class="menu-has-children current-menu-item {{@$navProductActiveClass}}">
                <span class="toggle-account-menu">
                    <span class="iconify" data-icon="fontisto:angle-down"></span>
                </span>
                <a href="#" class="{{@$navProductActiveClass}}"><span class="iconify mr-15" data-icon="solar:shop-outline"></span>{{ __('Manage Product') }}</a>
                <ul class="account-sub-menu">
                    <li><a href="{{ route('lms_product.instructor.product.dashboard') }}" class="{{@$subNavProductDashboardActiveClass}}">{{ __('Dashboard') }}</a></li>
                    <li><a href="{{ route('lms_product.instructor.product.my-product') }}" class="{{ @$subNavMyProductActiveClass }}">{{ __('My Product') }}</a></li>
                    <li><a href="{{ route('lms_product.instructor.product.upload', 'step-1') }}" class="{{ @$subNavUploadProductActiveClass }}">{{ __('Upload Products') }}</a></li>
                    <li><a href="{{ route('lms_product.instructor.product.orders') }}" class="{{ @$subNavOrderProductActiveClass }}">{{ __('Orders') }}</a></li>
                </ul>
            </li>
            @endif
            <li><a href="{{route('instructor.refund.index')}}" class="{{ @$navRefundActiveClass }}" ><span class="iconify mr-15" data-icon="gridicons:refund"></span>{{__('Refund List')}}</a></li>
            <li><a href="{{route('instructor.certificate.index')}}" class="{{ @$navCertificateActiveClass }}" ><span class="iconify mr-15" data-icon="fluent:certificate-20-regular"></span>{{__('Certificate')}}</a></li>
            
            <li><a href="{{route('discussion.index')}}" class="{{ @$navDiscussionActiveClass }}" ><span class="iconify mr-15" data-icon="octicon:comment-discussion-24"></span>{{__('Discussion')}}</a></li>
            <li><a href="{{route('instructor.chat.index')}}" class="{{ @$navChatActiveClass }}" ><span class="iconify mr-15" data-icon="ion-ios-chatboxes"></span>{{__('Chat')}}</a></li>
            <li><a href="{{route('finance.analysis.index')}}" class="{{ @$subNavAnalysisActiveClass }}" ><span class="iconify mr-15" data-icon="system-uicons:heart-rate"></span></span>{{__('Finance')}}</a></li>
            <li><a href="{{route('instructor.multi_instructor')}}" class="{{@$navInstructorRequestActiveClass}}" ><span class="iconify mr-15" data-icon="fluent:branch-request-20-regular"></span>{{__('Instructor Request')}}</a></li>
            <li><a href="{{ route('instructor.followings') }}" class="{{ @$navFollowingsActiveClass }}" ><span class="iconify mr-15" data-icon="fluent-mdl2:follow-user"></span>{{__('Followings')}}</a></li>
            <li><a href="{{ route('instructor.followers') }}" class="{{ @$navFollowersActiveClass }}" ><span class="iconify mr-15" data-icon="fluent-mdl2:user-followed"></span>{{__('Followers')}}</a></li>
            <li class="menu-has-children current-menu-item {{@$navProfileActiveClass}}">
                <span class="toggle-account-menu">
                    <span class="iconify" data-icon="fontisto:angle-down"></span>
                </span>
                <a href="#" class="{{@$navProfileActiveClass}}"><span class="iconify mr-15" data-icon="bx:bx-user"></span>{{__('Profile')}}</a>
                <ul class="account-sub-menu">
                    <li><a href="{{ route('instructor.profile') }}" class="{{ @$subNavProfileBasicActiveClass }}">{{__('Basic Information')}}</a></li>
                    <li><a href="{{ route('instructor.address') }}" class="{{ @$subNavProfileAddressActiveClass }}">{{__('Address & Location')}}</a></li>
                </ul>
            </li>
            <li><a href="{{route('instructor.zoom-setting.update')}}" class="{{ @$navZoomSettingActiveClass }}" ><span class="iconify mr-15" data-icon="fluent:meet-now-28-filled"></span>{{ __('Zoom Settings') }}</a></li>
            @if(get_option('gmeet_status'))
            <li><a href="{{route('instructor.gmeet_setting.update')}}" class="{{ @$navGmeetSettingActiveClass }}" ><span class="iconify mr-15" data-icon="fluent:meet-now-24-regular"></span>{{ __('Gmeet Settings') }}</a></li>
            @endif
        </ul>
    </nav>
</div>
