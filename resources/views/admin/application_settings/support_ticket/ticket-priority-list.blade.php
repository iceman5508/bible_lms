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
                                <h2>{{ __('Application Settings') }}</h2>
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
                <div class="col-lg-3 col-md-4">
                    @include('admin.application_settings.sidebar')
                </div>
                <div class="col-lg-9 col-md-8">
                    <div class="email-inbox__area bg-style">
                        <div class="item-top mb-30 d-flex justify-content-between">
                            <h2>{{ __(@$title) }}</h2>
                            <button class="btn btn-success btn-sm" type="button" data-bs-toggle="modal" data-bs-target="#add-todo-modal">
                                <i class="fa fa-plus"></i> {{ __('Add Priority') }}
                            </button>
                        </div>
                        <div class="customers__table">
                        <table id="customers-table" class="row-border data-table-filter table-style">
                            <thead>
                            <tr>
                                <th width="25%">{{ __('Name') }}</th>
                                <th width="5%">{{__('Action')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($priorities as $priority)
                                <tr>
                                    <td>
                                        {{$priority->name}}
                                    </td>
                                    <td>
                                        <div class="action__buttons">
                                            <a class=" btn-action mr-1 edit" data-item="{{ $priority }}" data-toggle="tooltip" title="Edit">
                                                <img src="{{asset('admin/images/icons/edit-2.svg')}}" alt="edit">
                                            </a>
                                            <button class="ms-2">
                                                <span data-formid="delete_row_form_{{$priority->uuid}}" class="deleteItem">
                                                    <img src="{{asset('admin/images/icons/trash-2.svg')}}" alt="trash">
                                                </span>
                                            </button>

                                            <form action="{{ route('settings.support-ticket.priority.delete', $priority->uuid) }}" method="post" id="delete_row_form_{{ $priority->uuid }}">
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
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('Add Priority') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('settings.support-ticket.priority.store') }}" method="post">
                    <div class="modal-body">
                        @csrf
                        <div class="input__group">
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
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('Edit Priority') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('settings.support-ticket.priority.store') }}" id="updateEditModal" method="post">
                    @csrf
                    <input type="hidden" name="id">
                    <div class="modal-body">
                        <div class="input__group">
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
                modal.find('input[name=id]').val($(this).data('item').id)
                modal.modal('show')
            })
        })
    </script>
@endpush
