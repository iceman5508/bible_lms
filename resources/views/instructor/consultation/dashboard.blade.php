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
        <div class="instructor-quiz-list-page instructor-consultation-page">

            <div class="are-you-available-box mb-30">
                <form action="{{ route('instructor.consultation.instructorAvailabilityStoreUpdate') }}" method="post">
                    @csrf
                    <h6 class="are-you-available-title mb-3 d-inline-flex align-items-center"><span class="iconify me-2" data-icon="heroicons-outline:thumb-up"></span>{{ __('Are you available for 1 to 1 consultation?') }}</h6>
                    <div class="mb-3">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" @if($instructor->consultation_available == 1) checked @endif type="radio" id="inlineCheckbox1" value="1"
                                   name="consultation_available">
                            <label class="form-check-label color-heading mb-0" for="inlineCheckbox1">{{ __('Yes') }}</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" @if($instructor->consultation_available != 1) checked @endif type="radio" id="inlineCheckbox2" value="0"
                                   name="consultation_available">
                            <label class="form-check-label color-heading mb-0" for="inlineCheckbox2">{{ __('No') }}</label>
                        </div>
                    </div>
                    <h6 class="are-you-available-title mb-3 d-inline-flex align-items-center"><span class="iconify me-2" data-icon="heroicons-outline:thumb-up"></span>{{ __('Available type for 1 to 1 consultation?') }}</h6>
                    <div class="mb-3">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" @if($instructor->available_type == 1) checked @endif type="radio" id="inlineCheckbox3" value="1"
                                   name="available_type">
                            <label class="form-check-label color-heading mb-0" for="inlineCheckbox3">{{ __('In Person') }}</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" @if($instructor->available_type == 2) checked @endif type="radio" id="inlineCheckbox4" value="2"
                                   name="available_type">
                            <label class="form-check-label color-heading mb-0" for="inlineCheckbox4">{{ __('Online') }}</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" @if($instructor->available_type == 3) checked @endif type="radio" id="inlineCheckbox5" value="3"
                                   name="available_type">
                            <label class="form-check-label color-heading mb-0" for="inlineCheckbox5">{{ __('Both') }}</label>
                        </div>
                    </div>

                    <div id="consultancyArea" class="@if($instructor->available_type == 2) d-none @endif">
                        <h6 class="are-you-available-title mb-3 d-inline-flex align-items-center"><span class="iconify me-2" data-icon="heroicons-outline:thumb-up"></span>{{ __('Consultancy Area') }}</h6>
                        <div class="mb-3">
                            @foreach (CONSULTANCY_AREA_ARRAY as $key => $consultancyArea)
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" @if($instructor->consultancy_area == $key) checked @endif type="radio" id="consultancyArea{{ $key }}" value="{{ $key }}"
                                        name="consultancy_area">
                                    <label class="form-check-label color-heading mb-0" for="consultancyArea{{ $key }}">{{ __($consultancyArea) }}</label>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="mb-3 row instructor-panel-hourly-rate-box align-items-center">
                        <label for="hourlyRate" class="col-sm-2 col-form-label font-medium color-heading">{{ __('Hourly Rate') }} {{ get_currency_symbol() }}</label>
                        <div class="col-sm-6 col-md-3">
                            <input type="number" step="any" min="0" name="hourly_rate" class="form-control" id="hourlyRate" placeholder="Ex.20"
                                   value="{{ $instructor->hourly_rate }}">
                        </div>
                        @if ($errors->has('hourly_rate'))
                            <span class="text-danger"><i class="fas fa-exclamation-triangle"></i> {{ $errors->first('hourly_rate') }}</span>
                        @endif
                    </div>
                    <div class="mb-3 row instructor-panel-hourly-rate-box align-items-center">
                        <label for="hourlyOldRate" class="col-sm-2 col-form-label font-medium color-heading">{{ __('Hourly Old Rate') }} {{ get_currency_symbol() }}</label>
                        <div class="col-sm-6 col-md-3">
                            <input type="number" step="any" min="0" name="hourly_old_rate" class="form-control" id="hourlyOldRate" placeholder="Ex.20"
                                   value="{{ $instructor->hourly_old_rate }}">
                        </div>
                        @if ($errors->has('hourly_old_rate'))
                            <span class="text-danger"><i class="fas fa-exclamation-triangle"></i> {{ $errors->first('hourly_old_rate') }}</span>
                        @endif
                    </div>

                    <div class="mb-3">
                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" id="offlineStatus" name="is_offline" value="1" {{ $instructor->is_offline == INSTRUCTOR_IS_OFFLINE ? 'checked' : '' }}>
                            <label class="form-check-label color-heading mb-0" for="offlineStatus">{{ __('Offline Status') }}</label>
                        </div>
                        <div class="mb-3 {{ $instructor->is_offline == INSTRUCTOR_IS_OFFLINE ? '' : 'd-none' }}" id="offlineMessage">
                            <label for="offlineMessageText" class="form-label">{{ __('Offline Message') }}</label>
                            <textarea class="form-control" id="offlineMessageText" name="offline_message" placeholder="{{ __('Message') }}" >{{ $instructor->offline_message }}</textarea>
                            <small class="text-muted mt-1">
                                {{ __('If you put your account offline, a message will be displayed in your profile and it will be noticed to users. You can type a personalized message in the following input.') }}
                            </small>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" id="isSubscriptionEnable" name="is_subscription_enable" value="1" {{ $instructor->is_subscription_enable == STATUS_ACCEPTED ? 'checked' : '' }}>
                            <label class="form-check-label color-heading mb-0" for="isSubscriptionEnable">{{ __('Is Subscription Enable') }}</label>
                        </div>
                    </div>

                    <button type="submit" class="theme-btn theme-button1 default-hover-btn">{{ __('Save') }}</button>
                </form>

            </div>

            <div class="instructor-my-courses-title d-flex justify-content-between align-items-center">
                <h6>{{ __('Your Time Slot List') }}</h6>
            </div>

            <div class="row time-slot-list-wrap">
                <div class="col-12">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                            <tr>
                                <th scope="col">{{ __('Days') }}</th>
                                <th scope="col">{{ __('Action') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td class="font-18 font-medium">{{ __('Saturday') }}</td>
                                <td>
                                    <div class="notice-board-action-btns">
                                        <button type="button" class="theme-btn theme-button1 default-hover-btn saturdayAddSlot" data-bs-toggle="modal"
                                                data-bs-target="#addSlotModal">{{ __('Add Slot') }}
                                        </button>
                                        <a href="{{ route('instructor.consultation.dayAvailableStatusChange', 6) }}"
                                           class="theme-btn theme-button1 orange-theme-btn default-hover-btn offDayDeactive {{ getDayAvailableStatus(6) == 1 ? '' : 'disabled-btn' }}">
                                            {{ getDayAvailableStatus(6) == 1 ? __('On day') : __('Off day') }}</a>
                                        <button type="button" class="theme-btn theme-button1 green-theme-btn default-hover-btn saturdayViewSlot viewSlot"
                                                data-route="{{ route('instructor.consultation.slotView', 6) }}" data-bs-toggle="modal" data-bs-target="#viewSlotModal">{{ __('View') }}
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            <tr>
                                <td class="font-18 font-medium">{{ __('Sunday') }}</td>
                                <td>
                                    <div class="notice-board-action-btns">
                                        <button type="button" class="theme-btn theme-button1 default-hover-btn sundayAddSlot" data-bs-toggle="modal" data-bs-target="#addSlotModal">
                                            {{ __('Add Slot') }}
                                        </button>
                                        <a href="{{ route('instructor.consultation.dayAvailableStatusChange', 0) }}"
                                           class="theme-btn theme-button1 orange-theme-btn default-hover-btn offDayDeactive {{ getDayAvailableStatus(0) == 1 ? '' : 'disabled-btn' }}">{{ getDayAvailableStatus(0) == 1 ? __('On day') : __('Off day') }}</a>
                                        <button type="button" class="theme-btn theme-button1 green-theme-btn default-hover-btn sundayViewSlot viewSlot"
                                                data-route="{{ route('instructor.consultation.slotView', 0) }}" data-bs-toggle="modal" data-bs-target="#viewSlotModal">{{ __('View') }}
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="font-18 font-medium">{{ __('Monday') }}</td>
                                <td>
                                    <div class="notice-board-action-btns">
                                        <button type="button" class="theme-btn theme-button1 default-hover-btn mondayAddSlot" data-bs-toggle="modal" data-bs-target="#addSlotModal">
                                            {{ __('Add Slot') }}
                                        </button>
                                        <a href="{{ route('instructor.consultation.dayAvailableStatusChange', 1) }}"
                                           class="theme-btn theme-button1 orange-theme-btn default-hover-btn offDayDeactive {{ getDayAvailableStatus(1) == 1 ? '' : 'disabled-btn' }}">{{ getDayAvailableStatus(1) == 1 ? __('On day') : __('Off day') }}</a>
                                        <button type="button" class="theme-btn theme-button1 green-theme-btn default-hover-btn mondayViewSlot viewSlot"
                                                data-route="{{ route('instructor.consultation.slotView', 1) }}" data-bs-toggle="modal" data-bs-target="#viewSlotModal">{{ __('View') }}
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="font-18 font-medium">{{ __('Tuesday') }}</td>
                                <td>
                                    <div class="notice-board-action-btns">
                                        <button type="button" class="theme-btn theme-button1 default-hover-btn tuesdayAddSlot" data-bs-toggle="modal"
                                                data-bs-target="#addSlotModal">{{ __('Add Slot') }}
                                        </button>
                                        <a href="{{ route('instructor.consultation.dayAvailableStatusChange', 2) }}"
                                           class="theme-btn theme-button1 orange-theme-btn default-hover-btn offDayDeactive {{ getDayAvailableStatus(2) == 1 ? '' : 'disabled-btn' }}">{{ getDayAvailableStatus(2) == 1 ? __('On day') : __('Off day') }}</a>
                                        <button type="button" class="theme-btn theme-button1 green-theme-btn default-hover-btn tuesdayViewSlot viewSlot"
                                                data-route="{{ route('instructor.consultation.slotView', 2) }}" data-bs-toggle="modal" data-bs-target="#viewSlotModal">{{ __('View') }}
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="font-18 font-medium">{{ __('Wednesday') }}</td>
                                <td>
                                    <div class="notice-board-action-btns">
                                        <button type="button" class="theme-btn theme-button1 default-hover-btn wednesdayAddSlot" data-bs-toggle="modal"
                                                data-bs-target="#addSlotModal">{{ __('Add Slot') }}
                                        </button>
                                        <a href="{{ route('instructor.consultation.dayAvailableStatusChange', 3) }}"
                                           class="theme-btn theme-button1 orange-theme-btn default-hover-btn offDayDeactive {{ getDayAvailableStatus(3) == 1 ? '' : 'disabled-btn' }}">{{ getDayAvailableStatus(3) == 1 ? __('On day') : __('Off day') }}</a>
                                        <button type="button" class="theme-btn theme-button1 green-theme-btn default-hover-btn wednesdayViewSlot viewSlot"
                                                data-route="{{ route('instructor.consultation.slotView', 3) }}" data-bs-toggle="modal" data-bs-target="#viewSlotModal">{{ __('View') }}
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="font-18 font-medium">{{ __('Thursday') }}</td>
                                <td>
                                    <div class="notice-board-action-btns">
                                        <button type="button" class="theme-btn theme-button1 default-hover-btn thursdayAddSlot" data-bs-toggle="modal"
                                                data-bs-target="#addSlotModal">{{ __('Add Slot') }}
                                        </button>
                                        <a href="{{ route('instructor.consultation.dayAvailableStatusChange', 4) }}"
                                           class="theme-btn theme-button1 orange-theme-btn default-hover-btn offDayDeactive {{ getDayAvailableStatus(4) == 1 ? '' : 'disabled-btn' }}">{{ getDayAvailableStatus(4) == 1 ? __('On day') : __('Off day') }}</a>
                                        <button type="button" class="theme-btn theme-button1 green-theme-btn default-hover-btn thursdayViewSlot viewSlot"
                                                data-route="{{ route('instructor.consultation.slotView', 4) }}" data-bs-toggle="modal" data-bs-target="#viewSlotModal">{{ __('View') }}
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="font-18 font-medium">{{ __('Friday') }}</td>
                                <td>
                                    <div class="notice-board-action-btns">
                                        <button type="button" class="theme-btn theme-button1 default-hover-btn fridayAddSlot" data-bs-toggle="modal" data-bs-target="#addSlotModal">
                                            {{ __('Add Slot') }}
                                        </button>
                                        <a href="{{ route('instructor.consultation.dayAvailableStatusChange', 5) }}"
                                           class="theme-btn theme-button1 orange-theme-btn default-hover-btn offDayDeactive {{ getDayAvailableStatus(5) == 1 ? '' : 'disabled-btn' }}">{{ getDayAvailableStatus(5) == 1 ? __('On day') : __('Off day') }}</a>
                                        <button type="button" class="theme-btn theme-button1 green-theme-btn default-hover-btn fridayViewSlot viewSlot"
                                                data-route="{{ route('instructor.consultation.slotView', 5) }}" data-bs-toggle="modal" data-bs-target="#viewSlotModal">{{ __('View') }}
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- Add Slot Modal Start-->
    <div class="modal fade" id="addSlotModal" tabindex="-1" aria-labelledby="addSlotModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title dayName" id="addSlotModalLabel">{{ __('Saturday') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('instructor.consultation.slotStore') }}" method="post">
                        @csrf
                        <div class="add-slot-daywise-wrap">
                            <label class="label-text-title color-heading font-medium font-20 mb-2">{{ __('Time') }}</label>

                            <div class="row slot_field_wrap">
                                <!-------Note---------
                                after add time slot input field color and border color will change. To do that please add class="time_added_field" into input class name
                                -------Note--------->
                                <div class="d-flex">
                                    <div class="col-sm-5 col-md-5">
                                        <div class="input-group add-slot-day-item">
                                            <label for="" class="color-heading">{{ __('Start Time') }}</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-5 col-md-5 ms-2">
                                        <div class="input-group add-slot-day-item ">
                                            <label for="" class="color-heading">{{ __('End Time') }}</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex">
                                    <div class="col-sm-5 col-md-5">
                                        <div class="input-group add-slot-day-item mb-3">
                                            <input type="time" class="form-control" name="starTimes[]" placeholder="Ex. 9:00 AM" required>
                                        </div>
                                    </div>
                                    <div class="col-sm-5 col-md-5 ms-2">
                                        <div class="input-group add-slot-day-item mb-3">
                                            <input type="time" class="form-control" name="endTimes[]" placeholder="Ex. 10:00 AM" required>
                                        </div>
                                    </div>
                                    <div class="col-sm-2 col-md-2">
                                        <div class="input-group add-slot-day-item mb-3">
                                            <span class="input-group-text bg-transparent border-0 cursor remove_button">
                                                <span class="iconify" data-icon="fluent:delete-48-filled"></span>
                                            </span>
                                        </div>
                                    </div>

                                </div>
                            </div>

                            <input type="hidden" name="day" class="day" value="">

                            <div class="row">
                                <div class="col d-flex justify-content-between">
                                    <button type="button" class="theme-btn theme-button1 default-btn green-theme-btn add_button">
                                        <i class="fas fa-plus"> {{ __('Add Slot') }}</i>
                                    </button>
                                    <button type="submit" class="theme-btn theme-button1 default-hover-btn">{{ __('Save') }}</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Add Slot Modal End-->

    <!-- View Slot Modal Start-->
    <div class="modal fade" id="viewSlotModal" data-bs-backdrop="static" tabindex="-1" aria-labelledby="viewSlotModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title dayName" id="viewSlotModalLabel">{{ __('Saturday') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="add-slot-daywise-wrap">
                            <label class="label-text-title color-heading font-medium font-20 mb-2">{{ __('Time') }}</label>

                            <div class="row slot_field_wrap appendSlotList">

                            </div>

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- View Slot Modal End-->
@endsection

@push('script')
    <script src="{{ asset('frontend/assets/js/instructor/consultation.js') }}"></script>
@endpush
