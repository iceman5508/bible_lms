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
                                <h2>{{ __('Badge') }}</h2>
                            </div>
                        </div>
                        <div class="breadcrumb__content__right">
                            <nav aria-label="breadcrumb">
                                <ul class="breadcrumb">
                                    <li class="breadcrumb-item"><a
                                            href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">{{ __('Badge') }}</li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="customers__area bg-style mb-30">
                        <div class="orders__top affiliate-tab-list-top">
                            <div class="item">
                                <div class="sort-by">
                                    <ul class="nav nav-tabs affiliate-tab-list" id="pills-tab" role="tablist">
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link active" id="pills-registration_date-tab"
                                                data-bs-toggle="pill" data-bs-target="#pills-registration_date"
                                                type="button" role="tab" aria-controls="pills-registration_date"
                                                aria-selected="true">{{ __('Membership') }}</button>
                                        </li>
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link" id="pills-earning-tab" data-bs-toggle="pill"
                                                data-bs-target="#pills-earning" type="button" role="tab"
                                                aria-controls="pills-earning"
                                                aria-selected="true">{{ __('Author Level') }}</button>
                                        </li>
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link" id="pills-courses_count-tab" data-bs-toggle="pill"
                                                data-bs-target="#pills-courses_count" type="button" role="tab"
                                                aria-controls="pills-courses_count"
                                                aria-selected="true">{{ __('Courses Count') }}</button>
                                        </li>
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link" id="pills-students_count-tab" data-bs-toggle="pill"
                                                data-bs-target="#pills-students_count" type="button" role="tab"
                                                aria-controls="pills-students_count"
                                                aria-selected="true">{{ __('Students Count') }}</button>
                                        </li>
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link" id="pills-courses_sale_count-tab"
                                                data-bs-toggle="pill" data-bs-target="#pills-courses_sale_count"
                                                type="button" role="tab" aria-controls="pills-courses_sale_count"
                                                aria-selected="true">{{ __('Courses Sale Count') }}</button>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="item">
                                <form method="POST" action="{{ route('ranking.reset_badge') }}">
                                    @csrf
                                    <button type="submit" class="btn btn-send p-1">{{ __('Refresh user badge') }}</button>
                                </form>
                            </div>
                        </div>
                        <div class="tab-content" id="pills-tabContent">
                            <div class="tab-pane fade show active" id="pills-registration_date" role="tabpanel"
                                aria-labelledby="pills-registration_date-tab">
                                <form action="{{ route('ranking.store') }}" method="post"
                                    enctype="multipart/form-data">
                                    @csrf
                                    <input type="hidden" name="type" value="1">
                                    <div class="row mb-5">
                                        <div class="col-md-6">
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="input__group mb-25">
                                                        <label for="name">{{ __('Name') }}</label>
                                                        <input type="text" name="name" id="name"
                                                            placeholder="{{ __('Type name') }}"
                                                            value="{{ old('name') }}" required>
                                                        @if ($errors->has('name'))
                                                            <span class="text-danger"><i
                                                                    class="fas fa-exclamation-triangle"></i>
                                                                {{ $errors->first('name') }}</span>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <label for="">{{ __('Rule') }}</label>
                                                    <div class="input-group mb-25">
                                                        <span class="input-group-text">{{ __('From') }}</span>
                                                        <input type="number" name="from" step="any"
                                                            value="0" class="form-control">
                                                        <span class="input-group-text">{{ __('To') }}</span>
                                                        <input type="number" name="to" step="0"
                                                            value="0" class="form-control">
                                                        <span class="input-group-text">{{ getBadgeButtonName(RANKING_LEVEL_REGISTRATION) }}</span>
                                                    </div>
                                                    <span>{{ __('Create your rules carefully. So that all the possible ') }}</span>
                                                </div>
                                                <div class="col-12">
                                                    <label for="">{{ __('Description') }}</label>
                                                    <div class="input-group mb-25">
                                                        <textarea name="description" placeholder="{{ __('Description') }}" class="form-control" rows="5"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-6">
                                                    <div class="input__group mb-25">
                                                        <label for="badge_image">{{ __('Badge Image') }}</label>
                                                        <div class="">
                                                            <div class="upload-img-box">
                                                                <img src="">
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
                                            <button type="submit" class="btn btn-purple">{{ __('Save') }}</button>
                                        </div>
                                    </div>
                                    <div class="row ">
                                        <div class="col-md-12 mt-5">
                                            <div class="customers__table">
                                                <table id="" class="row-border data-table-filter table-style">
                                                    <thead>
                                                        <tr>
                                                            <th>{{ __('Image') }}</th>
                                                            <th>{{ __('Name') }}</th>
                                                            <th>{{ __('From') }}</th>
                                                            <th>{{ __('To') }}</th>
                                                            <th>{{ __('Descripiton') }}</th>
                                                            <th>{{ __('Action') }}</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($levels->where('type', RANKING_LEVEL_REGISTRATION) as $level)
                                                            <tr class="removable-item">
                                                                <td>
                                                                    <div class="admin-dashboard-blog-list-img">
                                                                        <img style="width:30px" src="{{ getImageFile($level->image_path) }}">
                                                                    </div>
                                                                </td>
                                                                <td>{{ $level->name }}</td>
                                                                <td>
                                                                    {{ number_format($level->from) }}
                                                                </td>
                                                                <td>{{ number_format($level->to) }}</td>
                                                                <td>{{ $level->description }}</td>
                                                                <td>
                                                                    <div class="action__buttons">
                                                                        <a href="{{ route('ranking.edit', $level->uuid) }}"
                                                                            class=" btn-action mr-1 edit"
                                                                            data-toggle="tooltip" title="Edit">
                                                                            <img style="width:30px" src="{{ asset('admin/images/icons/edit-2.svg') }}"
                                                                                alt="edit">
                                                                        </a>
                                                                        <a href="javascript:void(0);"
                                                                            data-url="{{ route('ranking.delete', [$level->uuid]) }}"
                                                                            class="btn-action delete" title="Delete">
                                                                            <img style="width:30px" src="{{ asset('admin/images/icons/trash-2.svg') }}"
                                                                                alt="trash">
                                                                        </a>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                                <div class="mt-3">
                                                    {{ $levels->links() }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="tab-pane fade" id="pills-earning" role="tabpanel"
                                aria-labelledby="pills-earning-tab">
                                <form action="{{ route('ranking.store') }}" method="post"
                                    enctype="multipart/form-data">
                                    @csrf
                                    <input type="hidden" name="type" value="2">
                                    <div class="row mb-5">
                                        <div class="col-md-6">
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="input__group mb-25">
                                                        <label for="name">{{ __('Name') }}</label>
                                                        <input type="text" name="name" id="name"
                                                            placeholder="{{ __('Type name') }}"
                                                            value="{{ old('name') }}" required>
                                                        @if ($errors->has('name'))
                                                            <span class="text-danger"><i
                                                                    class="fas fa-exclamation-triangle"></i>
                                                                {{ $errors->first('name') }}</span>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <label for="">{{ __('Rule') }}</label>
                                                    <div class="input-group mb-25">
                                                        <span class="input-group-text">{{ __('From') }}</span>
                                                        <input type="number" name="from" step="any"
                                                            value="0" class="form-control">
                                                        <span class="input-group-text">{{ __('To') }}</span>
                                                        <input type="number" name="to" step="0"
                                                            value="0" class="form-control">
                                                        <span class="input-group-text">{{ getBadgeButtonName(RANKING_LEVEL_EARNING) }}</span>
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <label for="">{{ __('Description') }}</label>
                                                    <div class="input-group mb-25">
                                                        <textarea name="description" placeholder="{{ __('Description') }}" class="form-control" rows="5"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-6">
                                                    <div class="input__group mb-25">
                                                        <label for="badge_image">{{ __('Badge Image') }}</label>
                                                        <div class="">
                                                            <div class="upload-img-box">
                                                                <img src="">
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
                                            <button type="submit" class="btn btn-purple">{{ __('Save') }}</button>
                                        </div>
                                    </div>
                                    <div class="row ">
                                        <div class="col-md-12 mt-5">
                                            <div class="customers__table">
                                                <table id="" class="row-border data-table-filter table-style">
                                                    <thead>
                                                        <tr>
                                                            <th>{{ __('Image') }}</th>
                                                            <th>{{ __('Name') }}</th>
                                                            <th>{{ __('From') }}</th>
                                                            <th>{{ __('To') }}</th>
                                                            <th>{{ __('Descripiton') }}</th>
                                                            <th>{{ __('Action') }}</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($levels->where('type', RANKING_LEVEL_EARNING) as $level)
                                                            <tr class="removable-item">
                                                                <td>
                                                                    <div class="admin-dashboard-blog-list-img">
                                                                        <img style="width:30px" src="{{ getImageFile($level->image_path) }}">
                                                                    </div>
                                                                </td>
                                                                <td>{{ $level->name }}</td>
                                                                <td>
                                                                    {{ $level->from }}
                                                                </td>
                                                                <td>{{ $level->to }}</td>
                                                                <td>{{ $level->description }}</td>
                                                                <td>
                                                                    <div class="action__buttons">
                                                                        <a href="{{ route('ranking.edit', $level->uuid) }}"
                                                                            class=" btn-action mr-1 edit"
                                                                            data-toggle="tooltip" title="Edit">
                                                                            <img style="width:30px" src="{{ asset('admin/images/icons/edit-2.svg') }}"
                                                                                alt="edit">
                                                                        </a>
                                                                        <a href="javascript:void(0);"
                                                                            data-url="{{ route('ranking.delete', [$level->uuid]) }}"
                                                                            class="btn-action delete" title="Delete">
                                                                            <img style="width:30px" src="{{ asset('admin/images/icons/trash-2.svg') }}"
                                                                                alt="trash">
                                                                        </a>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                                <div class="mt-3">
                                                    {{ $levels->links() }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="tab-pane fade" id="pills-courses_count" role="tabpanel"
                                aria-labelledby="pills-courses_count-tab">
                                <form action="{{ route('ranking.store') }}" method="post"
                                    enctype="multipart/form-data">
                                    @csrf
                                    <input type="hidden" name="type" value="3">
                                    <div class="row mb-5">
                                        <div class="col-md-6">
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="input__group mb-25">
                                                        <label for="name">{{ __('Name') }}</label>
                                                        <input type="text" name="name" id="name"
                                                            placeholder="{{ __('Type name') }}"
                                                            value="{{ old('name') }}" required>
                                                        @if ($errors->has('name'))
                                                            <span class="text-danger"><i
                                                                    class="fas fa-exclamation-triangle"></i>
                                                                {{ $errors->first('name') }}</span>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <label for="">{{ __('Rule') }}</label>
                                                    <div class="input-group mb-25">
                                                        <span class="input-group-text">{{ __('From') }}</span>
                                                        <input type="number" name="from" step="any"
                                                            value="0" class="form-control">
                                                        <span class="input-group-text">{{ __('To') }}</span>
                                                        <input type="number" name="to" step="0"
                                                            value="0" class="form-control">
                                                        <span class="input-group-text">{{ getBadgeButtonName(RANKING_LEVEL_COURSES_COUNT) }}</span>
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <label for="">{{ __('Description') }}</label>
                                                    <div class="input-group mb-25">
                                                        <textarea name="description" placeholder="{{ __('Description') }}" class="form-control" rows="5"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-6">
                                                    <div class="input__group mb-25">
                                                        <label for="badge_image">{{ __('Badge Image') }}</label>
                                                        <div class="">
                                                            <div class="upload-img-box">
                                                                <img src="">
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
                                            <button type="submit" class="btn btn-purple">{{ __('Save') }}</button>
                                        </div>
                                    </div>
                                    <div class="row ">
                                        <div class="col-md-12 mt-5">
                                            <div class="customers__table">
                                                <table id="" class="row-border data-table-filter table-style">
                                                    <thead>
                                                        <tr>
                                                            <th>{{ __('Image') }}</th>
                                                            <th>{{ __('Name') }}</th>
                                                            <th>{{ __('From') }}</th>
                                                            <th>{{ __('To') }}</th>
                                                            <th>{{ __('Descripiton') }}</th>
                                                            <th>{{ __('Action') }}</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($levels->where('type', RANKING_LEVEL_COURSES_COUNT) as $level)
                                                            <tr class="removable-item">
                                                                <td>
                                                                    <div class="admin-dashboard-blog-list-img">
                                                                        <img
                                                                            style="width:30px" src="{{ getImageFile($level->image_path) }}">
                                                                    </div>
                                                                </td>
                                                                <td>{{ $level->name }}</td>
                                                                <td>
                                                                    {{ number_format($level->from) }}
                                                                </td>
                                                                <td>{{ number_format($level->to) }}</td>
                                                                <td>{{ $level->description }}</td>
                                                                <td>
                                                                    <div class="action__buttons">
                                                                        <a href="{{ route('ranking.edit', $level->uuid) }}"
                                                                            class=" btn-action mr-1 edit"
                                                                            data-toggle="tooltip" title="Edit">
                                                                            <img style="width:30px" src="{{ asset('admin/images/icons/edit-2.svg') }}"
                                                                                alt="edit">
                                                                        </a>
                                                                        <a href="javascript:void(0);"
                                                                            data-url="{{ route('ranking.delete', [$level->uuid]) }}"
                                                                            class="btn-action delete" title="Delete">
                                                                            <img style="width:30px" src="{{ asset('admin/images/icons/trash-2.svg') }}"
                                                                                alt="trash">
                                                                        </a>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                                <div class="mt-3">
                                                    {{ $levels->links() }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="tab-pane fade" id="pills-students_count" role="tabpanel"
                                aria-labelledby="pills-students_count-tab">
                                <form action="{{ route('ranking.store') }}" method="post"
                                    enctype="multipart/form-data">
                                    @csrf
                                    <input type="hidden" name="type" value="4">
                                    <div class="row mb-5">
                                        <div class="col-md-6">
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="input__group mb-25">
                                                        <label for="name">{{ __('Name') }}</label>
                                                        <input type="text" name="name" id="name"
                                                            placeholder="{{ __('Type name') }}"
                                                            value="{{ old('name') }}" required>
                                                        @if ($errors->has('name'))
                                                            <span class="text-danger"><i
                                                                    class="fas fa-exclamation-triangle"></i>
                                                                {{ $errors->first('name') }}</span>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <label for="">{{ __('Rule') }}</label>
                                                    <div class="input-group mb-25">
                                                        <span class="input-group-text">{{ __('From') }}</span>
                                                        <input type="number" name="from" step="any"
                                                            value="0" class="form-control">
                                                        <span class="input-group-text">{{ __('To') }}</span>
                                                        <input type="number" name="to" step="0"
                                                            value="0" class="form-control">
                                                        <span class="input-group-text">{{ getBadgeButtonName(RANKING_LEVEL_STUDENTS_COUNT) }}</span>
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <label for="">{{ __('Description') }}</label>
                                                    <div class="input-group mb-25">
                                                        <textarea name="description" placeholder="{{ __('Description') }}" class="form-control" rows="5"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-6">
                                                    <div class="input__group mb-25">
                                                        <label for="badge_image">{{ __('Badge Image') }}</label>
                                                        <div class="">
                                                            <div class="upload-img-box">
                                                                <img src="">
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
                                            <button type="submit" class="btn btn-purple">{{ __('Save') }}</button>
                                        </div>
                                    </div>
                                    <div class="row ">
                                        <div class="col-md-12 mt-5">
                                            <div class="customers__table">
                                                <table id="" class="row-border data-table-filter table-style">
                                                    <thead>
                                                        <tr>
                                                            <th>{{ __('Image') }}</th>
                                                            <th>{{ __('Name') }}</th>
                                                            <th>{{ __('From') }}</th>
                                                            <th>{{ __('To') }}</th>
                                                            <th>{{ __('Descripiton') }}</th>
                                                            <th>{{ __('Action') }}</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($levels->where('type', RANKING_LEVEL_STUDENTS_COUNT) as $level)
                                                            <tr class="removable-item">
                                                                <td>
                                                                    <div class="admin-dashboard-blog-list-img">
                                                                        <img
                                                                            style="width:30px" src="{{ getImageFile($level->image_path) }}">
                                                                    </div>
                                                                </td>
                                                                <td>{{ $level->name }}</td>
                                                                <td>
                                                                    {{ number_format($level->from) }}
                                                                </td>
                                                                <td>{{ number_format($level->to) }}</td>
                                                                <td>{{ $level->description }}</td>
                                                                <td>
                                                                    <div class="action__buttons">
                                                                        <a href="{{ route('ranking.edit', $level->uuid) }}"
                                                                            class=" btn-action mr-1 edit"
                                                                            data-toggle="tooltip" title="Edit">
                                                                            <img style="width:30px" src="{{ asset('admin/images/icons/edit-2.svg') }}"
                                                                                alt="edit">
                                                                        </a>
                                                                        <a href="javascript:void(0);"
                                                                            data-url="{{ route('ranking.delete', [$level->uuid]) }}"
                                                                            class="btn-action delete" title="Delete">
                                                                            <img style="width:30px" src="{{ asset('admin/images/icons/trash-2.svg') }}"
                                                                                alt="trash">
                                                                        </a>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                                <div class="mt-3">
                                                    {{ $levels->links() }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="tab-pane fade" id="pills-courses_sale_count" role="tabpanel"
                                aria-labelledby="pills-courses_sale_count-tab">
                                <form action="{{ route('ranking.store') }}" method="post"
                                    enctype="multipart/form-data">
                                    @csrf
                                    <input type="hidden" name="type" value="5">
                                    <div class="row mb-5">
                                        <div class="col-md-6">
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="input__group mb-25">
                                                        <label for="name">{{ __('Name') }}</label>
                                                        <input type="text" name="name" id="name"
                                                            placeholder="{{ __('Type name') }}"
                                                            value="{{ old('name') }}" required>
                                                        @if ($errors->has('name'))
                                                            <span class="text-danger"><i
                                                                    class="fas fa-exclamation-triangle"></i>
                                                                {{ $errors->first('name') }}</span>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <label for="">{{ __('Rule') }}</label>
                                                    <div class="input-group mb-25">
                                                        <span class="input-group-text">{{ __('From') }}</span>
                                                        <input type="number" name="from" step="any"
                                                            value="0" class="form-control">
                                                        <span class="input-group-text">{{ __('To') }}</span>
                                                        <input type="number" name="to" step="0"
                                                            value="0" class="form-control">
                                                        <span class="input-group-text">{{ getBadgeButtonName(RANKING_LEVEL_COURSES_SALE_COUNT) }}</span>
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <label for="">{{ __('Description') }}</label>
                                                    <div class="input-group mb-25">
                                                        <textarea name="description" placeholder="{{ __('Description') }}" class="form-control" rows="5"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-6">
                                                    <div class="input__group mb-25">
                                                        <label for="badge_image">{{ __('Badge Image') }}</label>
                                                        <div class="">
                                                            <div class="upload-img-box">
                                                                <img src="">
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
                                            <button type="submit" class="btn btn-purple">{{ __('Save') }}</button>
                                        </div>
                                    </div>
                                    <div class="row ">
                                        <div class="col-md-12 mt-5">
                                            <div class="customers__table">
                                                <table id="" class="row-border data-table-filter table-style">
                                                    <thead>
                                                        <tr>
                                                            <th>{{ __('Image') }}</th>
                                                            <th>{{ __('Name') }}</th>
                                                            <th>{{ __('From') }}</th>
                                                            <th>{{ __('To') }}</th>
                                                            <th>{{ __('Descripiton') }}</th>
                                                            <th>{{ __('Action') }}</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($levels->where('type', RANKING_LEVEL_COURSES_SALE_COUNT) as $level)
                                                            <tr class="removable-item">
                                                                <td>
                                                                    <div class="admin-dashboard-blog-list-img">
                                                                        <img
                                                                            style="width:30px" src="{{ getImageFile($level->image_path) }}">
                                                                    </div>
                                                                </td>
                                                                <td>{{ $level->name }}</td>
                                                                <td>
                                                                    {{ number_format($level->from) }}
                                                                </td>
                                                                <td>{{ number_format($level->to) }}</td>
                                                                <td>{{ $level->description }}</td>
                                                                <td>
                                                                    <div class="action__buttons">
                                                                        <a href="{{ route('ranking.edit', $level->uuid) }}"
                                                                            class=" btn-action mr-1 edit"
                                                                            data-toggle="tooltip" title="Edit">
                                                                            <img style="width:30px" src="{{ asset('admin/images/icons/edit-2.svg') }}"
                                                                                alt="edit">
                                                                        </a>
                                                                        <a href="javascript:void(0);"
                                                                            data-url="{{ route('ranking.delete', [$level->uuid]) }}"
                                                                            class="btn-action delete" title="Delete">
                                                                            <img style="width:30px" src="{{ asset('admin/images/icons/trash-2.svg') }}"
                                                                                alt="trash">
                                                                        </a>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                                <div class="mt-3">
                                                    {{ $levels->links() }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
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
    <link rel="stylesheet" href="{{ asset('admin/css/custom/image-preview.css') }}">
@endpush

@push('script')
    <script style="width:30px" src="{{ asset('admin/js/custom/image-preview.js') }}"></script>
@endpush
