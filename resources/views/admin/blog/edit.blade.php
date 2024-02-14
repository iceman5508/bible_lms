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
                                <h2>{{__('All Blog')}}</h2>
                            </div>
                        </div>
                        <div class="breadcrumb__content__right">
                            <nav aria-label="breadcrumb">
                                <ul class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">{{__('Dashboard')}}</a></li>
                                    <li class="breadcrumb-item"><a href="{{route('blog.index')}}">{{__('All Blog')}}</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">{{__('Edit Blog')}}</li>
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
                            <h2>{{__('Edit Blog')}}</h2>
                        </div>
                        <form action="{{route('blog.update', [$blog->uuid])}}" method="post" class="form-horizontal" enctype="multipart/form-data">
                            @csrf

                            <div class="input__group mb-25">
                                <label>{{__('Title')}} <span class="text-danger">*</span></label>
                                <input type="text" name="title" value="{{$blog->title}}" placeholder="{{__('Title')}}" class="form-control slugable"  onkeyup="slugable()">
                                @if ($errors->has('title'))
                                    <span class="text-danger"><i class="fas fa-exclamation-triangle"></i> {{ $errors->first('title') }}</span>
                                @endif
                            </div>

                            <div class="input__group mb-25">
                                <label>{{__('Slug')}} <span class="text-danger">*</span></label>
                                <input type="text" name="slug" value="{{$blog->slug}}" placeholder="{{__('Slug')}}" class="form-control slug" onkeyup="getMyself()">
                                @if ($errors->has('slug'))
                                    <span class="text-danger"><i class="fas fa-exclamation-triangle"></i> {{ $errors->first('slug') }}</span>
                                @endif
                            </div>

                            <div class="input__group mb-25">
                                <label for="blog_category_id"> {{ __('Blog category') }} </label>
                                <select name="blog_category_id" id="blog_category_id">
                                    <option value="">--{{ __('Select Option') }}--</option>
                                    @foreach($blogCategories as $blogCategory)
                                        <option value="{{ $blogCategory->id }}" @if($blogCategory->id = $blog->blog_category_id) selected @endif>{{ $blogCategory->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="input__group mb-25">
                                <label>Status</label>
                                <select name="status" id="status">
                                    <option value="">--{{ __('Select Option') }}--</option>
                                    <option value="1" @if($blog->status == 1) selected @endif>{{ __('Published') }}</option>
                                    <option value="0" @if($blog->status != 1) selected @endif>{{ __('Unpublished') }}</option>
                                </select>
                            </div>
                            <div class="input__group mb-25">
                                <label for="tag_ids"> {{ __('Tag') }} </label>
                                <select name="tag_ids[]" multiple id="tag_ids" class="multiple-basic-single form-control">
                                    @foreach($tags as $tag)
                                        <option value="{{ $tag->id }}" {{ in_array($tag->id, @$blog->tags->pluck('tag_id')->toArray() ?? []) ? 'selected' : null }}>{{ $tag->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="input__group mb-25">
                                <label>{{__('Details')}} <span class="text-danger">*</span></label>
                                <textarea name="details" id="summernote">{{$blog->details}}</textarea>

                                @if ($errors->has('details'))
                                    <span class="text-danger"><i class="fas fa-exclamation-triangle"></i> {{ $errors->first('details') }}</span>
                                @endif

                            </div>

                            <div class="row">
                                <label>{{ __('OG Image') }}</label>
                                <div class="col-md-3">
                                    <div class="upload-img-box mb-25">
                                        @if($blog->image)
                                            <img src="{{asset($blog->image_path)}}" alt="img">
                                        @else
                                            <img src="" alt="No img">
                                        @endif
                                        <input type="file" name="image" id="image" accept="image/*" onchange="previewFile(this)">
                                        <div class="upload-img-box-icon">
                                            <i class="fa fa-camera"></i>
                                            <p class="m-0">{{__('Image')}}</p>
                                        </div>
                                    </div>
                                    @if ($errors->has('image'))
                                        <span class="text-danger"><i class="fas fa-exclamation-triangle"></i> {{ $errors->first('image') }}</span>
                                    @endif
                                    <p>{{ __('Accepted Files') }}: JPEG, JPG, PNG <br> {{ __('Recommend Size') }}: 870 x 500 (1MB)</p>
                                </div>
                            </div>

                            <div class="input__group mb-25">
                                <label>{{ __('Meta Title') }}</label>
                                <input type="text" name="meta_title" value="{{ old('meta_title', $blog->meta_title) }}" placeholder="{{ __('Meta title') }}" class="form-control">
                                @if ($errors->has('meta_title'))
                                    <span class="text-danger"><i class="fas fa-exclamation-triangle"></i> {{ $errors->first('meta_title') }}</span>
                                @endif
                            </div>
                           
                            <div class="input__group mb-25">
                                <label>{{ __('Meta Description') }}</label>
                                <input type="text" name="meta_description" value="{{ old('meta_description', $blog->meta_description) }}" placeholder="{{ __('meta description') }}" class="form-control">
                                @if ($errors->has('meta_description'))
                                    <span class="text-danger"><i class="fas fa-exclamation-triangle"></i> {{ $errors->first('meta_description') }}</span>
                                @endif
                            </div>

                            <div class="input__group mb-25">
                                <label>{{ __('Meta Keywords') }}</label>
                                <input type="text" name="meta_keywords" value="{{ old('meta_keywords',  $blog->meta_keywords) }}" placeholder="{{ __('meta keywords') }}" class="form-control">
                                @if ($errors->has('meta_keywords'))
                                    <span class="text-danger"><i class="fas fa-exclamation-triangle"></i> {{ $errors->first('meta_keywords') }}</span>
                                @endif
                            </div>

                            <div class="input__group mb-25">
                                <label>{{ __('OG Image') }}</label>
                                <div class="upload-img-box">
                                    @if($blog->og_image != NULL && $blog->og_image != '')
                                        <img src="{{getImageFile($blog->og_image)}}">
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

                            <div class="row mb-3">
                                <div class="col-md-12 text-right">
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

    <!-- Summernote CSS - CDN Link -->
    <link href="{{ asset('common/css/summernote/summernote.min.css') }}" rel="stylesheet">
    <link href="{{ asset('common/css/summernote/summernote-lite.min.css') }}" rel="stylesheet">
    <!-- //Summernote CSS - CDN Link -->
@endpush

@push('script')
    <script src="{{asset('admin/js/custom/image-preview.js')}}"></script>
    <script src="{{asset('admin/js/custom/slug.js')}}"></script>
    <script src="{{asset('admin/js/custom/form-editor.js')}}"></script>

    <!-- Summernote JS - CDN Link -->
    <script src="{{ asset('common/js/summernote/summernote-lite.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $("#summernote").summernote({dialogsInBody: true});
            $('.dropdown-toggle').dropdown();
        });
    </script>
    <!-- //Summernote JS - CDN Link -->
@endpush
