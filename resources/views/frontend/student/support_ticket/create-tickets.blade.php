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
                                    <li class="breadcrumb-item font-14"><a href="{{ route('support-ticket-faq') }}">{{ __('Support Tickets') }}</a></li>
                                    <li class="breadcrumb-item font-14 active" aria-current="page">{{ __('Create New Ticket') }}</li>
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

    <!-- Create tickets Start -->
    <section class="create-tickets-page section-t-space">
        <div class="container">
            <div class="instructor-quiz-list-page instructor-live-class-list-page instructor-ticket-content-wrap bg-white p-30 radius-8">

                <div class="row">
                    <div class="col-12">
                        <ul class="nav nav-tabs assignment-nav-tabs live-class-list-nav-tabs" id="myTab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="create_new_ticket-tab" data-bs-toggle="tab" data-bs-target="#create_new_ticket" type="button" role="tab"
                                        aria-controls="create_new_ticket" aria-selected="true">{{ __('Create New Ticket') }}
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="view_ticket-tab" data-bs-toggle="tab" data-bs-target="#view_ticket" type="button" role="tab"
                                        aria-controls="view_ticket" aria-selected="false">{{ __('View Ticket') }}
                                </button>
                            </li>
                        </ul>

                        <div class="tab-content live-class-list" id="myTabContent">
                            <div class="tab-pane fade show active" id="create_new_ticket" role="tabpanel" aria-labelledby="create_new_ticket-tab">
                                <form action="{{ route('student.support-ticket.store') }}" method="post" enctype="multipart/form-data">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-12 mb-30">
                                            <div class="label-text-title color-heading font-medium font-16 mb-3">{{__('Name')}}
                                            </div>
                                            <input type="text" name="name" value="{{ auth()->user()->name }}" class="form-control" placeholder="Write your full name" readonly required>
                                            @if ($errors->has('name'))
                                                <span class="text-danger"><i class="fas fa-exclamation-triangle"></i> {{ $errors->first('name') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12 mb-30">
                                            <div class="label-text-title color-heading font-medium font-16 mb-3">{{__('Email Address')}}
                                            </div>
                                            <input type="email" name="email" value="{{ auth()->user()->email }}" class="form-control" placeholder="Write your email address" readonly required>
                                            @if ($errors->has('email'))
                                                <span class="text-danger"><i class="fas fa-exclamation-triangle"></i> {{ $errors->first('email') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12 mb-30">
                                            <div class="label-text-title color-heading font-medium font-16 mb-3">{{__('Subject')}}
                                            </div>
                                            <input type="text" name="subject" value="{{ old('subject') }}" class="form-control" placeholder="Write your subject" required>
                                            @if ($errors->has('subject'))
                                                <span class="text-danger"><i class="fas fa-exclamation-triangle"></i> {{ $errors->first('subject') }}</span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-4 mb-30">
                                            <label class="label-text-title color-heading font-medium font-16 mb-3">{{__('Department')}}
                                            </label>
                                            <select class="form-select" name="department_id">
                                                <option value="">{{__('Select Option')}}</option>
                                                @foreach($departments as $department)
                                                <option value="{{ $department->id }}" @if($department->id == old('department_id')) selected @endif>{{ $department->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-4 mb-30">
                                            <label class="label-text-title color-heading font-medium font-16 mb-3">{{__('Related Service')}}
                                            </label>
                                            <select class="form-select" name="related_service_id">
                                                <option value="">{{__('Select Option')}}</option>
                                                @foreach($services as $service)
                                                    <option value="{{ $service->id }}" @if($service->id == old('related_service_id')) selected @endif>{{ $service->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-4 mb-30">
                                            <label class="label-text-title color-heading font-medium font-16 mb-3">{{__('Priority')}}
                                            </label>
                                            <select class="form-select" name="priority_id">
                                                <option value="">{{__('Select Option')}}</option>
                                                @foreach($priorities as $priority)
                                                    <option value="{{ $priority->id }}" @if($priority->id == old('priority_id')) selected @endif>{{ $priority->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12 mb-30">
                                            <div class="label-text-title color-heading font-medium font-16 mb-3">{{__('Message')}}
                                            </div>
                                            <textarea class="form-control" name="message" cols="30" rows="10" placeholder="Write your message" required>{{ old('message') }}</textarea>
                                            @if ($errors->has('message'))
                                                <span class="text-danger"><i class="fas fa-exclamation-triangle"></i> {{ $errors->first('message') }}</span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12 mb-15">
                                            <div class="">
                                                <div class="label-text-title color-heading font-medium font-16 mb-3">{{__('Upload Your File')}}
                                                </div>
                                                <input type="file" name="file" onchange="previewFile(this)">
                                            </div>
                                        </div>
                                        <div class="col-md-12 mb-20">
                                            @if ($errors->has('file'))
                                                <span class="text-danger"><i class="fas fa-exclamation-triangle"></i> {{ $errors->first('file') }}</span>
                                            @endif
                                            <p class="font-14 placeholder-color">Valid file type : jpg, jpeg, gif, png and File size max : 10 MB</p>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12 mb-30 create-tickets-btns">
                                            <button type="submit" class="theme-btn theme-button1 default-hover-btn">{{__('Submit')}}</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="tab-pane fade appendTicketList" id="view_ticket" role="tabpanel" aria-labelledby="create_new_ticket-tab">
                                @include('frontend.student.support_ticket.render-ticket-list')
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

</div>

@endsection

@push('script')
    <script>
        'use strict'
        $(document).on('click', '.pagination a', function (event) {
            event.preventDefault();
            var page = $(this).attr('href').split('page=')[1];
            var route = "{{ route('student.support-ticket.fetch-data') }}" + '?page=' + page;
            $.ajax({
                type: "GET",
                url: route,
                success: function (response) {
                    $('.appendTicketList').html(response);
                }
            });
        });

    </script>
@endpush
