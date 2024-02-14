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
                                <h2>{{ __('Menus') }}</h2>
                            </div>
                        </div>
                        <div class="breadcrumb__content__right">
                            <nav aria-label="breadcrumb">
                                <ul class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">{{__('Dashboard')}}</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">{{ __('Menu') }}</li>
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
                            <h2>{{ __('Menu List') }}</h2>
                            <button class="btn btn-success btn-sm" type="button" data-bs-toggle="modal" data-bs-target="#add-todo-modal">
                                <i class="fa fa-plus"></i> {{ __('Add Menu') }}
                            </button>
                        </div>
                        <div class="customers__table">
                            <table id="customers-table" class="row-border data-table-filter table-style">
                                <thead>
                                <tr>
                                    <th>{{ __('Title') }}</th>
                                    <th>{{ __('URL') }}</th>
                                    <th>{{ __('Status') }}</th>
                                    <th>{{__('Action')}}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($menus as $menu)
                                    <tr class="removable-item">
                                        <td>{{$menu->name}}</td>
                                        <td>{{ route('page', @$menu->page->slug) }}</td>
                                        <td>
                                            @if($menu->status == 1) {{ __('Active') }} @endif
                                            @if($menu->status == 2) {{ __('Deactivated') }} @endif
                                        </td>
                                        <td>
                                            <div class="action__buttons">
                                                <a class=" btn-action mr-1 edit" data-item="{{ $menu }}" data-updateurl="{{ route('menu.dynamic.update', @$menu->id) }}"
                                                   data-toggle="tooltip"
                                                   title="Edit">
                                                    <img src="{{asset('admin/images/icons/edit-2.svg')}}" alt="edit">
                                                </a>
                                                <a href="javascript:void(0);" data-url="{{route('menu.dynamic.delete', [$menu->id])}}" class="btn-action delete" title="Delete">
                                                    <img src="{{asset('admin/images/icons/trash-2.svg')}}" alt="trash">
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            <div class="mt-3">
                                {{$menus->links()}}
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
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <h5>{{ __('Add Menu') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <form action="{{ route('menu.dynamic.store') }}" method="post">
                    @csrf
                    <div class="modal-body">
                        <div class="input__group mb-25">
                            <label for="name">{{ __('Name') }}</label>
                            <input type="text" name="name" id="name" placeholder="{{ __('Type name') }}" value="" required>
                            @if ($errors->has('name'))
                                <span class="text-danger"><i class="fas fa-exclamation-triangle"></i> {{ $errors->first('name') }}</span>
                            @endif
                        </div>
                        <div class="input__group mb-25">
                            <label for="url">{{ __('URL') }}</label>
                            <select name="url" id="url" class="form-control">
                                <option value="">--{{ __('Select Option') }}--</option>
                                @foreach($urls as $url)
                                    <option value="{{ $url->id }}">{{ url('/').'/page/'.$url->slug }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="input__group">
                            <label for="status" class="text-lg-right text-black"> {{__('Status')}} </label>
                            <select name="status" id="status" class="form-control">
                                <option value="">--{{ __('Select Option') }}--</option>
                                <option value="1">{{ __('Active') }}</option>
                                <option value="2" >{{ __('Deactivated') }}</option>
                            </select>
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
                    <h5 class="modal-title">{{ __('Edit Menu') }}</h5>
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

                        <div class="input__group mb-25">
                            <label for="name">{{ __('URL') }}</label>
                            <select name="url" id="url" class="form-control">
                                <option value="">--{{ __('Select Option') }}--</option>
                                @foreach($urls as $url)
                                    <option value="{{ $url->id }}">{{ url('/').'/page/'.$url->slug }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="input__group">
                            <label for="status" class="text-lg-right text-black"> {{__('Status')}} </label>
                            <select name="status" id="status" class="form-control">
                                <option value="">--{{ __('Select Option') }}--</option>
                                <option value="1">{{ __('Active') }}</option>
                                <option value="2" >{{ __('Deactivated') }}</option>
                            </select>
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
                modal.find('select[name=status]').val($(this).data('item').status)
                modal.find('select[name=url]').val($(this).data('item').url)
                let route = $(this).data('updateurl');
                $('#updateEditModal').attr("action", route)
                modal.modal('show')
            })
        })
    </script>
@endpush
