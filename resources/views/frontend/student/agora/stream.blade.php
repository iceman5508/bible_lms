<div id="stream-player" class="player stream-player flex-grow-1 position-relative">
    {{-- @if($notStarted)
        <div id="notStartedAlert" class="no-result default-no-result d-flex align-items-center justify-content-center flex-column w-100 h-100">
            <div class="d-flex align-items-center flex-column mt-30 text-center">
                <h2 class="text-dark-blue">{{ __('This session is not live yet') }}</h2>
            </div>
        </div>
    @else
        <div class="agora-stream-loading">
            <p class="mt-10">{{ __('Please wait to join the host and enter you') }}</p>
        </div>
    @endif --}}

    <div id="remote-stream-player" class="remote-stream-box"></div>
</div>

<!-- Single button -->
<div class="align-items-center bg-white d-flex justify-content-around mt-0 pt-2 mt-15 px-15 px-lg-30 py-20 stream-footer">

    {{-- @if($sessionStreamType == 'multiple') --}}
    <button type="button" id="microphoneEffect" class="stream-bottom-actions btn-transparent d-flex flex-column align-items-center active">
        <span class="icon">
            <i data-feather="mic" width="24" height="24" class=""></i>
        </span>

        <span class="mt-1 text-gray font-14">{{ __('Microphone') }}</span>
    </button>


    <button type="button" id="cameraEffect" class="stream-bottom-actions btn-transparent d-flex flex-column align-items-center active">
        <span class="icon">
            <i data-feather="video" width="24" height="24" class=""></i>
        </span>

        <span class="mt-1 text-gray font-14">{{ __("Camera") }}</span>
    </button>
    {{-- @endif --}}

    <div class="stream-bottom-actions d-flex flex-column align-items-center">
        <i data-feather="clock" width="24" height="24" class=""></i>
        <span id="streamTimer" class="mt-1 font-14 text-gray d-flex align-items-center justify-content-center">
            <span class="d-flex align-items-center justify-content-center text-dark time-item hours">00</span>:
            <span class="d-flex align-items-center justify-content-center text-dark time-item minutes">00</span>:
            <span class="d-flex align-items-center justify-content-center text-dark time-item seconds">00</span>
        </span>
    </div>

    @if($isHost)
        <button type="button" id="shareScreen" class="stream-bottom-actions btn-transparent d-flex flex-column align-items-center ">
            <i data-feather="airplay" width="24" height="24" class=""></i>
            <span class="mt-1 text-gray font-14">{{ __("Share Screen") }}</span>
        </button>

        <button type="button" id="endShareScreen" class="stream-bottom-actions btn-transparent flex-column align-items-center dont-join-users d-none">
            <div class="icon-box">
                <i data-feather="airplay" width="24" height="24" class=""></i>
            </div>
            <span class="mt-1 text-gray font-14">{{ __("End Share Screen") }}</span>
        </button>

        <button type="button" class="stream-bottom-actions btn-transparent d-flex flex-column align-items-center text-danger" data-bs-toggle="modal" data-bs-target="#leaveModal">
            <i data-feather="x-square" width="24" height="24" class=" "></i>
            <span class="mt-1 font-14">{{ __("End Session") }}</span>
        </button>

        <div class="modal fade" id="leaveModal" tabindex="-1" aria-labelledby="leaveModal" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="leaveModalLabel">{{ __("End the session") }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body ">
                        <p class="">{{ __('Are you sure to leave the live session') }}</p>
                    </div>
                    <div class="modal-footer d-flex justify-content-between align-items-center">
                        <button type="button" class="theme-btn theme-button3 modal-back-btn" data-bs-dismiss="modal">{{ __('Close') }}</button>
                        <button type="submit" class="theme-btn theme-button1 default-hover-btn" id="leave">{{ __('Yes') }}</button>
                    </div>
                </div>
            </div>
        </div>
    @endif

</div>

@push('script')
    <script>
        var rtcToken = '{{ $rtcToken }}';
        var joinIsActiveLang = '{{ trans('update.join_is_active') }}';
        var joiningIsDisabledLang = '{{ trans('update.joining_is_disabled') }}';
        var notStarted = false;
        @if($notStarted) notStarted = true @endif

    </script>

@endpush

