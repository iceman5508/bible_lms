<div class="row">
    @forelse($assignmentSubmitsPending as $assignmentSubmitPending)
        <!-- Assignment Assessment Item Start -->
        <div class="col-12 col-md-6 col-lg-6 col-xl-6 col-xxl-4">
            <div class="card assignment-assessment-item">
                <form action="{{ route('assignment.assessment.update', $assignmentSubmitPending->uuid) }}" method="post">
                    @csrf
                    <div class="assignment-img-wrap mb-20">
                        <img src="{{ getImageFile(@$assignmentSubmitPending->user->image_path) }}" alt="img">
                    </div>
                    <div class="card-body p-0">
                        <h6 class="card-title mb-2">{{ @$assignmentSubmitPending->user->name }}</h6>
                        <p class="card-text">{{ __('Email') }} : {{ @$assignmentSubmitPending->user->email }}</p>
                        <div class="assignment-btn-group d-flex mt-20 mb-2">
                            <a href="{{ getVideoFile($assignmentSubmitPending->file) }}" class="theme-btn bg-green text-white mb-2 downloadAssignment" data-id="{{ $assignmentSubmitPending->id }}">
                                <span class="iconify" data-icon="akar-icons:download"></span>{{ __('Download') }}
                            </a>
                            <input name="marks" class="mb-2" type="number" min="0" step="any" placeholder="{{ __('Marks') }}" required
                                   value="{{ $assignmentSubmitPending->marks }}">
                        </div>
                        <div class="col-md-12">
                            <label class="label-text-title color-heading font-medium font-16 mb-1">{{ __('Notes') }}</label>
                            <textarea name="notes" class="form-control" cols="30" rows="10"
                                      placeholder="{{ __('Write your note here') }}">{{ $assignmentSubmitPending->notes }}</textarea>
                        </div>

                        <button type="submit" class="theme-btn theme-button1 default-hover-btn">{{ __('Submit') }}</button>
                    </div>
                </form>
            </div>
        </div>
        <!-- Assignment Assessment Item End -->
    @empty
        <!-- If there is no data Show Empty Design Start -->
        <div class="empty-data">
            <img src="{{ asset('frontend/assets/img/empty-data-img.png') }}" alt="img" class="img-fluid">
            <h5 class="my-3">{{ __('Empty Assignment Assessment') }}</h5>
        </div>
        <!-- If there is no data Show Empty Design End -->
    @endforelse
    <!-- Pagination Start -->
    @if(@$assignmentSubmitsPending->hasPages())
        {{ @$assignmentSubmitsPending->links('frontend.paginate.paginate') }}
    @endif
    <!-- Pagination End -->
</div>
