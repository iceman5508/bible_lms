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
                        <div class="item-top mb-30"><h2>{{ __(@$title) }}</h2></div>
                        <div class="customers__table">
                            <table id="customers-table" class="row-border data-table-filter table-style">
                                <thead>
                                <tr>
                                    <th width="25%">{{__('Page Name')}}</th>
                                    <th>{{__('Meta Content')}}</th>
                                    <th width="5%">{{__('Action')}}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($metas as $meta)
                                    <tr>
                                        <td>
                                            {{$meta->page_name}}
                                        </td>
                                        <td>
                                            <div class="mb-2">
                                                <b>{{ __('Meta Title') }}: </b> {{ $meta->meta_title }}
                                            </div>
                                            <div class="mb-2">
                                                <b>{{ __('Meta Description') }}: </b> {{ $meta->meta_description }}
                                            </div>
                                            <div>
                                                <b>{{ __('Meta Keywords') }}: </b> {{ $meta->meta_keyword }}
                                            </div>
                                            <div>
                                                <b>{{ __('OG Image') }}: </b> <a target="__blank" class="font-bold text-info" href="{{ getImageFile($meta->og_image) }}">{{ __("View") }}</a>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="action__buttons">
                                                <a href="{{route('settings.meta.edit', [$meta->uuid])}}" class="btn-action">
                                                    <img src="{{asset('admin/images/icons/edit-2.svg')}}" alt="edit">
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            <div class="mt-3">
                                {{$metas->links()}}
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

    <link rel="stylesheet" href="{{asset('admin/css/custom/image-preview.css')}}">
@endpush

@push('script')
    <script src="{{asset('admin/js/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('admin/js/custom/data-table-page.js')}}"></script>

    <script src="{{asset('admin/js/custom/image-preview.js')}}"></script>
@endpush
