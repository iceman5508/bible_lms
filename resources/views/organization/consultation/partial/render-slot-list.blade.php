@forelse($slots as $slot)
    <div class="col-sm-6 col-md-6">
        <div class="input-group add-slot-day-item mb-3">
            <input type="text" class="form-control time_added_field" name="field_name[]"
                placeholder="Ex. 9:00 AM - 10:00 AM" value="{{ $slot->time }}" disabled>
            <span class="input-group-text bg-transparent border-0 cursor deleteTimeSlot"
                data-route="{{ route('organization.consultation.slotDelete', $slot->id) }}"><span class="iconify"
                    data-icon="fluent:delete-48-filled"></span></span>
        </div>
    </div>
@empty
    <div class="col-sm-12 col-md-12">
        <div class="input-group add-slot-day-item mb-3">
            <span>{{ __('No Record Found') }}</span>
        </div>
    </div>
@endforelse
