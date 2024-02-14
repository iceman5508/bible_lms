@extends('layouts.organization')

@section('breadcrumb')
    <div class="page-banner-content text-center">
        <h3 class="page-banner-heading text-white pb-15"> {{ __('All Student') }} </h3>

        <!-- Breadcrumb Start-->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb justify-content-center">
                <li class="breadcrumb-item font-14"><a href="{{ route('organization.dashboard') }}">{{ __('Dashboard') }}</a>
                </li>
                <li class="breadcrumb-item font-14 active" aria-current="page">{{ __('All Student') }}</li>
            </ol>
        </nav>
    </div>
@endsection

@section('content')
    <div class="instructor-profile-right-part">
        <div class="instructor-quiz-list-page instructor-all-student-page">
            <div class="instructor-my-courses-title d-flex justify-content-between align-items-center">
                <h6>{{ __('All Student') }}</h6>
            </div>
            <div class="row">
                @if (count($students) > 0)
                    <div class="col-12">
                        <div class="table-responsive table-responsive-xl">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th scope="col">{{ __('Image') }}</th>
                                        <th scope="col">{{ __('Name') }}</th>
                                        <th scope="col">{{ __('Email') }}</th>
                                        <th scope="col">{{ __('Phone Number') }}</th>
                                        <th scope="col">{{ __('Status') }}</th>
                                        <th scope="col">{{ __('Action') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($students as $student)
                                        <tr>
                                            <td>
                                                <div class="all-student-img-wrap">
                                                    <img src="{{ getImageFile(@$student->user->image_path) }}"
                                                        alt="img" class="img-fluid">
                                                </div>
                                            </td>
                                            <td>{{ @$student->user->name }}</td>
                                            <td>{{ @$student->user->email }}</td>
                                            <td>{{ @$student->user->mobile_number }}</td>
                                            <td>
                                                <span id="hidden_id" style="display: none">{{ $student->id }}</span>
                                                <select name="status" class="status form-control form-select">
                                                    <option value="1" {{ $student->status == 1 ? 'selected' : '' }}>{{ __('Approved') }}</option>
                                                    <option value="2" {{ $student->status == 2 ? 'selected' : '' }}>{{ __('Rejected') }}</option>
                                                </select>
                                            </td>
                                            <td>
                                                <a href="{{ route('organization.student.edit', $student->uuid) }}"
                                                    class=""><img src="{{ asset('admin/images/icons/edit-2.svg') }}"
                                                        alt="eye"></a>
                                                <a href="{{ route('organization.student.view', $student->uuid) }}"
                                                    class="ms-2"><img src="{{ asset('admin/images/icons/eye-2.svg') }}"
                                                        alt="eye"></a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Pagination Start -->
                    @if (@$students->hasPages())
                        {{ @$students->links('frontend.paginate.paginate') }}
                    @endif
                    <!-- Pagination End -->
                @else
                    <!-- If there is no data Show Empty Design Start -->
                    <div class="empty-data">
                        <img src="{{ asset('frontend/assets/img/empty-data-img.png') }}" alt="img" class="img-fluid">
                        <h5 class="my-3">{{ __('Empty Student') }}</h5>
                    </div>
                    <!-- If there is no data Show Empty Design End -->
                @endif
            </div>

        </div>
    </div>

@endsection

@push('script')
    <script src="{{ asset('frontend/assets/js/custom/organization.js') }}"></script>
    <script>
        'use strict'
        $(".status").change(function() {
            var id = $(this).closest('tr').find('#hidden_id').html();
            var status_value = $(this).closest('tr').find('.status option:selected').val();
            Swal.fire({
                title: "{{ __('Are you sure to change status?') }}",
                text: "{{ __('You will be able to revert this!') }}",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "{{ __('Yes, Change it!') }}",
                cancelButtonText: "{{ __('No, cancel!') }}",
                reverseButtons: true
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        type: "POST",
                        url: "{{ route('organization.student.status') }}",
                        data: {
                            "status": status_value,
                            "id": id,
                            "_token": "{{ csrf_token() }}",
                        },
                        datatype: "json",
                        success: function(res) {
                            toastr.options.positionClass = 'toast-bottom-right';
                            if (res.status == true) {
                                toastr.success('', res.message);
                            } else {
                                toastr.error('', res.message);
                            }
                        },
                        error: function(error) {
                            toastr.options.positionClass = 'toast-bottom-right';
                            toastr.error('', JSON.parse(error.responseText).message);
                        },
                    });
                } else if (result.dismiss === "cancel") {}
            });
        });
    </script>
@endpush
