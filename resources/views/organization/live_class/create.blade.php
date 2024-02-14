@extends('layouts.organization')

@section('breadcrumb')
    <div class="page-banner-content text-center">
        <h3 class="page-banner-heading text-white pb-15"> {{__('My Courses')}} </h3>

        <!-- Breadcrumb Start-->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb justify-content-center">
                <li class="breadcrumb-item font-14"><a href="{{route('organization.dashboard')}}">{{__('Dashboard')}}</a></li>
                <li class="breadcrumb-item font-14"><a href="{{ route('organization.live-class.course-live-class.index') }}">{{ __('Live Class Course List') }}</a></li>
                <li class="breadcrumb-item font-14 active" aria-current="page">{{ __('Create Live') }}</li>
            </ol>
        </nav>
    </div>
@endsection

@section('content')
    <div class="instructor-profile-right-part">
        <div class="instructor-quiz-list-page instructor-create-live-class-page">
            <div class="instructor-my-courses-title d-flex justify-content-between align-items-center">
                <h6>{{ __('Create Live Class') }}</h6>
            </div>

            <div class="row">
                <div class="col-12">
                    <form action="{{ route('organization.live-class.store', $course->uuid) }}" method="post">
                        @csrf
                        <div class="row mb-30">
                            <div class="col-md-12">
                                <label class="label-text-title color-heading font-medium font-16 mb-3">{{ __('Live Class Topic') }}</label>
                                <input type="text" name="class_topic" class="form-control topic" placeholder="Enter your topic" required value="{{ old('class_topic') }}">
                                @if ($errors->has('class_topic'))
                                    <span class="text-danger"><i class="fas fa-exclamation-triangle"></i> {{ $errors->first('class_topic') }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="row mb-30">
                            <div class="col-md-12">
                                <label class="label-text-title color-heading font-medium font-16 mb-3">{{ __('Live Class Date') }}</label>
                                <input type="datetime-local" name="date" class="form-control date" placeholder="Selected Date" required>
                                @if ($errors->has('date'))
                                    <span class="text-danger"><i class="fas fa-exclamation-triangle"></i> {{ $errors->first('date') }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="row mb-30">
                            <div class="col-md-12">
                                <label class="label-text-title color-heading font-medium font-16 mb-3">{{ __('Time Duration (Write minutes)') }}</label>
                                <input type="number" name="duration" class="form-control duration" placeholder="Type duration in minutes" required>
                                @if ($errors->has('duration'))
                                    <span class="text-danger"><i class="fas fa-exclamation-triangle"></i> {{ $errors->first('duration') }}</span>
                                @endif
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12 mb-30">
                                <label class="label-text-title color-heading font-medium font-16 mb-3">{{ __('Meeting Host Name') }}
                                    <span class="cursor tooltip-show-btn share-referral-big-btn primary-btn get-referral-btn border-0" data-toggle="popover"  data-bs-placement="bottom" data-bs-content="Meridian sun strikes upper urface of the impenetrable foliage of my trees">
                                        !
                                    </span>
                                </label>
                                <select name="meeting_host_name" id="meeting_host_name" class="form-select" required>
                                    <option value="">{{ __('Select Option') }}</option>
                                    @if(zoom_status() == 1) <option value="zoom">Zoom</option> @endif
                                    @if(get_option('bbb_status') == 1) <option value="bbb">BigBlueButton</option> @endif
                                    @if(get_option('jitsi_status') == 1) <option value="jitsi">Jitsi</option> @endif
                                    @if(get_option('gmeet_status') == 1 && $gmeet) <option value="gmeet">Google Meet</option> @endif
                                    @if(get_option('agora_status') == 1) <option value="agora">Agora In App Live</option> @endif
                                </select>

                                @if ($errors->has('meeting_host_name'))
                                    <span class="text-danger"><i class="fas fa-exclamation-triangle"></i> {{ $errors->first('meeting_host_name') }}</span>
                                @endif
                            </div>
                        </div>

                        @if(zoom_status() == 1)
                        <div class="row mb-30 d-none zoom_live_link_div">
                            <div class="col">
                                <label class="label-text-title color-heading font-medium font-16 mb-3">{{ __('Zoom Live Class Link') }}</label>
                                <div class="row align-items-center">
                                    <div class="col-md-9">
                                        <input type="text" name="start_url" class="form-control start_url" id="zoom_start_url" placeholder="Generate your live class link"
                                               value="{{ old('start_url') }}">
                                        @if ($errors->has('start_url'))
                                            <span class="text-danger"><i class="fas fa-exclamation-triangle"></i> {{ $errors->first('start_url') }}</span>
                                        @endif
                                    </div>
                                    <div class="col">
                                        <button type="button" class="theme-btn theme-button1 default-hover-btn green-theme-btn createLiveLink">{{ __('Create Live Link') }}</button>
                                    </div>
                                </div>
                                <div class="row align-items-center d-none">
                                    <div class="col-md-9">
                                        <input type="hidden" name="join_url" class="form-control join_url" id="zoom_join_url" placeholder="Generate your live class link"
                                               value="{{ old('join_url') }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                        @if(get_option('jitsi_status') == 1)
                        <div class="row mb-30 d-none jitsi_live_link_div">
                            <div class="col">
                                <label class="label-text-title color-heading font-medium font-16 mb-3">{{ __('Jitsi Meeting ID/Room') }}</label>
                                <div class="row align-items-center">
                                    <div class="col-md-9">
                                        <input type="text" name="jitsi_meeting_id" class="form-control" id="jitsi_meeting_id" placeholder="Type jitsi meeting id/room" minlength="6"
                                               value="{{ old('jitsi_meeting_id') }}">
                                        @if ($errors->has('jitsi_meeting_id'))
                                            <span class="text-danger"><i class="fas fa-exclamation-triangle"></i> {{ $errors->first('jitsi_meeting_id') }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif

                        @if(get_option('bbb_status') == 1)
                            <div class="d-none bbb_live_link_div">
                            <div class="row mb-30">
                                <div class="col">
                                    <label class="label-text-title color-heading font-medium font-16 mb-3">{{ __('Moderator Password') }}</label>
                                    <div class="row align-items-center">
                                        <div class="col-md-9">
                                            <input type="text" name="moderator_pw" minlength="6" class="form-control " id="moderator_pw" placeholder="Type moderator password (min length  6)" value="{{ old('moderator_pw') }}">
                                            @if ($errors->has('moderator_pw'))
                                                <span class="text-danger"><i class="fas fa-exclamation-triangle"></i> {{ $errors->first('moderator_pw') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-30">
                                <div class="col">
                                    <label class="label-text-title color-heading font-medium font-16 mb-3">{{ __('Attendee Password') }}</label>
                                    <div class="row align-items-center">
                                        <div class="col-md-9">
                                            <input type="text" name="attendee_pw" minlength="6" class="form-control" id="attendee_pw" placeholder="Type attendee password (min length  6)" value="{{ old('attendee_pw') }}">
                                            @if ($errors->has('attendee_pw'))
                                                <span class="text-danger"><i class="fas fa-exclamation-triangle"></i> {{ $errors->first('attendee_pw') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            </div>
                        @endif

                        <div>
                            <a href="{{ route('organization.live-class.index', $course->uuid) }}" class="theme-btn theme-button3 quiz-back-btn default-hover-btn">{{ __('Back') }}</a>
                            <button type="submit" class="theme-btn theme-button1 default-hover-btn">{{ __('Create Meeting') }}</button>
                        </div>
                    </form>

                </div>
            </div>

        </div>
    </div>

    <input type="hidden" class="getZoomLinkRoute" value="{{ route('organization.live-class.get-zoom-link') }}">
@endsection

@push('script')
    <script src="{{ asset('frontend/assets/js/instructor/create-live-class-zoom-link.js') }}"></script>
    <script>
        "use strict"
        $('#meeting_host_name').change(function(){
            var meeting_host_name = this.value
            if (meeting_host_name == 'zoom')
            {
                $('.zoom_live_link_div').removeClass('d-none')
                $('.bbb_live_link_div').addClass('d-none')
                $('.jitsi_live_link_div').addClass('d-none')

                $("#zoom_start_url").attr("required", true);
                $('#moderator_pw').removeAttr('required');
                $('#attendee_pw').removeAttr('required');
                $('#jitsi_meeting_id').removeAttr('required');
            }

            else if (meeting_host_name == 'bbb')
            {
                $('.bbb_live_link_div').removeClass('d-none')
                $('.zoom_live_link_div').addClass('d-none')
                $('.jitsi_live_link_div').addClass('d-none')

                $('#jitsi_meeting_id').removeAttr('required');
                $('#zoom_start_url').removeAttr('required');
                $("#moderator_pw").attr("required", true);
                $("#attendee_pw").attr("required", true);
            }

            else if (meeting_host_name == 'jitsi')
            {
                $('.jitsi_live_link_div').removeClass('d-none')
                $('.bbb_live_link_div').addClass('d-none')
                $('.zoom_live_link_div').addClass('d-none')

                $("#zoom_start_url").removeAttr('required');
                $('#moderator_pw').removeAttr('required');
                $('#attendee_pw').removeAttr('required');
                $("#jitsi_meeting_id").attr("required", true);
            }else{
                $('.jitsi_live_link_div').addClass('d-none')
                $('.bbb_live_link_div').addClass('d-none')
                $('.zoom_live_link_div').addClass('d-none')

                $("#zoom_start_url").removeAttr('required');
                $('#moderator_pw').removeAttr('required');
                $('#attendee_pw').removeAttr('required');
                $("#jitsi_meeting_id").removeAttr("required");
            }
        })
    </script>
@endpush


