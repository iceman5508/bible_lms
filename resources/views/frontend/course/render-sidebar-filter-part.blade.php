<div class="col-md-4 col-lg-3 col-xl-3 coursesLeftSidebar">
    <div class="courses-sidebar-area bg-light">

        <div class="accordion" id="accordionPanelsStayOpenExample">
            <div class="accordion-item course-sidebar-accordion-item">
                <h2 class="accordion-header course-sidebar-title" id="panelsStayOpen-headingOne">
                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseOne" aria-expanded="true" aria-controls="panelsStayOpen-collapseOne">
                        {{ __('Categories') }}
                    </button>
                </h2>
                <div id="panelsStayOpen-collapseOne" class="accordion-collapse collapse show" aria-labelledby="panelsStayOpen-headingOne">
                    <div class="accordion-body">
                        <div class="accordion inner-accordion" id="accordionExample2">
                            @foreach($categories as $key => $category)
                                <div class="accordion-item sidebar-inner-accordion-item">
                                    <h2 class="accordion-header sidebar-inner-title" id="innerheading{{ $key }}">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#innercollapse{{ $key }}" aria-expanded="false" aria-controls="innercollapse{{ $key }}">
                                            {{ $category->name }}
                                        </button>
                                    </h2>
                                    <div id="innercollapse{{ $key }}" class="accordion-collapse collapse" aria-labelledby="innerheading{{ $key }}" data-bs-parent="#accordionExample2">
                                        <div class="accordion-body inner-accordion-body">

                                            @forelse($category->subcategories as $subCategoryKey => $subcategoryItem)
                                                <div class="sidebar-radio-item">
                                                    <div class="form-check">
                                                        <input class="form-check-input filterSubCategory" type="checkbox" name="filterSubCategory" id="exampleRadiosSubCategory{{ $category->id . $subCategoryKey }}" value="{{ $subcategoryItem->id }}">
                                                        <label class="form-check-label" for="exampleRadiosSubCategory{{ $category->id . $subCategoryKey }}">
                                                            {{ $subcategoryItem->name }}
                                                        </label>
                                                    </div>
                                                    <div class="radio-right-text"></div>
                                                </div>
                                            @empty
                                                <div class="row">
                                                    <small>{{ __('No Subcategory Found') }}!</small>
                                                </div>
                                            @endforelse

                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <div class="accordion-item course-sidebar-accordion-item">
                <h2 class="accordion-header course-sidebar-title" id="panelsStayOpen-headingTwo">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseTwo" aria-expanded="false" aria-controls="panelsStayOpen-collapseTwo">
                        {{ __('Course Level') }}
                    </button>
                </h2>
                <div id="panelsStayOpen-collapseTwo" class="accordion-collapse collapse show" aria-labelledby="panelsStayOpen-headingTwo">
                    <div class="accordion-body">
                        @foreach(@$difficulty_levels as $key => $level)
                            <div class="sidebar-radio-item">
                                <div class="form-check">
                                    <input class="form-check-input filterDifficultyLevel" type="checkbox" name="filterDifficultyLevel" id="exampleRadiosDifficulty{{ $key }}" value="{{ $level->id }}">
                                    <label class="form-check-label" for="exampleRadiosDifficulty{{ $key }}">
                                        {{ __($level->name) }}
                                    </label>
                                </div>
                                <div class="radio-right-text"></div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="accordion-item course-sidebar-accordion-item">
                <h2 class="accordion-header course-sidebar-title" id="panelsStayOpen-headingThree">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseThree" aria-expanded="false" aria-controls="panelsStayOpen-collapseThree">
                        {{ __('Rating') }}
                    </button>
                </h2>
                <div id="panelsStayOpen-collapseThree" class="accordion-collapse collapse show" aria-labelledby="panelsStayOpen-headingThree">
                    <div class="accordion-body">

                        <div class="sidebar-radio-item">
                            <div class="form-check">
                                <input class="form-check-input filterRating" type="checkbox" name="filterRating" id="exampleRadios41" value="5">
                                <label class="form-check-label" for="exampleRadios41">
                                    <span class="iconify" data-icon="bi:star-fill"></span>{{ __('5 star') }}
                                </label>
                            </div>
                            <div class="radio-right-text"></div>
                        </div>

                        <div class="sidebar-radio-item">
                            <div class="form-check">
                                <input class="form-check-input filterRating" type="checkbox" name="filterRating" id="exampleRadios42" value="4">
                                <label class="form-check-label" for="exampleRadios42">
                                    <span class="iconify" data-icon="bi:star-fill"></span>{{ __('4 star or above') }}
                                </label>
                            </div>
                            <div class="radio-right-text"></div>
                        </div>

                        <div class="sidebar-radio-item">
                            <div class="form-check">
                                <input class="form-check-input filterRating" type="checkbox" name="filterRating" id="exampleRadios43" value="3">
                                <label class="form-check-label" for="exampleRadios43">
                                    <span class="iconify" data-icon="bi:star-fill"></span>{{ __('3 star or above') }}
                                </label>
                            </div>
                            <div class="radio-right-text"></div>
                        </div>

                        <div class="sidebar-radio-item">
                            <div class="form-check">
                                <input class="form-check-input filterRating" type="checkbox" name="filterRating" id="exampleRadios44" value="2">
                                <label class="form-check-label" for="exampleRadios44">
                                    <span class="iconify" data-icon="bi:star-fill"></span>{{ __('2 star or above') }}
                                </label>
                            </div>
                            <div class="radio-right-text"></div>
                        </div>

                        <div class="sidebar-radio-item">
                            <div class="form-check">
                                <input class="form-check-input filterRating" type="checkbox" name="filterRating" id="exampleRadios45" value="1">
                                <label class="form-check-label" for="exampleRadios45">
                                    <span class="iconify" data-icon="bi:star-fill"></span>{{ __('1 star or above') }}
                                </label>
                            </div>
                            <div class="radio-right-text"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="accordion-item course-sidebar-accordion-item">
                <h2 class="accordion-header course-sidebar-title" id="panelsStayOpen-headingFour">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseFour" aria-expanded="false" aria-controls="panelsStayOpen-collapseFour">
                        {{ __('Price') }}
                    </button>
                </h2>
                <div id="panelsStayOpen-collapseFour" class="accordion-collapse collapse show" aria-labelledby="panelsStayOpen-headingFour">
                    <div class="accordion-body">
                        <div class="range-value-box">
                            <div class="range-value-wrap"><label for="min_price">{{ get_option('app_currency') }}{{ __('Min') }}:</label>
                                <input type="number" min=0 max="9900" value="0" id="" class="price-range-field min_price" />
                            </div>
                            <div class="range-value-wrap"><label for="max_price">{{ get_option('app_currency') }}{{ __('Max') }}:</label>
                                <input type="number" min=0 max="10000" value="{{ $highest_price }}" id="" class="price-range-field max_price" />
                            </div>
                            <div class="range-value-wrap-go-btn d-flex align-items-center">
                                <button type="button" class="filterPrice"><i class="fas fa-arrow-right"></i></button>
                            </div>
                        </div>

                        <div class="sidebar-radio-item">
                            <div class="form-check">
                                <input class="form-check-input filterLearnerAccessibility" type="checkbox" name="filterLearnerAccessibility" id="exampleRadiosAccessibility32" value="free">
                                <label class="form-check-label" for="exampleRadiosAccessibility32">
                                    {{ __('Free') }}
                                </label>
                            </div>
                        </div>
                        <div class="sidebar-radio-item">
                            <div class="form-check">
                                <input class="form-check-input filterLearnerAccessibility" type="checkbox" name="filterLearnerAccessibility" id="exampleRadiosAccessibility33" value="paid">
                                <label class="form-check-label" for="exampleRadiosAccessibility33">
                                    {{ __('Paid') }}
                                </label>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <div class="accordion-item course-sidebar-accordion-item">
                <h2 class="accordion-header course-sidebar-title" id="panelsStayOpen-headingFive">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseFive" aria-expanded="false" aria-controls="panelsStayOpen-collapseFive">
                        {{ __('Duration') }}
                    </button>
                </h2>
                <div id="panelsStayOpen-collapseFive" class="accordion-collapse collapse show" aria-labelledby="panelsStayOpen-headingFive">
                    <div class="accordion-body">

                        <div class="sidebar-radio-item">
                            <div class="form-check">
                                <input class="form-check-input filterDuration" type="checkbox" name="filterDuration" id="exampleRadiosDuration34" value="1">
                                <label class="form-check-label" for="exampleRadiosDuration34">{{ __('Less Than 24 Hours') }}</label>
                            </div>
                        </div>
                        <div class="sidebar-radio-item">
                            <div class="form-check">
                                <input class="form-check-input filterDuration" type="checkbox" name="filterDuration" id="exampleRadiosDuration35" value="2">
                                <label class="form-check-label" for="exampleRadiosDuration35">{{ __('24 to 36 Hours') }}</label>
                            </div>
                        </div>

                        <div class="sidebar-radio-item">
                            <div class="form-check">
                                <input class="form-check-input filterDuration" type="checkbox" name="filterDuration" id="exampleRadiosDuration36" value="3">
                                <label class="form-check-label" for="exampleRadiosDuration36">{{ __('36 to 72 Hours') }}</label>
                            </div>
                        </div>
                        <div class="sidebar-radio-item">
                            <div class="form-check">
                                <input class="form-check-input filterDuration" type="checkbox" name="filterDuration" id="exampleRadiosDuration37" value="4">
                                <label class="form-check-label" for="exampleRadiosDuration37">{{ __('Above 72 Hours') }}</label>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

        </div>

    </div>
</div>
