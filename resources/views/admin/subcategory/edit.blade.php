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
                                <h2>{{__('Edit Subcategory')}}</h2>
                            </div>
                        </div>
                        <div class="breadcrumb__content__right">
                            <nav aria-label="breadcrumb">
                                <ul class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">{{__('Dashboard')}}</a></li>
                                    <li class="breadcrumb-item"><a href="{{route('subcategory.index')}}">{{__('Subcategories')}}</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">{{__('Edit Subcategory')}}</li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-vertical__item bg-style">
                        <div class="item-top mb-30">
                            <h2>{{__('Edit Subcategory')}}</h2>
                        </div>
                        <form action="{{route('subcategory.update', [$subcategory->uuid])}}" method="post" enctype="multipart/form-data">
                            @csrf

                            <div class="input__group mb-25">
                                <label for="category_id"> {{__('Category')}} </label>
                                <select name="category_id" id="category_id" class="form-control">
                                    <option value="">{{ __('Select Category') }}</option>
                                    @foreach($categories as $category)
                                        <option value="{{$category->id}}" {{$subcategory->category_id == $category->id ? 'selected' : '' }}>{{$category->name}}</option>
                                    @endforeach
                                </select>
                                @if ($errors->has('category_id'))
                                    <span class="text-danger"><i class="fas fa-exclamation-triangle"></i> {{ $errors->first('category_id') }}</span>
                                @endif
                            </div>


                            <div class="input__group mb-25">
                                <label for="name"> {{__('Name')}} </label>
                                <div>
                                    <input type="text" name="name" id="name" value="{{$subcategory->name}}" class="form-control" placeholder="{{__('Name')}} ">
                                    @if ($errors->has('name'))
                                        <span class="text-danger"><i class="fas fa-exclamation-triangle"></i> {{ $errors->first('name') }}</span>
                                    @endif
                                </div>
                            </div>

                            <div class="input__group mb-25">
                                <label>{{ __('Meta Title') }}</label>
                                <input type="text" name="meta_title" value="{{ old('meta_title', $subcategory->meta_title) }}" placeholder="{{ __('Meta title') }}" class="form-control">
                                @if ($errors->has('meta_title'))
                                    <span class="text-danger"><i class="fas fa-exclamation-triangle"></i> {{ $errors->first('meta_title') }}</span>
                                @endif
                            </div>
                           
                            <div class="input__group mb-25">
                                <label>{{ __('Meta Description') }}</label>
                                <input type="text" name="meta_description" value="{{ old('meta_description', $subcategory->meta_description) }}" placeholder="{{ __('meta description') }}" class="form-control">
                                @if ($errors->has('meta_description'))
                                    <span class="text-danger"><i class="fas fa-exclamation-triangle"></i> {{ $errors->first('meta_description') }}</span>
                                @endif
                            </div>

                            <div class="input__group mb-25">
                                <label>{{ __('Meta Keywords') }}</label>
                                <input type="text" name="meta_keywords" value="{{ old('meta_keywords',  $subcategory->meta_keywords) }}" placeholder="{{ __('meta keywords') }}" class="form-control">
                                @if ($errors->has('meta_keywords'))
                                    <span class="text-danger"><i class="fas fa-exclamation-triangle"></i> {{ $errors->first('meta_keywords') }}</span>
                                @endif
                            </div>

                            <div class="input__group mb-25">
                                <label>{{ __('OG Image') }}</label>
                                <div class="upload-img-box">
                                    @if($subcategory->og_image != NULL && $subcategory->og_image != '')
                                        <img src="{{getImageFile($subcategory->og_image)}}">
                                    @else
                                        <img src="">
                                    @endif
                                    <input type="file" name="og_image" id="og_image" accept="image/*" onchange="previewFile(this)">
                                    <div class="upload-img-box-icon">
                                        <i class="fa fa-camera"></i>
                                        <p class="m-0">{{__('OG Image')}}</p>
                                    </div>
                                </div>
                                @if ($errors->has('og_image'))
                                    <span class="text-danger"><i class="fas fa-exclamation-triangle"></i> {{ $errors->first('og_image') }}</span>
                                @endif
                                <p><span class="text-black">{{ __('Accepted Files') }}:</span> PNG, JPG <br> <span class="text-black">{{ __('Recommend Size') }}:</span> 1200 x 627</p>
                            </div>

                            <div class="input__group">
                                <div>
                                    @updateButton
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
    <link rel="stylesheet" href="{{asset('admin/css/custom/image-preview.css')}}">
@endpush

@push('script')
    <script src="{{asset('admin/js/custom/image-preview.js')}}"></script>
@endpush

