@extends('layouts.admin')

@section('content')
    <!-- Page content area start -->
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="breadcrumb__content">
                        <div class="breadcrumb__content__left">
                            <div class="breadcrumb__title">
                                <h2>{{ __(@$title) }}</h2>
                            </div>
                        </div>
                        <div class="breadcrumb__content__right">
                            <nav aria-label="breadcrumb">
                                <ul class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">{{__('Dashboard')}}</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">{{ __(@$title) }}</li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="customers__area bg-style mb-30">
                        <div class="item-title d-flex justify-content-between">
                            <h2>{{ __('Forum Category') }}</h2>
                            <button class="btn btn-success btn-sm" type="button" data-bs-toggle="modal" data-bs-target="#add-todo-modal">
                                <i class="fa fa-plus"></i> {{ __('Add Forum Category') }}
                            </button>
                        </div>
                        <div class="customers__table">
                            <table id="customers-table" class="row-border data-table-filter table-style">
                                <thead>
                                <tr>
                                    <th>{{ __('SL') }}</th>
                                    <th>{{ __('Logo') }}</th>
                                    <th>{{__('Title')}}</th>
                                    <th>{{ __('Subtitle') }}</th>
                                    <th>{{__('Status')}}</th>
                                    <th>{{__('Action')}}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($forumCategories as $category)
                                    <tr class="removable-item">
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            <div class="user-info">
                                                <div class="user-info__img">
                                                    <img src="{{getImageFile($category->logo)}}" alt="logo">
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{Str::limit($category->title, 20)}}</td>
                                        <td>{{Str::limit($category->subtitle, 60)}}</td>
                                        <td>
                                            @if($category->status == 1)
                                                <span class="status bg-green">{{ __('Active') }}</span>
                                            @else
                                                <span class="status bg-red">{{ __('Disable') }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="action__buttons">
                                                <a class=" btn-action mr-1 edit" data-logo="{{getImageFile($category->logo)}}" data-item="{{ $category }}"
                                                   data-updateurl="{{ route('admin.forum.category.update', @$category->uuid) }}" data-toggle="tooltip" title="Edit">
                                                    <img src="{{asset('admin/images/icons/edit-2.svg')}}" alt="edit">
                                                </a>
                                                <a href="javascript:void(0);" data-url="{{route('admin.forum.category.delete', [$category->uuid])}}" class="btn-action delete"
                                                   title="Delete">
                                                    <img src="{{asset('admin/images/icons/trash-2.svg')}}" alt="trash">
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            <div class="mt-3">
                                {{$forumCategories->links()}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <!-- Page content area end -->

    <!-- Add Modal section start -->
    <div class="modal fade" id="add-todo-modal" aria-hidden="true" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('Add Forum Category') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('admin.forum.category.store') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="input__group mb-30">
                            <label for="name">{{ __('Title') }}</label>
                            <input type="text" name="title" id="title" placeholder="{{ __('Type title') }}" value="" required>
                            @if ($errors->has('title'))
                                <span class="text-danger"><i class="fas fa-exclamation-triangle"></i> {{ $errors->first('title') }}</span>
                            @endif
                        </div>
                        <div class="input__group mb-30">
                            <label for="name">{{ __('Subtitle') }}</label>
                            <input type="text" name="subtitle" id="subtitle" placeholder="{{ __('Type title') }}" value="" required>
                            @if ($errors->has('subtitle'))
                                <span class="text-danger"><i class="fas fa-exclamation-triangle"></i> {{ $errors->first('subtitle') }}</span>
                            @endif
                        </div>

                        <div class="input__group mb-30">
                            <label for="status" class="text-lg-right text-black"> {{__('Status')}} </label>
                            <select name="status" id="status" class="form-control">
                                <option value="">--{{ __('Select Option') }}--</option>
                                <option value="1">{{ __('Active') }}</option>
                                <option value="0">{{ __('Disable') }}</option>
                            </select>
                        </div>
                        <div class="input__group mb-30">
                            <label for="status" class="text-lg-right text-black"> {{ __('Logo') }} </label>
                            <div class="upload-img-box mb-25">
                                <img src="">
                                <input type="file" name="logo" id="logo" accept="image/*" onchange="previewFile(this)">
                                <div class="upload-img-box-icon">
                                    <i class="fa fa-camera"></i>
                                </div>
                            </div>
                            @if ($errors->has('logo'))
                                <span class="text-danger"><i class="fas fa-exclamation-triangle"></i> {{ $errors->first('logo') }}</span>
                            @endif
                            <p>{{ __('Accepted Image Files') }}: PNG <br> {{ __('Accepted Size') }}: 60 x 60 (1MB)</p>

                        </div>

                        <div>
                            <button type="submit" class="btn btn-purple">{{ __('Save') }}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Add Modal section end -->

    <!-- Edit Modal section start -->
    <div class="modal fade edit_modal" id="add-todo-modal" aria-hidden="true" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('Edit Forum Category') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="" id="updateEditModal" method="post" enctype="multipart/form-data">
                    @csrf
                    @method('patch')
                    <div class="modal-body">
                        @csrf
                        <div class="input__group mb-30">
                            <label for="name">{{ __('Title') }}</label>
                            <input type="text" name="title" id="title" placeholder="{{ __('Type title') }}" value="" required>
                            @if ($errors->has('title'))
                                <span class="text-danger"><i class="fas fa-exclamation-triangle"></i> {{ $errors->first('title') }}</span>
                            @endif
                        </div>
                        <div class="input__group mb-30">
                            <label for="name">{{ __('Subtitle') }}</label>
                            <input type="text" name="subtitle" id="subtitle" placeholder="{{ __('Type title') }}" value="" required>
                            @if ($errors->has('subtitle'))
                                <span class="text-danger"><i class="fas fa-exclamation-triangle"></i> {{ $errors->first('subtitle') }}</span>
                            @endif
                        </div>
                        <div class="input__group mb-30">
                            <label for="status" class="text-lg-right text-black"> {{__('Status')}} </label>
                            <select name="status" id="status" class="form-control">
                                <option value="">--{{ __('Select Option') }}--</option>
                                <option value="1">{{ __('Active') }}</option>
                                <option value="0">{{ __('Disable') }}</option>
                            </select>
                        </div>
                        <div class="input__group mb-30">
                            <label for="status" class="text-lg-right text-black"> {{ __('Logo') }} </label>
                            <div class="upload-img-box mb-25">
                                <img id="logoSrc" src="">
                                <input type="file" name="logo" id="logo" accept="image/*" onchange="previewFile(this)">
                                <div class="upload-img-box-icon">
                                    <i class="fa fa-camera"></i>
                                </div>
                            </div>
                            @if ($errors->has('logo'))
                                <span class="text-danger"><i class="fas fa-exclamation-triangle"></i> {{ $errors->first('logo') }}</span>
                            @endif
                            <p>{{ __('Accepted Image Files') }}: PNG <br> {{ __('Accepted Size') }}: 60 x 60 (1MB)</p>

                        </div>
                        <div>
                            @updateButton
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Edit Modal section end -->

@endsection

@push('style')
    <link rel="stylesheet" href="{{asset('admin/css/jquery.dataTables.min.css')}}">
    <link rel="stylesheet" href="{{asset('admin/css/custom/image-preview.css')}}">
@endpush

@push('script')
    <script src="{{asset('admin/js/custom/image-preview.js')}}"></script>
    <script src="{{asset('admin/js/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('admin/js/custom/data-table-page.js')}}"></script>

    <script>
        $(function () {
            'use strict'
            $('.edit').on('click', function (e) {
                e.preventDefault();
                const modal = $('.edit_modal');
                modal.find('input[name=title]').val($(this).data('item').title)
                modal.find('input[name=subtitle]').val($(this).data('item').subtitle)
                modal.find('select[name=status]').val($(this).data('item').status)
                $("#logoSrc").attr("src", $(this).data('logo'));
                let route = $(this).data('updateurl');
                $('#updateEditModal').attr("action", route)
                modal.modal('show')
            })
        })
    </script>
@endpush
