@if(count($orderItems) > 0)
    <div class="table-responsive">
        <table class="table bg-white my-courses-page-table stu-consult-tbl">
            <thead>
            <tr>
                <th scope="col" class="color-gray font-15 font-medium">{{__('Author')}}</th>
                <th scope="col" class="color-gray font-15 font-medium">{{__('Details')}}</th>
                <th scope="col" class="color-gray font-15 font-medium">{{__('Price')}}</th>
                <th scope="col" class="color-gray font-15 font-medium">{{__('Order Id')}}</th>
                <th scope="col" class="color-gray font-15 font-medium">{{__('Type')}}</th>
                <th scope="col" class="color-gray font-15 font-medium">{{__('Status')}}</th>
                <th scope="col" class="color-gray font-15 font-medium">{{__('Action')}}</th>
            </tr>
            </thead>
            <tbody>

            @foreach($orderItems as $orderItem)
                @if($orderItem->consultation_slot_id)
                    @php
                        $relation = getUserRoleRelation($orderItem->consultationSlot->user)
                    @endphp
                <tr>
                    <td class="wishlist-course-item">
                        <div class="card course-item wishlist-item border-0 d-flex align-items-center">
                            <div class="course-img-wrap flex-shrink-0 overflow-hidden">
                                <a href=""><img src="{{ getImageFile(@$orderItem->consultationSlot->user->image_path) }}" alt="course" class="img-fluid"></a>
                            </div>
                            <div class="card-body flex-grow-1">
                                <h5 class="card-title course-title"><a href="">{{ @$orderItem->consultationSlot->user->$relation->full_name }}</a></h5>
                                <div class="card-text font-medium font-14 mb-1">
                                    <p class="card-text instructor-name-certificate font-medium">{{ @$orderItem->consultationSlot->user->$relation->full_namee }}
                                        {{ @$orderItem->consultationSlot->user->$relation->professional_title }}
                                        @if(get_instructor_ranking_level(@$orderItem->consultationSlot->user->badges))
                                            | {{ get_instructor_ranking_level(@$orderItem->consultationSlot->user->badges) }}
                                        @endif
                                    </p>
                                    <a target="_blank" href="{{route('student.download-invoice', [@$orderItem->id])}}" class="color-gray2 me-2 my-learning-invoice">
                                        <img src="{{ asset('frontend/assets/img/courses-img/invoice-icon.png') }}" alt="report" class="me-1">{{__('Invoice')}}</a>
                                </div>
                            </div>
                        </div>
                    </td>
                    <td >
                        <div class="card course-item wishlist-item border-0 d-flex">
                            <div class="card-body flex-grow-1">
                                <h5 class="card-title course-title">Date : {{ @$orderItem->bookingHistory->date }}</h5>
                                <h5 class="card-title course-title">Time : {{ @$orderItem->bookingHistory->time }}</h5>
                                <h5 class="card-title course-title">Duration : {{ @$orderItem->bookingHistory->duration }}</h5>
                            </div>
                        </div>
                    </td>
                    <td class="wishlist-price font-15 color-heading">
                        @if($orderItem->unit_price > 0)
                            @if(get_currency_placement() == 'after')
                                {{ $orderItem->unit_price }} {{ get_currency_symbol() }}
                            @else
                                {{ get_currency_symbol() }} {{ $orderItem->unit_price }}
                            @endif
                        @else
                            {{ __('Free') }}
                        @endif
                    </td>
                    <td class="wishlist-price font-15 color-heading">{{@$orderItem->order->order_number}}</td>
                    <td class="wishlist-price font-15 color-heading">
                        @if(@$orderItem->bookingHistory->type == 1)
                            {{ __('In Person') }}
                        @elseif(@$orderItem->bookingHistory->type == 2)
                            {{ __('Online') }}
                        @endif
                    </td>

                    <td class="wishlist-price font-15 color-heading">
                        @if(@$orderItem->bookingHistory->status == 1)
                            <span class="status active">{{ __('Approved') }}</span>
                        @elseif(@$orderItem->bookingHistory->status == 2)
                            <span class="status blocked">{{ __('Cancelled') }}</span>
                        @elseif(@$orderItem->bookingHistory->status == 3)
                            <span class="status active">{{ __('Completed') }}</span>
                        @else
                            {{ __('Pending') }}
                        @endif
                    </td>

                    <td class="wishlist-add-to-cart-btn">
                        <div class="booking-table-detail-btn quiz-details-action-btns">

                            <button data-meeting_host_name="{{ @$orderItem->bookingHistory->meeting_host_name }}" data-id="{{ $orderItem->id }}" class="collapsed create_link" type="button" data-bs-toggle="collapse" data-bs-target="#collapseExample{{ $orderItem->id }}"
                                    aria-expanded="false" aria-controls="collapseExample{{ $orderItem->id }}" title="See Details">
                                <span class="iconify" data-icon="fa6-solid:angle-down"></span>
                            </button>
                        </div>
                    </td>
                </tr>
                <tr class="show-details-content">
                    <td class="p-0" colspan="7">
                        <div id="collapseExample{{ $orderItem->id }}" class="booking-history-details accordion-collapse collapse"
                             aria-labelledby="collapseExample{{ $orderItem->id }}" data-bs-parent="#accordionExample">
                            @if(@$orderItem->bookingHistory->type == 1)
                                <div class="booking-history-details-wrap align-items-center p-20">
                                    <div class="booking-history-left">
                                        <h6 class="font-15">{{ __('Instructor Details') }}</h6>
                                        <hr>
                                        <p><h6 class="font-15 d-inline">{{ __('Name') }}</h6>: {{ @$orderItem->bookingHistory->instructorUser->instructor->full_name }}</p>
                                        <p><h6 class="font-15 d-inline">{{ __('Email') }}</h6>: {{ @$orderItem->bookingHistory->instructorUser->email }}</p>
                                        <p><h6 class="font-15 d-inline">{{ __('Phone Number') }}</h6>: {{ @$orderItem->bookingHistory->instructorUser->instructor->phone_number }}</p>
                                    </div>
                                </div>

                            @elseif(@$orderItem->bookingHistory->type == 2)
                                <div class="booking-history-details-wrap d-flex align-items-center p-20">
                                    <div class="booking-history-left">
                                        <h6 class="font-15">{{ __('How to Join The Call?') }}</h6>
                                        <p>{{ __('If you want to join online call. You need to join below meeting host.') }}</p>
                                    </div>
                                </div>
                                @if(@$orderItem->bookingHistory->meeting_host_name)
                                    <div class="booking-history-details-wrap d-flex align-items-center p-20">
                                        <div class="booking-history-left">
                                            @if(@$orderItem->bookingHistory->meeting_host_name == 'zoom')
                                                <h6 class="font-15"><a href="{{ @$orderItem->bookingHistory->join_url }}" target="_blank" class="theme-btn theme-button1 default-hover-btn green-theme-btn">Join Now</a></h6>
                                            @elseif(@$orderItem->bookingHistory->meeting_host_name == 'bbb')
                                                <h6 class="font-15"><a href="{{ route('student.consultation.join-bbb-meeting', $orderItem->bookingHistory->id) }}" target="_blank" class="theme-btn theme-button1 default-hover-btn green-theme-btn">Join Now</a></h6>
                                            @elseif(@$orderItem->bookingHistory->meeting_host_name == 'jitsi')
                                                <h6 class="font-15"><a href="{{ route('consultation.join-jitsi-meeting', $orderItem->bookingHistory->uuid) }}" target="_blank" class="theme-btn theme-button1 default-hover-btn green-theme-btn">Join Now</a></h6>
                                            @elseif(@$orderItem->bookingHistory->meeting_host_name == 'gmeet')
                                                <h6 class="font-15"><a href="{{ @$orderItem->bookingHistory->join_url }}" target="_blank" class="theme-btn theme-button1 default-hover-btn green-theme-btn">Join Now</a></h6>
                                            @elseif(@$orderItem->bookingHistory->meeting_host_name == 'agora')
                                                <h6 class="font-15"><a href="{{ @$orderItem->bookingHistory->join_url }}" target="_blank" class="theme-btn theme-button1 default-hover-btn green-theme-btn">Join Now</a></h6>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                                <div class="align-items-center p-20">
                                    <form action="#">
                                        <div class="row">
                                            <div class="col-md-12 mb-30">
                                                <label class="label-text-title color-heading font-medium font-16 mb-3">{{ __('Meeting Host Name') }}
                                                </label>

                                                @if(@$orderItem->bookingHistory->meeting_host_name == 'zoom')
                                                    <option value="zoom" {{ @$orderItem->bookingHistory->meeting_host_name == 'zoom' ? 'selected' : null }}>Zoom</option>
                                                @endif
                                                @if(@$orderItem->bookingHistory->meeting_host_name == 'bbb')
                                                    <option value="bbb" {{ @$orderItem->bookingHistory->meeting_host_name == 'bbb' ? 'selected' : null }}>BigBlueButton</option>
                                                @endif
                                                @if(@$orderItem->bookingHistory->meeting_host_name == 'jitsi')
                                                    <option value="jitsi" {{ @$orderItem->bookingHistory->meeting_host_name == 'jitsi' ? 'selected' : null }}>Jitsi</option>
                                                @endif
                                                @if(@$orderItem->bookingHistory->meeting_host_name == 'gmeet')
                                                    <option value="gmeet" {{ @$orderItem->bookingHistory->meeting_host_name == 'gmeet' ? 'selected' : null }}>Gmeet</option>
                                                @endif
                                                @if(@$orderItem->bookingHistory->meeting_host_name == 'agora')
                                                    <option value="agora" {{ @$orderItem->bookingHistory->meeting_host_name == 'agora' ? 'selected' : null }}>Agora In App Video</option>
                                                @endif

                                            </div>
                                        </div>

                                        @if(@$orderItem->bookingHistory->meeting_host_name == 'zoom')
                                            <div class="row mb-30  zoom_live_link_div">
                                                <div class="col">
                                                    <label class="label-text-title color-heading font-medium font-16 mb-3">{{ __('Zoom Live Class Link') }}</label>
                                                    <div class="row align-items-center">
                                                        <div class="col-md-9">
                                                            <textarea class="form-control" name="" id="" cols="30" rows="10" disabled>{{$orderItem->bookingHistory->join_url}} </textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                        @if(@$orderItem->bookingHistory->meeting_host_name == 'jitsi')
                                            <div class="row mb-30 jitsi_live_link_div">
                                                <div class="col">
                                                    <label class="label-text-title color-heading font-medium font-16 mb-3">{{ __('Jitsi Meeting ID/Room') }}</label>
                                                    <div class="row align-items-center">
                                                        <div class="col-md-9">
                                                            <input type="text" name="jitsi_meeting_id" class="form-control"
                                                                   placeholder="" minlength="6" disabled
                                                                   value="{{ @$orderItem->bookingHistory->meeting_id }}">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                        @if(@$orderItem->bookingHistory->meeting_host_name == 'bbb')
                                            <div class="">
                                                <div class="row mb-30">
                                                    <div class="col">
                                                        <label class="label-text-title color-heading font-medium font-16 mb-3">{{ __('Moderator Password') }}</label>
                                                        <div class="row align-items-center">
                                                            <div class="col-md-9">
                                                                <input type="text" name="moderator_pw" minlength="6" class="form-control "  disabled
                                                                       placeholder="" value="{{ @$orderItem->bookingHistory->moderator_pw }}">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row mb-30">
                                                    <div class="col">
                                                        <label class="label-text-title color-heading font-medium font-16 mb-3">{{ __('Attendee Password') }}</label>
                                                        <div class="row align-items-center">
                                                            <div class="col-md-9">
                                                                <input type="text" name="attendee_pw" minlength="6" class="form-control"
                                                                       placeholder="" value="{{ @$orderItem->bookingHistory->attendee_pw }}" disabled>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </form>
                                </div>
                            @endif
                        </div>
                    </td>
                </tr>

                @if(@$orderItem->bookingHistory->status == 2)
                <tr class="show-details-content">
                    <td class="p-0" colspan="6">
                        <div id="collapseExample{{ $orderItem->id }}" class="booking-history-details accordion-collapse collapse"
                             aria-labelledby="collapseExample{{ $orderItem->id }}" data-bs-parent="#accordionExample">
                            <div class="booking-history-details-wrap d-flex align-items-center p-20">
                                <div class="booking-history-left">
                                    <h6 class="font-15">{{ __('Cancel Reason?') }}</h6>
                                    <p>{{ @$orderItem->bookingHistory->cancel_reason }}</p>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
                @endif
                @endif
            @endforeach

            </tbody>
        </table>
    </div>
@else
    <!-- If there is no data Show Empty Design Start -->
    <div class="empty-data">
        <img src="{{ asset('frontend/assets/img/empty-data-img.png') }}" alt="img" class="img-fluid">
        <h4 class="my-3">{{ __('Empty Consultation') }}</h4>
    </div>
    <!-- If there is no data Show Empty Design End -->
@endif
<!-- Pagination Start -->
@if(@$orderItems->hasPages())
    {{ @$orderItems->links('frontend.paginate.paginate') }}
@endif
<!-- Pagination End -->
