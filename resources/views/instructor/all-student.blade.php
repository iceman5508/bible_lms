@extends('layouts.instructor')

@section('breadcrumb')
    <div class="page-banner-content text-center">
        <h3 class="page-banner-heading text-white pb-15"> {{__('All Students')}} </h3>

        <!-- Breadcrumb Start-->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb justify-content-center">
                <li class="breadcrumb-item font-14"><a href="{{route('instructor.dashboard')}}">{{__('Dashboard')}}</a></li>
                <li class="breadcrumb-item font-14 active" aria-current="page">{{__('All Students')}}</li>
            </ol>
        </nav>
    </div>
@endsection

@section('content')
    <div class="instructor-profile-right-part">
        <div class="instructor-quiz-list-page instructor-all-student-page">

            <div class="instructor-my-courses-title d-flex justify-content-between align-items-center">
                <h6>{{__('All Students')}}</h6>

                <div class="dropdown all-student-filter-dropdown">
                    <button class="all-student-filter-dropdown-btn color-heading font-18 font-medium d-inline-flex align-items-center" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                        <span class="iconify" data-icon="bx:filter-alt"></span>{{ __('Filter') }}
                    </button>
                    <div class="dropdown-menu  {{selectedLanguage()->rtl == 1 ? 'dropdown-menu-start' : 'dropdown-menu-end' }}" aria-labelledby="dropdownMenuButton1">
                      <form action="{{ route('instructor.all-student') }}" name="get">
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <div class="label-text-title color-heading font-medium font-16 mb-2">{{__('Search By Name')}}</div>

                                <input type="search" name="search_name" class="form-control" placeholder="{{ __('Search by Name') }}" value="{{ app('request')->search_name }}">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label class="label-text-title color-heading font-medium font-16 mb-2">{{__('Search By Course')}}</label>
                                <select class="form-select" name="course_id">
                                    <option value="">{{__('Select Course')}}</option>
                                    @foreach($courses as $course)
                                    <option value="{{ $course->id }}" @if(app('request')->course_id == $course->id) selected @endif>{{ $course->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <button type="submit" class="theme-btn default-hover-btn theme-button1">{{__('Search')}}</button>
                      </form>
                    </div>
                </div>

            </div>

            <div class="row">
                @if(count($enrollments) > 0)
                <div class="col-12">
                    <div class="table-responsive table-responsive-xl">
                        <table class="table">
                            <thead>
                            <tr>
                                <th scope="col">{{__('Image')}}</th>
                                <th scope="col">{{__('Name')}}</th>
                                <th scope="col">{{__('Email')}}</th>
                                <th scope="col">{{__('Course Name')}}</th>
                                <th scope="col">{{__('Enroll Date')}}</th>
                                <th scope="col">{{__('Action')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($enrollments as $enrollment)
                                <tr>
                                    <td><div class="all-student-img-wrap"><img src="{{ getImageFile(@$enrollment->user->image_path) }}" alt="img" class="img-fluid"></div></td>
                                    <td>{{ @$enrollment->user->name }}</td>
                                    <td>{{ @$enrollment->user->email }}</td>
                                    <td>{{ @$enrollment->course->title }}</td>
                                    <td>{{ $enrollment->start_date }}
                                    </td>
                                    <td>
                                        <div class="red-blue-action-btns">
                                            <button type="button" data-country="{{@$enrollment->user->student->country->country_name}}" data-image="{{ getImageFile($enrollment->user->image) }}" data-item="{{ $enrollment }}"
                                                    data-purchase_date="{{ @$enrollment->start_date }}" data-bs-toggle="modal" data-bs-target="#allStudentViewModal"
                                                    class="theme-btn theme-button1 green-theme-btn default-hover-btn viewStudent">{{ __('View') }}</button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Pagination Start -->
                    @if(@$enrollments->hasPages())
                        {{ @$enrollments->links('frontend.paginate.paginate') }}
                    @endif
                <!-- Pagination End -->
                @else
                    <!-- If there is no data Show Empty Design Start -->
                    <div class="empty-data">
                        <img src="{{ asset('frontend/assets/img/empty-data-img.png') }}" alt="img" class="img-fluid">
                        <h5 class="my-3">{{__('Empty Student')}}</h5>
                    </div>
                    <!-- If there is no data Show Empty Design End -->
                @endif
            </div>

        </div>
    </div>

@endsection

@section('modal')

<!--All Student View Modal Start-->
<div class="modal fade viewStudentModal" id="allStudentViewModal" tabindex="-1" aria-labelledby="allStudentViewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="allStudentViewModalLabel">{{__('Student Information')}}</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <div class="all-student-modal-img d-flex align-items-center">
                                <div class="all-student-img-wrap "><img src="" alt="img" class="img-fluid user_image"></div><span class="user_name"></span>
                            </div>
                            <div class="student-detail-info-row">
                                <div class="all-student-modal-inner row">
                                    <div class="all-student-info-title col-sm-6 col-md-6 col-lg-4">{{ __('Course Name') }} <span>:</span></div><div class="all-student-info-value col-sm-6 col-md-6 col-lg-8 course_name"></div>
                                </div>
                                <div class="all-student-modal-inner row">
                                    <div class="all-student-info-title col-sm-6 col-md-6 col-lg-4">{{ __('Course Join Date') }} <span>:</span></div><div class="all-student-info-value col-sm-6 col-md-6 col-lg-8 purchase_date"></div>
                                </div>
                                <div class="all-student-modal-inner row">
                                    <div class="all-student-info-title col-sm-6 col-md-6 col-lg-4">{{ __('Email') }} <span>:</span></div><div class="all-student-info-value col-sm-6 col-md-6 col-lg-8 email"></div>
                                </div>
                                <div class="all-student-modal-inner row">
                                    <div class="all-student-info-title col-sm-6 col-md-6 col-lg-4">{{ __('Phone') }} <span>:</span></div><div class="all-student-info-value col-sm-6 col-md-6 col-lg-8 phone"></div>
                                </div>

                                <div class="all-student-modal-inner row">
                                    <div class="all-student-info-title col-sm-6 col-md-6 col-lg-4">{{ __('Country') }} <span>:</span></div><div class="all-student-info-value col-sm-6 col-md-6 col-lg-8 country"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer d-flex justify-content-between align-items-center">
                <button type="button" class="theme-btn theme-button1 default-hover-btn default-back-btn" data-bs-dismiss="modal">{{ __('Back') }}</button>
            </div>
        </div>
    </div>
</div>
<!--All Student View Modal End-->
@endsection

@push('script')
    <script src="{{ asset('frontend/assets/js/instructor/view-student.js') }}"></script>
@endpush
