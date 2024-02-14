<div class="col-lg-3 p-0">
    <div class="student-profile-left-part">
        <h6>{{ @Auth::user()->name }}</h6>
        <ul>
            <li><a href="{{ route('student.profile') }}" class="font-medium font-15 {{active_if_full_match('student/profile')}}">{{__('Profile')}}</a></li>
            <li><a href="{{ route('student.address') }}" class="font-medium font-15 {{active_if_full_match('student/address')}}">{{__('Address & Location')}}</a></li>
            <li><a href="{{ route('student.change-password') }}" class="font-medium font-15 {{active_if_full_match('student/change-password')}}">{{__('Change Password')}}</a></li>
        </ul>
    </div>
</div>
