<div class="tab-pane fade" id="Notice" role="tabpanel" aria-labelledby="Notice-tab">
    <div class="row">
        <div class="col-12">
            <div class="after-purchase-course-watch-tab bg-white p-30">

                <!-- If there is no data Show Empty Design Start -->
                <div class="empty-data d-none">
                    <img src="{{ asset('frontend/assets/img/empty-data-img.png') }}" alt="img" class="img-fluid">
                    <h5 class="my-3">{{ __('Empty Notice') }}</h5>
                </div>
                <!-- If there is no data Show Empty Design End -->

                <div class="course-watch-notice-board-wrap">
                    <div class="row">
                        @if(count($notices))
                            <div class="notice-board-title-box text-center mb-30">
                                <h4>{{ __('Notice Board') }}</h4>
                                <img src="{{ asset('frontend/assets/img/notice-board-title-vector.png') }}" alt="img" class="img-fluid">
                            </div>
                            @foreach(@$notices as $notice)
                                <div class="col-lg-12 col-xl-12 col-xxl-6">
                                    <div class="notice-board-box-item">
                                        <h6 class="font-medium mb-15">{{ $notice->created_at->format('d - m - Y') }}</h6>
                                        <div class="notice-board-box">
                                            <div class="notice-board-pin-img text-center">
                                                <img src="{{ asset('frontend/assets/img/notice-board-pin.png') }}" alt="img" class="img-fluid"></div>
                                            <h6 class="font-18 font-medium">{{ $notice->topic }}</h6>
                                            <p>
                                                {{ $notice->details }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <!-- If there is no data Show Empty Design Start -->
                            <div class="empty-data">
                                <img src="{{ asset('frontend/assets/img/empty-data-img.png') }}" alt="img" class="img-fluid">
                                <h5 class="my-3">{{ __('Empty Notice Board') }}</h5>
                            </div>
                            <!-- If there is no data Show Empty Design End -->
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
