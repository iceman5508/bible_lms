@extends('frontend.layouts.app')

@section('content')

<div class="bg-page">

<!-- Page Header Start -->
<header class="page-banner-header blank-page-banner-header gradient-bg position-relative">
    <div class="section-overlay">
        <div class="blank-page-banner-wrap">
            <div class="container">
                <div class="row">
                    <div class="col-12 col-sm-12 col-md-12">
                        <div class="page-banner-content text-center">
                            <h3 class="page-banner-heading color-heading pb-15">{{ __(@$pageTitle) }}</h3>

                            <!-- Breadcrumb Start-->
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb justify-content-center">
                                    <li class="breadcrumb-item font-14"><a href="{{ url('/') }}">{{ __('Home') }}</a></li>
                                    <li class="breadcrumb-item font-14 active" aria-current="page">{{ __(@$pageTitle) }}</li>
                                </ol>
                            </nav>
                            <!-- Breadcrumb End-->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
<!-- Page Header End -->

<!-- Wishlist Page Area Start -->
<section class="wishlist-page-area">
    <div class="container">
        <div class="row">
            <div class="table-responsive">
                <table class="table bg-white wishlist-table">
                    <thead>
                    <tr>
                        <th scope="col" class="color-gray font-15 font-medium">{{ __('Item') }}</th>
                        <th scope="col" class="color-gray font-15 font-medium">{{ __('Type') }}</th>
                        <th scope="col" class="color-gray font-15 font-medium">{{ __('Price') }}</th>
                        <th scope="col" class="color-gray font-15 font-medium">{{ __('Action') }}</th>
                        <th scope="col" class="color-gray font-15 font-medium">{{ __('Remove') }}</th>
                    </tr>
                    </thead>
                    <tbody>

                    @forelse($wishlists as $wishlist)
                    <tr>
                        <td class="wishlist-course-item">
                            <div class="card course-item wishlist-item border-0 d-flex align-items-center">
                                <div class="course-img-wrap flex-shrink-0 overflow-hidden">
                                    <?php
                                    $special = @$wishlist->course->specialPromotionTagCourse->specialPromotionTag->name;
                                    ?>
                                    @if(@$special)
                                        <span class="course-tag badge radius-3 font-12 font-medium position-absolute bg-orange">
                                            {{ @$special }}
                                        </span>
                                    @endif

                                    @if($wishlist->course_id)
                                        <a href="{{ route('course-details', @$wishlist->course->slug) }}"><img src="{{ getImageFile(@$wishlist->course->image_path) }}" alt="course" class="img-fluid"></a>
                                    @elseif($wishlist->bundle_id)
                                        <a href="{{ route('bundle-details', [@$wishlist->bundle->slug]) }}"><img src="{{ getImageFile(@$wishlist->bundle->image) }}" alt="bundle course" class="img-fluid"></a>
                                    @endif
                                </div>
                                <div class="card-body flex-grow-1">
                                    <h5 class="card-title course-title">
                                        @if($wishlist->course_id)
                                            <a href="{{ route('course-details', @$wishlist->course->slug) }}">{{ @$wishlist->course->title }}</a>
                                        @elseif($wishlist->bundle_id)
                                            <a href="{{ route('bundle-details', [@$wishlist->bundle->slug]) }}">{{ @$wishlist->bundle->name }}</a>
                                        @endif
                                    </h5>
                                    <p class="card-text instructor-name-certificate font-medium">
                                        @if($wishlist->course_id)
                                            {{ @$wishlist->course->instructor->name }}
                                            @if(get_instructor_ranking_level(@$wishlist->course->user->badges))
                                                | {{ get_instructor_ranking_level(@$wishlist->course->user->badges) }}
                                            @endif
                                        @elseif($wishlist->bundle_id)
                                            {{ @$wishlist->bundle->user->instructor->name }}
                                            @if(get_instructor_ranking_level(@$wishlist->bundle->user->badges))
                                                | {{ get_instructor_ranking_level(@$wishlist->bundle->user->badges) }}
                                            @endif
                                        @endif

                                    </p>
                                    @if($wishlist->course_id)
                                    <!--Start:: Rating --->
                                    <div class="course-item-bottom">
                                        <div class="course-rating d-flex align-items-center">
                                            <span class="font-medium font-14 me-2">{{ @$wishlist->course->average_rating }}</span>
                                            <ul class="rating-list d-flex align-items-center me-2">
                                                @if(@$wishlist->course->average_rating >= 1)
                                                    <li class="star-full"><span class="iconify" data-icon="bi:star-fill"></span></li>
                                                    @if(@$wishlist->course->average_rating > 1 && @$wishlist->course->average_rating < 2)
                                                        <li class="star-full"><span class="iconify" data-icon="bi:star-half"></span></li>
                                                        <li class=""><span class="iconify" data-icon="bi:star-fill"></span></li>
                                                        <li class=""><span class="iconify" data-icon="bi:star-fill"></span></li>
                                                        <li class=""><span class="iconify" data-icon="bi:star-fill"></span></li>
                                                    @elseif(@$wishlist->course->average_rating >= 2)
                                                        <li class="star-full"><span class="iconify" data-icon="bi:star-fill"></span></li>
                                                        @if(@$wishlist->course->average_rating > 2 && @$wishlist->course->average_rating < 3)
                                                            <li class="star-full"><span class="iconify" data-icon="bi:star-half"></span></li>
                                                            <li class=""><span class="iconify" data-icon="bi:star-fill"></span></li>
                                                            <li class=""><span class="iconify" data-icon="bi:star-fill"></span></li>
                                                        @elseif(@$wishlist->course->average_rating >= 3)
                                                            <li class="star-full"><span class="iconify" data-icon="bi:star-fill"></span></li>
                                                            @if(@$wishlist->course->average_rating > 3 && @$wishlist->course->average_rating < 4)
                                                                <li class="star-full"><span class="iconify" data-icon="bi:star-half"></span></li>
                                                                <li class=""><span class="iconify" data-icon="bi:star-fill"></span></li>
                                                            @elseif(@$wishlist->course->average_rating >= 4)
                                                                <li class="star-full"><span class="iconify" data-icon="bi:star-fill"></span></li>
                                                                @if(@$wishlist->course->average_rating > 4 && @$wishlist->course->average_rating < 5)
                                                                    <li class="star-full"><span class="iconify" data-icon="bi:star-half"></span></li>
                                                                @elseif(@$wishlist->course->average_rating >= 5)
                                                                    <li class="star-full"><span class="iconify" data-icon="bi:star-fill"></span></li>
                                                                @else
                                                                    <li class=""><span class="iconify" data-icon="bi:star-fill"></span></li>
                                                                @endif

                                                            @else
                                                                <li class=""><span class="iconify" data-icon="bi:star-fill"></span></li>
                                                                <li class=""><span class="iconify" data-icon="bi:star-fill"></span></li>
                                                            @endif
                                                        @else
                                                            <li class=""><span class="iconify" data-icon="bi:star-fill"></span></li>
                                                            <li class=""><span class="iconify" data-icon="bi:star-fill"></span></li>
                                                            <li class=""><span class="iconify" data-icon="bi:star-fill"></span></li>
                                                        @endif
                                                    @else
                                                        <li class=""><span class="iconify" data-icon="bi:star-fill"></span></li>
                                                        <li class=""><span class="iconify" data-icon="bi:star-fill"></span></li>
                                                        <li class=""><span class="iconify" data-icon="bi:star-fill"></span></li>
                                                        <li class=""><span class="iconify" data-icon="bi:star-fill"></span></li>
                                                    @endif
                                                @else
                                                    <li class=""><span class="iconify" data-icon="bi:star-fill"></span></li>
                                                    <li class=""><span class="iconify" data-icon="bi:star-fill"></span></li>
                                                    <li class=""><span class="iconify" data-icon="bi:star-fill"></span></li>
                                                    <li class=""><span class="iconify" data-icon="bi:star-fill"></span></li>
                                                    <li class=""><span class="iconify" data-icon="bi:star-fill"></span></li>
                                                @endif
                                            </ul>
                                            <span class="rating-count font-14">({{ @$wishlist->course->reviews->count() }})</span>
                                        </div>
                                    </div>
                                    <!--End:: Rating --->
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="wishlist-price font-15 color-heading">
                            @if($wishlist->course_id)
                                {{ __('Course') }}
                            @elseif($wishlist->bundle_id)
                                {{ __('Bundle Offer') }}
                            @endif
                        </td>
                        <td class="wishlist-price font-15 color-heading">
                            @if(get_currency_placement() == 'after')
                                @if($request->course_id)
                                    {{@$wishlist->course->price}} {{ get_currency_symbol() }}
                                @elseif($request->bundle_id)
                                    {{@$wishlist->bundle->price}} {{ get_currency_symbol() }}
                                @endif
                            @else
                                @if($wishlist->course_id)
                                    {{ get_currency_symbol() }} {{@$wishlist->course->price}}
                                @elseif($wishlist->bundle_id)
                                    {{@$wishlist->bundle->price}} {{ get_currency_symbol() }}
                                @endif
                            @endif
                        </td>
                        <td class="wishlist-add-to-cart-btn">
                            @if(!is_null($wishlist->course_id) && $wishlist->course->status != STATUS_UPCOMING_APPROVED)
                            <button class="theme-button theme-button1 theme-button3 font-13 addToCart" data-course_id="{{ $wishlist->course_id }}" data-bundle_id="{{ $wishlist->bundle_id }}"
                                    data-route="{{ route('student.addToCart') }}">
                                {{ __('Add to Cart') }}
                            </button>
                            @elseif(is_null($wishlist->course_id))
                                <button class="theme-button theme-button1 theme-button3 font-13 addToCart" data-course_id="{{ $wishlist->course_id }}" data-bundle_id="{{ $wishlist->bundle_id }}"
                                    data-route="{{ route('student.addToCart') }}">
                                    {{ __('Add to Cart') }}
                                </button>
                            @endif
                        </td>
                        <td class="wishlist-remove font-15">
                            <button>
                                <span data-formid="delete_row_form_{{$wishlist->id}}" class="iconify deleteItem" data-icon="fluent:delete-48-filled"></span>
                            </button>

                            <form action="{{ route('student.wishlistDelete', $wishlist->id) }}" method="post" id="delete_row_form_{{ $wishlist->id }}">
                                {{ method_field('DELETE') }}
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            </form>
                        </td>
                    </tr>
                    @empty
                        <tr><td colspan="5" class="text-center">{{ __('No Record Found') }}</td></tr>
                    @endforelse
                    </tbody>
                </table>
                <!-- Pagination Start -->
                <div class="col-12">
                @if(@$wishlists->hasPages())
                    {{ @$wishlists->links('frontend.paginate.paginate') }}
                @endif
                <!-- Pagination End -->
                </div>
            </div>

        </div>
    </div>
</section>
<!-- Wishlist Page Area End -->

</div>

@endsection

@push('script')
    <script src="{{ asset('frontend/assets/js/course/addToCart.js') }}"></script>
@endpush
