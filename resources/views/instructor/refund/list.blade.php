@extends('layouts.instructor')

@section('breadcrumb')
    <div class="page-banner-content text-center">
        <h3 class="page-banner-heading text-white pb-15">{{ __(@$title) }}</h3>

        <!-- Breadcrumb Start-->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb justify-content-center">
                <li class="breadcrumb-item font-14"><a href="{{ route('instructor.dashboard') }}">{{ __('Dashboard') }}</a>
                </li>
                <li class="breadcrumb-item font-14 active" aria-current="page">{{ __(@$title) }}</li>
            </ol>
        </nav>
    </div>
@endsection

@section('content')
    <div class="instructor-profile-right-part">
        <div class="instructor-quiz-list-page instructor-live-class-page">

            <div class="instructor-my-courses-title d-flex justify-content-between align-items-center">
                <h6>{{ __(@$title) }}</h6>
            </div>

            <div class="row">
                <div class="col-12">
                    @if (count($refunds) > 0)
                        <div class="table-responsive table-responsive-xl">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>{{ __('SL') }}</th>
                                        <th scope="col">{{ __('Student') }}</th>
                                        <th scope="col">{{ __('Course') }}</th>
                                        <th scope="col">{{ __('Reason') }}</th>
                                        <th scope="col">{{ __('Amount') }}</th>
                                        <th scope="col">{{ __('Status') }}</th>
                                        <th scope="col">{{ __('Action') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($refunds as $refund)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>
                                               {{ $refund->user->name }}
                                            </td>
                                            <td>
                                               {{ $refund->order_item->course->title }}
                                            </td>
                                            <td>
                                               {{ $refund->reason }}
                                            </td>
                                            <td>
                                                @if(get_currency_placement() == 'after')
                                                    {{  $refund->amount }} {{ get_currency_symbol() }}
                                                @else
                                                    {{ get_currency_symbol() }} {{  $refund->amount }}
                                                @endif
                                            </td>
                                            <td>
                                               @if($refund->status == STATUS_PENDING)
                                               <span class="pending status">Pending</span>
                                               @elseif($refund->status == STATUS_APPROVED)
                                               <span class="active status">Approved</span>
                                               @else
                                               <span class="blocked status">Rejected</span>
                                               @endif
                                            </td>
                                            <td>
                                                @if ($refund->status == STATUS_PENDING)
                                                <button type="button" class="status-change-btn btn btn-primary p-1" data-data="{{ $refund }}" data-type="1"><i class="fa fa-check" aria-hidden="true"></i></button>
                                                <button type="button" class="status-change-btn btn btn-danger p-1" data-data="{{ $refund }}" data-type="2"><i class="fa fa-ban" aria-hidden="true"></i></button>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <!-- If there is no data Show Empty Design Start -->
                        <div class="empty-data">
                            <img src="{{ asset('frontend/assets/img/empty-data-img.png') }}" alt="img"
                                class="img-fluid">
                            <h5 class="my-3">{{ __('Empty Refund') }}</h5>
                        </div>
                        <!-- If there is no data Show Empty Design End -->
                    @endif
                </div>
            </div>

        </div>
    </div>
    
    <!--Refund Modal Start-->
    <div class="modal fade" id="refundModal" tabindex="-1" aria-labelledby="refundModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="refundModalLabel">{{ __('Refund Request') }}</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="row mb-30">
                            <div class="col-md-12">
                                <input type="hidden" name="refund_id" id="refund-id" value="">
                                <input type="hidden" name="refund_type" id="refund-type" value="">
                                <label class="font-medium font-15 color-heading">{{ __('Refund Amount') }}</label>
                                <input type="text" class="form-control" disabled value="" id="refund-amount">
                            </div>
                            <div class="col-md-12 mt-15">
                                <label class="font-medium font-15 color-heading">{{ __('Reason') }}</label>
                                <textarea class="form-control" id="refund-reason" disabled rows="3" placeholder="{{ __('Please write your refund request reason') }}"></textarea>
                            </div>
                            <div class="col-md-12 mt-15 d-none" id="feedback">
                                <label class="font-medium font-15 color-heading">{{ __('Feedback') }}</label>
                                <textarea class="form-control" id="instructor-feedback" rows="3" placeholder="{{ __('Please write your feedback here') }}"></textarea>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer d-flex justify-content-between align-items-center">
                    <button type="button" class="theme-btn theme-button3" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                    <button type="button" class="theme-btn theme-button1" id="statusChangeRefund">{{ __('Refund Accepted') }}</button>
                </div>
            </div>
        </div>
    </div>
    <!--Refund Modal End-->
    <input type="hidden" id="statusChangeRefundUrl" value="{{ route('instructor.refund.status-change') }}">
@endsection

@push('script')
    <script src="{{ asset('frontend/assets/js/instructor/refund-request.js') }}"></script>
@endpush
