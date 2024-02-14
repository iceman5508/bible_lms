@extends('frontend.layouts.app')

@section('content')

    <div class="bg-page">
        <!-- Course Single Page Header Start -->
        <header class="page-banner-header gradient-bg position-relative">
            <div class="section-overlay">
                <div class="container">
                    <div class="row">
                        <div class="col-12 col-sm-12 col-md-12">
                            <div class="page-banner-content text-center">
                                <h3 class="page-banner-heading text-white pb-15">{{ __('Support Tickets') }}</h3>

                                <!-- Breadcrumb Start-->
                                <nav aria-label="breadcrumb">
                                    <ol class="breadcrumb justify-content-center">
                                        <li class="breadcrumb-item font-14"><a href="{{ url('/') }}">{{ __('Home') }}</a></li>
                                        <li class="breadcrumb-item font-14"><a href="{{ url('/') }}">{{ __('Support Tickets') }}</a></li>
                                        <li class="breadcrumb-item font-14"><a href="{{ route('student.support-ticket.create') }}">{{ __('View Ticket') }}</a></li>
                                        <li class="breadcrumb-item font-14 active" aria-current="page">{{ __('Ticket Details') }}</li>
                                    </ol>
                                </nav>
                                <!-- Breadcrumb End-->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>
        <!-- Course Single Page Header End -->

        <!-- Ticket Details Start -->
        <section class="ticket-details-page section-t-space">
            <div class="container">
                <div class="instructor-ticket-content-wrap bg-white p-30 radius-8">

                    <div class="row">
                        <div class="col-12">

                            <div class="instructor-my-courses-title d-flex justify-content-between align-items-center">
                                <h6>{{ __('Ticket Details') }}</h6>
                            </div>

                            <div class="row">
                                <div class="col-12 col-md-12 col-lg-8 col-xl-8">
                                    <div class="ticket-details-left-part">
                                        <div class="ticket-replies-wrap mb-20 ticket-details-box radius-4 p-20">

                                            <div class="ticket-details-box-title">
                                                <h6 class="font-16 font-medium mb-20">{{ __('Ticket Replies') }}</h6>
                                            </div>


                                            @forelse($ticketMessages as $ticketMessage)
                                                <div class="ticket-reply-item
                                                    @if($ticketMessage->reply_admin_user_id) ticket-reply-item-staff @elseif($ticketMessage->sender_user_id) ticket-reply-item-student @endif">
                                                    <h6 class="font-16 font-medium mb-2">
                                                        @if($ticketMessage->sender_user_id)
                                                            {{ @$ticketMessage->sendUser->name }}
                                                        @elseif($ticketMessage->reply_admin_user_id)
                                                            {{ @$ticketMessage->replyUser->name }} (Admin)
                                                        @endif
                                                    </h6>
                                                    <div class="ticket-reply-content">
                                                        <p>{{ $ticketMessage->message }}<br>
                                                        </p>
                                                    </div>
                                                </div>
                                            @empty
                                                <p>{{ __('No Reply Found!') }}</p>
                                            @endforelse

                                        </div>

                                        <div class="ticket-details-reply-form-box ticket-details-box radius-4 p-20">

                                            <div class="ticket-details-box-title">
                                                <h6 class="font-16 font-medium mb-20">{{ __('Write a reply') }}</h6>
                                            </div>

                                            <form action="{{ route('student.support-ticket.message.store') }}" method="post" enctype="multipart/form-data">
                                                @csrf
                                                <input type="hidden" name="ticket_id" value="{{ $ticket->id }}">
                                                <div class="row">
                                                    <div class="col-md-12 mb-30">
                                                        <textarea class="form-control" name="message" cols="30" rows="6" placeholder="Write your message" required></textarea>
                                                        @if ($errors->has('message'))
                                                            <span class="text-danger"><i class="fas fa-exclamation-triangle"></i> {{ $errors->first('message') }}</span>
                                                        @endif
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-12 mb-15">
                                                        <div class="">
                                                            <div class="label-text-title color-heading font-medium font-16 mb-3">{{ __('Upload Your File') }}
                                                            </div>
                                                            <input type="file" name="file">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12 mb-20">
                                                        @if ($errors->has('file'))
                                                            <span class="text-danger"><i class="fas fa-exclamation-triangle"></i> {{ $errors->first('file') }}</span>
                                                        @endif
                                                        <p class="font-14 placeholder-color">{{ __('Valid file type') }} : jpg, jpeg, gif, png and File size max : 10 MB</p>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-12 mb-30 create-tickets-btns">
                                                        <button type="submit" class="theme-btn theme-button1 default-hover-btn">{{ __('Submit') }}</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>

                                    </div>
                                </div>
                                <div class="col-12 col-md-12 col-lg-4 col-xl-4">
                                    <div class="ticket-details-right-part ticket-details-box radius-4 p-20">

                                        <div class="ticket-details-box-title d-flex justify-content-between align-items-center mb-20">
                                            <h6 class="font-16 font-medium">{{ __('Ticket Info') }}</h6>
                                            <div class="ticket-info-right-status d-flex align-items-center">
                                                <span class="font-15">{{ __('Status') }} :</span>
                                                @if($ticket->status == 1)
                                                    <div class="ticket-status-box radius-3 bg-green text-white font-14">{{ __('Open') }}</div>
                                                @elseif($ticket->status == 2)
                                                    <div class="ticket-status-box radius-3 bg-deep-orange text-white font-14">{{ __('Closed') }}</div>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="ticket-info-top-box">
                                            <div class="ticket-info-content">
                                                <p class="font-semi-bold">{{ __('Subject') }}</p>
                                                <p>#{{ $ticket->ticket_number }} - {{$ticket->subject}}</p>
                                            </div>
                                            @if(@$ticket->department->name)
                                                <div class="ticket-info-content">
                                                    <p class="font-semi-bold">{{ __('Department') }}</p>
                                                    <p>{{ @$ticket->department->name }}</p>
                                                </div>
                                            @endif

                                            @if(@$ticket->priority->name)
                                                <div class="ticket-info-content">
                                                    <p class="font-semi-bold">{{ __('Priority') }}</p>
                                                    <p>{{ @$ticket->priority->name }}</p>
                                                </div>
                                            @endif

                                            @if(@$ticket->service->name)
                                                <div class="ticket-info-content">
                                                    <p class="font-semi-bold">{{ __('Related Service') }}</p>
                                                    <p>{{ @$ticket->service->name }}</p>
                                                </div>
                                            @endif
                                        </div>

                                        <div class="ticket-info-bottom-box">
                                            <div class="ticket-info-content">
                                                <p>{{ __('Opened') }} : {{ $ticket->created_at }}</p>
                                                <p>{{ __('Last Response') }} : {{ @$last_message->created_at }}</p>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                </div>
            </div>
        </section>
        <!-- Ticket Details End -->

    </div>

@endsection
