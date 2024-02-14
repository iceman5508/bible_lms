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
                                <h2>{{__('Settings')}}</h2>
                            </div>
                        </div>
                        <div class="breadcrumb__content__right">
                            <nav aria-label="breadcrumb">
                                <ul class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">{{__('Dashboard')}}</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">{{__('Language Settings')}}</li>
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
                            <h2>{{__('Language Settings')}}</h2>
                            <a href="{{route('language.create')}}" class="btn btn-success btn-sm"> <i class="fa fa-plus"></i> {{__('Add Language')}} </a>
                        </div>

                        <div class="customers__table">
                            <table id="customers-table" class="row-border data-table-filter table-style">
                                <thead>
                                <tr>
                                    <th>{{__('Flag')}}</th>
                                    <th>{{__('Language')}}</th>
                                    <th>{{__('ISO Code')}}</th>
                                    <th>{{ __('RTL') }}</th>
                                    <th>{{__('Action')}}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($languages as $key => $language)
                                    <tr class="removable-item">
                                        <td>
                                            <img src="{{getImageFile($language->flag)}}" height="50">
                                        </td>
                                        <td>{{$language->language}}</td>
                                        <td>{{$language->iso_code}}</td>
                                        <td>{{$language->rtl == 1 ? 'Yes' : 'No'}}</td>
                                        <td>
                                            <div class="action__buttons">
                                                <a href="{{ route('language.edit', [$language->id, $language->iso_code]) }}" class="btn-action" title="Edit">
                                                    <img src="{{asset('admin/images/icons/edit-2.svg')}}" alt="edit">
                                                </a>
                                                @if($language->id != 1)
                                                    <a href="javascript:void(0);" data-url="{{route('language.delete', [$language->id])}}" class="btn-action delete" title="Delete">
                                                        <img src="{{asset('admin/images/icons/trash-2.svg')}}" alt="trash">
                                                    </a>
                                                @endif
                                                <a href="{{route('language.translate', [$language->id])}}" class="btn-action" title="Edit">
                                                    <span class="status edit"> {{ __('Translator') }}</span>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            <div class="mt-3">
                                {{$languages->links()}}
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
    <link rel="stylesheet" href="{{asset('admin/css/jquery.dataTables.min.css')}}">
@endpush

@push('script')
    <script src="{{asset('admin/js/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('admin/js/custom/data-table-page.js')}}"></script>
@endpush
