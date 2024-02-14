<div class="modal-body ">
    <div class="row consultation-modal-schedule-list-wrap ">
        <div class="col-md-2 ">
            <h6 class="font-17 font-semi-bold color-heading consultation-schedule-day">{{ @$bookingDay }}:</h6>
        </div>
        <div class="col-md-10">
            <div class="row">
                @forelse($slots as $slot)
                    <div class="col-sm-6 col-md-4">
                        <div class="time-slot-item">
                            <input type="radio" name="time" class="btn-check" data-item="{{ $slot }}" value="{{ $slot->time }}" id="checkbox{{ $slot->id }}"
                                   autocomplete="off">
                            <label class="btn btn-outline-primary" for="checkbox{{ $slot->id }}">{{ $slot->time }}</label>
                        </div>
                    </div>
                @empty
                    <div class="col-sm-6 col-md-4">
                        <div class="time-slot-item">
                            <span>{{ __('No Schedule Found') }}</span>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<div class="modal-footer d-none meetingDetails">
    <p class="font-medium color-heading mb-1">{{ __('Your Meeting Details') }}</p>
    <p class="font-16 mb-1">{{ __('Meeting Date & Time') }}: <span class="meetingDateTime color-heading"></span></p>
    <p class="font-16 mb-1">{{ __('Total Duration') }}: <span class="meetingDuration color-heading"></span></p>
    <p class="font-16 mb-1">{{ __('Total Cost') }}: <span class="meetingCost color-heading"></span></p>
    <input type="hidden" class="consultation_slot_id">
</div>

<script>
    "use strict"
    $(document).ready(function () {
        $("input[name='time']").click(function () {
            var time = $("input[name='time']:checked").val();
            var duration = $(this).data('item').duration;
            var hour_duration = $(this).data('item').hour_duration;
            var minute_duration = $(this).data('item').minute_duration;
            var hourly_rate = $('.hourly_rate').val();

            var minuteCost = 0;
            if (minute_duration > 0){
                minuteCost = (parseFloat(hourly_rate) / (60 / parseFloat(minute_duration)));
            }

            var cost = ((parseFloat(hour_duration) * parseFloat(hourly_rate)) + minuteCost).toFixed(2)
            var bookingDate = $('.bookingDate').val();

            $('.meetingDetails').removeClass('d-none')
            $('.meetingDetails').addClass('d-block')

            $('.meetingDateTime').html(bookingDate + ' | ' + time)
            $('.meetingDuration').html(duration)

            var currency_symbol = "{{ $currencySymbol ?? get_currency_symbol() }}";
            var currency_placement = "{{ $currencyPlacement ?? get_currency_placement() }}";
            var totalCost = 0;
            if(currency_placement == 'after'){
                totalCost = cost + ' ' + currency_symbol;
            } else {
                totalCost = currency_symbol + ' ' + cost ;
            }
            $('.meetingCost').html(totalCost)
            $('.consultation_slot_id').val($(this).data('item').id)
        });
    });
</script>
