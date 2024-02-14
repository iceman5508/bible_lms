<div class="tab-pane fade" id="Resources" role="tabpanel" aria-labelledby="Resources-tab">
    <div class="row">
        <div class="col-12">
            <div class="after-purchase-course-watch-tab bg-white p-30">
                <!-- If there is no data Show Empty Design Start -->
                <div class="empty-data d-none">
                    <img src="{{ asset('frontend/assets/img/empty-data-img.png') }}" alt="img" class="img-fluid">
                    <h5 class="my-3">{{ __('Empty Resources') }}</h5>
                </div>
                <!-- If there is no data Show Empty Design End -->
                @if(count($course->resources) > 0)
                    <div class="watch-course-tab-inside-top-heading d-flex justify-content-between align-items-center">
                        <h6>Resources({{ count($course->resources) }})</h6>
                    </div>

                    @foreach($course->resources as $resource)
                        <div class="resource-list-text">
                            <span class="iconify" data-icon="akar-icons:link-chain"></span>
                            <a href="{{ getVideoFile($resource->file) }}" class="text-decoration-underline">{{ $resource->original_filename }}</a>
                        </div>
                    @endforeach
                @else
                    <!-- If there is no data Show Empty Design Start -->
                    <div class="empty-data">
                        <img src="{{ asset('frontend/assets/img/empty-data-img.png') }}" alt="img" class="img-fluid">
                        <h5 class="my-3">{{ __('Empty Resources') }}</h5>
                    </div>
                    <!-- If there is no data Show Empty Design End -->
                @endif
            </div>
        </div>
    </div>
</div>
