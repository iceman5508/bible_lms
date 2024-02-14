@extends('layouts.organization')

@section('breadcrumb')
    <div class="page-banner-content text-center">
        <h3 class="page-banner-heading text-white pb-15"> {{ __('Consultation') }} </h3>

        <!-- Breadcrumb Start-->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb justify-content-center">
                <li class="breadcrumb-item font-14"><a href="{{ route('organization.dashboard') }}">{{ __('Dashboard') }}</a>
                </li>
                <li class="breadcrumb-item font-14 active" aria-current="page">{{ __('Consultation') }}</li>
            </ol>
        </nav>
    </div>
@endsection

@section('content')
    <div class="instructor-profile-right-part">
        <div class="instructor-quiz-list-page instructor-consultation-list-page booking-history-page">

            <div class="instructor-my-courses-title d-flex justify-content-between align-items-center">
                <h6>{{ __('All Booking History') }}</h6>
            </div>

            <div class="row booking-history-tabs">
                <div class="col-12">
                    <ul class="nav nav-tabs assignment-nav-tabs live-class-list-nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link {{ @$upcomingActive }}" id="upcoming-tab" data-bs-toggle="tab"
                                data-bs-target="#upcoming" type="button" role="tab" aria-controls="upcoming"
                                aria-selected="true">{{ __('Upcoming') }}
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link {{ @$completedActive }}" id="past-tab" data-bs-toggle="tab"
                                data-bs-target="#past" type="button" role="tab" aria-controls="past"
                                aria-selected="false">{{ __('Completed') }}
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link {{ @$cancelledActive }}" id="past-tab" data-bs-toggle="tab"
                                data-bs-target="#cancelled" type="button" role="tab" aria-controls="cancelled"
                                aria-selected="false">{{ __('Cancelled') }}
                            </button>
                        </li>
                    </ul>

                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade {{ @$upcomingShowActive }}" id="upcoming" role="tabpanel"
                            aria-labelledby="upcoming-tab">
                            <div class="table-responsive">
                                <table class="table booking-history-upcoming-table">
                                    <thead>
                                        <tr>
                                            <th>{{ __('Student Name') }}</th>
                                            <th>{{ __('Date') }}</th>
                                            <th>{{ __('Time') }}</th>
                                            <th>{{ __('Hours') }}</th>
                                            <th>{{ __('Type') }}</th>
                                            <th>{{ __('View') }}</th>
                                            <th>{{ __('Action') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody class="accordion" id="accordionExample">
                                        @foreach ($bookingHistoryUpcoming as $upcoming)
                                            <!-- First Row Item Wrap End -->
                                            <tr>
                                                <td>
                                                    <div class="table-data-with-img d-flex align-items-center">
                                                        <div class="flex-shrink-0">
                                                            <div class="instructor-student-img-wrap radius-50">
                                                                <img src="{{ getImageFile(@$upcoming->user->image_path) }}"
                                                                    alt="img" class="img-fluid">
                                                            </div>
                                                        </div>
                                                        <div class="flex-grow-1 table-data-course-name">
                                                            {{ @$upcoming->user->student->name }}</div>
                                                    </div>
                                                </td>
                                                <td>{{ $upcoming->date }}</td>
                                                <td>{{ $upcoming->time }}</td>
                                                <td>{{ $upcoming->duration }}</td>
                                                <td>
                                                    @if ($upcoming->type == 1)
                                                        {{ __('In-person') }}
                                                    @elseif($upcoming->type == 2)
                                                        {{ __('Online') }}
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="booking-table-detail-btn quiz-details-action-btns">
                                                        <button data-meeting_host_name="{{ $upcoming->meeting_host_name }}"
                                                            data-id="{{ $upcoming->id }}" class="collapsed create_link"
                                                            type="button" data-bs-toggle="collapse"
                                                            data-bs-target="#collapseExample{{ $upcoming->id }}"
                                                            aria-expanded="false"
                                                            aria-controls="collapseExample{{ $upcoming->id }}"
                                                            title="See Details">
                                                            <span class="iconify" data-icon="fa6-solid:angle-down"></span>
                                                        </button>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="red-blue-action-btns">
                                                        <button type="button"
                                                            data-route="{{ route('organization.bookingStatus', [$upcoming->uuid, 3]) }}"
                                                            class="theme-btn theme-button1 default-hover-btn bookingStatus">{{ __('Completed') }}</button>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr class="show-details-content">
                                                <td class="p-0" colspan="7">
                                                    <div id="collapseExample{{ $upcoming->id }}"
                                                        class="booking-history-details accordion-collapse collapse"
                                                        aria-labelledby="collapseExample{{ $upcoming->id }}"
                                                        data-bs-parent="#accordionExample">
                                                        @if ($upcoming->type == 1)
                                                            <div
                                                                class="booking-history-details-wrap align-items-center p-20">
                                                                <div class="booking-history-left">
                                                                    <h6 class="font-15">{{ __('Student Details') }}</h6>
                                                                    <hr>
                                                                    <p>
                                                                    <h6 class="font-15 d-inline">{{ __('Name') }}</h6>:
                                                                    {{ @$upcoming->user->student->name }}</p>
                                                                    <p>
                                                                    <h6 class="font-15 d-inline">{{ __('Email') }}</h6>:
                                                                    {{ @$upcoming->user->email }}</p>
                                                                    <p>
                                                                    <h6 class="font-15 d-inline">{{ __('Phone Number') }}
                                                                    </h6>: {{ @$upcoming->user->student->phone_number }}
                                                                    </p>
                                                                </div>
                                                            </div>
                                                        @elseif($upcoming->type == 2)
                                                            <div
                                                                class="booking-history-details-wrap d-flex align-items-center p-20">
                                                                <div class="booking-history-left">
                                                                    <h6 class="font-15">{{ __('How to Join The Call?') }}
                                                                    </h6>
                                                                    <p>{{ __('If you want to join online call. You need to create link using below meeting host.') }}
                                                                    </p>
                                                                </div>
                                                            </div>

                                                            @if (@$upcoming->meeting_host_name)
                                                                <div
                                                                    class="booking-history-details-wrap d-flex align-items-center p-20">
                                                                    <div class="booking-history-left">
                                                                        @if (@$upcoming->meeting_host_name == 'zoom')
                                                                            <h6 class="font-15"><a
                                                                                    href="{{ $upcoming->start_url }}"
                                                                                    target="_blank"
                                                                                    class="theme-btn theme-button1 default-hover-btn green-theme-btn">{{ __('Start Meeting Now') }}</a>
                                                                            </h6>
                                                                        @elseif(@$upcoming->meeting_host_name == 'bbb')
                                                                            <h6 class="font-15"><a
                                                                                    href="{{ route('organization.consultation.join-bbb-meeting', $upcoming->id) }}"
                                                                                    target="_blank"
                                                                                    class="theme-btn theme-button1 default-hover-btn green-theme-btn">{{ __('Start Meeting Now') }}</a>
                                                                            </h6>
                                                                        @elseif(@$upcoming->meeting_host_name == 'jitsi')
                                                                            <h6 class="font-15"><a
                                                                                    href="{{ route('consultation.join-jitsi-meeting', $upcoming->uuid) }}"
                                                                                    target="_blank"
                                                                                    class="theme-btn theme-button1 default-hover-btn green-theme-btn">{{ __('Start Meeting Now') }}</a>
                                                                            </h6>
                                                                        @elseif(@$upcoming->meeting_host_name == 'gmeet')
                                                                        <h6 class="font-15"><a href="{{ $upcoming->join_url }}" target="_blank" class="theme-btn theme-button1 default-hover-btn green-theme-btn">{{ __('Start Meeting Now') }}</a></h6>
                                                                        @elseif(@$upcoming->meeting_host_name == 'agora')
                                                                        <h6 class="font-15"><a href="{{ $upcoming->join_url }}" target="_blank" class="theme-btn theme-button1 default-hover-btn green-theme-btn">{{ __('Start Meeting Now') }}</a></h6>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            @endif
                                                            <div class="align-items-center p-20">
                                                                <form
                                                                    action="{{ route('organization.bookingMeetingStore', $upcoming->uuid) }}"
                                                                    method="post">
                                                                    @csrf
                                                                    <input type="hidden" class="topic"
                                                                        value="consultation">
                                                                    <input type="hidden" class="date"
                                                                        value="{{ $upcoming->date }}">
                                                                    <input type="hidden" class="duration"
                                                                        value="60">
                                                                    <div class="row">
                                                                        <div class="col-md-12 mb-30">
                                                                            <label
                                                                                class="label-text-title color-heading font-medium font-16 mb-3">{{ __('Meeting Host Name') }}
                                                                            </label>
                                                                            <select name="meeting_host_name"
                                                                                data-id="{{ $upcoming->id }}"
                                                                                id="meeting_host_name_{{ $upcoming->id }}"
                                                                                class="form-select meeting_host_name"
                                                                                required>
                                                                                <option value="">
                                                                                    {{ __('Select Option') }}</option>
                                                                                @if (zoom_status() == 1)
                                                                                    <option value="zoom"
                                                                                        {{ @$upcoming->meeting_host_name == 'zoom' ? 'selected' : null }}>
                                                                                        {{ __('Zoom') }}</option>
                                                                                @endif
                                                                                @if (get_option('bbb_status') == 1)
                                                                                    <option value="bbb"
                                                                                        {{ @$upcoming->meeting_host_name == 'bbb' ? 'selected' : null }}>
                                                                                        {{ __('BigBlueButton') }}
                                                                                    </option>
                                                                                @endif
                                                                                @if (get_option('jitsi_status') == 1)
                                                                                    <option value="jitsi"
                                                                                        {{ @$upcoming->meeting_host_name == 'jitsi' ? 'selected' : null }}>
                                                                                        {{ __('Jitsi') }}</option>
                                                                                @endif
                                                                                @if(get_option('gmeet_status') == 1 && $gmeet)
                                                                                    <option value="gmeet" {{ @$upcoming->meeting_host_name == 'gmeet' ? 'selected' : null }}>{{ __('Gmeet') }}</option>
                                                                                @endif
                                                                                @if(get_option('agora_status') == 1)
                                                                                    <option value="agora" {{ @$upcoming->meeting_host_name == 'agora' ? 'selected' : null }}>{{ __('Agora') }}</option>
                                                                                @endif
                                                                            </select>

                                                                            @if ($errors->has('meeting_host_name'))
                                                                                <span class="text-danger"><i
                                                                                        class="fas fa-exclamation-triangle"></i>
                                                                                    {{ $errors->first('meeting_host_name') }}</span>
                                                                            @endif
                                                                        </div>
                                                                    </div>

                                                                    @if (zoom_status() == 1)
                                                                        <div class="row mb-30 d-none zoom_live_link_div">
                                                                            <div class="col">
                                                                                <label
                                                                                    class="label-text-title color-heading font-medium font-16 mb-3">{{ __('Zoom Live Class Link') }}</label>
                                                                                <div class="row align-items-center">
                                                                                    <div class="col-md-9">
                                                                                        <input type="text"
                                                                                            name="start_url"
                                                                                            class="form-control start_url"
                                                                                            id="zoom_start_url{{ $upcoming->id }}"
                                                                                            placeholder="{{ __('Generate your live class link') }}"
                                                                                            value="{{ @$upcoming->start_url }}">
                                                                                        @if ($errors->has('start_url'))
                                                                                            <span class="text-danger"><i
                                                                                                    class="fas fa-exclamation-triangle"></i>
                                                                                                {{ $errors->first('start_url') }}</span>
                                                                                        @endif
                                                                                    </div>
                                                                                    <div class="col">
                                                                                        <button type="button"
                                                                                            class="theme-btn theme-button1 default-hover-btn green-theme-btn createLiveLink">{{ __('Create Live Link') }}
                                                                                        </button>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="row align-items-center d-none">
                                                                                    <div class="col-md-9">
                                                                                        <input type="hidden"
                                                                                            name="join_url"
                                                                                            class="form-control join_url"
                                                                                            id="zoom_join_url{{ $upcoming->id }}"
                                                                                            placeholder="{{ __('Generate your live class link') }}"
                                                                                            value="{{ @$upcoming->join_url }}">
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    @endif
                                                                    @if (get_option('jitsi_status') == 1)
                                                                        <div class="row mb-30 d-none jitsi_live_link_div">
                                                                            <div class="col">
                                                                                <label
                                                                                    class="label-text-title color-heading font-medium font-16 mb-3">{{ __('Jitsi Meeting ID/Room') }}</label>
                                                                                <div class="row align-items-center">
                                                                                    <div class="col-md-9">
                                                                                        <input type="text"
                                                                                            name="jitsi_meeting_id"
                                                                                            class="form-control jitsi_meeting_id"
                                                                                            id="jitsi_meeting_id_{{ $upcoming->id }}"
                                                                                            placeholder="Type jitsi meeting id/room"
                                                                                            minlength="6"
                                                                                            value="{{ @$upcoming->meeting_id }}">
                                                                                        @if ($errors->has('jitsi_meeting_id'))
                                                                                            <span class="text-danger"><i
                                                                                                    class="fas fa-exclamation-triangle"></i>
                                                                                                {{ $errors->first('jitsi_meeting_id') }}</span>
                                                                                        @endif
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    @endif
                                                                    @if (get_option('bbb_status') == 1)
                                                                        <div class="d-none bbb_live_link_div">
                                                                            <div class="row mb-30">
                                                                                <div class="col">
                                                                                    <label
                                                                                        class="label-text-title color-heading font-medium font-16 mb-3">{{ __('Moderator Password') }}</label>
                                                                                    <div class="row align-items-center">
                                                                                        <div class="col-md-9">
                                                                                            <input type="text"
                                                                                                name="moderator_pw"
                                                                                                minlength="6"
                                                                                                class="form-control "
                                                                                                id="moderator_pw_{{ $upcoming->id }}"
                                                                                                placeholder="{{ __('Type moderator password (min length  6)') }}"
                                                                                                value="{{ @$upcoming->moderator_pw }}">
                                                                                            @if ($errors->has('moderator_pw'))
                                                                                                <span
                                                                                                    class="text-danger"><i
                                                                                                        class="fas fa-exclamation-triangle"></i>
                                                                                                    {{ $errors->first('moderator_pw') }}</span>
                                                                                            @endif
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="row mb-30">
                                                                                <div class="col">
                                                                                    <label
                                                                                        class="label-text-title color-heading font-medium font-16 mb-3">{{ __('Attendee Password') }}</label>
                                                                                    <div class="row align-items-center">
                                                                                        <div class="col-md-9">
                                                                                            <input type="text"
                                                                                                name="attendee_pw"
                                                                                                minlength="6"
                                                                                                class="form-control"
                                                                                                id="attendee_pw_{{ $upcoming->id }}"
                                                                                                placeholder="{{ __('Type attendee password (min length  6)') }}"
                                                                                                value="{{ @$upcoming->attendee_pw }}">
                                                                                            @if ($errors->has('attendee_pw'))
                                                                                                <span
                                                                                                    class="text-danger"><i
                                                                                                        class="fas fa-exclamation-triangle"></i>
                                                                                                    {{ $errors->first('attendee_pw') }}</span>
                                                                                            @endif
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    @endif

                                                                    <div>
                                                                        <button type="submit"
                                                                            class="theme-btn theme-button1 default-hover-btn">{{ __('Save Meeting') }}</button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                            <!-- First Row Item Wrap End -->
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <!-- Pagination Start -->
                            @if (@$bookingHistoryUpcoming->hasPages())
                                {{ @$bookingHistoryUpcoming->links('frontend.paginate.paginate') }}
                            @endif
                            <!-- Pagination End -->
                        </div>
                        <div class="tab-pane fade {{ @$completedShowActive }}" id="past" role="tabpanel"
                            aria-labelledby="past-tab">
                            <div class="table-responsive">
                                <table class="table booking-history-past-table">
                                    <thead>
                                        <tr>
                                            <th>{{ __('Student Name') }}</th>
                                            <th>{{ __('Date') }}</th>
                                            <th>{{ __('Time') }}</th>
                                            <th>{{ __('Hours') }}</th>
                                            <th>{{ __('Type') }}</th>
                                            <th>{{ __('Status') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($bookingHistoryCompleted as $completed)
                                            <tr>
                                                <td>
                                                    <div class="table-data-with-img d-flex align-items-center">
                                                        <div class="flex-shrink-0">
                                                            <div class="instructor-student-img-wrap radius-50">
                                                                <img src="{{ getImageFile(@$completed->user->image_path) }}"
                                                                    alt="img" class="img-fluid">
                                                            </div>
                                                        </div>
                                                        <div class="flex-grow-1 table-data-course-name">
                                                            {{ @$completed->user->student->name }}</div>
                                                    </div>
                                                </td>
                                                <td>{{ $completed->date }}</td>
                                                <td>{{ $completed->time }}</td>
                                                <td>{{ $completed->duration }}</td>
                                                <td>
                                                    @if ($completed->type == 1)
                                                        In-person
                                                    @elseif($completed->type == 2)
                                                        Online
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($completed->status == 2)
                                                        <span class="status blocked">{{ __('Cancel') }}</span>
                                                    @elseif($completed->status == 3)
                                                        <span class="status active">{{ __('Completed') }}</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <!-- Pagination Start -->
                            @if (@$bookingHistoryCompleted->hasPages())
                                {{ @$bookingHistoryCompleted->links('frontend.paginate.paginate') }}
                            @endif
                            <!-- Pagination End -->
                        </div>
                        <div class="tab-pane fade {{ @$cancelledShowActive }}" id="cancelled" role="tabpanel"
                            aria-labelledby="cancelled-tab">
                            <div class="table-responsive">
                                <table class="table booking-history-past-table">
                                    <thead>
                                        <tr>
                                            <th>{{ __('Student Name') }}</th>
                                            <th>{{ __('Date') }}</th>
                                            <th>{{ __('Time') }}</th>
                                            <th>{{ __('Hours') }}</th>
                                            <th>{{ __('Type') }}</th>
                                            <th>{{ __('Status') }}</th>
                                            <th>{{ __('View') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($bookingHistoryCancelled as $cancelled)
                                            <tr>
                                                <td>
                                                    <div class="table-data-with-img d-flex align-items-center">
                                                        <div class="flex-shrink-0">
                                                            <div class="instructor-student-img-wrap radius-50">
                                                                <img src="{{ getImageFile(@$cancelled->user->image_path) }}"
                                                                    alt="img" class="img-fluid">
                                                            </div>
                                                        </div>
                                                        <div class="flex-grow-1 table-data-course-name">
                                                            {{ @$cancelled->user->student->name }}</div>
                                                    </div>
                                                </td>
                                                <td>{{ $cancelled->date }}</td>
                                                <td>{{ $cancelled->time }}</td>
                                                <td>{{ $cancelled->duration }}</td>
                                                <td>
                                                    @if ($cancelled->type == 1)
                                                        {{ __('In-person') }}
                                                    @elseif($cancelled->type == 2)
                                                        {{ __('Online') }}
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($cancelled->status == 2)
                                                        <span class="status blocked">{{ __('Cancel') }}</span>
                                                    @elseif($cancelled->status == 3)
                                                        <span class="status active">{{ __('Completed') }}</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="booking-table-detail-btn quiz-details-action-btns">
                                                        <button class="collapsed" type="button"
                                                            data-bs-toggle="collapse"
                                                            data-bs-target="#collapseExample{{ $cancelled->id }}"
                                                            aria-expanded="false" aria-controls="collapseExample1"
                                                            title="See Details">
                                                            <span class="iconify" data-icon="fa6-solid:angle-down"></span>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr class="show-details-content">
                                                <td class="p-0" colspan="7">
                                                    <div id="collapseExample{{ $cancelled->id }}"
                                                        class="booking-history-details accordion-collapse collapse"
                                                        aria-labelledby="collapseExample{{ $cancelled->id }}"
                                                        data-bs-parent="#accordionExample">
                                                        <div
                                                            class="booking-history-details-wrap d-flex align-items-center p-20">
                                                            <div class="booking-history-left">
                                                                <h6 class="font-15">{{ __('Cancel Reason?') }}</h6>
                                                                <p>{{ $cancelled->cancel_reason }}</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <!-- Pagination Start -->
                            @if (@$bookingHistoryCancelled->hasPages())
                                {{ @$bookingHistoryCancelled->links('frontend.paginate.paginate') }}
                            @endif
                            <!-- Pagination End -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <input type="hidden" class="getZoomLinkRoute" value="{{ route('live-class.get-zoom-link') }}">
@endsection

@push('script')
    <script src="{{ asset('frontend/assets/js/instructor/create-live-class-zoom-link.js') }}"></script>
    <script>
        "use strict"
        $(document).on('click', '.create_link', function() {
            var meeting_host_name = $(this).data('meeting_host_name')
            var id = $(this).data('id')
            $('.zoom_live_link_div').addClass('d-none')
            $('.jitsi_live_link_div').addClass('d-none')
            $('.bbb_live_link_div').addClass('d-none')

            meetingHost(meeting_host_name, id)
        });

        function meetingHost(meeting_host_name, id) {
            if (meeting_host_name == 'zoom') {
                $('.zoom_live_link_div').removeClass('d-none')
                $('.bbb_live_link_div').addClass('d-none')
                $('.jitsi_live_link_div').addClass('d-none')

                $("#zoom_start_url" + id).attr("required", true);
                $('#moderator_pw_' + id).removeAttr('required');
                $('#attendee_pw_' + id).removeAttr('required');
                $('#jitsi_meeting_id_' + id).removeAttr('required');
            }

            if (meeting_host_name == 'bbb') {
                $('.bbb_live_link_div').removeClass('d-none')
                $('.zoom_live_link_div').addClass('d-none')
                $('.jitsi_live_link_div').addClass('d-none')

                $('#jitsi_meeting_id_' + id).removeAttr('required');
                $('#zoom_start_url' + id).removeAttr('required');
                $("#moderator_pw_" + id).attr("required", true);
                $("#attendee_pw_" + id).attr("required", true);
            }

            if (meeting_host_name == 'jitsi') {
                $('.jitsi_live_link_div').removeClass('d-none')
                $('.bbb_live_link_div').addClass('d-none')
                $('.zoom_live_link_div').addClass('d-none')

                $("#zoom_start_url" + id).removeAttr('required');
                $('#moderator_pw_' + id).removeAttr('required');
                $('#attendee_pw_' + id).removeAttr('required');
                $("#jitsi_meeting_id_" + id).attr("required", true);
            }

             
            if (meeting_host_name == 'gmeet' || meeting_host_name == 'agora') {
                $('.jitsi_live_link_div').addClass('d-none')
                $('.bbb_live_link_div').addClass('d-none')
                $('.zoom_live_link_div').addClass('d-none')

                $("#zoom_start_url" + id).removeAttr('required');
                $('#moderator_pw_' + id).removeAttr('required');
                $('#attendee_pw_' + id).removeAttr('required');
                $("#jitsi_meeting_id_" + id).removeAttr("required", true);
            }
        }

        $('.meeting_host_name').change(function() {
            var meeting_host_name = this.value
            var id = $(this).data('id')
            meetingHost(meeting_host_name, id)
        })

        $('.bookingStatus').click(function() {
            Swal.fire({
                title: "{{ __('Are you sure?') }}",
                text: "{{ __('You won`t be able to revert this!') }}",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, change it!'
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        type: 'GET',
                        url: $(this).data("route"),
                        success: function(response) {
                            location.reload();
                        }
                    })
                }
            })
        });
    </script>
@endpush
