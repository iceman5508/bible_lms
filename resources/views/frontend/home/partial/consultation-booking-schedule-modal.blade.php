<!-- Consultation Booking Modal Start-->
<div class="modal fade" id="consultationBookingModal" tabindex="-1" aria-labelledby="consultationBookingModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="consultationBookingModalLabel">{{ __('Booking Now') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="row booking-header-row consultation-select-date-hour">
                <div class="input-group flex-nowrap col-md-6 consultantion-calendar-box">
                    <label class="col-md-5 col-form-label font-17 font-semi-bold color-heading">{{ __('Select Date') }}:</label>
                        <div class="book-schedule-calendar-wrap position-relative appendDatePicker">
                           <!-- Append from booking.js -->
                        </div>
                </div>
                <div class="input-group col-md-6 consultantion-hours-box">
                    <label class="col-md-3 col-form-label font-17 font-semi-bold color-heading">{{ __('Hours') }}</label>
                    <input type="text" class="form-control font-medium hourly_fee" disabled value="0">
                    <input type="hidden" class="form-control font-medium hourly_rate" value="0">
                </div>
                <input type="hidden" class="booking_instructor_user_id" value="">
            </div>

            <div class="row booking-header-row">
                <div class="col-sm-6 col-md-4">
                    <div class="input-group row">
                        <label class="col-sm-4 col-md-6 col-form-label font-17 font-semi-bold color-heading">{{ __('Type') }}</label>
                        <div class="col-sm-8 col-md-6 InPerson d-none">
                            <div class="time-slot-item">
                                <input type="radio" name="available_type" class="btn-check" value="1"
                                    id="checkboxInperson" autocomplete="off">
                                <label class="btn btn-outline-primary mb-0" for="checkboxInperson">{{ __('In Person') }}</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-4 col-md-2">
                    <div class="input-group row">
                        <div class="col Online d-none">
                            <div class="time-slot-item">
                                <input type="radio" name="available_type" class="btn-check" value="2"
                                    id="checkboxOnline" autocomplete="off">
                                <label class="btn btn-outline-primary mb-0" for="checkboxOnline">{{ __('Online') }}</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="appendDayAndTime">

            </div>

            <div class="modal-footer d-flex justify-content-between align-items-center">
                <button type="button" class="theme-btn theme-button1 default-hover-btn w-100 makePayment"
                    data-route="{{ route('student.addToCartConsultation') }}">{{ __('Make Payment') }}</button>
            </div>
        </div>
    </div>
</div>
<!-- Consultation Booking Modal End-->

<input type="hidden" class="getInstructorBookingTimeRoute" value="{{ route('getInstructorBookingTime') }}">
