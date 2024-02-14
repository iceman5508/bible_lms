@forelse($bundles as $bundle)
    <!-- Course item start -->
    <div class="col-sm-6 col-md-6 col-lg-6 col-xl-4">
        <div class="card course-item border-0 radius-3 bg-white">
            <div class="course-img-wrap overflow-hidden">
                <a href="{{ route('bundle-details', [$bundle->slug]) }}"><img src="{{ getImageFile($bundle->image) }}" alt="course" class="img-fluid"></a>
                <div class="course-item-hover-btns position-absolute">
                                                <span class="course-item-btn addToWishlist" data-bundle_id="{{ $bundle->id }}" data-route="{{ route('student.addToWishlist') }}"
                                                      title="Add to Wishlist">
                                                    <i data-feather="heart"></i>
                                                </span>
                    <span class="course-item-btn addToCart" data-bundle_id="{{ $bundle->id }}" data-route="{{ route('student.addToCart') }}"
                          title="Add to Cart">
                                                    <i data-feather="shopping-bag"></i>
                                                </span>
                </div>
            </div>
            @php 
                $relation = getUserRoleRelation($bundle->user);
            @endphp
            <div class="card-body">
                <h5 class="card-title course-title"><a href="{{ route('bundle-details', [$bundle->slug]) }}">{{ Str::limit(__($bundle->name), 40) }}</a></h5>
                <p class="card-text instructor-name-certificate font-medium font-12">
                    {{ @$bundle->user->$relation->name }}
                    @if(get_instructor_ranking_level(@$bundle->user->badges))
                        | {{ get_instructor_ranking_level(@$bundle->user->badges) }}
                    @endif
                </p>
                <div class="course-item-bottom">
                    <div class="instructor-bottom-item font-14 font-semi-bold mb-15">Courses: <span class="color-hover">{{ @$bundle->bundleCourses->count() }}</span></div>
                    <div class="instructor-bottom-item font-14 font-semi-bold">Price: <span class="color-hover">
                                                        @if(get_currency_placement() == 'after')
                                {{$bundle->price}} {{ get_currency_symbol() }}
                            @else
                                {{ get_currency_symbol() }} {{$bundle->price}}
                            @endif
                                                    </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Course item end -->
@empty
    <div class="no-course-found text-center">
        <img src="{{ asset('frontend/assets/img/empty-data-img.png') }}" alt="img" class="img-fluid">
        <h5 class="mt-3">{{ __('Bundles Not Found') }}</h5>
    </div>
@endforelse
<!-- Pagination Start -->
<div class="col-12">
    @if(@$bundles->hasPages())
        {{ @$bundles->links('frontend.paginate.paginate') }}
    @endif
</div>
<!-- Pagination End -->
