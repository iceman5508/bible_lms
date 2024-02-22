<div class="row">
    @forelse($assignmentSubmitsDone as $assignmentSubmitDone)
        <!-- Assignment Assessment Item Start -->
        <div class="col-12 col-md-6 col-lg-6 col-xl-6 col-xxl-4">
            <div class="card assignment-assessment-item done-assignment-item">
                <form>
                    <div class="assignment-img-wrap mb-20">
                        <img src="{{ getImageFile(@$assignmentSubmitDone->user->image_path) }}" alt="img">
                    </div>
                    <div class="card-body p-0">
                        <h6 class="card-title mb-2"><span class="user_name">{{ @$assignmentSubmitDone->user->name }}</span></h6>
                        <p class="card-text">Email : <span class="user_email">{{ @$assignmentSubmitDone->user->email }}</span></p>
                        <div class="assignment-btn-group d-flex mt-20 mb-2">
                            <a href="{{ getVideoFile($assignmentSubmitDone->file) }}" class="theme-btn bg-green text-white mb-2 downloadAssignment" data-id="{{ $assignmentSubmitDone->id }}">
                                <span class="iconify" data-icon="akar-icons:download"></span>{{ __('Download') }}
                            </a>
                            <input type="text" readonly value="{{ @$assignmentSubmitDone->marks}}" placeholder="{{ __('Marks') }}">
                        </div>
                        <div class="col-md-12">
                            <label class="label-text-title color-heading font-medium font-16 mb-1">{{ __('Notes') }}</label>
                            <p class="assignment-notes-text">{{ @$assignmentSubmitDone->notes }}</p>
                        </div>
                        <!-- Button trigger modal -->
                        <button type="button" class="theme-btn assignment-edit-btn updateMark"
                                data-updateroute="{{ route('organization.assignment.assessment.update', $assignmentSubmitDone->uuid) }}"
                                data-item="{{ $assignmentSubmitDone }}" data-bs-toggle="modal" data-bs-target="#assignmentEditModal">{{ __('Edit') }}
                        </button>
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
    @if(@$assignmentSubmitsDone->hasPages())
        {{ @$assignmentSubmitsDone->links('frontend.paginate.paginate') }}
    @endif
    <!-- Pagination End -->
</div>

