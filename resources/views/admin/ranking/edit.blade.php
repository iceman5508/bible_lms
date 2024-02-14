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
                                <h2>{{ __('Edit Badge') }}</h2>
                            </div>
                        </div>
                        <div class="breadcrumb__content__right">
                            <nav aria-label="breadcrumb">
                                <ul class="breadcrumb">
                                    <li class="breadcrumb-item"><a
                                            href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></li>
                                    <li class="breadcrumb-item"><a
                                            href="{{ route('ranking.index') }}">{{ __('Manage Badge') }}</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">{{ __('Edit Badge') }}</li>
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
                            <h2>{{ __('Edit Badge') }}</h2>
                        </div>
                        <form action="{{ route('ranking.update', [$level->uuid]) }}" method="post" class="form-horizontal"
                            enctype="multipart/form-data">
                            @csrf
                            @method('patch')
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="input__group mb-25">
                                                <label for="name">{{ __('Name') }}</label>
                                                <input type="text" name="name" id="name"
                                                    placeholder="{{ __('Type name') }}" value="{{ $level->name }}"
                                                    required>
                                                @if ($errors->has('name'))
                                                    <span class="text-danger"><i class="fas fa-exclamation-triangle"></i>
                                                        {{ $errors->first('name') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <label for="">{{ __('Condition') }}</label>
                                            <div class="input-group mb-25">
                                                <span class="input-group-text">{{ __('From') }}</span>
                                                <input type="number" name="from" step="any" value="{{ $level->from }}"
                                                    class="form-control">
                                                <span class="input-group-text">{{ __('To') }}</span>
                                                <input type="number" name="to" step="0" value="{{ $level->to }}"
                                                    class="form-control">
                                                <span class="input-group-text">{{ getBadgeButtonName($level->type) }}</span>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <label for="">{{ __('Description') }}</label>
                                            <div class="input-group mb-25">
                                                <textarea name="description" placeholder="{{ __('Description') }}" class="form-control" rows="5">{{ $level->description }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="input__group mb-25">
                                                <label for="badge_image">{{ __('Badge Image') }}</label>
                                                <div class="">
                                                    <div class="upload-img-box">
                                                        @if ($level->badge_image)
                                                            <img src="{{ getImageFile($level->image_path) }}">
                                                        @else
                                                            <img src="">
                                                        @endif
                                                        <input type="file" name="badge_image" id="badge_image"
                                                            accept="image/*" onchange="previewFile(this)">
                                                        <div class="upload-img-box-icon">
                                                            <i class="fa fa-camera"></i>
                                                            <p class="m-0">{{ __('Badge Image') }}</p>
                                                        </div>
                                                    </div>
                                                    @if ($errors->has('badge_image'))
                                                        <span class="text-danger"><i
                                                                class="fas fa-exclamation-triangle"></i>
                                                            {{ $errors->first('badge_image') }}</span>
                                                    @endif
                                                    <p class="mb-0 mt-1">{{ __('Accepted') }}: PNG, JPG <br>
                                                        {{ __('Accepted Size') }}: 30 x 30 (100KB)</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-purple">{{ __('Update') }}</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <!-- Page content area end -->
@endsection

@push('style')
    <link rel="stylesheet" href="{{ asset('admin/css/custom/image-preview.css') }}">
@endpush

@push('script')
    <script src="{{ asset('admin/js/custom/image-preview.js') }}"></script>
@endpush
