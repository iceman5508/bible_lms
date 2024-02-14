@extends('layouts.instructor')

@section('breadcrumb')
    <div class="page-banner-content text-center">
        <h3 class="page-banner-heading text-white pb-15"> {{__('Consultation')}} </h3>

        <!-- Breadcrumb Start-->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb justify-content-center">
                <li class="breadcrumb-item font-14"><a href="{{route('instructor.dashboard')}}">{{__('Dashboard')}}</a></li>
                <li class="breadcrumb-item font-14 active" aria-current="page">{{__('Consultation')}}</li>
            </ol>
        </nav>
    </div>
@endsection

@section('content')
    <div class="instructor-profile-right-part">
        <div class="instructor-quiz-list-page instructor-consultation-list-page">

            <div class="instructor-my-courses-title d-flex justify-content-between align-items-center">
                <h6>{{ __('All Booking Request') }}</h6>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                            <tr>
                                <th scope="col">{{__('Student Name')}}</th>
                                <th scope="col">{{__('Date')}}</th>
                                <th scope="col">{{__('Time')}}</th>
                                <th scope="col">{{__('Duration')}}</th>
                                <th scope="col">{{__('Action')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($bookingHistories as $bookingHistory)
                                <tr>
                                    <td>
                                        <div class="table-data-with-img d-flex align-items-center">
                                            <div class="flex-shrink-0">
                                                <div class="instructor-student-img-wrap radius-50">
                                                    <img src="{{ getImageFile(@$bookingHistory->user->image_path) }}" alt="img" class="img-fluid"></div>
                                            </div>
                                            <div class="flex-grow-1 table-data-course-name">{{ @$bookingHistory->user->student->name }}</div>
                                        </div>
                                    </td>
                                    <td>{{ $bookingHistory->date }}</td>
                                    <td>{{ $bookingHistory->time }}</td>
                                    <td>{{ $bookingHistory->duration }}</td>
                                    <td>
                                        <div class="red-blue-action-btns">
                                            <button type="button" data-route="{{ route('instructor.bookingStatus', [$bookingHistory->uuid, 1]) }}"
                                               class="theme-btn theme-button1 default-hover-btn bookingStatus">{{__('Approve')}}</button>

                                            <button type="button" data-bs-toggle="modal" data-bs-target="#commentReplyModal" data-route="{{ route('instructor.cancelReason', $bookingHistory->uuid) }}"
                                                    class="theme-btn theme-button1 orange-theme-btn default-hover-btn bookingCancel">{{__('Cancel')}}</button>
                                        </div>
                                    </td>
                                </tr>

                            @empty
                            @endforelse
                            </tbody>
                        </table>
                        @if(count($bookingHistories) < 1)
                            <div class="empty-data ">
                                <h6 class="my-3">{{ __('No Record Found') }}</h6>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Start -->
    <div class="modal fade" id="commentReplyModal" tabindex="-1" aria-labelledby="writeReviewModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="writeReviewModalLabel">{{ __('Cancel Reason') }}</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="cancelReasonForm" action="" method="post">
                    @csrf
                    <div class="modal-body">
                        <!-- Leave a Comment Area-->

                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group para-color font-14 color-gray nessage-text mb-30">
                                    <textarea name="cancel_reason" rows="3" class="form-control cancel_reason" required placeholder="Type your cancel reason *"></textarea>
                                </div>
                            </div>
                        </div>
                        <!-- Leave a Comment Area-->
                    </div>
                    <div class="modal-footer d-flex justify-content-between align-items-center">
                        <button type="button" class="theme-btn theme-button3 modal-back-btn" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                        <button type="submit" class="theme-btn theme-button1 default-hover-btn">{{ __('Submit') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Modal End -->
@endsection

@push('script')
    <script type="text/javascript">
        $('.bookingStatus').click(function (){
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
                        success: function (response) {
                            location.reload();
                        }
                    })
                }
            })
        });

        $('.bookingCancel').click(async function (){
            var route = $(this).data('route');
            $("#cancelReasonForm").attr('action', route);
        })
    </script>
@endpush
