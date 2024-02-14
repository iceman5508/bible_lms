<div class="tab-pane fade" id="Certificate" role="tabpanel" aria-labelledby="Certificate-tab">
    <div class="row">
        <div class="col-12">
            <div class="after-purchase-course-watch-tab bg-white p-30">
                @if($course->studentCertificate)
                    <!-- Course watch Certificate Start -->
                    <div class="course-watch-certificate-wrap">
                        <div class="watch-course-tab-inside-top-heading d-flex justify-content-between align-items-center">
                            <h6>{{__('Certificate')}}</h6>
                            <div class="course-watch-certificate-download-btns">
                                <a href="{{ asset($course->studentCertificate->path) }}" target="_blank" class="theme-btn theme-button1 default-hover-btn"><span class="iconify" data-icon="fluent:print-32-regular"></span>{{ __('Prints') }}</a>
                                <a href="{{ asset($course->studentCertificate->path) }}" download="" class="theme-btn theme-button1 green-theme-btn default-hover-btn"><span class="iconify" data-icon="heroicons-outline:download"></span>{{ __('Download') }}</a>
                            </div>
                        </div>

                        <div class="course-watch-certificate-img text-center">
                            @php 
                                $ext = pathinfo(public_path($course->studentCertificate->path), PATHINFO_EXTENSION);
                            @endphp
                            @if($ext == 'pdf')
                            <iframe src="{{ asset($course->studentCertificate->path) }}#toolbar=0&navpanes=0&scrollbar=0" class="certificate-pdf-iframe" width="750" height="500"></iframe>
                            @else
                            <img  class="img-fluid" src="{{ asset($course->studentCertificate->path) }}" alt="{{ $course->studentCertificate->path }}">
                            @endif
                        </div>

                    </div>
                    <!-- Course watch Certificate End -->
                @else
                    <!-- If there is no data Show Empty Design Start -->
                    <div class="empty-data">
                        <img src="{{ asset('frontend/assets/img/empty-data-img.png') }}" alt="img" class="img-fluid">
                        <h5 class="my-3">{{ __('After completing the course, you will receive a certificate.') }}</h5>
                    </div>
                    <!-- If there is no data Show Empty Design End -->
                @endif
            </div>
        </div>
    </div>
</div>
