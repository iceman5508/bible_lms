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
                            <h2>{{ __('Special Promotion Tag') }}</h2>
                        </div>
                    </div>
                    <div class="breadcrumb__content__right">
                        <nav aria-label="breadcrumb">
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">{{__('Dashboard')}}</a></li>
                                <li class="breadcrumb-item">{{ __('Course') }}</li>
                                <li class="breadcrumb-item active" aria-current="page">{{ __('Special Promotion Tag List') }}</li>
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
                        <h2>{{ __('Special Promotion Tag List') }}</h2>
                        <button class="btn btn-success btn-sm" type="button" data-bs-toggle="modal" data-bs-target="#add-todo-modal">
                            <i class="fa fa-plus"></i> {{ __('Add') }}
                        </button>
                    </div>
                    <div class="customers__table">
                        <table id="customers-table" class="row-border data-table-filter table-style">
                            <thead>
                            <tr>
                                <th>{{ __('SL')}}.</th>
                                <th>{{ __('Name') }}</th>
                                <th>{{__('Action')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($specials as $special)
                            <tr class="removable-item">
                                <td>{{ @$loop->iteration }}</td>
                                <td>{{$special->name}}</td>
                                <td>
                                    <div class="action__buttons">
                                        <a href="{{ route('special_promotional_tag.editSpecialPromotionCourse', $special->uuid) }}" class="btn-action mr-1" title="View promotion details">
                                            <button class="btn btn-primary btn-sm">+/- {{ __('Course') }}</button>
                                        </a>
                                        <a class=" btn-action mr-1 edit" data-item="{{ $special }}" data-updateurl="{{ route('special_promotional_tag.update', @$special->uuid) }}"
                                           data-toggle="tooltip"
                                           title="Edit">
                                            <img src="{{asset('admin/images/icons/edit-2.svg')}}" alt="edit">
                                        </a>
                                        <button class="btn-action ms-2 deleteItem" data-formid="delete_row_form_{{$special->uuid}}">
                                            <img src="{{asset('admin/images/icons/trash-2.svg')}}" alt="trash">
                                        </button>

                                        <form action="{{route('special_promotional_tag.delete', [$special->uuid])}}" method="post" id="delete_row_form_{{ $special->uuid }}">
                                            {{ method_field('DELETE') }}
                                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
<!-- Page content area end -->

<!-- Add Modal section start -->
<div class="modal fade" id="add-todo-modal" aria-hidden="true" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header border-0">
                <h5>{{ __('Add Special Promotion Tag') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form action="{{ route('special_promotional_tag.store') }}" method="post">
                @csrf
                <div class="modal-body">
                    <div class="input__group mb-25">
                        <label for="name">{{ __('Name') }}</label>
                        <input type="text" name="name" id="name" placeholder="{{ __('Type name') }}" value="" required>
                        @if ($errors->has('name'))
                            <span class="text-danger"><i class="fas fa-exclamation-triangle"></i> {{ $errors->first('name') }}</span>
                        @endif
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-purple">{{ __('Save') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Add Modal section end -->

<!-- Edit Modal section start -->
<div class="modal fade edit_modal" id="add-todo-modal" aria-hidden="true" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5>{{ __('Edit Special Promotion Tag') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="" id="updateEditModal" method="post">
                @csrf
                @method('patch')
                <div class="modal-body">
                    @csrf
                    <div class="input__group mb-25">
                        <label for="name">{{ __('Name') }}</label>
                        <input type="text" name="name" id="name" placeholder="{{ __('Type name') }}" value="" required>
                        @if ($errors->has('name'))
                            <span class="text-danger"><i class="fas fa-exclamation-triangle"></i> {{ $errors->first('name') }}</span>
                        @endif
                    </div>

                </div>
                <div class="modal-footer">
                    @updateButton
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Edit Modal section end -->

@endsection

@push('style')
<link rel="stylesheet" href="{{asset('admin/css/jquery.dataTables.min.css')}}">
@endpush

@push('script')
<script src="{{asset('admin/js/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('admin/js/custom/data-table-page.js')}}"></script>

<script>
    $(function(){
        'use strict'
        $('.edit').on('click', function(e){
            e.preventDefault();
            const modal = $('.edit_modal');
            modal.find('input[name=name]').val($(this).data('item').name)
            let route = $(this).data('updateurl');
            $('#updateEditModal').attr("action", route)
            modal.modal('show')
        })
    })
</script>
@endpush
