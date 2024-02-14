@extends('frontend.layouts.app')

@section('content')
    <div class="bg-page">
        <!-- Page Header Start -->
        @include('frontend.student.settings.header')
        <!-- Page Header End -->

        <!-- Student Profile Page Area Start -->
        <section class="student-profile-page">
            <div class="container">
                <div class="student-profile-page-content">
                    <div class="row">
                        <div class="col-12">
                            <div class="row bg-white">
                                <!-- Student Profile Left part -->
                                @include('frontend.student.settings.sidebar')

                                <!-- Student Profile Right part -->
                                <div class="col-lg-9 p-0">
                                    <div class="student-profile-right-part">
                                        <h6>{{__('Update Password')}}</h6>
                                        <form action="{{route('student.change-password')}}" method="POST">
                                            @csrf

                                            <div class="row">
                                                <div class="col-md-6 mb-30">
                                                    <label class="font-medium font-15 color-heading">{{__('Old Password')}}</label>
                                                    <input type="password"  name="old_password" value="" class="form-control" placeholder="{{__('Old Password')}}">
                                                    @if ($errors->has('old_password'))
                                                        <span class="text-danger"><i class="fas fa-exclamation-triangle"></i> {{ $errors->first('old_password') }}</span>
                                                    @endif
                                                </div>
                                                <div class="col-md-6 mb-30">
                                                    <label class="font-medium font-15 color-heading">{{__('New Password')}}</label>
                                                    <input type="password" name="new_password" minlength="6" maxlength="12" value="{{$user->new_password}}" class="form-control" placeholder="{{__('New Password')}}">
                                                    @if ($errors->has('new_password'))
                                                        <span class="text-danger"><i class="fas fa-exclamation-triangle"></i> {{ $errors->first('new_password') }}</span>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="col-12 d-flex justify-content-end">
                                                <button type="submit" class="theme-btn theme-button1 theme-button3 font-15 fw-bold">{{__('Update')}}</button>
                                            </div>
                                        </form>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- Student Profile Page Area End -->
    </div>
@endsection

@push('style')
    <link rel="stylesheet" href="{{asset('admin/css/custom/image-preview.css')}}">
@endpush

@push('script')
    <script src="{{asset('admin/js/custom/image-preview.js')}}"></script>
    <script src="{{asset('frontend/assets/js/custom/student-profile.js')}}"></script>
@endpush
