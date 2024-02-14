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
                                <h2>{{__('All Users')}}</h2>
                            </div>
                        </div>
                        <div class="breadcrumb__content__right">
                            <nav aria-label="breadcrumb">
                                <ul class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">{{__('Dashboard')}}</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">{{__('All Users')}}</li>
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
                            <h2>{{__('All Users')}}</h2>
                            <a href="{{route('user.create')}}" class="btn btn-success btn-sm"> <i class="fa fa-plus"></i> {{__('Add User')}} </a>
                        </div>
                        <div class="customers__table">
                            <table id="customers-table" class="row-border data-table-filter table-style">
                                <thead>
                                <tr>
                                    <th>{{__('Name')}}</th>
                                    <th>{{__('Email')}}</th>
                                    <th>{{__('Address')}}</th>
                                    <th>{{__('Phone')}}</th>
                                    <th>{{__('Role')}}</th>
                                    <th>{{__('Status')}}</th>
                                    <th>{{__('Action')}}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($users as $user)
                                    <tr class="removable-item">
                                        <td>
                                            {{$user->name}}
                                        </td>
                                        <td>
                                            {{$user->email}}
                                        </td>
                                        <td>
                                            {{$user->address}}
                                        </td>
                                        <td>
                                            {{$user->phone_number}}
                                        </td>
                                        <td>  @if(count($user->getRoleNames()) > 0) {{$user->getRoleNames()[0] }}@endif </td>
                                        <td>  @if($user->deleted_at == null)
                                                Active
                                            @else
                                                Deleted
                                            @endif
                                        </td>
                                        <td>
                                            @if($user->deleted_at == null)
                                                <div class="action__buttons">
                                                    <a href="{{route('user.edit', [$user->id])}}" class="btn-action" title="Edit User">
                                                        <img src="{{asset('admin/images/icons/edit-2.svg')}}" alt="edit">
                                                    </a>
                                                    <a href="javascript:void(0);" data-url="{{route('user.delete', [$user->id])}}" class="btn-action delete" title="Delete User">
                                                        <img src="{{asset('admin/images/icons/trash-2.svg')}}" alt="trash">
                                                    </a>
                                                </div>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            <div class="mt-3">
                                {{$users->links()}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <!-- Page content area end -->
@endsection

@push('style')
    <link rel="stylesheet" href="{{asset('admin/css/jquery.dataTables.min.css')}}" />
@endpush

@push('script')
    <script src="{{asset('admin/js/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('admin/js/custom/data-table-page.js')}}"></script>
@endpush
