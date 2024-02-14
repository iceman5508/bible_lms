@extends('frontend.layouts.app')

@push('style')
    <link rel="stylesheet" href="{{ asset("frontend/agora/agora.css") }}"/>
@endpush

@section('content')
   <!-- Page Header Start -->
<header class="page-banner-header gradient-bg position-relative">
    <div class="section-overlay">
        <div class="container">
            <div class="row">
                <div class="col-12 col-sm-12 col-md-12">
                    <div class="page-banner-content text-center">
                        <h3 class="page-banner-heading text-white pb-15">{{ __($channelName) }}</h3>

                        <!-- Breadcrumb Start-->
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb justify-content-center">
                                <li class="breadcrumb-item font-14"><a href="{{ url('/') }}">{{__('Home')}}</a></li>
                                <li class="breadcrumb-item font-14 active" aria-current="page">{{ __($channelName) }}</li>
                            </ol>
                        </nav>
                        <!-- Breadcrumb End-->
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
<!-- Page Header End -->

<!-- Gallery Area Start -->
<section class="our-gallery-area pt-30">
    <div class="container">
        <div class="row">
            <div class="bg-light col-md-12 p-2">
                <div class="agora-stream flex-grow-1 bg-info-light p-15">
                    @include('frontend.student.agora.stream')
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('script')
    <script>
        var joinedToChannel = '{{ __("Joined to this session") }}';
        var appId = '{{ $appId }}';
        var accountName = '{{ $accountName }}';
        var channelName = '{{ $channelName }}';
        var streamRole = '{{ $streamRole }}';
        var redirectAfterLeave = '{{ route("main.index") }}';
        var liveEndedLang = '{{ __("This live has been End") }}';
        var redirectToPanelInAFewMomentLang = '{{ __("Wait a few moments to redirect") }}';
        var streamStartAt = Number("{{ $streamStartAt }}");
        var sessionId = Number(1);
        var sessionStreamType = '{{ $sessionStreamType }}';
        var authUserId = Number({{ $authUserId }});
        var hostUserId = Number({{ $hostUserId }});
        var getUserIfoRoute = "{{ route('get-user-info') }}"
    </script>

<script src="{{ asset("frontend/agora/time-counter-down.min.js") }}"></script>

<script src="{{ asset("frontend/agora/AgoraRTC_N.js") }}"></script>
<script src="{{ asset("frontend/agora/stream.min.js") }}"></script>

@endpush
