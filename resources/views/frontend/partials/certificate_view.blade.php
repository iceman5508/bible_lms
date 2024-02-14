<div class="row align-items-center">
    <div class="col-md-12">
        @if (!is_null($certificate))
            <div class="thankyou-box text-center bg-white px-5 mt-5">
                <img src="{{ asset('frontend/assets/img/thank-you-img.png') }}" alt="img" class="img-fluid">
                <h6 class="mt-5">{{ __('This certificate is valid') }}</h6>
            </div>
        @else
            <div class="thankyou-box text-center bg-white px-5 mt-5">
                <img src="{{ asset('frontend/assets/img/certificate-verify-faield.png') }}" alt="img"
                    class="img-fluid">
                <h6 class="mt-5">{{ __('The Certificate is invalid with the following information') }}</h6>
            </div>
        @endif

    </div>
</div>
@if (!is_null($certificate))
    <div class="row align-items-center justify-content-center mt-5">
        <div class="col-md-12">
            <div class="certificate-result-box pt-5">
                <div class="certificate-result-inner-box mx-auto">
                    
                    <div class="">
                        @php 
                        $ext = pathinfo(public_path($certificate->path), PATHINFO_EXTENSION);
                        @endphp
                        @if($ext == 'pdf')
                        <embed src="{{ asset($certificate->path) }}#toolbar=0&navpanes=0&scrollbar=0" width="100%" height="450px" />
                        @else
                        <img class="img-fluid" src="{{ asset($certificate->path) }}" alt="{{ $certificate->path }}">
                        @endif
                    </div>
                    <div class="certificate-verify-result-left mt-3">
                        <div class="table-responsive">
                            <table class="table">
                                <tbody>
                                    <tr>
                                        <th>{{ __('Course') }}:</th>
                                        <td>{{ $certificate->course->title }}</td>
                                    </tr>
                                    <tr>
                                        <th>{{ __('Student') }}:</th>
                                        <td>{{ $certificate->student->name }}</td>
                                    </tr>
                                    <tr>
                                        <th>{{ __('Certificate Number') }}:</th>
                                        <td>{{ $certificate->certificate_number }}</td>
                                    </tr>
                                    <tr>
                                        <th>{{ __('Asign Date') }}:</th>
                                        <td>{{ date('Y-m-d', strtotime($certificate->created_at)) }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif
