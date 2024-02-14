@extends('layouts.organization')
@section('breadcrumb')
    <div class="page-banner-content text-center">
        <h3 class="page-banner-heading text-white pb-15">{{ __('Chat') }}</h3>

        <!-- Breadcrumb Start-->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb justify-content-center">
                <li class="breadcrumb-item font-14"><a href="{{ route('organization.dashboard') }}">{{ __('Dashboard') }}</a>
                </li>
                <li class="breadcrumb-item font-14 active" aria-current="page">{{ __('Chat') }}</li>
            </ol>
        </nav>
    </div>
@endsection
@section('content')
    <div class="instructor-profile-right-part">
        <div class="instructor-quiz-list-page instructor-discussion-page">
            <div class="instructor-my-courses-title d-flex justify-content-between align-items-center">
                <h6>{{ __('Chat Box') }}</h6>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <input id="senderRole" type="number" hidden value="{{ auth()->user()->role }}">
                    <input id="senderId" type="number" hidden value="{{ auth()->user()->id }}">
                    <input type="hidden" id="pusherKey" value="{{ get_option('pusher_app_key') }}">
                    <input type="hidden" id="pusherCluster" value="{{ get_option('pusher_app_cluster') }}">
                    <input type="hidden" id="chatMessage" value="{{ route('organization.chat.messages') }}">
                    <input type="hidden" id="chatSend" value="{{ route('organization.chat.send') }}">
                    {{-- Student list --}}
                    <div class="my-3">
                        <p class="mb-4 fw-bold">{{ __('Students with enrolled courses') }}</p>
                        <div class="chat-users scroll-bar">
                            <ul class="course-list">
                                @if (!empty($data))
                                    @foreach ($data as $index => $item)
                                        <li class="course-user">
                                            <img src="{{ getImageFile($item[0]->user_image) }}" alt="image">
                                            <span class="fw-bold me-1 user-name"> {{ $item[0]->user_name }}:
                                            </span>
                                            @foreach ($item as $course)
                                                <span
                                                    class="course-title m-2 course-{{ $course->course_id }}-{{ $course->user_id }}"
                                                    data-sender-id={{ Auth::user()->id }} data-receiver-id={{
                                                        $course->user_id }} data-course-id={{ $course->course_id }}
                                                    onclick="getMessages('{{ Auth::user()->id }}',
                                                '{{ $course->user_id }}','{{ $course->course_id }}')">
                                                    <span class="iconify text-warning notification-icon d-none"
                                                        data-icon="bx:bx-bell"></span>
                                                    <span class="unseen-count me-2"
                                                        id="unseen-{{ $course->user_id }}-{{ $course->course_id }}"></span>
                                                    <span class="title"> {{ $course->course_title }}</span>
                                                </span>
                                            @endforeach
                                        </li>
                                    @endforeach
                                @endif
                            </ul>
                        </div>
                    </div>
                </div>
                {{-- message box area --}}
                <div class="col-md-7">
                    <div class="chat-box">
                        <input type="number" id="receiverId" hidden>
                        <input type="number" id="courseId" hidden>
                        <div class="selected-user d-flex align-items-center">
                            <div id="chat-user-image"></div>
                            <p class="fw-bold" id="chat-user-name"></p>
                            <small class="ms-3" id="course-title"></small>
                        </div>
                        <div id="chat-error"></div>
                        <div class="scroll-bar message-area" id="messages-container"></div>
                        <div>
                            <form class="d-flex align-items-center px-4" id="chat-send">
                                <input type="text" placeholder="enter your text" id="chat-message" class="form-control">
                                <button type="submit" id="chat-send"
                                    class="d-flex ms-3 my-3 align-items-center btn btn-success p-2">
                                    <span>send</span>
                                    <i class="ri-send-plane-line ms-1"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                    <div id="select-user"></div>
                </div>
            </div>

        </div>
    </div>
@endsection
@push('style')
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/chat/chat.css') }}">
@endpush
@push('script')
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>
    <script src="{{ asset('frontend/assets/js/chat.js') }}"></script>
    @if(get_option('broadcast_default', 0) == 'pusher')
    <script src="{{ asset('frontend/assets/js/pusher.min.js') }}"></script>
    <script>
        const pusherAppKey = $('#pusherKey').val();
        const pusherAppCluster = $('#pusherCluster').val();
        const pusher = new Pusher(pusherAppKey, {
            cluster: pusherAppCluster
        });
        const channel = pusher.subscribe('my-channel');
            channel.bind('my-event', function (res) {
                unseenCount(res.data.senderId, res.data.courseId);
                getPusherMessages(res.data.senderId, res.data.receiverId, res.data.courseId, res.data.message)
            });
    </script>
    @else
    <script>
        setInterval(() => {
                let course_id = $('.course-title.active').data('course-id');
                let receiver_id = $('.course-title.active').data('receiver-id');
                let sender_id = $('.course-title.active').data('sender-id');
                if(typeof sender_id != 'undefined' && typeof receiver_id != 'undefined' && typeof course_id != 'undefined'){
                    getMessages(sender_id, receiver_id, course_id);
                }
            }, 20000);
    </script>
    @endif
@endpush
